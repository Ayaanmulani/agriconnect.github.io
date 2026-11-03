<?php
session_start();

if (!isset($_SESSION['aadhar'])) {
    die("Session not set. User is not logged in.");
}

$worker_aadhar = $_SESSION['aadhar'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $work_details = $_POST['work_details'];
    $work_location = $_POST['work_location'];
    $conditions = $_POST['conditions'];
    $crops = $_POST['crops'];
    $hours = $_POST['hours'];
    $total_cost = $_POST['total_cost'];
    $booking_date = date('Y-m-d H:i:s');

    $insert_sql = "INSERT INTO worker_bookings (worker_aadhar, work_details, work_location, conditions, crops, hours, total_cost, booking_date) 
                   VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("sssssiis", $worker_aadhar, $work_details, $work_location, $conditions, $crops, $hours, $total_cost, $booking_date);

    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Work request submitted successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to submit work request."]);
    }

    $stmt->close();
    $conn->close();
    exit();
}

$sql = "SELECT id, worker_aadhar, work_details, work_location, conditions, crops, hours, total_cost, booking_date 
        FROM worker_bookings 
        WHERE worker_aadhar = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $worker_aadhar);
$stmt->execute();
$result = $stmt->get_result();

$work_requests = [];

while ($row = $result->fetch_assoc()) {
    $work_requests[] = $row;
}

$stmt->close();
$conn->close();


header('Content-Type: application/json');
echo json_encode($work_requests);
?>
