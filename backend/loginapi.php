<?php
include 'connection.php'; // Include the database connection
include 'email_utils.php'; // Include the email utility functions

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
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
                // Generate OTP
                $otp = rand(100000, 999999);

                // Save OTP to the database
                saveOtp($email, $otp);

                // Send OTP via email
                sendOtpEmail($email, $otp);

                // Respond with success and prompt for OTP
                //echo json_encode(array("success" => true));
                echo json_encode(array("success" => true, "otp" => $otp));
                error_log("OTP sent to email: $email");
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
    } elseif ($data && $data['action'] === 'verify_otp') {
        $email = $data['email'];
        $otp = $data['otp'];

        // Verify OTP
        try {
            if (verifyOtp($email, $otp)) {
                // Generate a token
                $token = generateToken($email);

                // Save the token to the database
                saveToken($email, $token);

                // Get user details
                $user = getUserDetails($email);

                // Send success response with token and user data
                $userData = array(
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "usertype" => $user['usertype'],
                    "userid" => $user['userid'],
                    "address" => $user['address']
                );
                echo json_encode(array("success" => true, "token" => $token, "user" => $userData));
                error_log("OTP verified for email: $email");
            } else {
                // OTP verification failed
                echo json_encode(array("error" => "Invalid OTP"));
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

function saveOtp($email, $otp)
{
    global $conn;
    $query = $conn->prepare("UPDATE tbluser SET otp = :otp, otpexp = DATE_ADD(NOW(), INTERVAL 10 MINUTE) WHERE email = :email");
    $query->bindParam(':otp', $otp, PDO::PARAM_INT);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
}

function verifyOtp($email, $otp)
{
    global $conn;
    $query = $conn->prepare("SELECT * FROM tbluser WHERE email = :email AND otp = :otp AND otpexp > NOW()");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':otp', $otp, PDO::PARAM_INT);
    $query->execute();
    return $query->rowCount() > 0;
}

function getUserDetails($email)
{
    global $conn;
    $query = $conn->prepare("SELECT * FROM tbluser WHERE email = :email");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function saveToken($email, $token)
{
    global $conn;
    $query = $conn->prepare("UPDATE tbluser SET token = :token WHERE email = :email");
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->execute();
}

function authenticateUser($email, $password)
{
    global $conn;
    // error_log("Email: $email, Password: $password"); // Log received email and password
    $query = $conn->prepare("SELECT * FROM tbluser WHERE email = :email AND password = :password");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch(PDO::FETCH_ASSOC);
    // error_log("User data: " . print_r($user, true)); // Log user data retrieved from the database
    return $user;
}


function createUser($email, $password, $name, $usertype, $address)
{
    global $conn;
    $query = $conn->prepare("INSERT INTO tbluser (email, password, name, usertype, address, createdat, isdeleted) VALUES (:email, :password, :name, :usertype, :address, NOW(), 0)");
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR); // No hashing here
    $query->bindParam(':name', $name, PDO::PARAM_STR);
    $query->bindParam(':usertype', $usertype, PDO::PARAM_STR);
    $query->bindParam(':address', $address, PDO::PARAM_STR);
    $query->execute();
}
