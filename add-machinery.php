<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        
        $upload_dir = "uploads/";
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $machinery_name = filter_input(INPUT_POST, 'machinery_name', FILTER_SANITIZE_STRING);
        $owner_name = filter_input(INPUT_POST, 'owner_name', FILTER_SANITIZE_STRING);
        $owner_contact = filter_input(INPUT_POST, 'owner_contact', FILTER_SANITIZE_STRING);
        $location = filter_input(INPUT_POST, 'location', FILTER_SANITIZE_STRING);
        $total_working_hours = filter_input(INPUT_POST, 'total_working_hours', FILTER_VALIDATE_INT);
        $owner_percentage = filter_input(INPUT_POST, 'owner_percentage', FILTER_VALIDATE_FLOAT);
        $profit = filter_input(INPUT_POST, 'profit', FILTER_VALIDATE_FLOAT);

      
        if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            
            $file_extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $allowed_extensions = array('jpg', 'jpeg', 'png', 'bmp');
            
            if (!in_array($file_extension, $allowed_extensions)) {
                throw new Exception("Invalid file type. Only JPG, JPEG, PNG, and BMP files are allowed.");
            }

            $unique_filename = uniqid('machinery_', true) . '.' . $file_extension;
            $target_file = $upload_dir . $unique_filename;

         
            if ($_FILES['image']['size'] > 5000000) {
                throw new Exception("File is too large. Maximum size is 5MB.");
            }

            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                
                $sql = "INSERT INTO machinery_rentals (
                    image, machinery_name, owner_name, owner_contact, 
                    location, total_working_hours, owner_percentage, profit
                ) VALUES (
                    :image, :machinery_name, :owner_name, :owner_contact,
                    :location, :total_working_hours, :owner_percentage, :profit
                )";

                $stmt = $conn->prepare($sql);
                
                
                $stmt->bindParam(':image', $target_file);
                $stmt->bindParam(':machinery_name', $machinery_name);
                $stmt->bindParam(':owner_name', $owner_name);
                $stmt->bindParam(':owner_contact', $owner_contact);
                $stmt->bindParam(':location', $location);
                $stmt->bindParam(':total_working_hours', $total_working_hours);
                $stmt->bindParam(':owner_percentage', $owner_percentage);
                $stmt->bindParam(':profit', $profit);

                if ($stmt->execute()) {
                    $success_message = "Machinery added successfully!";
                }
            } else {
                throw new Exception("Failed to upload image.");
            }
        } else {
            throw new Exception("Please select an image.");
        }
    } catch (Exception $e) {
        $error_message = "Error: " . $e->getMessage();
    } catch (PDOException $e) {
        $error_message = "Database Error: " . $e->getMessage();
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Machinery for Rent</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background: linear-gradient(135deg, #6e7dff, #4e5f92);
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            animation: fadeIn 2s ease-in-out;
        }

        .container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            width: 450px;
            padding: 40px;
            text-align: center;
            opacity: 0;
            animation: fadeIn 1s forwards 0.5s;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
            font-weight: bold;
            animation: slideUp 0.5s ease-out forwards;
        }

        .form-group {
            margin-bottom: 20px;
            animation: slideUp 0.5s ease-out forwards;
        }

        label {
            font-size: 14px;
            font-weight: 500;
            color: #444;
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="number"], input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background-color: #f9f9f9;
            transition: border 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="file"]:focus {
            border-color: #4e5f92;
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #4e5f92;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            opacity: 0;
            animation: fadeIn 1s ease-out forwards 1.5s;
        }

        button:hover {
            background-color: #3a4b72;
        }

        .message {
            margin-top: 20px;
            font-size: 16px;
            font-weight: 500;
        }

        .success {
            color: #28a745;
        }

        .error {
            color: #e74c3c;
        }

        .form-group input[type="file"] {
            padding: 8px;
            font-size: 14px;
        }

        .form-group input[type="file"]:focus {
            border-color: #4e5f92;
        }

        @keyframes fadeIn {
            0% {
                opacity: 0;
            }
            100% {
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Machinery for Rent</h2>

        <?php if (!empty($success_message)): ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="machinery_name">Machinery Name</label>
                <input type="text" name="machinery_name" id="machinery_name" required>
            </div>

            <div class="form-group">
                <label for="owner_name">Owner Name</label>
                <input type="text" name="owner_name" id="owner_name" required>
            </div>

            <div class="form-group">
                <label for="owner_contact">Owner Contact</label>
                <input type="text" name="owner_contact" id="owner_contact" required>
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" required>
            </div>

            <div class="form-group">
                <label for="total_working_hours">Total Working Hours</label>
                <input type="number" name="total_working_hours" id="total_working_hours" required>
            </div>

            <div class="form-group">
                <label for="owner_percentage">Owner Percentage (%)</label>
                <input type="number" name="owner_percentage" id="owner_percentage" required>
            </div>

            <div class="form-group">
                <label for="profit">Profit Amount (INR)</label>
                <input type="number" name="profit" id="profit" required>
            </div>

            <div class="form-group">
                <label for="image">Upload Machinery Image</label>
                <input type="file" name="image" id="image" required>
            </div>

            <button type="submit">Add Machinery</button>
        </form>
    </div>
</body>
</html>

