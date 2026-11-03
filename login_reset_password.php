<?php
// Database connection 
$host = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database = 'agri_connect'; 

$conn = new mysqli($host, $username, $password, $database);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'forgot_password') {
    $aadhar = trim($_POST['aadhar']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $new_password = trim($_POST['new_password']);

    
    if (empty($aadhar) || empty($email) || empty($phone) || empty($new_password)) {
        echo "<script>
            alert('All fields are required.');
            window.location.href = 'forgot_password.html';
        </script>";
        exit();
    }

    
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

   
    $query = "SELECT id FROM users WHERE aadhar = ? AND email = ? AND phone = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sss", $aadhar, $email, $phone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
     
        $update_query = "UPDATE users SET password = ? WHERE aadhar = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("ss", $hashed_password, $aadhar);

        if ($update_stmt->execute()) {
            echo "<script>
                alert('Password updated successfully. Please login.');
                window.location.href = 'index.html';
            </script>";
        } else {
            echo "<script>
                alert('Error updating password. Please try again.');
                window.location.href = 'forgot_password.html';
            </script>";
        }
        $update_stmt->close();
    } else {
        echo "<script>
            alert('User not found or details do not match.');
            window.location.href = 'forgot_password.html';
        </script>";
    }

    $stmt->close();
}
$conn->close();
?>
