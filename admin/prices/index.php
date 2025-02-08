<?php
/**
 * @var int $perPage;
 */
include '../../configs/config.php';
include '../../configs/database.php';
include '../checkLogin.php';
include '../../databases/DBHelper.php';
include '../../databases/ProductDAO.php';
include '../../databases/UnitDAO.php';
include '../checkLogin.php';

$page = 1;
if(isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}

$unitdao = new UnitDAO();
$unitList = $unitdao->selectAll();

$productdao = new ProductDAO();
$productList = $productdao->selectByPage($page, $perPage);
foreach ($productList as &$product) {
    $res = $productdao->selectProductPrice($product['productId']);
    if(count($res) === 0) $product['price'] = 0;
    else {
        $product['price'] = $res[0]['price'];
    }
}
unset($product);


if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$updateSuccess = false;
if (isset($_SESSION['updateSuccess'])) {
    if ($_SESSION['updateSuccess']) {
        $updateSuccess = true;
        unset($_SESSION['updateSuccess']);
    }
}

$insertSuccess = false;
if (isset($_SESSION['insertSuccess'])) {
    if ($_SESSION['insertSuccess']) {
        $insertSuccess = true;
        unset($_SESSION['insertSuccess']);
    }
}

$totalRow = $productdao->count();
$totalPage = floor($totalRow / $perPage);
if($totalRow % $perPage > 0) {
    $totalPage += 1;
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
    <title>Danh sách sản phẩm</title>
    <link href="../assets/css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
            crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/jquery.twbsPagination.min.js"></script>

</head>
<body class="sb-nav-fixed">
<?php include '../includes/header.php' ?>

<div id="layoutSidenav">
    <?php include '../includes/sidebar.php' ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid p-4">

                <div>
                    <div class="table-wrapper">
                        <div class="table-title">
                            <div class="row mb-3 justify-content-between">
                                <div class="col-sm-6">
                                    <h2>Thiết lập giá cho sản phẩm</h2>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>STT</th>
                                <th>Mã SP</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Hãng</th>
                                <th>Danh mục</th>
                                <th>Chức năng</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count = 1;
                            foreach ($productList as $product) {
                                $element = "<tr>";
                                $element .= "<td>$count</td>";
                                $element .= "<td>{$product['productId']}</td>";
                                $element .= "<td>{$product['productName']}</td>";
                                $element .= ("<td>" . number_format($product['price'], 0, ',', '.') . " đ" . "</td>");
                                $element .= "<td>{$product['brand']}</td>";
                                $element .= "<td>{$product['category']}</td>";

                                $element .= "<td>";
                                $element .= "<a href=updatePrice.php?id=" . $product['productId'] . " class='editBtn me-2' data-bs-toggle='tooltip'  data-productId='{$product['productId']}' data-bs-toggle='modal'>Cập nhật giá</a>";
                                $element .= ("<a href='priceDetail.php?id={$product['productId']}' class='viewBtn' data-bs-toggle='tooltip' data-productId='{$product['productId']}'>Xem chi tiết</a>");
                                $element .= "</td>";
                                $element .= "</tr>";

                                echo $element;
                                $count++;
                            }
                            ?>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-center"><ul id="pagination-demo" class="pagination"></ul></div>
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
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật sản phẩm thành công!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }


</script>

<script>
    $('#pagination-demo').twbsPagination({
        totalPages: <?php echo $totalPage ?>,
        visiblePages: 7,
        startPage: <?php echo $page ?>
    }).on('page', function (event, page) {
        window.location.assign('<?php echo BASE_URL ?>/admin/products/index.php?page=' + page)
    });
</script>
</body>
</html>
