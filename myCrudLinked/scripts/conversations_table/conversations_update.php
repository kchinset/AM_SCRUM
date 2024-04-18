<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $id = isset($_POST['id']) ? $_POST['id'] : NULL;
        $conversationSummary = isset($_POST['conversationSummary']) ? $_POST['conversationSummary'] : '';
        $contact_id = isset($_POST['contact_id']) ? $_POST['contact_id'] : '';
        $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
        // Update the record
        $stmt = $pdo->prepare('UPDATE conversations SET id = ?, conversationSummary = ?, contact_id = ?, created = ? WHERE id = ?');
        $stmt->execute([$id, $conversationSummary, $contact_id, $created, $_GET['id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the contacts table
    $stmt = $pdo->prepare('SELECT * FROM conversations WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $conversation = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$conversation) {
        exit('Conversation doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update Conversation #<?=$conversation['id']?></h2>
    <form action="conversations_update.php?id=<?=$conversation['id']?>" method="post">
        <label for="id">ID</label>
         <label for="conversationSummary">Summary</label>
        <input type="text" name="id" placeholder="1" value="<?=$conversation['id']?>" id="id">
        <input type="text" name="conversationSummary" placeholder="Summarize Conversation" 
               value="<?=$conversation['conversationSummary']?>" id="conversationSummary">
       
         <?php
        $stmt = $pdo->query("SELECT id, name FROM contacts");
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <label for="contact_id">Contact</label>
        <label for="created">Created</label>
        <select name="contact_id" id="contact_id">
            <option value="<?=$conversation['contact_id']?>" selected><?=$conversation['contact_id']?></option>
            <?php foreach($contacts as $contact) : ?>
            <option value="<?php echo $contact['id']; ?>"><?php echo $contact['id'] . ' - ' . $contact['name']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i', strtotime($conversation['created']))?>" id="created">
        <input type="submit" value="Update">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
