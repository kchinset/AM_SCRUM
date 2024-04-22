<?php
@include_once('../app_config.php');
@include_once(APP_ROOT.APP_FOLDER_NAME . '/scripts/functions.php');

?>

<?=template_header('Home')?>



<div class="content">

	
	<div class="container_content_home">
	
    <div class="overlay_content_home">
		

		<span id="span_content_three_home">
		<span>
    <div class="content_three_home">
		<h4>Register Patient</h4><br>
    <a href="'.WEB_ROOT.APP_FOLDER_NAME.'/scripts/patient_table/patientCreate.php"><button class="buttons_home">REGISTER</button></a><br><br>
    </div>
		
		</span>

  </span>
			
    </div>
  </div>
	
</div>

<?=template_footer()?>
