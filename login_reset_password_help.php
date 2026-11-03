<?php
// Database connection
$host = "localhost";
$username = "root";
$password = "";
$database = "agri_connect"; 

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT name, phone FROM users WHERE type = 'admin'";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Help - Admin Contact</title>
    <link rel="stylesheet" href="style.css">
    <style>
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            overflow-x: hidden;
            background-color: #f8f8f8;
        }

        .login-background {
            position: relative;
            background-image: url('images/login-background1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .login-background::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('images/login-background1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            filter: blur(8px);
            z-index: -1;
        }

        .help-widget {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 25px rgba(0, 0, 0, 0.3);
            text-align: center;
            max-width: 400px;
            width: 100%;
            animation: fadeIn 1.5s ease;
            color: black; 
        }

        .help-widget h1 {
            font-size: 30px;
            margin-bottom: 20px;
            color: #333; 
        }

        .help-widget p {
            font-size: 18px;
            margin-bottom: 20px;
            color: black; 
        }

        .admin-details {
            margin-top: 20px;
        }

        .admin-card {
            background-color: #f1f1f1;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: left;
            color: black; 
        }

        .admin-card h2 {
            font-size: 18px;
            margin: 0 0 5px;
            color: black; 
        }

        .admin-card p {
            margin: 0;
            font-size: 16px;
            color: black; 
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #45a049;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="login-background">
        <div class="help-widget">
            <h1>Contact Admin for Password Reset</h1>
            <p>Admins are available to assist you with password resets between <strong>9:00 AM</strong> and <strong>5:00 PM</strong>.</p>
            <div class="admin-details">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='admin-card'>
                                <h2>" . htmlspecialchars($row['name']) . "</h2>
                                <p>Phone: " . htmlspecialchars($row['phone']) . "</p>
                              </div>";
                    }
                } else {
                    echo "<p>No admin contact information available.</p>";
                }
                ?>
            </div>
            <a href="login.html" class="back-btn">Back to login</a>
        </div>
    </div>
</body>
</html>
<?php
$conn->close();
?>
