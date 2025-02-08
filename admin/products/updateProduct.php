<?php
include '../../configs/config.php';
include '../../configs/database.php';
include '../checkLogin.php';
include '../../databases/DBHelper.php';
include '../../databases/ProductDAO.php';
include '../../databases/UnitDAO.php';
include '../../databases/BrandDAO.php';
include '../../databases/CategoryDAO.php';

$productId = null;
if (isset($_GET['id'])) {
    if (is_numeric($_GET['id'])) {
        $productId = intval($_GET['id']);
    }
}

$unitdao = new UnitDAO();
$unitList = $unitdao->selectAll();

$categorydao = new CategoryDAO();
$categoryList = $categorydao->selectAll();

$branddao = new BrandDAO();
$brandList = $branddao->selectAll();

$productdao = new ProductDAO();
$product = $productdao->selectProductById($productId);
if(count($product) === 0) {
    echo "không tìm thấy sản phẩm";
    die();
}
$product = $product[0];

$db = new DBHelper();
$imageList = $db->select("select * from image where product_id = $productId");


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
    <title>Cập nhật sản phẩm</title>
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
                <form id="formUpdate" method="post" enctype="multipart/form-data"
                      action="handle/handleUpdateProduct.php">
                    <div class="">
                        <h4 class="">Cập nhật sản phẩm</h4>
                    </div>
                    <div>
                        <div class="form-group mt-3">
                            <label for="name">Tên sản phẩm:</label>
                            <input type="text" class="form-control" name="name" id="name" required
                                   value="<?php echo $product['name'] ?>">
                        </div>
                        <input type="text" style="display: none;" value="<?php echo $productId ?>" name="productid"/>
                        <div class="form-group mt-3">
                            <label for="description">Mô tả sản phẩm:</label>
                            <textarea class="form-control" id="description" name="description" style="height: 100px;"
                                      required><?php echo $product['description']; ?></textarea>
                        </div>

                        <div class="form-group mt-2">
                            <label for="category">Danh mục sản phẩm:</label>
                            <select class="form-control" id="category" name="categoryId">
                                <?php
                                foreach ($categoryList as $category) {
                                    if ($category['id'] === $product['category_id']) {
                                        echo "<option value='{$category['id']}' selected>{$category['name']}</option>";
                                    } else {
                                        echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
<!--                        <div class="form-group mt-3">-->
<!--                            <label for="price">Giá bán:</label>-->
<!--                            <input type="number" class="form-control" name="price" id="price" required-->
<!--                                   value="--><?php //echo $product['price']; ?><!--">-->
<!--                        </div>-->
                        <div class="form-group mt-3">
                            <label for="unit-select">Đơn vị:</label>
                            <select class="form-control" id="unit-select" aria-label="Default select example"
                                    name="unit" required>
                                <?php
                                foreach ($unitList as $unit) {
                                    if ($unit['id'] === $product['unit_id']) {
                                        echo "<option  value='{$unit['id']}' selected>{$unit['name']}</option>";
                                    } else {
                                        echo "<option value='{$unit['id']}'>{$unit['name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="brand-select">Thương hiệu:</label>
                            <select class="form-control" id="brand-select" name="brandId">
                                <?php
                                foreach ($brandList as $brand) {
                                    if ($brand['id'] === $product['brand_id']) {
                                        echo "<option value='{$brand['id']}' selected>{$brand['name']}</option>";
                                    } else {
                                        echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="status-select">Trạng thái:</label>
                            <select class="form-control" id="status-select" name="status">
                                <?php
                                $statusList = [1 => 'Đang kinh doanh', 0 => 'Ngừng kinh doanh'];
                                for ($i = 1; $i >= 0; $i--) {
                                    if ($product['status'] === $i) {
                                        echo "<option selected value='$i'>{$statusList[$i]}</option>";
                                    } else {
                                        echo "<option value='$i'>{$statusList[$i]}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label for="imageUpdate">Chọn ảnh:</label>
                            <input type="file" accept=".png, .jpg, .jpeg" class="form-control" id="imageUpdate"
                                   aria-describedby="inputGroupFileAddon01" name="images[]" multiple>
                        </div>
                        <input name="deletedImage" type="text" style="display: none;" value=""/>
                        <div id="imageList" class="my-4 row">
                            <?php
                            foreach ($imageList as $image) {
                                $element = "<div class='col-3 col-lg-2'>";
                                $element .= '<div class="card p-2 mb-3">';
                                $element .= '<div class="ratio ratio-1x1">';
                                $element .= "<img class='card-img-top rounded' src='" . BASE_URL . '/' . $image['path'] . "'" . "data-id='{$image['id']}'" . "class='card-img-top'>";
                                $element .= '</div>';
                                $element .= '<div class="card-body px-1 py-2">';
                                $element .= '<div class="btn btn-danger float-end">Xoá</div>';
                                $element .= '</div></div></div>';
                                echo $element;
                            }
                            ?>
                        </div>

                    </div>
                    <div class="float-end mb-4">
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
    let imageListContainer = document.querySelector('#imageList');
    let imageCardList = document.querySelectorAll('#imageList .card');
    for (let imageCard of imageCardList) {
        let imageDeleteBtn = imageCard.querySelector('.btn.btn-danger');
        imageDeleteBtn.onclick = function () {
            let imgId = imageCard.querySelector('img').dataset.id;
            let deletedImageElement = document.querySelector('input[name="deletedImage"]')
            deletedImageElement.value += `${imgId};`
            imageCard.remove();

            // nếu xoá hết hình rồi thì input name=images[] là required
            if (document.querySelectorAll('#imageList .card').length === 0) {
                let inputImages = document.querySelector('#imageUpdate')
                inputImages.setAttribute('required', '')
            }
        }
    }

    // show toast message khi cập nhật không thành công
    let updateSuccess = <?php echo $updateSuccess ? 'true' : 'false' ?>;
    if (!updateSuccess) {
        let toastElement = document.querySelector('.toast')
        toastElement.className = 'toast float-right bg-danger';
        toastElement.querySelector('.toast-body').textContent = 'Cập nhật sản phẩm thất bại!'
        const toast = new bootstrap.Toast(toastElement)
        toast.show()
    }
</script>
</body>
</html>
