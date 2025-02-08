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

$priceList = array(['price' => null, 'dateStart' => null, 'dateEnd' => null], ['price' => 0]);
if (count($priceListRaw) === 0) {
    $priceList[0] = ['price' => null, 'dateStart' => null, 'dateEnd' => null];
    $priceList[1] = ['price' => 0];
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
// dùng để show toast message khi cập nhật không thành công

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$isUpdateSuccess = true;
if (isset($_SESSION['updateSuccess'])) {
    if (!$_SESSION['updateSuccess']) {
        $updateSuccess = false;
        unset($_SESSION['updateSuccess']);
    }
}

if(isset($_POST['submit'])) {
    $originalPrice = $_POST['originalPrice'];
    $salePrice = isset($_POST['salePrice']) ? $_POST['salePrice'] : "";
    $dateStart = isset($_POST['date-start']) ? $_POST['date-start'] : "";
    $dateEnd = isset($_POST['date-end']) ? $_POST['date-end'] : "";

    if (((strlen($dateStart) > 0 && strlen($dateEnd) > 0)  && ($dateStart > $dateEnd || $dateEnd < date('Y-m-d')) || ((strlen($dateStart) == 0 && strlen($dateEnd) > 0)  && ($dateEnd < date('Y-m-d'))))) {
        $isUpdateSuccess = false;
    } else {
        // update original price
        $sql = "select * from price where product_id = :productId and isOriginalPrice = 1 order by datetime desc limit 1";
        $db = new DBHelper();
        $res = $db->select($sql, [':productId' => $productId]);

        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $now = date('Y-m-d H:i:s');

        try {
            if(count($res) > 0) {
                if(intval($res[0]['price']) !== intval($originalPrice)) {
                    $db->insert('price', ['price' => $originalPrice, 'product_id' => $productId, 'datetime' => $now, 'isOriginalPrice' => 1]);
                }
            } else {
                $db->insert('price', ['price' => $originalPrice, 'product_id' => $productId, 'datetime' => $now, 'isOriginalPrice' => 1]);
            }


            // update sale price
            if(strlen($salePrice) > 0 && is_numeric(($salePrice))) {
                // xoá giá cũ
                $db->delete('price', "product_id = $productId and isOriginalPrice = 0");

                if(isset($_POST['date-management'])) {
                    $db->insert('price', ['product_id' => $productId, 'price' => $salePrice, 'isOriginalPrice' => 0, 'datetime' => $now, 'dateStart' => strlen($dateStart) === 0 ? null : $dateStart, 'dateEnd' => strlen($dateEnd) === 0 ? null : $dateEnd]);
                } else {
                    $db->insert('price', ['product_id' => $productId, 'price' => $salePrice, 'isOriginalPrice' => 0, 'datetime' => $now, 'dateStart' => null, 'dateEnd' => null]);
                }
            } else if(strlen($salePrice) === 0) {
                $db->delete('price', "product_id = $productId and isOriginalPrice = 0");
            }
        } catch(Exception $e) {
            $isUpdateSuccess = false;
        }
    }


    if ($isUpdateSuccess) {
        $_SESSION['updateSuccess'] = true;
        header('Location: ' . BASE_URL . '/admin/prices/priceDetail.php' . "?id=$productId");
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
    <title>Cập nhật giá sản phẩm</title>
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
                <form method="POST">
                    <div class="">
                        <h4 class="">Cập nhật giá sản phẩm</h4>
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
                        <div class="form-group row mt-2">
                            <label for="originalPrice" class="col-2 col-form-label">Giá gốc:</label>
                            <div class="col-md-3 col-sm-7">
                                <input class="form-control" type="number" id="originalPrice-input" name="originalPrice"
                                       value="<?php echo $priceList[1]['price'] ?>" required>
                            </div>
                        </div>
                        <div class="form-group row mt-2">
                            <label for="salePrice" class="col-2 col-form-label">Giá ưu đãi:</label>
                            <div class="col-md-3 col-sm-7">
                                <input class="form-control" type="number" id="salePrice-input" name="salePrice"
                                       value="<?php echo $priceList[0]['price'] ?>">
                            </div>
                            <div class="col form-check">
                                <input type="checkbox" class="form-check-input" id="date-management" name="date-management"  <?php if(!($priceList[0]['dateStart'] === null && $priceList[0]['dateEnd'] === null)) echo 'checked' ?>>
                                <label class="form-check-label" for="date-management">Quản lý ngày</label>
                            </div>
                        </div>
                        <span class="text-danger"><?php if(isset($priceList[0]['note'])) echo ' (' .$priceList[0]['note'] . ')' ?></span>
                        <div class="mt-2" id="date-management-group">
                            <div class="form-group row">
                                <label class="col-2" for="date-end">Từ ngày: </label>
                                <div class="col-5">
                                    <input type="date" class="form-control" name="date-start" id="date-start-input" value="<?php if($priceList[0]['dateStart'] !== null) echo $priceList[0]['dateStart']?>">
                                </div>
                            </div>
                            <div class="form-group row mt-2">
                                <label class="col-2" for="date-end">Đến ngày: </label>
                                <div class="col-5"><input type="date" class="form-control" name="date-end" id="date-end-input" value="<?php if($priceList[0]['dateEnd'] !== null) echo $priceList[0]['dateEnd']?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end"><input type="submit" class="btn btn-success" name="submit" value="Cập nhật giá"></div>
                </form>
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
    let checkDateManagement = document.querySelector('#date-management')
    let dateManagementGroup = document.querySelector('#date-management-group')
    let dateStartInput = document.querySelector('#date-start-input');
    let dateEndInput = document.querySelector('#date-end-input')
    checkDateManagement.onchange = function(e) {
        e.preventDefault()
        if(e.target.checked) {
            dateManagementGroup.classList.remove('d-none')
            dateEndInput.value = '<?php if($priceList[0]['dateEnd'] !== null) echo $priceList[0]['dateEnd']?>';
            dateStartInput.value = '<?php if($priceList[0]['dateStart'] !== null) echo $priceList[0]['dateStart']?>';

            console.log(dateStartInput.value)
            console.log(dateEndInput.value)
        } else {
            dateManagementGroup.classList.add('d-none')
            dateEndInput.value = "";
            dateStartInput.value = "";
        }
    }

    window.onload = function() {
        if(!checkDateManagement.checked) {
            dateManagementGroup.classList.add('d-none')
        }
    }


    // show toast message khi cập nhật không thành công
    let updateSuccess = <?php echo $isUpdateSuccess ? 'true' : 'false' ?>;
    if (!updateSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-danger';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật giá thất bại!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
