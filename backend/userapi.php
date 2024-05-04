<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all records
    if ($_GET['action'] === 'get_all') {
        $stmt = $conn->prepare("SELECT * FROM tbluser WHERE isdeleted = 0");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM tbluser WHERE userid = ? AND isdeleted = 0");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create or update a record
    if ($data['action'] === 'create') {
        // Create a new record
        try {
            $email = $data['email'];
            $password = $data['password'];
            $usertype = $data['usertype'];
            $name = $data['name'];
            $address = $data['address'];
            $createdat = date('Y-m-d'); // Assuming current date for createdat field
            $isdeleted = 0; // Assuming newly created user is not deleted

            $stmt = $conn->prepare("INSERT INTO tbluser (email, password, usertype, name, address, createdat, isdeleted) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$email, $password, $usertype, $name, $address, $createdat, $isdeleted]);

            echo json_encode(array("message" => "User created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating user: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $userid = $data['userid'];
            $email = $data['email'];
            $password = $data['password'];
            $usertype = $data['usertype'];
            $name = $data['name'];
            $address = $data['address'];

            $stmt = $conn->prepare("UPDATE tbluser SET email=?, password=?, usertype=?, name=?, address=? WHERE userid=?");
            $stmt->execute([$email, $password, $usertype, $name, $address, $userid]);

            echo json_encode(array("message" => "User updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating user: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}


// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Delete a record by ID
    if ($data['action'] === 'delete' && isset($data['id'])) {
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("UPDATE tbluser SET isdeleted = 1 WHERE userid = ?");
            $stmt->execute([$id]);

            echo json_encode(array("message" => "User deleted successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error deleting user: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action or missing ID"));
    }
}

// Close database connection
$conn = null;
?>
