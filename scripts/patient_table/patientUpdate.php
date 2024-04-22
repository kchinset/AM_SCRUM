<?php
@include_once ('../../app_config.php');
@include_once (APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');
$pdo = pdo_connect_mysql();
$msg = '';
// Check if the contact id exists, for example //update.php?id=1 will get the contact with the id //of 1
if (isset($_GET['patient_id'])) {
    if (!empty($_POST)) {
        // This part is similar to the create.php, //but instead we update a record and not //insert
        $patient_id = isset($_POST['patient_id']) ? $_POST['patient_id'] : NULL;
        $first_name = isset($_POST['first_name']) ? $_POST['first_name'] : '';
        $last_name = isset($_POST['last_name']) ? $_POST['last_name'] : '';
        $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
        $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
        $genetics = isset($_POST['genetics']) ? $_POST['genetics'] : '';
        $diabetes = isset($_POST['diabetes']) ? $_POST['diabetes'] : '';
        $other_conditions = isset($_POST['other_conditions']) ? $_POST['other_conditions'] : '';
        // Update the record
        $stmt = $pdo->prepare('UPDATE patients SET patient_id = ?, first_name = ?, last_name = ?, gender = ?, birthdate = ?, genetics = ?, diabetes = ?, other_conditions = ? WHERE patient_id = ?');
        $stmt->execute([$patient_id, $first_name, $last_name, $gender, $birthdate, $genetics, $diabetes, $other_conditions, $_GET['patient_id']]);
        $msg = 'Updated Successfully!';
    }
    // Get the contact from the patients table
    $stmt = $pdo->prepare('SELECT * FROM patients WHERE patient_id = ?');
    $stmt->execute([$_GET['patient_id']]);
    $patient = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$patient) {
        exit('Contact doesn\'t exist with that ID!');
    }
} else {
    exit('No ID specified!');
}
?>
<?=template_header('Read')?>

<div class="content update">
	<h2>Update patient #<?=$patient['patient_id']?></h2>
    <form action="patientUpdate.php?patient_id=<?=$patient['patient_id']?>" method="post">
        <label for="id">ID</label>
        <label for="name">First Name</label>
        <input type="text" name="patient_id" placeholder="1" value="<?=$patient['patient_id']?>" id="patient_id">
        <input type="text" name="first_name" placeholder="John" value="<?=$patient['first_name']?>" id="first_name">
        <label for="last_name">Last Name</label>
        <label for="gender">Gender</label>
        <input type="text" name="last_name" placeholder="Doe" value="<?=$patient['last_name']?>" id="last_name">
        <input type="text" name="gender" placeholder="M/F/Other/Not Specified" value="<?=$patient['gender']?>" id="gender">
        <label for="birthdate">Birthdate</label>
        <label for="genetics">Genetics</label>
        <input type="text" name="birthdate" placeholder="04/26/2002" value="<?=$patient['birthdate']?>" id="birthdate">
        <input type="text" name="genetics" placeholder="ACGTACGTGT" value="<?=$patient['genetics']?>" id="genetics">
        <label for="diabetes">Diabetes</label>
        <label for="other_conditions">Other Conditions</label>
        <input type="text" name="diabetes" placeholder="04/26/2002" value="<?=$patient['diabetes']?>" id="diabetes">
        <input type="text" name="other_conditions" placeholder="" value="<?=$patient['other_conditions']?>" id="other_conditions">
        <input type="submit" value="Update">
    </form>
    <button onclick="window.history.back()" class="back-button">Back</button>
    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>
</div>

<?=template_footer()?>
