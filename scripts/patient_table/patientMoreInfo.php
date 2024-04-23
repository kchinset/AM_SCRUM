<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Check if patient_id is set in GET parameters
if (isset($_GET['patient_id'])) {
    // Fetch patient details
    $stmt = $pdo->prepare('SELECT *, YEAR(CURRENT_DATE) - YEAR(birthdate) AS age FROM patients WHERE patient_id = ?');
    $stmt->execute([$_GET['patient_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch visits for the patient
    
    $stmt = $pdo->prepare('
    SELECT v.*, 
           p.first_name AS patient_first_name, 
           p.last_name AS patient_last_name,
           d.doctor_name
    FROM visits v 
    JOIN patients p ON v.patient_id = p.patient_id 
    JOIN doctors d ON v.doctor_id = d.doctor_id
    WHERE p.patient_id = ?
    ORDER BY v.visit_date DESC');
    $stmt->execute([$_GET['patient_id']]);
    $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    

    // Iterate through each visit
    foreach ($visits as $visit) {
        // Fetch prescriptions for each visit
        $stmt = $pdo->prepare('SELECT * FROM prescriptions WHERE visit_id = ?');
        $stmt->execute([$visit['visit_id']]);
        $prescriptions[$visit['visit_id']] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch FEV1 results for each visit
        $stmt = $pdo->prepare('SELECT * FROM fev1_results WHERE visit_id = ?');
        $stmt->execute([$visit['visit_id']]);
        $fev1_results[$visit['visit_id']] = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$patient) {
        exit('Patient not found!');
    }
} else {
    exit('No patient ID specified!');
}
?>

<?=template_header('Read')?>

<div class="content read">
    <h2>More Information for Patient #<?=$patient['patient_id']?></h2>
    <!-- Display patient details -->
    <table>
        <thead>
            <tr>
                <td>Patient ID</td>
                <td>First Name</td>
                <td>Last Name</td>
                <td>Gender</td>
                <td>Age</td>
                <td>Birthdate</td>
                <td>Genetics</td>
                <td>Diabetes</td>
                <td>Other Conditions</td>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><?=$patient['patient_id']?></td>
                <td><?=$patient['first_name']?></td>
                <td><?=$patient['last_name']?></td>
                <td><?=$patient['gender']?></td>
                <td><?=$patient['age']?></td>
                <td><?=$patient['birthdate']?></td>
                <td><?=$patient['genetics']?></td>
                <td><?=$patient['diabetes']?></td>
                <td><?=$patient['other_conditions']?></td>
                <td class="actions">
                    <a href="patientUpdate.php?patient_id=<?=$patient['patient_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="patientDelete.php?patient_id=<?=$patient['patient_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
        </tbody>
    </table>


    <!-- Display visit information along with FEV1 values -->
    <h3>Visit Information and FEV1 Values</h3>
    <table>
        <thead>
            <tr>
                <td>Visit ID</td>
                <td>Visit Date</td>
                <td>Doctor ID</td>
                <td>Doctor Name</td>
                <td>Highest FEV1 Value</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visits as $visit): ?>
                <?php
                $stmt = $pdo->prepare('SELECT MAX(fev1_value) AS max_fev1 FROM fev1_results WHERE visit_id = ?');
                $stmt->execute([$visit['visit_id']]);
                $max_fev1 = $stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                <tr>
                    <td><?=$visit['visit_id']?></td>
                    <td><?=$visit['visit_date']?></td>
                    <td><?=$visit['doctor_id']?></td>
                    <td><?=$visit['doctor_name']?></td>
                    <td><?=($max_fev1['max_fev1'] !== null) ? $max_fev1['max_fev1'] : "No FEV1 value"?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="/CIS4033/AM_SCRUM/scripts/visit_table/visitCreate.php?patient_id=<?=$patient['patient_id']?>" class="create-contact">Create Visit</a>

    <!-- Display prescription information -->
 <!-- Display prescription information -->
<h3>Prescription Information</h3>
<table>
    <thead>
        <tr>
            <td>Visit Date</td>
            <td>Prescription ID</td>
            <td>Medication Name</td>
            <td>Prescription Type/Dosage</td>
            <td>Prescription Quantity</td>
            <td>Date Received</td>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($visits as $visit): ?>
            <?php if (isset($prescriptions[$visit['visit_id']])): ?>
                <?php foreach ($prescriptions[$visit['visit_id']] as $prescription): ?>
                    <tr>
                        <td><?=$visit['visit_date']?></td>
                        <!-- Fetch medication name for the prescription -->
                        <?php
                        $stmt = $pdo->prepare('SELECT med_name FROM medications WHERE med_id = ?');
                        $stmt->execute([$prescription['med_id']]);
                        $medication = $stmt->fetch(PDO::FETCH_ASSOC);
                        ?>
                        <td><?=$prescription['presc_id']?></td>
                        <td><?=$medication['med_name']?></td>
                        <td><?=$prescription['presc_dosage']?></td>
                        <td><?=$prescription['presc_quantity']?></td>
                        <td><?=$prescription['date_received']?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td><?=$visit['visit_date']?></td>
                    <td colspan="3">No prescriptions</td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
    </tbody>
</table>
<a href="/CIS4033/AM_SCRUM/scripts/prescription_table/prescriptionCreate.php?patient_id=<?=$patient['patient_id']?>" class="create-contact">Create Prescription</a>
<button onclick="window.history.back()" class="back-button">Back</button>