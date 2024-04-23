<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');

// Connect to MySQL database
$pdo = pdo_connect_mysql();

// Initialize variables
$visit_id = isset($_GET['visit_id']) ? $_GET['visit_id'] : null;

// If visit ID is provided in the URL, fetch only that visit
if ($visit_id) {
    // Prepare the SQL statement to fetch the specific visit
    $stmt = $pdo->prepare('
        SELECT v.*, 
               p.first_name AS patient_first_name, 
               p.last_name AS patient_last_name,
               d.doctor_name
        FROM visits v 
        JOIN patients p ON v.patient_id = p.patient_id 
        JOIN doctors d ON v.doctor_id = d.doctor_id
        WHERE v.visit_id = :visit_id');
    $stmt->execute([':visit_id' => $visit_id]);
    $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If visit ID is not provided, fetch all visits
    // Get the page via GET request (URL param: page), if non exists default the page to 1
    $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
    // Number of records to show on each page
    $records_per_page = 5;
    // Prepare the SQL statement and get records from our visits table, LIMIT will determine the page
    $stmt = $pdo->prepare('
        SELECT v.*, 
               p.first_name AS patient_first_name, 
               p.last_name AS patient_last_name,
               d.doctor_name
        FROM visits v 
        JOIN patients p ON v.patient_id = p.patient_id 
        JOIN doctors d ON v.doctor_id = d.doctor_id
        ORDER BY v.visit_date DESC 
        LIMIT :current_page, :record_per_page');
    $stmt->bindValue(':current_page', ($page-1)*$records_per_page, PDO::PARAM_INT);
    $stmt->bindValue(':record_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();
    // Fetch the records so we can display them in our template.
    $visits = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<?=template_header('Read')?>

<div class="content read">
    <h2>Read Visits</h2>
    <a href="visitCreate.php" class="create-contact">Create Visit</a>
    <table>
        <thead>
            <tr>
                <td>Visit ID</td>
                <td>Visit Date</td>
                <td>Patient ID</td>
                <td>Patient Name</td>
                <td>Doctor ID</td>
                <td>Doctor Name</td>
                <td>Highest FEV1 Value</td>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($visits as $visit): ?>
            <?php
            $stmt = $pdo->prepare('SELECT MAX(fev1_value) AS max_fev1 FROM fev1_results WHERE visit_id = ?');
            $stmt->execute([$visit['visit_id']]);
            $max_fev1 = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <tr>
                <td><?=$visit['visit_id']?></td>
                <td><?=$visit['visit_date']?></td>
                <td><?=$visit['patient_id']?></td>
                <td><?=$visit['patient_first_name'] . ' ' . $visit['patient_last_name']?></td>
                <td><?=$visit['doctor_id']?></td>
                <td><?=$visit['doctor_name']?></td>
                <td><?=($max_fev1['max_fev1'] !== null) ? $max_fev1['max_fev1'] : "No FEV1 value"?></td>

                <td class="actions">
                    <a href="visitUpdate.php?visit_id=<?=$visit['visit_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="visitDelete.php?visit_id=<?=$visit['visit_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?=template_footer()?>
