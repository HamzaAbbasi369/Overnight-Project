<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

// Ensure visibility
if ( empty( $product ) || ! $product->is_visible() ) {
	return;
}

// check if variation are avaiable if all are out of stock don't display the glass
if (is_a($product, 'WC_Product_Simple')) {
	$variations = '';
} else {
	$variations = $product->get_available_variations();
}

$all_out_of_stock = 0;
if ($variations != '') {
	foreach ($variations as $variation) {
		if ($variation['is_in_stock'] == 1) {
			$all_out_of_stock = 1;
		} else { 
			$all_out_of_stock = 0; 
		}
	}
}
if ($all_out_of_stock == 1) {
	return;
}



?>
<li <?php post_class('small-12 medium-6 large-4 columns category-wrap for-single-right-height'); ?>>
	<?php
	/**
	 * woocommerce_before_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_open - 10
	 */
	if ($product->get_title()=='ANY FRAME') {
     ?>
        <div class="item-row custom-look-item">
            <div class="look-item">
                <a href="<?php echo get_site_url(); ?>/product_category/your-frames/" class="look--item-wrap"></a>
                <div class="parent-sale content-sale"><span class="onsale">Sale</span></div>
                <img src="<?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new.jpg"
                     class="attachment-shop_single size-shop_single wp-post-image your-frame-img" alt="Glasses Lens
                     Replacement" title="Glasses Lens Replacement" srcset="<?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new.jpg 614w, <?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new-300x147.jpg 300w, <?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new-205x100.jpg 205w, <?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new-283x138.jpg 283w, <?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new-360x176.jpg 360w" sizes="(max-width: 639px) 98vw, (max-width: 1199px) 64vw, 614px" data-svizoom-image="<?php echo get_site_url(); ?>/content/uploads/2015/11/your-frame_new.jpg" data-woosvi="">
                <h2 class="woocommerce-loop-product__title">New lenses for your own frame</h2>
                 <span class="price"><del><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>55.00</span></del> <ins><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span>32.00</span></ins></span>
                <p class="woocommerce-loop-product__title">Have a frame that you love? Send it to us and we will
                                                            replace your lenses with your new prescription.</p>
                <div class="hover--bottom-out-wrap">
                    <div class="clearfix hover--bottom-wrap">
                        <div class="right-menu float-right">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php
        return;
    }
    else
    {
        do_action('woocommerce_before_shop_loop_item');
    }

	/**
	 * woocommerce_before_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_show_product_loop_sale_flash - 10
	 * @hooked woocommerce_template_loop_product_thumbnail - 10
	 */
	do_action( 'woocommerce_before_shop_loop_item_title' );

	/**
	 * woocommerce_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_product_title - 10
	 */
	do_action( 'woocommerce_shop_loop_item_title' );

	/**
	 * woocommerce_after_shop_loop_item_title hook.
	 *
	 * @hooked woocommerce_template_loop_rating - 5
	 * @hooked woocommerce_template_loop_price - 10
	 */
	do_action( 'woocommerce_after_shop_loop_item_title' );
	/**
	 * woocommerce_after_shop_loop_item hook.
	 *
	 * @hooked woocommerce_template_loop_product_link_close - 5
	 * @hooked woocommerce_template_loop_add_to_cart - 10
	 */
	// FIX to force YOTPO on SYNC of products
	//wc_yotpo_show_buttomline($product->id);
	do_action( 'woocommerce_after_shop_loop_item' );
	?>
</li>
