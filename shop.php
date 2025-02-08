<?php
include 'utils/common.php';
include 'configs/config.php';
include 'configs/database.php';
include 'databases/DBHelper.php';
include 'databases/ProductDAO.php';
include 'databases/CategoryDAO.php';
include 'databases/BrandDAO.php';
?>

<?php
$sort_by = 0;
if (isset($_GET['sort-by']) && is_numeric($_GET['sort-by'])) {
    $sort_by = intval($_GET['sort-by']);
}

$productList = array();

$productdao = new ProductDAO();
if ($sort_by === 0) {
    $productList = $productdao->selectAll();
} else if ($sort_by === 1) {
    // select latest product
    $productList = $productdao->selectOrderByUpdatedAt();
} else if ($sort_by === 2) {
    // select product order by price from low to high
    $productList = $productdao->selectOrderByLowPrice();
} else if ($sort_by === 3) {
// select product order by price from high to low
    $productList = $productdao->selectOrderByHighPrice();
}

foreach ($productList as &$product) {
    $res = $productdao->selectProductPrice($product['productId']);
    if(count($res) === 0) $product['price'] = 0;
    else {
        $product['price'] = $res[0]['price'];
    }
}
unset($product);


if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
    $productList = array_filter($productList, function($el) {
       return intval($el['category_id']) === intval($_GET['categoryId']);
    });
}
if(isset($_GET['brandId']) && is_numeric($_GET['brandId'])) {
    $productList = array_filter($productList, function($el) {
        return intval($el['brand_id']) === intval($_GET['brandId']);
    });
}

if(isset($_GET['search'])) {
    $productList = array_filter($productList, function($el) {
        return stripos($el['productName'], trim($_GET['search'])) !== false;
    });
}

$categorydao = new CategoryDAO();
$categoryList = $categorydao->selectAll();
$branddao = new BrandDAO();
$brandList = $branddao->selectAll();
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
    <title>Freshshop - Ecommerce Bootstrap 4 HTML Template</title>
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
    <!-- ALL JS FILES -->
    <script src="assets/js/jquery-3.2.1.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</head>

<body style="padding: 0px !important;">
<!-- HEADER -->
<?php include 'includes/header.php' ?>

<!-- Start All Title Box -->
<div class="all-title-box">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Shop</h2>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- End All Title Box -->

<!-- Start Shop Page  -->
<div class="shop-box-inner">
    <div class="container">
        <?php
        if(isset($_GET['search'])) {
            echo "<p class='h4 mb-4'>Tìm kiếm: {$_GET['search']}</p>";
        }
        ?>
        <div class="row">
            <div class="col-xl-9 col-lg-9 col-sm-12 col-xs-12 shop-content-right">
                <div class="right-product-box">
                    <div class="product-item-filter row">
                        <div class="col-12 col-sm-8 text-center text-sm-left">
                            <div class="toolbar-sorter-right" id="sort-by-select">
                                <span>Sort by </span>
                                <select id="basic" class="selectpicker show-tick form-control" data-placeholder="$ USD">
                                    <option <?php echo $sort_by === 0 ? "selected" : "" ?> value="0">Không</option>
                                    <option <?php echo $sort_by === 1 ? "selected" : "" ?> value="1">Mới nhất</option>
                                    <option <?php echo $sort_by === 2 ? "selected" : "" ?> value="2">Giá thấp → Giá
                                        cao
                                    </option>
                                    <option <?php echo $sort_by === 3 ? "selected" : "" ?> value="3">Giá cao → Giá
                                        thấp
                                    </option>
                                </select>
                            </div>
                            <p>Showing all <?php echo count($productList) ?> results</p>
                        </div>

                    </div>

                    <div class="product-categorie-box mt-4">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade show active" id="grid-view">
                                <div class="row">
                                    <?php
                                    foreach ($productList as $product) {
                                        include 'productCard.php';
                                    }
                                    //                                    ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-sm-12 col-xs-12 sidebar-shop-left">
                <div class="product-filter">
                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Danh mục</h3>
                        </div>
                        <div id="filter-category" class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men"
                             data-children=".sub-men">

                            <?php
                            foreach ($categoryList as $category) {
                                $queryString = setUrlSearchQuery($_SERVER['QUERY_STRING'], 'categoryId', $category['id']);

                                $checked = false;
                                if(isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
                                    if(intval($_GET['categoryId']) === intval($category['id'])) {
                                        $checked = true;
                                    }
                                }
                                $amountProduct = $categorydao->getAmountProduct($category['id']);

                                $element = '<div class="form-check d-flex align-items-center">';

                                if($checked) {
                                    $element .= "<input checked class='form-check-input' type='radio' name='filter-category' value='{$category['id']}' id='category-{$category['id']}'>";
                                } else {
                                    $element .= "<input class='form-check-input' type='radio' name='filter-category' value='{$category['id']}' id='category-{$category['id']}'>";
                                }

                                $element .= "<label for='category-{$category['id']}' class='list-group-item list-group-item-action'>";
                                $element .= "{$category['name']} ";
                                $element .= '<small class="text-muted">';

                                $element .= "($amountProduct)";
                                $element .= '</small>';
                                $element .= "</label></div>";
                                echo $element;
                            }
                            ?>
                        </div>
                    </div>

                    <div class="filter-sidebar-left">
                        <div class="title-left">
                            <h3>Thương hiệu</h3>
                        </div>
                        <div id="filter-brand" class="list-group list-group-collapse list-group-sm list-group-tree" id="list-group-men"
                             data-children=".sub-men">

                            <?php
                            foreach ($brandList as $brand) {
                                $queryString = setUrlSearchQuery($_SERVER['QUERY_STRING'], 'brandId', $brand['id']);
                                $amountProduct = $branddao->getAmountProduct($brand['id']);

                                $checked = false;
                                if(isset($_GET['brandId']) && is_numeric($_GET['brandId'])) {
                                    if(intval($_GET['brandId']) === intval($brand['id'])) {
                                        $checked = true;
                                    }
                                }
                                $element = '<div class="form-check d-flex align-items-center">';

                                if($checked) {
                                    $element .= "<input checked class='form-check-input' type='radio' name='filter-brand' value='{$brand['id']}' id='brand-{$brand['id']}'>";
                                } else {
                                    $element .= "<input class='form-check-input' type='radio' name='filter-brand' value='{$brand['id']}' id='brand-{$brand['id']}'>";
                                }

                                $element .= "<label for='brand-{$brand['id']}' class='list-group-item list-group-item-action'>";
                                $element .= "{$brand['name']} ";
                                $element .= '<small class="text-muted">';

                                $element .= "($amountProduct)";
                                $element .= '</small>';
                                $element .= "</label></div>";
                                echo $element;
                            }
                            ?>
                        </div>
                    </div>
                    <div class="filter-price-left">
                        <div class="title-left">
                            <h3>Giá</h3>
                        </div>
                        <div class="price-box-slider">
<!--                            <div id="slider-range"></div>-->
<!--                            <p>-->
<!--                                <input type="text" id="amount" readonly-->
<!--                                       style="border:0; color:#fbb714; font-weight:bold;">-->
<!--                            </p>-->
                            <p>
                                <button id="filter-btn" class="btn hvr-hover">Lọc</button>
                                <button id="clear-filter" class="mr-2 btn hvr-hover">Xoá</button>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Shop Page -->


<!--    FOOTER-->
<?php include 'includes/footer.php' ?>


<a href="#" id="back-to-top" title="Back to top" style="display: none;">&uarr;</a>

<!-- ALL PLUGINS -->
<script src="assets/js/jquery.superslides.min.js"></script>
<script src="assets/js/bootstrap-select.js"></script>
<script src="assets/js/inewsticker.js"></script>
<script src="assets/js/bootsnav.js."></script>
<script src="assets/js/images-loded.min.js"></script>
<script src="assets/js/isotope.min.js"></script>
<script src="assets/js/owl.carousel.min.js"></script>
<script src="assets/js/baguetteBox.min.js"></script>
<script src="assets/js/jquery-ui.js"></script>
<script src="assets/js/jquery.nicescroll.min.js"></script>
<script src="assets/js/form-validator.min.js"></script>
<script src="assets/js/contact-form-script.js"></script>
<script src="assets/js/custom.js"></script>

<!-- script để handle filter sản phẩm -->
<script>
    let formSortBy = document.querySelector('#sort-by-select')
    let selectElement = formSortBy.querySelector('select');
    selectElement.onchange = function (e) {
        e.preventDefault();

        let url = new URL(window.location);
        url.search = '';

        let urlParams = new URLSearchParams(url)
        urlParams.set('sort-by', selectElement.value)
        url = url.toString() + '?' + urlParams.toString()

        window.location.assign(url)
    }

</script>

<!-- script để handle mở trang detail-->
<script>
    let productItemList = document.querySelectorAll('div.products-single.fix');
    for (let productItem of productItemList) {
        productItem.onclick = function (e) {
            e.preventDefault();

            let urlParam = new URLSearchParams()
            urlParam.set('id', productItem.dataset.productid)
            window.location.assign(`shop-detail.php?${urlParam.toString()}`)
        }

        let addToCartBtn = productItem.querySelector('a.cart')
        addToCartBtn.onclick = function (e) {
            e.stopPropagation();
            e.preventDefault();

            fetch(e.target.href)
            window.location.reload();
        }
    }
</script>

<script>
    let filterBtn = document.getElementById('filter-btn');
    let filterCategory = document.getElementById('filter-category')
    let filterBrand = document.getElementById('filter-brand')

    let urlSearchParam = new URL(window.location.href).searchParams;

    filterBtn.onclick = function(e) {
        e.preventDefault();

        let brandId = null;
        let categoryId = null;

        let categorySelectionList = filterCategory.querySelectorAll('input[type="radio"]')
        let brandSelectionList = filterBrand.querySelectorAll('input[type="radio"]')

        for(let categorySelection of categorySelectionList) {
            if(categorySelection.checked) {
                categoryId = categorySelection.value;
            }
        }

        for(let brandSelection of brandSelectionList) {
            if(brandSelection.checked) {
                brandId = brandSelection.value;
            }
        }

        if(brandId) {
            urlSearchParam.set('brandId', brandId)
        }
        if(categoryId) {
            urlSearchParam.set('categoryId', categoryId)
        }

        window.location.assign("<?php echo BASE_URL . '/' . basename($_SERVER['SCRIPT_NAME']) ?>?" + urlSearchParam.toString());
    }

    let clearFilterBtn = document.getElementById('clear-filter');
    clearFilterBtn.onclick = function(e) {
        e.preventDefault()

        let categorySelectionList = filterCategory.querySelectorAll('input[type="radio"]')
        let brandSelectionList = filterBrand.querySelectorAll('input[type="radio"]')

        for(let categorySelection of categorySelectionList) {
            categorySelection.checked = false
        }

        for(let brandSelection of brandSelectionList) {
            if(brandSelection.checked) {
                brandSelection.checked = false
            }
        }

        urlSearchParam.delete('brandId')
        urlSearchParam.delete('categoryId')
        window.location.assign("<?php echo BASE_URL . '/' . basename($_SERVER['SCRIPT_NAME']) ?>?" + urlSearchParam.toString());
    }

    let searchInputItem = document.getElementById('search-input')

    let searchProductForm = document.querySelector('#search-form')
    searchInputItem.oninput = function(e) {
        e.preventDefault();

        let searchValue = e.target.value
        urlSearchParam.set('search', searchValue)
    }

    searchProductForm.onsubmit = function(e) {
        e.preventDefault();
        urlSearchParam.delete('brandId')
        urlSearchParam.delete('categoryId')
        window.location.assign("<?php echo BASE_URL . '/' . basename($_SERVER['SCRIPT_NAME']) ?>?" + urlSearchParam.toString());
    }

</script>
</body>

</html>