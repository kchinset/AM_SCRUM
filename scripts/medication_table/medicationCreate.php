<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : NULL;
    $med_name = isset($_POST['med_name']) ? $_POST['med_name'] : '';
    // Create the record
    $stmt = $pdo->prepare('INSERT INTO medications VALUES  (?, ?) ');
    $stmt->execute([$med_id, $med_name]);
     $msg = 'Created Successfully!';
}

?>
<?=template_header('Create')?>

<div class="content update">
	<h2>Create Medication</h2>
    <form action="medicationCreate.php" method="post">
    <label for="id">Medication ID</label>
        <label for="name">Medication Name</label><br>
        <input type="text" name="med_id" placeholder="1"value="auto" id="id">
        <input type="text" name="med_name" placeholder="Eulexin" " id="name">
        <input type="submit" value="Create">
    </form>
    <a href="medicationRead.php" class="back-button">Back</a>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>