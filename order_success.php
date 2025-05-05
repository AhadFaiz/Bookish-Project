<?php
session_start();

// Generate a random order ID
$_SESSION['order_id'] = rand(100000, 999999);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful | Bookish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .success-container {
            text-align: center;
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
        }

        .success-container h2 {
            color: #28a745;
        }

        .success-container p {
            font-size: 1.2rem;
            color: #333;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <div class="success-container">
        <i class="fa fa-check-circle" style="font-size: 4rem; color: #28a745;"></i>
        <h2>Order Placed Successfully!</h2>
        <p>Your order has been confirmed.</p>
        <p><strong>Order ID:</strong> #<?= $_SESSION['order_id'] ?></p>
        <p>Thank you for shopping with us!</p>
        <a href="products.php" class="btn"><i class="fa fa-shopping-cart"></i> Continue Shopping</a>
    </div>

</body>
</html>
