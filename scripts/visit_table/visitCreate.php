<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch the last inserted ID from the prescriptions table
$stmt = $pdo->query('SELECT MAX(visit_id) AS max_id FROM visits');
$max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];

// Increment the ID for the next prescription
$new_visit_id = $max_id + 1;

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
}

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted
    $visit_id = $new_visit_id;
    $visit_date = isset($_POST['visit_date']) ? $_POST['visit_date'] : '';
    $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : NULL;
    $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';

    // Insert the record
    $stmt = $pdo->prepare('INSERT INTO visits (visit_date, patient_id, doctor_id) VALUES (?, ?, ?)');
    $stmt->execute([$visit_date, $patient_id, $doctor_id]);
    
    // Get the last inserted visit_id
    $last_visit_id = $pdo->lastInsertId();

    $msg = 'Visit created successfully! Visit ID: ' . $new_visit_id;
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create Visit <?php if(isset($patient)) echo 'for Patient #' . $patient['patient_id']; ?></h2>
    <form action="visitCreate.php<?=isset($_GET['patient_id']) ? '?patient_id=' . $_GET['patient_id'] : ''?>" method="post">
    <label for="visit_id">Visit ID</label>
        <input type="text" name="visit_id" value="<?=$new_visit_id?>" disabled>
        <label for="visit_date">Visit Date</label>
        <input type="date" name="visit_date" id="visit_date">
        <?php if(isset($patient)): ?>
        <input type="hidden" name="patient_id" value="<?=$patient['patient_id']?>">
        <label>Patient ID</label>
        <input type="text" value="<?=$patient['patient_id']?>" disabled>
        <?php else: ?>
        <label for="patient_id">Patient ID</label>
        <select name="patient_id" id="patient_id">
            <option value="">Select Patient ID</option>
            <?php
            // Fetch all patient IDs from the database
            $stmt = $pdo->query('SELECT patient_id, first_name, last_name FROM patients');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['patient_id'] . '">' . $row['patient_id'] . ' - ' . $row['first_name']. ' ' . $row['last_name']. '</option>';
            }
            ?>
        </select>
        <?php endif; ?>
        <label for="doctor_id">Doctor ID</label>
        <select name="doctor_id" id="doctor_id">
            <option value="">Select Doctor ID</option>
            <?php
            // Fetch doctor IDs and names from the database
            $stmt = $pdo->query('SELECT doctor_id, doctor_name FROM doctors');
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . $row['doctor_id'] . '">' . $row['doctor_id'] . ' - ' . $row['doctor_name'] . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Create">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>

