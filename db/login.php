
<?php
 error_reporting(E_ERROR | E_PARSE);
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once('../db/db_connection.php');
$conn;
function login_user($username,$passwrd){
	global $conn;
	$conn=check_connection();
	if($conn){
		$value=authorize_user($username,$passwrd);
		if ($value===true) {
			// $value=register($data);

		}
	}
	else{
		return $conn;
	}
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

function authorize_user($username,$passwrd){
	try {
		global $conn;	
	    // Prepare the SQL query
	    $query = "SELECT * FROM profile WHERE Username = ? AND password=?";
	    
	    // Create a prepared statement
	    $stmt = mysqli_prepare($conn, $query);
	    
	    // Bind the parameter to the prepared statement
	    mysqli_stmt_bind_param($stmt, "ss", $username, $passwrd);

	    // Execute the prepared statement
	    mysqli_stmt_execute($stmt);

	    // Get the result
	    $result = mysqli_stmt_get_result($stmt);

	    // Get the number of rows returned by the query
	    $numRows = mysqli_num_rows($result);

	    if ($numRows ==0) {
			$response=array("success" => false, "message" => "username or password incorrect");	
	    }
	    else{
	    	 $row = mysqli_fetch_assoc($result);
        	 $data=['id'=>$row['Id'],'fname'=>$row['Fname'],'lname'=>$row['Lname'],'dob'=>$row['DoB'],'contact'=>$row['Contact'],'username'=>$row['Username']];
    		mysqli_free_result($result);
    		$response=array("success" => true, "message" =>$data);	
	    }
	   
	    return $response;
	} catch(Exception $e) {
	    $response=array("success" => false, "message" => "Error login" . mysqli_stmt_error($stmt));	
		return $response;
	}

	// Close the statement
	mysqli_stmt_close($stmt);

}

?>
