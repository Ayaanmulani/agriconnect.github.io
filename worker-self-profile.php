<?php
session_start();

if (!isset($_SESSION['aadhar'])) {
    header("Location: login.php");
    exit();
}

$aadhar = $_SESSION['aadhar'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, phone, location, per_hour_rate, total_work_time, experience, aadhar, profile_image FROM users WHERE aadhar = ? AND type = 'worker'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $aadhar);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $worker = $result->fetch_assoc();
} else {
    echo "<script>alert('Worker profile not found.'); window.location.href='login.php';</script>";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Profile</title>
    <style>
        body {
        background-color: #121212;
        background-image: url('images/worker-self-profile-background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        margin: 0;
        font-family: 'Arial', sans-serif;
        color: white;
        position: relative;
        overflow: auto;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('images/worker-self-profile-background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1;
        pointer-events: none;
    }

        .header {
            background: linear-gradient(135deg, #4CAF50, #2E8B57);
            color: white;
            padding: 40px 20px;
            text-align: center;
            font-size: 2.5em;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            animation: fadeInDown 1s ease;
        }

        .content {
            padding: 50px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            animation: fadeIn 1.5s ease;
        }

        .profile-container {
            background-color: white;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 60%;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 50px;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            animation: popUp 1.2s ease;
            position: relative;
            overflow: hidden;
        }

        .profile-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 5px;
            background: linear-gradient(to right, #4CAF50, #2E8B57);
            animation: progressBar 2s ease-out infinite;
        }

        .profile-container:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .profile-container img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 4px solid #4CAF50;
            transition: transform 0.3s ease;
        }

        .profile-container img:hover {
            transform: scale(1.1) rotate(5deg);
        }

        .profile-container h2 {
            font-size: 2.2em;
            margin: 10px 0;
            color: #4CAF50;
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .profile-container h2:hover {
            color: #2e7d32;
            transform: scale(1.1);
        }

        .profile-container p {
            font-size: 1.1em;
            margin: 5px 0;
            color: #555;
        }

        .profile-details {
            margin-top: 30px;
            text-align: left;
            width: 100%;
            font-size: 1.1em;
            color: #333;
        }

        .profile-details p {
            margin: 10px 0;
            transition: transform 0.3s ease, color 0.3s ease;
        }

        .profile-details p:hover {
            transform: translateX(10px);
            color: #4CAF50;
        }

        .location {
            font-size: 1.1em;
            font-weight: 600;
            color: #888;
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes popUp {
            from {
                transform: scale(0.9);
                opacity: 0;
            }
            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes progressBar {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        .back-button {
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: #8B4513;
            color: white;
            padding: 8px 16px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
            transition: 0.3s ease-in-out;
            z-index: 1000;
        }

        .back-button:hover {
            background-color: #A0522D;
        }
    </style>
</head>
<body>
<button class="back-button" onclick="goBack()">⬅ Back</button>

<div class="header">Worker Profile</div>
<div class="content">
    <div class="profile-container">
        <img src="data:image/jpeg;base64,<?php echo base64_encode($worker['profile_image']); ?>" alt="Profile Photo">
        <h2><?php echo htmlspecialchars($worker['name']); ?></h2>
        <p class="location">Location: <?php echo htmlspecialchars($worker['location']); ?></p>
        <div class="profile-details">
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($worker['phone']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($worker['email']); ?></p>
            <p><strong>Hourly Rate:</strong> ₹<?php echo htmlspecialchars($worker['per_hour_rate']); ?></p>
            <p><strong>Aadhaar:</strong> <?php echo substr(htmlspecialchars($worker['aadhar']), 0, 4) . '****'; ?></p>
        </div>
    </div>
</div>
</body>
<script>
function goBack() {
    window.history.back();
}
</script>
</html>
