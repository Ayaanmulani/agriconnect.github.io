<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$status = $data['status'];

$sql = "UPDATE messages SET status = '$status' WHERE id = $id";

if ($conn->query($sql) === TRUE) {
    echo "Booking updated successfully!";
} else {
    echo "Error updating booking: " . $conn->error;
}

$conn->close();
?>
