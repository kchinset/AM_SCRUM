<?php
@include_once('app_config.php');
$landingPage= WEB_ROOT.APP_FOLDER_NAME.'/scripts/landingPage.php';
//echo $landingPage;
header("location:$landingPage");
?>  