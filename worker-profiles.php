<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "agri_connect"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT name, email, phone, location, per_hour_rate, total_work_time, experience, aadhar, profile_image FROM users WHERE type = 'worker'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $workers = $result->fetch_all(MYSQLI_ASSOC); 
} else {
    $workers = [];
}


$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri Connect - Admin Workers Access</title>
    <link rel="stylesheet" href="style.css">
    <style>
body {
        background-color: #121212;
        background-image: url('images/worker-profiles-background.jpg');
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
        background-image: url('images/worker-profiles-background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1;
        pointer-events: none;
    }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 15px;
            text-align: center;
            animation: slideInDown 0.8s;
        }

        .menu a {
            margin: 0 15px;
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .menu a:hover {
            color: #FFD700;
        }

        .content {
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            justify-content: center;
        }

        .worker-profile {
            background-color: #2b2b2b;
            color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
            transition: transform 0.3s, box-shadow 0.3s;
            text-align: center;
        }

        .worker-profile:hover {
            transform: translateY(-10px) scale(1.03);
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.8);
        }

        .worker-photo {
            width: 150px;
            height: 150px;
            border-radius: 75px;
            cursor: pointer;
            transition: transform 0.3s ease-in-out;
        }

        .worker-photo:hover {
            transform: scale(1.2) rotate(5deg);
        }

        hr {
            border: 1px solid #444;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
            animation: fadeIn 0.5s;
        }

        .modal-content {
            display: block;
            margin: auto;
            max-width: 700px;
        }

        .modal-content img {
            width: 100%;
            border-radius: 10px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #fff;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @keyframes slideInDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Workers' Profiles</h1>
        <div class="menu">
            <a href="admin-dashboard-main.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content profile-content">
        <?php if (empty($workers)): ?>
            <p>No workers found.</p>
            <a href="add-worker.html" style="color: #4CAF50; text-decoration: none;">Add a Worker</a>
        <?php else: ?>
            <?php foreach ($workers as $worker): ?>
                <div class="worker-profile">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($worker['profile_image']); ?>" 
                         alt="<?php echo htmlspecialchars($worker['name']); ?>" 
                         class="worker-photo" 
                         onclick="openModal(this.src)">
                    <h2>Profile Information</h2>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($worker['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($worker['email']); ?></p>
                    <p><strong>Phone No:</strong> <?php echo htmlspecialchars($worker['phone']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($worker['location']); ?></p>
                    <p><strong>Per Hour Rate:</strong> ₹<?php echo htmlspecialchars($worker['per_hour_rate']); ?></p>
                    <p><strong>Total Work Time:</strong> <?php echo htmlspecialchars($worker['total_work_time']); ?></p>
                    <p><strong>Experience:</strong> <?php echo htmlspecialchars($worker['experience']); ?></p>
                    <p><strong>Aadhaar:</strong> <?php echo substr(htmlspecialchars($worker['aadhar']), 0, 4) . '****'; ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <div class="modal-content">
            <img id="modalImg" src="" alt="Worker Profile Image">
        </div>
    </div>

    <script>
        function openModal(src) {
            document.getElementById("imageModal").style.display = "block";
            document.getElementById("modalImg").src = src;
        }

        function closeModal() {
            document.getElementById("imageModal").style.display = "none";
        }
    </script>
</body>
</html>
