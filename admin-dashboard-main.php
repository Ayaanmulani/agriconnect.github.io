<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agri Connect - Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-image: url('images/admin-dashboard-background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            font-family: 'Arial', sans-serif;
            color: black;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center; /* Center horizontally */
            justify-content: flex-start; /* Align from top */
            height: 100vh;
            animation: fadeIn 1s ease;
        }

        .header {
            background-color: rgba(0, 0, 0, 0.7);
            padding: 15px;
            color: white;
            text-align: center;
            width: 100%; /* Ensure header takes full width */
            animation: fadeIn 1s ease;
        }

        .menu {
            display: flex;
            justify-content: center;
            background-color: #333;
            padding: 12px 0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease;
        }

        .menu a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            margin: 0 10px;
            background-color: rgba(0, 128, 0, 0.8);
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu a:hover {
            background-color: rgba(0, 128, 0, 1);
        }

        .content {
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            width: 80%; /* Adjust the width based on the desired size */
            max-width: 1200px; /* Set a max width */
            margin-top: 20px; /* Adjust space between header and content */
            margin-bottom: 20px; /* Add some space below content */
            animation: slideUp 1s ease;
        }

        h2 {
            font-size: 30px;
            margin-bottom: 20px;
            color: #333;
        }

        .cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            flex: 1;
            margin: 0 15px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            perspective: 1000px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
        }

        .card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            transform-style: preserve-3d;
            transition: transform 0.6s;
        }

        .card:hover .card-inner {
            transform: rotateY(180deg);
        }

        .card-front, .card-back {
            position: absolute;
            width: 100%;
            backface-visibility: hidden;
        }

        .card-back {
            transform: rotateY(180deg);
            background-color: #f4f4f4;
            border-radius: 15px;
            padding: 20px;
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 10px;
            color: #333;
        }

        .card p {
            font-size: 24px;
            font-weight: bold;
            color: green;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            font-size: 16px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 15px;
            text-align: center;
        }

        table th {
            background-color: rgba(0, 128, 0, 0.1);
            color: #333;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .action-btn {
            background-color: green;
            color: white;
            padding: 12px 30px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .action-btn:hover {
            background-color: darkgreen;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.5);
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Admin Dashboard</h1>
        <div class="menu">
            <a href="farmer-profiles.php">View Profile</a>
            <a href="worker-profiles.php">Worker Profiles</a>
            <a href="#revenue-history">Revenue History</a>
            <a href="#machineryRentalsTable">Machinery Rentals</a>
            <a href="admin-dashboard-withdraw.php">Worker Withdraws </a>
            <a href="add-product.php">Add Product</a>
            <a href="add-machinery.php">Renting Machinery</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="content">
        <h2>Overview</h2>

        <div class="cards">
            <div class="card">
                <h3>Total Farmers</h3>
                <p id="farmers-count">Loading...</p>
            </div>
            <div class="card">
                <h3>Total Worker Commission</h3>
                <p id="total-revenue">₹0</p>
            </div>
            <div class="card">
                <h3>Total machinery Gross</h3>
                <p id="total-profit">₹0</p>
            </div>
            <div class="card">
                <h3>Total Product Gross</h3>
                <p id="total-product-price">₹0</p>
            </div>
            <div class="card">
                <h3>Total Workers</h3>
                <p id="workers-count">Loading...</p>
            </div>
        </div>


<h3>Workers Overview</h3>
<table border="1">
    <thead>
        <tr>
            <th>Farmer Aadhar</th>
            <th>Work Location</th>
            <th>Selected Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Wages</th>
            <th>Commission</th>
            <th>Revenue</th>
            <th>Approval Time</th>
        </tr>
    </thead>
    <tbody id="workers-overview">
        <tr><td colspan="9">Loading...</td></tr>
    </tbody>
</table>

<div class="header">Machinery Rentals</div>
    <div class="content">
        <h2>Completed Machinery Rentals</h2>
        <div class="search-container">
  <input type="text" id="searchInput" placeholder="Search for machinery..." onkeyup="searchTable()">
</div>

<table id="machineryRentalsTable">
  <thead>
    <tr>
      <th>Machinery Name</th>
      <th>Location</th>
      <th>Total Working Hours</th>
      <th>Profit</th>
      <th>Created At</th>
    </tr>
  </thead>
  <tbody id="machinery-overview">
    
  </tbody>
  <tfoot>
    <tr class="total-row">
      <td colspan="3">Total Profit</td>
      <td colspan="2" id="total-profit1">₹0.00</td>
    </tr>
  </tfoot>
</table>
    </div>

        <a href="add-machinery.php" class="action-btn">Add New Machinery</a>
        <a href="add-product.php" class="action-btn">Add New Product</a>

        <h3 id="revenue-history">Revenue History (Purchases)</h3>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User Aadhar</th>
            <th>Product ID</th>
            <th>User Name</th>
            <th>Quantity</th>
            <th>Total Price</th>
            <th>Purchase Date</th>
        </tr>
    </thead>
    <tbody id="revenue-history-overview">
        <tr>
            <td colspan="7">Loading...</td>
        </tr>
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="5">Total Product income </td>
            <td colspan="2" id="total-price">₹0.00</td>
        </tr>
    </tfoot>
</table>
    <script>
    // dashboard data
    fetch('admin-worker-dashboard.php')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {

            document.getElementById('total-revenue').textContent = `₹${data.totalRevenue}`;
          
            const workersOverview = document.getElementById('workers-overview');
            workersOverview.innerHTML = '';
            data.workers.forEach(worker => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${worker.worker_aadhar}</td>
                    <td>${worker.work_location}</td>
                    <td>${worker.selected_date}</td>
                    <td>${worker.start_time}</td>
                    <td>${worker.end_time}</td>
                    <td>₹${worker.wages}</td>
                    <td>₹${worker.commission}</td>
                    <td>₹${worker.revenue}</td>
                    <td>${worker.approval_time}</td>
                `;
                workersOverview.appendChild(row);
            });
        })
        .catch(error => {
            console.error("Error fetching data:", error);
        });
        
    </script>
    <script>
        
        fetch('admin-logs-dashboard.php')
            .then(response => response.json())
            .then(data => {
                
                document.getElementById('farmers-count').textContent = data.farmersCount;
                document.getElementById('workers-count').textContent = data.workersCount;
            })
            .catch(error => console.error('Error fetching data:', error));
    </script>
<script>
 
  fetch('admin_machinery_dashboard.php')
    .then(response => response.json())
    .then(data => {
      
      if (data.error) {
        console.error('Error:', data.error);
        return;
      }

      const tbody = document.getElementById('machinery-overview');
      let totalProfit = 0;

     
      data.forEach(booking => {
        const row = document.createElement('tr');
        row.innerHTML = `
          <td>${booking.machinery_name}</td>
          <td>${booking.delivery_location}</td>
          <td>${booking.rental_hours}</td>
          <td>₹${parseFloat(booking.amount).toFixed(2)}</td>
          <td>${new Date(booking.booked_at).toLocaleDateString()}</td>
        `;
        tbody.appendChild(row);

       
        totalProfit += parseFloat(booking.amount);
      });

      
      document.getElementById('total-profit').textContent = `₹${totalProfit.toFixed(2)}`;
      document.getElementById('total-profit1').textContent = `₹${totalProfit.toFixed(2)}`;
    })
    .catch(error => {
      console.error('Error fetching data:', error);
    });

 
  function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const rows = document.querySelectorAll('#machineryRentalsTable tbody tr');

    rows.forEach(row => {
      const cells = row.getElementsByTagName('td');
      let match = false;

     
      for (let i = 0; i < cells.length; i++) {
        if (cells[i] && cells[i].innerText.toLowerCase().indexOf(filter) > -1) {
          match = true;
          break;
        }
      }

     
      row.style.display = match ? '' : 'none';
    });
  }
  document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.getElementById("revenue-history-overview");
    const totalProductPriceElement = document.getElementById("total-product-price");
    const totalProductPriceElement1 = document.getElementById("total-price");


    
    fetch("admin_purchases_dashboard.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                tableBody.innerHTML = `<tr><td colspan="7">Error: ${data.error}</td></tr>`;
            } else if (data.length === 0) {
                tableBody.innerHTML = `<tr><td colspan="7">No purchases found.</td></tr>`;
            } else {
                let totalPrice = 0;

                tableBody.innerHTML = data.map(purchase => {
                    totalPrice += Number(purchase.total_price); 
                    return `
                        <tr>
                            <td>${purchase.id}</td>
                            <td>${purchase.user_aadhar}</td>
                            <td>${purchase.product_id}</td>
                            <td>${purchase.name}</td>
                            <td>${purchase.quantity}</td>
                            <td>₹${Number(purchase.total_price).toFixed(2)}</td>
                            <td>${new Date(purchase.purchase_date).toLocaleString()}</td>
                        </tr>
                    `;
                }).join('');

                
                totalProductPriceElement.textContent = `₹${totalPrice.toFixed(2)}`;
                totalProductPriceElement1.textContent = `₹${totalPrice.toFixed(2)}`;
            }
        })
        .catch(error => {
            tableBody.innerHTML = `<tr><td colspan="7">Error loading data: ${error.message}</td></tr>`;
        });
});

</script>
</body>
</html>

