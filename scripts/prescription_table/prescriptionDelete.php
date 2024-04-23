<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['presc_id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM prescriptions WHERE presc_id = ?');
    $stmt->execute([$_GET['presc_id']]);
    $presc = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$presc) {
        exit('Prescription doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM prescriptions WHERE presc_id = ?');
            $stmt->execute([$_GET['presc_id']]);
            $msg = 'You have deleted the prescription!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: prescriptionRead.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Prescription #<?=$presc['presc_id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete prescription #<?=$presc['presc_id']?>?</p>
    <div class="yesno">
        <a href="prescriptionDelete.php?presc_id=<?=$presc['presc_id']?>&confirm=yes">Yes</a>
        <a href="prescriptionDelete.php?presc_id=<?=$presc['presc_id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
