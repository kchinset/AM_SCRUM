<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    $doctor_id = isset($_POST['doctor_id']) ? $_POST['doctor_id'] : NULL;
    $doctor_name = isset($_POST['doctor_name']) ? $_POST['doctor_name'] : '';
    // Update the record
    $stmt = $pdo->prepare('INSERT INTO doctors VALUES (?, ?)');
    $stmt->execute([$doctor_id, $doctor_name]);
    $msg = 'Created Successfully!';
}

?>
<?=template_header('Create')?>

<div class="content update">
	<h2>Create Doctor</h2>
    <form action="doctorCreate.php" method="post">
    <label for="id">Doctor ID</label>
        <label for="name">Doctor Name</label><br>
        <input type="text" name="doctor_id" placeholder="1"value="auto" id="doctor_id">
        <input type="text" name="doctor_name" placeholder="Bajaj" id="doctor_name">
        <input type="submit" value="Create">
    </form>
    <a href="doctorRead.php" class="back-button">Back</a>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>