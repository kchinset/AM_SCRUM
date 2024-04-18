<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check that the order ID exists
if (isset($_GET['orderID'])) {
    // Select the record that is going to be deleted
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE orderID = ?');
    $stmt->execute([$_GET['orderID']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        exit('order doesn\'t exist with that ID!');
    }
    // Make sure the user confirms before deletion
    if (isset($_GET['confirm'])) {
        if ($_GET['confirm'] == 'yes') {
            // User clicked the "Yes" button, delete record
            $stmt = $pdo->prepare('DELETE FROM orders WHERE orderID = ?');
            $stmt->execute([$_GET['orderID']]);
            $msg = 'You have deleted the order!';
        } else {
            // User clicked the "No" button, redirect them back to the read page
            header('Location: orders_read.php');
            exit;
        }
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Delete')?>

<div class="content delete">
	<h2>Delete order #<?=$order['orderID']?></h2>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php else: ?>
	<p>Are you sure you want to delete order #<?=$order['orderID']?>?</p>
    <div class="yesno">
        <a href="orders_delete.php?orderID=<?=$order['orderID']?>&confirm=yes">Yes</a>
        <a href="orders_delete.php?orderID=<?=$order['orderID']?>&confirm=no">No</a>
    </div>
    <?php endif; ?>
</div>

<?=template_footer()?>
