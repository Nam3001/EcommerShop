<?php
include '../../../configs/config.php';
include '../../../configs/database.php';
include '../../checkLogin.php';
include '../../../databases/DBHelper.php';
include '../../../databases/ProductDAO.php';


$id = $_POST['id'];


// xoá ảnh trong /uploads
$imageList = (new DBHelper())->select("select * from image where product_id = :productId", [':productId' => $id]);
$uploadDirPath = ROOT_DIR . 'uploads/productImages';

foreach($imageList as $image) {
    $imagename = pathinfo($image['path'], PATHINFO_BASENAME);
    $imagePath = $uploadDirPath . '/' . $imagename;
    unlink($imagePath);
}


header('Content-Type: application/json');
$productdao = new ProductDAO();
$rowCount = $productdao->delete($id);

if($rowCount >= 1) {
    echo json_encode(array('status' => true));
} else {
    echo json_encode(array('status' => false));
}
?>
