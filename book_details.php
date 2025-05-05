<?php
session_start();


if (!isset($_GET['id'])) {
    header('Location: products.php'); 
    exit;
}

$product_id = intval($_GET['id']); 
include "db_connection.php"; 


$query = "SELECT * FROM product WHERE id = ?";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Database error: " . $conn->error);
}

$stmt->bind_param("i", $product_id);

if (!$stmt->execute()) {
    die("Execution error: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $book_details = $result->fetch_assoc();
} else {
    echo "<p>Book not found.</p>";
    $stmt->close();
    $conn->close();
    exit;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book_details['title']) ?> | Book Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .details-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #ffe3f1;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .details-container img {
            max-width: 30%;
            height: auto;
            border-radius: 8px;
        }

        .details-container h1 {
            margin: 1rem 0;
            font-size: 2rem;
        }

        .details-container p {
            font-size: 1.2rem;
            color: #555;
        }

        .btn {
            background-color: #e84393;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            display: inline-block;
            margin-top: 1rem;
            transition: background 0.3s;
        }

        .btn:hover {
            background-color: #d7337f;
        }

        
        #helpModal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        .help-content {
            background-color: white;
            width: 80%;
            max-width: 500px;
            margin: 10% auto;
            padding: 2rem;
            border-radius: 10px;
            text-align: left;
            position: relative;
        }

        .help-content h2 {
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="details-container">
        <img src="images/<?= htmlspecialchars($book_details['image_name']) ?>" alt="<?= htmlspecialchars($book_details['title']) ?>">
        <h1><?= htmlspecialchars($book_details['title']) ?></h1>

        <p><strong>Category:</strong> <?= htmlspecialchars($book_details['category']) ?></p>
        <p><strong>Price:</strong> <sup>SAR</sup> <?= htmlspecialchars($book_details['price']) ?></p>
        <p><strong>Description:</strong> <?= htmlspecialchars($book_details['description']) ?></p>
        <p><strong>ID:</strong> <?= htmlspecialchars($book_details['id']) ?></p>


        <a href="products.php" class="btn">Back to Products</a>

        <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
            <button class="btn" onclick="openHelp()">Help</button>

           
            <form method="post" action="add_to_cart.php" style="display: inline;">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($book_details['id']) ?>">
                <input type="hidden" name="product_name" value="<?= htmlspecialchars($book_details['title']) ?>">
                <input type="hidden" name="product_price" value="<?= htmlspecialchars($book_details['price']) ?>">
                <button type="submit" class="btn">Buy Now</button>
            </form>
        <?php endif; ?>
    </div>


    <div id="helpModal" aria-hidden="true" role="dialog" aria-labelledby="helpModalTitle" aria-describedby="helpModalDesc">
        <div class="help-content">
            <h2 id="helpModalTitle">Need Help?</h2>
            <p id="helpModalDesc">
                • This page displays detailed information about the selected book.<br>
                • You can read a brief description, check the price, and see the available stock.<br>
                • To buy the book, select the quantity and click “Add to Cart.”<br>
                • If you are an admin, you may also see options to modify or delete the product.<br>
                • Click the back button to return to the main products page.
            </p>
            <button onclick="closeHelp()" class="btn" style="background-color:#555; margin-top:1rem;">Close</button>
        </div>
    </div>

    <script>
        function openHelp() {
            document.getElementById('helpModal').style.display = 'block';
            document.getElementById('helpModal').setAttribute('aria-hidden', 'false');
        }

        function closeHelp() {
            document.getElementById('helpModal').style.display = 'none';
            document.getElementById('helpModal').setAttribute('aria-hidden', 'true');
        }
    </script>
</body>
</html>
