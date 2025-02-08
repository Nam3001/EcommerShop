<?php
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CartDAO.php';

session_start();

if(!isset($_GET['id'])) {
    header('location: index.php');
    die();
}

$productId = $_GET['id'];

// check sản phẩm ngừng kinh doanh thì không thêm
$productdao = new ProductDAO();
if(!$productdao->checkBusinessProduct($productId)) {
    header('location: index.php');
    die();
}

$quantity = 1;
if(isset($_GET['quantity']) && is_numeric($_GET['quantity'])) {
    $quantity = intval($_GET['quantity']);
    if($quantity <= 0) $quantity = 1;
}
$product = null;
$product = $productdao->selectProductById($productId);
if(count($product) > 0) $product = $product[0];
$max = $product['quantity'];

$isLogin = false;
$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if($user['roleId'] === 2 && $user['roleName'] === 'customer') {
        $isLogin = true;
        $user = $_SESSION['user'];
    }
}

if($isLogin) {
    $cartdao = new CartDAO();
    $cartdao->addToCart($user['id'], $productId, $quantity);
} else {
    if(isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];
        $productExist = array_key_exists($productId, $cart);
        if($productExist) {
            if($cart[$productId] + $quantity > $max) {
                $cart[$productId] = $max;
            } else {
                $cart[$productId] = $cart[$productId] + $quantity;
            }
        } else {
            $cart[$productId] = $quantity;
        }

        $_SESSION['cart'] = $cart;
    } else {
        $_SESSION['cart'] = array($productId => $quantity);
    }

}
