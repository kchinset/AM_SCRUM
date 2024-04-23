<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the medication ID exists
if (isset($_GET['med_id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM medications WHERE med_id = ?');
    $stmt->execute([$_GET['med_id']]);
    $medication = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$medication) {
        exit('Medication doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM medications WHERE med_id = ?');
            $stmt->execute([$_GET['med_id']]);
            $msg = 'You have deleted the medication!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: medicationRead.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete medication #<?=$medication['med_id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete medication #<?=$medication['med_id']?>?</p>
    <div class="yesno">
        <a href="medicationDelete.php?med_id=<?=$medication['med_id']?>&confirm=yes">Yes</a>
        <a href="medicationDelete.php?med_id=<?=$medication['med_id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>