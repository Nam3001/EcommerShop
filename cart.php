<?php
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CartDAO.php';

session_start();
$isLogin = false;
$user = null;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if($user['roleId'] === 2 && $user['roleName'] === 'customer' && array_key_exists('roleId', $user) && array_key_exists('roleName', $user)) {
        $isLogin = true;
        $user = $_SESSION['user'];
    }
}

$cartProducts = [];
$productdao = new ProductDAO();
$subTotalPrice = 0;
$discount = 0;

if($isLogin === true) {
    $cartdao = new CartDAO();
    $cartItemsFromDB = $cartdao->getCartItems($user['id']);

    foreach($cartItemsFromDB as $cartItem) {

        $res = $productdao->selectProductById($cartItem['product_id']);
        if(count($res) > 0) {
            // chuẩn bị price
            $priceList = $productdao->selectProductPrice($cartItem['product_id']);
            $price = 0;
            if (count($priceList) === 0) {
                $price = 0;
            } else {
                $price = $priceList[0]['price'];
            }

            $product = $res[0];
            $product['cartQuantity'] = $cartItem['quantity'];
            $product['price'] = $price;
            array_push($cartProducts, $product);

            $subTotalPrice += $product['price'] * $product['cartQuantity'];
        }
    }
} else {
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

    foreach ($cart as $key => $value) {
        $product = $productdao->selectProductById($key);
        if (count($product) === 0) {
            continue;
        } else {
            // chuẩn bị giá
            $priceList = $productdao->selectProductPrice($key);
            $price = 0;
            if (count($priceList) === 0) {
                $price = 0;
            } else {
                $price = $priceList[0]['price'];
            }

            $product = $product[0];
            $product['cartQuantity'] = $value;
            $product['price'] = $price;
            array_push($cartProducts, $product);

            $subTotalPrice += $product['price'] * $product['cartQuantity'];
        }
    }
}

$totalPrice = $subTotalPrice + $discount;
?>
<!DOCTYPE html>
<html lang="en">
<!-- Basic -->

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Site Metas -->
    <title>ThewayShop - Ecommerce Bootstrap 4 HTML Template</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Site Icons -->
    <link rel="shortcut icon" href="assets/images/favicon.ico" type="image/x-icon">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"
          integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Site CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Responsive CSS -->
    <link rel="stylesheet" href="assets/css/responsive.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/custom.css">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>
<!-- HEADER -->
<?php include 'includes/header.php' ?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Cart</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Cart</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Cart  -->
<div class="cart-box-main">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-main table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Hình ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá bán</th>
                            <th>Số lượng</th>
                            <th>Tổng giá</th>
                            <th>Xoá</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        foreach ($cartProducts as $product) {
                            $element = "
                            <tr>
                                <td class='thumbnail-img'>
                                    <img class='img-fluid' src='". BASE_URL . '/' . "{$product['image']}' />
                                </td>
                                <td class='name-pr'>{$product['name']}</td>
                                <td class='price-pr'><p>" . number_format($product['price'], 0, '.', ',') . "đ</p></td>
                                <td class='quantity-box'><form><input type='number' data-productid='{$product['id']}' size='4' value='{$product['cartQuantity']}' min='0' step='1' max='{$product['quantity']}' class='c-input-text qty text'></form></td>
                                <td class='total-pr'>". number_format($product['cartQuantity'] * $product['price']) . "đ" . "<p></p></td>
                                <td class='remove-pr'><a href='removeCart.php?id={$product['id']}'><i class='fas fa-times'></i></a></td>
                            </tr>";
                            echo $element;
                        }
                        ?>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-lg-6 col-sm-6">
                <div class="coupon-box">
                    <div class="input-group input-group-sm">
                        <input class="form-control" placeholder="Nhập mã giảm giá của bạn" aria-label="Coupon code"
                               type="text">
                        <div class="input-group-append">
                            <button class="btn btn-theme" type="button">Áp dụng mã giảm giá</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row my-5">
            <div class="col-lg-8 col-sm-12"></div>
            <div class="col-lg-4 col-sm-12">
                <div class="order-box">
                    <h3>Tóm tắt đơn hàng</h3>
                    <div class="d-flex">
                        <h4>Tổng phụ</h4>
                        <div class="ml-auto font-weight-bold"><?php echo number_format($subTotalPrice) . ' đ' ?></div>
                    </div>
                    <div class="d-flex">
                        <h4>Giảm giá</h4>
                        <div class="ml-auto font-weight-bold"><?php echo number_format($discount) . ' đ' ?></div>
                    </div>
<!--                    <hr class="my-1">-->
<!--                    <div class="d-flex">-->
<!--                        <h4>Mã giảm giá</h4>-->
<!--                        <div class="ml-auto font-weight-bold"> $ 10</div>-->
<!--                    </div>-->
<!--                    <div class="d-flex">-->
<!--                        <h4>Thuế</h4>-->
<!--                        <div class="ml-auto font-weight-bold"> $ 2</div>-->
<!--                    </div>-->
                    <div class="d-flex">
                        <h4>Phí giao hàng</h4>
                        <div class="ml-auto font-weight-bold">Miễn phí</div>
                    </div>
                    <hr>
                    <div class="d-flex gr-total">
                        <h5>Tổng tiền:
                        </h5>
                        <div class="ml-auto h5"> <?php echo number_format($totalPrice) . '  đ' ?></div>
                    </div>
                    <hr>
                </div>
            </div>
            <div class="col-12 d-flex shopping-box"><a href="checkout.php" class="ml-auto btn hvr-hover <?php if(count($cartProducts) === 0) echo "disabled" ?>">Thanh toán</a>
            </div>
        </div>

    </div>
</div>
<!-- End Cart -->


<!--    FOOTER-->
<?php include 'includes/footer.php' ?>

<a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

<!-- ALL JS FILES -->
<script src="assets/js/jquery-3.2.1.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<!-- ALL PLUGINS -->
<script src="assets/js/jquery.superslides.min.js"></script>
<script src="assets/js/bootstrap-select.js"></script>
<script src="assets/js/inewsticker.js"></script>
<script src="assets/js/bootsnav.js."></script>
<script src="assets/js/images-loded.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/baguetteBox.min.js"></script>
<script src="assets/js/form-validator.min.js"></script>
<script src="assets/js/contact-form-script.js"></script>
<script src="assets/js/custom.js"></script>

<script>
    let tbodyEl = document.querySelector('.table-main.table-responsive tbody')

    for(let cartItemRemoveEl of tbodyEl.querySelectorAll('td.remove-pr a')) {
        cartItemRemoveEl.onclick = function (e) {
            e.preventDefault()
            removeCartItemURL = cartItemRemoveEl.href

            fetch(removeCartItemURL)
            window.location.reload()
        }
    }

    for(let trEl of tbodyEl.querySelectorAll('tr')) {
        let quantityInputEl = trEl.querySelector('input[type="number"]')
        quantityInputEl.onkeypress = function(e) {
            if(e.key === 'Enter') {
                e.preventDefault()
            }
        }

        quantityInputEl.oninput = function(e) {
            e.preventDefault()
            let quantity = Number(e.target.value)
            let max = e.target.max

            if(quantity > max)
                e.target.value = max
        }

        quantityInputEl.onchange = function(e){
            e.preventDefault();
            let productId = e.target.dataset.productid
            let quantity = Number(e.target.value)

            fetch(`updateCart.php?id=${productId}&qty=${quantity}`)
            window.location.reload()
        }
    }
</script>
</body>

</html>