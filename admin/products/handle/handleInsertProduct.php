<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/ProductDAO.php';

$name = $_POST['name'];
$desc = $_POST['description'];
$unit = $_POST['unit'];
$imageList = $_FILES['images'];
$categoryId = $_POST['categoryId'];
$brandId = $_POST['brandId'];
$quantity = 0;
$status = $_POST['status'];



$images = array();

for($i = 0; $i < count($imageList['name']); $i++) {
    $imgtmp = $imageList['tmp_name'][$i];
    $imgname = basename($imageList['name'][$i]);

    $target_dir = '../../../uploads/productImages/';

    $indexedImgName = $name . "_" . uniqid();


    $target_file = $target_dir . $indexedImgName . "." . pathinfo($imgname, PATHINFO_EXTENSION);


    if (!move_uploaded_file($imgtmp, $target_file)) {
        header('Content-Type: application/json');
        echo json_encode(['status' => false]);
        return;
    }

    $target_file = str_replace('../../../', '', $target_file);
    array_push($images, $target_file);
}


$productdao = new ProductDAO();
$isSuccess = $productdao->insert($name, $desc, $unit, $images, $quantity, $categoryId, $brandId, $status);

session_start();
if($isSuccess) {
    $_SESSION['insertSuccess'] = true;
    header('Location: ' . '../index.php');
} else {
    $_SESSION['insertSuccess'] = false;
    header('Location: ' . '../insertProduct.php');
}
