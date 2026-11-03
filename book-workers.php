<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Worker Booking</title>
    <style>
body {
    background-color: #121212; 
    background-image: url('images/worker-booking-background.jpg');
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
    background-image: url('images/worker-booking-background.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    filter: blur(8px);
    z-index: -1;
    pointer-events: none;
}

.container {
    width: 90%;
    max-width: 850px;
    margin: 50px auto;
    padding: 50px;
    background-color: #141414;
    box-shadow: 0 20px 50px rgba(0, 255, 0, 0.2);
    border-radius: 25px;
    border: 1px solid #4CAF50;
    position: relative;
    overflow: hidden;
    transition: transform 0.4s ease, box-shadow 0.4s ease;
}

.container:hover {
    transform: translateY(-10px);
    box-shadow: 0 25px 60px rgba(0, 255, 0, 0.3);
}

h1 {
    color: #4CAF50;
    text-align: center;
    font-size: 3rem;
    margin-bottom: 30px;
    position: relative;
}

h1::after {
    content: '';
    width: 120px;
    height: 4px;
    background-color: #4CAF50;
    display: block;
    margin: 10px auto 0;
    border-radius: 2px;
}

.form-group {
    margin-bottom: 30px;
    position: relative;
}

.form-group label {
    display: block;
    margin-bottom: 12px;
    font-size: 1.2rem;
    font-weight: bold;
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 18px;
    border: 2px solid #4CAF50;
    background-color: #1a1a1a;
    color: #fff;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    border-color: #66ff66;
    box-shadow: 0 0 15px rgba(102, 255, 102, 0.4);
    outline: none;
}

.form-group input[type="date"] {
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
    background-color: #1a1a1a;
    color: #fff;
    padding: 18px;
    border: 2px solid #4CAF50;
    border-radius: 10px;
    font-size: 1rem;
    position: relative;
}

.form-group input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
}

.form-group input[type="date"]:focus {
    border-color: #66ff66;
    box-shadow: 0 0 15px rgba(102, 255, 102, 0.4);
    outline: none;
}

.form-group button {
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, #4CAF50, #45a049);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1.3rem;
    text-transform: uppercase;
    cursor: pointer;
    transition: background 0.3s ease, transform 0.3s ease;
    font-weight: bold;
    letter-spacing: 1.5px;
}

.form-group button:hover {
    background: linear-gradient(135deg, #66ff66, #4CAF50);
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(76, 175, 80, 0.4);
}

.form-group input[type="time"] {
    width: 100%;
    padding: 18px;
    border: 2px solid #4CAF50;
    background-color: #1a1a1a;
    color: #fff;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    appearance: none;
    -webkit-appearance: none;
    -moz-appearance: none;
}

.form-group input[type="time"]::-webkit-calendar-picker-indicator {
    filter: invert(1);
    cursor: pointer;
    background-color: transparent;
}

.form-group input[type="time"]:focus {
    border-color: #66ff66;
    box-shadow: 0 0 15px rgba(102, 255, 102, 0.4);
    outline: none;
    color: white;
}

.alert {
    display: none;
    padding: 20px;
    margin-top: 30px;
    border-radius: 12px;
    text-align: center;
    font-size: 1.1rem;
}

.alert.success {
    background-color: #4CAF50;
    color: white;
}

.alert.error {
    background-color: #d9534f;
    color: white;
}

.worker-info {
    margin-top: 40px;
    background-color: #1c1c1c;
    padding: 25px;
    border: 1px solid #4CAF50;
    border-radius: 15px;
    display: none;
    font-size: 1.2rem;
    animation: fadeIn 0.6s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.floating-circles {
    position: absolute;
    top: -80px;
    right: -80px;
    width: 150px;
    height: 150px;
    background-color: rgba(76, 175, 80, 0.2);
    border-radius: 50%;
    animation: float 6s infinite ease-in-out;
}

.floating-circles::before {
    content: '';
    width: 100px;
    height: 100px;
    background-color: rgba(76, 175, 80, 0.3);
    position: absolute;
    top: 50px;
    left: -60px;
    border-radius: 50%;
}

@keyframes float {
    0% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-20px);
    }
    100% {
        transform: translateY(0);
    }
}

html, body {
    height: 100%;
    overflow-y: auto;
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
    <h1>Book a Worker</h1>
    <form id="bookingForm" method="POST" action="process_booking.php">
        <div class="form-group">
            <label for="worker">Select Worker:</label>
            <select name="worker_aadhar" id="worker" required>
                <option value="">Select a Worker</option>
                <?php
                $conn = new mysqli('localhost', 'root', '', 'agri_connect');
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
                $workers_sql = "SELECT aadhar, name, location, experience, per_hour_rate FROM users WHERE type = 'worker'";
                $workers_result = $conn->query($workers_sql);

                if ($workers_result->num_rows > 0) {
                    while ($worker = $workers_result->fetch_assoc()) {
                        echo "<option value='{$worker['aadhar']}' data-rate='{$worker['per_hour_rate']}' data-location='{$worker['location']}' data-experience='{$worker['experience']}'>{$worker['name']} - ₹{$worker['per_hour_rate']} per hour</option>";
                    }
                }
                $conn->close();
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date">Select Date:</label>
            <input type="date" id="date" name="selected_date" required>
        </div>
        
        <div id="workerInfo" class="worker-info">
            <p><strong>Location:</strong> <span id="workerLocation"></span></p>
            <p><strong>Experience:</strong> <span id="workerExperience"></span> years</p>
            <p><strong>Hourly Rate:</strong> ₹<span id="workerRate"></span></p>
        </div>

        <div class="form-group">
            <label for="workDetails">Details of Work:</label>
            <textarea name="work_details" id="workDetails" rows="3" placeholder="Describe the work..." required></textarea>
        </div>

        <div class="form-group">
            <label for="workLocation">Work Location:</label>
            <input type="text" name="work_location" id="workLocation" placeholder="Enter the work location" required>
        </div>

        <div class="form-group">
            <label for="conditions">Special Conditions (Optional):</label>
            <textarea name="conditions" id="conditions" rows="3" placeholder="Mention any special conditions"></textarea>
        </div>

        <div class="form-group">
            <label for="crops">Type of Crops (Optional):</label>
            <input type="text" name="crops" id="crops" placeholder="Specify crop types">
        </div>

        <div class="form-group">
            <label for="hours">Number of Hours:</label>
            <input type="number" name="hours" id="hours" min="1" placeholder="Enter the number of hours" required>
        </div>

        <div id="totalCost" class="form-group" style="display:none;">
            <strong>Total Cost: ₹<span id="calculatedCost">0</span></strong>
        </div>

        <div class="form-group">
            <label for="startTime">Start Time:</label>
            <input type="time" name="start_time" id="startTime" required>
        </div>

        <div class="form-group">
            <label for="endTime">End Time:</label>
            <input type="time" name="end_time" id="endTime" required>
        </div>

        <div class="form-group">
            <button type="submit">Confirm</button>
        </div>

        <div class="alert success" id="successAlert">Booking successful!</div>
        <div class="alert error" id="errorAlert">There was an error processing your booking.</div>
    </form>
</div>

<script>
    document.getElementById('worker').addEventListener('change', function() {
        const workerSelect = this;
        const workerInfo = document.getElementById('workerInfo');
        const workerLocation = document.getElementById('workerLocation');
        const workerExperience = document.getElementById('workerExperience');
        const workerRate = document.getElementById('workerRate');
        
        const selectedOption = workerSelect.options[workerSelect.selectedIndex];
        const rate = selectedOption.getAttribute('data-rate');
        const location = selectedOption.getAttribute('data-location');
        const experience = selectedOption.getAttribute('data-experience');

        if (rate) {
            workerInfo.style.display = 'block';
            workerLocation.textContent = location;
            workerExperience.textContent = experience;
            workerRate.textContent = rate;
        } else {
            workerInfo.style.display = 'none';
        }
    });
</script>
<script>
function goBack() {
    window.history.back();
}
</script>
</body>
</html>
