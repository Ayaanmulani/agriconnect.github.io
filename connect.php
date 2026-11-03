<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "agri_connect"; 


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $userType = $_POST['type'] ?? '';
    $aadhar = $_POST['aadhar'] ?? '';
    $name = $_POST['name'] ?? '';
    $location = $_POST['location'] ?? '';
    $password = $_POST['password'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $landSize = $_POST['landSize'] ?? '';
    $cropsGrown = $_POST['cropsGrown'] ?? '';

    
    $totalWorkTime = $userType === 'worker' ? ($_POST['totalWorkTime'] ?? NULL) : NULL;
    $perHourRate = $userType === 'worker' ? ($_POST['perHourRate'] ?? NULL) : NULL;
    $experience = $userType === 'worker' ? ($_POST['experience'] ?? NULL) : NULL;

    
    if ($userType === 'admin') {
        $adminPassKey = $_POST['adminPassKey'] ?? '';
        if ($adminPassKey !== '123') {
            echo "<script>
                alert('Invalid Admin Pass Key. Registration failed.');
                window.location.href = 'registration.php';
            </script>";
            exit();
        }
    }

   
    if (!preg_match('/^\d{12}$/', $aadhar)) {
        echo "<script>
            alert('Invalid Aadhar number. It must be exactly 12 digits.');
            window.location.href = 'registration.php';
        </script>";
        exit();
    }

    
    if ($userType !== 'farmer') {
        $landSize = NULL;  
        $cropsGrown = NULL;  
    }    

    
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

   
    if (isset($_FILES['profileImage']) && $_FILES['profileImage']['error'] == 0) {
        $image = $_FILES['profileImage']['tmp_name'];
        $profileImage = addslashes(file_get_contents($image)); 
    } else {
        $profileImage = NULL;
    }

   
    $sql = "INSERT INTO users (type, aadhar, name, location, email, phone, profile_image, password, land_size, crops_grown, total_work_time, per_hour_rate, experience)
    VALUES ('$userType', '$aadhar', '$name', '$location', '$email', '$phone', '$profileImage', '$hashedPassword', '$landSize', '$cropsGrown', '$totalWorkTime', '$perHourRate', '$experience')";
    
  
    if ($conn->query($sql) === TRUE) {
        echo "<script>
            alert('Registration successful!');
            window.location.href = 'index.html';
        </script>";
    } else {
        echo "<script>
            alert('Registration failed. Please try again.');
            window.location.href = 'register.html';
        </script>";
    }

   
    $conn->close();
}
?>
