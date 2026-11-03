<?php

$user_aadhar = isset($_POST['user_aadhar']) ? $_POST['user_aadhar'] : null; 
$name = isset($_GET['name']) ? $_GET['name'] : 'Unknown Product'; 
$quantity = isset($_POST['quantity']) ? $_POST['quantity'] : 1; 
$total_price = isset($_GET['amount']) ? $_GET['amount'] : 100.00; 

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
}
$amount = isset($_GET['amount']) ? $_GET['amount'] : 100.00; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Online Payment Page</title>
    <style>
       
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        h2 {
            color: #444;
            margin-bottom: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 5px;
            box-sizing: border-box;
        }

        input:focus, select:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        button.btn {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button.btn:hover {
            background-color: #45a049;
        }

        .success-message {
            text-align: center;
            margin-top: 20px;
            padding: 15px;
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            display: none;
        }

        select {
            font-size: 14px;
        }

        input[type="password"] {
            letter-spacing: 2px;
        }

        /* Terms and Conditions */
        #termsRules {
            display: none;
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 14px;
            color: #333;
        }

        #termsRules h3 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure Online Payment</h1>
        <form id="paymentForm" class="payment-form" method="POST" action="payment_process_product.php">
           
    <h2>Billing Information</h2>
    <div class="form-group">
        <label for="fullName">Full Name</label>
        <input type="text" id="fullName" name="fullName" placeholder="Enter your full name" required>
        <span class="error-message" id="fullNameError"></span>
    </div>
    <div class="form-group">
        <label for="aadharNumber">Aadhar Number</label>
        <input type="text" id="aadharNumber" name="aadharNumber" placeholder="Enter 12-digit Aadhar Number" maxlength="12" required>
        <span class="error-message" id="aadharError"></span>
    </div>
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="example@domain.com" required>
        <span class="error-message" id="emailError"></span>
    </div>
    <div class="form-group">
        <label for="address">Billing Address</label>
        <input type="text" id="address" name="address" placeholder="123 Main Street" required>
    </div>

    <h2>Payment Details</h2>
    <div class="form-group">
        <span class="error-message" id="paymentAmountError"></span>
    </div>
    <div class="form-group card-field">
        <label for="cardNumber">Card Number</label>
        <input type="text" id="cardNumber" name="cardNumber" placeholder="1111-2222-3333-4444" maxlength="16">
        <span class="error-message" id="cardError"></span>
    </div>
    <div class="form-group card-field">
        <label for="expiryDate">Expiry Date</label>
        <input type="month" id="expiryDate" name="expiryDate">
        <span class="error-message" id="expiryError"></span>
    </div>
    <div class="form-group card-field">
        <label for="cvv">CVV</label>
        <input type="password" id="cvv" name="cvv" maxlength="3" placeholder="123">
        <span class="error-message" id="cvvError"></span>
    </div>
    <div id="phoneField" class="form-group" style="display: none;">
        <label for="mobilenumber">Phone Number</label>
        <input type="tel" id="mobilenumber" name="mobilenumber" placeholder="Enter your phone number" maxlength="10">
        <span class="error-message" id="phoneError"></span>
    </div>

   
    <h2>Payment Options</h2>
    <div class="form-group">
        <select id="paymentMethod" name="paymentMethod" required>
            <option value="" disabled selected>Select Payment Method</option>
            <option value="creditCard">Credit Card</option>
            <option value="debitCard">Debit Card</option>
            <option value="upi">UPI</option>
            <option value="wallet">Wallet</option>
            <option value="googlePay">Google Pay</option>
            <option value="phonePe">PhonePe</option>
        </select>
        <span class="error-message" id="methodError"></span>
    </div>

 
    <div class="form-group">
        <input type="checkbox" id="terms" name="terms" required>
        <label for="terms">I agree to the <a href="javascript:void(0);" id="showTerms">Terms & Conditions</a>.</label>
        <span class="error-message" id="termsError"></span>
    </div>

    <div id="termsRules">
        <h3>Terms and Conditions</h3>
        <ul>
            <li>You must be at least 18 years old to make a payment.</li>
            <li>All payments are non-refundable.</li>
            <li>By agreeing to the terms, you authorize us to process payments for your purchases.</li>
            <li>We are not responsible for any transaction failures caused by third-party services.</li>
        </ul>
    </div>


    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
    <input type="hidden" name="name" value="<?php echo htmlspecialchars($name); ?>"> <!-- Product Name -->
    <input type="hidden" name="paymentAmount" value="<?php echo htmlspecialchars($amount); ?>">

    
    <input type="number" name="quantity" value="1" min="1" required onchange="updateTotalPrice(<?php echo $amount; ?>, this.value)">
    
   
    <input type="hidden" name="total_price" id="totalPrice" value="<?php echo $total_price; ?>">

    <p>Total Price: ₹<span id="displayPrice"><?php echo $total_price; ?></span></p>


            <button type="submit" class="btn">Pay Now</button>
        </form>
        <input type="hidden" id="paymentAmount" name="paymentAmount" value="<?php echo htmlspecialchars($amount); ?>">
      
        <div id="successMessage" class="success-message"></div>
    </div>

    <script>

    document.getElementById('paymentMethod').addEventListener('change', function () {
        const selectedMethod = this.value;
        const cardFields = document.querySelectorAll('.card-field');
        const phoneField = document.getElementById('phoneField');

        if (selectedMethod === 'upi' || selectedMethod === 'wallet' || selectedMethod === 'googlePay' || selectedMethod === 'phonePe') {
            cardFields.forEach(field => field.style.display = 'none');
            phoneField.style.display = 'block';
        } else if (selectedMethod === 'creditCard' || selectedMethod === 'debitCard') {
            cardFields.forEach(field => field.style.display = 'block');
            phoneField.style.display = 'none';
        } else {
            cardFields.forEach(field => field.style.display = 'none');
            phoneField.style.display = 'none';
        }
    });

   
    document.getElementById('paymentForm').addEventListener('submit', function (event) {
        event.preventDefault();
        resetErrors();

        let isValid = true;

        
        const fullName = document.getElementById('fullName').value;
        if (!fullName) {
            displayError('fullNameError', 'Full name is required.');
            isValid = false;
        }

       
        const aadharNumber = document.getElementById('aadharNumber').value;
        if (!aadharNumber || aadharNumber.length !== 12) {
            displayError('aadharError', 'Please enter a valid 12-digit Aadhar number.');
            isValid = false;
        }

      
        const email = document.getElementById('email').value;
        if (!email || !validateEmail(email)) {
            displayError('emailError', 'Please enter a valid email address.');
            isValid = false;
        }

        const paymentMethod = document.getElementById('paymentMethod').value;
        if (!paymentMethod) {
            displayError('methodError', 'Please select a payment method.');
            isValid = false;
        }

        if (paymentMethod === 'upi' || paymentMethod === 'wallet' || paymentMethod === 'googlePay' || paymentMethod === 'phonePe') {
            const mobilenumber = document.getElementById('mobilenumber').value;
            if (!mobilenumber || mobilenumber.length !== 10) {
                displayError('phoneError', 'Please enter a valid 10-digit phone number.');
                isValid = false;
            }
        } else if (paymentMethod === 'creditCard' || paymentMethod === 'debitCard') {
          
            const cardNumber = document.getElementById('cardNumber').value;
            if (!cardNumber || cardNumber.length < 16) {
                displayError('cardError', 'Please enter a valid card number.');
                isValid = false;
            }

            const expiryDate = document.getElementById('expiryDate').value;
            if (!expiryDate) {
                displayError('expiryError', 'Expiry date is required.');
                isValid = false;
            }

            const cvv = document.getElementById('cvv').value;
            if (!cvv || cvv.length < 3) {
                displayError('cvvError', 'Please enter a valid CVV.');
                isValid = false;
            }
        }

        
        const terms = document.getElementById('terms').checked;
        if (!terms) {
            displayError('termsError', 'You must agree to the Terms & Conditions.');
            isValid = false;
        }

      
        if (isValid) {
            this.submit();
        }
    });

    function displayError(id, message) {
        document.getElementById(id).textContent = message;
    }

   
    function resetErrors() {
        document.querySelectorAll('.error-message').forEach(error => error.textContent = '');
    }

    
    function validateEmail(email) {
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
</script>
<script>
    function updateTotalPrice(pricePerUnit, quantity) {
        const totalPrice = pricePerUnit * quantity;
        document.getElementById('totalPrice').value = totalPrice;
        document.getElementById('displayPrice').textContent = totalPrice;
    }
</script>
</body>
</html>
