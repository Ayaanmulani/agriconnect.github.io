<?php
session_start();
$aadhar = $_SESSION['aadhar'];


$conn = new mysqli('localhost', 'root', '', 'agri_connect');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT * FROM assign_work WHERE farmer_aadhar = '$aadhar'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Work Requests</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-image: url('images/farmer-request-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: white;
        }

        h1 {
            text-align: center;
            margin: 40px 0;
            font-size: 36px;
            font-weight: 600;
            color: #4CAF50;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.6);
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
        }

        .request-card {
            background-color: rgba(30, 30, 30, 0.85);
            border-radius: 12px;
            width: 320px;
            margin: 15px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .request-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.6);
        }

        .request-card .title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 12px;
            color: #4CAF50;
        }

        .request-card p {
            font-size: 14px;
            margin-bottom: 8px;
            color: #ddd;
        }

        .pay-btn {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            text-align: center;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .pay-btn:hover {
            background-color: #388e3c;
            transform: scale(1.05);
        }

        .pay-btn:focus {
            outline: none;
        }

        .empty-message {
            text-align: center;
            font-size: 18px;
            color: #bbb;
        }

        .back-button {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #28a745;
            color: white;
            padding: 10px 18px;
            font-size: 16px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
            z-index: 1000;
        }

        .back-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<button class="back-button" onclick="goBack()">⬅ Back</button>

<h1>Pending Work Requests</h1>

<div class="container">
    <?php if ($result->num_rows > 0) : ?>
        <?php while ($row = $result->fetch_assoc()) : ?>
            <div class="request-card">
                <div class="title">Work ID: <?php echo htmlspecialchars($row['id']); ?></div>
                <p><strong>Farmer Aadhar:</strong> <?php echo htmlspecialchars($row['farmer_aadhar']); ?></p>
                <p><strong>Worker Aadhar:</strong> <?php echo htmlspecialchars($row['worker_aadhar']); ?></p>
                <p><strong>Work Details:</strong> <?php echo htmlspecialchars($row['work_details']); ?></p>
                <p><strong>Total Cost:</strong> ₹<?php echo htmlspecialchars($row['total_cost']); ?></p>
                <a href="payment_worker.php?work_id=<?php echo $row['id']; ?>">
                    <button class="pay-btn">Proceed to Payment</button>
                </a>
            </div>
        <?php endwhile; ?>
    <?php else : ?>
        <div class="empty-message">No pending work requests found.</div>
    <?php endif; ?>
</div>

<script>
    function goBack() {
        window.history.back();
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
