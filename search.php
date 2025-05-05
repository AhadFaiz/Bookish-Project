


<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header("Location: login.php");
    exit;
}

$action = $_GET['action'] ?? 'modify'; 
$query = $_GET['q'] ?? '';
$searchTerm = strtolower(trim($query));

$sql = "SELECT * FROM product WHERE LOWER(title) LIKE ? OR LOWER(description) LIKE ?";
$stmt = $conn->prepare($sql);
$like = "%$searchTerm%";
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Products | Bookish</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/product_style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ffeef4;
        }
        .search-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .search-container h1 {
            text-align: center;
            color: #d63384;
        }
        .search-container form {
            display: flex;
            justify-content: center;
            margin-bottom: 2rem;
        }
        .search-container input[type="text"] {
            width: 70%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .search-container button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #c2185b;
        }
        .product-list {
            margin-top: 2rem;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ccc;
        }
        .product-item:last-child {
            border-bottom: none;
        }
        .product-item button {
            background-color: #d63384;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            cursor: pointer;
        }
        .product-item button:hover {
            background-color: #c2185b;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this book?");
        }
    </script>
</head>
<body>
    <div class="search-container">
    <form action="search.php" method="GET" style="display: flex; gap: 10px; align-items: center; justify-content: center;">
        <input 
            type="text" 
            name="q" 
            placeholder="Search by title or description..." 
            value="<?= htmlspecialchars($query) ?>" 
            style="width: 70%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 5px;"
        >
        <input type="hidden" name="action" value="<?= htmlspecialchars($action) ?>">
        <button type="submit">Search</button>
        <a href="AdminDashboard.php" style="text-decoration: none;">
            <button type="button" style="background-color:rgb(165, 161, 163);">Back</button>
        </a>
    </form>

    <div class="product-list">
        <?php if ($result->num_rows > 0): ?>
            <?php while ($product = $result->fetch_assoc()): ?>
                <div class="product-item">
                    <span><?= htmlspecialchars($product['title']) ?></span>
                    <?php if ($action === 'modify'): ?>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn">Edit</a>
                    <?php elseif ($action === 'delete'): ?>
                        <form action="delete_product.php" method="POST" style="display:inline;" onsubmit="return confirmDelete();">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <button type="submit">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No products found.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>