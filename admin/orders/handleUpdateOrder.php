<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/OrderDAO.php';
include '../../databases/ProductDAO.php';
include '../checkLogin.php';

if(!isset($_POST['id']) || !is_numeric($_POST['id'])) {
    header('location: ' . BASE_URL . '/errors/404.php');
}
if(!isset($_POST['order-status'])) {
    die();
}

$orderId = $_POST['id'];
$status = $_POST['order-status'];


$orderDao = new OrderDAO();

$selectOrderRes = $orderDao->selectOrderById($orderId);
$order = null;
if(count($selectOrderRes) > 0) {
    $order = $selectOrderRes[0];
}

$orderItemList = array();
$selectOrderItemRes = $orderDao->selectOrderItems($orderId);
if (count($selectOrderItemRes) > 0) {
    $orderItemList = $selectOrderItemRes;
}

$isSuccess = true;

if($order !== null) {
    $prevStatus = $order['status'];

    if($prevStatus === 'confirmed' || $prevStatus === 'delivered' || $prevStatus === 'shipping') {
        if($status === 'processing' || $status === 'cancelled') {
            foreach ($orderItemList as $orderItem) {
                $productDao = new ProductDAO();
                $db = new DBHelper();
                $res = $db->select("select quantity from product where id = :productId", [':productId' => $orderItem['product_id']]);
                if(count($res) > 0) {
                    $quantity = $res[0]['quantity'];
                    $updateQuantityRes = $productDao->updateQuantity($orderItem['product_id'], $quantity + $orderItem['quantity']);

                    if(!$updateQuantityRes) $isSuccess = false;
                }
            }
        }
    }

    if($prevStatus === 'processing' || $prevStatus === 'cancelled') {
        if($status === 'confirmed' || $status === 'delivered' || $status === 'shipping') {
            foreach ($orderItemList as $orderItem) {
                $productDao = new ProductDAO();
                $db = new DBHelper();
                $res = $db->select("select quantity from product where id = :productId", [':productId' => $orderItem['product_id']]);
                if(count($res) > 0) {
                    $quantity = $res[0]['quantity'];
                    $updateQuantityRes = $productDao->updateQuantity($orderItem['product_id'], $quantity - $orderItem['quantity']);
                    if(!$updateQuantityRes) $isSuccess = false;
                }
            }
        }
    }
}

$updateRes = $orderDao->updateOrderStatus($orderId, $status);

session_start();
header('Content-Type: application/json');
if($updateRes && $isSuccess) {
    echo json_encode(array('updateSuccess' => true));
} else {
    echo json_encode(array('updateSuccess' => false));
}