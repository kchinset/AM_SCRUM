<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['orderID'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $orderID = isset($_POST['orderID']) ? $_POST['orderID'] : NULL;
        $orderDescr = isset($_POST['orderDescr']) ? $_POST['orderDescr'] : '';
        $custID = isset($_POST['custID']) ? $_POST['custID'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        // Update the record
        $stmt = $pdo->prepare('UPDATE orders SET orderID = ?, orderDescr = ?, custID = ?, created = ? WHERE orderID = ?');
        $stmt->execute([$orderID, $orderDescr, $custID, $created, $_GET['orderID']]);
        $msg = 'Updated Successfully!';
    }
    // Get the order from the orders table
    $stmt = $pdo->prepare('SELECT * FROM orders WHERE orderID = ?');
    $stmt->execute([$_GET['orderID']]);
    $order = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$order) {
        exit('order doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update order #<?=$order['orderID']?></h2>
    <form action="orders_update.php?orderID=<?=$order['orderID']?>" method="post">
        <label for="custID">Order ID</label>
        <label for="orderDescr">Order Description</label>
        <input type="text" name="orderID" placeholder="1" value="auto" id="orderID">
        <input type="text" name="orderDescr" placeholder="Enter Description" id="email">
        <label for="custID">Customer ID</label>
        <label for="created">Date Created</label>
        <input type="text" name="custID" placeholder="Customer ID" id="custID">
        <input type="date" name="created" id="created">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
