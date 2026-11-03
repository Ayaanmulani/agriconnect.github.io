<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['aadhar']) || empty($_SESSION['aadhar'])) {
    header('Location: login.php');
    exit();
}

$aadhar = mysqli_real_escape_string($conn, $_SESSION['aadhar']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('CSRF token validation failed');
    }
}
$csrf_token = bin2hex(random_bytes(32));
$_SESSION['csrf_token'] = $csrf_token;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_SANITIZE_NUMBER_INT);
        $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT) ?: 1;
        
        $stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE aadhar = ? AND product_id = ?");
        $stmt->bind_param("si", $aadhar, $product_id);
        $stmt->execute();
        $cart_item = $stmt->get_result()->fetch_assoc();
        
        if ($cart_item) {
            $stmt = $conn->prepare("
                UPDATE cart 
                SET quantity = quantity + ?,
                    total_price = (SELECT price FROM products WHERE id = ?) * (quantity + ?)
                WHERE aadhar = ? AND product_id = ?
            ");
            $stmt->bind_param("iissi", $quantity, $product_id, $quantity, $aadhar, $product_id);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("
                INSERT INTO cart (aadhar, product_id, quantity, total_price)
                SELECT ?, id, ?, price * ? FROM products WHERE id = ?
            ");
            $stmt->bind_param("siii", $aadhar, $quantity, $quantity, $product_id);
            $stmt->execute();
        }
    }
}

$stmt = $conn->prepare("
    SELECT 
        cart.id AS cart_id,
        products.name,
        products.price,
        cart.quantity,
        cart.total_price
    FROM cart 
    JOIN products ON cart.product_id = products.id 
    WHERE cart.aadhar = ?
    ORDER BY cart.id DESC
");
$stmt->bind_param("s", $aadhar);
$stmt->execute();
$cart_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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

        h1 {
            text-align: center;
            font-size: 36px;
            color: white;
            margin-top: 50px;
        }

        .cart-table {
            margin: 20px auto;
            padding: 20px;
            max-width: 1200px;
            background-color: rgba(0, 0, 0, 0.8);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            color: white;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #444;
        }

        th {
            background-color: #4CAF50;
        }

        tr:hover {
            background-color: rgba(76, 175, 80, 0.1);
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        input[type="number"] {
            width: 60px;
            padding: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            text-align: center;
            background-color: #222;
            color: white;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    <h1>WishList</h1>
    <div style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.location.href='sell-products.php'" class="btn">Back to Sell Products</button>
    </div>
    <div class="cart-table">
        <?php if ($cart_items->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Price (₹)</th>
                        <th>Quantity</th>
                        <th>Total (₹)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($item = $cart_items->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" required onchange="updateTotalPrice(<?php echo $item['price']; ?>, this.value)" readonly>
                            </td>
                            <td>₹<span id="total_<?php echo $item['cart_id']; ?>"><?php echo number_format($item['total_price'], 2); ?></span></td>
                            <td>
                                <a href="payment_product.php?product_id=<?php echo $item['cart_id']; ?>&quantity=<?php echo $item['quantity']; ?>&amount=<?php echo $item['total_price']; ?>" class="btn">Buy Now</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </div>
    
    <script>
    function updateTotalPrice(price, quantity) {
        let total = price * quantity;
        document.getElementById('total_' + event.target.name).textContent = total.toFixed(2);
    }
    </script>
</body>
</html>
