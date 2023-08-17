
<?php
 error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../db/db_connection.php');
$conn;
function register_user($data){
	global $conn;
	$conn=check_connection();
	if($conn){
		$value=check_username_exist($data['username']);
		if ($value===true) {
			$value=register($data);
			if ($value===true){
				return true;
			}
		}
	}
	else
		return $conn;
	// Close the connection
	mysqli_close($conn);
	return $value;
}

function check_connection(){
	try {
		global $conn;
    	$conn = connect_to_db();
    	if($conn)
    		return $conn;
    	else{
    		$response=array("success" => false, "message" => "connection error ".$conn);	
    		return $response;	
    	}
		
	    
	} catch (Exception $e) {
		$response=array("success" => false, "message" => "connection error".$e->getMessage());	
		return $response;
	}
	
}

function check_username_exist($username){
	try {
		global $conn;	
	    // Prepare the SQL query
	    $query = "SELECT * FROM profile WHERE Username = ?";
	    
	    // Create a prepared statement
	    $stmt = mysqli_prepare($conn, $query);
	    
	    // Bind the parameter to the prepared statement
	    mysqli_stmt_bind_param($stmt, "s", $username);

	    // Execute the prepared statement
	    mysqli_stmt_execute($stmt);

	    // Get the result
	    $result = mysqli_stmt_get_result($stmt);

	    // Get the number of rows returned by the query
	    $numRows = mysqli_num_rows($result);

	    if ($numRows > 0) {
			$response=array("success" => false, "message" => "username already exists");	
			return $response;
	    }
	    return true;
	   
	} catch(Exception $e) {
	    $response=array("success" => false, "message" => "Error inserting record: " . mysqli_stmt_error($stmt));	
		return $response;
	}

	// Close the statement
	mysqli_stmt_close($stmt);

}

function register($data){
	try {
		global $conn;
	    // Prepare the SQL insert query
	    $query = "INSERT INTO `profile`(`Fname`, `Lname`,  `Contact`, `Username`, `Password`, `DoB`, `Id`) VALUES (?,?,?,?,?,?,?)";
	    
	    // Create a prepared statement
	    $stmt = mysqli_prepare($conn, $query);
	    
	    // Bind the parameters to the prepared statement
	    mysqli_stmt_bind_param($stmt, "sssssss", $data['fname'],$data['lname'],$data['contact'],$data['username'],$data['passwrd'],$data['dob'],$data['id']);
	     // Execute the prepared statement
	    $executionResult = mysqli_stmt_execute($stmt);

	    if ($executionResult) {
	        return true;
	    } else {
	        $response=array("success" => false, "message" => "Error inserting record: " . mysqli_stmt_error($stmt));	
			return $response;
	    }
	} catch(Exception $e) {
	    $response=array("success" => false, "message" => "Error inserting record: " . mysqli_stmt_error($stmt));	
		return $response;
	}

}
?>
