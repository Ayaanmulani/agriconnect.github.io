<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $host = 'localhost'; 
    $username = 'root';  
    $password = '';  
    $database = 'agri_connect'; 
  
    $formData = [
        'user_aadhar' => isset($_POST['user_aadhar']) && is_string($_POST['user_aadhar']) ? htmlspecialchars(trim($_POST['user_aadhar'])) : '',
        'product_id' => isset($_POST['product_id']) && is_string($_POST['product_id']) ? htmlspecialchars(trim($_POST['product_id'])) : '',
        'name' => isset($_POST['name']) && is_string($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '',
        'quantity' => isset($_POST['quantity']) && is_numeric($_POST['quantity']) ? (int)$_POST['quantity'] : 0,
        'total_price' => isset($_POST['total_price']) && is_numeric($_POST['total_price']) ? (float)$_POST['total_price'] : 0.00,
        'full_name' => isset($_POST['full_name']) && is_string($_POST['full_name']) ? htmlspecialchars(trim($_POST['full_name'])) : '',
        'email' => isset($_POST['email']) && is_string($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '',
        'address' => isset($_POST['address']) && is_string($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '',
        'card_number' => isset($_POST['card_number']) && is_string($_POST['card_number']) ? htmlspecialchars(trim($_POST['card_number'])) : '',
        'expiry_date' => isset($_POST['expiry_date']) ? $_POST['expiry_date'] : '',
        'cvv' => isset($_POST['cvv']) && is_string($_POST['cvv']) ? htmlspecialchars(trim($_POST['cvv'])) : '',
        'payment_method' => isset($_POST['payment_method']) && is_string($_POST['payment_method']) ? htmlspecialchars(trim($_POST['payment_method'])) : '',
        'payment_amount' => isset($_POST['payment_amount']) && is_numeric($_POST['payment_amount']) ? (float)$_POST['payment_amount'] : 0.00
    ];

     
    $errors = [];

    if (empty($formData['user_aadhar']) || strlen($formData['user_aadhar']) != 12 || !ctype_digit($formData['user_aadhar'])) {
        $errors[] = 'A valid 12-digit Aadhar Number is required.';
    }

    if (empty($formData['product_id'])) {
        $errors[] = 'Product ID is required.';
    }

    if (empty($formData['name'])) {
        $errors[] = 'Product name is required.';
    }

    if ($formData['quantity'] <= 0) {
        $errors[] = 'A valid quantity is required.';
    }

    if ($formData['total_price'] <= 0) {
        $errors[] = 'A valid total price is required.';
    }

    if (empty($formData['full_name']) || empty($formData['email']) || empty($formData['address']) || empty($formData['card_number']) || empty($formData['expiry_date']) || empty($formData['cvv']) || empty($formData['payment_method']) || $formData['payment_amount'] <= 0) {
        $errors[] = 'All payment details are required and must be valid.';
    }

    if (empty($errors)) {
        
        $conn = new mysqli($host, $username, $password, $database);

         
        if ($conn->connect_error) {
            die(json_encode([
                'success' => false,
                'message' => 'Database connection failed: ' . $conn->connect_error
            ]));
        }

     
        $sql_purchases = "INSERT INTO purchases (user_aadhar, product_id, name, quantity, total_price) VALUES (?, ?, ?, ?, ?)";
        $stmt_purchases = $conn->prepare($sql_purchases);
        $stmt_purchases->bind_param('sssid', $formData['user_aadhar'], $formData['product_id'], $formData['name'], $formData['quantity'], $formData['total_price']);
        $stmt_purchases->execute();

         
        $sql_payments = "INSERT INTO payments (full_name, aadhar_number, email, address, card_number, expiry_date, cvv, payment_method, payment_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_payments = $conn->prepare($sql_payments);
        $stmt_payments->bind_param('ssssssssd', $formData['full_name'], $formData['user_aadhar'], $formData['email'], $formData['address'], $formData['card_number'], $formData['expiry_date'], $formData['cvv'], $formData['payment_method'], $formData['payment_amount']);
        $stmt_payments->execute();

     
        header('Location: purchase_done.html');
        exit();
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
