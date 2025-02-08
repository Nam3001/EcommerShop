<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/CategoryDAO.php';

$categoryId = $_POST['id'];

$categoryDao = new CategoryDAO();
$rowCount = $categoryDao->delete($categoryId);

header('Content-Type: application/json');
if($rowCount >= 1) {
    echo json_encode(array('status' => true));
} else {
    echo json_encode(array('status' => false));
}