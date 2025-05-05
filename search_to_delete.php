<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Missing book ID.";
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM product WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Book not found.";
    exit;
}

$book = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Book | Bookish</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/product_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body {
    background-color: #ffffff; 
    font-family: Arial, sans-serif;
}

.edit-container {
    background-color: #ffeef4; 
}


        .edit-container {
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
            border-radius: 10px;
            background-color: #fff0f5;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .edit-container h2 {
            text-align: center;
            color: #d63384;
            font-size: 2rem;
            margin-bottom: 2rem;
            position: relative;
        }

        .edit-container h2::after {
            content: "";
            display: block;
            width: 60px;
            height: 4px;
            background-color: #d63384;
            margin: 0.5rem auto 0;
            border-radius: 5px;
        }

        .edit-container .header-title {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .edit-container img {
            width: 150px;
            border-radius: 8px;
        }

        .edit-container form {
            font-size: 1.1rem;
        }

        .edit-container input,
        .edit-container label,
        .edit-container button {
            display: block;
            width: 100%;
            margin-bottom: 1.2rem;
            font-size: 1.05rem;
        }

        .edit-container input {
            padding: 0.5rem;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .edit-container button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 0.75rem;
            border-radius: 6px;
            font-size: 1.1rem;
            cursor: pointer;
        }

        .edit-container button:hover {
            background-color: #c2185b;
        }

        .btn-back {
            background-color: #6c757d;
            color: white;
            padding: 10px 15px;
            display: inline-block;
            border-radius: 6px;
            margin-bottom: 1.5rem;
            text-decoration: none;
            font-size: 1rem;
        }

        .btn-back:hover {
            background-color: #5a6268;
        }

        .image-section {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .image-section label {
            font-weight: bold;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<section class="edit-container">
    <a href="products.php" class="btn-back"><i class="fas fa-arrow-left"></i> Back to Products</a>

    <div class="header-title">
        <i class="fas fa-book fa-lg" style="color:#d63384;"></i>
        <h2>Edit Book</h2>
    </div>

    <form action="update_product.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $book['id'] ?>">

        <div class="image-section">
            <label>Current Book Image:</label><br>
            <img src="images/<?= htmlspecialchars($book['image_name']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
        </div>

        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>

        <label>Price (SAR):</label>
        <input type="number" name="price" step="0.01" value="<?= $book['price'] ?>" required>

        <label>Category:</label>
        <input type="text" name="category" value="<?= $book['category'] ?>" required>

        <label>Author ID:</label>
        <input type="number" name="author_id" value="<?= $book['author_id'] ?>" required>

        <label>Stock:</label>
        <input type="number" name="stock" value="<?= $book['stock'] ?>" required>

        <label>Change Image (optional):</label>
        <input type="file" name="image">

        <button type="submit"><i class="fas fa-edit"></i> Update Book</button>
    </form>
</section>

</body>
</html>
