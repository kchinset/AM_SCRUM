<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
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
        <label for="id">ID</label>
        <label for="name">First Name</label>
        <input type="text" name="patient_id" placeholder="1" value="auto" id="patient_id">
        <input type="text" name="first_name" placeholder="John"  id="first_name">
        <label for="last_name">Last Name</label>
        <label for="gender">Gender</label>
        <input type="text" name="last_name" placeholder="Doe" id="last_name">
        <input type="text" name="gender" placeholder="M/F/Other/Not Specified" id="gender">
        <label for="birthdate">Birthdate</label>
        <label for="genetics">Genetics</label>
        <input type="text" name="birthdate" placeholder="04/26/2002" id="birthdate">
        <input type="text" name="genetics" placeholder="ACGTACGTGT" id="genetics">
        <label for="diabetes">Diabetes</label>
        <label for="other_conditions">Other Conditions</label>
        <input type="text" name="diabetes" placeholder="04/26/2002" id="diabetes">
        <input type="text" name="other_conditions" placeholder="" id="other_conditions">
        <input type="submit" value="Create">
    </form>
    <a href="patientRead.php" class="back-button">Back</a>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>