<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

function myHash($text)
{
    $base64Encoded = substr(base64_encode($text), 0, 14);
    while (strlen($base64Encoded) < 14) {
        $base64Encoded .= '=';
    }
    return $base64Encoded;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Retrieve all records
    if ($_GET['action'] === 'get_all') {
        $stmt = $conn->prepare("SELECT * FROM tblperson ORDER BY residentid desc");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['residentid'])) {
        $residentid = $_GET['residentid'];
        $stmt = $conn->prepare("SELECT * FROM tblperson WHERE residentid = ?");
        $stmt->execute([$residentid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_rid' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM tblperson WHERE residentid = ?");
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

            $precinctid = $data['precinctid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("INSERT INTO tblperson (precinctid, lastname, firstname, middlename, addressline1, barangay, bday) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$precinctid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $residentid = $data['residentid'];
            $precinctid = $data['precinctid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("UPDATE tblperson SET precinctid=?, lastname=?, firstname=?, middlename=?, addressline1=?, barangay=?, bday=? WHERE residentid=?");
            $stmt->execute([$precinctid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday, $residentid]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } else if ($data['action'] === 'import' && isset($data['records']) && is_array($data['records'])) {
        try {

            $stmt = $conn->prepare("INSERT INTO tblperson (precinctid, lastname, firstname, middlename, addressline1, barangay, bday) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($data['records'] as $record) {
                $precinctid = $record[0];  // Assuming the first column is precinctid
                $lastname = myHash($record[1]);    // Apply myHash to lastname
                $firstname = myHash($record[2]);   // Apply myHash to firstname
                $middlename = $record[3];  // Assuming the fourth column is middlename
                $addressline1 = myHash($record[4]); // Apply myHash to addressline1
                $barangay = $record[5];    // Assuming the sixth column is barangay
                $bday = $record[6];        // Assuming the seventh column is bday

                $stmt->execute([$precinctid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);
            }

            echo json_encode(array("success" => true, "message" => "Records imported successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("successaaa" => false, "error" => "Error importing records: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'get_resident') {
        try {
            $barangayscope = $data['barangayscope'];
            error_log('Fetching residents for barangayscope: ' . $barangayscope);
            // Check if barangayscope is "All"
            if ($barangayscope === 'All') {
                $stmt = $conn->prepare("SELECT * FROM tblperson ORDER BY barangay");
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("SELECT * FROM tblperson WHERE barangay LIKE ?");
                $stmt->execute(["%{$barangayscope}%"]);
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching residents: " . $e->getMessage()));
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