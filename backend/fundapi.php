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
        $stmt = $conn->prepare("SELECT * FROM tblfund ORDER BY fundid desc");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve a single record by ID
    if ($_GET['action'] === 'get_by_id' && isset($_GET['fundid'])) {
        $fundid = $_GET['fundid'];
        $stmt = $conn->prepare("SELECT * FROM tblfund WHERE fundid = ?");
        $stmt->execute([$fundid]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($row);
    }

    // Retrieve programs
    if ($_GET['action'] === 'get_programs') {
        $stmt = $conn->prepare("SELECT programname FROM tblprogram ORDER BY programname");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }

    // Retrieve users
    if ($_GET['action'] === 'get_users') {
        $stmt = $conn->prepare("SELECT userid, email, name FROM tbluser ORDER BY name");
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($rows);
    }
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse incoming JSON data
    $data = json_decode(file_get_contents("php://input"), true);

    // Create a new record
    if ($data['action'] === 'create') {
        try {
            $programid = $data['programid'];
            $amount = $data['amount'];
            $userid = $data['userid'];
            $createdby = $data['createdby'];
            $createdat = $data['createdat'];
            $editedby = $data['editedby'];
            $editedat = $data['editedat'];
            $isdeleted = $data['isdeleted'];
            $deletedby = $data['deletedby'];

            $stmt = $conn->prepare("INSERT INTO tblfund (programid, amount, userid, createdby, createdat, editedby, editedat, isdeleted, deletedby) 
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$programid, $amount, $userid, $createdby, $createdat, $editedby, $editedat, $isdeleted, $deletedby]);

            echo json_encode(array("message" => "Record created successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error creating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'update') {
        try {
            $fundid = $data['fundid'];
            $programid = $data['programid'];
            $amount = $data['amount'];
            $userid = $data['userid'];
            $createdby = $data['createdby'];
            $createdat = $data['createdat'];
            $editedby = $data['editedby'];
            $editedat = $data['editedat'];
            $isdeleted = $data['isdeleted'];
            $deletedby = $data['deletedby'];

            $stmt = $conn->prepare("UPDATE tblfund SET programid=?, amount=?, userid=?, createdby=?, createdat=?, editedby=?, editedat=?, isdeleted=?, deletedby=? WHERE fundid=?");
            $stmt->execute([$programid, $amount, $userid, $createdby, $createdat, $editedby, $editedat, $isdeleted, $deletedby, $fundid]);

            echo json_encode(array("message" => "Record updated successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error updating record: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'delete') {
        try {
            $fundid = $data['fundid'];
            $deletedby = isset($data['deletedby']) ? $data['deletedby'] : null; // Ensure deletedby is set
            $deletedat = date('Y-m-d H:i:s');

            $stmt = $conn->prepare("UPDATE tblfund SET isdeleted=1, deletedby=?, deletedat=? WHERE fundid=?");
            $stmt->execute([$deletedby, $deletedat, $fundid]);

            echo json_encode(array("message" => "Record marked as deleted successfully"));
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error marking record as deleted: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'fetch_funds') {
        try {
            $stmt = $conn->prepare("SELECT f.fundid, f.programid, p.programname, f.amount, u.name AS userid 
                                    FROM tblfund f
                                    LEFT JOIN tbluser u ON f.userid = u.userid
                                    LEFT JOIN tblprogram p ON f.programid = p.programid
                                    WHERE f.isdeleted = 0 
                                    ORDER BY f.fundid DESC");
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching funds: " . $e->getMessage()));
        }
    } elseif ($data['action'] === 'search_funds') {
        $datestart = $data['datestart'];
        $dateend = $data['dateend'];
        $userid = $data['userid'];

        try {
            // Initialize base SQL query
            $sql = "SELECT 
            f.fundid,
            f.programid,
            f.amount,
            u1.name AS userid,
            u2.name AS createdby,
            f.createdat,
            u3.name AS editedby,
            f.editedat,
            f.isdeleted,
            f.deletedby
        FROM 
            tblfund f
        LEFT JOIN 
            tbluser u1 ON f.userid = u1.userid
        LEFT JOIN 
            tbluser u2 ON f.createdby = u2.userid
        LEFT JOIN 
            tbluser u3 ON f.editedby = u3.userid
        WHERE 
            f.isdeleted = 0 
            AND f.createdat BETWEEN ? AND ?";

            // Initialize parameters array
            $params = [$datestart, $dateend];

            // Add condition for userid if it's not 'All'
            if ($userid !== 'All') {
                $sql .= " AND f.userid = ?";
                $params[] = $userid;
            }

            // Order by fundid descending
            $sql .= " ORDER BY f.fundid DESC";

            // Prepare the statement
            $stmt = $conn->prepare($sql);

            // Execute the statement with the parameters
            $stmt->execute($params);

            // Fetch all results
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Output the results as JSON
            echo json_encode($rows);
        } catch (PDOException $e) {
            echo json_encode(array("error" => "Error fetching funds: " . $e->getMessage()));
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
            $stmt = $conn->prepare("DELETE FROM tblfund WHERE fundid = ?");
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
