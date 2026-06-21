<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Workspace</title>
    <link rel="stylesheet" href="doctor.css">
</head>

<body>

    <?php include('inc/doctor_header.php'); ?>

    <section class="doctorWorkspace">

        <!-- LEFT COLUMN -->
        <div class="leftColumn">

            <article class="doctorCard">
                <h2>Current Consultations</h2>

                <div class="currentconstBox">
                    <div>
                        <h3 style="color: #0369A1;">A103</h3>
                        <p style="font-weight: 600; font-size: 16px;">
                            Siti Sarah bt Roslan
                        </p>
                        <p class="appointmentLabel">
                            Type of Appointment: Medical Checkup
                        </p>
                    </div>

                    <button class="startBtn" data-queue="A103">
                        Start Session
                    </button>
                </div>

            </article>

            <article class="doctorCard">
                <h2>Search Patient</h2>

                <input 
                    class="searchInput" 
                    type="text" 
                    placeholder="Search by name or queue ID"
                >

                <p class="recentText">
                    Recent Search:<br>
                    • Amir bin Amar<br>
                    • D514698002
                </p>
            </article>

        </div>

        <!-- RIGHT WORKSPACE -->
        <article class="workspaceCard" id="workspaceCard">

            <!-- CONSULTATION VIEW -->
            <div id="defaultWorkspace">

                <p id="placeholderText">
                    Please select a patient to start consultation.
                </p>

                <div id="patientRecordDisplay" style="display:none;">

                    <h2 class="recordTitle">CURRENT CONSULTATIONS</h2>

                    <h3 class="recordSubTitle">PATIENT RECORD</h3>

                    <div class="patientInfo">

                        <p>
                            <strong>Full name:</strong>
                            <span id="pName"></span>
                        </p>

                        <p>
                            <strong>Gender:</strong>
                            <span id="pGender"></span>
                        </p>

                        <p>
                            <strong>ID:</strong>
                            <span id="pID"></span>
                        </p>

                        <p>
                            <strong>Blood type:</strong>
                            <span id="pBlood"></span>
                        </p>

                    </div>

                    <!-- MINI TAB BAR -->
                    <div class="miniTabBar">
                        <button class="miniTab active" onclick="switchMiniTab(this, 'overview')">
                            Overview
                        </button>

                        <button class="miniTab" onclick="switchMiniTab(this, 'visits')">
                            Visits
                        </button>

                        <button class="miniTab" onclick="switchMiniTab(this, 'diagnosis')">
                            Diagnosis
                        </button>

                        <button class="miniTab" onclick="switchMiniTab(this, 'prescription')">
                            Prescription
                        </button>
                    </div>

                    <!-- OVERVIEW SECTION -->
                    <div class="overviewSection">

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Allergies</h3>

                                <button 
                                    type="button"
                                    class="addSmallBtn" 
                                    onclick="addItem('pAllergyList')"
                                >
                                    + Add Allergy
                                </button>
                            </div>

                            <div id="pAllergyList" class="editableList"></div>
                        </div>

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Chronic Diseases</h3>

                                <button 
                                    type="button"
                                    class="addSmallBtn" 
                                    onclick="addItem('pChronicList')"
                                >
                                    + Add Condition
                                </button>
                            </div>

                            <div id="pChronicList" class="editableList"></div>
                        </div>

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Current Medication</h3>

                                <button 
                                    type="button"
                                    class="addSmallBtn" 
                                    onclick="addItem('pMedList')"
                                >
                                    + Add Medication
                                </button>
                            </div>

                            <div id="pMedList" class="editableList"></div>
                        </div>

                    </div>

                    <!-- VISITS SECTION -->
                    <div id="visitsSection" class="tabSection" style="display: none;">

                <!-- CARD 1 -->
                <div class="visitCard">

                    <h2 class="visitTitle">Medical Record</h2>

                    <table class="visitTable">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <th>Doctor</th>
                                <th>Appointment Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>11:00 AM - 12:00 PM</td>
                                <td>Dr Anis</td>
                                <td>Same-Day Consultation</td>
                                <td><span class="status done">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="visitDetail">
                        <div><b>Reason for Visit:</b> Fever</div>
                        <div><b>Clinical Findings:</b> Viral infection</div>
                        <div><b>Diagnosis:</b> Influenza</div>
                        <div><b>Treatment Plan:</b> Rest + fluids</div>
                        <div><b>Prescription:</b> Paracetamol</div>
                    </div>

                </div>

                <!-- CARD 2 -->
                <div class="visitCard">

                    <h2 class="visitTitle">Medical Record</h2>

                    <table class="visitTable">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <th>Doctor</th>
                                <th>Appointment Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>2:00 PM - 4:00 PM</td>
                                <td>Dr Siva</td>
                                <td>Follow Up</td>
                                <td><span class="status done">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="visitDetail">
                        <div><b>Reason for Visit:</b> Asthma review</div>
                        <div><b>Clinical Findings:</b> Stable condition</div>
                        <div><b>Diagnosis:</b> Chronic Asthma</div>
                        <div><b>Treatment Plan:</b> Continue inhaler</div>
                        <div><b>Prescription:</b> Ventolin</div>
                    </div>

                </div>

                <!-- CARD 3 -->
                <div class="visitCard">

                    <h2 class="visitTitle">Medical Record</h2>

                    <table class="visitTable">
                        <thead>
                            <tr>
                                <th>Time Slot</th>
                                <th>Doctor</th>
                                <th>Appointment Type</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>9:00 AM - 10:00 AM</td>
                                <td>Dr Ahmad</td>
                                <td>Medical Checkup</td>
                                <td><span class="status done">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="visitDetail">
                        <div><b>Reason for Visit:</b> Headache</div>
                        <div><b>Clinical Findings:</b> Mild dehydration</div>
                        <div><b>Diagnosis:</b> Tension headache</div>
                        <div><b>Treatment Plan:</b> Hydration + rest</div>
                        <div><b>Prescription:</b> Ibuprofen</div>
                    </div>

                </div>

</div>

                    <!-- DIAGNOSIS SECTION -->
                    <div id="diagnosisSection" class="tabSection" style="display: none;">

                        <div class="diagnosisCleanBox">

                            <div class="diagnosisCleanHeader">
                                <h3>Diagnosis</h3>

                                <button type="button" class="addSmallBtn" onclick="addFindings()">
                                    + Add Findings
                                </button>
                            </div>

                            <div class="diagnosisSectionBlock">
                                <h4>Reason for Visit</h4>
                                <p id="reasonVisitText">
                                    Patient reports fever and headache.
                                </p>
                            </div>

                            <div class="diagnosisSectionBlock">
                                <h4>Clinical Findings</h4>

                                <div class="findingsGrid">
                                    <div class="findingBox">
                                        <strong>Temperature</strong>
                                        <span>38.5 Celsius</span>
                                    </div>

                                    <div class="findingBox">
                                        <strong>Blood Pressure</strong>
                                        <span>120/80</span>
                                    </div>

                                    <div class="findingBox">
                                        <strong>Heart Rate</strong>
                                        <span>92 bpm</span>
                                    </div>
                                </div>

                                <div class="findingTextBox">
                                    <strong>Physical Observation</strong>
                                    <p>Patient appears fatigued.</p>
                                </div>

                                <div class="findingTextBox">
                                    <strong>Test Results</strong>
                                    <p>
                                        Rapid COVID Test: Negative<br>
                                        Blood Glucose: 6.1 mmol/L
                                    </p>
                                </div>

                                <div id="extraFindingsArea"></div>
                            </div>

                            <div class="diagnosisSectionBlock">
                                <h4>Diagnosis</h4>
                                <p>Acute Viral Infection</p>
                            </div>

                            <div class="diagnosisSectionBlock">
                                <h4>Treatment Plan</h4>
                                <p>Patient advised rest and hydration.</p>
                            </div>

                        </div>

                    </div>

<!-- PRESCRIPTION SECTION -->
<div id="prescriptionSection" class="tabSection" style="display: none;">

    <div class="prescriptionCleanBox">

        <div class="prescriptionCleanHeader">
            <h3>PRESCRIPTION</h3>
        </div>

        <div id="prescriptionList">

            <div class="prescriptionItem">
                <p>
                    <strong>Medicine:</strong> Paracetamol<br>
                    <strong>Dose:</strong> 500mg<br>
                    <strong>Frequency:</strong> 3 times daily<br>
                    <strong>Duration:</strong> 5 days<br>
                    <strong>Instructions:</strong> After Food, 3x a day
                </p>
            </div>

            <div class="prescriptionItem">
                <p>
                    <strong>Medicine:</strong> Amoxicillin<br>
                    <strong>Dose:</strong> 800mg<br>
                    <strong>Frequency:</strong> 2 times daily<br>
                    <strong>Duration:</strong> 7 days<br>
                    <strong>Instructions:</strong> Before Food, 5x a day
                </p>
            </div>

        </div>

        <button type="button" class="addPrescriptionBtn" onclick="addPrescription()">
            + Add Prescription
        </button>

    </div>


    <!-- CONSULTATION ACTION BUTTONS -->
    <div class="workspaceActions consultationBottomActions">
        <button class="saveBtn" onclick="saveDraft()">
            Save Draft
        </button>

        <button class="completeBtn" onclick="completeConsultation()">
            Complete Consultation
        </button>
    </div>
    </section>

    <!-- MESSAGE POPUP -->
    <div id="messagePopup" class="messagePopup">
        <div class="messageContent">
            <p class="messageText" id="messageText">
                Data is saved.
            </p>

            <button type="button" class="okBtn" id="okMessageBtn">
                OK
            </button>
        </div>
    </div>

    <!-- ADD FINDINGS POPUP -->
    <div id="addFindingsPopup" class="addFindingsOverlay" style="display: none;">

        <div class="addFindingsPopupBox">

            <button type="button" class="closeFindingsBtn" onclick="closeFindingsPopup()">
                X
            </button>

            <h3>Add Clinical Finding</h3>

            <div class="findingFormGroup">
                <label>Type :</label>

                <label class="findingCheck">
                    <input type="checkbox" name="findingType" value="Vital Sign">
                    Vital Sign
                </label>

                <label class="findingCheck">
                    <input type="checkbox" name="findingType" value="Physical Exam Result">
                    Physical Exam Result
                </label>

                <label class="findingCheck">
                    <input type="checkbox" name="findingType" value="Lab Result">
                    Lab Result
                </label>
            </div>

            <div class="findingFormGroup">
                <label>Value:</label>
                <input type="text" id="findingValueInput" class="findingValueInput">
            </div>

            <button type="button" class="saveFindingBtn" onclick="saveFinding()">
                Save
            </button>

        </div>

    <div class="messagePopup">
        <p class="messageText"></p>
        <button class="okBtn">OK</button>
    </div>

    <script src="doctor.js"></script>

</body>
</html>