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

                    <button class="endBtn" data-queue="A103">
                        End Session
                    </button>
                </div>

            </article>

            <article class="doctorCard">

                <div class="searchPatientBox">
                <h2>Search Patient</h2>

                <input 
                    type="text" 
                    id="searchPatientInput" 
                    placeholder="Search by name or queue ID"
                    oninput="searchPatientLive()"
                >

                <div id="searchResultBox"></div>

               <div class="searchPatientBox">
                <h2>Search Patient</h2>

                <input 
                    type="text" 
                    id="searchPatientInput" 
                    placeholder="Search by name or queue ID"
                    oninput="searchPatientLive()"
                >

                <div id="searchResultBox"></div>
            </div>
                </div>

            </article>

        </div>

        <!-- RIGHT WORKSPACE -->
        <article class="workspaceCard" id="workspaceCard">

            <!-- CONSULTATION VIEW -->
            <div id="defaultWorkspace">
            <!-- CURRENT CONSULTATION VIEW -->
                <p id="placeholderText">
                    Please select a patient to start consultation.
                </p>

                <div id="searchPatientView" style="display:none;"></div>

        <!-- ==========================
            PATIENT RECORD DISPLAY (ACTIVE)
            ========================== -->
            <div id="patientRecordDisplay" style="display:none;">

                <h2 class="recordTitle">CURRENT CONSULTATION</h2>

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

                    <button class="miniTab active" onclick="switchTab('overview', this)">Overview</button>
                    <button class="miniTab" onclick="switchTab('visits', this)">Visits</button>
                    <button class="miniTab" onclick="switchTab('diagnosis', this)">Diagnosis</button>
                    <button class="miniTab" onclick="switchTab('prescription', this)">Prescription</button>

                </div>

                <!-- OVERVIEW -->
                <div class="overviewSection">

                    <div class="infoBlock">
                        <h3>Allergies</h3>
                        <div id="pAllergyList"></div>
                    </div>

                    <div class="infoBlock">
                        <h3>Chronic Diseases</h3>
                        <div id="pChronicList"></div>
                    </div>

                    <div class="infoBlock">
                        <h3>Medication</h3>
                        <div id="pMedList"></div>
                    </div>

                </div>

            </div>


       

                        <!----SEARCH PATIENT VIEW---->
                        <div id="viewOnlyRecordDisplay" style="display:none;">
                        <h2 class="recordTitle">
                        PATIENT RECORD <span style="color:green;">(VIEW ONLY)</span>
                        </h2>

                        <div class="viewOnlyAlert">
                            👁 You are viewing this patient record. Information shown below is for reference only.
                        </div>

                        <div class="patientInfo">

                            <p>
                                <strong>Full name:</strong>
                                <span>Amir bin Amar</span>
                            </p>

                            <p>
                                <strong>Gender:</strong>
                                <span>Male</span>
                            </p>

                            <p>
                                <strong>ID:</strong>
                                <span>D514698002</span>
                            </p>

                            <p>
                                <strong>Blood type:</strong>
                                <span>B+</span>
                            </p>

                        </div>

                        <h3 class="viewSectionTitle">OVERVIEW</h3>

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Allergies</h3>
                                <button type="button" class="addSmallBtn" disabled>+ Add Allergy</button>
                            </div>

                            <p>• Dust (Patient Reported)</p>
                            <p>• Peanuts (Doctor Confirmed)</p>
                        </div>

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Chronic Diseases</h3>
                                <button type="button" class="addSmallBtn" disabled>+ Add Condition</button>
                            </div>

                            <p>No chronic diseases recorded.</p>
                        </div>

                        <div class="infoBlock">
                            <div class="infoHeader">
                                <h3>Current Medication</h3>
                                <button type="button" class="addSmallBtn" disabled>+ Add Medication</button>
                            </div>

                            <p>• Ventolin Inhaler</p>
                        </div>

                        <h3 class="viewSectionTitle">VISITS (MEDICAL RECORD)</h3>

                        <div class="visitViewCard">
                            <div class="visitViewHeader">
                                <strong>Visit #3 (Latest)</strong>
                                <span>22/06/2026, 02:30:45 pm</span>
                            </div>

                            <p><strong>Reason for Visit:</strong> Patient reports fever and headache.</p>
                            <p><strong>Clinical Findings:</strong> Temperature 38.5°C, BP 120/80 mmHg, HR 92 bpm, RR 20/min, SpO2 98%, Additional: Throat redness.</p>
                            <p>No shortness of breath</p>
                            <p><strong>Diagnosis:</strong> Influenza</p>
                            <p><strong>Treatment Plan:</strong> Rest + fluids</p>
                            <p><strong>Prescription:</strong> Paracetamol 500mg | 1 tablet | TDS | 5 days</p>
                        </div>

                        <div class="visitMiniCard">
                            <strong>Visit #2</strong>
                            <span>22/06/2026, 11:35:20 pm</span>
                        </div>

                        <div class="visitMiniCard">
                            <strong>Visit #1</strong>
                            <span>10/05/2026, 10:15:10 am</span>
                        </div>

                        <div class="visitMiniCard">
                            <strong>Visit #0</strong>
                            <span>05/03/2026, 09:40:00 am</span>
                        </div>

                        <div class="viewOnlyNote">
                            🔒 This is a view only page. To create a new consultation, please start a session from the Current Consultations.
                        </div>

                    </div>

                    </div>


        <!-- VISITS SECTION -->
        <div id="visitsSection" class="tabSection" style="display: none;">
            <div id="visitHistoryList"></div>

                <!-- CARD 1 -->
                <div id="visitCard">
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
                <div id="visitCard">
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
                <div id="visitCard">
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
                        <!-- 1. REASON FOR VISIT -->
                        <div class="cardBox">
                            <h4>Reason for Visit</h4>
                            <p id="reasonVisitText">
                                Patient reports fever and headache.
                            </p>
                        </div>

                        <!-- 2. CLINICAL FINDINGS -->
                        <div class="cardBox">

                            <div class="cardHeader">
                                <h4>Clinical Findings</h4>
                            </div>

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

                            <div class="findingText">
                                <strong>Add Clinical Finding</strong>
                            </div>


                            <div id="findingOutput"></div>
                            <!-- optional doctor input -->
                            <div class="noteRow">
                                <input id="newFinding" placeholder="Add clinical note..." />
                                <button type ="button" onclick="addFinding()">Add</button>
                            </div>

                            <div id="findingList"></div>

                            <div class="findingText">
                                <strong>Physical Observation</strong>
                                <p>Patient appears fatigued.</p>
                            </div>

                            <div class="findingText">
                                <strong>Test Results</strong>
                                <p>
                                    Rapid COVID Test: Negative<br>
                                    Blood Glucose: 6.1 mmol/L
                                </p>
                            </div>
                </div>

                        <!-- 3. DIAGNOSIS -->
                        <div class="cardBox">
                            <h4>Diagnosis</h4>
                            <p id="diagnosisText">Acute Viral Infection</p>
                        </div>

                        <!-- 4. TREATMENT PLAN -->
                        <div class="cardBox">
                            <h4>Treatment Plan</h4>
                            <p id="treatmentText">Patient advised rest and hydration.</p>
                        </div>
            </div>
</div>

            <!-- PRESCRIPTION SECTION -->
            <div id="prescriptionSection" class="tabSection" style="display:none;">

                <h2 class="prescriptionTitle">PRESCRIPTION</h2>

                <!-- ADD MEDICINE SECTION -->
                <div class="formCard">

                    <div class="rowTop">
                        <h3>Add Medicine <span class="infoIcon">ⓘ</span></h3>

                        <button type="button" class="adminBtn">
                            + Add New Medicine (Admin Only)
                        </button>
                    </div>

                    <div class="grid">

                        <div class="field">
                            <label>Medicine</label>
                            <select id="medicine" onchange="updateStock()">
                                <option value="Paracetamol 500mg" data-stock="120">Paracetamol 500mg</option>
                                <option value="Amoxicillin 500mg" data-stock="80">Amoxicillin 500mg</option>
                                <option value="Ibuprofen 400mg" data-stock="50">Ibuprofen 400mg</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Stock Available</label>
                            <div class="stockBox" id="stockBox">120 tablets</div>
                        </div>

                        <div class="field">
                            <label>Dosage</label>
                            <select id="dosage">
                                <option>1 tablet</option>
                                <option>2 tablets</option>
                                <option>1 capsule</option>
                                <option>500mg</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Frequency</label>
                            <select id="frequency">
                                <option>TDS (3 times a day)</option>
                                <option>BD (2 times a day)</option>
                                <option>OD (once a day)</option>
                                <option>SOS (When needed)</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Duration</label>
                            <select id="duration">
                                <option>3 days</option>
                                <option>5 days</option>
                                <option>7 days</option>
                                <option>14 days</option>
                            </select>
                        </div>

                        <div class="field">
                            <label>Notes (Optional)</label>
                            <input id="notes" placeholder="e.g. After meals">
                        </div>

                    </div>

                    <div class="addPrescriptionWrap">
                        <button type="button" class="addBtn" onclick="addPrescription()">
                            + Add to Prescription
                        </button>
                    </div>

                </div>

                <!-- PRESCRIPTION LIST -->
                <div class="prescriptionListBox">
                    <h3>Prescription List</h3>
                    <p>Listed medicines will be saved for this patient.</p>

                    <table class="rxTable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Medicine</th>
                                <th>Dosage</th>
                                <th>Frequency</th>
                                <th>Duration</th>
                                <th>Notes</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody id="rxTableBody">
                            <tr>
                                <td>1</td>
                                <td>Paracetamol 500mg</td>
                                <td>1 tablet</td>
                                <td>TDS (3 times a day)</td>
                                <td>5 days</td>
                                <td>After meals</td>
                                <td><button type="button" class="deleteRxBtn" onclick="deleteRx(this)">🗑</button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- IMPORTANT NOTES -->
                <div class="importantBox">
                    <div>
                        <h4>ⓘ Important Notes</h4>
                        <p>• Please review the prescription before submitting.</p>
                        <p>• Stock will be deducted after saving the prescription.</p>
                        <p>• Cannot prescribe medicine with 0 stock.</p>
                    </div>

                    <button type="button" class="savePrescriptionBtn">
                        💾 Save Prescription
                    </button>
                </div>

            </div>

            <!-- <div class="consultationBottomActions">
                <button type="button" class="completeBtn" onclick="endSession()">
                    End Consultation
                </button>
            </div> -->

    <script src="doctor.js"></script>

</body>
</html>