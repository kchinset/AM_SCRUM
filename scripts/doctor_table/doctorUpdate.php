<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['doctor_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : NULL;
        $doctor_name = isset($_POST['doctor_name']) ? $_POST['doctor_name'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE doctors SET doctor_id = ?, doctor_name = ? WHERE doctor_id = ?');
        $stmt->execute([$doctor_id, $doctor_name, $_GET['doctor_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the doctors table
    $stmt = $pdo->prepare('SELECT * FROM doctors WHERE doctor_id = ?');
    $stmt->execute([$_GET['doctor_id']]);
    $doctor = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$doctor) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Doctor #<?=$doctor['doctor_id']?></h2>
    <form action="doctorUpdate.php?doctor_id=<?=$doctor['doctor_id']?>" method="post">
        <label for="id">Doctor ID</label>
        <label for="name">Doctor Name</label>
        <input type="text" name="doctor_id" placeholder="1" value="<?=$doctor['doctor_id']?>" id="doctor_id">
        <input type="text" name="doctor_name" placeholder="John" value="<?=$doctor['doctor_name']?>" id="doctor_name">
        <input type="submit" value="Update">
    </form>
    <a href="doctorRead.php" class="back-button">Back</a>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>