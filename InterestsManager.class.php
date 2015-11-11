<?php
	
	// InterestsManager.php
	
class InterestsManager {
	
	private $connection;
	private $user_id;
	
	// kui tekitan new, siis käivitatakse see funktsioon
	function __construct($mysqli, $user_id_from_session){

		// selle klassi muutuja
		$this->connection = $mysqli;
		$this->user_id = $user_id_from_session;
		
		echo "Huvialade haldus käivitatud, kasutaja=".$this->user_id;
	
	}
	
	function addInterest($add_interest){

		// teen objekti
		// seal on error, ->id ja ->message
		// või success ja sellel on ->message
		$response = new StdClass();
		
		// kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM interests WHERE name=?");
		$stmt->bind_param("s", $add_interest);
		$stmt->bind_result($id);
		$stmt->execute();
		
		// kas sain rea andmeid
		if($stmt->fetch()){
			
			// annan errori, et selline email olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Huviala <strong>".$add_interest."</strong> on juba olemas!";
			
			$response->error = $error;
			
			// kõik, mis on pärast returni enam ei käivitata
			return $response;
		}
		
		// panen eelmise päringu kinni
		$stmt->close();
	
		$stmt = $this->connection->prepare("INSERT INTO interests (name) VALUES (?)");
		$stmt->bind_param("s", $add_interest);
		
		// sai edukalt salvestatud
		if($stmt->execute()){
			
			$success = new StdClass();
			$success->message = "Huviala edukalt lisatud!";
			
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
	
}
	




?>