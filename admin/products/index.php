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
                                    <h2>Danh sách sản phẩm</h2>
                                </div>
                                <div class="col-sm-4 col-lg-3">
                                    <a href="insertProduct.php"
                                       class="btn btn-success d-flex justify-content-center" data-toggle="modal">
                                        <i class="material-icons">&#xE147;</i> <span
                                                class="ms-2">Thêm sản phẩm</span></a>
                                </div>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Tên sản phẩm</th>
                                <th>Giá bán</th>
                                <th>Tồn kho</th>
                                <th>Hãng</th>
                                <th>Danh mục</th>
                                <th>Trạng thái</th>
                                <th>Chức năng</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php

                            foreach ($productList as $product) {
                                $element = "<tr>";
                                $element .= "<td>{$product['productName']}</td>";
                                $element .= ("<td>" . number_format($product['price'], 0, ',', '.') . " đ" . "</td>");
                                $element .= "<td>{$product['quantity']}</td>";
                                $element .= "<td>{$product['brand']}</td>";
                                $element .= "<td>{$product['category']}</td>";

                                if ($product['status'] === 0) {
                                    $element .= "<td>Ngừng kinh doanh</td>";
                                } else if ($product['status'] === 1) {
                                    $element .= "<td>Đang kinh doanh</td>";
                                }

                                $element .= "<td>";
                                $element .= "<a href=updateProduct.php?id=" . $product['productId'] . " class='editBtn' data-bs-toggle='tooltip'  data-productId='{$product['productId']}' data-bs-toggle='modal'><i class='text-success material-icons' title='Edit'>&#xE254;</i></a>";
                                $element .= ("<a href='' class='deleteBtn' data-bs-toggle='tooltip' data-productId='{$product['productId']}'><i class='text-danger material-icons' title='Delete'>&#xE872;</i></a>");
                                $element .= "</td>";
                                $element .= "</tr>";

                                echo $element;
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>


<script>
    // handle click delete
    let deleteBtnList = document.querySelectorAll('.deleteBtn')
    for (let deleteBtn of deleteBtnList) {
        deleteBtn.onclick = function (e) {
            e.preventDefault();
            e.stopPropagation();

            // show modal xác nhận xoá
            const myModal = new bootstrap.Modal('#deleteProductModal', {
                keyboard: false
            })
            myModal.show();

            // get ra nút xác nhận xoá để set event khi click vào
            let deleteProductConfirmBtn = document.querySelector('#deleteProductModal .confirmDelete')
            deleteProductConfirmBtn.onclick = function () {
                myModal.hide()
                // lấy id product đê xoá
                let productId = Number(deleteBtn.dataset.productid)
                if (!isNaN(productId)) {
                    // chuẩn bị data id product để gửi
                    let formdata = new FormData()
                    formdata.append('id', productId)
                    fetch('handle/handleDeleteProduct.php', {
                        method: 'POST',
                        body: formdata
                    }).then(res => res.json()).then(data => {
                        // xử lý toast thông báo xoá thành công - thất bại
                        let toastElement = document.querySelector('.toast')
                        const toast = new bootstrap.Toast(toastElement)
                        if (data.status) {
                            toastElement.className = 'toast float-right bg-success';
                            toastElement.querySelector('.toast-body').textContent = 'Xoá sản phẩm thành công!'
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000)
                        } else {
                            toastElement.className = 'toast float-right bg-danger';
                            toastElement.querySelector('.toast-body').textContent = 'Xoá sản phẩm thất bại!'
                        }
                        toast.show();
                    })
                } else {
                    alert('lỗi: productId không phải số!')
                }
            }
        }
    }


    let updateSuccess = <?php echo $updateSuccess ? 'true' : 'false' ?>//;
    if (updateSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-success';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật sản phẩm thành công!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }

    let insertSuccess = <?php echo $insertSuccess ? 'true' : 'false' ?>;
    if (insertSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-success';
        toastElement.querySelector('.toast-body').textContent = 'Thêm sản phẩm thành công!'
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
