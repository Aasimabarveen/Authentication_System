
<?php
//  error_reporting(E_ERROR | E_PARSE);
// error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../db/db_connection.php');
$conn;
function update_user($data){
	global $conn;
	$conn=check_connection();
	if($conn){
			$value=update($data);
			if ($value===true){
				return true;
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

function update($data){
	try {
		global $conn;
	    // Prepare the SQL insert query
	    $query = "UPDATE `profile` SET `Fname`=?, `Lname`=?, `Contact`=?, `DoB`=? WHERE `Id`=?";
	    
	    // Create a prepared statement
	    $stmt = mysqli_prepare($conn, $query);
	    
	    // Bind the parameters to the prepared statement
	    mysqli_stmt_bind_param($stmt, "sssss", $data['fname'],$data['lname'],$data['contact'],$data['dob'],$data['id']);
	     // Execute the prepared statement
	    $executionResult = mysqli_stmt_execute($stmt);

	    if ($executionResult) {
	    	
	        return true;
	    } else {
	        $response=array("success" => false, "message" => "Error updating record: " . mysqli_stmt_error($stmt));	
			return $response;
	    }
	} catch(Exception $e) {
	    $response=array("success" => false, "message" => "Error updating record: " . mysqli_stmt_error($stmt));	
		return $response;
	}

}
?>
