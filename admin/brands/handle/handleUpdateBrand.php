<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/BrandDAO.php';


if(isset($_POST['submit'])) {
    $id = $_POST['brandId'];
    $name = $_POST['name'];
    $description = $_POST['description'];


    $brandDao = new BrandDAO();
    $isSuccess = $brandDao->update($id, $name, $description);

    session_start();
    if ($isSuccess) {
        $_SESSION['updateSuccess'] = true;
        header('Location: ' . BASE_URL . '/admin/brands');
    } else {
        $_SESSION['updateSuccess'] = false;
        header('Location: ' . '../updateBrand.php?id=' . $id);
    }

}