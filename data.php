<?php
	require_once("functions.php");
	
	// kui kasutaja ei ole sisseloginud,
	// siis suunan tagasi
	if(!isset($_SESSION["logged_in_user_id"])){
		header("Location: login.php");
		
		// see katkestab faili edasise lugemise
		exit();
	}
	
	// kasutaja tahab välja logima
	
	if(isset($_GET["logout"])){
		// aadressireal on olemas muutuja logout
		
		//kustutame kõik sessoni muutujad ja peatame sessiooni
		session_destroy();
		
		header("Location: login.php");
	}
?>
<p>
	Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1">logi välja<a>	
</p>

