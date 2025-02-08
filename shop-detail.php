<?php
include 'configs/database.php';
include 'configs/config.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php'
?>

<?php
$product = null;
$imageList = array();
$priceList = array();
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('location: ./errors/404.php');
} else {
    $productId = intval($_GET['id']);
    $productdao = new ProductDAO();
    $product = $productdao->selectProductById($productId);
    if (count($product) === 0) {
        header('location: ./errors/404.php');
    } else {
        $product = $product[0];
    }

    $db = new DBHelper();
    $imageList = $db->select("select * from image where product_id = $productId");

    $similarProducts = $productdao->selectSimilarProducts($product['category_id'], $productId);

    $priceListRaw = $productdao->selectProductPrice($product['id']);
    $priceList = array(['price' => null, 'dateStart' => null, 'dateEnd' => null], ['price' => 0, 'dateStart' => null, 'dateEnd' => null]);
    if (count($priceListRaw) === 0) {
        $priceList[0] = ['price' => null, 'dateStart' => null, 'dateEnd' => null];
        $priceList[1] = ['price' => 0, 'dateStart' => null, 'dateEnd' => null];
    } else {
        for($i = count($priceListRaw) - 1; $i >= 0 ; $i--) {
            $price = $priceListRaw[$i];
            if($price['isOriginalPrice'] === 0) {
                $priceList[0] = ['price' => $price['price'], 'dateStart' => $price['dateStart'], 'dateEnd' => $price['dateEnd']];
            } else if($price['isOriginalPrice' === 1]) {
                $priceList[1] = ['price' =>$price['price']];
            }
        }
    }
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
                <h2>Shop Detail</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Shop</a></li>
                    <li class="breadcrumb-item active">Shop Detail</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Detail  -->
<div class="shop-detail-box-main">
    <div class="container">
        <div class="row">
            <div class="col-xl-5 col-lg-5 col-md-6">
                <div id="carousel-example-1" class="single-product-slider carousel slide" data-ride="carousel">
                    <div class="carousel-inner" role="listbox">
                        <?php
                        $count = 0;
                        foreach ($imageList as $image) {
                            $element = "";
                            if ($count === 0) {
                                $element .= '<div class="carousel-item active">';
                            } else {
                                $element .= '<div class="carousel-item">';
                            }
                            $element .= '<img class="d-block w-100" src="' . BASE_URL . '/' . $image['path'] . '">';
                            $element .= '</div>';
                            echo $element;

                            $count++;
                        }
                        //                            ?>
                        <!--                            <div class="carousel-item"> <img class="d-block w-100" src="-->
                        <?php //echo BASE_URL ?><!--/assets/images/big-img-02.jpg" alt="Second slide"> </div>-->
                        <!--                            <div class="carousel-item"> <img class="d-block w-100" src="-->
                        <?php //echo BASE_URL ?><!--/assets/images/big-img-03.jpg" alt="Third slide"> </div>-->
                    </div>
                    <a class="carousel-control-prev" href="#carousel-example-1" role="button" data-slide="prev">
                        <i class="fa fa-angle-left" aria-hidden="true"></i>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carousel-example-1" role="button" data-slide="next">
                        <i class="fa fa-angle-right" aria-hidden="true"></i>
                        <span class="sr-only">Next</span>
                    </a>
                    <ol class="carousel-indicators">
                        <?php
                        $count = 0;
                        foreach ($imageList as $image) {
                            $element = "";
                            if ($count === 0) {
                                $element .= "<li data-target='#carousel-example-1' data-slide-to='$count' class='active'>";
                            } else {
                                $element .= "<li data-target='#carousel-example-1' data-slide-to='$count'>";
                            }
                            $element .= '<img class="d-block w-100 img-fluid" src="' . BASE_URL . '/' . $image['path'] . '"/>';
                            $element .= '</li>';
                            echo $element;
                            $count++;
                        }
                        ?>
                    </ol>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-6">
                <div class="single-product-details">
                    <h2><?php echo $product['name'] ?></h2>
                    <h5>
<!--                        <del class="mr-1">$ 60.00-->
<!--                        </del> -->
                        <?php
                        if ($priceList[0]['price'] !== null) {
                            // old price
                            echo "<del class='mr-2' style='font-size: 13px;
    color: #666;'>" . number_format($priceList[1]['price'], 0, ',', '.') . 'đ' . "</del>";

                            echo number_format($priceList[0]['price'], 0, ',', '.') . "đ";
                        } else {
                            echo number_format($priceList[1]['price'], 0, ',', '.') . "đ";
                        }
                        ?>
                    </h5>
                    <?php
                    if($product['status'] === 0) {
                        echo "<h4 class='text-danger'>Sản phẩm ngừng kinh doanh!</h4>";
                    }
                    ?>
                    
                    <h4>Mô tả sản phẩm:</h4>
                    <p><?php echo $product['description'] ?></p>

                    <p>Tồn kho: <span id="product-quantity"><?php echo $product['quantity'] ?></span></p>
                    <ul>
                        <li>
                            <div class="form-group quantity-box">
                                <label class="control-label">Số lượng:</label>
                                <input id="cart-quantity" class="form-control" value="1" min="1" max="<?php echo $product['quantity'] ?>"
                                       type="number">
                            </div>
                        </li>

                    </ul>

                    <div class="price-box-bar">
                        <div class="cart-and-bay-btn">
                            <a id="buyBtn" class="btn hvr-hover <?php if($product['status'] === 0) echo 'disabled' ?>" data-productid="<?php echo $product['id'] ?>">Mua ngay</a>
                            <a id="addToCartBtn" data-productid="<?php echo $product['id'] ?>" class="btn hvr-hover <?php if($product['status'] === 0) echo 'disabled' ?>" href="#">Thêm vào giỏ hàng</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="row my-5">
            <div class="col-lg-12">
                <div class="title-all text-center">
                    <h1>Sản phẩm tương tự</h1>
                </div>
                <div class="featured-products-box owl-carousel owl-theme">

                    <?php
                    foreach ($similarProducts as $product) {
                        $element = '<div class="item pt-1">';
                        $element .= '<div class="products-single fix"' . "data-productid='{$product['productId']}'" . 'style="box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;">';
                        $element .= '<div class="box-img-hover d-flex align-items-center" style="aspect-ratio: 1/1">';
                        $element .= "<img src='" . BASE_URL . '/' . $product['image'] . "'" . 'class="img-fluid" alt="Image">';
                        $element .= '<div class="mask-icon">';
                        $element .= '<a class="cart" href="#">Add to Cart</a>';
                        $element .= ' </div></div>';
                        $element .= '<div class="why-text">';
                        $element .= "<h4>{$product['name']}</h4>";

                        // thêm price
                        $element .= '<div style="min-height: 54.33px">';


                        $priceListRaw = $productdao->selectProductPrice($product['productId']);
                        $priceList = array(['price' => null, 'dateStart' => null, 'dateEnd' => null], ['price' => 0, 'dateStart' => null, 'dateEnd' => null]);
                        if (count($priceListRaw) === 0) {
                            $priceList[0] = ['price' => null, 'dateStart' => null, 'dateEnd' => null];
                            $priceList[1] = ['price' => 0, 'dateStart' => null, 'dateEnd' => null];
                        } else {
                            for($i = count($priceListRaw) - 1; $i >= 0 ; $i--) {
                                $price = $priceListRaw[$i];
                                if($price['isOriginalPrice'] === 0) {
                                    $priceList[0] = ['price' => $price['price'], 'dateStart' => $price['dateStart'], 'dateEnd' => $price['dateEnd']];
                                } else if($price['isOriginalPrice' === 1]) {
                                    $priceList[1] = ['price' =>$price['price']];
                                }
                            }
                        }

                        if ($priceList[0]['price'] !== null) {
                            // old price
                            $element .= "<del class='mr-2' style='font-size: 13px;
    color: #666;'>" . number_format($priceList[1]['price'], 0, ',', '.') . 'đ' . "</del>";

                            //current price
                            $element .= "<h6>" . number_format($priceList[0]['price'], 0, ',', '.') . " đ" . "</h6>";
                        } else {
                            $element .= "<h6>" . number_format($priceList[1]['price'], 0, ',', '.') . " đ" . "</h6>";
                        }

                        $element .= "</div>";

                        $element .= '</div></div></div>';

                        echo $element;
                    }

                    ?>
                </div>
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
    let addToCartBtn = document.querySelector('#addToCartBtn')
    let cartQuantity = document.querySelector('#cart-quantity') // element chứa số lượng sp thêm vào cart
    let productQuantity = Number(document.querySelector('#product-quantity').textContent) // số lượng sản phẩm tồn kho


    let buyNowBtn = document.getElementById('buyBtn')
    let productId = buyNowBtn.dataset.productid
    buyNowBtn.href = "checkout.php?buy-now=true&id=" + productId + "&qty=" + cartQuantity.value;

    buyNowBtn.onclick = function (e) {
        buyNowBtn.href = "checkout.php?buy-now=true&id=" + productId + "&qty=" + cartQuantity.value;
    }

    cartQuantity.oninput = function(e) {
        let value = Number(e.target.value)
        console.log(value)
        if (value > productQuantity) {
            cartQuantity.value = productQuantity;
        }
        if(value < 1) {
            cartQuantity.value = ''
        }
    }
    cartQuantity.onchange = function(e) {
        let value = Number(e.target.value)
        if(value === 0) e.target.value = 1
    }

    addToCartBtn.onclick = function(e) {
        e.preventDefault()
        if(cartQuantity.value > productQuantity) {
            alert('Vui lòng không mua quá ' + productQuantity + ' sản phẩm!')
            return;
        }

        fetch(`addToCart.php?id=${addToCartBtn.dataset.productid}&quantity=${Number(cartQuantity.value)}`)
        window.location.reload()
    }

</script>
</body>

</html>