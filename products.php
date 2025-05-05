<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookish | Products</title>

 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


    <link rel="stylesheet" href="./css/product_style.css">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>

<header>
    <section class="flex">
        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>

        <div style="display: flex; align-items: center; gap: 2rem;">
            <a href="index.php" class="btn back-btn"><i class="fas fa-arrow-left"></i></a>
            <a href="index.php" class="logo">Bookish<span>.</span></a>
        </div>

        <nav class="navbar">
            <a href="index.php">home</a>

            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="About.html">about</a>
            <?php endif; ?>

            <a href="products.php">products</a>

            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="Contact.html">contact us</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="AdminDashboard.php" class="admin-btn">Admin's Dashboard</a>
            <?php endif; ?>
        </nav>

        <div class="icons">
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="cart.php" class="fas fa-shopping-cart"></a>
            <?php endif; ?>

            <a href="login.php" class="fas fa-user"></a>

            <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                <a href="logout.php" class="btn">Log out</a>
            <?php endif; ?>
        </div>
    </section>
</header>


<section class="products" id="products">
    <h1 class="heading"> latest <span>products</span> </h1>

    <div class="box-container">
        <?php
        include "db_connection.php";

        $query = "SELECT * FROM product";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                $id = $product['id'];
                $name = $product['title'];
                $price = $product['price'];
                $image = $product['image_name'];
                $stock = $product['stock'];
        ?>
                <div class="box">
                    <div class="image">
                        <img src="images/<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($name) ?>">

                        <div class="icons" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; margin-top: 1rem;">
                            <a href="book_details.php?id=<?= $id ?>" class="btn-outline small-btn" title="View Details">
                                <i class="fas fa-eye"></i> View
                            </a>

                            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                                <form method="post" action="add_to_cart.php" style="display: flex; gap: 0.5rem; align-items: center;">
                                    <input type="hidden" name="product_id" value="<?= $id ?>">
                                    <input type="number" name="quantity" value="1" min="1" max="<?= $stock ?>" required style="width: 50px; padding: 2px;">
                                    <button type="submit" class="btn small-btn" title="Add to Cart">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="content">
                        <h3><?= htmlspecialchars($name) ?></h3>
                        <div class="price">
                            <sup style="font-size: 0.6em;">SAR</sup> <?= htmlspecialchars($price) ?>
                        </div>
                    </div>
                </div>
        <?php
            }
        } else {
            echo "<p>No products available.</p>";
        }
        ?>
    </div>
</section>


<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>quick links</h3>
            <a href="index.php">home</a>
            <a href="About.html">about</a>
            <a href="products.php">products</a>
            <a href="Contact.html">contact</a>
        </div>
        <div class="box">
            <h3>extra links</h3>
            <a href="login.php">my account</a>
        </div>
        <div class="box">
            <h3>Locations</h3>
            <p>Khobar</p>
            <p>Riyadh</p>
            <p>Jeddah</p>
            <p>Dammam</p>
        </div>
        <div class="box">
            <h3>Contact Info</h3>
            <p>+966 50 662 2314</p>
            <p><a href="mailto:Bookish@hotmail.com">Bookish@hotmail.com</a></p>
            <p>Saudi Arabia, Khobar - 34714</p>
            <div style="margin-top: 1rem;">
                <iframe
                  src="https://www.google.com/maps?q=Khobar,Saudi+Arabia&output=embed"
                  width="100%"
                  height="200"
                  style="border:0; border-radius: 0.5rem;"
                  allowfullscreen=""
                  loading="lazy"
                  referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
            <img src="images/payment.png" alt="">
        </div>
    </div>
    <div class="credit"> created by <span> web based systems group 2 </span> | all rights reserved </div>
</section>


<style>
.btn {
    color: #fff;
    background-color: rgb(225, 63, 157);
    padding: 0.5rem 1rem;
    border: none;
    border-radius: 4px;
    text-decoration: none;
    font-size: 1rem;
    cursor: pointer;
}

.btn:hover {
    background-color: rgb(77, 163, 250);
}

.btn-outline {
    background: transparent;
    border: 1px solid #aaa;
    color: #333;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 0.9rem;
    transition: 0.3s;
    text-decoration: none;
}

.btn-outline:hover {
    background-color: #eee;
    border-color: #555;
    color: #000;
}

.small-btn {
    font-size: 0.85rem;
    padding: 5px 8px;
}
</style>

</body>
</html>
