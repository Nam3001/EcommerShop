<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/BrandDAO.php';

$brandId = $_POST['id'];

$brandDao = new BrandDAO();
$rowCount = $brandDao->delete($brandId);

header('Content-Type: application/json');
if($rowCount >= 1) {
    echo json_encode(array('status' => true));
} else {
    echo json_encode(array('status' => false));
}