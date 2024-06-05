<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    if (!isset($_SESSION['basket'])) {
        $_SESSION['basket'] = [];
    }

    if (isset($_SESSION['basket'][$product_id])) {
        $_SESSION['basket'][$product_id] += $quantity;
    } else {
        $_SESSION['basket'][$product_id] = $quantity;
    }

    header("Location: shop.php");
    exit();
}
?>
