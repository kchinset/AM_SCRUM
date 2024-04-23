<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch the last inserted ID from the prescriptions table
$stmt = $pdo->query('SELECT MAX(patient_id) AS max_id FROM patients');
$max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];

// Increment the ID for the next prescription
$new_patient_id = $max_id + 1;

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    if (!empty($_POST)) {
        // This part is similar to the update.php, //but instead we insert a record and not //update
        $patient_id = isset($_POST['patient_id']) && !empty($_POST['patient_id']) && $_POST['patient_id'] != 'auto' ? $_POST['patient_id'] : NULL;
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $genetics = isset($_POST['genetics']) ? $_POST['genetics'] : '';
        $diabetes = isset($_POST['diabetes']) ? $_POST['diabetes'] : '';
        $other_conditions = isset($_POST['other_conditions']) ? $_POST['other_conditions'] : '';
        // Insert new record into the patients table
        $stmt = $pdo->prepare('INSERT INTO patients VALUES  (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$patient_id, $first_name, $last_name, $gender, $birthdate, $genetics, $diabetes, $other_conditions]);
        // Output message
        $msg = 'Created Successfully!';
}
}
?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create patient</h2>
    <form action="patientCreate.php" method="post">
        <label for="patient_id">Patient ID</label>
        <label for="name">First Name</label>
        <input type="text" name="patient_id" value="<?=$new_patient_id?>" disabled>
        <input type="text" name="first_name" placeholder="John"  id="first_name">
        <label for="last_name">Last Name</label>
        <label for="gender">Gender</label>
        <input type="text" name="last_name" placeholder="Doe" id="last_name">
        <select name="gender" id="gender">
            <option value="M">Male</option>
            <option value="F">Female</option>
            <option value="Other">Other</option>
            <option value="Not Specified">Not Specified</option>
        </select>
        <label for="birthdate">Birthdate</label>
        <label for="genetics">Genetics</label>
        <input type="date" name="birthdate" placeholder="04/26/2002" id="birthdate">
        <input type="text" name="genetics" placeholder="ACGTACGTGT" id="genetics">
        <label for="diabetes">Diabetes</label>
        <label for="other_conditions">Other Conditions</label>
        <input type="text" name="diabetes" placeholder="Y/N" id="diabetes">
        <input type="text" name="other_conditions" placeholder="None" id="other_conditions">
        <input type="submit" value="Create">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>