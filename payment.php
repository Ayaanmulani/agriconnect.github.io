<?php

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
        <form id="paymentForm" class="payment-form" method="POST" action="payment_process.php">
           
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
            <div id="paymentDetails" style="display:none;">
                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" name="cardNumber" placeholder="1111-2222-3333-4444" maxlength="16" required>
                    <span class="error-message" id="cardError"></span>
                </div>
                <div class="form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="month" id="expiryDate" name="expiryDate" required>
                    <span class="error-message" id="expiryError"></span>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="password" id="cvv" name="cvv" maxlength="3" placeholder="123" required>
                    <span class="error-message" id="cvvError"></span>
                </div>
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
      
            <input type="hidden" id="paymentAmount" name="paymentAmount" value="<?php echo htmlspecialchars($amount); ?>">
            <button type="submit" class="btn">Pay Now</button>
        </form>

       
        <div id="successMessage" class="success-message"></div>
    </div>

    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
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
            if (!email) {
                displayError('emailError', 'Email address is required.');
                isValid = false;
            } else if (!validateEmail(email)) {
                displayError('emailError', 'Please enter a valid email address.');
                isValid = false;
            }

            
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

           
            const paymentMethod = document.getElementById('paymentMethod').value;
            if (!paymentMethod) {
                displayError('methodError', 'Please select a payment method.');
                isValid = false;
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
            const errorElement = document.getElementById(id);
            errorElement.textContent = message;
        }

        
        function resetErrors() {
            const errorElements = document.querySelectorAll('.error-message');
            errorElements.forEach(error => error.textContent = '');
        }

        
        function validateEmail(email) {
            const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return regex.test(email);
        }

        
        document.getElementById('terms').addEventListener('change', function() {
            const termsRules = document.getElementById('termsRules');
            if (this.checked) {
                termsRules.style.display = 'block';
            } else {
                termsRules.style.display = 'none';
            }
        });
    </script>
        <script>
        document.getElementById("paymentMethod").addEventListener("change", function() {
            var paymentMethod = this.value;
            var paymentDetailsSection = document.getElementById("paymentDetails");

            if (paymentMethod === "creditCard" || paymentMethod === "debitCard") {
                paymentDetailsSection.style.display = "block";
            } else {
                paymentDetailsSection.style.display = "none";
            }
        });
    </script>
</body>
</html>
