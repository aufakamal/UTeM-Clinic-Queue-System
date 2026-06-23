<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="inc/patient.css">
    <title>UTeM Clinic Queue System</title>
</head>

<body>

    <?php include('inc/patient_header.php'); ?>

    <section>
        <h2>Self-Assessment</h2>
        <p>Check your symptoms or explore health information.</p>

        <div class="topCards">
            <button id="symptomChecker" class="cardBtn">
                <h2>Symptom Checker</h2>
                <h3>Answer a few questions to receive guidance on whether you should visit the clinic.</h3>
                <p>Click to start assessment</p>
            </button>
        </div>
    </section>

    <section>
        <article id="mainAssessmentCard" class="hidden">
            <form id="mainAssessmentForm">
                <h3>What best describes your concern today?</h3>

                <div class="radioOption">
                    <input type="radio" id="feverFlu" name="mainAssessment" value="feverFlu">
                    <label for="feverFlu">Fever / Flu Symptoms</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="painInjury" name="mainAssessment" value="painInjury">
                    <label for="painInjury">Pain or Injury</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="medicationConcern" name="mainAssessment" value="medicationConcern">
                    <label for="medicationConcern">Medication Concern</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mentalHealthConcern" name="mainAssessment" value="mentalHealthConcern">
                    <label for="mentalHealthConcern">Mental Health Concern</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="generalHealthConcern" name="mainAssessment" value="generalHealthConcern">
                    <label for="generalHealthConcern">General Health Concern</label>
                </div>

                <button type="submit" class="submitBtn">Next</button>
            </form>
        </article>

        <article id="feverFluCard" class="hidden">
            <form id="feverFluForm">
                <h3>Do you have fever?</h3>

                <div class="radioOption">
                    <input type="radio" id="flu_fever_yes" name="flu_fever" value="Yes">
                    <label for="flu_fever_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="flu_fever_no" name="flu_fever" value="No">
                    <label for="flu_fever_no">No</label>
                </div>


                <h3>Duration of Symptoms</h3>

                <div class="radioOption">
                    <input type="radio" id="flu_duration_less2" name="flu_duration" value="Less than 2 days">
                    <label for="flu_duration_less2">Less than 2 days</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="flu_duration_2to5" name="flu_duration" value="2-5 days">
                    <label for="flu_duration_2to5">2-5 days</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="flu_duration_more5" name="flu_duration" value="More than 5 days">
                    <label for="flu_duration_more5">More than 5 days</label>
                </div>


                <h3>Any of the following?</h3>

                <div class="radioOption">
                    <input type="checkbox" id="flu_symptom_cough" name="flu_symptoms" value="Cough">
                    <label for="flu_symptom_cough">Cough</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_symptom_sore_throat" name="flu_symptoms" value="Sore throat">
                    <label for="flu_symptom_sore_throat">Sore throat</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_symptom_runny_nose" name="flu_symptoms" value="Runny nose">
                    <label for="flu_symptom_runny_nose">Runny nose</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_symptom_headache" name="flu_symptoms" value="Headache">
                    <label for="flu_symptom_headache">Headache</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_symptom_body_ache" name="flu_symptoms" value="Body ache">
                    <label for="flu_symptom_body_ache">Body ache</label>
                </div>


                <h3>Are you experiencing?</h3>

                <div class="radioOption">
                    <input type="checkbox" id="flu_warning_vomiting" name="flu_warning_signs" value="Persistent vomiting">
                    <label for="flu_warning_vomiting">Persistent vomiting</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_warning_breathing" name="flu_warning_signs" value="Difficulty breathing">
                    <label for="flu_warning_breathing">Difficulty breathing</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="flu_warning_chest_pain" name="flu_warning_signs" value="Chest pain">
                    <label for="flu_warning_chest_pain">Chest pain</label>
                </div>

                <h3>Have you taken any medication?</h3>

                <div class="radioOption">
                    <input type="radio" id="flu_medication_no" name="flu_medication" value="No">
                    <label for="flu_medication_no">No</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="flu_medication_otc" name="flu_medication" value="Over the counter medicine">
                    <label for="flu_medication_otc">Over the counter medicine</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="flu_medication_prescribed" name="flu_medication" value="Prescribed medication">
                    <label for="flu_medication_prescribed">Prescribed medication</label>
                </div>


                <button type="submit" class="submitBtn">See Result</button>
            </form>
        </article>

        <article id="painInjuryCard" class="hidden">
            <form id="painInjuryForm">
                <h3>Did injury occur recently?</h3>

                <div class="radioOption">
                    <input type="radio" id="pain_injury_recent_yes" name="pain_injury_recent" value="Yes">
                    <label for="pain_injury_recent_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="pain_injury_recent_no" name="pain_injury_recent" value="No">
                    <label for="pain_injury_recent_no">No</label>
                </div>


                <h3>Are you able to move normally?</h3>

                <div class="radioOption">
                    <input type="radio" id="pain_move_normal_yes" name="pain_move_normal" value="Yes">
                    <label for="pain_move_normal_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="pain_move_normal_no" name="pain_move_normal" value="No">
                    <label for="pain_move_normal_no">No</label>
                </div>


                <h3>Pain level:</h3>

                <div class="radioOption">
                    <input type="radio" id="pain_level_mild" name="pain_level" value="Mild">
                    <label for="pain_level_mild">Mild</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="pain_level_moderate" name="pain_level" value="Moderate">
                    <label for="pain_level_moderate">Moderate</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="pain_level_severe" name="pain_level" value="Severe">
                    <label for="pain_level_severe">Severe</label>
                </div>


                <h3>Location of pain:</h3>

                <div class="optionColumns">
                    <div>
                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_head_neck" name="pain_location" value="Head & Neck">
                            <label for="pain_location_head_neck">Head &amp; Neck</label>
                        </div>

                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_upper_body" name="pain_location" value="Upper Body">
                            <label for="pain_location_upper_body">Upper Body</label>
                        </div>

                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_abdomen_torso" name="pain_location" value="Abdomen & Torso">
                            <label for="pain_location_abdomen_torso">Abdomen &amp; Torso</label>
                        </div>

                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_not_sure" name="pain_location" value="Not sure">
                            <label for="pain_location_not_sure">Not sure</label>
                        </div>
                    </div>

                    <div>
                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_pelvic_area" name="pain_location" value="Pelvic Area">
                            <label for="pain_location_pelvic_area">Pelvic Area</label>
                        </div>

                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_lower_body" name="pain_location" value="Lower Body">
                            <label for="pain_location_lower_body">Lower Body</label>
                        </div>

                        <div class="radioOption">
                            <input type="checkbox" id="pain_location_whole_body" name="pain_location" value="Whole body">
                            <label for="pain_location_whole_body">Whole body</label>
                        </div>
                    </div>
                </div>


                <h3>Any swelling, bruising, or numbness?</h3>

                <div class="radioOption">
                    <input type="radio" id="pain_swelling_yes" name="pain_swelling" value="Yes">
                    <label for="pain_swelling_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="pain_swelling_no" name="pain_swelling" value="No">
                    <label for="pain_swelling_no">No</label>
                </div>


                <button type="submit" class="submitBtn">See Result</button>
            </form>
        </article>

        <article id="medicationConcernCard" class="hidden">
            <form id="medicationConcernForm">
                <h3>Are you currently taking prescribed medication?</h3>

                <div class="radioOption">
                    <input type="radio" id="medication_prescribed_yes" name="medication_prescribed" value="Yes">
                    <label for="medication_prescribed_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="medication_prescribed_no" name="medication_prescribed" value="No">
                    <label for="medication_prescribed_no">No</label>
                </div>


                <h3>Are you experiencing any symptoms after taking medication?</h3>

                <div class="radioOption">
                    <input type="radio" id="medication_symptoms_yes" name="medication_symptoms" value="Yes">
                    <label for="medication_symptoms_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="medication_symptoms_no" name="medication_symptoms" value="No">
                    <label for="medication_symptoms_no">No</label>
                </div>


                <h3>What issue are you experiencing?</h3>

                <div class="radioOption">
                    <input type="checkbox" id="medication_issue_side_effects" name="medication_issues" value="Side effects">
                    <label for="medication_issue_side_effects">Side effects</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="medication_issue_not_working" name="medication_issues" value="Medication not working">
                    <label for="medication_issue_not_working">Medication not working</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="medication_issue_unsure_how" name="medication_issues" value="Unsure how to take medication">
                    <label for="medication_issue_unsure_how">Unsure how to take medication</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="medication_issue_advice" name="medication_issues" value="Need medication advice">
                    <label for="medication_issue_advice">Need medication advice</label>
                </div>


                <h3>Severity of concern</h3>

                <div class="radioOption">
                    <input type="radio" id="medication_severity_mild" name="medication_severity" value="Mild concern">
                    <label for="medication_severity_mild">Mild concern</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="medication_severity_moderate" name="medication_severity" value="Moderate concern">
                    <label for="medication_severity_moderate">Moderate concern</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="medication_severity_urgent" name="medication_severity" value="Urgent concern">
                    <label for="medication_severity_urgent">Urgent concern</label>
                </div>


                <button type="submit" class="submitBtn">See Result</button>
            </form>
        </article>

        <article id="mentalHealthConcernCard" class="hidden">
            <form id="mentalHealthConcernForm">
                <h3>Difficulty concentrating on studies/work?</h3>

                <div class="radioOption">
                    <input type="radio" id="mental_concentration_yes" name="mental_concentration" value="Yes">
                    <label for="mental_concentration_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_concentration_no" name="mental_concentration" value="No">
                    <label for="mental_concentration_no">No</label>
                </div>


                <h3>Duration of symptoms:</h3>

                <div class="radioOption">
                    <input type="radio" id="mental_duration_less2" name="mental_duration" value="Less than 2 days">
                    <label for="mental_duration_less2">Less than 2 days</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_duration_2to5" name="mental_duration" value="2-5 days">
                    <label for="mental_duration_2to5">2-5 days</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_duration_more5" name="mental_duration" value="More than 5 days">
                    <label for="mental_duration_more5">More than 5 days</label>
                </div>


                <h3>Any of the following?</h3>

                <div class="radioOption">
                    <input type="checkbox" id="mental_symptom_cough" name="mental_symptoms" value="Cough">
                    <label for="mental_symptom_cough">Cough</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="mental_symptom_sore_throat" name="mental_symptoms" value="Sore throat">
                    <label for="mental_symptom_sore_throat">Sore throat</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="mental_symptom_runny_nose" name="mental_symptoms" value="Runny nose">
                    <label for="mental_symptom_runny_nose">Runny nose</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="mental_symptom_headache" name="mental_symptoms" value="Headache">
                    <label for="mental_symptom_headache">Headache</label>
                </div>

                <div class="radioOption">
                    <input type="checkbox" id="mental_symptom_body_ache" name="mental_symptoms" value="Body ache">
                    <label for="mental_symptom_body_ache">Body ache</label>
                </div>


                <h3>Over the past 2 weeks, how have you felt emotionally?</h3>

                <div class="radioOption">
                    <input type="radio" id="mental_emotion_well" name="mental_emotion" value="Mostly well">
                    <label for="mental_emotion_well">Mostly well</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_emotion_stressed" name="mental_emotion" value="Stressed">
                    <label for="mental_emotion_stressed">Stressed</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_emotion_anxious" name="mental_emotion" value="Anxious">
                    <label for="mental_emotion_anxious">Anxious</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_emotion_low_mood" name="mental_emotion" value="Low mood">
                    <label for="mental_emotion_low_mood">Low mood</label>
                </div>


                <h3>Would you like to speak with a healthcare professional?</h3>

                <div class="radioOption">
                    <input type="radio" id="mental_speak_yes" name="mental_speak_professional" value="Yes">
                    <label for="mental_speak_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_speak_maybe" name="mental_speak_professional" value="Maybe">
                    <label for="mental_speak_maybe">Maybe</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="mental_speak_not_sure" name="mental_speak_professional" value="Not sure">
                    <label for="mental_speak_not_sure">Not sure</label>
                </div>


                <button type="submit" class="submitBtn">See Result</button>
            </form>
        </article>

        <article id="generalHealthConcernCard" class="hidden">
            <form id="generalHealthConcernForm">
                <h3>What best describes your concern?</h3>

                <div class="radioOption">
                    <input type="radio" id="general_concern_unwell" name="general_concern" value="Feeling unwell">
                    <label for="general_concern_unwell">Feeling unwell</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_concern_medical_advice" name="general_concern" value="Need medical advice">
                    <label for="general_concern_medical_advice">Need medical advice</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_concern_health_check" name="general_concern" value="Health check inquiry">
                    <label for="general_concern_health_check">Health check inquiry</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_concern_other" name="general_concern" value="Other concern">
                    <label for="general_concern_other">Other concern</label>
                </div>


                <h3>Duration of concern</h3>

                <div class="radioOption">
                    <input type="radio" id="general_duration_today" name="general_duration" value="Today">
                    <label for="general_duration_today">Today</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_duration_few_days" name="general_duration" value="Few days">
                    <label for="general_duration_few_days">Few days</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_duration_more_week" name="general_duration" value="More than a week">
                    <label for="general_duration_more_week">More than a week</label>
                </div>


                <h3>Are symptoms worsening?</h3>

                <div class="radioOption">
                    <input type="radio" id="general_worsening_yes" name="general_worsening" value="Yes">
                    <label for="general_worsening_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_worsening_no" name="general_worsening" value="No">
                    <label for="general_worsening_no">No</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_worsening_not_sure" name="general_worsening" value="Not sure">
                    <label for="general_worsening_not_sure">Not sure</label>
                </div>


                <h3>Is this affecting daily activities?</h3>

                <div class="radioOption">
                    <input type="radio" id="general_daily_yes" name="general_daily_activities" value="Yes">
                    <label for="general_daily_yes">Yes</label>
                </div>

                <div class="radioOption">
                    <input type="radio" id="general_daily_no" name="general_daily_activities" value="No">
                    <label for="general_daily_no">No</label>
                </div>


                <button type="submit" class="submitBtn">See Result</button>
            </form>
        </article>
    </section>

    <section>
        <article id="assessmentResultCard" class="hidden">
            <h2>Symptom Checker Result</h2>
            <h3 id="assessmentResultText">Result</h3>
            <p>Refresh page to restart Symptom Checker</p>
        </article>
    </section>

    <?php include('inc/patient_footer.php'); ?>
    <script src="js/selfAssessment.js"></script>
</body>
</html>