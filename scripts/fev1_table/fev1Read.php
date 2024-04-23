<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 10;
// Prepare the SQL statement and get records from our fev1s table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM fev1_results ORDER BY fev1_id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$fev1 = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of fev1s, this is so we can determine whether there should be a next and previous button
$num_fev1 = $pdo->query('SELECT COUNT(*) FROM fev1_results')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read FEV Values</h2>
	<a href="fev1Create.php" class="create-contact">Create FEV1 Value</a>
	<table>
        <thead>
            <tr>
                <td>FEV ID</td>
                <td>Visit ID</td>
                <td>FEV Value</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fev1 as $fev1): ?>
            <tr>
                <td><?=$fev1['fev1_id']?></td>
                <td><?=$fev1['visit_id']?></td>
                <td><?=$fev1['fev1_value']?></td>
                <td class="actions">
                    <a href="fev1Update.php?fev1_id=<?=$fev1['fev1_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="fev1Delete.php?fev1_id=<?=$fev1['fev1_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="fev1Read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_fev1): ?>
		<a href="fev1Read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>


<?=template_footer()?>