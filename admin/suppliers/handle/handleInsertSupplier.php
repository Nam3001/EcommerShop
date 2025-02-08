<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/SupplierDAO.php';

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];


    $supplierDao = new SupplierDAO();
    $isSuccess = $supplierDao->insert($name, $phone, $address, $email);

    session_start();
    if($isSuccess) {
        $_SESSION['insertSuccess'] = true;
        header('Location: ' . '../index.php');
    } else {
        $_SESSION['insertSuccess'] = false;
        header('Location: ' . '../insertBrand.php');
    }
}
