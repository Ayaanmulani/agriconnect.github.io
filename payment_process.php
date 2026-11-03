<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $host = 'localhost'; 
    $username = 'root'; 
    $password = ''; 
    $database = 'agri_connect'; 
  
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
        // Connect to the database
        $conn = new mysqli($host, $username, $password, $database);

        
        if ($conn->connect_error) {
            die(json_encode([
                'success' => false,
                'message' => 'Database connection failed: ' . $conn->connect_error
            ]));
        }

         
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
            echo json_encode([
                'success' => false,
                'message' => 'Failed to save payment data: ' . $stmt->error
            ]);
        }
 
        $stmt->close();
        $conn->close();
    } else {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method.'
    ]);
}
?>
