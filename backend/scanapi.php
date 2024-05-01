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


            $stmt = $conn->prepare("INSERT INTO tblscan (id,programid, residentid, barangay, createdby,createdat) 
                                    VALUES (?,?, ?, ?, ?, NOW())");
            $stmt->execute([$id, $programid, $residentid, $barangay, $createdby]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } else if ($data['action'] === 'getscan') {
        try {
            $residentid = $data['residentid'];
            $lastname = $data['lastname'];
            $barangay = $data['barangay'];
            $programid = $data['programid']; // Define programid

            // Build the SQL query with search filters
            $sql = "SELECT tblscan.id, tblscan.programid, tblscan.residentid, tblscan.barangay, tblscan.createdby, tblscan.createdat,
            tblperson.lastname, tblperson.firstname, tblperson.addressline1
            FROM tblscan
            INNER JOIN tblperson ON tblscan.residentid = tblperson.residentid
            WHERE (tblscan.residentid = ? OR tblperson.lastname LIKE ? OR tblscan.barangay = ?) AND tblscan.programid = ?;
            ";
            $stmt = $conn->prepare($sql);

            // Bind parameters and execute the query
            $stmt->execute([$residentid, "%{$lastname}%", $barangay, $programid]); // Corrected $programid

            // Fetch and return the results as JSON
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($results);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching records: " . $e->getMessage()));
        }
    }
}

// Close database connection
$conn = null;



//https://rjprint10.com/marilaomis/backend/personapi.php?action=get_by_id&id=3