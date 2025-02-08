<?php
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CartDAO.php';
include

session_start();

if(!isset($_GET['id'])) {
    header('location: cart.php');
    die();
}

if(!isset($_GET['qty'])) {
    header('location: cart.php');
    die();
}

$isLogin = false;
$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if($user['roleId'] === 2 && $user['roleName'] === 'customer' && array_key_exists('roleId', $user) && array_key_exists('roleName', $user)) {
        $isLogin = true;
        $user = $_SESSION['user'];
    }
}

// prepare productId and quantity
$productId = $_GET['id'];
$quantity = $_GET['qty'];

if($isLogin) {
    $cartdao = new CartDAO();
    $cartdao->updateCart($user['id'], $productId, $quantity);
} else {
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        $product = null;
        $product = (new ProductDAO())->selectProductById($productId);
        if(count($product) > 0) $product = $product[0];
        $max = $product['quantity'];
        if($quantity > $max) {
            $cart[$productId] = $max;
        } else {
            $cart[$productId] = $quantity;
        }

        $_SESSION['cart'] = $cart;
    }
}

header('location: cart.php');