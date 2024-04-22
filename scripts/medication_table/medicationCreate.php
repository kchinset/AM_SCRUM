<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch the last inserted ID from the prescriptions table
$stmt = $pdo->query('SELECT MAX(med_id) AS max_id FROM medications');
$max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];

// Increment the ID for the next prescription
$new_med_id = $max_id + 1;

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted, //we must check if the POST variables exist if not we //can default them to blank
    $med_id = isset($_POST['med_id']) ? $_POST['med_id'] : NULL;
    $med_name = isset($_POST['med_name']) ? $_POST['med_name'] : '';
    // Create the record
    $stmt = $pdo->prepare('INSERT INTO medications VALUES  (?, ?) ');
    $stmt->execute([$med_id, $med_name]);
     $msg = 'Created Successfully!';
}

?>
<?=template_header('Create')?>

<div class="content update">
	<h2>Create Medication</h2>
    <form action="medicationCreate.php" method="post">
        <label for="id">Medication ID</label>
        <label for="name">Medication Name</label><br>
        <input type="text" name="med_id" value="<?=$new_med_id?>" disabled>
        <input type="text" name="med_name" placeholder="Eulexin" id="name">
        <input type="submit" value="Create">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>