<?php
/**
 * Single Product Meta
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/meta.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

?>
<div class="product_meta">

	<?php do_action( 'woocommerce_product_meta_start' ); ?>

	<?php //echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php //echo wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>
<?php
$tabs_desktop = apply_filters('woocommerce_product_tabs', []);
unset($tabs_desktop['description']); ?>
<?php echo do_shortcode('[yith_wcwl_add_to_wishlist]');?>
<div class="woocommerce-tabs wc-tabs-wrapper hide-for-small-only">
    <ul class="tabs wc-tabs">
        <?php foreach ($tabs_desktop as $key => $tab) : ?>
            <li class="<?php echo esc_attr($key); ?>_tab">
                <a href="#tab-<?php echo esc_attr($key); ?>">
                    <?php echo apply_filters('woocommerce_product_'.$key.'_tab_title', esc_html($tab['title']), $key); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>

    <?php foreach ($tabs_desktop as $key => $tab) : ?>
        <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr($key); ?>
        panel entry-content wc-tab"
             id="tab-<?php echo esc_attr($key); ?>">
            <?php
            call_user_func($tab['callback'], $key, $tab); ?>
        </div>
    <?php endforeach; ?>
</div>
