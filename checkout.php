<?php
session_start();


if (!isset($_SESSION['aadhar'])) {
    header("Location: login.php");
    exit();
}


$product_name = isset($_GET['product']) ? $_GET['product'] : 'Unknown Product';
$product_price = isset($_GET['price']) ? $_GET['price'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Agri Connect</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/payment-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .payment-container {
            margin: 20px;
            padding: 20px;
            background-color: rgb(41, 202, 130);
            border-radius: 5px;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .cart-summary {
            margin-bottom: 20px;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .payment-option, .delivery-address {
            margin-bottom: 20px;
        }

        .delivery-address textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
            margin-right: 10px;
        }

        .btn-payment {
            background-color: green;
            color: white;
            border: none;
        }

        .btn-cancel {
            background-color: red;
            color: white;
            border: none;
        }

        .payment-fields {
            display: none; 
            margin-top: 20px;
        }

        .payment-fields input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Payment Options</h1>
    </div>

    <div class="content payment-container">
        <div class="cart-summary">
            <h3>Product to Purchase:</h3>
            <div class="cart-item">
                <span><?php echo htmlspecialchars($product_name); ?></span>
                <span>₹<?php echo htmlspecialchars($product_price); ?></span>
            </div>
        </div>

        <div class="total">
            Total Amount: ₹<span><?php echo htmlspecialchars($product_price); ?></span>
        </div>

        <form action="process_payment.php" method="POST">
            <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product_name); ?>">
            <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product_price); ?>">

            <div class="payment-option">
                <label for="paymentMethod">Choose Payment Method:</label>
                <select id="paymentMethod" name="paymentMethod" onchange="showPaymentFields()">
                    <option value="">-- Select Payment Method --</option>
                    <option value="credit-card">Credit Card</option>
                    <option value="debit-card">Debit Card</option>
                    <option value="upi">UPI</option>
                    <option value="net-banking">Net Banking</option>
                </select>
            </div>

            
            <div id="paymentFields" class="payment-fields">
                <div id="cardFields" style="display: none;">
                    <h4>Card Details</h4>
                    <input type="text" name="card_number" placeholder="Card Number" required>
                    <input type="text" name="exp_date" placeholder="MM/YY" required>
                    <input type="text" name="cvv" placeholder="CVV" required>
                </div>
                <div id="upiFields" style="display: none;">
                    <h4>UPI Details</h4>
                    <input type="text" name="upi_id" placeholder="UPI ID" required>
                </div>
                <div id="netBankingFields" style="display: none;">
                    <h4>Net Banking Details</h4>
                    <input type="text" name="bank_name" placeholder="Bank Name" required>
                    <input type="text" name="account_number" placeholder="Account Number" required>
                </div>
            </div>

            <div class="delivery-address">
                <label for="deliveryAddress">Enter Delivery Address:</label>
                <textarea id="deliveryAddress" name="deliveryAddress" rows="4" placeholder="Your address here" required></textarea>
            </div>

            <button type="submit" class="btn btn-payment">Proceed to Payment</button>
            <button type="button" class="btn btn-cancel" onclick="cancelPayment()">Cancel</button>
        </form>
    </div>

    <script>
        function showPaymentFields() {
            const method = document.getElementById("paymentMethod").value;
            const paymentFields = document.getElementById("paymentFields");

           
            document.getElementById("cardFields").style.display = "none";
            document.getElementById("upiFields").style.display = "none";
            document.getElementById("netBankingFields").style.display = "none";

            
            switch (method) {
                case "credit-card":
                case "debit-card":
                    paymentFields.style.display = "block";
                    document.getElementById("cardFields").style.display = "block";
                    break;
                case "upi":
                    paymentFields.style.display = "block";
                    document.getElementById("upiFields").style.display = "block";
                    break;
                case "net-banking":
                    paymentFields.style.display = "block";
                    document.getElementById("netBankingFields").style.display = "block";
                    break;
                default:
                    paymentFields.style.display = "none"; 
            }
        }

        function cancelPayment() {
            window.location.href = 'cart.php';
        }
    </script>
</body>
</html>
