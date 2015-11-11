<?php

	require_once("../configGlobal.php");
	require_once("user.class.php");
	
	$database = "if15_toomloo_3";
	
	session_start();
	
	$mysqli = new mysqli($servername, $server_username, $server_password, $database);
	
	// saadan ühenduse classi ja loon uue classi
	$User = new User($mysqli);
	
	//var_dump($User->connection);
	
	

?>