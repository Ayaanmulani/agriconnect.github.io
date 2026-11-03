<?php

header('Content-Type: application/json');

// Database credentials
$host = 'localhost';
$dbname = 'agri_connect';
$username = 'root';
$password = '';

try {
    
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
    $stmt = $pdo->query(
        "SELECT 
            purchases.id,
            purchases.user_aadhar,
            purchases.product_id,
            purchases.name,
            purchases.quantity,
            CAST(purchases.total_price AS DECIMAL(10, 2)) AS total_price,
            purchases.purchase_date
        FROM purchases"
    );

    $purchases = $stmt->fetchAll(PDO::FETCH_ASSOC);

   
    echo json_encode($purchases);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
