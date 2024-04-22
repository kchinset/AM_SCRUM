<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['visit_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id'] : NULL;
        $visit_date = isset($_POST['visit_date']) ? $_POST['visit_date'] : '';
        $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : '';
        $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE visits SET visit_id = ?, visit_date = ?, patient_id = ?, doctor_id = ? WHERE visit_id = ?');
        $stmt->execute([$visit_id, $visit_date, $patient_id, $doctor_id, $_GET['visit_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the visits table
    $stmt = $pdo->prepare('SELECT * FROM visits WHERE visit_id = ?');
    $stmt->execute([$_GET['visit_id']]);
    $visit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$visit) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Visit #<?=$visit['visit_id']?></h2>
    <form action="visitUpdate.php?visit_id=<?=$visit['visit_id']?>" method="post">
        <label for="id">Visit ID</label>
        <label for="name">Visit Date</label>
        <input type="text" name="visit_id" placeholder="1" value="<?=$visit['visit_id']?>" id="visit_id">
        <input type="text" name="visit_date" placeholder="John" value="<?=$visit['visit_date']?>" id="visit_date">
        <label for="patient_id">Patient ID</label>
        <label for="doctor_id">Doctor ID</label>
        <input type="text" name="patient_id" placeholder="Doe" value="<?=$visit['patient_id']?>" id="patient_id">
        <input type="text" name="doctor_id" placeholder="M/F/Other/Not Specified" value="<?=$visit['doctor_id']?>" id="doctor_id">
        <input type="submit" value="Update">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>