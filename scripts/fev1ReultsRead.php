<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our fev1_resultss table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM fev1_results ORDER BY fev1_id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$fev1_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of fev1_resultss, this is so we can determine whether there should be a next and previous button
$num_fev1_results = $pdo->query('SELECT COUNT(*) FROM fev1_results')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read FEV1 Results</h2>
	<a href="fev1ResultsCreate.php" class="create-contact">Create FEV1 Results</a>
	<table>
        <thead>
            <tr>
                <td>FEV1 ID</td>
                <td>Visit ID</td>
                <td>FEV1 Value</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fev1_results as $fev1_result): ?>
            <tr>
                <td><?=$fev1_result['fev1_id']?></td>
                <td><?=$fev1_result['visit_id']?></td>
                <td><?=$fev1_result['fev1_value']?></td>
                <td class="actions">
                    <a href="fev1ResultsUpdate.php?fev1_id=<?=$fev1_results['fev1_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="fev1ResultsDelete.php?fev1_id=<?=$fev1_results['fev1_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="fev1ResultsRead.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_fev1_results): ?>
		<a href="fev1ResultsRead.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>


<?=template_footer()?>