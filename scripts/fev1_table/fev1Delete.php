<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the FEV1 ID exists
if (isset($_GET['fev1_id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM fev1_results WHERE fev1_id = ?');
    $stmt->execute([$_GET['fev1_id']]);
    $fev1 = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$fev1) {
        exit('FEV1 Value doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM fev1_results WHERE fev1_id = ?');
            $stmt->execute([$_GET['fev1_id']]);
            $msg = 'You have deleted the FEV1 value!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: fev1Read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
    <h2>Delete FEV1 #<?=$fev1['fev1_id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
    <p>Are you sure you want to delete FEV1 #<?=$fev1['fev1_id']?>?</p>
    <div class="yesno">
        <a href="fev1Delete.php?fev1_id=<?=$fev1['fev1_id']?>&confirm=yes">Yes</a>
        <a href="fev1Delete.php?fev1_id=<?=$fev1['fev1_id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
