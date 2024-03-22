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
    } elseif ($data['action'] === 'update') {
        try {
            $id = $data['id'];
            $residentid = $data['residentid'];
            $precinctid = $data['precinctid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $baranggay = $data['baranggay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("UPDATE tblperson SET residentid=?, precinctid=?, lastname=?, firstname=?, middlename=?, addressline1=?, baranggay=?, bday=? WHERE id=?");
            $stmt->execute([$residentid, $precinctid, $lastname, $firstname, $middlename, $addressline1, $baranggay, $bday, $id]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action"));
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Delete a record by ID
    if ($data['action'] === 'delete' && isset($data['id'])) {
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("DELETE FROM tblperson WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(array("message" => "Record deleted successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error deleting record: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action or missing ID"));
    }
}

// Close database connection
$conn = null;



//https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&id=3