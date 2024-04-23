<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');

// Connect to MySQL database
$pdo = pdo_connect_mysql();

$msg = ''; // To store the message whether the insert was successful or not

// Check if POST data is not empty (this is the case when the form is submitted)
if (!empty($_POST)) {
    // The POST data is not empty, insert a new record
    $fev1_id = isset($_POST['fev1_id']) ? $_POST['fev1_id'] : '';
    $visit_id = isset($_POST['visit_id']) ? $_POST['visit_id'] : '';
    $fev1_value = isset($_POST['fev1_value']) ? $_POST['fev1_value'] : '';
    
    // Insert new FEV1 value into the fev1_results table
    $stmt = $pdo->prepare('INSERT INTO fev1_results (visit_id, fev1_value) VALUES (?, ?)');
    $stmt->execute([$visit_id, $fev1_value]);
    $msg = 'FEV1 Value Created Successfully!';
}

// Check if visit_id is set in GET parameters
if (isset($_GET['visit_id'])) {
    // Fetch visit details
    $stmt = $pdo->prepare('SELECT * FROM visits WHERE visit_id = ?');
    $stmt->execute([$_GET['visit_id']]);
    $visit = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$visit) {
        exit('Visit not found!');
    }


    // Fetch FEV1 values for the visit
    $stmt = $pdo->prepare('SELECT * FROM fev1_results WHERE visit_id = ? ORDER BY fev1_value DESC' );
    $stmt->execute([$_GET['visit_id']]);
    $fev1_results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch patient details
    $stmt = $pdo->prepare('SELECT * FROM patients WHERE patient_id = ?');
    $stmt->execute([$visit['patient_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);

   }   else {
        exit('No visit ID specified!');
    }

?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Create/Add FEV1 Value for the Visit #<?=$visit['visit_id']?></h2>
    <!-- Display FEV1 Results -->
    <form action="" method="post">
        <input type="hidden" name="visit_id" value="<?=($visit['visit_id'])?>">
        <label for="fev1_value">FEV1 Value</label>
        <input type="number" name="fev1_value" id="fev1_value" required><br>
        <input type="submit"class="create-contact" value="Add FEV1 Value">
    </form>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
    <button onclick="window.history.back()" class="back-button">Back</button>
</div>

<?=template_footer()?>