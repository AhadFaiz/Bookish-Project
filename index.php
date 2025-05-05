<?php
session_start();


$registration_success = '';
if (isset($_SESSION['registration_success'])) {
    $registration_success = $_SESSION['registration_success'];
    unset($_SESSION['registration_success']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://use.fontawesome.com/be1ba39dfe.js"></script>
    <link rel="stylesheet" href="./css/style.css">
    <title>Bookish</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>

<h1>Welcome to Bookish!</h1>

<header>
    <section class="flex">
        <input type="checkbox" name="" id="toggler">
        <label for="toggler" class="fas fa-bars"></label>

        <a href="#" class="logo">Bookish<span>.</span></a>

        <nav class="navbar">
            <a href="index.php">home</a>

           
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="About.html">about</a>
            <?php endif; ?>

            <a href="products.php">products</a>

            
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
                <a href="Contact.html">contact us</a>
            <?php endif; ?>


    <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="#past-purchases">past purchases</a>
    <?php endif; ?>
        </nav>

        <div class="icons">
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <span class="admin-name" style="margin-right: 10px; font-weight: bold; color: #333;">
            Welcome, <span style="color: #d63384 "><?php echo htmlspecialchars($_SESSION['admin_name'] ?? 'Admin'); ?></span>!
        </span>
    <?php endif; ?>
   
    <a href="login.php" class="fas fa-user"></a>
    <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
        <a href="cart.php" class="fas fa-shopping-cart"></a>
    <?php endif; ?>
</div>
		
		

       
        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] && isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
            <a href="AdminDashboard.php" class="btn admin-btn">Admin Dashboard</a>
        <?php endif; ?>
    </section>
</header>

<div class="home-container">

    <section class="home" id="home">
        <div class="content">
            <h3>Explore a World of Books</h3>
            <span> Books That Inspire, Entertain & Educate </span>
            <p>You will enjoy our website! .</p>
            <?php if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']): ?>
    <a href="products.php" class="btn">shop now</a>
<?php endif; ?>

    
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
        <a href="logout.php" class="btn">Log out, Admin!</a>
    <?php endif; ?>

        </div>    
      
    </section> 

    <?php
    if (isset($_COOKIE['past_purchases'])) {
        $pastPurchases = unserialize($_COOKIE['past_purchases']);
        
        if (!empty($pastPurchases) && is_array($pastPurchases)) {
            echo '<div id="past-purchases" style="margin-top: 50px; padding: 20px; background-color: #f9f9f9; border-radius: 10px;">';
            echo '<h2 style="color: #333; margin-bottom: 20px; text-align:center;">Your Past Purchases</h2>';
            echo '<ul style="list-style-type: none; padding: 0; text-align:center;">';
            
            foreach ($pastPurchases as $purchase) {
                if (is_array($purchase) && isset($purchase['title'], $purchase['price'], $purchase['quantity'])) {
                    echo '<li style="padding: 10px 0; border-bottom: 1px solid #ccc;">';
                    echo htmlspecialchars($purchase['title']) . ' - SAR ' . number_format($purchase['price'], 2) . ' (Quantity: ' . intval($purchase['quantity']) . ')';
                    echo '</li>';
                } else {
                   
                    echo '<li style="padding: 10px 0; border-bottom: 1px solid #ccc;">' . htmlspecialchars((string)$purchase) . '</li>';
                }
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
    ?>

    <?php if (!empty($registration_success)): ?>
        <p style="color:green; text-align:center;"><?php echo $registration_success; ?></p>
    <?php endif; ?>

</div> 

</body>
</html>
