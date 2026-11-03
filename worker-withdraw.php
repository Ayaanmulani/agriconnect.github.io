<?php session_start();
if (!isset($_SESSION['aadhar'])) {
    echo "Aadhar not found. Please login first.";
    exit;
}
$aadhar = $_SESSION['aadhar'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Withdrawal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2563eb;
            --primary-hover: #1d4ed8;
            --background: #f8fafc;
            --card-bg: #ffffff;
            --text-color: #1e293b;
            --border-color: #e2e8f0;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--background);
            color: var(--text-color);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 480px;
            background: var(--card-bg);
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            position: relative;
        }

        .back-button {
            position: absolute;
            top: 1.5rem;
            left: 1.5rem;
            background: none;
            border: none;
            color: var(--text-color);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.875rem;
            padding: 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .back-button:hover {
            background-color: var(--background);
            color: var(--primary-color);
        }

        h2 {
            color: var(--text-color);
            font-size: 1.5rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
            padding-top: 1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #64748b;
        }

        input[type="text"],
        input[type="number"] {
            width: 100%;
            padding: 0.75rem 1rem 0.75rem 2.5rem;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background-color: #fff;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        input:read-only {
            background-color: #f1f5f9;
            cursor: not-allowed;
        }

        button[type="submit"] {
            width: 100%;
            padding: 0.75rem 1.5rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover {
            background-color: var(--primary-hover);
        }

        #responseMessage {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            font-size: 0.875rem;
        }

        .success {
            background-color: #dcfce7;
            color: #166534;
        }

        .error {
            background-color: #fee2e2;
            color: #991b1b;
        }
    </style>
</head>
<body>
    <div class="container">
        <button onclick="window.location.href='worker-dashboard.html'" class="back-button">
            <i class="fas fa-arrow-left"></i> Back
        </button>
        <h2>Withdraw Amount</h2>
        <form id="withdrawForm" method="POST" action="worker-withdraw-process.php">
            <div class="form-group">
                <label for="workerAadhar">Worker Aadhar</label>
                <div class="input-group">
                    <i class="fas fa-id-card"></i>
                    <input type="text" id="workerAadhar" name="worker_aadhar" value="<?php echo $aadhar; ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label for="withdraw_amount">Withdraw Amount</label>
                <div class="input-group">
                    <i class="fas fa-rupee-sign"></i>
                    <input type="number" id="withdraw_amount" name="withdraw_amount" min="1" required>
                </div>
            </div>

            <div class="form-group">
                <label for="upi_number">UPI Number</label>
                <div class="input-group">
                    <i class="fas fa-mobile-alt"></i>
                    <input type="text" id="upi_number" name="upi_number" pattern="\d{10}" title="Please enter a 10-digit phone number" required>
                </div>
            </div>

            <button type="submit">
                <i class="fas fa-wallet"></i> Withdraw
            </button>
        </form>
        <div id="responseMessage"></div>
    </div>

    <script>
        document.getElementById("withdrawForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            
            try {
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                });
                
                const result = await response.json();
                const messageDiv = document.getElementById("responseMessage");
                messageDiv.innerText = result.message;
                messageDiv.className = result.status === 'success' ? 'success' : 'error';
                
                if (result.status === 'success') {
                    form.reset();
                }
            } catch (error) {
                const messageDiv = document.getElementById("responseMessage");
                messageDiv.innerText = "An error occurred. Please try again.";
                messageDiv.className = 'error';
            }
        });
    </script>
</body>
</html>