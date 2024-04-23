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
    $visit = $stmt->fetch(PDO::FETCH_ASSOC);

    // Prepare the SQL statement to fetch FEV values for the visit
    $stmt = $pdo->prepare('
        SELECT fev1_id, fev1_value
        FROM fev1_results
        WHERE visit_id = :visit_id');
    $stmt->execute([':visit_id' => $visit_id]);
    $fev_values = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // If visit ID is not provided, display an error message or redirect as needed
    // For example:
    // header('Location: error_page.php');
    // exit;
}

?>

<?=template_header('Read')?>

<div class="content read">
    <h2>More Information for Visit #<?=$visit['visit_id']?></h2>
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
            <tr>
                <td><?=$visit['visit_id']?></td>
                <td><?=$visit['visit_date']?></td>
                <td><?=$visit['patient_id']?></td>
                <td><?=$visit['patient_first_name'] . ' ' . $visit['patient_last_name']?></td>
                <td><?=$visit['doctor_id']?></td>
                <td><?=$visit['doctor_name']?></td>
                <td>
                    <?php
                    // Find the highest FEV1 value
                    $stmt = $pdo->prepare('SELECT MAX(fev1_value) AS max_fev1 FROM fev1_results WHERE visit_id = ?');
                    $stmt->execute([$visit_id]);
                    $max_fev1 = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo ($max_fev1['max_fev1'] !== null) ? $max_fev1['max_fev1'] : "No FEV1 value";
                    ?>
                </td>
                <td class="actions">
                    <a href="visitUpdate.php?visit_id=<?=$visit['visit_id']?>" class="edit"><i class="fas fa-pen fa-xs"></i></a>
                    <a href="visitDelete.php?visit_id=<?=$visit['visit_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<div class="content read">
    <h2>FEV1 Values for Visit #<?=$visit_id?></h2>
    <a href="/CIS4033/AM_SCRUM/scripts/fev1_table/fev1Create.php?visit_id=<?=$visit['visit_id']?>" class="create-contact">Add FEV1 Value</a>
    <table>
        <thead>
            <tr>
                <td>FEV1 ID</td>
                <td>FEV1 Value</td>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($fev_values as $fev_value): ?>
            <tr>
                <td><?=$fev_value['fev1_id']?></td>
                <td><?=$fev_value['fev1_value']?></td>
                <td class="actions">
                    <a href="/CIS4033/AM_SCRUM/scripts/fev1_table/fev1Delete.php?fev1_id=<?=$fev_value['fev1_id']?>" class="trash"><i class="fas fa-trash fa-xs"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
    <button onclick="window.history.back()" class="back-button">Back</button>
</div>

<?=template_footer()?>
