<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/SupplierDAO.php';

if(isset($_POST['submit'])) {
    $id= $_POST['id'];
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];


    $supplierDao = new SupplierDAO();
    $isSuccess = $supplierDao->update($id, $name, $phone, $address, $email);

    session_start();
    if($isSuccess) {
        $_SESSION['updateSuccess'] = true;
        header('Location: ' . '../index.php');
    } else {
        $_SESSION['updateSuccess'] = false;
        header('Location: ' . '../insertBrand.php');
    }
}
