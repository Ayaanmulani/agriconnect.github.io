<?php
session_start();
$aadhar = $_SESSION['aadhar'];

// Database connection
$conn = new mysqli('localhost', 'root', '', 'agri_connect');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if (isset($_GET['work_id'])) {
    $work_id = $_GET['work_id'];
    $sql = "SELECT * FROM assign_work WHERE id = '$work_id' AND farmer_aadhar = '$aadhar'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
       
        $wages = $row['total_cost'];
        $revenue = $wages * 0.90;  
        $commission = $wages * 0.10;  
    } else {
        echo "<script>alert('Invalid work request'); window.location.href='farmer-dashboard.html';</script>";
        exit;
    }
}


if (isset($_POST['approve_payment'])) {
    $id = $_POST['id'];
    $payment_time = date('Y-m-d H:i:s');

    
    $done_sql = "INSERT INTO done_work (id, farmer_aadhar, worker_aadhar, work_details, work_location, conditions, crops, hours, total_cost, booking_date, start_time, end_time, selected_date, approval_time, wages, revenue, commission) 
                 SELECT id, farmer_aadhar, worker_aadhar, work_details, work_location, conditions, crops, hours, total_cost, booking_date, start_time, end_time, selected_date, '$payment_time', '$wages', '$revenue', '$commission'
                 FROM assign_work WHERE id = $id";
    
    if ($conn->query($done_sql)) {
        
        $delete_sql = "DELETE FROM assign_work WHERE id = $id";
        $conn->query($delete_sql);
        echo "<script>alert('Payment successful and work request completed!'); window.location.href='farmer-dashboard.html';</script>";
    } else {
        echo "<script>alert('Error processing payment. Please try again.');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Approval</title>
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

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        input, select, button {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 5px;
            box-sizing: border-box;
        }

        input:focus, select:focus, button:focus {
            border-color: #4CAF50;
            outline: none;
        }

        button {
            background-color: #4CAF50;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            font-size: 12px;
        }

        #termsRules {
            display: none;
            background-color: #f8f8f8;
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #termsRules h3 {
            font-size: 18px;
            color: #333;
        }

        #termsRules ul {
            list-style-type: none;
            padding-left: 20px;
        }

        #termsRules ul li {
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<div class="container">
        <h1>Payment Approval</h1>
        <form method="POST" action="">
            
            <input type="hidden" name="id" value="<?php echo isset($work_id) ? $work_id : ''; ?>">
            
            <h2>Work Details</h2>
            <div class="form-group">
                <label>Work Description</label>
                <p><?php echo isset($row) ? $row['work_details'] : ''; ?></p>
            </div>

            <div class="form-group">
                <label>Total Cost</label>
                <p><?php echo isset($wages) ? '₹' . number_format($wages, 2) : ''; ?></p>
            </div>

            <div class="form-group">
                <label>Revenue (90%)</label>
                <p><?php echo isset($revenue) ? '₹' . number_format($revenue, 2) : ''; ?></p>
            </div>

            <div class="form-group">
                <label>Commission (10%)</label>
                <p><?php echo isset($commission) ? '₹' . number_format($commission, 2) : ''; ?></p>
            </div>

            <h2>Payment Options</h2>
            <div class="form-group">
                <label for="paymentMethod">Select Payment Method</label>
                <select id="paymentMethod" name="paymentMethod" required>
                    <option value="" disabled selected>Select Payment Method</option>
                    <option value="creditCard">Credit Card</option>
                    <option value="debitCard">Debit Card</option>
                    <option value="upi">UPI</option>
                    <option value="wallet">Wallet</option>
                    <option value="googlePay">Google Pay</option>
                    <option value="phonePe">PhonePe</option>
                </select>
            </div>

            <div id="phoneField" class="form-group" style="display: none;">
                <label for="mobilenumber">Phone Number</label>
                <input type="tel" id="mobilenumber" name="mobilenumber" maxlength="10">
            </div>

            <div id="cardFields" style="display: none;">
                <div class="form-group">
                    <label for="cardNumber">Card Number</label>
                    <input type="text" id="cardNumber" name="cardNumber" maxlength="16">
                </div>
                <div class="form-group">
                    <label for="expiryDate">Expiry Date</label>
                    <input type="month" id="expiryDate" name="expiryDate">
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="password" id="cvv" name="cvv" maxlength="3">
                </div>
            </div>

            <div class="form-group">
                <input type="checkbox" id="terms" name="terms" required>
                <label for="terms">I agree to the <a href="javascript:void(0);" id="showTerms">Terms & Conditions</a>.</label>
            </div>

            <div id="termsRules">
                <h3>Terms and Conditions</h3>
                <ul>
                    <li>You must be at least 18 years old to make a payment.</li>
                    <li>All payments are non-refundable.</li>
                </ul>
            </div>

            <div class="form-group">
                <button type="submit" name="approve_payment">Approve Payment</button>
            </div>
        </form>
    </div>
    <script>
        
        document.getElementById('showTerms').addEventListener('click', function() {
            var termsRules = document.getElementById('termsRules');
            termsRules.style.display = (termsRules.style.display === 'none' || termsRules.style.display === '') ? 'block' : 'none';
        });
        
        
        document.getElementById('paymentMethod').addEventListener('change', function () {
        const selectedMethod = this.value;
        const cardFields = document.querySelectorAll('#cardNumber, #expiryDate, #cvv'); 
        const cardFieldGroups = document.querySelectorAll('.form-group.card-field'); 
        const phoneField = document.getElementById('phoneField');

        if (selectedMethod === 'upi' || selectedMethod === 'wallet' || selectedMethod === 'googlePay' || selectedMethod === 'phonePe') {
            cardFields.forEach(field => field.closest('.form-group').style.display = 'none'); 
            phoneField.style.display = 'block'; 
        } else if (selectedMethod === 'creditCard' || selectedMethod === 'debitCard') {
            cardFields.forEach(field => field.closest('.form-group').style.display = 'block'); 
            phoneField.style.display = 'none'; // Hide phone number input
        } else {
            cardFields.forEach(field => field.closest('.form-group').style.display = 'none'); 
            phoneField.style.display = 'none';
        }
    });

    
    function displayError(elementId, message) {
        document.getElementById(elementId).innerText = message;
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
        }
    </script>
</body>
</html>