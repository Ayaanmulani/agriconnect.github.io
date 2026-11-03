<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (!isset($_SESSION['aadhar'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

$aadhar = $_SESSION['aadhar'];
$product_id = $_POST['product_id'];
$quantity = 1;


$check_cart = "SELECT * FROM cart WHERE aadhar = '$aadhar' AND product_id = '$product_id'";
$result = $conn->query($check_cart);

if ($result->num_rows > 0) {
   
    $update_cart = "UPDATE cart 
                    SET quantity = quantity + $quantity, 
                        total_price = total_price + (SELECT price FROM products WHERE id = '$product_id') 
                    WHERE aadhar = '$aadhar' AND product_id = '$product_id'";
    $conn->query($update_cart);
    echo json_encode(['status' => 'success', 'message' => 'Product quantity updated']);
} else {
  
    $add_cart = "INSERT INTO cart (aadhar, product_id, quantity, total_price)
                 SELECT '$aadhar', id, '$quantity', price FROM products WHERE id = '$product_id'";
    $conn->query($add_cart);
    echo json_encode(['status' => 'success', 'message' => 'Product added to cart']);
}

$conn->close();
?>
