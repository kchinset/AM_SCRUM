<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    $id = isset($_POST['id']) && !empty($_POST['id']) && $_POST['id'] != 'auto' ? $_POST['id'] : NULL;
    /*Check if POST variable //"conversationSummary" exists, if not default //the value to blank, basically the same for //all variables*/
    $conversationSummary = isset($_POST['conversationSummary']) ? $_POST['conversationSummary'] : '';
    $contact_id = isset($_POST['contact_id']) ? $_POST['contact_id'] : '';
    $created = isset($_POST['created']) ? $_POST['created'] : date('Y-m-d H:i:s');
    // Insert new record into the contacts table
    $stmt = $pdo->prepare('INSERT INTO conversations VALUES (?, ?, ?, ? )');
    $stmt->execute([$id, $conversationSummary, $contact_id, $created]);
    // Output message
    $msg = 'Created Successfully!';
}
?>
<?=template_header('Create')?>

<div class="content update">
	<h2>Create Conversation</h2>
    <form action="conversations_create.php" method="post">
        <label for="id">ID</label>
        <label for="conversationSummary">Summary</label>
        <input type="text" name="id" placeholder="26" value="auto" id="id">
        <input type="text" name="conversationSummary" placeholder="Summarize Conversation" id="conversationSummary">
        <?php
        $stmt = $pdo->query("SELECT id, name FROM contacts");
        $contacts = $stmt->fetchAll(PDO::FETCH_ASSOC);
      
        ?>
        <label for="contact_id">Contact</label>
        <label for="created">Created</label>
        
         <select name="contact_id" id="contact_id">
            <?php foreach($contacts as $contact) : ?>
            <option value="<?php echo $contact['id']; ?>"><?php echo $contact['id'] . ' - ' . $contact['name']; ?></option>
            <?php endforeach; ?>
        </select>
        
        <input type="datetime-local" name="created" value="<?=date('Y-m-d\TH:i')?>" id="created">
        <input type="submit" value="Create">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>