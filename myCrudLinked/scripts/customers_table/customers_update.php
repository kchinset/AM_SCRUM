<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['custId'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $custId = isset($_POST['custId']) ? $_POST['custId'] : NULL;
        $custName = isset($_POST['custName']) ? $_POST['custName'] : '';
        $custEmail = isset($_POST['custEmail']) ? $_POST['custEmail'] : '';
        $custPassword = isset($_POST['custPassword']) ? $_POST['custPassword'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE customers SET custId = ?, custName = ?, custEmail = ?, custPassword = ? WHERE custId = ?');
        $stmt->execute([$custId, $custName, $custEmail, $custPassword, $_GET['custId']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the customers table
    $stmt = $pdo->prepare('SELECT * FROM customers WHERE custId = ?');
    $stmt->execute([$_GET['custId']]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$customer) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Customer #<?=$customer['custId']?></h2>
    <form action="customers_update.php?custId=<?=$customer['custId']?>" method="post">
        <label for="id">ID</label>
        <label for="name">Name</label>
        <input type="text" name="custId" placeholder="1" value="<?=$customer['custId']?>" id="id">
        <input type="text" name="custName" placeholder="John Doe" value="<?=$customer['custName']?>" id="name">
        <label for="email">Email</label>
        <label for="phone">Phone</label>
        <input type="text" name="custEmail" placeholder="johndoe@example.com" value="<?=$customer['custEmail']?>" id="email">
        <input type="text" name="custPassword" placeholder="2025550143" value="<?=$customer['custPassword']?>" id="phone">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
