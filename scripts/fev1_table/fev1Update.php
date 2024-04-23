<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['fev1_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $fev1_id = isset($_POST['fev1_id']) ? $_POST['fev1_id'] : NULL;
        $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id'] : '';
        $fev1_value = isset($_POST['fev1_value']) ? $_POST['fev1_value'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE fev1_results SET fev1_id = ?, visit_id = ?, fev1_value = ? WHERE fev1_id = ?');
        $stmt->execute([$fev1_id, $visit_id, $fev1_value, $_GET['fev1_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the customers table
    $stmt = $pdo->prepare('SELECT * FROM fev1_results WHERE fev1_id = ?');
    $stmt->execute([$_GET['fev1_id']]);
    $fev1 = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fev1) {
        exit('FEV1 Value doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update FEV1 Value #<?=$fev1['fev1_id']?></h2>
    <form action="fev1Update.php?fev1_id=<?=$fev1['fev1_id']?>" method="post">
        <label for="id">FEV1 ID</label>
        <input type="text" name="fev1_id" value="<?=$fev1['fev1_id']?>" id="fev1_id" disabled>
        <label for="name">Visit ID</label><br>
        <input type="text" name="visit_id" value="<?=$fev1['visit_id']?>" id="visit_id" disabled><br>
        <label for="name">FEV1 Value</label>
        <input type="text" name="fev1_value" value="<?=$fev1['fev1_value']?>" id="fev1_value">
        <input type="submit" value="Update">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
