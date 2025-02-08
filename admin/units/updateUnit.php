<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../checkLogin.php';
include '../../databases/DBHelper.php';
include '../../databases/UnitDAO.php';

$unitId = null;
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $unitId = intval($_GET['id']);
    }
}


$unitDao = new UnitDAO();
$unit = $unitDao->selectById($unitId);
if(count($unit) === 0) {
    echo "không tìm thấy đơn vị";
    die();
}
$unit = $unit[0];


// dùng để show toast message khi cập nhật không thành công

if(session_status() === PHP_SESSION_NONE) {
    session_start();
}
$updateSuccess = true;
if (isset($_SESSION['updateSuccess'])) {
    if (!$_SESSION['updateSuccess']) {
        $updateSuccess = false;
        unset($_SESSION['updateSuccess']);
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
    <title>Cập nhật đơn vị</title>
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
                <form id="formUpdate" method="post"
                      action="handle/handleUpdateUnit.php">
                    <div class="">
                        <h4 class="">Cập nhật đơn vị</h4>
                    </div>
                    <div>
                        <div class="form-group mt-3">
                            <label for="name">Tên đơn vị:</label>
                            <input type="text" class="form-control" name="name" id="name" required
                                   value="<?php echo $unit['name'] ?>">
                        </div>
                        <input type="text" style="display: none;" value="<?php echo $unitId ?>" name="unitId"/>
                        <div class="form-group mt-3">
                            <label for="description">Mô tả đơn vị:</label>
                            <textarea class="form-control" id="description" name="description" style="height: 100px;"
                            ><?php echo $unit['description']; ?></textarea>
                        </div>
                    </div>
                    <div class="float-end mt-4">
                        <input type="submit" class="btn btn-success" value="Cập nhật">
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
    // show toast message khi cập nhật không thành công
    let updateSuccess = <?php echo $updateSuccess ? 'true' : 'false' ?>;
    if (!updateSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-danger';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật đơn vị thất bại!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
