<?php

session_start();

// Database connection
$conn = new mysqli('localhost', 'root', '', 'agri_connect');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_SESSION['aadhar'])) {
    $farmer_aadhar = $_SESSION['aadhar'];

   
    $worker_aadhar = $_POST['worker_aadhar'];
    $work_details = $_POST['work_details'];
    $work_location = $_POST['work_location'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $conditions = $_POST['conditions'] ?? NULL;
    $crops = $_POST['crops'] ?? NULL;
    $hours = (int)$_POST['hours'];

   
    $selected_date = $_POST['selected_date']; 
    
    $query = $conn->prepare("SELECT per_hour_rate FROM users WHERE aadhar = ?");
    $query->bind_param("s", $worker_aadhar);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $worker = $result->fetch_assoc();
        $per_hour_rate = $worker['per_hour_rate'];
        $total_cost = $hours * $per_hour_rate;

        
        $booking_date = date('Y-m-d H:i:s'); 

       
        $stmt = $conn->prepare("INSERT INTO worker_bookings (farmer_aadhar, worker_aadhar, work_details, work_location, booking_date, start_time, end_time, conditions, crops, hours, total_cost, selected_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssds", $farmer_aadhar, $worker_aadhar, $work_details, $work_location, $booking_date, $start_time, $end_time, $conditions, $crops, $hours, $total_cost, $selected_date);

        if ($stmt->execute()) {
            echo "<script>
                alert('Booking successful! Total Cost: ₹$total_cost');
                window.location.href = 'farmer-dashboard.html';
            </script>";
        } else {
            echo "<script>
                alert('Error: " . $stmt->error . "');
                window.history.back();
            </script>";
        }
        $stmt->close();
    } else {
        echo "<script>
            alert('Worker not found.');
            window.history.back();
        </script>";
    }
    $query->close();
} else {
    echo "<script>
        alert('Farmer is not logged in. Please log in to proceed.');
        window.location.href = 'login.php';
    </script>";
}


$conn->close();
?>
