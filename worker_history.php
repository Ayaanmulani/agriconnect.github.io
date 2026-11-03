<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Work History</title>
    <style>
        body {
            background-color: #121212;
            background-image: url('images/worker_history-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            margin: 0;
            font-family: 'Arial', sans-serif;
            color: black;
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
            background-image: url('images/worker_history-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
            pointer-events: none;
        }

        .header {
            background: linear-gradient(135deg, #4CAF50, #2E8B57);
            color: white;
            padding: 40px 20px;
            text-align: center;
            font-size: 2.5em;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .content {
            padding: 50px 20px;
        }

        h2 {
            text-align: center;
            font-size: 2em;
            margin-bottom: 30px;
        }

        .search-container {
            margin-bottom: 20px;
            text-align: center;
        }

        .search-container input {
            width: 80%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: white;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.1);
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .back-button {
            position: fixed;
            top: 10px;
            left: 10px;
            background-color: #8B4513;
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
            background-color: #A0522D;
        }
    </style>
</head>
<?php
session_start();
if (!isset($_SESSION['aadhar'])) {
    echo "Aadhar not found. Please login first.";
    exit;
}
$aadhar = $_SESSION['aadhar'];
?>
<body>
<button class="back-button" onclick="goBack()">⬅ Back</button>

<div class="header">Work History</div>
<div class="content">
    <h2>Completed Work Details</h2>
    <div class="search-container">
        <input type="text" id="searchInput" placeholder="Search for work..." onkeyup="searchTable()">
    </div>
    <table id="workHistoryTable">
        <thead>
            <tr>
                <th>Farmer Aadhar</th>
                <th>Location</th>
                <th>Date</th>
                <th>From</th>
                <th>To</th>
                <th>Wages</th>
                <th>Commission (10%)</th>
                <th>Revenue (90%)</th>
                <th>Approval Time</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $conn = new mysqli('localhost', 'root', '', 'agri_connect');
            if ($conn->connect_error) {
                die("<tr><td colspan='9'>Connection failed: " . htmlspecialchars($conn->connect_error) . "</td></tr>");
            }

            $totalRevenue = 0;
            $totalWithdrawn = 0;

            $sql = "SELECT farmer_aadhar, work_location, selected_date, start_time, end_time, total_cost, approval_time FROM done_work WHERE worker_aadhar = '$aadhar'";
            $result = $conn->query($sql);

            if ($result) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $wages = $row['total_cost'];
                        $commission = $wages * 0.1;
                        $revenue = $wages - $commission;
                        $totalRevenue += $revenue;

                        echo "<tr>
                            <td>" . htmlspecialchars($row['farmer_aadhar']) . "</td>
                            <td>" . htmlspecialchars($row['work_location']) . "</td>
                            <td>" . htmlspecialchars($row['selected_date']) . "</td>
                            <td>" . htmlspecialchars($row['start_time']) . "</td>
                            <td>" . htmlspecialchars($row['end_time']) . "</td>
                            <td>₹" . number_format($wages, 2) . "</td>
                            <td>₹" . number_format($commission, 2) . "</td>
                            <td>₹" . number_format($revenue, 2) . "</td>
                            <td>" . htmlspecialchars($row['approval_time']) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr id='noDataRow'><td colspan='9'>No completed work found in the database.</td></tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Error fetching work details: " . htmlspecialchars($conn->error) . "</td></tr>";
            }

            $withdrawSql = "SELECT SUM(withdraw) as total_withdrawn FROM worker_balance WHERE worker_aadhar = '$aadhar'";
            $withdrawResult = $conn->query($withdrawSql);

            if ($withdrawResult) {
                if ($withdrawResult->num_rows > 0) {
                    $withdrawRow = $withdrawResult->fetch_assoc();
                    $totalWithdrawn = $withdrawRow['total_withdrawn'] ? $withdrawRow['total_withdrawn'] : 0;
                }
            } else {
                echo "<tr><td colspan='9'>Error fetching withdraw details: " . htmlspecialchars($conn->error) . "</td></tr>";
            }

            $balance = $totalRevenue - $totalWithdrawn;

            $checkSql = "SELECT id FROM worker_balance WHERE worker_aadhar = '$aadhar'";
            $checkResult = $conn->query($checkSql);
            if ($checkResult->num_rows == 0) {
                $insertSql = "INSERT INTO worker_balance (worker_aadhar, total_revenue) VALUES ('$aadhar', '$totalRevenue')";
                if ($conn->query($insertSql) === FALSE) {
                    echo "<tr><td colspan='9'>Error inserting data into worker_balance table: " . htmlspecialchars($conn->error) . "</td></tr>";
                }
            }
            ?>
            <tr class="total-row">
                <td colspan="7">Total Revenue</td>
                <td colspan="2">₹<?php echo number_format($totalRevenue, 2); ?></td>
            </tr>
            <tr class="balance-row">
                <td colspan="7">Balance</td>
                <td colspan="2">₹<?php echo number_format($balance, 2); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="withdraw-section">
        <form action="worker-withdraw.php" method="POST">
            <input type="hidden" name="worker_aadhar" value="<?php echo $aadhar; ?>">
            <input type="hidden" name="balance" value="<?php echo $balance; ?>">
            <button type="submit">Withdraw</button>
        </form>
    </div>
</div>

<script>
    function searchTable() {
        const input = document.getElementById("searchInput");
        const filter = input.value.toLowerCase();
        const table = document.getElementById("workHistoryTable");
        const rows = table.getElementsByTagName("tr");
        let dataFound = false;

        for (let i = 1; i < rows.length; i++) {
            let row = rows[i];
            let cells = row.getElementsByTagName("td");
            let found = false;

            for (let j = 0; j < cells.length; j++) {
                if (cells[j].innerText.toLowerCase().includes(filter)) {
                    found = true;
                    break;
                }
            }

            row.style.display = found ? "" : "none";
            if (found && row.id !== "noDataRow") {
                dataFound = true;
            }
        }

        const noDataRow = document.getElementById("noDataRow");
        if (noDataRow) {
            noDataRow.style.display = dataFound ? "none" : "";
        }
    }

    function goBack() {
        window.history.back();
    }
</script>
</body>
</html>
