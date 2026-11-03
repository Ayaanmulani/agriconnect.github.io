<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agri_connect";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM machinery_rentals";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Machinery</title>
    <link rel="stylesheet" href="style.css">
    <style>
* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}
body {
    background-color: #121212;
    background-image: url('images/machinery-background.jpg');
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
    background-image: url('images/machinery-background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(8px);
    z-index: -1;
    pointer-events: none;
}
.container {
    width: 80%;
    margin: 50px auto;
    background-color: rgba(20, 20, 20, 0.9);
    padding: 30px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2), 0 0 15px rgba(76, 175, 80, 0.6);
}
.header {
    text-align: center;
    margin-bottom: 40px;
}
.header h1 {
    font-size: 36px;
    font-weight: bold;
    color: #4CAF50;
}
.machinery-item {
    display: flex;
    align-items: center;
    margin-bottom: 25px;
    padding: 20px;
    background-color: #2e2e2e;
    border-radius: 15px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 0 15px rgba(76, 175, 80, 0.5);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.machinery-item:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15), 0 0 20px rgba(76, 175, 80, 0.8);
}
.machinery-item img {
    width: 180px;
    height: 180px;
    border-radius: 10px;
    object-fit: cover;
    margin-right: 20px;
}
.details {
    flex: 1;
    font-size: 18px;
}
.details h3 {
    font-size: 22px;
    color: #4CAF50;
    margin-bottom: 10px;
}
.details p {
    margin: 5px 0;
    color: #ddd;
}
.machinery-item button {
    background-color: #4CAF50;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s, transform 0.3s, box-shadow 0.3s;
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5);
}
.machinery-item button:hover {
    background-color: #388e3c;
    transform: scale(1.05);
    box-shadow: 0 6px 15px rgba(76, 175, 80, 0.7);
}
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    justify-content: center;
    align-items: center;
}
.modal-content {
    background-color: #121212;
    padding: 40px;
    border-radius: 10px;
    width: 50%;
    max-width: 600px;
    text-align: center;
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5);
    color: white;
}
.modal-content h2 {
    font-size: 24px;
    margin-bottom: 20px;
    color: #4CAF50;
}
.modal .close {
    font-size: 28px;
    cursor: pointer;
    position: absolute;
    top: 10px;
    right: 10px;
    color: #ddd;
}
.amount {
    margin-top: 20px;
    font-size: 20px;
    font-weight: bold;
    color: #4CAF50;
}
.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}
label {
    width: 150px;
    font-weight: bold;
    margin-right: 10px;
    text-align: right;
}
.interactive-input {
    flex: 1;
    padding: 10px;
    border: 1px solid #444;
    border-radius: 5px;
    font-size: 16px;
    background-color: #2e2e2e;
    color: white;
}
.rent-form button {
    background-color: #4CAF50;
    color: white;
    cursor: pointer;
    box-shadow: 0 4px 8px rgba(76, 175, 80, 0.5);
}
.rent-form button:hover {
    background-color: #388e3c;
    box-shadow: 0 6px 15px rgba(76, 175, 80, 0.7);
}
@media (max-width: 768px) {
    .container {
        width: 90%;
    }
    .machinery-item {
        flex-direction: column;
        align-items: flex-start;
    }
    .machinery-item img {
        width: 120px;
        height: 120px;
    }
    .details h3 {
        font-size: 18px;
    }
    .details p {
        font-size: 16px;
    }
    .modal-content {
        width: 80%;
    }
}
.back-button {
    position: fixed;
    top: 10px;
    left: 10px;
    background-color: #28a745;
    color: white;
    padding: 8px 16px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    transition: 0.3s ease-in-out;
    z-index: 1000;
}
.back-button:hover {
    background-color: #218838;
}
    </style>
</head>
<body>
<button class="back-button" onclick="goBack()">⬅ Back</button>

    <div class="container">
        <div class="header">
            <h1>Available Machinery for Rent</h1>
        </div>

        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="machinery-item">
                    <img src="<?php echo $row['image']; ?>" alt="<?php echo $row['machinery_name']; ?>">
                    <div class="details">
                        <h3><?php echo $row['machinery_name']; ?></h3>
                        <p>Owner: <?php echo $row['owner_name']; ?></p>
                        <p>Location: <?php echo $row['location']; ?></p>
                        <p>Rate: ₹<?php echo $row['profit']; ?> per hour</p>
                        <button onclick="openModal('<?php echo $row['machinery_name']; ?>', <?php echo $row['profit']; ?>, <?php echo $row['id']; ?>)">Rent Now</button>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No machinery available for rent.</p>
        <?php endif; ?>

        <div id="rentModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2>Confirm Rental</h2>
                <p id="modal-text" class="modal-description">Choose the rental duration and delivery details below.</p>
                <form id="rent-form" action="payment_rent.php" method="POST">
                    <input type="hidden" name="machinery_id" id="modal-machinery-id">
                    <input type="hidden" name="machinery_name" id="modal-machinery-name">
                    <input type="hidden" name="user_id" value="123">
                    
                    <div class="form-group">
                        <label for="rental_hours">Hours:</label>
                        <input type="number" name="rental_hours" id="rental_hours" min="1" required onchange="calculateAmount()" class="interactive-input">
                    </div>

                    <div class="form-group">
                        <label for="delivery_location">Delivery Location:</label>
                        <input type="text" name="delivery_location" id="delivery_location" required class="interactive-input">
                    </div>

                    <div class="amount" id="total-amount"></div>
                    <input type="hidden" name="amount" id="amount">
                    <button type="submit" class="interactive-btn" id="submit-button">Confirm Rental</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        let modal = document.getElementById("rentModal");
        let modalText = document.getElementById("modal-text");
        let modalMachineryId = document.getElementById("modal-machinery-id");
        let modalMachineryName = document.getElementById("modal-machinery-name");
        let rentalHoursInput = document.getElementById("rental_hours");
        let totalAmountElement = document.getElementById("total-amount");
        let amountInput = document.getElementById("amount");
        let submitButton = document.getElementById("submit-button");

        let hourlyRate;

        function openModal(name, rate, id) {
            hourlyRate = rate;
            modalText.innerText = `Rent ${name} at ₹${rate} per hour.`;
            modalMachineryId.value = id;
            modalMachineryName.value = name;
            modal.style.display = "flex";
        }

        function closeModal() {
            modal.style.display = "none";
        }

        function calculateAmount() {
            const hours = rentalHoursInput.value;
            if (hours) {
                const totalAmount = hours * hourlyRate;
                totalAmountElement.innerText = `Total Amount: ₹${totalAmount}`;
                amountInput.value = totalAmount;
            } else {
                totalAmountElement.innerText = '';
                amountInput.value = '';
            }
        }

        submitButton.addEventListener('click', function(e) {
            e.preventDefault();
            submitButton.classList.add('loading');
            submitButton.innerText = "Processing...";
            setTimeout(function() {
                document.getElementById("rent-form").submit();
            }, 2000);
        });
    </script>
    <script>
function goBack() {
    window.history.back();
}
</script>
</body>
</html>

<?php $conn->close(); ?>
