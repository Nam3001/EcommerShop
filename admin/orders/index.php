<?php
/**
 * @var int $perPage ;
 */
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/OrderDAO.php';
include '../checkLogin.php';

$page = 1;
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $page = intval($_GET['page']);
}

$orderDao = new OrderDAO();

$orderList = array();

if(!isset($_GET['status'])) {
    $orderList = $orderDao->selectOrderByPage($page, $perPage);
} else {
    if($_GET['status'] === 'all') {
        $orderList = $orderDao->selectOrderByPage($page, $perPage);
    } else {
        $orderList = $orderDao->selectOrderByPage($page, $perPage, $_GET['status']);
    }
}


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


$totalRow = $orderDao->countOrder();
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
                                        <h2>Danh sách đơn hàng</h2>
                                    </div>
                                    <div class="col-sm-4 col-lg-3">
                                        <select id="status-select" class="form-select" name="order-status">
                                            <option <?php if (!isset($_GET['status'])) echo 'selected' ?>
                                                    value="all">Tất cả
                                            </option>
                                            <option <?php if (isset($_GET['status']) && $_GET['status'] === 'processing') echo 'selected' ?>
                                                    value="processing">Đang chờ xử lý
                                            </option>
                                            <option <?php if (isset($_GET['status']) && $_GET['status'] === 'confirmed') echo 'selected' ?>
                                                    value="confirmed">Đã xác nhận
                                            </option>
                                            <option <?php if (isset($_GET['status']) && $_GET['status'] === 'shipping') echo 'selected' ?>
                                                    value="shipping">Đang giao hàng
                                            </option>
                                            <option <?php if (isset($_GET['status']) && $_GET['status'] === 'delivered') echo 'selected' ?>
                                                    value="delivered">Đã giao
                                            </option>
                                            <option <?php if (isset($_GET['status']) && $_GET['status'] === 'cancelled') echo 'selected' ?>
                                                    value="cancelled">Đã huỷ
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã đơn hàng</th>
                                    <th>Ngày đặt hàng</th>
                                    <th>Tên khách hàng</th>
                                    <th>Điện thoại</th>
                                    <th>Trạng thái</th>
                                    <th>Chức năng</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php

                                $count = 1;
                                foreach ($orderList as $order) {
                                    $element = "<tr>";
                                    $element .= "<td>$count</td>";
                                    $element .= "<td>{$order['order_id']}</td>";
                                    $element .= "<td>" . date_format(date_create($order['createdAt']), "d/m/Y H:m:s") . "</td>";
                                    $element .= "<td>" . $order['firstname'] . ' ' . $order['lastname'] . "</td>";
                                    $element .= "<td>{$order['phone']}</td>";
                                    $element .= "<td>{$order['status']}</td>";

                                    $element .= "<td>";
                                    $element .= "<a href=orderDetail.php?id=" . $order['order_id'] . " class='editBtn' data-bs-toggle='tooltip'  data-productId='{$order['order_id']}' data-bs-toggle='modal'>Xem</a>";

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

<script src="../assets/js/scripts.js"></script>
<script>
    $('#pagination-demo').twbsPagination({
        totalPages: <?php echo $totalPage ?>,
        visiblePages: 7,
        startPage: <?php echo $page ?>
    }).on('page', function (event, page) {
        window.location.assign('<?php echo BASE_URL ?>/admin/orders/index.php?page=' + page)
    });
</script>
<script>
    let statusSelectElement = document.getElementById('status-select')
    statusSelectElement.onchange = function(e) {
        let url = '<?php echo BASE_URL ?>' + '/admin/orders/index.php'
        let urlParam = new URLSearchParams()
        urlParam.set('status', statusSelectElement.value)
        url += '?' + urlParam.toString()

        window.location.assign(url);
    }
</script>
</body>
</html>
