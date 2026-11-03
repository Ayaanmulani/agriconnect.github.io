<?php


// Database connection parameters
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "agri_connect"; 


$success_message = "";
$error_message = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    
    $conn = new mysqli($servername, $username, $password, $dbname);

    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $distributor_name = $_POST['distributor_name'];
    $distributor_number = $_POST['distributor_number'];
    $email = $_POST['email'];
    $description = $_POST['description'];

    
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "images/";
        $target_file = $target_dir . $image_name;

        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {

            // Insert product data into the database
            $sql = "INSERT INTO products (image, name, price, distributor_name, distributor_number, email, description)
                    VALUES ('$target_file', '$product_name', '$price', '$distributor_name', '$distributor_number', '$email', '$description')";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Product added successfully!";
            } else {
                $error_message = "Error: " . $conn->error;
            }
        } else {
            $error_message = "Failed to upload the image.";
        }
    } else {
        $error_message = "No image file selected or upload error.";
    }

    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
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

        input[type="text"], input[type="email"], input[type="number"], textarea, input[type="file"] {
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

        input[type="text"]:focus, input[type="email"]:focus, input[type="number"]:focus, textarea:focus, input[type="file"]:focus {
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
        <h2>Add New Product</h2>

        <?php if (!empty($success_message)): ?>
            <p class="message success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <p class="message error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form action="add-product.php" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" name="product_name" id="product_name" required>
            </div>

            <div class="form-group">
                <label for="price">Price (₹)</label>
                <input type="number" name="price" id="price" required>
            </div>

            <div class="form-group">
                <label for="distributor_name">Distributor Name</label>
                <input type="text" name="distributor_name" id="distributor_name" required>
            </div>

            <div class="form-group">
                <label for="distributor_number">Distributor Contact Number</label>
                <input type="text" name="distributor_number" id="distributor_number" required>
            </div>

            <div class="form-group">
                <label for="email">Distributor Email</label>
                <input type="email" name="email" id="email" required>
            </div>

            <div class="form-group">
                <label for="description">Product Description</label>
                <textarea name="description" id="description" rows="4" required></textarea>
            </div>

            <div class="form-group">
                <label for="image">Product Image</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>

            <button type="submit">Add Product</button>
        </form>
    </div>
</body>
</html>
