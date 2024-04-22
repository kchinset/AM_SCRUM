<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch the last inserted ID from the prescriptions table
$stmt = $pdo->query('SELECT MAX(presc_id) AS max_id FROM prescriptions');
$max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];

// Increment the ID for the next prescription
$new_presc_id = $max_id + 1;

// Check if patient ID is passed as a parameter
if (isset($_GET['patient_id'])) {
    // Fetch patient details based on the passed patient ID
    $stmt = $pdo->prepare('SELECT * FROM patients WHERE patient_id = ?');
    $stmt->execute([$_GET['patient_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if patient exists
    if (!$patient) {
        exit('Patient not found!');
    }

    // Fetch visits associated with the patient
    $stmt = $pdo->prepare('SELECT * FROM visits WHERE patient_id = ?');
    $stmt->execute([$patient['patient_id']]);
    $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted
    $presc_id = $new_presc_id; // Use the new prescription ID
    $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : '';
    $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id'] : '';
    $presc_dosage = isset($_POST['presc_dosage']) ? $_POST['presc_dosage'] : '';
    $date_received = isset($_POST['date_received']) ? $_POST['date_received'] : '';

    // Insert the record
    $stmt = $pdo->prepare('INSERT INTO prescriptions VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$presc_id, $med_id, $visit_id, $presc_dosage, $date_received]);
    $msg = 'Created Successfully!';
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create Prescription <?php if(isset($patient)) echo 'for Patient #' . $patient['patient_id']; ?></h2>
    <form action="prescriptionCreate.php<?=isset($_GET['patient_id']) ? '?patient_id=' . $_GET['patient_id'] : ''?>" method="post">
        <label for="presc_id">Prescription ID</label>
        <input type="text" name="presc_id" value="<?=$new_presc_id?>" disabled>
        <label for="med_id">Medication</label>
        <select name="med_id" id="med_id">
            <option value="">Select Medication</option>
            <?php
            // Fetch all medications from the database
            $stmt = $pdo->query('SELECT * FROM medications');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['med_id'] . '">' . $row['med_name'] . '</option>';
            }
            ?>
        </select>
        <?php if(isset($visits)): ?>
            <label for="visit_id">Visit ID</label>
            <select name="visit_id" id="visit_id">
                <option value="">Select Visit ID</option>
                <?php foreach ($visits as $visit): ?>
                    <option value="<?=$visit['visit_id']?>"><?=$visit['visit_id']?> - <?=$visit['visit_date']?></option>
                <?php endforeach; ?>
            </select>
        <?php endif; ?>
        <label for="presc_dosage">Prescription Dosage</label>
        <input type="text" name="presc_dosage" placeholder="Prescription Dosage" id="presc_dosage">
        <label for="date_received">Date Received</label>
        <input type="date" name="date_received" id="date_received">
        <input type="submit" value="Create">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
