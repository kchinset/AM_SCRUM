<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our visits table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM visits ORDER BY visit_date DESC LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of visits, this is so we can determine whether there should be a next and previous button
$num_visits = $pdo->query('SELECT COUNT(*) FROM visits')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read Visits</h2>
	<a href="visitCreate.php" class="create-contact">Create visit</a>
	<table>
        <thead>
            <tr>
                <td>Visit ID</td>
                <td>Visit Date</td>
                <td>Patient ID</td>
                <td>Doctor ID</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visits as $visit): ?>
            <tr>
                <td><?=$visit['visit_id']?></td>
                <td><?=$visit['visit_date']?></td>
                <td><?=$visit['patient_id']?></td>
                <td><?=$visit['doctor_id']?></td>
                <td class="actions">
                    <a href="visitUpdate.php?visit_id=<?=$visit['visit_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="visitDelete.php?visit_id=<?=$visit['visit_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="visitRead.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_visits): ?>
		<a href="visitRead.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>


<?=template_footer()?>