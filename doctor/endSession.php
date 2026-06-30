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
    if (!is_array($prescription)) {
        $prescription = [];
    }

    $conn->begin_transaction();

    try {

        /* -----------------------------
           Consultation
        ----------------------------- */

        $sql = "
        INSERT INTO consultation
        (
            queueID,
            doctorUserID,
            startTime,
            endTime,
            reasonForVisit,
            clinicalFindings,
            diagnosis,
            treatmentPlan
        )
        VALUES
        (
            ?,
            ?,
            NOW(),
            NOW(),
            ?,
            ?,
            ?,
            ?
        )
        ";

        $stmt = $conn->prepare($sql);

        echo json_encode([
            "success" => false,
            "debug" => [
                "doctorUserID" => $doctorUserID,
                "queueID" => $queueID,
                "reason" => $reason,
                "findings" => $findings,
                "diagnosis" => $diagnosis,
                "treatment" => $treatment
            ]
        ]);
        exit;

        $stmt->bind_param(
            "iissss",
            $queueID,
            $doctorUserID,
            $reason,
            $findings,
            $diagnosis,
            $treatment
        );

        var_dump($doctorUserID);
        var_dump(gettype($doctorUserID));
        exit;

        if (!$stmt->execute()) {

            echo json_encode([
                "success" => false,
                "sql_error" => $stmt->error,
                "sql_errno" => $stmt->errno,
                "doctorUserID" => $doctorUserID,
                "queueID" => $queueID
            ]);

            exit;
        }

        $consultationID = $conn->insert_id;

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
                throw new Exception($stmt->error);
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

                if (!$stmt->execute()) {
                    throw new Exception($stmt->error);
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