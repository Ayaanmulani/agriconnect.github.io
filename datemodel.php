<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_date = $_POST['selected_date'];
    
    $sql = "INSERT INTO worker_bookings (selected_date) VALUES ('$selected_date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Date saved successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
