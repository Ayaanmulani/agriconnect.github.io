<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$paymentMethod = $_POST['paymentMethod'];
$deliveryAddress = $_POST['deliveryAddress'];

$cart = [
    ['product_id' => 1, 'quantity' => 2, 'price' => 1000],
    ['product_id' => 2, 'quantity' => 1, 'price' => 1500]
];

$totalPrice = 0;
foreach ($cart as $item) {
    $totalPrice += $item['price'] * $item['quantity'];
}

foreach ($cart as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $price = $item['price'] * $quantity;

    $stmt = $conn->prepare("INSERT INTO purchases (user_id, product_id, quantity, payment_method, delivery_address, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiissd", $user_id, $product_id, $quantity, $paymentMethod, $deliveryAddress, $price);
    $stmt->execute();
}

$conn->close();
header("Location: success.php");
exit();
?>
