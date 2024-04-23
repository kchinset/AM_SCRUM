<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['visit_id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM visits WHERE visit_id = ?');
    $stmt->execute([$_GET['visit_id']]);
    $visit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$visit) {
        exit('CVisit doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM visits WHERE visit_id = ?');
            $stmt->execute([$_GET['visit_id']]);
            $msg = 'You have deleted the visit!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: visitRead.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete visit #<?=$visit['visit_id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <a href="visitRead.php" class="back-button">Back</a>
    <?php else: ?>
	<p>Are you sure you want to delete visit #<?=$visit['visit_id']?>?</p>
    <div class="yesno">
        <a href="visitDelete.php?visit_id=<?=$visit['visit_id']?>&confirm=yes">Yes</a>
        <a href="visitDelete.php?visit_id=<?=$visit['visit_id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
