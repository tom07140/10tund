<?php
	require_once("functions.php");
	require_once("InterestsManager.class.php");
	
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
	
	// uus instants klassist
	$InterestsManager = new InterestsManager($mysqli, $_SESSION["logged_in_user_id"]);
	
	if(isset($_GET["add_interest"])){

		$add_new_response = $InterestsManager->addInterest($_GET["add_interest"]);
		
	}
	 // create if end
 function cleanInput($data) {
  	$data = trim($data);
  	$data = stripslashes($data);
  	$data = htmlspecialchars($data);
  	return $data;
  }
?>
<p>
	Tere, <?=$_SESSION["logged_in_user_email"];?>
	<a href="?logout=1">logi välja<a>	
</p>

<h2>Lisa huvi</h2>
  
  <?php if(isset($add_new_response->error)): ?>
  
	<p style="color:red"> <?=$add_new_response->error->message;?> </p>
  
  <?php elseif(isset($add_new_response->success)): ?>
  
	<p style="color:green"> <?=$add_new_response->success->message;?> </p>
  
  <?php endif; ?>
  
  <form>
  	<input name="add_interest"><br><br>
  	<input type="submit">
  </form>
