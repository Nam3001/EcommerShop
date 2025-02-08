<?php
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CartDAO.php';

session_start();
$isLogin = false;
$user = null;
if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if ($user['roleId'] === 2 && $user['roleName'] === 'customer' && array_key_exists('roleId', $user) && array_key_exists('roleName', $user)) {
        $isLogin = true;
        $user = $_SESSION['user'];
    }
}

$cartProducts = [];
$productdao = new ProductDAO();
$subTotalPrice = 0;
$discount = 0;

$isBuyNow = false;
if (isset($_GET['buy-now'])) {
    $isBuyNow = filter_var($_GET['buy-now'], FILTER_VALIDATE_BOOLEAN);
}

$product = null;
$productQuantity = 1;
if ($isBuyNow) {
    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $product = $productdao->selectProductById($_GET['id']);
        if (count($product) > 0) {
            $product = $product[0];

            // chuẩn bị price
            $priceList = $productdao->selectProductPrice($_GET['id']);
            $price = 0;
            if (count($priceList) === 0) {
                $price = 0;
            } else {
                $price = $priceList[0]['price'];
            }
            if(isset($_GET['qty']) && is_numeric($_GET['qty'])) {
                $productQuantity = intval($_GET['qty']);
                if($productQuantity > intval($product['quantity'])) {
                    $productQuantity = intval($product['quantity']);
                }
            }

            $product['price'] = $price;
            $subTotalPrice += $product['price'] * $productQuantity;
        } else {
            echo "<h1>Không có sản phẩm để checkout</h1>";
            die();
        }
    } else {
        echo "<h1>Không có sản phẩm để checkout</h1>";
        die();
    }
} else {
    if ($isLogin === true) {
        $cartdao = new CartDAO();
        $cartItemsFromDB = $cartdao->getCartItems($user['id']);

        foreach ($cartItemsFromDB as $cartItem) {
            $res = $productdao->selectProductById($cartItem['product_id']);

            if (count($res) > 0) {
                $product = $res[0];
                // chuẩn bị price
                $priceList = $productdao->selectProductPrice($cartItem['product_id']);
                $price = 0;
                if (count($priceList) === 0) {
                    $price = 0;
                } else {
                    $price = $priceList[0]['price'];
                }

                $product['price'] = $price;
                $product['cartQuantity'] = $cartItem['quantity'];
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
                $product = $product[0];

                // chuẩn bị price
                $priceList = $productdao->selectProductPrice($key);
                $price = 0;
                if (count($priceList) === 0) {
                    $price = 0;
                } else {
                    $price = $priceList[0]['price'];
                }

                $product['price'] = $price;
                $product['cartQuantity'] = $value;
                array_push($cartProducts, $product);

                $subTotalPrice += $product['price'] * $product['cartQuantity'];
            }
        }
    }
}

$isCartEmpty = count($cartProducts) === 0;
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
                <h2>Checkout</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Cart  -->
<div class="cart-box-main">
    <div class="container">
        <form id="form-order" method="POST" action="payOrder.php">
            <?php
            if($isBuyNow) {
                echo "<input type='hidden' name='buy-now' value='true' >";
                echo "<input type='hidden' name='id' value='{$_GET['id']}' >";
                echo "<input type='hidden' name='qty' value='{$_GET['qty']}' >";
            }
            ?>
            <div class="row">
                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="checkout-address">
                        <div class="title-left">
                            <h3>Thông tin khách hàng</h3>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">Họ đệm *</label>
                                <input type="text" class="form-control" id="firstname" name="firstname" placeholder=""
                                       value="<?php if ($isLogin) echo $user['familyname'] ?>" required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập họ đệm của bạn!')"
                                       oninput="this.setCustomValidity('')">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Tên *</label>
                                <input type="text" class="form-control" id="lastname" name="lastname" placeholder=""
                                       value="<?php if ($isLogin) echo $user['firstname'] ?>" required
                                       oninvalid="this.setCustomValidity('Vui lòng nhập tên của bạn!')"
                                       oninput="this.setCustomValidity('')">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="phone">Điện thoại *</label>
                            <input type="text" class="form-control" id="phone" name="phone" placeholder=""
                                   value="<?php if ($isLogin) echo $user['phone'] ?>" required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập số điện thoại!')"
                                   oninput="this.setCustomValidity('')">
                            <div class="invalid-feedback">.</div>
                        </div>
                        <div class="mb-3">
                            <label for="address">Địa chỉ nhận hàng *</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder=""
                                   value="<?php if ($isLogin) echo $user['address'] ?>" required
                                   oninvalid="this.setCustomValidity('Vui lòng nhập địa chỉ!')"
                                   oninput="this.setCustomValidity('')">
                        </div>
                        <div class="mb-3">
                            <label for="email">Địa chỉ Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?php if ($isLogin) echo $user['email'] ?>" placeholder="">
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-6 mb-3">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="odr-box">
                                <div class="title-left">
                                    <h3>Đơn hàng</h3>
                                </div>
                                <div class="rounded p-2 bg-light">
                                    <?php
                                    if ($isBuyNow) {
                                        $element = "
                                        <div class='media mb-2 border-bottom'>
                                            <div class='media-body'><a href='shop-detail.php?id={$product['id']}'> {$product['name']}</a>
                                                <div class='small text-muted'>Giá: {$product['price']} <span class='mx-2'>|</span> Số lượng: $productQuantity
                                                <span class='mx-2'>|</span> Đơn giá: " . number_format($product['price'] * $productQuantity) . " đ" .
                                            "</div>
                                        </div>
                                    </div>";

                                        echo $element;
                                    } else {
                                        foreach ($cartProducts as $product) {
                                            $element = "
                                        <div class='media mb-2 border-bottom'>
                                            <div class='media-body'><a href='shop-detail.php?id={$product['id']}'> {$product['name']}</a>
                                                <div class='small text-muted'>Giá: {$product['price']} <span class='mx-2'>|</span> Số lượng: {$product['cartQuantity']}
                                                <span class='mx-2'>|</span> Đơn giá: " . number_format($product['price'] * $product['cartQuantity']) . " đ" .
                                                "</div>
                                        </div>
                                    </div>";

                                            echo $element;
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-12">
                            <div class="order-box">
                                <div class="d-flex mt-3">
                                    <div></div>
                                    <div class="ml-auto font-weight-bold">Total</div>
                                </div>
                                <hr class="my-1">
                                <div class="d-flex">
                                    <h4>Đơn giá</h4>
                                    <div class="ml-auto font-weight-bold"> <?php echo number_format($subTotalPrice) . " đ" ?></div>
                                </div>
                                <div class="d-flex">
                                    <h4>Giảm giá</h4>
                                    <div class="ml-auto font-weight-bold"> <?php echo number_format($discount) . " đ" ?> </div>
                                </div>
                                <div class="d-flex">
                                    <h4>Phí ship</h4>
                                    <div class="ml-auto font-weight-bold"> Miễn phí</div>
                                </div>
                                <hr>
                                <div class="d-flex gr-total">
                                    <h5>Tổng tiền</h5>
                                    <div class="ml-auto h5"> <?php echo number_format($totalPrice) . " đ" ?></div>
                                </div>
                                <hr>
                            </div>
                        </div>
                        <div class="col-12 d-flex shopping-box">
                            <input <?php if ($isCartEmpty && !$isBuyNow) echo "disabled" ?> id="proceed-order"
                                                                                            class="text-white ml-auto btn hvr-hover"
                                                                                            type="submit" name="submit"
                                                                                            value="Đặt hàng">
                        </div>
                    </div>
                </div>
        </form>
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
    let inputPhoneElement = document.querySelector('#phone')
    inputPhoneElement.onkeydown = function (evt) {
        var charCode = (evt.which) ? evt.which : evt.keyCode
        if (charCode > 31 && (charCode !== 46 && (charCode < 48 || charCode > 57)) && charCode !== 37 && charCode !== 39 && charCode !== 46)
            return false;
        else if (evt.target.value.length + 1 > 11 && charCode !== 37 && charCode !== 39 && charCode !== 46 && charCode !== 8) return false
        return true
    }
</script>
</body>

</html>