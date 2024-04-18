<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our customers table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM orders ORDER BY orderID LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_orders = $pdo->query('SELECT COUNT(*) FROM orders')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read Orders</h2>
	<a href="orders_create.php" class="create-contact">Create Order</a>
	<table>
        <thead>
            <tr>
                <td>Order ID</td>
                <td>Description</td>
                <td>Customer ID</td>
                <td>Date Created</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
            <tr>
                <td><?=$order['orderID']?></td>
                <td><?=$order['orderDescr']?></td>
                <td><?=$order['custID']?></td>
                <td><?=$order['created']?></td>
                <td class="actions">
                    <a href="orders_update.php?orderID=<?=$order['orderID']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="orders_delete.php?orderID=<?=$order['orderID']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="orders_read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_orders): ?>
		<a href="orders_read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>