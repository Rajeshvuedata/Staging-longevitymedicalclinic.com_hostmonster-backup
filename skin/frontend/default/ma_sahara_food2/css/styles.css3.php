<?php
    header('Content-type: text/css; charset: UTF-8');
    header('Cache-Control: must-revalidate');
    header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 3600) . ' GMT');
    $url = $_REQUEST['url'];
?>
#Example_F {
	-moz-box-shadow: 0 0 5px 5px #888;
	-webkit-box-shadow: 0 0 5px 5px #888;
	box-shadow: 0 0 5px 5px #888;
}
.header .currency-header ul li a {
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    behavior: url(<?php echo $url; ?>css/css3.htc);
}
.header .search-container .form-search.focus,.footer-static-center-one .block-subscribe .block-content.focus  {
    -moz-transition: border 0.3s ease-in-out 0s;
    -webkit-transition: border 0.3s ease-in-out 0s;
    transition: border 0.3s ease-in-out;
}
.drop-lang li a,.drop-currency li a,.header .links a,button.button span span,.top-link .links li a,.drop-currency .sub-currency li a,.block-footer1 .footer-static-content a,.footer address a,.banner-box .text a,.block-tags .block-content .actions a,.block-layered-nav li a,.pt_custommenu div.pt_menu.act .itemMenu .itemMenuName span,.pt_custommenu div.pt_menu.act .itemSubMenu .itemMenuName span,.pt_custommenu div.pt_menu.act a span,.pt_custommenu .itemMenu h4.level1 span,
.pt_custommenu .itemMenu a.level1 span,.pt_custommenu .itemSubMenu h4.level2,.pt_custommenu .itemSubMenu a.level2,.pt_custommenu .itemSubMenu h4.level3,.pt_custommenu .itemSubMenu a.level3,.pt_custommenu .itemMenu a.level1.nochild,
.register-login .login a,.ma-thumbnail-container .bx-wrapper .bx-controls-direction a,.item-blog .box-blog,.tp-bullets .tparrows,.menu-image img,.cus-image img{
    -moz-transition: all 0.3s ease-in-out 0s;
    transition: all 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.drop-lang li a:hover,.product-name a,.drop-currency li a:hover,.header .links a:hover,button.button:hover span span,
.top-link .links li a:hover,.drop-currency .sub-currency li a:hover,.block-footer1 .footer-static-content a:hover,.footer address a:hover,.banner-box .text a:hover,.block-tags .block-content .actions a:hover,.block-layered-nav li a:hover,
.pt_custommenu div.pt_menu.act .itemMenu .itemMenuName:hover span,.pt_custommenu div.pt_menu.act .itemSubMenu .itemMenuName:hover span,.pt_custommenu div.pt_menu .parentMenu a:hover span,
.pt_custommenu div.pt_menu .parentMenu span.block-title:hover,
.pt_custommenu #pt_menu_link ul li a:hover span,
.pt_custommenu div.pt_menu.active .parentMenu a span,.pt_custommenu .itemMenu h4.level1:hover span,
.pt_custommenu .itemMenu a.level1:hover span,.pt_custommenu .itemSubMenu a.level2:hover,
.pt_custommenu .itemMenu a.level1.nochild:hover,.register-login .login a:hover,.ma-thumbnail-container .bx-wrapper .bx-controls-direction a:hover,.item-blog:hover .box-blog,.tp-bullets .tparrows:hover,.menu-image img:hover,.cus-image img:hover {
    -moz-transition: all 0.3s ease 0s;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.custom_banner img,.block-subscribe button.button span,.product-name a:hover{
    -moz-transition: all 0.3s ease-in-out 0s;
    transition: all 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.custom_banner img:hover,.block-subscribe button.button:hover span{
    -moz-transition: all 0.5s ease 0s;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.footer-static-center-one .f-one a {
    -moz-transition: background-color 0.4s ease-in-out 0s;
    -webkit-transition: background-color 0.4s ease-in-out 0s;
    transition: background-color 0.5s ease-in-out;
}
.footer-static-center-one .f-one a:hover {
    -moz-transition: background-color 0.4s ease 0s;
    -webkit-transition: background-color 0.4s ease-in-out 0s;
}
.footer-icon a,.block-tags .block-content a,.pt_custommenu div.pt_menu.act a, .pt_custommenu div.pt_menu.active .parentMenu a,.pager .view-mode strong.grid, .pager .view-mode a.grid:hover
,.pager .view-mode a.list,.pager .view-mode strong.list, .pager .view-mode a.list:hover,.pager .view-mode a.grid,.bx-wrapper .bx-controls a,
.banner-static-contain .banner-link a,.banner-one2 .banner-one-link a,.banner-left .link-banner-left a,.nivo-controlNav a
,.product-view .link-wishlist,.product-view .link-compare,
.product-view .email-friend a,.top-cart-wrapper .btn-edit,.top-cart-wrapper .btn-remove,button.button span,
#back-top,.register-login .login a,.register-login .register a,.top-cart-wrapper .btn-remove,.top-cart-wrapper .btn-edit,
.banner-static-contain .box-images a,.banner-static-contain .banner-box .box-images a,
.banner-static-contain .banner-box .box-title a,.ma-timer-container  .bx-wrapper .bx-controls-direction a,
.link-wishlist,.block-footer .f-icon a,.banner-box .text,.banner-left .banner-left-text,.pager .pages a,.tp-button{
    -moz-transition: all 0.3s ease;
    transition: all 0.3s ease;
    -webkit-transition: all 0.3s ease;
}
.footer-icon a:hover,.block-tags .block-content a:hover,.pager .view-mode strong.grid, .pager .view-mode a.grid:hover
,.pager .view-mode a.list,.pager .view-mode strong.list, .pager .view-mode a.list:hover,.pager .view-mode a.grid
,.bx-wrapper .bx-controls a:hover,.banner-static-contain .banner-link a:hover,.banner-one2 .banner-one-link a:hover,.banner-left .link-banner-left a:hover,
.nivo-controlNav a:hover,.product-view .link-wishlist:hover,
.product-view .link-compare:hover,.product-view .email-friend a:hover,.top-cart-wrapper .btn-edit:hover,.top-cart-wrapper .btn-remove:hover,button.button:hover span,#back-top:hover,.register-login .login a:hover,.register-login .register a:hover,
.top-cart-wrapper .btn-remove:hover,.top-cart-wrapper .btn-edit:hover,.banner-static-contain .banner-box:hover .box-images a,
.banner-static-contain .banner-box:hover .box-title a,.ma-timer-container .bx-wrapper .bx-controls-direction a:hover,
.link-wishlist:hover,.block-footer .f-icon a:hover,.banner-box:hover .text,.banner-left:hover .banner-left-text,.pager .pages a:hover,.tp-button.lightgrey:hover{
     -moz-transition: all 0.3s ease;
     -webkit-transition: all 0.3s ease;
}
.products-list button.btn-cart span,.products-list .link-wishlist,.products-list .link-compare {
	-moz-transition: background 0s ease-in-out 0s;
    transition: background 0s ease-in-out;
    -webkit-transition: background 0s ease-in-out 0s;
}
.products-list button.btn-cart:hover span,.products-list .link-wishlist:hover,.products-list .link-compare:hover {
	-moz-transition: background 0s ease 0s;
    -webkit-transition: background 0s ease-in-out 0s;
}
.header .search-container button.button span {
    transition:background 0s ease-in-out 0s;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.footer-static-container ul li a,.products-list .desc .link-learn {
    -moz-transition: color 0.3s ease-in-out 0s;
    transition: color 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.footer-static-container ul li a:hover,.products-list .desc .link-learn:hover {
    transition:color 0.3s ease-in-out 0s;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.tab_container .item-inner .ratings,.category-products .products-grid .item-inner .ratings{
    -moz-transition: top 0.5s ease-in-out 0s;
    transition: top 0.5s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.tab_container .item-inner:hover .ratings,.category-products .products-grid .item-inner:hover .ratings{
     -moz-transition: top 0.5s ease 0s;
     -webkit-transition: all 0.3s ease-in-out 0s;
}

.tab_container .item-inner .add-to-links,.category-products .item-inner .cart-content{
    -moz-transition: right 0.5s ease-in-out 0s;
    transition: right 0.5s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.tab_container .item-inner:hover .add-to-links,.category-products .item-inner:hover .cart-content{
     -moz-transition: right 0.5s ease 0s;
     -webkit-transition: all 0.3s ease-in-out 0s;
}

.item-inner .box-link{
    -moz-transition: bottom 0.3s ease-in-out 0s;
    transition: bottom 0.5s ease-in-out;
    -webkit-transition: bottom 0.5s ease-in-out 0s;
}
.item-inner:hover .box-link{
     -moz-transition: bottom 0.5s ease 0s;
     -webkit-transition: bottom 0.5s ease-in-out 0s;
}

.block-footer .f-icon span:before,
.block-footer .f-icon span:after,.block-footer .f-icon span{
    -moz-transition: all 0.3s ease-in-out 0s;
    transition: all 0.3s ease-in-out;
    -webkit-transition: all 0.3s ease-in-out 0s;
}
.block-footer .f-icon a:hover span{
     -moz-transition: all 0.3s ease 0s;
     -webkit-transition: all 0.3s ease-in-out 0s;
}

.tab_container .bx-wrapper .bx-controls a,.ma-brand-slider-contain .bx-wrapper .bx-controls a,.ma-thumbnail-container .bx-wrapper .bx-controls-direction a,
.ma-upsellslider-container .bx-wrapper .bx-controls a,.drop-currency .currency-trigger .sub-currency{
    -moz-transition: opacity 0.3s ease-in-out 0s;
    transition: opacity 0.3s ease-in-out;
    -webkit-transition: opacity 0.3s ease-in-out 0s;
}
.tab_container:hover .bx-wrapper .bx-controls a,.ma-brand-slider-contain:hover .bx-wrapper .bx-controls a,.ma-thumbnail-container .bx-wrapper:hover .bx-controls-direction a,
.ma-upsellslider-container .bx-wrapper:hover .bx-controls a,.drop-currency .currency-trigger:hover .sub-currency{
     -moz-transition: opacity 0.3s ease 0s;
     -webkit-transition: opacity 0.3s ease-in-out 0s;
}
.pt_custommenu div.pt_menu .parentMenu a span, .pt_custommenu div.pt_menu .parentMenu span.block-title {
    -moz-box-shadow:
        inset 5px 0 5px -5px #fff,
        inset -5px 0 5px -5px #fff;
    -webkit-box-shadow:
        inset 5px 0 5px -5px #fff,
        inset -5px 0 5px -5px #fff;
    box-shadow:
        inset 5px 0 5px -5px #fff,
        inset -5px 0 5px -5px #fff;
}
.pt_custommenu div.pt_menu.act a span,.pt_custommenu div.pt_menu.act .itemSubMenu .itemMenuName:hover span,
.pt_custommenu div.pt_menu .parentMenu a:hover span,
.pt_custommenu div.pt_menu .parentMenu span.block-title:hover,
.pt_custommenu #pt_menu_link ul li a:hover span,
.pt_custommenu div.pt_menu.active .parentMenu a span {
    -moz-box-shadow:
        inset 5px 0 5px -5px #f6f6f6,
        inset -5px 0 5px -5px #f6f6f6;
    -webkit-box-shadow:
        inset 5px 0 5px -5px #f6f6f6,
        inset -5px 0 5px -5px #f6f6f6;
    box-shadow: 7px 0 5px -5px #f6f6f6 inset, -6px 0 5px -3px #f6f6f6 inset;
    background:rgba(0, 0, 0,0.01);
}

.header-container .form-search input.input-text {
	-moz-transition: width 0.3s ease-in-out 0s;
    transition: width 0.3s ease-in-out;
    -webkit-transition: width 0.3s ease-in-out 0s;
}
.header-container .form-search:hover input.input-text {
	-moz-transition: width 0.3s ease 0s;
    -webkit-transition: width 0.3s ease-in-out 0s;
}
