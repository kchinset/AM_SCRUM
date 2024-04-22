<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['med_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : NULL;
        $med_name = isset($_POST['med_name']) ? $_POST['med_name'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE medications SET med_id = ?, med_name = ? WHERE med_id = ?');
        $stmt->execute([$med_id, $med_name, $_GET['med_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the customers table
    $stmt = $pdo->prepare('SELECT * FROM medications WHERE med_id = ?');
    $stmt->execute([$_GET['med_id']]);
    $medication = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$medication) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Medication #<?=$medication['med_id']?></h2>
    <form action="medicationUpdate.php?med_id=<?=$medication['med_id']?>" method="post">
        <label for="id">Medication ID</label>
        <label for="name">Medication Name</label><br>
        <input type="text" name="med_id" placeholder="1" value="<?=$medication['med_id']?>" id="id">
        <input type="text" name="med_name" placeholder="John Doe" value="<?=$medication['med_name']?>" id="name">
        <input type="submit" value="Update">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
