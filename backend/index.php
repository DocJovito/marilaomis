<?php
$servername = "localhost";
$username = "rjprhiwg_admin";
$password = "ici123!@#";
$database = 'rjprhiwg_dbmarilao';

$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
