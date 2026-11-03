<?php
session_start();


$type = $_POST["type"] ?? '';
$aadhar = trim($_POST['aadhar'] ?? ''); 
$password = $_POST['password'] ?? '';


if (empty($aadhar) || empty($password)) {
    echo "Aadhar number or password cannot be empty.";
    exit();
}


$conn = new mysqli("localhost", "root", "", "agri_connect");


if ($conn->connect_error) {
    die("Failed to connect: " . $conn->connect_error);
} else {
    
    $stmt = $conn->prepare("SELECT id, type, password FROM users WHERE aadhar = ?");
    $stmt->bind_param("s", $aadhar); // Bind the Aadhar number
    $stmt->execute();
    $stmt->store_result();

    
    if ($stmt->num_rows > 0) {
        
        $stmt->bind_result($userId, $fetchedUserType, $hashedPassword);
        $stmt->fetch();

        
        if (password_verify($password, $hashedPassword)) {
           
            $_SESSION['aadhar'] = $aadhar;  
            $_SESSION['user_id'] = $userId; 
            
            switch ($fetchedUserType) {
                case 'farmer':
                    header("Location: farmer-dashboard.html");
                    exit();
                case 'admin':
                    header("Location: admin-dashboard-main.php");
                    exit();
                case 'worker':
                    header("Location: worker-dashboard.html");
                    exit();
                default:
                    echo "Invalid user type.";
            }
        } else {
            echo "<script>
                alert('Invalid Aadhar number or password.');
                window.location.href = 'index.html';
            </script>";
        }
        } else {
            
            echo "<script>
                alert('No user found with that Aadhar number. Please register first.');
                window.location.href = 'register.html';
            </script>";
        }
        

    
    $stmt->close();
    $conn->close();
}
