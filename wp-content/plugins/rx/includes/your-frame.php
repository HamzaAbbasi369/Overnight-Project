<?php
/**
 * Created by PhpStorm.
 * User: mkhakhayev
 * Date: 6/26/18
 * Time: 4:54 PM
 */




    add_action('woocommerce_before_add_to_cart_button', function () {
        global $product;
        $product_title = $product->get_title();
        if ($product_title != "Your Frames") {
            return;
        }
        add_filter('woocommerce_before_add_to_cart_button_show_rx', '__return_true', 60);
        add_filter('woocommerce_before_add_to_cart_button_show_fashion', '__return_false', 60);
        add_filter('woocommerce_before_add_to_cart_button_show_cart', '__return_false', 60);
    });