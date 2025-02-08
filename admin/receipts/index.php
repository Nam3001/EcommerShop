<?php
/**
 * @var int $perPage ;
 */
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/ReceiptDAO.php';
include '../../databases/SupplierDAO.php';
include '../checkLogin.php';

$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}

$supplierDao = new SupplierDAO();

$receiptDao = new ReceiptDAO();
$receiptList = $receiptDao->selectReceiptByPage($page, $perPage);
//print_r($receiptList);
//die();


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$insertSuccess = false;
if (isset($_SESSION['insertSuccess'])) {
    if ($_SESSION['insertSuccess']) {
        $insertSuccess = true;
        unset($_SESSION['insertSuccess']);
    }
}


$totalRow = $receiptDao->countReceipt();
$totalPage = floor($totalRow / $perPage);
if ($totalRow % $perPage > 0) {
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
    <title>Danh sách đơn hàng</title>
    <link href="../assets/css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
            crossorigin="anonymous"></script>
    <script src="../../assets/js/jquery-3.2.1.min.js"></script>
    <script src="../../assets/js/jquery.twbsPagination.min.js"></script>

</head>
<body class="sb-nav-fixed">
<?php include '../includes/header.php' ?>

<div class="sb-nav-fixed">
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
                                        <h2>Danh sách nhập hàng</h2>
                                    </div>
                                    <div class="col-sm-4 col-lg-3">
                                        <a href="insertReceipt.php"
                                           class="btn btn-success d-flex justify-content-center" data-toggle="modal">
                                            <i class="material-icons">&#xE147;</i> <span
                                                    class="ms-2">Nhập thêm hàng</span></a>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã nhập hàng</th>
                                    <th>Tên nhà cung cấp</th>
                                    <th>Ngày nhập</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $count = 1;
                                foreach ($receiptList as $receipt) {
                                    $element = "<tr>";
                                    $element .= "<td>$count</td>";
                                    $element .= "<td>{$receipt['id']}</td>";

                                    $res = $supplierDao->selectById($receipt['supplier_id']);
                                    if(count($res) > 0) {
                                        $element .= "<td>{$res[0]['name']}</td>";
                                    } else {
                                        $element .= "<td>?</td>";
                                    }

                                    $element .= "<td>". date_format(date_create($receipt['datetime']), 'd/m/Y'). "</td>";

                                    $element .= "<td>";
                                    $element .= "<a href=receiptDetail.php?id=" . $receipt['id'] . " class='editBtn' data-bs-toggle='tooltip'  data-productId='{$receipt['id']}' data-bs-toggle='modal'>Xem</a>";

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
                <div class="d-flex justify-content-center">
                    <ul id="pagination-demo" class="pagination"></ul>
                </div>
            </main>
        </div>
    </div>

    <!--toast message-->
    <?php include '../includes/toast.php' ?>

    <div id="deleteProductModal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xoá sản phẩm</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Bạn có muốn xoá sản phẩm này không?</p>
                    <p class="text-warning"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Bỏ qua</button>
                    <button class="confirmDelete btn btn-danger">Xoá</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!--toast message-->
<?php include '../includes/toast.php' ?>
<script>
    let insertSuccess = <?php echo $insertSuccess ? 'true' : 'false' ?>//;
    if (insertSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-success';
        toastElement.querySelector('.toast-body').textContent = 'Nhập hàng thành công!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }


</script>
<script src="../assets/js/scripts.js"></script>
<script>
    $('#pagination-demo').twbsPagination({
        totalPages: <?php echo $totalPage ?>,
        visiblePages: 7,
        startPage: <?php echo $page ?>
    }).on('page', function (event, page) {
        window.location.assign('<?php echo BASE_URL ?>/admin/receipts/index.php?page=' + page)
    });
</script>

</body>
</html>
