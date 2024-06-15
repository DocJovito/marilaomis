<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new record
    if ($data['action'] === 'create') {
        try {
            $id = $data['id'];
            $programid = $data['programid'];
            $residentid = $data['residentid'];
            $barangay = $data['barangay'];
            $createdby = $data['createdby'];
            $budgetperhead = $data['budgetperhead'];

            $stmt = $conn->prepare("INSERT INTO tblscan (id,programid, residentid, barangay, createdby,createdat, budgetperhead) 
                                    VALUES (?,?, ?, ?, ?, NOW(), ?)");
            $stmt->execute([$id, $programid, $residentid, $barangay, $createdby, $budgetperhead]);

            echo json_encode(array("Scan Success. Resident Name: "));
        } catch (PDOException $e) {
            echo json_encode(array("Resident is Already Scanned: "));
            // echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } else if ($data['action'] === 'getscan') {
        try {
            $residentid = $data['residentid'];
            $lastname = $data['lastname'];
            $barangay = $data['barangay'];
            $programid = $data['programid']; // Define programid

            // Build the SQL query with search filters
            $sql = "SELECT tblscan.id, tblscan.programid, tblscan.residentid, tblscan.barangay, tbluser.name AS createdby, tblscan.createdat,
            tblperson.lastname, tblperson.firstname, tblperson.addressline1, tblscan.budgetperhead
            FROM tblscan
            LEFT JOIN tblperson ON tblscan.residentid = tblperson.residentid
            LEFT JOIN tbluser ON tblscan.createdby = tbluser.userid
    WHERE (tblscan.residentid LIKE ? OR tblperson.lastname LIKE ?) and tblscan.barangay like ?
      AND tblscan.programid = ?;
            ";
            $stmt = $conn->prepare($sql);

            // Bind parameters and execute the query
            $stmt->execute([$residentid, "%{$lastname}%", "%{$barangay}%", $programid]); // Corrected $programid

            // Fetch and return the results as JSON
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching records: " . $e->getMessage()));
        }
    }elseif ($data['action'] === 'search_scans') {
        try {
            $datestart = $data['datestart'];
            $dateend = $data['dateend'];
            $barangay = $data['barangay'];
            $programid = $data['programid'];
            $userid = $data['userid'];
            
$query = "SELECT tblscan.id, tblprogram.programname AS programid, tblperson.lastname AS residentid, tblscan.barangay, 
                 tbluser.name AS createdby, tblscan.createdat, tblscan.budgetperhead
          FROM tblscan
          LEFT JOIN tblprogram ON tblscan.programid = tblprogram.programid
          LEFT JOIN tblperson ON tblscan.residentid = tblperson.residentid
          LEFT JOIN tbluser ON tblscan.createdby = tbluser.userid
          WHERE 1=1";

// Add conditions dynamically based on input values
$params = [];
if ($barangay !== 'All') {
    $query .= " AND tblscan.barangay = ?";
    $params[] = $barangay;
}
if ($programid !== 'All') {
    $query .= " AND tblscan.programid = ?";
    $params[] = $programid;
}
if ($userid !== 'All') {
    $query .= " AND tblscan.createdby = ?";
    $params[] = $userid;
}

$query .= " AND tblscan.createdat BETWEEN ? AND ? ORDER BY tblscan.createdat";
            $params[] = $datestart;
            $params[] = $dateend;
            $stmt = $conn->prepare($query);
            $stmt->execute($params);      
            
           $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating program: " . $e->getMessage()));
        }
    }
}

// Close database connection
$conn = null;



//https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&id=3