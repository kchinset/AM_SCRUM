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
$stmt = $pdo->prepare('SELECT * FROM customers ORDER BY custId LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of contacts, this is so we can determine whether there should be a next and previous button
$num_customers = $pdo->query('SELECT COUNT(*) FROM customers')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read Customers</h2>
	<a href="customers_create.php" class="create-contact">Create Customer</a>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Name</td>
                <td>Email</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?=$customer['custId']?></td>
                <td><?=$customer['custName']?></td>
                <td><?=$customer['custEmail']?></td>
                <td class="actions">
                    <a href="customers_update.php?custId=<?=$customer['custId']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="customers_delete.php?custId=<?=$customer['custId']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="customers_read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_customers): ?>
		<a href="customers_read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
    <a href="../orders_table/orders_read.php" class="create-contact">Orders</a>
</div>

<?=template_footer()?>