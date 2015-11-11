<?php
class User{

	// private - klassi sees
	private $connection;

	// klassi loomisel(new User)
	function __construct($mysqli){
		// this tähendab selle klassi muutujat
		$this->connection = $mysqli;
	}
	
	function createUser($hash, $create_email){

		// teen objekti
		// seal on error, ->id ja ->message
		// või success ja sellel on ->message
		$response = new StdClass();
		
		// kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		// kas sain rea andmeid
		if($stmt->fetch()){
			
			// annan errori, et selline email olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellise epostiga kasutaja on juba olemas!";
			
			$response->error = $error;
			
			// kõik, mis on pärast returni enam ei käivitata
			return $response;
		}
		
		// panen eelmise päringu kinni
		$stmt->close();
	
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		
		// sai edukalt salvestatud
		if($stmt->execute()){
			
			$success = new StdClass();
			$success->message = "Kasutaja edukalt loodud!";
			
			$response->success = $success;
			
		}else{
			
			// midagi läks katki
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki";
			
			$response->error = $error;
			
			
		}
		$stmt->close();
		return $response;
	}
	
	function loginUser($email, $hash){
		
		$response = new StdClass();
		
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $email);
				
		//muutujad tulemustele
		$stmt->bind_result($id);
		$stmt->execute();
				
		//Kontrollin kas tulemusi leiti
		if(!$stmt->fetch()){
			// ab'i oli midagi
			// echo "Email ja parool õiged, kasutaja id=".$id_from_db;
			
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellist kasutajat ei ole!";
			$response->error = $error;
			
			return $response;
			
			/*
			// tekitan sessiooni muutujad
			$_SESSION["logged_in_user_id"] = $id_from_db;
			$_SESSION["logged_in_user_email"] = $email_from_db;
			
			// suunan data.php lehele
			header("Location: data.php");
			*/
		}
		$stmt->close();
		
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");
		$stmt->bind_param("ss", $email, $hash);
		$stmt->bind_result($id_from_db, $email_from_db);
		$stmt->execute();
			 
		if($stmt->fetch()){

			$success = new StdClass();
			$success->message = "Kasutaja edukalt sisse logitud!";
			
			$response->success = $success;
			
			$user = new StdClass();
			$user->id = $id_from_db;
			$user->email = $email_from_db;
			
			$response->user = $user;
			
		}else{
			
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Parool vale!";
			
			$response->error = $error;
			
		}
				
		$stmt->close();
		return $response;
		
	}

	
	
}?>