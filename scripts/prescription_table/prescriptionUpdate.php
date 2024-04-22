<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['presc_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $presc_id = isset($_POST['presc_id']) ? $_POST['presc_id'] : NULL;
        $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : '';
        $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id'] : '';
        $presc_dosage = isset($_POST['presc_dosage']) ? $_POST['presc_dosage'] : '';
        $date_received = isset($_POST['date_received']) ? $_POST['date_received'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE prescriptions SET presc_id = ?, med_id = ?, visit_id = ?, presc_dosage = ?, date_received = ? WHERE presc_id = ?');
        $stmt->execute([$presc_id, $med_id, $visit_id, $presc_dosage, $date_received, $_GET['presc_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the prescriptionss table
    $stmt = $pdo->prepare('SELECT * FROM prescriptions WHERE presc_id = ?');
    $stmt->execute([$_GET['presc_id']]);
    $prescription = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$prescription) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Prescription #<?=$prescription['presc_id']?></h2>
    <form action="prescriptionUpdate.php?presc_id=<?=$prescription['presc_id']?>" method="post">
        <label for="presc_id">Prescription ID</label>
        <label for="med_id">Med ID</label>
        <input type="text" name="presc_id" placeholder="1" value="<?=$prescription['presc_id']?>" id="presc_id">
        <input type="text" name="med_id" placeholder="Med ID" value="<?=$prescription['med_id']?>" id="med_id">

        <label for="visit_id">Visit ID</label>
        <label for="presc_dosage">Prescription Dosage</label>
        <input type="text" name="visit_id" placeholder="2" value="<?=$prescription['visit_id']?>" id="visit_id">
        <input type="text" name="presc_dosage" placeholder="50mg" value="<?=$prescription['presc_dosage']?>" id="presc_dosage">

        <label for="date_received">Date Received</label>
        <label for=""></label>
        <input type="text" name="date_received" placeholder="1" value="<?=$prescription['date_received']?>" id="date_received">
        <input type="submit" value="Update">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>