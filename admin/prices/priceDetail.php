<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../checkLogin.php';
include '../../databases/DBHelper.php';
include '../../databases/ProductDAO.php';
include '../../databases/UnitDAO.php';
include '../../databases/BrandDAO.php';
include '../../databases/CategoryDAO.php';
include '../checkLogin.php';

$productId = null;
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $productId = intval($_GET['id']);
    }
}


$productdao = new ProductDAO();
$product = $productdao->selectProductById($productId);
if (count($product) === 0) {
    echo "không tìm thấy sản phẩm";
    die();
}
$product = $product[0];

$priceListRaw = $productdao->selectProductPrice($productId); // du lieu gia ban chua xu ly
$priceList = array(['price' => null, 'dateStart' => null, 'dateEnd' => null], ['price' => 0, 'dateStart' => null, 'dateEnd' => null]);
if (count($priceListRaw) === 0) {
    $priceList[0] = ['price' => null, 'dateStart' => null, 'dateEnd' => null];
    $priceList[1] = ['price' => 0, 'dateStart' => null, 'dateEnd' => null];
} else {
    for($i = count($priceListRaw) - 1; $i >= 0 ; $i--) {
        $price = $priceListRaw[$i];
        if($price['isOriginalPrice'] === 0) {
            $priceList[0] = ['price' => $price['price'], 'dateStart' => $price['dateStart'], 'dateEnd' => $price['dateEnd']];
        } else if($price['isOriginalPrice' === 1]) {
            $priceList[1] = ['price' =>$price['price']];
        }
    }
}


if($priceList[0]['price'] === null) {
    $res = $productdao->selectProductSalePriceButDontApplyYet($productId);
    if(count($res) > 0) {
        $priceList[0] = ['price' => $res[0]['price'], 'dateStart' => $res[0]['dateStart'], 'dateEnd' => $res[0]['dateEnd'], 'note' => 'Chưa áp dụng hôm nay'];
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$updateSuccess = false;
if (isset($_SESSION['updateSuccess'])) {
    if ($_SESSION['updateSuccess']) {
        $updateSuccess = true;
        unset($_SESSION['updateSuccess']);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Chi tiết giá sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet"/>
    <link href="../assets/css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
            integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
            crossorigin="anonymous"></script>


</head>
<body class="sb-nav-fixed">
<?php include '../includes/header.php' ?>
<div id="layoutSidenav">
    <?php include '../includes/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-4">
                <div class="">
                    <h4 class="">Chi tiết giá sản phẩm</h4>
                </div>
                <div>
                    <div class="row">
                        <div class="col-2">Mã sản phẩm:</div>
                        <div class="col-3"><?php echo $product['id'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Tên sản phẩm:</div>
                        <div class="col-3"><?php echo $product['name'] ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Giá gốc:</div>
                        <div class="col-3"><?php echo number_format($priceList[1]['price']) . ' đ' ?></div>
                    </div>
                    <div class="row">
                        <div class="col-2">Giá ưu đãi:</div>
                        <div class="col-2"><?php if($priceList[0]['price'] !== null) echo number_format($priceList[0]['price']) . ' đ'; else echo 'Không có' ?><span class="text-danger"><?php if(isset($priceList[0]['note'])) echo ' (' .$priceList[0]['note'] . ')' ?></span></div>
                        <?php if($priceList[0]['price'] !== null) {
                            if(strlen($priceList[0]['dateStart']) > 0) {
                                echo "<div class='col-2'>Từ ngày: ". date_format(date_create($priceList[0]['dateStart']), "d/m/Y"). "</div>";
                            } else echo "<div class='col-2'>Từ ngày: __/__/____ </div>";
                            echo " ";
                            if(strlen($priceList[0]['dateEnd']) > 0) {
                                echo "<div class='col'>Đến ngày: " . date_format(date_create($priceList[0]['dateEnd']), "d/m/Y") . "</div>";
                            }else echo "<div class='col-2'>Đến ngày: __/__/____ </div>";
                        } ?>
                    </div>

                </div>
            </div>
        </main>
        <?php include '../includes/footer.php' ?>
    </div>
</div>

<!--toast message-->
<?php include '../includes/toast.php' ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>
<script>
    let updateSuccess = <?php echo $updateSuccess ? 'true' : 'false' ?>//;
    if (updateSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-success';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật Giá thành công!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
