<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

function myHash($text)
{
    $base64Encoded = base64_encode($text);
    return $base64Encoded;
}

function unHash($encodedText)
{
    $decodedText = base64_decode($encodedText);
    return $decodedText;
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

            $precintid = $data['precintid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("INSERT INTO tblperson (precintid, lastname, firstname, middlename, addressline1, barangay, bday) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$precintid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $residentid = $data['residentid'];
            $precintid = $data['precintid'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("UPDATE tblperson SET precintid=?, lastname=?, firstname=?, middlename=?, addressline1=?, barangay=?, bday=? WHERE residentid=?");
            $stmt->execute([$precintid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday, $residentid]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'delete') {
        try {
            $residentid = $data['residentid'];

            $stmt = $conn->prepare("UPDATE tblperson SET isdeleted=1 WHERE residentid=?");
            $stmt->execute([$residentid]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } else if ($data['action'] === 'import' && isset($data['records']) && is_array($data['records'])) {
        try {

            $stmt = $conn->prepare("INSERT INTO tblperson (precintid, lastname, firstname, middlename, addressline1, barangay, bday) 
            VALUES (?, ?, ?, ?, ?, ?, ?)");

            foreach ($data['records'] as $record) {
                $precintid = $record[0];
                $lastname = myHash($record[1]);
                $firstname = myHash($record[2]);
                $middlename = $record[3];
                $addressline1 = myHash($record[4]);
                $barangay = $record[5];
                $bday = $record[6];

                $stmt->execute([$precintid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);
            }

            echo json_encode(array("success" => true, "message" => "Records imported successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("successaaa" => false, "error" => "Error importing records: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_resident') {
        try {
            $lll = $data['lastname'];
            $lastname = myHash($lll);
            $stmt = $conn->prepare("SELECT * FROM tblperson WHERE lastname like ? AND isdeleted = 0 ORDER BY residentid desc ");
            $stmt->execute(["%{$lastname}%"]);
            //   $stmt->execute([$lastname]);

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