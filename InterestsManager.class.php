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
	
	
	function createDropdown(){
		
		$html = "";
		
		$html .= '<select name="new_dd_selection">';
		
		//$html .= '<option>1</option>';
		//$html .= '<option>2</option>';
		//$html .= '<option>3</option>';
		//$html .= '<option>4</option>';
		
		//$stmt = $this->connection->prepare("SELECT id, name FROM interests");
		$stmt = $this->connection->prepare("SELECT interests.id, interests.name FROM interests LEFT JOIN user_sample_interests ON interests.id = user_sample_interests.interests_id WHERE user_sample_interests.user_sample_id IS NULL OR user_sample_interests.user_sample_id != ?");
		$stms->bind_param("i", $this->user_id);
		$stmt->bind_result($id, $name);
		$stmt->execute();
		
		// iga rea kohta ab's teen midagi
		while($stmt->fetch()){
			$html .= '<option value="'.$id.'">'.$name.'</option>';
			
			
		}
		
		$html .= '</select>';
		return $html;
		
	}
	
	function addUserInterest($new_interest_id){
		
		$response = new StdClass();
		
		// kas sellel kasutajal on see huviala
		$stmt = $this->connection->prepare("SELECT id FROM user_sample_interests WHERE user_sample_id=? AND interests_id=?");
		$stmt->bind_param("ii", $this->user_id, $new_interest_id);
		$stmt->bind_result($id);
		$stmt->execute();
		
		// kas sain rea andmeid
		if($stmt->fetch()){
			
			// annan errori, et selline email olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sul on see huviala juba olemas";
			
			$response->error = $error;
			
			// kõik, mis on pärast returni enam ei käivitata
			return $response;
		}
		
		// panen eelmise päringu kinni
		$stmt->close();
	
		$stmt = $this->connection->prepare("INSERT INTO user_sample_interests (user_sample_id, interests_id) VALUES (?,?)");
		$stmt->bind_param("ii", $this->user_id, $new_interest_id);
		
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
	
	function getUserInterests(){
		
		$html = '';
		$stmt = $this->connection->prepare("SELECT interests.name FROM user_sample_interests INNER JOIN interests ON user_sample_interests.interests_id = interests.id WHERE user_sample_interests.user_sample_id = ?");
		$stmt->bind_param("i", $this->user_id);
		$stmt->bind_result($name);
		$stmt->execute();
		
		//iga rea kohta
		while($stmt->fetch()){
			$html .= '<p>'.$name.'</p>';
			
		}
		
		return $html;
		
	}
	
}
	




?>