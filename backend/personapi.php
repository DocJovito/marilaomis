<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all records
    if ($_GET['action'] === 'get_all') {
        $stmt = $conn->prepare("SELECT * FROM tblperson");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM tblperson WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new record
    if ($data['action'] === 'create') {
        try {
            $residentid = $data['residentid'];
            $precinctid = $data['precinctid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $baranggay = $data['baranggay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("INSERT INTO tblperson (residentid, precinctid, lastname, firstname, middlename, addressline1, baranggay, bday) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$residentid, $precinctid, $lastname, $firstname, $middlename, $addressline1, $baranggay, $bday]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}

// Close database connection
$conn = null;
