<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/BrandDAO.php';

$name = $_POST['name'];
$desc = $_POST['description'];

$brandDao = new BrandDAO();
$isSuccess = $brandDao->insert($name, $desc);

session_start();
if($isSuccess) {
    $_SESSION['insertSuccess'] = true;
    header('Location: ' . '../index.php');
} else {
    $_SESSION['insertSuccess'] = false;
    header('Location: ' . '../insertBrand.php');
}