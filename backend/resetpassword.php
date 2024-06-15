<?php
// Include necessary files and functions
include 'connection.php'; // Include the database connection

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
    if ($data && isset($data['otp']) && isset($data['newPassword'])) {
        $otp = $data['otp'];
        $new_password = $data['newPassword'];

        try {
            // Verify OTP and update password
            $email = verifyOtp($otp);
            if ($email) {
                resetPassword($email, $new_password);
                echo json_encode(array("success" => true));
                exit();
            } else {
                echo json_encode(array("error" => "Invalid OTP or OTP expired 123"));
                exit();
            }
        } catch (PDOException $e) {
            // Handle database errors
            error_log("Database error: " . $e->getMessage());
            echo json_encode(array("error" => "Database error: " . $e->getMessage()));
            exit();
        }
    } else {
        error_log("Invalid request: Missing required fields in the request");
        echo json_encode(array("error" => "Invalid request"));
        exit();
    }
} else {
    error_log("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    echo json_encode(array("error" => "Invalid request method"));
    exit();
}

function verifyOtp($otp)
{
    global $conn;
    $query = $conn->prepare("SELECT email, otp, otpexp FROM tbluser WHERE otp = :otp");
    $query->bindParam(':otp', $otp, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $otp_expiry_time = strtotime($result['otpexp']);
        $current_time = time();

        error_log("OTP Expiry Time: " . date('Y-m-d H:i:s', $otp_expiry_time));
        error_log("Current Time: " . date('Y-m-d H:i:s', $current_time));

        if ($otp_expiry_time > $current_time) {
            return $result['email'];
        } else {
            error_log("OTP has expired");
        }
    }
    return false;
}

function resetPassword($email, $new_password)
{
    global $conn;
    $query = $conn->prepare("UPDATE tbluser SET password = :new_password, otp = NULL, otpexp = NULL WHERE email = :email");
    $query->bindParam(':new_password', $new_password, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
}
?>
