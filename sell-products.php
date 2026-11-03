<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, price, image, distributor_name, distributor_number, email, description FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri-Connect - Sell Products</title>
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    background-color: #121212;
    background-image: url('images/sell-products-background.jpg');
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
    background-image: url('images/sell-products-background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(8px);
    z-index: -1;
    pointer-events: none;
}

.header {
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 30px 20px;
    text-align: center;
}

.header h1 {
    font-size: 36px;
    margin-bottom: 10px;
    color: white;
}

.header a {
    color: white;
    font-size: 16px;
    text-decoration: none;
    margin: 0 10px;
    transition: color 0.3s;
}

.header a:hover {
    color: #4CAF50;
}

.cart {
    position: fixed;
    top: 20px;
    right: 20px;
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border-radius: 50px;
    font-size: 18px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s;
}

.cart:hover {
    background-color: #45a049;
}

.content {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 20px;
    padding: 20px;
}

.product-item {
    background-color: rgba(20, 20, 20, 0.8);
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.product-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 15px rgba(0, 0, 0, 0.25), 0 0 15px rgba(76, 175, 80, 0.6);
}

.product-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    border-bottom: 2px solid #333;
}

.product-item .details {
    padding: 20px;
}

.product-item .details h3 {
    font-size: 22px;
    color: #4CAF50;
    margin-bottom: 10px;
}

.product-item .details p {
    color: #e6e6e6;
    font-size: 16px;
    margin-bottom: 8px;
}

.buttons {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.buttons button, .buy-now {
    padding: 10px 20px;
    font-size: 16px;
    border-radius: 5px;
    text-align: center;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.buttons button {
    background-color: #4CAF50;
    color: white;
    border: none;
}

.buttons button:hover {
    background-color: #45a049;
    transform: scale(1.05);
}

.buy-now {
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    display: block;
    text-align: center;
}

.buy-now:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}

.no-products {
    text-align: center;
    font-size: 18px;
    color: #e6e6e6;
}
    </style>
</head>
<body>
    <div class="cart">
        <a href="cart.php">🛒 Wishlist (<span id="cart-count">0</span> items)</a>
    </div>

    <div class="header">
        <h1>Available Products</h1>
        <a href="farmer-dashboard.html">Back to Dashboard</a> | 
        <a href="logout.php">Logout</a>
    </div>

    <div class="content">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product-item">
                <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['name']; ?>">
                <div class="details">
                    <h3><?php echo $row['name']; ?></h3>
                    <p>Price: ₹<?php echo $row['price']; ?></p>
                    <p>Distributor: <?php echo $row['distributor_name']; ?></p>
                    <p>Contact: <?php echo $row['distributor_number']; ?> | <?php echo $row['email']; ?></p>
                    <p>Description: <?php echo $row['description']; ?></p>
                    <div class="buttons">
                        <button onclick="addToCart(<?php echo $row['id']; ?>)">Add to wishlist</button>
                        <a href="payment_product.php?product_id=<?php echo $row['id']; ?>&name=<?php echo urlencode($row['name']); ?>&amount=<?php echo $row['price']; ?>" class="buy-now">
                            Buy Now ₹<?php echo $row['price']; ?>
                        </a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="no-products">
            <p>No products available.</p>
        </div>
    <?php endif; ?>

    <?php $conn->close(); ?>
</div>

    <script>
        function addToCart(productId) {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "add_to_cart.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.status === "success") {
                        alert(response.message);
                        const cartCountElement = document.getElementById("cart-count");
                        cartCountElement.innerText = parseInt(cartCountElement.innerText) + 1;
                    } else {
                        alert(response.message);
                    }
                }
            };

            xhr.send(`product_id=${productId}`);
        }
    </script>
</body>
</html>
