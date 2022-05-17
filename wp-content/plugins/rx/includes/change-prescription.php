<?php

add_action('wp_ajax_wdm_add_user_custom_data_options', 'change_rx_wdm_add_user_custom_data_options_callback');
add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options', 'change_rx_wdm_add_user_custom_data_options_callback');

function change_rx_wdm_add_user_custom_data_options_callback () {
    if (array_key_exists('cart_item_key', $_REQUEST)) {
        $cart_item_key = $_REQUEST['cart_item_key'];
        $bool = WC()->cart->remove_cart_item( $cart_item_key );
        unset($_REQUEST['cart_item_key']);
    }
}

add_filter('woocommerce_cart_item_remove_link', function($link, $cart_item_key){
    
    $cart = WC()->cart->get_cart();
    
    if (array_key_exists($cart_item_key, $cart)) {
       
        $cart_item = $cart[$cart_item_key];
        if(!array_key_exists('_all_lens_data',$cart_item)){
            $cart_item['_all_lens_data'] = array();
        }
        
        $frameSelect = 0;
        if (empty($cart_item['variation']['attribute_pa_color'])) {
         //   return $link;
           $frameSelect = 1;     
        }

        $_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
        $product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

        if ( $_product &&
             $_product->exists() &&
             $cart_item['quantity'] > 0 &&
             apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) &&
             array_key_exists('_all_lens_data',$cart_item)
        ) {
            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
           
            $dataTypeRx = 'rx';
            if($frameSelect == 1){
                $dataTypeRx ='rxframe';
            }
            // remove Button of Change frame for Your Frame
            $prodName = explode("-",$_product->get_name());
            $prodYourFrame = '';
            if(trim($prodName[0]) == 'Your Frames'){
               // $prodYourFrame = 'style="display:none"';
            }
            $link = sprintf(
                '<a href="%s" title="%s" class="cart-change-link" data-color="%s" data-type="'.$dataTypeRx.'" data-lens="%s" data-cart-item-key="%s">Change&nbsp;Rx</a>',
                esc_url(get_permalink( $product_id )),
                __('Change Prescription', 'woocommerce-rx'),
                esc_attr($cart_item['variation']['attribute_pa_color']),
                esc_attr(json_encode($cart_item['_all_lens_data'])),
                esc_attr($cart_item_key)
            )
            
            . sprintf(
                '<a '.$prodYourFrame.' href="%s" title="%s" class="cart-change-link" data-color="%s" data-type="frame"  data-lens="%s" data-cart-item-key="%s">Change&nbsp;Frame</a>',
                esc_url(wc_get_page_permalink( 'shop' )),
                __('Change Frame', 'woocommerce-rx'),
                esc_attr($cart_item['variation']['attribute_pa_color']),
                esc_attr(json_encode($cart_item['_all_lens_data'])),
                esc_attr($cart_item_key)
            )
            . $link;
        }
    }
    return $link;
}, 10, 2);

add_action('template_redirect', function(){

    if (is_singular( 'product' )) {
        $js = '';
        if (!empty($_REQUEST['ltype'])) {
            $type = $_REQUEST['ltype'];
            $js .= /** @lang JavaScript */ <<<JS
            jQuery(document).ready(function($){  
                console.log('load Rx');
                jQuery('body.single-product .summary.entry-summary .variations_form').on('found_variation', function (event, variation) {
                     jQuery('#order_rx[data-ltype="{$type}"]:visible').click();
                });
            });

JS;
        }
        #/* ever open rx */
        if (!empty($_REQUEST['forcelrxtype'])) {
            $js .= /** @lang JavaScript */ <<<JS
            jQuery(document).ready(function($){
		jQuery(".yotpo").hide();
                setTimeout( 
                    function($){
                        jQuery('#order_rx[data-ltype="Rx"]:visible').click();
                    }, 500 );
		setTimeout(
		   function($) {
			jQuery('#single-product-wrap').css('visibility', 'visible');
		   }, 2000 );
            });
JS;
        }
        $js .= /** @lang JavaScript */ <<<JS
            if (localStorage.getItem('change_rx_cart_item_key')) {
                wp.hooks.addFilter( 'wdm_add_user_custom_data_options_data', function( data ) {
                    data['cart_item_key'] = localStorage.getItem('change_rx_cart_item_key');
                    localStorage.removeItem('change_rx_cart_item_key');
                    return data;
                } );
            }
JS;
        add_action('wp_enqueue_scripts', function() use ($js){
            wp_add_inline_script( 'rx_jscript', $js, 'before');
        },20);


        if (isset( $_COOKIE['change_frame_cart_item_key'] ) ) {
            foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
                if ($cart_item_key === $_COOKIE['change_frame_cart_item_key'] ) {
                    $old_product = $values['data'];
                    break;
                }
            }
            if (isset($old_product)) {
                add_action('woocommerce_before_add_to_cart_button', function() use ($old_product){
                    add_filter('woocommerce_before_add_to_cart_button_show_rx', '__return_false', 60) ;
                    add_filter('woocommerce_before_add_to_cart_button_show_fashion', '__return_false', 60) ;
                    add_filter('woocommerce_before_add_to_cart_button_show_cart', '__return_true', 60);
                    rx_get_template( 'change_frame_is_in_the_cart.php', [
                        'product' => $old_product
                    ]);
                });
            }
        }
    }

    if (is_cart()) {
	    $js = /** @lang JavaScript */ <<<'JS'
            Cookies.remove("change_frame_cart_item_key");
            jQuery('.shopping--content-wrap').on('click', '.cart-change-link', function(e){
                e.preventDefault();
                e.stopPropagation();
                var $this = jQuery(this);
                var rxData = localStorage.getItem('rx_data') ? JSON.parse(localStorage.getItem('rx_data')) : {};
                var oldData = $this.data('lens');
                for (var i in oldData) {
                    if (!oldData.hasOwnProperty(i)) {
                        continue;
                    }
                    rxData[i] = oldData[i];
                }
                localStorage.setItem('rx_data', JSON.stringify(rxData));
		var url = this.href;
		console.log("Data type: " + $this.data('type'));
                if ('rx' === $this.data('type')) {
                    url +=  '?' +jQuery.param( {'ltype':  'Rx'} ) + '#color='+$this.data('color');
		    localStorage.setItem('change_rx_cart_item_key', $this.data('cartItemKey'));
		    Cookies.remove("change_frame_cart_item_key");
                } else if ('rxframe' === $this.data('type')) {
                    url +=  '?' +jQuery.param( {'forcelrxtype':  'Rx'} );
                    localStorage.setItem('change_rx_cart_item_key', $this.data('cartItemKey'));
                } else if ('frame' === $this.data('type')) {
		    //jQuery.cookie("change_frame_cart_item_key", $this.data('cartItemKey'), { path: '/', expires: 3 });
		    console.log("Set cookie for change frame");
		    Cookies.set("change_frame_cart_item_key", $this.data('cartItemKey'), { path: '/', expires: 3 });	
		    //url +=  '?' +jQuery.param( {'filter': {'pa_taxonomy' : {'pa_color': $this.data('color')}}}  ) ;
                }
                window.location.href = url;
            });
JS;
        add_action('wp_enqueue_scripts', function() use ($js){
            wp_add_inline_script( 'wc-cart', $js, 'before');
        },20);
    }
});

add_filter('woocommerce_add_cart_item', function($cart_item_data, $cart_item_key){
    if (!defined('WC_RX_META_ATTRIBUTES')) {
        return $cart_item_data;
    }

    if ( WC()->cart->is_empty() ) {
        return $cart_item_data;
    }

    if ( !array_key_exists('change_frame_cart_item_key', $_COOKIE) ) {
        return $cart_item_data;
    }

    $lens_package = null;
    $cart           = WC()->cart->get_cart();
    

    foreach ( $cart as $cart_item_key => $values ) {
        if ( $cart_item_key === $_COOKIE['change_frame_cart_item_key'] ) {
            $lens_package = $values;
            foreach (WC_RX_META_ATTRIBUTES as $meta_key => $values_key) {
                $value = $lens_package[$values_key];
                if ($meta_key==='price') {
                    $value = isset($lens_package['_all_lens_data']['lens_price'])
                        ? $lens_package['_all_lens_data']['lens_price']
                        : 0;
                    $value = $cart_item_data[$values_key] + $value;
                }
                $cart_item_data[$values_key] = $value;
            }
            break;
        }
    }

    //and remove $lens_package from the cart
    WC()->cart->remove_cart_item($_COOKIE['change_frame_cart_item_key']);

    return $cart_item_data;
}, 10,2);
