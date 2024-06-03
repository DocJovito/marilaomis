<?php
include 'connection.php'; // Include the database connection
include 'email_utils.php'; // Include the email utility functions

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

// Handle POST requests
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($data && isset($data['email'])) {
        $email = $data['email'];

        // Generate OTP
        $otp = generateOTP();

        // Store OTP in the database
        try {
            saveOtp($email, $otp);
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Database error: " . $e->getMessage());
            // echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            exit();
        }

        // Send OTP via email
        try {
            sendOtpEmail($email, $otp);
            echo json_encode(array("success" => true));
            exit();
        } catch (Exception $e) {
            // Handle email sending errors
            error_log("Error sending OTP: " . $e->getMessage());
            echo json_encode(array("error" => "Error sending OTP: " . $e->getMessage()));
            exit();
        }
    } else {
        error_log("Invalid request: Missing email in the request");
        echo json_encode(array("error" => "Invalid request"));
        exit();
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(array("error" => "Invalid request"));
    exit();
}

function generateOTP()
{
    // Generate a random OTP (6 digits)
    return rand(100000, 999999);
}

function saveOtp($email, $otp)
{
    global $conn;
    $query = $conn->prepare("UPDATE tbluser SET otp = :otp, otpexp = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = :email");
    $query->bindParam(':otp', $otp, PDO::PARAM_INT);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
}
