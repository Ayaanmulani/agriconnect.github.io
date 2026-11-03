<?php

$host = '127.0.0.1';
$db = 'agri_connect';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}


$stmt = $pdo->query("SELECT id, distributor_name, name AS productName, distributor_number, price, description FROM products");
$products = $stmt->fetchAll();


header('Content-Type: application/json');
echo json_encode($products);
?>
