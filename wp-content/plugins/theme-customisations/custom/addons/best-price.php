<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

add_action( 'ong_after_cart_subtotal', function(){
	ong_get_template_part( 'template-parts/cart/totals', 'best-price-guarantee' );
	ong_get_template_part( 'template-parts/cart/totals', 'satisfaction' );
	ong_get_template_part( 'template-parts/cart/totals', 'prescription' );
	ong_get_template_part( 'template-parts/cart/totals', 'free-shipping' );
	
});
