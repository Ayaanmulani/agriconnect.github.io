<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $quantity = $_POST['quantity'] ?? 1;

    
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]['quantity'] += $quantity;
    } else {
        // Add new product to the cart
        $_SESSION['cart'][$product_id] = [
            'product_name' => $product_name,
            'product_price' => $product_price,
            'quantity' => $quantity
        ];
    }

    echo json_encode(['status' => 'success', 'message' => 'Product added to cart successfully!']);
    exit();
}
?>
