<?php 
//application folder name on your web root or htdocs folder
define('APP_ROOT',$_SERVER['DOCUMENT_ROOT']);
//DEBUG echo (APP_ROOT)."      <br>";
define('APP_FOLDER_NAME', '/CIS4033/AM_SCRUM');
define('WEB_ROOT','http://'.$_SERVER['SERVER_NAME'].':8080');
//DEBUG echo(WEB_ROOT);
define('DSN1', 'mysql:host=localhost;dbname=ACMEMed_SCRUM');
define('USER1','root');
define('PASSWD1','');
?>
