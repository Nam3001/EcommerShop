<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/CategoryDAO.php';

$id = $_POST['categoryId'];
$name = $_POST['name'];
$description = $_POST['description'];


$categoryDao = new CategoryDAO();
$isSuccess = $categoryDao->update($id, $name, $description);

session_start();
if ($isSuccess) {
    $_SESSION['updateSuccess'] = true;
    header('Location: ' . BASE_URL . '/admin/categories');
} else {
    $_SESSION['updateSuccess'] = false;
    header('Location: ' . '../updateCategory.php?id=' . $id);
}
