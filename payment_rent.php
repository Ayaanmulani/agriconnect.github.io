<?php
$machinery_name = isset($_POST['machinery_name']) ? $_POST['machinery_name'] : '';

$amount = isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : 100.00; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Online Payment</title>
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

        .amount-display {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Secure Online Payment</h1>

        <div class="amount-display">
        Per Hour Amount: ₹<?php echo number_format((float)$amount, 2, '.', ''); ?>
        </div>

        <form id="paymentForm" class="payment-form" method="POST" action="payment_process_rent.php">
   
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
        <label for="paymentAmount">Payment Amount</label>
        <input 
            type="number" 
            id="paymentAmount" 
            name="paymentAmount" 
            value="<?php echo htmlspecialchars($_POST['amount']); ?>" 
            readonly
        >
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
    <label for="machinery_id">Machinery ID:</label>
<input type="text" id="machinery_id" name="machinery_id" value="<?php echo htmlspecialchars($_POST['machinery_id']); ?>" readonly><br><br>

<label for="machinery_name">Machinery Name:</label>
Name:</label> <input type="text" id="machinery_name" name="machinery_name" value="<?php echo htmlspecialchars($machinery_name); ?>" readonly><br><br>

<label for="rental_hours">Rental Hours:</label>
<input type="number" id="rental_hours" name="rental_hours" value="<?php echo htmlspecialchars($_POST['rental_hours']); ?>" readonly><br><br>

<label for="delivery_location">Delivery Location:</label>
<input type="text" id="delivery_location" name="delivery_location" value="<?php echo htmlspecialchars($_POST['delivery_location']); ?>" readonly><br><br>

<label for="amount">Amount:</label>
<input type="number" id="amount" name="amount" value="<?php echo htmlspecialchars($_POST['amount']); ?>" readonly><br><br>

    <button type="submit" class="btn">Pay Now</button>
</form>


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

document.addEventListener("DOMContentLoaded", function() {
   
    var machineryId = document.getElementById("modal-machinery-id").value;
    var machineryName = document.getElementById("modal-machinery-name").value;
    var amount = document.getElementById("amount").value;

  
    document.getElementById("machinery_id").value = machineryId;
    document.getElementById("machinery_id").readOnly = true;
    document.getElementById("machinery_name").value = machineryName;
    document.getElementById("machinery_name").readOnly = true; 
    document.getElementById("amount").value = amount;
    document.getElementById("amount").readOnly = true; 

   
    document.getElementById("rental_hours").addEventListener("input", calculateAmount);
});


function calculateAmount() {
    const rentalHours = document.getElementById('rental_hours').value;
    const pricePerHour = 100;  
    const totalAmount = rentalHours * pricePerHour;
    
    document.getElementById('total-amount').textContent = "Total Amount: " + totalAmount;
    document.getElementById('amount').value = totalAmount;
}
</script>

</body>
</html>
