<?php
add_action('template_redirect', function(){
	$is_sunglasses = (is_product() && has_term('Sunglasses', 'product_cat'));

	add_filter('woocommerce_before_add_to_cart_button_show_rx', function($show_rx, $product) use ($is_sunglasses){
		if ($is_sunglasses) {
			$show_rx = 0;
		}
		return $show_rx;
	},10,2) ;

	add_filter('woocommerce_before_add_to_cart_button_show_fashion', function($show_fashion, $product) use ($is_sunglasses){
		if ($is_sunglasses) {
			$show_fashion = 0;
		}
		return $show_fashion;
	},10,2) ;

	add_filter('woocommerce_before_add_to_cart_button_show_cart', function($show_cart, $product) use ($is_sunglasses){
		if ($is_sunglasses) {
			$show_cart = 1;
		}
		return $show_cart;
	},10,2);
});
