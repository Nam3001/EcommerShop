<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="<?php echo BASE_URL ?>/admin/index.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Bảng điều khiển
                </a>
                <div class="sb-sidenav-menu-heading">Các chức năng</div>
<!--                hoá đơn -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseOrders" aria-expanded="false" aria-controls="collapseOrders">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Đơn hàng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseOrders" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/orders/index.php"">Danh sách</a>
<!--                        <a class="nav-link" href="layout-sidenav-light.html">Thêm hoá đơn</a>-->
                    </nav>
                </div>
<!--                nhập hàng -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseInputProduct" aria-expanded="false" aria-controls="collapseInputProduct">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Nhập hàng
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseInputProduct" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/receipts/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/receipts/insertReceipt.php">Nhập thêm hàng</a>
                    </nav>
                </div>

<!--                sản phẩm-->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseProduct" aria-expanded="false" aria-controls="collapseProduct">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Sản phẩm
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseProduct" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/products/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/products/insertProduct.php">Thêm sản phẩm</a>
                    </nav>
                </div>
<!--                danh mục -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCategory" aria-expanded="false" aria-controls="collapseCategory">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Danh mục
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseCategory" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/categories/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/categories/insertCategory.php">Thêm danh mục</a>
                    </nav>
                </div>

<!--                thương hiệu -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBrand" aria-expanded="false" aria-controls="collapseBrand">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Thương hiệu
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseBrand" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/brands/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/brands/insertBrand.php">Thêm thương hiệu</a>
                    </nav>
                </div>

                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePrice" aria-expanded="false" aria-controls="collapsePrice">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Thiết lập giá
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapsePrice" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/prices/index.php">Thiết lập giá bán</a>
                    </nav>
                </div>

<!--                đơn vị -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUnit" aria-expanded="false" aria-controls="collapseUnit">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Đơn vị
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseUnit" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/units/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/units/insertUnit.php">Thêm đơn vị</a>
                    </nav>
                </div>

<!--                nhà cung cấp -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseSupplier" aria-expanded="false" aria-controls="collapseSupplier">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Nhà cung cấp
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseSupplier" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/suppliers/index.php">Danh sách</a>
                        <a class="nav-link" href="<?php echo BASE_URL ?>/admin/suppliers/insertSupplier.php">Thêm nhà cung cấp</a>
                    </nav>
                </div>

<!--                combo -->
<!--                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCombo" aria-expanded="false" aria-controls="collapseCombo">-->
<!--                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>-->
<!--                    Combo-->
<!--                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>-->
<!--                </a>-->
<!--                <div class="collapse" id="collapseCombo" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">-->
<!--                    <nav class="sb-sidenav-menu-nested nav">-->
<!--                        <a class="nav-link" href="--><?php //echo BASE_URL ?><!--/admin/combo/index.php">Danh sách</a>-->
<!--                        <a class="nav-link" href="--><?php //echo BASE_URL ?><!--/admin/combo/insertCombo.php">Thêm combo</a>-->
<!--                    </nav>-->
<!--                </div>-->
<!--               coupon -->
<!--                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseCoupon" aria-expanded="false" aria-controls="collapseCoupon">-->
<!--                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>-->
<!--                    Mã giảm giá-->
<!--                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>-->
<!--                </a>-->
<!--                <div class="collapse" id="collapseCoupon" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">-->
<!--                    <nav class="sb-sidenav-menu-nested nav">-->
<!--                        <a class="nav-link" href="--><?php //echo BASE_URL ?><!--/admin/coupons/index.php">Danh sách</a>-->
<!--                        <a class="nav-link" href="--><?php //echo BASE_URL ?><!--/admin/coupons/insertCoupon.php">Thêm coupon</a>-->
<!--                    </nav>-->
<!--                </div>-->

<!--                nhập hàng -->

<!--                tài khoản -->
<!--                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="false" aria-controls="collapseAccount">-->
<!--                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>-->
<!--                    Tài khoản-->
<!--                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>-->
<!--                </a>-->
<!--                <div class="collapse" id="collapseAccount" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">-->
<!--                    <nav class="sb-sidenav-menu-nested nav">-->
<!--                        <a class="nav-link" href="layout-static.html">Danh sách phiếu</a>-->
<!--                        <a class="nav-link" href="layout-sidenav-light.html">Thêm tài khoản</a>-->
<!--                    </nav>-->
<!--                </div>-->
            </div>
        </div>
    </nav>
</div>