<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/OrderDAO.php';
include '../checkLogin.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: errors/404.php');
    die();
}

$id = intval($_GET['id']);

$orderDao = new OrderDAO();

$res = $orderDao->selectOrderById($id);
if (count($res) === 0) {
    header('location: errors/404.php');
    die();
}

$order = $res[0];

$orderItemList = array();

$res = $orderDao->selectOrderItems($order['id']);
if (count($res) > 0) {
    $orderItemList = $res;
}

$subTotalPrice = 0;
foreach ($orderItemList as $item) {
    $subTotalPrice += doubleval($item['price']) * intval($item['quantity']);
}
$totalPrice = $subTotalPrice + intval($order['discount']);


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$updateSuccess = false;
if (isset($_SESSION['updateOrderStatusSuccess'])) {
    if ($_SESSION['updateOrderStatusSuccess']) {
        $updateSuccess = true;
        unset($_SESSION['updateOrderStatusSuccess']);
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
    <title>Chi tiết đơn hàng sản phẩm</title>
    <link href="../assets/css/styles.css" rel="stylesheet"/>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
            integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
            crossorigin="anonymous"></script>


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
                    <div class="d-flex justify-content-center"><h4>Xem và cập nhật trạng thái đơn hàng</h4></div>
                    <div class="container">
                        <form method="POST" action="handleUpdateOrder.php?id=<?php echo $order['id']?>">
                            <div class="row">
                                <div class="col-3">Mã đơn hàng:</div>
                                <div class="col-3"><?php echo $order['id'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Họ tên khách hàng:</div>
                                <div class="col-3"><?php echo $order['firstname'] . ' ' . $order['lastname'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Ngày đặt:</div>
                                <div class="col-3"><?php echo date_format(date_create($order['createdAt']), 'd/m/Y H:m:s') ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Địa chỉ:</div>
                                <div class="col-3"><?php echo $order['address'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Số điện thoại:</div>
                                <div class="col-3"><?php echo $order['phone'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Email:</div>
                                <div class="col-3"><?php echo $order['email'] ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Giảm giá:</div>
                                <div class="col-3"><?php echo number_format($order['discount']) . ' đ' ?></div>
                            </div>
                            <div class="row">
                                <div class="col-3">Trạng thái đơn hàng:</div>
                                <div class="col-3">
                                    <select id="status" class="form-select" name="order-status">
                                        <option <?php if ($order['status'] === 'processing') echo 'selected' ?>
                                                value="processing">Đang chờ xử lý
                                        </option>
                                        <option <?php if ($order['status'] === 'confirmed') echo 'selected' ?>
                                                value="confirmed">Đã xác nhận
                                        </option>
                                        <option <?php if ($order['status'] === 'shipping') echo 'selected' ?>
                                                value="shipping">Đang giao hàng
                                        </option>
                                        <option <?php if ($order['status'] === 'delivered') echo 'selected' ?>
                                                value="delivered">Đã giao
                                        </option>
                                        <option <?php if ($order['status'] === 'cancelled') echo 'selected' ?>
                                                value="cancelled">Đã huỷ
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end">
                                <button id="update-btn" class="btn btn-success mt-2 float-end">Cập nhật đơn hàng</button>
                            </div>
                        </form>
                    </div>

                    <p class="h5">Danh sách sản phẩm:</p>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá bán</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 1;
                        foreach ($orderItemList as $item) {
                            $element = "
                                <tr>
                                    <th>$count</th>
                                    <td>{$item['product_id']}</td>
                                    <td>{$item['productName']}</td>
                                    <td>{$item['quantity']}</td>
                                    <td>" . number_format($item['price']) . ' đ' . "</td>
                                </tr>";

                            echo $element;
                            $count++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="mt-2">
                        <p class="h6">Tóm tắt đơn hàng:</p>
                        <p class="mb-0">Đơn giá: <span
                                    class="text-dark"><?php echo number_format($subTotalPrice) . ' đ' ?></span></p>
                        <p class="mb-0">Giảm giá: <span
                                    class="text-dark"><?php echo number_format(intval($order['discount'])) . ' đ' ?></span>
                        </p>
                        <p class="mb-0 text-danger">Tổng tiền:
                            <span"><?php echo number_format($totalPrice) . ' đ' ?></span></p>
                    </div>
                </div>
            </main>
            <?php include '../includes/footer.php' ?>
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
<?php include '../includes/toast.php' ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>

<script>
    let updateBtn = document.querySelector('#update-btn')
    let selectElement = document.querySelector('#status')

    updateBtn.onclick = function (e) {
        e.preventDefault();

        let status = selectElement.value

        let formData = new FormData()
        formData.append('id', '<?php echo $order['id']?>')
        formData.append('order-status', status)

        fetch('<?php echo BASE_URL ?>/admin/orders/handleUpdateOrder.php', {
            method: 'POST',
            body: formData
        }).then(res => res.json()).then(data => {


            let toastElement = document.querySelector('.toast')
            const toast = new bootstrap.Toast(toastElement)
            if (data.updateSuccess) {
                toastElement.className = 'toast float-right bg-success';
                toastElement.querySelector('.toast-body').textContent = 'Cập nhật trạng thái thành công!'
            } else {
                toastElement.className = 'toast float-right bg-danger';
                toastElement.querySelector('.toast-body').textContent = 'Cập nhật trạng thái thất bại!'
            }
            toast.show();
        })
    }
</script>
</body>
</html>
