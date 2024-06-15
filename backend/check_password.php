<?php
// Include the database connection
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Handle POST requests
    $data = json_decode(file_get_contents('php://input'), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Check if the required data is provided
        if (!isset($data['currentPassword']) || !isset($data['email'])) {
            error_log("Missing currentPassword or email: currentPassword=" . (isset($data['currentPassword']) ? $data['currentPassword'] : 'not provided') . ", email=" . (isset($data['email']) ? $data['email'] : 'not provided'));
            echo json_encode(array("error" => "Current password or email is not provided"));
            exit();
        }

        $currentPassword = $data['currentPassword'];
        $email = $data['email'];

        // Retrieve the user's password from the database based on their email
        $query = $conn->prepare("SELECT password FROM tbluser WHERE email = :email");
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->execute();
        $row = $query->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            // Check if the provided password matches the password retrieved from the database
            if ($currentPassword === $row['password']) {
                // Password is correct
                echo json_encode(array("success" => true));
            } else {
                // Password is incorrect
                error_log("Incorrect password for user: $email");
                echo json_encode(array("error" => "Incorrect password"));
            }
        } else {
            // User not found
            error_log("User not found: $email");
            echo json_encode(array("error" => "User not found"));
        }
    } else {
        // Invalid request method
        error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
        echo json_encode(array("error" => "Invalid request"));
    }
} catch (Exception $e) {
    // Exception occurred
    error_log("Exception occurred: " . $e->getMessage());
    echo json_encode(array("error" => "An error occurred"));
}
?>
