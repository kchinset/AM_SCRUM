<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our medications table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM medication ORDER BY patient_id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$medication = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of medications, this is so we can determine whether there should be a next and previous button
$num_medication = $pdo->query('SELECT COUNT(*) FROM medication')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read medication</h2>
	<a href="medicationCreate.php" class="create-contact">Create medication</a>
	<table>
        <thead>
            <tr>
                <td>Patient ID</td>
                <td>Vest</td>
                <td>Acapella</td>
                <td>Inhaled Tobitis</td>
                <td>Inhaled Colistin</td>
                <td>Hypertonic Saline</td>
                <td>Azithromycin</td>
                <td>Clarithromycin</td>
                <td>Inhaled Gentamicin</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($medication as $medication): ?>
            <tr>
                <td><?=$medication['patient_id']?></td>
                <td><?=$medication['vest']?></td>
                <td><?=$medication['acapella']?></td>
                <td><?=$medication['inhaled_tobi']?></td>
                <td><?=$medication['inhaled_colistin']?></td>
                <td><?=$medication['hypertonic_saline']?></td>
                <td><?=$medication['azithromycin']?></td>
                <td><?=$medication['clarithromycin']?></td>
                <td><?=$medication['inhaled_gentamicin']?></td>
                <td class="actions">
                    <a href="medicationUpdate.php?id=<?=$medication['patient_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="medicationDelete.php?id=<?=$medication['patient_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="medicationRead.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_medication): ?>
		<a href="medicationRead.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>


<?=template_footer()?>