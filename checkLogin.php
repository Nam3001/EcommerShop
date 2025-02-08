<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if(!isset($_SESSION['user'])) {
    header('location: ' . BASE_URL . '/login.php');
    die();
} else {
    $user = $_SESSION['user'];
    if($user['roleId'] !== 2 || $user['roleName'] !== 'customer' || !array_key_exists('roleId', $user) || !array_key_exists('roleName', $user)) {
        header('location: ' . BASE_URL . '/login.php');
        die();
    }
}
