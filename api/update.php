
<?php
// error_reporting(E_ERROR | E_PARSE);
// error_reporting(E_ALL);
require_once('../db/update.php');
require_once('token_handling.php');
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
         $destinationArray = [];
         $destinationArray['fname'] = isset($data['fname']) ? $data['fname'] : '';
         $destinationArray['lname']  = isset($data['lname']) ? $data['lname'] : '';
         $destinationArray['dob']  = isset($data['dob']) ? $data['dob'] : '';
         $destinationArray['contact']  = isset($data['contact']) ? $data['contact'] : '';
         $token = isset($data['token']) ? $data['token'] : '';
         $value="";
         
        try {
            $response=validateToken($token);
            if($response==false)
            {
                $response = array(
                'message' => 'Token validation failed'
                );
            }
            else{
                $value=update_user($data);
                if ($value===true) {
                    $token=generateToken($destinationArray);
                    if($token)
                        $data['token']=$token;
                    $response = array(
                    'message' => 'User Details Updated Succesfully',
                    'received_data' => $data
                    );
                }
                else{
                    $response=$value;
                }
        }
            
        } catch (Exception $e) {
            $response =array("success" => false, "message" => $e->getMessage());
        }
        header('Content-Type: application/json');
        echo json_encode($response);
    }
}

?>
