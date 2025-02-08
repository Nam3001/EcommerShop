<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/SupplierDAO.php';

$supplierId = $_POST['id'];

$supplierDao = new SupplierDAO();
$rowCount = $supplierDao->delete($supplierId);

header('Content-Type: application/json');
if($rowCount >= 1) {
    echo json_encode(array('status' => true));
} else {
    echo json_encode(array('status' => false));
}