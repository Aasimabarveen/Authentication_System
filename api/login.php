<?php
error_reporting(E_ERROR | E_PARSE);
require_once('../db/login.php');
require_once('token_handling.php');
header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
header('Access-Control-Allow-Methods: GET, POST, OPTIONS'); // Allow specific HTTP methods
header('Access-Control-Allow-Headers: Content-Type'); // Allow specific headers

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
        $username = isset($data['username']) ? $data['username'] : '';
        $passwrd = isset($data['passwrd']) ? $data['passwrd'] : '';
        $value="";
    try {
        $value=login_user($username,$passwrd);
        if ($value['success']===true) {
            // Generate a JWT token
            $jwt = generateToken($value['message']);
            // $dataArray = json_decode($jsonResponse, true);
            $response = array(
                'message' =>"authentication successful",
                'data' => $value['message'],
                'token' => $jwt
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
