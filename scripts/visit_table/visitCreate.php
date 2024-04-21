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
        $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id']:'';
        $visit_date = isset($_POST['visit_date']) ? $_POST['visit_date'] : '';
        $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : NULL;
        $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
        // Update the record
        $stmt = $pdo->prepare('INSERT INTO visits VALUES (?, ?, ?, ?)');
        $stmt->execute([$visit_id, $visit_date, $patient_id, $doctor_id]);
        $msg = 'Created Successfully!';
}
}
?>

<?=template_header('Create')?>

<div class="content update">
	<h2>Create Visit </h2>
    <form action="visitCreate.php" method="post">
        <label for="id">Visit ID</label>
        <label for="name">Visit Date</label>
        <input type="text" name="visit_id" placeholder="1"  id="visit_id">
        <input type="text" name="visit_date" placeholder="04/26/2024" id="visit_date">
        <label for="patient_id">Patient ID</label>
        <label for="doctor_id">Doctor ID</label>
        <input type="text" name="patient_id" placeholder="1" id="patient_id">
        <input type="text" name="doctor_id" placeholder="1" id="doctor_id">
        <input type="submit" value="Create">
    </form>
    <a href="visitRead.php" class="back-button">Back</a>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>