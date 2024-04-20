<?php

function pdo_connect_mysql() {
    @include_once ('../app_config.php');    
    try {
    	return new PDO(DSN1.';charset=utf8', USER1, PASSWD1);
    } catch (PDOException $exception) {
    	// If there is an error with the connection, stop the script and display the error.
    	exit('Failed to connect to database!');
    }
}
function template_header($title) {
    @include_once ('../app_config.php'); 
    echo'
    <!DOCTYPE html>
    <html>
	   <head>
		<meta charset="utf-8">
		<title>Scrum Project</title>
		<link href="'.WEB_ROOT.APP_FOLDER_NAME.'/styles/style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	   </head>
	   <body>
            <nav class="navtop">
    	       <div>
    		      <h1>ACME Medical Project</h1>
                  <a href="'.WEB_ROOT.APP_FOLDER_NAME.'/scripts/landingPage.php"><i class="fas fa-home"></i>Home</a>
    		      <a href="'.WEB_ROOT.APP_FOLDER_NAME.'/scripts/patient_table/patient_read.php"><i class="fas fa-address-book"></i>Patient Info</a>
							<a href="'.WEB_ROOT.APP_FOLDER_NAME.'/scripts/medication_table/medication_read.php"><i class="fas fa-address-book"></i>Medications</a>
							<a href="'.WEB_ROOT.APP_FOLDER_NAME.'/scripts/visit_table/visit_read.php"><i class="fas fa-address-book"></i>Visits/FEV1</a>
    	       </div>
            </nav>
';
}
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
?>
