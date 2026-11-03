<?php
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Agri-Connect</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #2c3e50;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: white;
            text-align: center;
        }
        .container {
            background-color: #16a085;
            border-radius: 10px;
            padding: 40px;
            width: 300px;
            box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.5s ease-in-out;
        }
        .container h1 {
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .container p {
            font-size: 1.2rem;
            margin-bottom: 30px;
        }
        .btn {
            background-color: #e74c3c;
            color: white;
            padding: 10px 20px;
            font-size: 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #c0392b;
        }
        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Thank You for Visiting!</h1>
    <p>Agri-Connect appreciates your time. We hope to see you again soon!</p>
    <form action="index.html" method="get">
        <button type="submit" class="btn">Return to Homepage</button>
    </form>
</div>

</body>
</html>
