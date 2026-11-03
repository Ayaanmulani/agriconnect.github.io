<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "agri_connect";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$searchAadhar = isset($_GET['searchAadhar']) ? $_GET['searchAadhar'] : '';


if ($searchAadhar) {
    $sql = "SELECT id, worker_aadhar, withdraw, balance, upi_number, updated_at 
            FROM worker_balance
            WHERE worker_aadhar LIKE '%$searchAadhar%'
            ORDER BY worker_aadhar, updated_at DESC";
} else {
    $sql = "SELECT id, worker_aadhar, withdraw, balance, upi_number, updated_at 
            FROM worker_balance
            ORDER BY worker_aadhar, updated_at DESC";
}

$result = $conn->query($sql);

$workers = [];
$totalWithdrawn = 0;
$totalBalance = 0;
$latest_balance = [];


while ($row = $result->fetch_assoc()) {
    $worker_aadhar = $row['worker_aadhar'];
    $totalWithdrawn += $row['withdraw'];
    
    
    if (!isset($latest_balance[$worker_aadhar])) {
        $latest_balance[$worker_aadhar] = $row['balance'];
        $totalBalance += $row['balance'];
    }
    
    $workers[] = $row;
}


$totalRevenue = $totalWithdrawn + $totalBalance;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Worker Balance Records</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .highlight { background-color: #f9fafb; }
        .summary { background: linear-gradient(to right, #ffefba, #ffffff); }
        .search-container { margin-bottom: 20px; }
        .search-input { padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 100%; }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <div class="container mx-auto">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Worker Balance Records</h1>
        <div class="mb-4 summary shadow-md rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Summary</h2>
            <div class="grid grid-cols-3 gap-4">
                <p class="text-xl">Total Withdrawn: <span class="font-bold text-red-600">₹<?php echo number_format($totalWithdrawn, 2); ?></span></p>
                <p class="text-xl">Total Avaliable Balance: <span class="font-bold text-green-600">₹<?php echo number_format($totalBalance, 2); ?></span></p>
                <p class="text-xl">Total Revenue Of Workers: <span class="font-bold text-blue-600">₹<?php echo number_format($totalRevenue, 2); ?></span></p>
            </div>
        </div>
        <div class="mb-6 search-container">
            <form method="GET" action="">
                <input type="text" name="searchAadhar" class="search-input" placeholder="Search by Worker Aadhar" value="<?php echo htmlspecialchars($searchAadhar); ?>">
            </form>
        </div>
        <div class="mb-4">
            <button onclick="window.history.back()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Back</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow-md rounded-lg">
                <thead class="bg-gray-200 text-gray-700">
                    <tr>
                        <th class="p-4 text-left">ID</th>
                        <th class="p-4 text-left">Aadhar Number</th>
                        <th class="p-4 text-left">UPI Number</th>
                        <th class="p-4 text-right">Withdrawn</th>
                        <th class="p-4 text-right">Available Balance</th>
                        <th class="p-4 text-left">Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($workers as $row): ?>
                    <tr class="border-b hover:bg-gray-100 highlight">
                        <td class="p-4"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($row['worker_aadhar']); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($row['upi_number']); ?></td>
                        <td class="p-4 text-right">₹<?php echo number_format($row['withdraw'], 2); ?></td>
                        <td class="p-4 text-right font-bold text-green-600">₹<?php echo number_format($row['balance'], 2); ?></td>
                        <td class="p-4"><?php echo htmlspecialchars($row['updated_at']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
