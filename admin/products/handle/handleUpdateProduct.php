<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/ProductDAO.php';

$productId = intval($_POST['productid']);
$prodName = $_POST['name'];
$desc = $_POST['description'];
$unit = $_POST['unit'];
$imageList = $_FILES['images'];
$categoryId = $_POST['categoryId'];
$brandId = $_POST['brandId'];
$status = $_POST['status'];


$imageIdListToDelete = $_POST['deletedImage']; // chứa cái id của image chuẩn bị xoá
$imageIdListToDelete = explode(';', $imageIdListToDelete);
$imageIdListToDelete = array_filter($imageIdListToDelete);

$imagesToInsert = array(); //chứa path của các image để upload


for ($i = 0; $i < count(array_filter($imageList['name'])); $i++) {
    $imgtmp = $imageList['tmp_name'][$i];
    $imgname = basename($imageList['name'][$i]);

    $target_dir = '../../../uploads/productImages/';
    $indexedImgName = $prodName . "_" . uniqid();

    $target_file = $target_dir . $indexedImgName . "." . pathinfo($imgname, PATHINFO_EXTENSION);


    if (!move_uploaded_file($imgtmp, $target_file)) {
        echo "upload image error";
        return;
    }


    $target_file = str_replace('../../../', '', $target_file);

    array_push($imagesToInsert, $target_file);
}

$productdao = new ProductDAO();
$isSuccess = $productdao->update($productId, $prodName, $desc, $unit, $brandId, $categoryId, $status, $imagesToInsert, $imageIdListToDelete);
session_start();

if ($isSuccess) {
    $_SESSION['updateSuccess'] = true;
    header('Location: ' . BASE_URL . '/admin/products');
} else {
    $_SESSION['updateSuccess'] = false;
    header('Location: ' . '../updateProduct.php?id=' . $productId);
}
