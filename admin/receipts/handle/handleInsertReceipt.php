<?php
if(!isset($_POST['submit'])) {
    die();
}

include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../../databases/DBHelper.php';
include '../../../databases/ReceiptDAO.php';
include '../../../databases/ProductDAO.php';
include '../checkLogin.php';

$receiptDao = new ReceiptDAO();
$productDao = new ProductDAO();

$count = 0;
foreach ($_POST as $key => $value) {
    if(str_contains($key, 'productId')) {
        $count++;
    }
}

$isSuccess = true;

$isInsertReceiptRes = $receiptDao->insertReceipt($_POST['supplierId']);
if(!$isInsertReceiptRes) {
    $isSuccess = false;
}
$latestId = $receiptDao->db->lastInsertId();
if($isInsertReceiptRes) {

    for ($i = 1; $i <= $count; $i++) {
        $productId = $_POST["productId-$i"];
        $quantity = $_POST["quantity-$i"];
        $price = $_POST["price-$i"];

        $isInsertDetailSuccess = $receiptDao->insertReceiptDetails($latestId, $productId, $price, $quantity);
        if($isInsertDetailSuccess) {
            $res = $productDao->selectProductById($productId);
            $prevQuantity = 0;
            if(count($res) > 0) {
                $prevQuantity = $res[0]['quantity'];
            }
            $newQuantity = $prevQuantity + $quantity;
            $productDao->updateQuantity($productId, $newQuantity);
        } else {
            $isSuccess = false;
        }
    }

}

session_start();
if ($isSuccess) {
    $_SESSION['insertSuccess'] = true;
    header('Location: ' . BASE_URL . '/admin/receipts');
} else {
    $_SESSION['insertSuccess'] = false;
    header('Location: ' . BASE_URL . '/admin/receipts/insertReceipt.php');
}
