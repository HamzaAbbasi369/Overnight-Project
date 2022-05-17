<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2018 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
add_action('wp_head', 'header_tracking_scripts', 1);
function header_tracking_scripts()
{
    ?>

<!-- Google Analytics -->
<script>
window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;
ga('create', 'UA-112363950-1', 'auto');
ga('send', 'pageview');
</script>
<script async src='https://www.google-analytics.com/analytics.js'></script>
<!-- End Google Analytics -->

    <script>
        //thankyoupage GA
        /*var checkoutUrlDefault = "checkout/order-received",
            siteHostNameDefault = "www.overnightglasses.com",
            //siteHostNameDefault = "local.overnightglasses.com",
            checkoutUrl = window.location.pathname.substring(1, 24),
            checkoutSearch = window.location.search.slice(1).substring(4),
            siteHostName = window.location.hostname;

        function setCookie(c_name, value, exdays) {
            var exdate = new Date();
            // exdate.setDate(exdate.getDate() + exdays);
            exdate.setDate(exdate.getDate() + 360*10);
            var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
            document.cookie = c_name + "=" + c_value;
            console.log(exdate);
        }
        function getCookie(c_name) {
            var i, x, y, ARRcookies = document.cookie.split(";");
            for (i = 0; i < ARRcookies.length; i++) {
                x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
                y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
                x = x.replace(/^\s+|\s+$/g, "");
                if (x === c_name) {
                    return unescape(y);
                }
            }
        }
        function DeleteCookie(name) {
            document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
        }
        function gaSet(){
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                };
                i[r].l = 1 * new Date();
                a = s.createElement(o);
                m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m);
            })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-112363950-1', 'auto');
            // ga('create', 'UA-31337118-41', 'auto');
            ga('send', 'pageview');
        }
        // if(siteHostName === siteHostNameDefault && checkoutUrl === checkoutUrlDefault){
        if(checkoutUrl === checkoutUrlDefault){
            var IsRefresh = getCookie("userKey");
            if (IsRefresh === checkoutSearch && IsRefresh !== "") {
                // DeleteCookie("IsRefresh");
            }
            else {
                setCookie("userKey", checkoutSearch, 1);
                gaSet();
            }
        }else if(checkoutUrl !== checkoutUrlDefault){
            gaSet();
}*/
    </script>
    <?php

}

/***************************Google remarketing script ******************************/

function add_google_script_footer()
{
    //Check if is homepage
    $woo_prefix = '';/* Merchant center prefix for products*/
    global $woocommerce;
    $is_sku = false;    /*If sku is used in merchant center change $is_sku variable to true*/
    if (is_front_page()) {
        $js = /** @lang JavaScript */ <<<JS
        var google_tag_params = {
            ecomm_pagetype: 'home'
        };
JS;
    } // Check if it is a product category page and set the category parameters.
    elseif (is_product_category()) {
        $js = /** @lang JavaScript */ <<<JS
        var google_tag_params = {
            ecomm_pagetype: 'category'
        };
JS;
    } // Check if it a search results page and set the searchresults parameters.
    elseif (is_search()) {
        $js = /** @lang JavaScript */ <<<JS
        var google_tag_params = {
            ecomm_pagetype: 'searchresults'
        };
JS;
    } // Check if it is a product page and set the product parameters.
    elseif (is_product()) {
        $product_id = get_the_ID();
        $product    = wc_get_product($product_id);
        if ($is_sku) {
            $product_id = $woo_prefix . $product->get_sku();
        } else {
            $product_id = $woo_prefix . $product->get_id();
        }
        $price = $product->get_price();
        $js = /** @lang JavaScript */ <<<JS
        var variants = jQuery('form.variations_form.cart[data-product_variations]').attr('data-product_variations');
        var id = new Array(), price = new Array();
        for (var i = 0, v = JSON.parse(variants); i < v.length; i++) {
            id.push(v[i].variation_id);
            price.push(v[i].display_regular_price);
        }
        var google_tag_params = {
            ecomm_prodid: id,
            ecomm_pagetype: 'product',
            ecomm_totalvalue: price
        };
JS;

    } // Check if it is the cart page and set the cart parameters.
    elseif (is_cart()) {
        $cartprods       = $woocommerce->cart->get_cart();
        $cartprods_items = [];
        foreach ((array) $cartprods as $entry) {
            if ($is_sku) {
                $sku = $entry['data']->get_sku();
                //echo "SKU is ".$woo_prefix.$sku;
                array_push($cartprods_items, "'" . $woo_prefix . $sku . "'");
            } else {
                array_push($cartprods_items, "'" . $woo_prefix . $entry['product_id'] . "'");
            }
        }
        $cartprods_items_string = implode(',', $cartprods_items);
        $js = /** @lang JavaScript */ <<<JS
        var google_tag_params = {
            ecomm_prodid: [{$cartprods_items_string}],
            ecomm_pagetype: 'cart',
            ecomm_totalvalue: {$woocommerce->cart->cart_contents_total}
        };
JS;

    } // Check if it the order received page and set the according parameters
    elseif (is_order_received_page()) {

        $order_key   = $_GET['key'];
        $order       = new WC_Order(wc_get_order_id_by_order_key($order_key));
        $order_total = $order->get_total();

        // Only run conversion script if the payment has not failed. (has_status('completed') is too restrictive)
        // And use the order meta to check if the conversion code has already run for this order ID. If yes, don't run it again.
        if (!$order->has_status('failed')) {
            $order_items       = $order->get_items();
            $order_items_array = [];
            foreach ((array) $order_items as $item) {
                if ($is_sku) {
                    $product = new WC_Product($item['product_id']);
                    $sku     = $product->get_sku();
                    array_push($order_items_array, "'" . $woo_prefix . $sku . "'");
                } else {
                    array_push($order_items_array, "'" . $woo_prefix . $item['product_id'] . "'");
                }
            }

            $order_items_array_string = implode(',', $order_items_array);

            $js = /** @lang JavaScript */ <<<JS
            var google_tag_params = {
                ecomm_prodid: [{$order_items_array_string}],
                ecomm_pagetype: 'purchase',
                ecomm_totalvalue: {$order_total}
            };
JS;

        } // end if order status
    } // For all other pages set the parameters for other.
    else {
        $js = /** @lang JavaScript */ <<<JS
        var google_tag_params = {
            ecomm_pagetype: 'other'
        };
JS;
    }

    $js_event = /** @lang JavaScript */
        <<<JS
try {
    ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
} catch (e) {
}
try {
    ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString());
} catch (e) {
}
try {
    ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString());
} catch (e) {
}
ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
    'nonInteraction': 1
});
JS;
//        var_dump(implode([$js, $js_event]));
    wp_add_inline_script( 'custom-js', implode("\r\n",[$js,$js_event]));
//    var_dump(wp_add_inline_script( 'custom-js', $js_event));
}

//add_action('wp_footer', 'add_google_script_footer');


add_action( 'wp_enqueue_scripts', 'add_google_script_footer', 1000 );
/****************************End Google remarketing *******************************/
