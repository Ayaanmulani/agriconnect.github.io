<?php
$conn = new mysqli('localhost', 'root', '', 'agri_connect');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'];
$farmer_aadhar = $data['farmer_aadhar'];
$worker_aadhar = $data['worker_aadhar'];
$work_details = $data['work_details'];
$work_location = $data['work_location'];
$conditions = $data['conditions'];
$crops = $data['crops'];
$hours = $data['hours'];
$total_cost = $data['total_cost'];
$booking_date = $data['booking_date'];
$start_time = $data['start_time'];
$end_time = $data['end_time'];
$selected_date = $data['selected_date'];


$insertSql = "INSERT INTO assign_work (farmer_aadhar, worker_aadhar, work_details, work_location, conditions, crops, hours, total_cost, booking_date, start_time, end_time, selected_date) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$insertStmt = $conn->prepare($insertSql);
$insertStmt->bind_param(
    "ssssssidsiss",
    $farmer_aadhar,
    $worker_aadhar,
    $work_details,
    $work_location,
    $conditions,
    $crops,
    $hours,
    $total_cost,
    $booking_date,
    $start_time,
    $end_time,
    $selected_date
);

if ($insertStmt->execute()) {
    
    $deleteSql = "DELETE FROM worker_bookings WHERE id = ?";
    $deleteStmt = $conn->prepare($deleteSql);
    $deleteStmt->bind_param("i", $id);
    $deleteStmt->execute();

    echo "Work request accepted and assigned successfully.";
} else {
    echo "Failed to assign work: " . $conn->error;
}

$insertStmt->close();
$conn->close();
?>
