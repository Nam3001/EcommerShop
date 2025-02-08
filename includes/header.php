<?php
if(!class_exists('CartDAO')) {
    include 'databases/CartDAO.php';
}

if(session_status() == PHP_SESSION_NONE) {
    session_start();
}


$isLogin = false;
if(isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    if($user['roleId'] === 2 && $user['roleName'] === 'customer') {
        $isLogin = true;
    }
}

$cartQuantity = 0;

if($isLogin) {
    try {
        $cartdao = new CartDAO();
        $cartQuantity = count($cartdao->getCartItems($_SESSION['user']['id']));
    } catch (Exception $e) {
        $cartQuantity = 0;
    }
} else {
    if(isset($_SESSION['cart'])) {
        $cartQuantity = count($_SESSION['cart']);
    }
}
?>

<!-- Start Main Top -->
<div class="main-top">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="right-phone-box">
                    <p>Call :- <a href="#"> +84 0399840032</a></p>
                </div>
                <div class="our-link">
                    <ul>
                        <li><a href="#"><i class="fa fa-user s_color"></i>
                            <?php
                            if($isLogin) {
                                echo $_SESSION['user']['familyname'] . " " . $_SESSION['user']['firstname'];
                            }
                            ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex justify-content-between col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="text-slid-box">
                    <div id="offer-box" class="carouselTicker">
                        <ul class="offer-box">
                            <li>
                                <i class="fab fa-opencart"></i> 50% - 80% off on Electronics
                            </li>
                            <li>
                                <i class="fab fa-opencart"></i> Off 10%! Shop Electronics
                            </li>
                            <li>
                                <i class="fab fa-opencart"></i> Off 50%! Shop Now
                            </li>
                            <li>
                                <i class="fab fa-opencart"></i> Off 10%! Shop Electronics
                            </li>
                            <li>
                                <i class="fab fa-opencart"></i> 50% - 80% off on Electronics
                            </li>
                            <li>
                                <i class="fab fa-opencart"></i> Off 50%! Shop Now
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="d-flex">
                    <?php
                    if(!$isLogin) {
                        echo '<a href="' . BASE_URL . '/register.php"' . ' class="btn hvr-hover text-white mr-2">Đăng kí</a>';
                        echo '<a href="' . BASE_URL . '/login.php"' . ' class="btn hvr-hover text-white">Đăng nhập</a>';
                    } else {
                        echo '<button type="button" class="btn hvr-hover text-white" data-toggle="modal" data-target="#logoutModal">Đăng xuất</button>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Main Top -->

<!-- Start Main Top -->
<header class="main-header">
    <!-- Start Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-default bootsnav">
        <div class="container">
            <!--             Start Header Navigation-->
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-menu"
                        aria-controls="navbars-rs-food" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <a class="navbar-brand" href="index.php"><img style="width: 150px;"
                                                              src="<?php echo BASE_URL ?>/assets/images/logo-electronic.png"
                                                              class="logo" alt=""></a>
            </div>
            <!--             End Header Navigation-->

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-menu">
                <ul class="nav navbar-nav ml-auto" data-in="fadeInDown" data-out="fadeOutUp">
                    <li class="nav-item active"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About Us</a></li>
                    <li class="dropdown">
                        <a href="#" class="nav-link dropdown-toggle arrow" data-toggle="dropdown">SHOP</a>
                        <ul class="dropdown-menu">
                            <li><a href="shop.php">Sidebar Shop</a></li>
                            <li><a href="shop-detail.php">Shop Detail</a></li>
                            <li><a href="cart.php">Cart</a></li>
                            <li><a href="checkout.php">Checkout</a></li>
                            <li><a href="my-account.php">My Account</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <!--             /.navbar-collapse-->

            <!--             Start Atribute Navigation-->
            <div class="attr-nav">
                <ul>
                    <li class="search"><a href="#"><i class="fa fa-search"></i></a></li>
                    <li>
                        <a class="d-flex align-items-center" href="cart.php">
                            <i class="fa fa-shopping-bag"></i>
                            <span class="badge"><?php echo $cartQuantity ?></span>
                            <p class="ml-1">My Cart</p>
                        </a>
                    </li>
                </ul>
            </div>
            <!--             End Atribute Navigation-->
        </div>

        <!-- SIDE MENU-->
        <!--        --><?php //include 'side_menu.php' ?>
    </nav>
    <!-- End Navigation -->
</header>
<!-- End Main Top -->

<!-- Start Top Search -->
<div class="top-search">
    <div class="container">
        <form id="search-form" class="input-group">
            <span class="input-group-addon"><i class="fa fa-search"></i></span>
            <input id="search-input" type="text" class="form-control" placeholder="Search">
            <span class="input-group-addon close-search"><i class="fa fa-times"></i></span>
        </form>
    </div>
</div>
<!-- End Top Search -->

<!-- Logout Modal -->
<div class="modal fade" id="logoutModal" style="padding: 0px!important;" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Xác nhận</h1>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn đăng xuất không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                <a href="logout.php" class="btn btn-danger">Có</a>
            </div>
        </div>
    </div>
</div>
