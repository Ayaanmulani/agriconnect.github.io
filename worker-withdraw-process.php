<?php
session_start();

if (!isset($_SESSION['aadhar'])) {
    echo json_encode(["status" => "error", "message" => "User not logged in."]);
    exit;
}

$aadhar = $_SESSION['aadhar'];
$withdrawAmount = isset($_POST['withdraw_amount']) ? floatval($_POST['withdraw_amount']) : 0;
$upiNumber = isset($_POST['upi_number']) ? $_POST['upi_number'] : '';

if ($withdrawAmount <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid withdrawal amount."]);
    exit;
}

if (!preg_match('/^\d{10}$/', $upiNumber)) {
    echo json_encode(["status" => "error", "message" => "Invalid UPI number."]);
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'agri_connect');

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]);
    exit;
}

$alterSql = "ALTER TABLE worker_balance ADD COLUMN IF NOT EXISTS upi_number VARCHAR(10) NOT NULL COMMENT 'UPI number (phone number)'";
$conn->query($alterSql);

$sql = "SELECT total_revenue, withdraw FROM worker_balance WHERE worker_aadhar = '$aadhar'";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $totalRevenue = $row['total_revenue'] ?? 0.00;
    $currentWithdraw = $row['withdraw'] ?? 0.00;
    $balance = $totalRevenue - $currentWithdraw;

    if ($withdrawAmount > $balance) {
        echo json_encode(["status" => "error", "message" => "Insufficient balance."]);
        exit;
    }

    $newWithdraw = $currentWithdraw + $withdrawAmount;
    $updateSql = "INSERT INTO worker_balance (worker_aadhar, total_revenue, withdraw, upi_number) 
                  VALUES ('$aadhar', '$totalRevenue', '$newWithdraw', '$upiNumber')
                  ON DUPLICATE KEY UPDATE withdraw = '$newWithdraw', upi_number = '$upiNumber'";

    if ($conn->query($updateSql)) {
        echo json_encode(["status" => "success", "message" => "Withdrawal of ₹" . number_format($withdrawAmount, 2) . " has been successful."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error processing withdrawal."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No worker found with that Aadhar number."]);
}

$conn->close();
?>
