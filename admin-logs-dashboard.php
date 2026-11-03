<?php
// database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT 
            COUNT(CASE WHEN type = 'farmer' THEN 1 END) AS farmersCount,
            COUNT(CASE WHEN type = 'worker' THEN 1 END) AS workersCount
        FROM users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
   
    $data = $result->fetch_assoc();
} else {
    $data = ['farmersCount' => 0, 'workersCount' => 0];
}

$conn->close();


header('Content-Type: application/json');
echo json_encode($data);
?>
