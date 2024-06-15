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
        $stmt = $conn->prepare("SELECT * FROM tblprogram WHERE isdeleted = 0");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $conn->prepare("SELECT * FROM tblprogram WHERE programid = ? AND isdeleted = 0");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }
        // Retrieve programs
    if ($_GET['action'] === 'get_programs') {
        $stmt = $conn->prepare("SELECT programid, programname FROM tblprogram ORDER BY programname");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
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
            $programname = $data['programname'];
            $description = $data['description'];
            $barangayscope = $data['barangayscope'];
            $budgetperhead = $data['budgetPerHead'];
            $ismember = $data['ismember'];
            $eventDate = $data['eventDate'];
            $isactive = 1;  // palaging 1  active
            $createdby = $data['createdby'];
            $createdat = date('Y-m-d'); // Assuming current date for createdat field
            $isdeleted = 0; // Assuming newly created program is not deleted

            $stmt = $conn->prepare("INSERT INTO tblprogram (programname, description, barangayscope,budgetperhead, ismember, eventDate, isactive, createdby, createdat, isdeleted) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
            $stmt->execute([$programname, $description, $barangayscope, $budgetperhead,$ismember,  $eventDate, $isactive, $createdby, $createdat, $isdeleted]);

            echo json_encode(array("message" => "Program created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating program: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $programid = $data['id'];
            $programname = $data['programname'];
            $description = $data['description'];
            $barangayscope = $data['barangayscope'];
            $budgetperhead = $data['budgetperhead'];
            $ismember =  $data['ismember'];
            $eventDate = $data['eventDate'];
            $isactive = $data['isactive'];

            $stmt = $conn->prepare("UPDATE tblprogram SET programname=?, description=?, barangayscope=?, budgetperhead=?, ismember=?, eventDate=?, isactive=? WHERE programid=?");
            $stmt->execute([$programname, $description, $barangayscope, $budgetperhead, $ismember , $eventDate, $isactive, $programid]);

            echo json_encode(array("message" => "Program updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating program: " . $e->getMessage()));
        }
    } else if ($data['action'] === 'get_programs') {
        try {
            $barangayscope = $data['barangayscope'];
            // Check if barangayscope is "All"
            if ($barangayscope === 'All') {
                $stmt = $conn->prepare("SELECT * FROM tblprogram WHERE isdeleted = 0 ORDER BY programid desc");
                $stmt->execute();
            } else {
                $stmt = $conn->prepare("SELECT * FROM tblprogram WHERE isdeleted = 0 AND barangayscope LIKE ? ORDER BY programid desc");
                $stmt->execute(["%{$barangayscope}%"]);
            }
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating user: " . $e->getMessage()));
        }
        
    }elseif ($data['action'] === 'search_program') try {
    $datestart = $data['datestart'];
    $dateend = $data['dateend'];
    $barangay = $data['barangay'];
    $ismember = $data['ismember'];

    // Base query
    $query = "
        SELECT 
            p.programid,
            p.programname,
            p.description,
            p.ismember,
            p.barangayscope,
            p.eventDate,
            p.isactive,
            u.name AS createdby,
            p.createdat,
            p.isdeleted,
            p.budgetperhead
        FROM 
            tblprogram p
        JOIN 
            tbluser u ON p.createdby = u.userid
        WHERE 
            p.isdeleted = 0 
            AND p.createdat BETWEEN ? AND ?
    ";
    
    // Parameters array
    $params = [$datestart, $dateend];

    // Add conditions based on inputs
    if ($barangay != 'All') {
        $query .= " AND p.barangayscope LIKE ?";
        $params[] = "%{$barangay}%";
    }

    if ($ismember != 'All') {
        $query .= " AND p.ismember = ?";
        $params[] = $ismember;
    }

    // Order by programid descending
    $query .= " ORDER BY p.programid DESC";

    // Prepare and execute the statement
    $stmt = $conn->prepare($query);
    $stmt->execute($params);

    // Fetch and return the results
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Error updating program: " . $e->getMessage()));
}

  
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);

    // Delete a record by ID
    if ($data['action'] === 'delete' && isset($data['id'])) {
        try {
            $id = $data['id'];
            $stmt = $conn->prepare("UPDATE tblprogram SET isdeleted = 1 WHERE programid = ?");
            $stmt->execute([$id]);

            echo json_encode(array("message" => "Program deleted successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error deleting program: " . $e->getMessage()));
        }
    } else {
        echo json_encode(array("error" => "Invalid action or missing ID"));
    }
}

// Close database connection
$conn = null;
