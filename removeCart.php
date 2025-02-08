<?php
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/CartDAO.php';

session_start();

if(!isset($_GET['id'])) {
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


if($isLogin) {
    $cartdao = new CartDAO();
    $cartdao->deleteCartItem($user['id'], $_GET['id']);
} else {
    if(isset($_SESSION['cart'])) {
        unset($_SESSION['cart'][$_GET['id']]);
    }
}

header('location: cart.php');