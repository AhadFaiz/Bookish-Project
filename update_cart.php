<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $index = (int)$_POST['index'];
    $quantity = (int)$_POST['quantity'];

    if (isset($_SESSION['cart'][$index])) {
        $_SESSION['cart'][$index]['quantity'] = $quantity;
    }

    header('Location: cart.php');
    exit;
}
?>