<?php
$servername = "localhost";
$username = "marintdr_admin";
$password = "ici123!@#ici";
$database = 'marintdr_dbmarilao';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password);

    // Set PDO to throw exceptions on error
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
    die();
}

// function authenticateUser($email, $password)
// {
//     global $conn;

//     $stmt = $conn->prepare("SELECT * FROM tbluser WHERE email = ? AND password = ?");
//     $stmt->execute([$email, $password]);
//     $user = $stmt->fetch(PDO::FETCH_ASSOC);

//     // Check if a user was found
//     if ($user) {
//         // Return the user's data, including name and usertype
//         return array(
//             "name" => $user['name'],
//             "email" => $user['email'],
//             "usertype" => $user['usertype'],
//             "userid" => $user['userid'],
//             "address" => $user['address']
//             // Add more fields as needed
//         );
//     } else {
//         // Return false if no user was found
//         return false;
//     }
// }
