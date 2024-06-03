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
        if (!isset($data['otp']) || !isset($data['newPassword']) || !isset($data['email'])) {
            error_log("Missing OTP, newPassword, or email");
            echo json_encode(array("error" => "OTP, newPassword, or email is not provided"));
            exit();
        }

        $otp = $data['otp'];
        $newPassword = $data['newPassword'];
        $email = $data['email'];

        // Verify OTP
        // Implement your OTP verification logic here
        // For example, retrieve the OTP from the database based on the email and compare it with the provided OTP

        // For demonstration purposes, assume OTP validation is successful
        // Update the user's password in the database
        $query = $conn->prepare("UPDATE tbluser SET password = :password WHERE email = :email AND otp = :otp");
        $query->bindParam(':password', $newPassword, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':otp', $otp, PDO::PARAM_STR);
        $query->execute();

        // Check if the password was updated successfully
        if ($query->rowCount() > 0) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("error" => "Invalid OTP or email"));
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
