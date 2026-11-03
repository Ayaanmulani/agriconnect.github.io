<?php
// database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $worker_id = $_POST['worker_id'] ?? '';
    $farmer_id = 1; 
    $hours = $_POST['hours'] ?? '';
    $message = $_POST['message'] ?? '';

    
    $worker_sql = "SELECT per_hour_rate FROM users WHERE id='$worker_id'";
    $worker_result = $conn->query($worker_sql);
    
    if ($worker_result->num_rows > 0) {
        $worker = $worker_result->fetch_assoc();
        $rate = $worker['per_hour_rate'];
        $total_cost = $hours * $rate;

        
        $insert_sql = "INSERT INTO bookings (worker_id, farmer_id, hours, total_cost) VALUES ('$worker_id', '$farmer_id', '$hours', '$total_cost')";
        if ($conn->query($insert_sql) === TRUE) {
            
            $message_sql = "INSERT INTO messages (worker_id, farmer_id, message) VALUES ('$worker_id', '$farmer_id', '$message')";
            $conn->query($message_sql);
            echo json_encode(["success" => true, "message" => "Booking successful! Total cost: ₹$total_cost."]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Error: Worker not found."]);
    }
}


$conn->close();
?>
