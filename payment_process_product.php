<?php

$host = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database = 'agri_connect'; 

session_start();


if (!isset($_SESSION['aadhar']) || empty($_SESSION['aadhar'])) {
    die("Aadhar session variable is not set.");
}


$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['product_id'])) {
        
        $product_id = $_POST['product_id'];
        $name = $_POST['name'];
        $quantity = $_POST['quantity'];
        $total_price = $_POST['total_price'];
        $user_aadhar = $_SESSION['aadhar']; 
     
        if (empty($product_id)) {
            die("Product ID is required.");
        }

        if (empty($quantity)) {
            die("Quantity is required.");
        }

        if (!ctype_digit($quantity) || $quantity <= 0) {
            die("Quantity must be a positive integer.");
        }

       
        $sql = "INSERT INTO purchases (user_aadhar, product_id, name, quantity, total_price, purchase_date)
                VALUES (?, ?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("sisis", $user_aadhar, $product_id, $name, $quantity, $total_price);

        if ($stmt->execute()) {
            echo "Purchase completed successfully!<br>";
            echo "Product ID: " . htmlspecialchars($product_id) . "<br>";
            echo "name : " . htmlspecialchars($name) . "<br>";
            echo "Quantity: " . htmlspecialchars($quantity) . "<br>";
            echo "Total Price: ₹" . number_format((float)$total_price, 2, '.', '') . "<br>";
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
            if ($stmt === false) {
                die("Error preparing statement: " . $conn->error);
            }

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
