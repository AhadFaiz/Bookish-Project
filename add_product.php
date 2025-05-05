<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start(); 


if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php'); 
    exit;
}

$output = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "db_connection.php"; 


    $product_name = trim($_POST['product_name']);
    $product_price = floatval($_POST['product_price']);
    $product_stock = intval($_POST['product_stock']);
    $product_category = trim($_POST['product_category']);
    $product_description = trim($_POST['product_description']);
    $product_image = $_FILES['product_image']['name'];

    if (empty($product_name) || $product_price <= 0 || $product_stock < 0 || empty($product_category) || empty($product_image)) {
        $output = "All fields are required and must be valid.";
    } else {
     
        $target_dir = "images/";
        $target_file = $target_dir . basename($product_image);

        if (!move_uploaded_file($_FILES['product_image']['tmp_name'], $target_file)) {
            $output = "Error uploading image.";
        } else {
        
            $query = "INSERT INTO product (title, category, price, image_name, stock, description) 
                      VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssdsss", $product_name, $product_category, $product_price, $product_image, $product_stock, $product_description);

            if ($stmt->execute()) {
                header('Location: AdminDashboard.php?message=Product added successfully');
                exit;
            } else {
                $output = "Error adding product: " . $conn->error;
            }

            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Product</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
        }

        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 500px;
        }

        .form-container h1 {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .input-box {
            margin-bottom: 1rem;
        }

        .input-box input, .input-box textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: rgb(225, 63, 157);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .btn:hover {
            background-color: rgb(221, 133, 184);
        }

        .btn-outline {
            display: block;
            width: 100%;
            padding: 0.8rem;
            background-color: transparent;
            color: rgb(225, 63, 157);
            border: 2px solid rgb(225, 63, 157);
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
            font-size: 1rem;
            text-decoration: none;
        }

        .btn-outline:hover {
            background-color: rgb(225, 63, 157);
            color: #fff;
        }

        .debug-output {
            margin: 1rem auto;
            padding: 1rem;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <?php if (!empty($output)): ?>
            <div class="debug-output">
                <?php echo htmlspecialchars($output); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="add_product.php" enctype="multipart/form-data">
            <h1>Add New Product</h1>
            <div class="input-box">
                <input type="text" name="product_name" placeholder="Product Name" required>
            </div>
            <div class="input-box">
                <input type="text" name="product_category" placeholder="Product Category" required>
            </div>
            <div class="input-box">
                <input type="number" step="0.01" name="product_price" placeholder="Product Price" required>
            </div>
            <div class="input-box">
                <input type="number" name="product_stock" placeholder="Product Stock" required>
            </div>
            <div class="input-box">
                <textarea name="product_description" placeholder="Product Description"></textarea>
            </div>
            <div class="input-box">
                <input type="file" name="product_image" required>
            </div>
            <button type="submit" class="btn">Add Product</button>
            <a href="AdminDashboard.php" class="btn-outline">Back to Admin's Dashboard</a>
        </form>
    </div>
</body>
</html>
