<?php
session_start();
if (!isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_name = trim($_POST['old_name']);
    $new_name = trim($_POST['new_name']);
    $new_price = trim($_POST['new_price']);
    $new_image = $_FILES['new_image']['name'];

    $filePath = 'products.txt';
    $products = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

 
    foreach ($products as &$product) {
        list($name, $price, $image) = explode('|', $product);
        if ($name === $old_name) {
            if (!empty($new_image)) {
                $target_dir = "images/";
                $target_file = $target_dir . basename($new_image);
                move_uploaded_file($_FILES['new_image']['tmp_name'], $target_file);
                $image = $new_image;
            }
            $product = "$new_name|$new_price|$image";
            break;
        }
    }

   
    file_put_contents($filePath, implode(PHP_EOL, $products));


    header('Location: products.html');
    exit;
}
?>