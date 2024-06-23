<?php
include 'connection.php'; // Include the database connection
include 'email_utils.php'; // Include the email utility functions

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header('Content-Type: application/json');

// error_log("Request received: " . file_get_contents('php://input'));

$data = json_decode(file_get_contents('php://input'), true);
// error_log("Parsed data: " . print_r($data, true));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($data && $data['action'] === 'login') {
        $email = $data['email'];
        $password = $data['password'];

        try {
            $user = authenticateUser($email, $password);
            if ($user) {
                $otp = rand(100000, 999999);
                saveOtp($email, $otp);
                sendOtpEmail($email, $otp);
                $response = json_encode(array("success" => true, "otp" => $otp));
                // error_log("Response: " . $response);
                echo $response;
            } else {
                $response = json_encode(array("error" => "Invalid email or password"));
                // error_log("Response: " . $response);
                echo $response;
            }
        } catch (PDOException $e) {
            $response = json_encode(array("error" => "Database error: " . $e->getMessage()));
            error_log("Response: " . $response);
            echo $response;
        } catch (Exception $e) {
            $response = json_encode(array("error" => "An error occurred: " . $e->getMessage()));
            error_log("Response: " . $response);
            echo $response;
        }
    } elseif ($data && $data['action'] === 'verify_otp') {
        $email = $data['email'];
        $otp = $data['otp'];

        try {
            if (verifyOtp($email, $otp)) {
                $token = generateToken($email);
                saveToken($email, $token);
                $user = getUserDetails($email);
                $userData = array(
                    "name" => $user['name'],
                    "email" => $user['email'],
                    "usertype" => $user['usertype'],
                    "userid" => $user['userid'],
                    "address" => $user['address']
                );
                $response = json_encode(array("success" => true, "token" => $token, "user" => $userData));
                // error_log("Response: " . $response);
                echo $response;
            } else {
                $response = json_encode(array("error" => "Invalid OTP"));
                error_log("Response: " . $response);
                echo $response;
            }
        } catch (PDOException $e) {
            $response = json_encode(array("error" => "Database error: " . $e->getMessage()));
            error_log("Response: " . $response);
            echo $response;
        } catch (Exception $e) {
            $response = json_encode(array("error" => "An error occurred: " . $e->getMessage()));
            error_log("Response: " . $response);
            echo $response;
        }
    } else {
        $response = json_encode(array("error" => "Invalid action 2"));
        error_log("Response: " . $response);
        echo $response;
    }
} else {
    $response = json_encode(array("error" => "Invalid request 3a"));
    // error_log("Response: " . $response);
    echo $response;
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
    $query = $conn->prepare("SELECT * FROM tbluser WHERE email = :email AND password = :password AND isdeleted = 0");
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
