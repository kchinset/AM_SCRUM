<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our doctors table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM doctors ORDER BY doctor_id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of doctors, this is so we can determine whether there should be a next and previous button
$num_doctors = $pdo->query('SELECT COUNT(*) FROM doctors')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read Doctor</h2>
	<a href="doctorCreate.php" class="create-contact">Create Doctor</a>
	<table>
        <thead>
            <tr>
                <td>Doctor ID</td>
                <td>Doctor Name</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($doctors as $doctor): ?>
            <tr>
                <td><?=$doctor['doctor_id']?></td>
                <td><?=$doctor['doctor_name']?></td>
                <td class="actions">
                    <a href="doctorUpdate.php?doctor_id=<?=$doctor['doctor_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="docotrDelete.php?doctor_id=<?=$doctor['doctor_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="doctorRead.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_doctors): ?>
		<a href="doctorRead.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>


<?=template_footer()?>