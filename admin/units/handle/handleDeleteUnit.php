<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/UnitDAO.php';

$unitId = $_POST['id'];

$unitDao = new UnitDAO();
$rowCount = $unitDao->delete($unitId);

header('Content-Type: application/json');
if($rowCount >= 1) {
    echo json_encode(array('status' => true));
} else {
    echo json_encode(array('status' => false));
}