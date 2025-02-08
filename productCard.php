<?php
$element = '<div class="col-sm-6 col-md-6 col-lg-4 col-xl-4">';
$element .= '<div class="products-single fix"' . "data-productid='{$product['productId']}'" . 'style="box-shadow: rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px;">';
$element .= '<div class="box-img-hover d-flex align-items-center" style="aspect-ratio: 1/1">';
$element .= "<img src='" . BASE_URL . '/' . $product['image'] . "'" . 'class="img-fluid" alt="Image">';

if(intval($product['status']) === 1) {
    $element .= '<div class="mask-icon">';
    $element .= '<a class="cart" href="addToCart.php?id='. $product['productId']. '">Add to Cart</a>';
    $element .= ' </div>';
} else {
    $element .= "<div style='position: absolute; width: 100%; height: 100%; background-color: rgba(70, 70, 70, 0.8); opacity: 0.5;' class='d-flex justify-content-center align-items-center'>
    <p class='text-white h5'>Ngừng kinh doanh</p>
</div>";
}

$element .= '</div>';
$element .= '<div class="why-text">';
$element .= "<h4>{$product['productName']}</h4>";

// thêm price
$element .= "<div>";

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

// nếu giá mới thấp hơn giá cũ và priceList > 1
if ($priceList[0]['price'] !== null) {
    // old price
    $element .= "<del class='mr-2' style='font-size: 13px;
    color: #666;'>" . number_format($priceList[1]['price'], 0, ',', '.') . 'đ' . "</del>";

    // current price
    $element .= "<h6>" . number_format($priceList[0]['price'], 0, ',', '.') . " đ" . "</h6>";
} else {
    $element .= "<h6>" . number_format($priceList[1]['price'], 0, ',', '.') . " đ" . "</h6>";
}


$element .= "</div>";

$element .= '</div></div></div>';

echo $element;
?>
