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
            $ismember = $data['ismember'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("INSERT INTO tblperson (precintid, ismember, lastname, firstname, middlename, addressline1, barangay, bday) 
                                    VALUES (?,?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$precintid, $ismember, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $residentid = $data['residentid'];
            $precintid = $data['precintid'];
            $ismember = $data['ismember'];
            $lastname = $data['lastname'];
            $firstname = $data['firstname'];
            $middlename = $data['middlename'];
            $addressline1 = $data['addressline1'];
            $barangay = $data['barangay'];
            $bday = $data['bday'];

            $stmt = $conn->prepare("UPDATE tblperson SET precintid=?, ismember=?, lastname=?, firstname=?, middlename=?, addressline1=?, barangay=?, bday=? WHERE residentid=?");
            $stmt->execute([$precintid, $ismember, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday, $residentid]);

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
                $middlename = myHash($record[3]);
                $addressline1 = myHash($record[4]);
                $barangay = $record[5];
                $bday = $record[6];

                // Check if $lastname is null
                if ($lastname != null || $lastname != "") {
                    $stmt->execute([$precintid, $lastname, $firstname, $middlename, $addressline1, $barangay, $bday]);
                }
            }

            echo json_encode(array("success" => true, "message" => "Records imported successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("success" => false, "error" => "Error importing records: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_resident') {
        try {
            $lll = $data['lastname'];
            $lastname = myHash($lll);
            $barangay = $data['barangay'];

            if ($barangay == 'All') {
                $stmt = $conn->prepare("SELECT * FROM tblperson WHERE lastname like ? AND isdeleted = 0 ORDER BY residentid desc ");
                $stmt->execute(["%{$lastname}%"]);
            } else {
                $stmt = $conn->prepare("SELECT * FROM tblperson WHERE lastname like ? AND barangay=? AND isdeleted = 0 ORDER BY residentid desc ");
                $stmt->execute(["%{$lastname}%", $barangay]);
            }



            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching residents: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_residents') {
        try {
            $isvoter = $data['isvoter'];
            $ismember = $data['ismember'];
            $barangay = $data['barangay'];

            // Base query
            $query = "SELECT * FROM tblperson WHERE isdeleted = 0";
            $params = [];

            // Add conditions based on inputs
            if ($barangay != 'All') {
                $query .= " AND barangay = ?";
                $params[] = $barangay;
            }
            if ($ismember != 'All') {
                $query .= " AND ismember = ?";
                $params[] = $ismember;
            }

            if ($isvoter != 'All') {
                if ($isvoter == 'true') {
                    $query .= " AND precintid != ''";
                } else {
                    $query .= " AND precintid = ''";
                }
            }

            // Order by residentid descending
            $query .= " ORDER BY residentid DESC";

            // Prepare and execute the statement
            $stmt = $conn->prepare($query);
            $stmt->execute($params);

            // Fetch and return the results
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