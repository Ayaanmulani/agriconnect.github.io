<?php
header('Content-Type: application/json');


session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";


if (!isset($_SESSION['aadhar'])) {
    echo json_encode(["error" => "Worker is not logged in"]);
    exit;
}

$worker_aadhar = $_SESSION['aadhar']; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

$sql = "SELECT * FROM worker_bookings WHERE worker_aadhar = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $worker_aadhar); 

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$stmt->close();
$conn->close();
?>
