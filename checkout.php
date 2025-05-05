<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$cart = $_SESSION['cart'];
$total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $cart));

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_order'])) {
    $name = trim($_POST['name']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $payment_method = $_POST['payment_method'];

    if (empty($name) || empty($address) || empty($phone) || empty($email)) {
        $error = "Please fill in all fields.";
    } else {
        $file = fopen("orders.txt", "a");
        fwrite($file, "Customer: $name | Address: $address | Phone: $phone | Email: $email | Payment: $payment_method\n");
        foreach ($cart as $product) {
            fwrite($file, "{$product['title']} | SAR {$product['price']} | Quantity: {$product['quantity']}\n");
        }
        fwrite($file, "----------------------------\n");
        fclose($file);

        foreach ($cart as $product) {
            $book_id = $product['id'];
            $quantity_purchased = $product['quantity'];

            $update_stock_query = "UPDATE product SET stock = stock - ? WHERE id = ?";
            $stmt = $conn->prepare($update_stock_query);
            $stmt->bind_param("ii", $quantity_purchased, $book_id);
            $stmt->execute();
        }

        if (!empty($_SESSION['cart'])) {
            $pastPurchases = [];
            if (isset($_COOKIE['past_purchases'])) {
                $pastPurchases = unserialize($_COOKIE['past_purchases']);
            }
            foreach ($_SESSION['cart'] as $item) {
                $pastPurchases[] = [
                    'title' => $item['title'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity']
                ];
            }
            setcookie('past_purchases', serialize($pastPurchases), time() + (86400 * 100), "/"); 
        }

        unset($_SESSION['cart']);
        header("Location: order_success.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Bookish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #fdf2f8;
            margin: 0;
            padding: 0;
        }

        .checkout-container {
            max-width: 900px;
            margin: 3rem auto;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #c2185b;
            margin-bottom: 2rem;
        }

        .checkout-image {
            width: 100%;
            max-height: 250px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 1.5rem;
        }

        .order-summary {
            margin-bottom: 2rem;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            background: #fff0f6;
            border-radius: 8px;
            overflow: hidden;
        }

        .order-summary th, .order-summary td {
            padding: 12px 15px;
            text-align: left;
        }

        .order-summary th {
            background-color: #ec407a;
            color: white;
        }

        .order-summary tr:nth-child(even) {
            background-color: #fce4ec;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
        }

        .btn {
            background-color: #ec407a;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            display: block;
            width: 100%;
            margin-top: 1rem;
        }

        .btn:hover {
            background-color: #d81b60;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="checkout-container">
    <h2>Checkout</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <div class="order-summary">
        <h3>Order Summary</h3>
        <table>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td>SAR <?= number_format($item['price'], 2) ?></td>
                    <td><?= $item['quantity'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <h3 style="text-align:right; color:#880e4f; margin-top: 1rem;">Total: SAR <?= number_format($total, 2) ?></h3>
    </div>

    <form method="post">
        <div class="form-group">
            <label for="name">Full Name:</label>
            <input type="text" id="name" name="name" required>
        </div>

        <div class="form-group">
            <label for="address">Shipping Address:</label>
            <input type="text" id="address" name="address" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number:</label>
            <input type="text" id="phone" name="phone" required>
        </div>

        <div class="form-group">
            <label for="email">Email Address:</label>
            <input type="email" id="email" name="email" required>
        </div>

        <div class="form-group">
            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method">
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
                <option value="Cash on Delivery">Cash on Delivery</option>
            </select>
        </div>

        <button type="submit" name="confirm_order" class="btn">Confirm Order</button>
    </form>
</div>

</body>
</html>
