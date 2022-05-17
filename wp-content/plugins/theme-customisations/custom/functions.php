<?php
/**
 * Functions.php
 *
 * @package  Theme_Customisations
 * @author   WooThemes
 * @since    1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
require_once(dirname(__FILE__) . '/autoloader.php');
add_action('after_setup_theme', 'woocommerce_support');
function woocommerce_support()
{
    add_theme_support('woocommerce');
}

if (!defined('ACF_LITE')) {
    /*
     * This will hide Advanced Custom Fields plugin from Admin Panel
     */
    //define('ACF_LITE', false);
}

if (!defined('ADDITIONAL_INFORMATION_ATTRIBUTES_LIST')) {
    /**
     *  Additional Information
     */
    define('ADDITIONAL_INFORMATION_ATTRIBUTES_LIST', [
        'pa_brands',
//        'pa_size',
//        'pa_lens-width',
//        'pa_lens-height',
//        'pa_color-code',
        'pa_frame-style',
    ]);
}

/**
 * Remove the sorting dropdown and result count from Woocommerce
 */

remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

if ( defined( 'WP_CLI' ) && WP_CLI ) {
    include_once( dirname(__FILE__) . '/addons/commands/commands.php' );
}
/**
 * functions.php
 * Add PHP snippets here
 */
require_once(dirname(__FILE__) . '/addons/ssi-includes.php');
require_once(dirname(__FILE__) . '/addons/ong-coupons/ong-coupons.php');
require_once(dirname(__FILE__) . '/addons/ong-subscriber/subscriber.php');
require_once(dirname(__FILE__) . '/addons/product_slider.php');
//require_once(dirname(__FILE__) . '/addons/color-variation.php');
//require_once(dirname(__FILE__) . '/addons/size_variation.php');
require_once(dirname(__FILE__) . '/addons/variation-swatches-for-woocommerce/variation-swatches-for-woocommerce.php');
require_once(dirname(__FILE__) . '/addons/smart-variations-images-pro/svipro.php');
require_once(dirname(__FILE__) . '/addons/virtual-mirror/virtual_mirror.php');
require_once(dirname(__FILE__) . '/addons/variable-color-category.php');
//require_once(dirname(__FILE__) . '/addons/change_to_front_photo.php'); //deprecated
require_once(dirname(__FILE__) . '/addons/set-woocommerce-image-dimensions-upon-theme-activation.php');
require_once(dirname(__FILE__) . '/addons/allow-svg-file-type.php');
require_once(dirname(__FILE__) . '/addons/change_position_of_amazon_checkout_button.php');
require_once(dirname(__FILE__) . '/addons/rush_services.php');
require_once(dirname(__FILE__) . '/addons/sort_payment_gateways.php');
require_once(dirname(__FILE__) . '/addons/import_woo.php');
if ( empty( $_GET['amazon_payments_advanced'] ) || !empty( $_GET['amazon_logout'] )) {
    require_once(dirname(__FILE__) . '/addons/ong_checkout_addon.php');
}
require_once(dirname(__FILE__) . '/addons/ong_wc_dropdown_variation_attribute_options.php');
require_once(dirname(__FILE__) . '/addons/theme_uri_shortcode.php');
require_once(dirname(__FILE__) . '/addons/woocommerce_return_to_shop_redirect.php');
require_once(dirname(__FILE__) . '/addons/remove_woothemes_updater_notice.php');
if (!empty($_SERVER['HTTP_HOST']) && $_SERVER['HTTP_HOST']==='www.overnightglasses.com'){
    require_once(dirname(__FILE__) . '/addons/tracking_scripts/tracking_scripts.php');
}
require_once(dirname(__FILE__) . '/addons/carbon-fields/category-banners.php');
require_once(dirname(__FILE__) . '/addons/top-menu-cart-link.php');
require_once(dirname(__FILE__) . '/addons/user-fit/user-fit.php');
require_once(dirname(__FILE__) . '/addons/yotpo-integration.php');  //v1.26
require_once(dirname(__FILE__) . '/addons/product_lines.php');  //v1.27
require_once(dirname(__FILE__) . '/addons/carbon-fields/popup_for_page.php');
require_once(dirname(__FILE__) . '/addons/related_product.php');
require_once(dirname(__FILE__) . '/addons/best-price.php');
require_once(dirname(__FILE__) . '/addons/carbon-fields/ong_club.php');

require_once(dirname(__FILE__) . '/addons/carbon-fields/ong_email_retargeting.php');
require_once(dirname(__FILE__) . '/addons/retargeting/ongRetargeting.php');

require_once(dirname(__FILE__) . '/addons/reports/custom_reports_rx.php');
require_once(dirname(__FILE__) . '/addons/separate_values_item.php');

require_once(dirname(__FILE__) . '/addons/shipping_label.php');
require_once(dirname(__FILE__) . '/addons/single_product_image.php');
require_once(dirname(__FILE__) . '/addons/content_product.php');
require_once(dirname(__FILE__) . '/addons/archive_product.php');
require_once(dirname(__FILE__) . '/addons/add_variation_size.php');
require_once(dirname(__FILE__) . '/addons/product_schema_reviews.php');


if (get_option('ong_club_show') === 'yes') {
    require_once(dirname(__FILE__) . '/addons/club/ongClub.php'); //v1.31
}
include_once(dirname(__FILE__) . '/addons/product/product.php' );
//include_once(dirname(__FILE__) . '/addons/remove-query-string-from-static-files.php' );

// CALIFORNIA DISCOUNT 
//include_once(dirname(__FILE__) . '/addons/apply_discount_california.php' );

add_action('ong_woocommerce_checkout_order_review', 'ong_woocommerce_order_review', 50);

// fix for shipping and billing
//add_filter('woocommerce_checkout_update_customer_data', '__return_false' );

add_shortcode('theme_custom_uri', 'theme_custom_uri_shortcode_custom');
/*
	 * Them Customization: create shortcode
	 * */
function theme_custom_uri_shortcode_custom()
{
	$theme_custom_uri = THEME_CUSTOMIZATION_URL;
	return trailingslashit($theme_custom_uri);
}

if (!function_exists('ong_woocommerce_order_review')) {
    function ong_woocommerce_order_review($deprecated = false)
    {
        wc_get_template('checkout/review-order.php', ['checkout' => WC()->checkout()]);
    }
}

add_filter('woocommerce_get_shop_page_permalink', function($link){
    $the_slug = 'all-glasses';
    $args = array(
        'name'        => $the_slug,
        'post_type'   => 'page',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    if (($my_posts = get_posts($args)) && ($new_link = get_permalink( $my_posts[0]->ID ))){
        $link = $new_link;
    }
    return $link;
});

add_filter('woocommerce_order_items_meta_display', function ($output, WC_Order_Item_Meta $item_Meta) {
    if (strpos($output, '<dt class="variation-')!==false) {
        $formatted_meta = $item_Meta->get_formatted('_');

        if (!empty($formatted_meta)) {
            $meta_list = array();

            foreach ($formatted_meta as $meta) {
                $meta_list[] = '
                        <div class="dt variation-' . sanitize_html_class(sanitize_text_field($meta['key'])) . '">' .
                    wp_kses_post($meta['label']) . ':</div>
                        <div class="dd variation-' . sanitize_html_class(sanitize_text_field($meta['key'])) . '">' .
                    wp_kses_post(wpautop(make_clickable($meta['value']))) . '</div>
                    ';
            }

            if (!empty($meta_list)) {
                $output = '';
                $output .= '<div class="variation">' . implode('', $meta_list) . '</div>';
            }
        }
    }

    return $output;
}, 10, 2);

require_once(dirname(__FILE__) . '/includes/carbon-fields.php');
require_once(dirname(__FILE__) . '/addons/email-new-order.php');


/**
 * @return array
 * @deprecated
 */
function sslPrm()
{
    $iv = '';
    return ["5ae1b8a17bad4da4fdac796f64c16ecd", $iv, "aes-128-cbc"];
}

/**
 * @param $msg
 *
 * @return string
 * @deprecated
 */
function sslEnc($msg)
{
    list ($pass, $iv, $method) = sslPrm();
    if (function_exists('openssl_encrypt')) {
        return urlencode(@openssl_encrypt(urlencode($msg), $method, $pass, false, $iv));
    } else {
        return urlencode(exec("echo \"" . urlencode($msg) . "\" | openssl enc -" . urlencode($method)
            . " -base64 -nosalt -K " . bin2hex($pass) . " -iv " . bin2hex($iv)));
    }
}

/**
 * @param $msg
 *
 * @return string
 * @deprecated
 */
function sslDec($msg)
{
    list ($pass, $iv, $method) = sslPrm();
    if (function_exists('openssl_decrypt')) {
        return trim(urldecode(@openssl_decrypt(urldecode($msg), $method, $pass, false, $iv)));
    } else {
        return trim(urldecode(exec("echo \"" . urldecode($msg) . "\" | openssl enc -" . $method
            . " -d -base64 -nosalt -K " . bin2hex($pass) . " -iv " . bin2hex($iv))));
    }
}

/**
 * This action is a work-around for Create New Account issue (https://trello.com/c/YxLbFHEi)
 * The issue happens for non-logged id users which entered customer details (including email)
 * and checkbox "createaccount" is checked
 * In such case an user  sees en error message so bump into bed user experience
 */
add_action( 'woocommerce_checkout_process', function() {
    if ( ! isset( $_POST['woocommerce_checkout_update_totals'] ) && wc_notice_count( 'error' ) == 0 ) {
        if (!is_user_logged_in() && (WC()->checkout()->is_registration_required() || !empty($_POST['createaccount']))) {
            if (email_exists($_POST['billing_email'])) {
                $_POST['createaccount'] = 0;
            }
        }
    }
});

remove_action( 'woocommerce_thankyou','woocommerce_order_details_table',  10 );

/**
 * @param $item
 * @param array $args
 * @return mixed|string|void
 */
function wc_display_item_meta( $item, $args = array() ) {
    $strings = array();
    $html    = '';
    $args    = wp_parse_args( $args, array(
        'before'    => '<div class="wc-item-meta"><div class="flex-block">',
        'after'     => '</div></div>',
        'separator' => '</div><div>',
        'echo'      => true,
        'autop'     => false,
    ) );

    foreach ( $item->get_formatted_meta_data() as $meta_id => $meta ) {
        $value     = $args['autop'] ? wp_kses_post( $meta->display_value ) : wp_kses_post( make_clickable( trim( $meta->display_value ) ) );
        $strings[] = '<strong class="wc-item-meta-label">' . wp_kses_post( $meta->display_key ) . ':</strong> ' . $value;
    }

    if ( $strings ) {
        $html = $args['before'] . implode( $args['separator'], $strings ) . $args['after'];
    }

    $html = apply_filters( 'woocommerce_display_item_meta', $html, $item, $args );

    if ( $args['echo'] ) {
        echo $html; // WPCS: XSS ok.
    } else {
        return $html;
    }
}
add_filter( 'woocommerce_product_tabs', 'remove_for_your_frames', 98 );

function remove_for_your_frames( $tabs ) {
    if (!is_product()) {
        return $tabs;
    }
    global $product;
    $product_title = $product->get_title();
    if ($product_title != "Your Frames") {
        return $tabs;
    }
    unset( $tabs['additional_information'] );
    return $tabs;
}


function move_checkout_button(){
?>
<script>
(function($){
    $(window).load(function() {
	console.log("cart change");
	$('#woo_pp_ec_button img').attr('src', '/content/themes/theme/assets/img/paypal_pay_button.png');
	$(".wcppec-checkout-buttons__separator").html("Fast checkout with");
	$(".wcppec-checkout-buttons__separator").addClass("line_separator");
	$("#pay_with_amazon").appendTo($(".wc-proceed"));
   $("#pay_with_amazon").removeAttr("data-treatment");
   $(".amazonpay-button-inner-image").removeAttr("src", "https://d2ldlvi1yef00y.cloudfront.net/us/live/apa/orange/medium/button_T15.png");
   $(".amazonpay-button-inner-image").removeAttr("srcset", "https://d2ldlvi1yef00y.cloudfront.net/us/live/en_us/amazonpay/gold/medium/button_T6_2x.png");
	$(".amazonpay-button-inner-image").removeAttr("srcset", "https://d2ldlvi1yef00y.cloudfront.net/us/live/en_us/amazonpay/gold/medium/button_T6_3x.png");
   $(".amazonpay-button-inner-image").removeAttr("uri", "https://d2ldlvi1yef00y.cloudfront.net/us/live/apa/orange/medium/button_T15.png");
	$(".amazonpay-button-inner-image").attr("src", "/content/themes/theme/assets/img/amazon_pay_button.png");
   $(".amazonpay-button-inner-image").attr("uri", "/content/themes/theme/assets/img/amazon_pay_button.png");
	$("#pay_with_amazon img").show();

   	// display checkout options
	$('.wc-proceed').show();
	$('#pay_with_amazon').after('<div style="text-align: left; font-size: 0.8em; text-transform: none; font-weight: 400; color: #aaa">* With Amazon checkout, you\'ll be redirected back to OvernightGlasses for order review</div><br/>');
        $(".checkout--details-title").html("SHIPPING ADDRESS");

   });
})(jQuery);
</script>
<?php
}
add_action('woocommerce_before_cart_table', 'move_checkout_button');


function move_coupon_checkout_page() {
?>
<script>
(function($){
    $(window).load(function() {
        $('li.payment_method_affirm').insertAfter($('li.payment_method_amazon').last());
        //console.log("show move coupon");
	$(".showcoupon").parent().css("cssText", "display: none !important;");
	$(".checkout_coupon").insertBefore(".woocommerce-checkout-review-order-table");
        if ($("#shcp").val() == "1") {
                $(".checkout_coupon").show();   
        }
    });
})(jQuery);
</script>
<?php
}
add_action('woocommerce_checkout_before_order_review', 'move_coupon_checkout_page');


function show_back_coupon($coupon_code) {
?>
<script>
	//console.log("remove coupon show couponm");
	$(".checkout_coupon").show();
</script>
<?php 
}
add_action('woocommerce_removed_coupon', 'show_back_coupon', 10, 1);


function custom_coupon_html($coupon) {
    if ( is_string( $coupon ) ) { 
        $coupon = new WC_Coupon( $coupon ); 
    } 
 
    $discount_amount_html = ''; 
 
    if ( $amount = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax ) ) { 
        $discount_amount_html = '-' . wc_price( $amount ); 
    } elseif ( $coupon->get_free_shipping() ) { 
        $discount_amount_html = __( 'Free shipping coupon', 'woocommerce' ); 
    } 
 
    $discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon ); 
    $coupon_html = '<a href="' . esc_url( add_query_arg( 'remove_coupon', urlencode( $coupon->get_code() ), defined( 'WOOCOMMERCE_CHECKOUT' ) ? wc_get_checkout_url() : wc_get_cart_url() ) ) . '" class="fa fa-trash" data-coupon="' . esc_attr( $coupon->get_code() ) . '">'. __( '', 'woocommerce' ) . '</a>&nbsp; - $' .$amount; 
 
    echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); 
    
}

add_filter( 'woocommerce_coupon_error', 'wpq_coupon_error', 10, 2 );
function wpq_coupon_error( $err, $err_code ) {
return ( '105' == $err_code ) ? '' : $err;
}

//add_filter('wc_cart_totals_coupon_html', 'custom_coupon_html');
// DISABLE BOGO add_action( 'woocommerce_cart_calculate_fees', 'buy_1_get_1_free', 10, 1 );
function buy_1_get_1_free( $cart ) {

    if ( is_admin() && ! defined( 'DOING_AJAX' ) )
        return;

    // sort cart by lower price first
    if ( empty( $cart->cart_contents ) ) {
	return;
    }
    $cart_sort = array();
    // add cart item inside the array
    foreach ( $cart->cart_contents as $cart_item_key => $cart_item ) {
    	$cart_sort[ $cart_item_key ] = $cart->cart_contents[ $cart_item_key ];
    }
    @uasort( $cart_sort, 'wpm_sort_by_price' );
    //replace the cart contents with the array sorted
    $cart->cart_contents = $cart_sort;

    if (count($cart->get_cart()) < 2) {
	return;	
    }

    // Initialising
    $count = 0;
    // Iterating though each cart items
    foreach ( $cart->get_cart() as $cart_item ) {
	//print_r($cart_item['data']);
	//print "<br/>NAME: ".$cart_item['data']->get_title();
        //print "<br/>PRICE: ".$cart_item['data']->get_price();
	//print "<br/>REGULAR PRICE: ".$cart_item['data']->regular_price." <br/>";
	$count++;
        if( 1 == $count){ // First item only
            $price = $cart_item['data']->get_price(); // product price
            $discount = $cart_item['data']->regular_price; // calculation
            $second_item = true;
	    $name_second = $cart_item['data']->get_title();
            break; // stop the loop
        }
    }
    if( isset($discount) && $discount > 0 )
        $cart->add_fee("[BOGO] FREE 2ND FRAME $name_second", -$discount );
}



function wpm_sort_by_price( $cart_item_a, $cart_item_b ) {
	return $cart_item_a['data']->regular_price > $cart_item_b['data']->regular_price;
}

function wpm_sort_by_price_desc( $cart_item_a, $cart_item_b ) {
	return $cart_item_a['data']->regular_price < $cart_item_b['data']->regular_price;
}

// For billing email and phone - Make them not required
add_filter( 'woocommerce_billing_fields', 'filter_billing_fields', 20, 1 );
function filter_billing_fields( $billing_fields ) {
    // Only on checkout page
    if( ! is_checkout() ) return $billing_fields;

    $billing_fields['billing_address_1']['required'] = false;
    $billing_fields['billing_address_2']['required'] = false;
    $billing_fields['billing_city']['required'] = false;
    $billing_fields['billing_first_name']['required'] = false;
    $billing_fields['billing_last_name']['required'] = false;
    $billing_fields['billing_state']['required'] = false;
    $billing_fields['billing_postcode']['required'] = false;
    
    //$billing_fields['shipping_state']['required'] = false;
    return $billing_fields;
}

// Delivery estimator 
require_once(dirname(__FILE__) . '/addons/delivery_estimation.php');

// Clearance
function show_clearance_products() {
	global $wpdb;
	$products = $wpdb->get_results($wpdb->prepare("select distinct post_parent from wp_postmeta m, wp_posts p where m.post_id = p.id and (m.meta_key= %s and m.meta_value != '')",'_sale_price'));
	$ps = array();
	foreach ($products as $prod) {	
		array_push($ps, $prod->post_parent);
	}
	$str_prods = join(",", $ps);
	//echo $str_prods;
	//echo 'do_shortcode("[ong_filter base_filter=\'{ product_id: {\"\$in\": ["'.$str_prods.'."]} }\']");';
	//echo $str_prods;
	//echo do_shortcode('[ong_filter base_filter=\'{"$match":{"product_id": 45742 }}\']');
	echo do_shortcode('[ong_filter clearance="'.$str_prods.'"]');
}
add_shortcode('show_clearance', 'show_clearance_products');


add_action('acf/init', 'my_acf_op_init');
function my_acf_op_init() {

    // Check function exists.
    if( function_exists('acf_add_options_sub_page') ) {

        // Add sub page.
        $child = acf_add_options_sub_page(array(
            'page_title'  => __('Cart Options'),
            'menu_title'  => __('Cart Options'),
            'parent_slug' => 'edit.php?post_type=product',
        ));
    }
}
