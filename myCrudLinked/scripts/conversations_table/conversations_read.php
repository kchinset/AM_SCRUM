<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
// Connect to MySQL database
$pdo = pdo_connect_mysql();
// Get the page via GET request (URL param: page), if non exists default the page to 1
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
// Number of records to show on each page
$records_per_page = 5;
// Prepare the SQL statement and get records from our contacts table, LIMIT will determine the page
$stmt = $pdo->prepare('SELECT * FROM conversations ORDER BY id LIMIT :current_page, :record_per_page');
$stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
$stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch the records so we can display them in our template.
$conversations = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Get the total number of conversations, this is so we can determine whether there should be a next and previous button
$num_conversations = $pdo->query('SELECT COUNT(*) FROM conversations')->fetchColumn();
?>
<?=template_header('Read')?>

<div class="content read">
	<h2>Read Conversations</h2>
	<a href="conversations_create.php" class="create-contact">Create Conversations</a>
	<table>
        <thead>
            <tr>
                <td>#</td>
                <td>Summary</td>
                <td>Contact ID</td>
                <td>Created</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($conversations as $contact): ?>
            <tr>
                <td><?=$contact['id']?></td>
                <td><?=$contact['conversationSummary']?></td>
                <td><?=$contact['contact_id']?></td>
                <td><?=$contact['created']?></td>
                <td class="actions">
                    <a href="conversations_update.php?id=<?=$contact['id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="conversations_delete.php?id=<?=$contact['id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="conversations_read.php?page=<?=$page-1?>"><i class="fas fa-angle-double-left fa-sm"></i></a>
		<?php endif; ?>
		<?php if ($page*$records_per_page < $num_conversations): ?>
		<a href="conversations_read.php?page=<?=$page+1?>"><i class="fas fa-angle-double-right fa-sm"></i></a>
		<?php endif; ?>
	</div>
</div>

<?=template_footer()?>