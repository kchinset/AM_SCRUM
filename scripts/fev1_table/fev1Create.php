<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';

// Fetch the last inserted ID from the fev1_results table
$stmt = $pdo->query('SELECT MAX(fev1_id) AS max_id FROM fev1_results');
$max_id = $stmt->fetch(PDO::FETCH_ASSOC)['max_id'];
// Increment the ID for the next fev1 result
$new_fev1_id = $max_id + 1;

// Initialize variables
$visit_id = isset($_GET['visit_id']) ? $_GET['visit_id'] : null;

// Check if visit ID is not provided in the URL
if (!$visit_id) {
    // If visit ID is not provided, allow manual input
    $visit_id_input = isset($_POST['visit_id']) ? $_POST['visit_id'] : '';
} else {
    $visit_id_input = $visit_id;
}

// Check if POST data is not empty
if (!empty($_POST)) {
    // Post data not empty insert a new record
    // Set-up the variables that are going to be inserted
    $fev1_id = $new_fev1_id;
    $visit_id = $visit_id_input; // Use the manually input visit ID if provided
    $fev1_value = isset($_POST['fev1_value']) ? $_POST['fev1_value'] : '';

    // Insert the record
    $stmt = $pdo->prepare('INSERT INTO fev1_results (fev1_id, visit_id, fev1_value) VALUES (?, ?, ?)');
    $stmt->execute([$fev1_id, $visit_id, $fev1_value]);
    
    $msg = 'FEV1 result created successfully!';
}
?>

<?=template_header('Create')?>

<div class="content update">
    <h2>Create FEV1 Result</h2>
    <form action="fev1Create.php<?=isset($_GET['visit_id']) ? '?visit_id=' . $_GET['visit_id'] : ''?>" method="post">
        <label for="fev1_id">FEV1 ID</label>
        <input type="text" name="fev1_id" value="<?=$new_fev1_id?>" disabled>
        <?php if (!$visit_id): ?>
        <!-- Allow manual input for visit ID if not provided in the URL -->
        <label for="visit_id">Visit ID</label>
        <input type="number" name="visit_id" id="visit_id" value="<?=$visit_id_input?>">
        <?php else: ?>
        <!-- Display the visit ID if provided in the URL -->
        <label for="visit_id">Visit ID</label>
        <input type="text" name="visit_id" value="<?=$visit_id?>" disabled>
        <?php endif; ?>
        <label for="fev1_value">FEV1 Value</label>
        <input type="number" name="fev1_value" id="fev1_value">
        <input type="submit" value="Create">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
        <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
