<?php

add_action('wp_enqueue_scripts', function(){
    $js = /** @lang JavaScript */ <<<JS
    
jQuery(document).on('rx-loaded', function () {
    var rx_form_container = jQuery('.rx-form-container');
    var coupon = jQuery('.best_deal_coupon');
    var deal_type = coupon.data('dealType');
    var percent = coupon.data('dealValue');
    
    //copy code functionality
    jQuery('.btn-action-02').click(function(){
        var input = jQuery('input#code-coupon');
        input.select();
        document.execCommand('copy');
        jQuery('.tooltiptext').show('fast');
        setTimeout(function() {
            jQuery('.tooltiptext').hide('slow');
        }, 3000);
    });
    
    
    rx_form_container.on('rx_calculate', function(event){
        var rush = isNaN(rx.price.get('rush')) ? 0 : rx.price.get('rush'); 
        var lensSubtotal =isNaN(rx.price.get('lens')) ? 0 : rx.price.get('lens');
        var total =isNaN(rx.price.get('total')) ? 0 : rx.price.get('total');

        if (deal_type==='lens') {
            if (lensSubtotal > 0) {
                var save = ((lensSubtotal) * percent) / 100;
                var new_total = total - save;
            }
        } 
        
        if (deal_type==='order') {
            save = ((total - rush) * percent) / 100;
            new_total = total - save;
        }
        
        if (save > 0) {
            coupon.find('.save_additional').html(rx.price.format(save));
            coupon.find('.final-total-price').html(rx.price.format(new_total));
            coupon.hide();    
        } else {
            coupon.hide();
        }
    });
});

JS;
    wp_add_inline_script( 'rx_jscript', $js);
},20);

#define('DEFAULT_PROMOTE_COUPONS_LIST', ['getbrand','get25','lens25','cyber30', 'dcm30']);
#ORIG define('DEFAULT_PROMOTE_COUPONS_LIST', ['get25','lens25','cyber30', 'dcm30']);


define('DEFAULT_PROMOTE_COUPONS_LIST', ['lens25', 'get25', 'dcm30']);

#define('DEFAULT_PROMOTE_COUPONS_LIST', ['cyber30', 'dcm30', 'get25', 'getbrand', 'lens25']);


add_filter('promote_coupon_can_be_applied', function($result, WC_Coupon $coupon, $options){
    static $product_cats;

    /** @var int $fprice */
    /** @var int $frame_regular_price */
    /** @var int $product_id */
    extract($options);

    $coupon_code = $coupon->get_code();
    if (empty($product_cats)) {
        $product_cats = wp_get_post_terms($product_id, 'product_cat', array( "fields" => "ids" ));
    }
    /*$is_sale_item = ($fprice != $frame_regular_price);
    if ($is_sale_item && $coupon->get_exclude_sale_items()) {
        return false;
    }*/
    if ($coupon_code==='getbrand' && is_array($coupon->get_product_categories()) && empty(array_intersect($coupon->get_product_categories(), $product_cats))) {
        return false;
    }
    return $result;
}, 10, 3);

add_action('after_package_data_list', function () {
    $options = [
        'fprice' => isset($_REQUEST['fprice']) ? $_REQUEST['fprice'] : '0',
        'frame_regular_price' => isset($_REQUEST['frame_regular_price']) ? $_REQUEST['frame_regular_price'] : '0',
        'product_id' => (isset($_REQUEST['product_id'])) ? $_REQUEST['product_id'] : ''
];
    $prod = wc_get_product($options['product_id']);
    $coupons = apply_filters('promote_coupons_list', DEFAULT_PROMOTE_COUPONS_LIST);
    if ($options['product_id'] != '14010') {
    	if ($prod->is_on_sale()) {
		$coupons = array_diff($coupons, ['get25']);
	} else {
		$coupons = array_diff($coupons, ['lens25']);
	}
    } else {
	    $coupons = array_diff($coupons, ['get25']);
    }	   
	    

    #if ($_REQUEST['product_id'] != '14010') {
    #	    if ($options['frame_regular_price'] != '0') {
    #	    	$coupons = array_diff($coupons, ['get25']);
  #	    }	
   # }
    
    #$coupons = array('cyber30');
    $message = '';
    foreach ($coupons as $coupon_code) {
        $coupon = new WC_Coupon($coupon_code);
        if (( $coupon->get_id() > 0 ) && apply_filters('promote_coupon_can_be_applied', true, $coupon, $options)) {
	    $message = getMessage($coupon_code, 'promote_coupons_'.$coupon_code.'_message');
	    break;
        }
    }
    echo $message;
});

function getMessage($coupon_code, $message)
{
    $message = carbon_get_theme_option($message);
    $coupon = new WC_Coupon($coupon_code);
    $coupon_amount = $coupon->get_amount();
    $message = str_replace('%coupon_amount%', $coupon_amount, $message);
    $message = str_replace('%coupon_code%', $coupon_code, $message);
    return $message;
}

/**
 * @param $coupon_code
 */
function autoCouponApply($coupon_code)
{
    if (get_option('is_show_auto_coupons_rx') === 'yes') {
        global /** @var WooCommerce $woocommerce */ $woocommerce;
//        $woocommerce->cart->remove_coupon($coupon_code);
        $woocommerce->cart->add_discount($coupon_code);
        wc_print_notices();
    }
}

add_action('woocommerce_add_to_cart', 'ong_action_woocommerce_add_to_cart', 10, 6);

function ong_action_woocommerce_add_to_cart ($cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data=null)
{
    $coupons = apply_filters('promote_coupons_list', DEFAULT_PROMOTE_COUPONS_LIST);

    /** @var WC_Product|WC_Product_Variation $_product */
    $_product = wc_get_product($variation_id ? $variation_id : $product_id);
    $options = [
        'fprice' => $_product->get_price(),
        'frame_regular_price' => $_product->get_regular_price(),
        'product_id' => $product_id
    ];
    $result_coupon_code = '';

    if ($options['product_id'] != '14010') {
        if ($_product->is_on_sale()) {
                $coupons = array_diff($coupons, ['get25']);
        } else {
                $coupons = array_diff($coupons, ['lens25']);
        }
    } else {
            $coupons = array_diff($coupons, ['get25']);
    }


    foreach ($coupons as $coupon_code) {
        $coupon = new WC_Coupon($coupon_code);
        if (( $coupon->get_id() > 0 ) && apply_filters('promote_coupon_can_be_applied', true, $coupon, $options)) {
            $result_coupon_code = $coupon_code;
            break;
        }
    }

    if (!empty($result_coupon_code)) {
//        autoCouponApply($result_coupon_code);
    }
};
