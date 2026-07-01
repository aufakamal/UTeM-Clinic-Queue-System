<?php

session_start();
include("../dbconnect.php");

header("Content-Type: application/json");

try {

    // FIX 1: prevent double decode + ensure safe JSON
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        throw new Exception("Invalid JSON payload");
    }

    $doctorUserID = $_SESSION["userID"] ?? null;

    if (!$doctorUserID) {
        echo json_encode([
            "success" => false,
            "message" => "Session userID not found",
            "session" => $_SESSION
        ]);
        exit;
    }

    if (!$doctorUserID) {
        throw new Exception("Session expired. Please login again.");
    }

    $queueID = $data["queueID"];
    $reason = $data["reason"];
    $findings = $data["findings"];
    $diagnosis = $data["diagnosis"];
    $treatment = $data["treatment"];

    // FIX 2: SAFE prescription handling
    $prescription = $data["prescription"] ?? [];
        if (!empty($prescription)) {
        // save prescription
    }

    $conn->begin_transaction();

    try {

    /* -----------------------------
    Update Consultation
    ----------------------------- */

    $sql = "
    UPDATE consultation
    SET
        endTime = NOW(),
        reasonForVisit = ?,
        clinicalFindings = ?,
        diagnosis = ?,
        treatmentPlan = ?
    WHERE queueID = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $reason,
        $findings,
        $diagnosis,
        $treatment,
        $queueID
    );

    if (!$stmt->execute()) {
        throw new Exception($stmt->error);
    }

    /* -----------------------------
    Get Consultation ID
    ----------------------------- */

    $sql = "
    SELECT consultationID
    FROM consultation
    WHERE queueID = ?
    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i", $queueID);

    $stmt->execute();

    $result = $stmt->get_result();

    $row = $result->fetch_assoc();

    if (!$row) {
        throw new Exception("Consultation record not found.");
    }

    $consultationID = $row["consultationID"];
    
        /* -----------------------------
           Prescription (SAFE OPTIONAL)
        ----------------------------- */

        $prescriptionID = null;

        if (!empty($prescription)) {

            $sql = "
            INSERT INTO prescription
            (
                consultationID,
                prescriptionDate,
                status,
                note
            )
            VALUES
            (
                ?,
                NOW(),
                'Pending',
                ''
            )
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $consultationID);

            if (!$stmt->execute()) {

                echo json_encode([
                    "success" => false,
                    "step" => "prescription",
                    "error" => $stmt->error,
                    "errno" => $stmt->errno,
                    "consultationID" => $consultationID
                ]);

                exit;
            }

            $prescriptionID = $conn->insert_id;
        }

        /* -----------------------------
           Prescription Items
        ----------------------------- */

        if (!empty($prescriptionID)) {

            foreach ($prescription as $item) {

                $medicineID = $item["medicineID"];
                $dosage = $item["dosage"];
                $frequency = $item["frequency"];
                $duration = $item["duration"];
                $quantity = $item["quantity"];
                $instruction = $item["instruction"];

                $sql = "
                INSERT INTO prescription_item
                (
                    prescriptionID,
                    medicineID,
                    quantity,
                    dosage,
                    frequency,
                    duration,
                    instructions
                )
                VALUES
                (
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?,
                    ?
                )
                ";

                $stmt = $conn->prepare($sql);

                $stmt->bind_param(
                    "iiissss",
                    $prescriptionID,
                    $medicineID,
                    $quantity,
                    $dosage,
                    $frequency,
                    $duration,
                    $instruction
                );

                /*if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
                }*/

                if (!$stmt->execute()) {

                echo json_encode([
                    "success" => false,
                    "step" => "prescription_item",
                    "error" => $stmt->error,
                    "errno" => $stmt->errno,
                    "medicineID" => $medicineID,
                    "quantity" => $quantity,
                    "dosage" => $dosage,
                    "frequency" => $frequency,
                    "duration" => $duration,
                    "instruction" => $instruction
                ]);

                exit;
            }



                /* -----------------------------
                   Deduct Stock
                ----------------------------- */

                $sql = "SELECT stockQuantity
                        FROM medicine
                        WHERE medicineID=?";

                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i",$medicineID);
                $stmt->execute();

                $currentStock = $stmt->get_result()->fetch_assoc()['stockQuantity'];

                if($currentStock < $quantity){
                    throw new Exception("Not enough stock.");
                }
            }
        }

        /* -----------------------------
           Complete Queue
        ----------------------------- */

        $sql = "
        UPDATE queue
        SET queueStatus='Completed'
        WHERE queueID=?
        ";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param("i", $queueID);

        if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

        $conn->commit();

        echo json_encode([
            "success" => true
        ]);

    } catch (Exception $e) {
        $conn->rollback();
        throw $e;
    }

} catch (Exception $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);
}

?>