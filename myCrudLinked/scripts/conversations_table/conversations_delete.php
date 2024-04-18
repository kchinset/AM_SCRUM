<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the contact ID exists
if (isset($_GET['id'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM conversations WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$conversation) {
        exit('Conversation doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM conversations WHERE id = ?');
            $stmt->execute([$_GET['id']]);
            $msg = 'You have deleted the conversation!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: conversations_read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete Conversation #<?=$conversation['id']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete conversation #<?=$conversation['id']?>?</p>
    <div class="yesno">
        <a href="conversations_delete.php?id=<?=$conversation['id']?>&confirm=yes">Yes</a>
        <a href="conversations_delete.php?id=<?=$conversation['id']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
