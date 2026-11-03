<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, phone, location, crops_grown, land_size, profile_image FROM users WHERE type = 'farmer'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $farmers = $result->fetch_all(MYSQLI_ASSOC);
} else {
    $farmers = [];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri Connect - Farmers' Profiles</title>
    <style>
body {
        background-color: #121212;
        background-image: url('images/farmer-profile-background.jpg');
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
        background-image: url('images/farmer-profile-background.jpg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1;
        pointer-events: none;
    }

    .header {
        background-color: #4caf50;
        color: white;
        padding: 15px;
        text-align: center;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.5);
        animation: slideInDown 0.8s ease-out;
    }

    .menu a {
        margin: 0 15px;
        color: white;
        text-decoration: none;
        font-weight: bold;
        transition: color 0.3s;
    }

    .menu a:hover {
        color: #ffd700;
    }

    .content {
        padding: 20px;
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        animation: fadeIn 1s ease-in-out;
    }

    .farmer-profile {
        background-color: #2b2b2b;
        padding: 20px;
        border-radius: 10px;
        margin: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.6);
        width: 300px;
        text-align: center;
        transition: transform 0.3s, box-shadow 0.3s;
        opacity: 0;
        animation: slideUp 0.5s ease-out forwards;
    }

    .farmer-profile:hover {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.8);
    }

    .farmer-photo {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin-bottom: 15px;
        border: 3px solid #4caf50;
        transition: transform 0.3s ease-in-out;
    }

    .farmer-photo:hover {
        transform: scale(1.2);
    }

    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.9);
        justify-content: center;
        align-items: center;
        animation: fadeInModal 0.3s ease-out;
    }

    .modal-content img {
        max-width: 90%;
        border-radius: 10px;
    }

    .close {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 30px;
        font-weight: bold;
        cursor: pointer;
    }

    @keyframes fadeIn {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
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

    @keyframes slideUp {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }
        100% {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes fadeInModal {
        0% {
            opacity: 0;
        }
        100% {
            opacity: 1;
        }
    }
    </style>
</head>
<body>
    <div class="header">
        <h1>Farmers' Profiles</h1>
        <div class="menu">
            <a href="admin-dashboard-main.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <?php if (empty($farmers)): ?>
            <p>No farmers found.</p>
        <?php else: ?>
            <?php foreach ($farmers as $farmer): ?>
                <div class="farmer-profile">
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($farmer['profile_image']); ?>" 
                         alt="Farmer Photo" 
                         class="farmer-photo" 
                         onclick="openModal(this.src)">
                    <h3><?php echo htmlspecialchars($farmer['name']); ?></h3>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($farmer['email']); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($farmer['phone']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($farmer['location']); ?></p>
                    <p><strong>Crops Grown:</strong> <?php echo htmlspecialchars($farmer['crops_grown']); ?></p>
                    <p><strong>Land Size:</strong> <?php echo htmlspecialchars($farmer['land_size']); ?> acres</p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div id="imageModal" class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <img id="modalImg" src="" alt="Farmer Photo">
    </div>

    <script>
        function openModal(src) {
            const modal = document.getElementById("imageModal");
            const modalImg = document.getElementById("modalImg");
            modal.style.display = "flex";
            modalImg.src = src;
        }

        function closeModal() {
            const modal = document.getElementById("imageModal");
            modal.style.display = "none";
        }
    </script>
</body>
</html>
