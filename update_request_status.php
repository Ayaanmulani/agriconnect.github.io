<?php
session_start();


$conn = mysqli_connect("your_hostname", "your_username", "your_password", "your_database_name");

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}

$request_id = $_POST['id'];
$status = $_POST['status'];

$sql = "UPDATE work_requests SET status = '$status' WHERE id = '$request_id'";
if (mysqli_query($conn, $sql)) {
  echo "Status updated successfully!";
} else {
  echo "Error updating status: " . mysqli_error($conn);
}

mysqli_close($conn);
?>