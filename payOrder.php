<?php
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CartDAO.php';
include 'databases/OrderDAO.php';

if(!isset($_POST['submit'])) {
    die();
}


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




$productdao = new ProductDAO();
$discount = 0;

$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$email = strlen($_POST['email']) > 0 ? $_POST['email'] : NULL;

if($email !== NULL) {
    $isEmail = preg_match("/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/", $email);
    if(!$isEmail) {
        $email = NULL;

    }
}

$isSuccess = false;
$orderdao = new OrderDAO();
$orderdao->insertOrder($firstname, $lastname, $phone, $address, $email);
$latestOrderId = $orderdao->db->lastInsertId();

if(isset($_POST['buy-now']) && filter_var($_POST['buy-now'], FILTER_VALIDATE_BOOLEAN)) {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $product = $productdao->selectProductById($_POST['id']);
        if (count($product) > 0) {
            $product = $product[0];
            // chuẩn bị price
            $priceList = $productdao->selectProductPrice($_POST['id']);
            $price = 0;
            if (count($priceList) === 0) {
                $price = 0;
            } else {
                $price = $priceList[0]['price'];
            }
            $product['price'] = $price;
            if(isset($_POST['qty']) && is_numeric($_POST['qty'])) {
                $productQuantity = intval($_POST['qty']);
                if($productQuantity <= intval($product['quantity'])) {
                    $res = $orderdao->insertOrderDetail($latestOrderId, $product['id'], $productQuantity, $product['price']);
                    if($res) {
                        $isSuccess = true;
                    }
                }
            }
        }
    }
} else {
    if ($isLogin === true) {
        try {
            $cartdao = new CartDAO();
            $cartItemsFromDB = $cartdao->getCartItems($user['id']);

            $isBreak = false;
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

                    if(intval($cartItem['quantity']) > intval($product['quantity'])) {
                        $isBreak = true;
                        break;
                    }
                    $res = $orderdao->insertOrderDetail($latestOrderId, $product['id'], intval($cartItem['quantity']), $product['price']);

                    if(!$res) {
                        $isBreak = true;
                        break;
                    }
                }
            }

            $cartdao->clearCart($user['id']);

            if(count($cartItemsFromDB) > 0 && !$isBreak)
                $isSuccess = true;
        } catch(Exception $e) {
            $isSuccess = false;
        }
    } else {
        try {
            $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

            $isBreak = false;
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
                    if(intval($value) > intval($product['quantity'])) {
                        $isBreak = true;
                        break;
                    }
                    $res = $orderdao->insertOrderDetail($latestOrderId, $product['id'], intval($value), $product['price']);

                    if(!$res) {
                        $isBreak = true;
                        break;
                    }
                }
            }
            unset($_SESSION['cart']);

            // kiểm tra nếu có sản phẩm trong giỏ hàng
            if(count($cart) > 0 && !$isBreak) {
                $isSuccess = true;
            }
        } catch (Exception $e) {
            $isSuccess = false;
        }
    }
}

if(!$isSuccess) {
    header('location: errors/500.php');
}
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
        <h3 class="font-weight-bold text-center">Cám ơn bạn đã đặt hàng tại cửa hàng của chúng tôi</h3>
        <h3 class="font-weight-bold text-center">Chúng tôi sẽ sớm liên lạc với bạn để chốt đơn hàng!</h3>
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