<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../checkLogin.php';
include '../../databases/DBHelper.php';
include '../../databases/UnitDAO.php';
include '../../databases/CategoryDAO.php';
include '../../databases/BrandDAO.php';

$unitdao = new UnitDAO();
$unitList = $unitdao->selectAll();

$categorydao = new CategoryDAO();
$categoryList = $categorydao->selectAll();

$branddao = new BrandDAO();
$brandList = $branddao->selectAll();

// dùng để show toast khi thêm thất bại

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$insertSuccess = true;
if (isset($_SESSION['insertSuccess'])) {
    if (!$_SESSION['insertSuccess']) {
        $insertSuccess = false;
        unset($_SESSION['insertSuccess']);
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
    <title>Thêm sản phẩm</title>
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
                <form method="post" enctype="multipart/form-data" id="formInsert"
                      action="handle/handleInsertProduct.php">
                    <div class="modal-header">
                        <h4 class="modal-title">Thêm mới sản phẩm</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mt-2">
                            <label for="name-insert">Tên sản phẩm:</label>
                            <input type="text" class="form-control" id="name-insert" name="name" required>
                        </div>
                        <div class="form-group mt-2">
                            <label for="desc-insert">Mô tả sản phẩm:</label>
                            <textarea class="form-control" id="desc-insert" style="height: 100px" name="description"
                                      required></textarea>
                        </div>
                        <div class="form-group mt-2">
                            <label for="category-insert">Danh mục sản phẩm:</label>
                            <select class="form-control" id="category-insert" name="categoryId">
                                <?php
                                $order = 0;
                                foreach ($categoryList as $category) {
                                    if ($order === 0) {
                                        echo "<option value='{$category['id']}' selected>{$category['name']}</option>";
                                    } else {
                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                    }
                                    $order++;
                                }
                                unset($order);
                                ?>
                            </select>
                        </div>
<!--                        <div class="form-group mt-2">-->
<!--                            <label for="price-insert">Giá bán:</label>-->
<!--                            <input type="number" class="form-control" id="price-insert" name="price" required>-->
<!--                        </div>-->
                        <div class="form-group mt-2">
                            <label for="unit-insert">Đơn vị:</label>
                            <select class="form-control" id="unit-insert" aria-label="Default select example"
                                    name="unit"
                                    required>
                                <?php
                                $order = 0;
                                foreach ($unitList as $unit) {
                                    if ($order === 0) {
                                        echo "<option value='{$unit['id']}' selected>{$unit['name']}</option>";
                                    } else {
                                        echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
                                    }
                                    $order++;
                                }
                                unset($order);
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-2">
                            <label for="brand-insert">Thương hiệu:</label>
                            <select class="form-control" id="brand-insert" name="brandId">
                                <?php
                                $order = 0;
                                foreach ($brandList as $brand) {
                                    if ($order === 0) {
                                        echo "<option value='{$brand['id']}' selected>{$brand['name']}</option>";
                                    } else {
                                        echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                    }
                                    $order++;
                                }
                                unset($order);
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="status-insert">Trạng thái:</label>
                            <select class="form-control" id="status-insert" name="status">
                                <option selected value="1">Đang kinh doanh</option>
                                <option value="0">Ngừng kinh doanh</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <label for="imageInsert" class="form-label">Hình ảnh sản phẩm:</label>
                        <input type="file" accept=".png, .jpg, .jpeg" name="images[]" multiple required
                               class="form-control" id="imageInsert">
                    </div>
                    <div class="my-3 float-end">
                        <input type="submit" name="submit" class="btn btn-success" value="Thêm sản phẩm">
                    </div>
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

    // show toast message khi thêm danh mục thất bại
    let insertSuccess = <?php echo $insertSuccess ? 'true' : 'false' ?>;
    if (!insertSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-danger';
        toastElement.querySelector('.toast-body').textContent = 'Thêm sản phẩm thất bại!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
