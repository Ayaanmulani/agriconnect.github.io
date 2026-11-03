<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri-connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selected_date = $_POST['selected_date'];
    $current_date = date("Y-m-d");

    $sql = "INSERT INTO worker_booking (booking_date, date) VALUES ('$selected_date', '$current_date')";

    if ($conn->query($sql) === TRUE) {
        echo "Date successfully posted!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>
