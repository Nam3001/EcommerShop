<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/ProductDAO.php';
include '../../databases/UnitDAO.php';
include '../../databases/SupplierDAO.php';
include '../checkLogin.php';

$supplierDao = new SupplierDAO();
$supplierList = $supplierDao->selectAll();

$productDao = new ProductDAO();
$productList = $productDao->selectAll();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
    <title>Nhập hàng</title>
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
<div class="sb-nav-fixed">
    <?php include '../includes/header.php' ?>

    <div id="layoutSidenav">
        <?php include '../includes/sidebar.php' ?>
        <div id="layoutSidenav_content">
            <main>
                <form id="form" method="POST" action="handle/handleInsertReceipt.php">
                    <div class="container-fluid p-4">
                        <h2>Nhập hàng</h2>
                        <div class="form-group mt-2">
                            <label for="supplierId">Nhà cung cấp:</label>
                            <select class="form-control" id="supplier-id" name="supplierId">
                                <?php
                                $order = 0;
                                foreach ($supplierList as $supplier) {
                                    if ($order === 0) {
                                        echo "<option value='{$supplier['id']}' selected>{$supplier['name']}</option>";
                                    } else {
                                        echo "<option value='{$supplier['id']}'>{$supplier['name']}</option>";
                                    }
                                    $order++;
                                }
                                unset($order);
                                ?>
                            </select>
                            <div id="productListContainer"></div>
                            <button id="add-product-btn" class="btn btn-success btn-sm mt-3 d-flex align-items-center"><i class="material-icons me-1">&#xE147;</i>Thêm sản phẩm</button>
                        </div>
                        <div class="d-flex justify-content-end"><input id="submit-btn" type="submit" name="submit" value="Nhập hàng" class="btn btn-danger"></div>
                    </div>
                </form>
            </main>
            <?php include '../includes/footer.php' ?>
        </div>
    </div>

    <!--toast message-->
    <?php include '../includes/toast.php' ?>


</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script src="../assets/js/scripts.js"></script>
<script>
    let addProductBtn = document.querySelector('#add-product-btn');
    let productListContainer = document.querySelector('#productListContainer')

    let form = document.querySelector('#form')
    let submitBtn = document.getElementById('submit-btn')

    let count = 1;

    addProductBtn.onclick = function(e) {
        e.preventDefault()

        const div = document.createElement('div');
        div.classList.add('product-item')
        div.classList.add('row')
        div.classList.add('mt-3')


        div.innerHTML = "" +
            "<label style='width: 100px'>Sản phẩm: </label>" +
            "<div class='col-3'><select class='form-select' required name='productId-" + count +"'>" +
            <?php foreach ($productList as $product) {
                echo '`<option value="'. $product['productId'] . '">' . $product['productName'] . '</option>`+';
            } ?>
            "</select></div>" +
            "<div class='col-3'><input class='form-control col-3' type='number' min='1' required name='quantity-" + count +"' placeholder='số lượng' /></div>" +
            "<div class='col-3'><input class='form-control col-3' type='number' min='1' required name='price-" +count + "' placeholder='giá nhập' /></div>" +
            "<button style='width: 50px' class='delete-product btn btn-sm btn-danger'>Xoá</button>"
        productListContainer.append(div)

        let productItemElList = document.querySelectorAll('.product-item')
        for (const productItemEl of productItemElList) {
            let deleteBtn = productItemEl.querySelector('.delete-product')
            deleteBtn.onclick = function(e) {
                e.preventDefault()
                productItemEl.remove()
            }
        }
        count++

        if(productItemElList.length > 0) {
            submitBtn.classList.remove('disabled')
        }
    }

    window.onload = function() {
        submitBtn.classList.add('disabled')
    }
</script>
</body>
</html>
