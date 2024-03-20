<?php
include 'connection.php';

// header('Content-Type: application/json');

//header for allowing cors 
header("Access-Control-Allow-Origin: *");

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create a new record
    if ($_POST['action'] === 'create') {
        $residentid = $_POST['residentid'];
        $precinctid = $_POST['precinctid'];
        $lastname = $_POST['lastname'];
        $firstname = $_POST['firstname'];
        $middlename = $_POST['middlename'];
        $addressline1 = $_POST['addressline1'];
        $baranggay = $_POST['baranggay'];
        $bday = $_POST['bday'];

        $stmt = $conn->prepare("INSERT INTO tblperson (residentid, precinctid, lastname, firstname, middlename, addressline1, baranggay, bday) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$residentid, $precinctid, $lastname, $firstname, $middlename, $addressline1, $baranggay, $bday]);
        echo "Record created successfully";
    }
}

// Add update and delete operations as needed

$conn = null; // Close database connection
