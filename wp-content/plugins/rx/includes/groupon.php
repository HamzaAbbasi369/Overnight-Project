<?php

define('GROUPON_NEW_CATEGORY', 'groupon-new');

add_action('template_redirect', function(){

    if(is_admin()) {
        return false;
    }

    /** http://wordpress.local/eyeglasses/?utm_campaign=groupon_value - redirect */
    if (isset($_REQUEST['utm_campaign']) && $_REQUEST['utm_campaign']==='groupon_value') {
        wp_redirect('/product-category/groupon-new/');
        exit;
    }
    /** http://wordpress.local/eyeglasses/?utm_campaign=groupon_value - redirect */

    $is_groupon = null;

    if (is_product()) {
        $is_groupon = !!has_term( GROUPON_NEW_CATEGORY, 'product_cat' );
    }

    if ( $is_groupon !== false ) {

        add_action('woocommerce_before_add_to_cart_button', function(){
                    add_filter('woocommerce_before_add_to_cart_button_show_rx', '__return_true', 60) ;
                    add_filter('woocommerce_before_add_to_cart_button_show_fashion', '__return_false', 60) ;
                    add_filter('woocommerce_before_add_to_cart_button_show_cart', '__return_false', 60);
        });

//        add_action('wp_enqueue_scripts', 'groupon_enqueue_scripts');
//        function groupon_enqueue_scripts () {
//            $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
//            wp_enqueue_script('rx_jscript_groupon', WcRx::get_assets_url() . 'js/rx_jscript_groupon'. $suffix .'.js', ['jquery', 'wp-js-hooks','rx_jscript'], WcRx::VERSION, true);
//        }
    }
});

//add_action('init', 'groupon_init');
//function groupon_init () {
//    remove_action('rx_form_main_section', [WcRx(),'rx_form_main_section_callback']);
//    add_action('rx_form_main_section', 'rx_form_main_section_groupon_callback');
//    function rx_form_main_section_groupon_callback ($d_mode) {
//        include WcRx::get_template('rx_functions_variables');
//        echo '<h1 id="progress-step-name" style="text-align: center; font-size: 15px; margin-bottom: 20px; margin-top: 15px;">PLEASE ENTER PRESCRIPTION</h1>';
//        require WcRx::get_template('rx_core');
//        draw_navbar("nb_step3", "Frame", "rx.navigation.hideRx()", "Add To Cart", "ToCart()");
//    }
//}

