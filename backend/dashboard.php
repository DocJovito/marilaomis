<?php
include 'connection.php';

// Set headers for allowing CORS and specifying JSON content type
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header('Content-Type: application/json');

$response = [];

try {
  // Summary Statistics
  $stmt = $conn->query("SELECT 
                          SUM(amount) AS totalFundsAllocated, 
                          (SELECT SUM(amount) FROM tblfund WHERE isdeleted = 0) AS totalFundsAvailable,
                          (SELECT COUNT(*) FROM tblprogram WHERE isactive = 1) AS activePrograms,
                          (SELECT COUNT(*) FROM tbluser WHERE isdeleted = 0) AS users
                        FROM tblfund WHERE isdeleted = 0");
  $summary = $stmt->fetch(PDO::FETCH_ASSOC);

  // Recent Fund Allocations
  $stmt = $conn->query("SELECT fundid, amount, userid, createdat 
                        FROM tblfund 
                        WHERE isdeleted = 0 
                        ORDER BY createdat DESC 
                        LIMIT 10");
  $recentAllocations = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Recent Edits
  $stmt = $conn->query("SELECT fundid, amount, editedby, editedat 
                        FROM tblfund 
                        WHERE isdeleted = 0 
                        AND editedat IS NOT NULL 
                        ORDER BY editedat DESC 
                        LIMIT 10");
  $recentEdits = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Chart Data
  // Fund Allocation by Program
  $stmt = $conn->query("SELECT programname, SUM(amount) as total_amount
                        FROM tblfund
                        JOIN tblprogram ON tblfund.userid = tblprogram.programid
                        WHERE tblfund.isdeleted = 0
                        GROUP BY programname");
  $allocationByProgram = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $programLabels = array_column($allocationByProgram, 'programname');
  $programValues = array_column($allocationByProgram, 'total_amount');

  // Fund Allocation Over Time
  $stmt = $conn->query("SELECT DATE(createdat) as date, SUM(amount) as total_amount
                        FROM tblfund
                        WHERE isdeleted = 0
                        GROUP BY DATE(createdat)
                        ORDER BY DATE(createdat)");
  $allocationOverTime = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $timeLabels = array_column($allocationOverTime, 'date');
  $timeValues = array_column($allocationOverTime, 'total_amount');

  $response = [
    'summary' => $summary,
    'recentAllocations' => $recentAllocations,
    'recentEdits' => $recentEdits,
    'charts' => [
        'programs' => [
            'labels' => $programLabels,
            'data' => $programValues
        ],
        'time' => [
            'labels' => $timeLabels,
            'data' => $timeValues
        ]
    ]
  ];

  echo json_encode($response);
} catch (PDOException $e) {
  echo json_encode(["error" => "Error fetching dashboard data: " . $e->getMessage()]);
}
?>
