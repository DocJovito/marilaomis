<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle POST requests
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($data && $data['action'] === 'login') {
        $email = $data['email'];
        $password = $data['password'];

        // Authenticate user against your database
        try {
            $user = authenticateUser($email, $password);
            if ($user) {
                // Generate a token
                $token = generateToken($email);

                // Send success response with token and user data
                $userData = array(
                    "name" => $user['name'],
                    "usertype" => $user['usertype']
                );
                echo json_encode(array("token" => $token, "user" => $userData));
                error_log("Received email: $email, Password: $password, password Correct, Name: {$user['name']}, Usertype: {$user['usertype']}");
            } else {
                // Authentication failed
                echo json_encode(array("error" => "Invalid email or password"));
            }
        } catch (PDOException $e) {
            // Handle database errors
            echo json_encode(array("error" => "Database error: " . $e->getMessage()));
        } catch (Exception $e) {
            // Handle other exceptions
            echo json_encode(array("error" => "An error occurred: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
} else {
    echo json_encode(array("error" => "Invalid request"));
}

function generateToken($email)
{
    // Generate a random token or use JWT library
    return bin2hex(random_bytes(32));
}
