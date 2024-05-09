<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

//or dito may add para may ksama token

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    if ($data['action'] === 'login') {
        try {
            $email = $data['email'];
            $password = $data['password'];
            $stmt = $conn->prepare("SELECT * FROM tbluser WHERE email = ? AND password = ? AND isdeleted = 0");
            $stmt->execute([$email, $password]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);

            //token dapat ako
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating user: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}


// Close database connection
$conn = null;
