<?php

$host = 'localhost'; 
$username = 'root';  
$password = '';  
$database = 'agri_connect'; 
 
$conn = new mysqli($host, $username, $password, $database);

 
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 
session_start();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
     
    if (isset($_POST['machinery_id'])) {
        $machinery_id = $_POST['machinery_id'];
        $machinery_name = $_POST['machinery_name'];
        $farmer_aadhar = $_SESSION['aadhar'];  
        $rental_hours = $_POST['rental_hours'];
        $delivery_location = $_POST['delivery_location'];
        $amount = $_POST['amount'];

         
        if (empty($machinery_id) || empty($machinery_name) || empty($farmer_aadhar) || empty($rental_hours) || empty($delivery_location) || empty($amount)) {
            echo "All fields are required.";
            exit;
        }

        
        $sql = "INSERT INTO machine_bookings (machinery_id, machinery_name, farmer_aadhar, rental_hours, delivery_location, amount, booked_at)
                VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        
        $stmt->bind_param("issisd", $machinery_id, $machinery_name, $farmer_aadhar, $rental_hours, $delivery_location, $amount);

        if ($stmt->execute()) {
            echo "Booking processed successfully!<br>";
            echo "Machinery ID: " . $machinery_id . "<br>";
            echo "Machinery Name: " . $machinery_name . "<br>";
            echo "Farmer Aadhar: " . $farmer_aadhar . "<br>";
            echo "Rental Hours: " . $rental_hours . "<br>";
            echo "Delivery Location: " . $delivery_location . "<br>";
            echo "Amount: ₹" . number_format((float)$amount, 2, '.', '') . "<br>";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }

 
    if (isset($_POST['fullName'])) {
         
        $fullName = htmlspecialchars($_POST['fullName']);
        $aadharNumber = htmlspecialchars($_POST['aadharNumber']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $mobilenumber = htmlspecialchars($_POST['mobilenumber']);
        $paymentMethod = htmlspecialchars($_POST['paymentMethod']);
        $paymentAmount = htmlspecialchars($_POST['paymentAmount']);

    
        $errors = [];

        if (empty($fullName)) {
            $errors[] = 'Full Name is required.';
        }

        if (empty($aadharNumber) || strlen($aadharNumber) != 12 || !ctype_digit($aadharNumber)) {
            $errors[] = 'A valid 12-digit Aadhar Number is required.';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'A valid email address is required.';
        }

        if (empty($paymentMethod)) {
            $errors[] = 'Payment Method is required.';
        }

        if (empty($paymentAmount) || !is_numeric($paymentAmount) || $paymentAmount <= 0) {
            $errors[] = 'A valid Payment Amount is required.';
        }

        if (empty($errors)) {
            
            $sql = "INSERT INTO payments (full_name, aadhar_number, email, address, mobile_number, payment_method, payment_amount)
                    VALUES (?, ?, ?, ?, ?, ?, ?)";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param(
                'ssssssd',
                $fullName,
                $aadharNumber,
                $email,
                $address,
                $mobilenumber,
                $paymentMethod,
                $paymentAmount
            );

            if ($stmt->execute()) {
            
                header('Location: payment_done.html');
                exit();
            } else {
                echo "Failed to save payment data: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo json_encode([
                'success' => false,
                'errors' => $errors
            ]);
        }
    }
} else {
    echo "Invalid request.";
}


$conn->close();
?>
