<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../../databases/DBHelper.php';
include '../../databases/SupplierDAO.php';
include '../checkLogin.php';
if(session_status() === PHP_SESSION_NONE) {
    session_start();
}

$supplierId = null;
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $supplierId = intval($_GET['id']);
    }
}

if($supplierId === null) {
    header('location: '. BASE_URL . '/errors/404.php');
    die();
}

$supplierDao = new SupplierDAO();
$supplier = $supplierDao->selectById($supplierId);

if(count($supplier) === 0) {
    header('location: '. BASE_URL . '/errors/404.php');
    die();
}
$supplier = $supplier[0];

$insertSuccess = true;
if (isset($_SESSION['insertSuccess'])) {
    if (!$_SESSION['insertSuccess']) {
        $insertSuccess = false;
    }
    unset($_SESSION['insertSuccess']);
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
    <title>Thêm nhà cung cấp</title>
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
                    <form method="post" enctype="multipart/form-data" id="formInsert"
                          action="handle/handleUpdateSupplier.php">
                        <div class="modal-header">
                            <h4 class="modal-title">Cập nhật nhà cung cấp</h4>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?php echo $supplier['id'] ?>" />
                            <div class="form-group mt-2">
                                <label for="name">Tên nhà cung cấp:</label>
                                <input type="text" class="form-control" id="name" name="name" required value="<?php echo $supplier['name'] ?>">
                            </div>
                            <div class="form-group mt-2">
                                <label for="address">Địa chỉ:</label>
                                <textarea class="form-control" id="address" style="height: 100px" name="address" required><?php echo $supplier['address'] ?></textarea>
                            </div>
                            <div class="form-group mt-2">
                                <label for="phone">Số điện thoại:</label>
                                <input type="text" class="form-control" id="phone" name="phone" required value="<?php echo $supplier['phone'] ?>">
                            </div>
                            <div class="form-group mt-2">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $supplier['email'] ?>">
                            </div>
                        </div>
                        <div class="my-3 float-end">
                            <input type="submit" name="submit" class="btn btn-success" value="Cập nhật nhà cung cấp">
                        </div>
                    </form>
                </div>
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

    // show toast message khi thêm thương hiệu thất bại
    let insertSuccess = <?php echo $insertSuccess ? 'true' : 'false' ?>;
    console.log(insertSuccess)
    if (!insertSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-danger';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật nhà cung cấp thất bại!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
