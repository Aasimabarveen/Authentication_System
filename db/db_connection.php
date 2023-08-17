<?php
error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
function connect_to_db(){
	$hostname = 'localhost';
	$username = 'root';
	$password = '';
	$db_name = 'crud';
	

	$conn = mysqli_connect($hostname, $username, $password, $db_name);
	
	if (!$conn) {
		$response=array("success" => false, "message" => 'error connection');	
		 echo json_encode($response);
	}
    return $conn;
}
?>
