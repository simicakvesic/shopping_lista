<?php
 
	$method = $_SERVER['REQUEST_METHOD'];
	$input = json_decode(file_get_contents('php://input'),true);
	
	$host = "localhost";
	$user = "root";
	$pass = "";
	$db = "shoppinglista";

	$dbConnection = mysqli_connect($host, $user, $pass);

	mysqli_set_charset($dbConnection,"utf8");
	$connection = mysqli_select_db($dbConnection, $db );

	// create SQL based on HTTP method
	switch ($method) {
		case 'GET':
			$query = "SELECT korpaID, nazivKorpe FROM korpa"; 
			break;
		case 'POST':
			$query = "INSERT INTO korpa (nazivKorpe) VALUES ('" . $input['name'] . "')"; 
			break;
		case 'DELETE':
			$query = "DELETE FROM korpa WHERE korpaID=" . $input['id'] ; break;
	}

	$sql = mysqli_query($dbConnection,$query);
	
	
	if ($method == 'GET'){	
		$result= array();
		while ($obj = mysqli_fetch_object($sql)) {
			$result[] = array('id' => $obj->korpaID,
							  'naziv' => $obj->nazivKorpe,
				);
			}
			echo '' . json_encode($result) . '';
	}else if($method == 'POST'){
		$ID = mysqli_insert_id($dbConnection);
		$result[] = array('id' => $ID,
							'naziv' => $input['name'],
				);
		echo '' . json_encode($result) . '';
	}

	mysqli_close($dbConnection);
	
	header('Content-type: application/json');
	header("Access-Control-Allow-Origin: *");
	
?>
