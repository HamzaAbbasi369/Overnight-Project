<?php
//if (!function_exists('carbon_get_comment_meta')) {
//	return;
//}
//
//use Carbon_Fields\Container;
//use Carbon_Fields\Field;
//
////var_dump(WcRx::$carbon_container);
//Container::make('theme_options', 'Progressive Lenses')
//         ->set_page_parent( 'rx-options' )
////         ->set_page_parent( WcRx::$carbon_container )
//         ->add_fields([
//	         Field::make("checkbox", "is_popup_sssfor_rx_material_enabled", "test")
//	              ->set_option_value('yes'),
////        Field::make('textarea', 'text_popup_for_rx_material', 'Popup HTML'),
//         ]);

define('LENS_PACKAGES_CATEGORY', 'lens-packages');

add_action('template_redirect', function(){

	if(is_admin()) {
		return false;
	}

	require_once( dirname( __FILE__ ) . '/../templates/rx_functions.php' );

	$is_lens_package = null;

	if (is_product()) {
		$is_lens_package = !!has_term( LENS_PACKAGES_CATEGORY, 'product_cat' );
	}

	if ( $is_lens_package === false ) {
		add_action('woocommerce_before_add_to_cart_button', function(){
			foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];

				if ( has_term( LENS_PACKAGES_CATEGORY, 'product_cat', $_product->get_id() ) ) {

					add_filter('woocommerce_before_add_to_cart_button_show_rx', '__return_false', 60) ;
					add_filter('woocommerce_before_add_to_cart_button_show_fashion', '__return_false', 60) ;
					add_filter('woocommerce_before_add_to_cart_button_show_cart', '__return_true', 60);

					rx_get_template( 'lens_package_is_in_the_cart.php', [
						'product' => $_product
					]);
					break;
				}
			}
		});
	}

	if ( $is_lens_package === true ) {
		remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);


        remove_action( 'wp_enqueue_scripts', 'wc_yotpo_load_js' );
        remove_action( 'template_redirect', 'wc_yotpo_front_end_init' );
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products',  20);
        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs',  10);

        remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);

        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_title', 5);
        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_rating', 10);
        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_price', 10);
        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_excerpt', 20);
        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_meta', 40);
        remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_sharing', 50);


        remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display',  15);

        remove_action( 'woocommerce_before_main_content', ['Theme_Customisations', 'ong_output_content_wrapper'], 10 );
        remove_action( 'woocommerce_after_main_content', ['Theme_Customisations', 'ong_output_content_wrapper_end'], 10 );

        add_filter('woocommerce_before_add_to_cart_button_show_rx', '__return_false', 50) ;
        add_filter('woocommerce_before_add_to_cart_button_show_fashion', '__return_false', 50) ;
        add_filter('woocommerce_before_add_to_cart_button_show_cart', '__return_false', 50);

		/** @var WC_Product_Simple $post */
        $_product = wc_get_product();
        if ( $_product->is_type( 'variation' ) ) {
            $post = get_post( $_product->get_parent_id() );
        } else {
            $post = get_post( $_product->get_id() );
        }

        $slug = $post->post_name;
		if (function_exists(str_replace("-", "_", 'draw_'.$slug))) {
            add_action('woocommerce_before_main_content', str_replace("-", "_", 'draw_'.$slug), 1000);
        } else {
            add_action('woocommerce_before_main_content', str_replace("-", "_", 'draw_'.LENS_PACKAGES_CATEGORY), 1000);
        }

        add_filter('user-fit-feature-could-be-displayed', '__return_false');
	}

	add_action( 'woocommerce_cart_collaterals', 'ong_woocommerce_button_proceed_to_checkout', 5 );
	function ong_woocommerce_button_proceed_to_checkout() {
		foreach ( WC()->cart->get_cart() as $cart_item_key => $values ) {
			$_product = $values['data'];

			if ( has_term( LENS_PACKAGES_CATEGORY, 'product_cat', $_product->get_id()) ) {

				remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );

				rx_get_template( 'continue-shopping-for-frame.php', [
					'type' => LENS_PACKAGES_CATEGORY,
					'message' => sprintf(__('Your lenses were added to cart. Please select your eyeglasses frame or send your own frame for lens replacement.', 'woocommerce-rx'),$_product->get_title())
				]);

			}
		}
	}
});

add_filter('woocommerce_add_cart_item', function($cart_item_data, $cart_item_key){
	if (!defined('WC_RX_META_ATTRIBUTES')) {
		return $cart_item_data;
	}

	if ( WC()->cart->is_empty() ) {
		return $cart_item_data;
	}

	$lens_package = null;
	$cart           = WC()->cart->get_cart();

	foreach ( $cart as $cart_item_key => $values ) {
		$_product          = $values['data'];
		if ( has_term( LENS_PACKAGES_CATEGORY, 'product_cat', $_product->get_id() ) ) {
			$lens_package = $values;
			$lens_package_key = $cart_item_key;

			foreach (WC_RX_META_ATTRIBUTES as $meta_key => $values_key) {
			    $value = $lens_package[$values_key];
			    if ($meta_key==='price') {
                    $value = $cart_item_data[$values_key] + $value;
                }
				$cart_item_data[$values_key] = $value;
			}

			//and remove $lens_package from the cart
			WC()->cart->remove_cart_item($lens_package_key);
			break;
		}
	}

	return $cart_item_data;
}, 10,2);
