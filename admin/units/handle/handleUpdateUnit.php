<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include  '../../../databases/UnitDAO.php';

$id = $_POST['unitId'];
$name = $_POST['name'];
$description = $_POST['description'];

$unitDao = new UnitDAO();
$isSuccess = $unitDao->update($id, $name, $description);

session_start();
if ($isSuccess) {
    $_SESSION['updateSuccess'] = true;
    header('Location: ' . BASE_URL . '/admin/units');
} else {
    $_SESSION['updateSuccess'] = false;
    header('Location: ' . '../updateUnit.php?id=' . $id);
}