<?php 
// // Function to generate a JWT token (replace with your token generation logic)
// function generateToken($user) {
//     $currentTime = time();
//      // Header
//     $header = json_encode([
//         'alg' => 'HS256', // Algorithm (HMAC SHA-256)
//         'typ' => 'JWT'    // Token type
//     ]);
//     $header = base64_encode($header);

//     // Payload
//     $payload = json_encode([
//         'user_profile' => $user,
//         'iat' => $currentTime,           // Issued at
//         'nbf' => $currentTime,           // Not before
//         'exp' => $currentTime  + 3600 // Expiration time (1 hour from now)
//     ]);
//     $payload = base64_encode($payload);

//     // Secret key for signing
//     $secretKey = 'your-secret-key';

//     // Signature
//     $signature = hash_hmac('sha256', "$header.$payload", $secretKey, true);
//     $signature = base64_encode($signature);

//     // JWT
//     $jwt = "$header.$payload.$signature";

//     return $jwt;
// }

// Secret key for signing tokens
$secret_key = 'your-secret-key';

// Function to generate a JWT token
function generateToken($user) {
    global $secret_key;

    $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    $payload = json_encode(['user' => $user, 'exp' => time() + 3600]); // Expires in 1 hour

    $header_encoded = base64UrlEncode($header);
    $payload_encoded = base64UrlEncode($payload);

    $signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true);
    $signature_encoded = base64UrlEncode($signature);

    $token = "$header_encoded.$payload_encoded.$signature_encoded";
    return $token;
}

// Function to validate a JWT token
function validateToken($token) {
    global $secret_key;

    list($header_encoded, $payload_encoded, $signature_encoded) = explode('.', $token);

    $expected_signature = hash_hmac('sha256', "$header_encoded.$payload_encoded", $secret_key, true);
    $expected_signature_encoded = base64UrlEncode($expected_signature);

    if ($signature_encoded === $expected_signature_encoded) {
        $payload = json_decode(base64UrlDecode($payload_encoded));
        if ($payload->exp >= time()) {
            return $payload->user;
        }
    }

    return false;
}

// Function to renew a JWT token
function renewToken($token) {
    $user = validateToken($token);
    
    if ($user !== false) {
        return generateToken($user);
    }    
    return false;
}


// Helper function to base64url encode data
function base64UrlEncode($data) {
    return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
}

// Helper function to base64url decode data
function base64UrlDecode($data) {
    return base64_decode(str_replace(['-', '_'], ['+', '/'], $data));
}

 ?>