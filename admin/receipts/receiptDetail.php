<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/ReceiptDAO.php';
include '../../databases/SupplierDAO.php';
include '../../databases/ProductDAO.php';
include '../checkLogin.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: errors/404.php');
    die();
}

$id = intval($_GET['id']);

$receiptDao = new ReceiptDAO();

$res = $receiptDao->selectReceiptById($id);
$receipt = null;
if (count($res) > 0) {
    $receipt = $res[0];
} else {
    header('location: errors/404.php');
    die();
}

$supplierDao = new SupplierDAO();
$supplier = null;
$res = $supplierDao->selectById($receipt['supplier_id']);
if(count($res) > 0) {
    $supplier = $supplierDao->selectById($receipt['supplier_id'])[0];
}

$receiptItemList = array();
$res = $receiptDao->selectReceiptDetails($id);
if (count($res) > 0) {
    $receiptItemList = $res;
}

$totalPrice = 0;
foreach ($receiptItemList as $item) {
    $totalPrice += (doubleval($item['price']) * intval($item['quantity']));
}

$productDao = new ProductDAO();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <meta name="description" content=""/>
    <meta name="author" content=""/>
    <title>Chi tiết nhập hàng</title>
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
                    <div class="d-flex justify-content-center"><h4>Chi tiết nhập hàng</h4></div>
                    <div class="container">

                        <div class="row">
                            <div class="col-3">Mã nhập hàng:</div>
                            <div class="col-3"><?php echo $receipt['id'] ?></div>
                        </div>
                        <div class="row">
                            <div class="col-3">Tên nhà cung cấp:</div>
                            <div class="col-3"><?php echo $supplier['name'] ?></div>
                        </div>
                        <div class="row">
                            <div class="col-3">Ngày đặt:</div>
                            <div class="col-3"><?php echo date_format(date_create($receipt['datetime']), 'd/m/Y H:m:s') ?></div>
                        </div>

                    </div>

                    <p class="h5 mt-4">Danh sách sản phẩm:</p>
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Mã sản phẩm</th>
                            <th>Tên sản phẩm</th>
                            <th>Số lượng</th>
                            <th>Giá bán</th>
                            <th>Tổng giá</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $count = 1;
                        foreach ($receiptItemList as $item) {
                            $element = "
                                <tr>
                                    <th>$count</th>
                                    <td>{$item['product_id']}</td>";

                            $product = $productDao->selectProductById($item['product_id'])[0];
                            $element .= "<td>{$product['name']}</td>";
                            $element .= "<td>{$item['quantity']}</td>
                                    <td>" . number_format($item['price']) . ' đ' . "</td>
                                    <td>". number_format(intval($item['quantity']) * doubleval($item['price'])) . ' đ' . "</td>
                                </tr>";

                            echo $element;
                            $count++;
                        }
                        ?>
                        </tbody>
                    </table>
                    <div class="mt-2">

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
