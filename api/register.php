
<?php
error_reporting(E_ERROR | E_PARSE);
require_once('../db/register.php');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // Handle preflight requests (CORS preflight check)
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
       // Ensure that the request contains JSON data
    $requestPayload = file_get_contents("php://input");
    $data = json_decode($requestPayload, true);

    if ($data === null) {
        // JSON data is not valid
        $response = array(
            'error' => 'Invalid JSON data'
        );
    } else {
         $response="";
         $fname = isset($data['fname']) ? $data['fname'] : '';
         $lname = isset($data['lname']) ? $data['lname'] : '';
         $dob = isset($data['dob']) ? $data['dob'] : '';
         $contact = isset($data['contact']) ? $data['contact'] : '';
         $username = isset($data['username']) ? $data['username'] : '';
         $passwrd = isset($data['passwrd']) ? $data['passwrd'] : '';
         $value="";
        try {
            $value=register_user($data);
            if ($value===true) {
                $response = array(
                'message' => 'Registration Succesful, Please Login',
                'received_data' => $data
                );
            }
            else{
                $response=$value;
            }
            
        } catch (Exception $e) {
            $response =array("success" => false, "message" => $e->getMessage());
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

?>
