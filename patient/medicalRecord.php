<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient.css">
    <title>UTeM Clinic Queue System</title>
</head>

<body>

    <?php include('inc/patient_header.php'); ?>

    <section>
        <h2>Medical Record</h2>
        <p>View your visit history, doctor notes, and prescriptions.</p>

        <article>
            <h2>12/6/2026</h2>

            <div class="medicalRecordTables">
                <table>
                    <tr>
                        <th>Time Slot</th>
                        <th>Doctor</th>
                        <th>Appointment Type</th>
                    </tr>
                    <tr>
                        <td>11:00 AM - 12:00 PM</td>
                        <td>Dr Anis</td>
                        <td>Same-Day Consultation</td>
                    </tr>
                </table>

                <table>
                    <tr>
                        <th>Reason for Visit</th>
                        <td>Fever, sore throat, and body ache for three days.</td>
                    </tr>
                    <tr>
                        <th>Clinical Findings</th>
                        <td>Patient has mild fever, throat redness, nasal congestion, and general body weakness. No breathing difficulty or chest pain reported.</td>
                    </tr>
                    <tr>
                        <th>Diagnosis</th>
                        <td>Acute upper respiratory tract infection.</td>
                    </tr>
                    <tr>
                        <th>Treatment Plan</th>
                        <td>Advise rest, increase fluid intake, and monitor temperature. Patient should return to the clinic if fever continues for more than five days or symptoms worsen.</td>
                    </tr>
                    <tr>
                        <th>Prescription</th>
                        <td>Paracetamol 500mg, take one tablet every 6 hours when needed for fever or body ache.</td>
                    </tr>
                </table>
            </div>
        </article>
    </section>

    <?php include('inc/patient_footer.php'); ?>
<script src="patient.js"></script>
</body>
</html>