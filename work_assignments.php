<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assigned Work</title>
    <style>
    body {
        background-color: #121212; /* Dark background */
        background-image: url('images/work_assignments-background.jpg');
        background-size: cover; /* Ensures the image covers the entire page */
        background-position: center; /* Centers the image */
        background-repeat: no-repeat; /* Prevents the image from repeating */
        background-attachment: fixed; /* Keeps the background fixed on scroll */
        height: 100vh; /* Ensures the height of the page matches the viewport */
        margin: 0; /* Removes default margin */
        font-family: 'Arial', sans-serif;
        color: black; /* Light text for contrast */
        position: relative; /* Needed for positioning pseudo-element */
        overflow: auto; /* Ensures scrolling works properly */
    }

    body::before {
        content: ''; /* Creates an empty pseudo-element */
        position: fixed; /* Keeps the pseudo-element fixed on scroll */
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-image: url('images/work_assignments-background.jpg'); /* Use the same image */
        background-size: cover; /* Ensures the background image remains aligned */
        background-position: center;
        background-repeat: no-repeat;
        z-index: -1; /* Ensures it stays behind the content */
        pointer-events: none; /* Prevent interaction with the pseudo-element */
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
        .back-button {
    position: fixed;
    top: 10px;  /* Adjusts position from the top */
    left: 10px; /* Adjusts position from the left */
    background-color: #8B4513; /* Soil brown color */
    color: white;
    padding: 8px 16px;
    font-size: 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
    transition: 0.3s ease-in-out;
    z-index: 1000; /* Ensures it stays on top */
}

.back-button:hover {
    background-color: #A0522D; /* Slightly lighter brown on hover */
}

    </style>
</head>
<body>
<button class="back-button" onclick="goBack()">⬅ Back</button>


    <div class="header">Assigned Work</div>
    <div class="content">
        <h2>Assigned Work Details</h2>
        <div class="search-container">
            <input type="text" id="searchInput" placeholder="Search for work..." onkeyup="searchTable()">
        </div>
        <table id="assignedWorkTable">
            <thead>
                <tr>
                    <th>Farmer Aadhar</th>
                    <th>Location</th>
                    <th>Date</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Wages</th>
                    <th>Revenue</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Database connection
                $conn = new mysqli('localhost', 'root', '', 'agri_connect');

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch data from assign_work table
                $sql = "SELECT farmer_aadhar, work_location, selected_date, start_time, end_time, total_cost FROM assign_work";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $revenue = $row['total_cost'] * 0.9;
                        echo "<tr>
                            <td>" . $row['farmer_aadhar'] . "</td>
                            <td>" . $row['work_location'] . "</td>
                            <td>" . $row['selected_date'] . "</td>
                            <td>" . $row['start_time'] . "</td>
                            <td>" . $row['end_time'] . "</td>
                            <td>₹" . $row['total_cost'] . "</td>
                            <td>₹" . number_format($revenue, 2) . "</td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No data available</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>

    <script>
        function searchTable() {
            const input = document.getElementById("searchInput");
            const filter = input.value.toLowerCase();
            const table = document.getElementById("assignedWorkTable");
            const rows = table.getElementsByTagName("tr");

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
            }
        }
    </script>
    <script>
function goBack() {
    window.history.back();
}
</script>
</body>
</html>
