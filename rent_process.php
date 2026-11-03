<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $machinery_id = $_POST['machinery_id'];
    $farmer_aadhar = $_SESSION['aadhar']; 
    $rental_hours = $_POST['rental_hours'];
    $delivery_location = $_POST['delivery_location'];
    $amount = $_POST['amount'];

   
    if (empty($machinery_id) || empty($farmer_aadhar) || empty($rental_hours) || empty($delivery_location) || empty($amount)) {
        echo "All fields are required.";
        exit;
    }

   
    $sql = "INSERT INTO machine_bookings (machinery_id, farmer_aadhar, rental_hours, delivery_location, amount, booked_at)
            VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Error preparing statement: " . $conn->error);
    }

    $stmt->bind_param("isisd", $machinery_id, $farmer_aadhar, $rental_hours, $delivery_location, $amount);

    if ($stmt->execute()) {
        echo "Booking processed successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Invalid request.";
}

$conn->close();
?>
