<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    $custID = isset($_POST['custID']) && !empty($_POST['custID']) && $_POST['custID'] != 'auto' ? $_POST['custID'] : NULL;
    // Check if POST variable "name" exists, if not default //the value to blank, basically the same for all //variables
    $orderID = isset($_POST['orderID']) ? $_POST['orderID'] : '';
    $orderDescr = isset($_POST['orderDescr']) ? $_POST['orderDescr'] : '';
    $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO orders VALUES (?, ?, ?, ?)');
    $stmt->execute([$orderID, $orderDescr, $custID, $created]);
    // Output message
    $msg = 'Created Successfully!';
}
?>
<?=template_header('Create')?>

<div class="content update">
	<h2>Create Order</h2>
    <form action="orders_create.php" method="post">
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