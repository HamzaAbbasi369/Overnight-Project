<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
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
 * @version 3.3.2
 */
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global /** @var woocommerce_svi_frontend $woosvi_class */
$post, $woocommerce, $product, $woosvi, $woosvi_class;

$img = 'images ';
if ($woosvi['data']['sviforce_image']) {
    $img = '';
}

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes', array(
    'woocommerce-product-gallery',
    'woocommerce-product-gallery--' . ( has_post_thumbnail() ? 'with-images' : 'without-images' ),
    'woocommerce-product-gallery--columns-' . absint( $columns ),
    'images',
) );
?>
<ul class="menu vertical product--glasses-wrap" data-columns="<?php echo esc_attr( $columns ); ?>">
    <li>
        <div class="woosvi_strap <?php echo $img . $woosvi['class']; ?>" <?php echo $woosvi['lens']; ?>>
        <?php

            if ($product->get_type() === 'simple') {
                echo get_the_post_thumbnail(
                    $post->ID,
                    apply_filters('single_product_large_thumbnail_size', 'shop_single'),
                    [
                        'title' => get_the_title(get_post_thumbnail_id())
                    ]
                );
            }

            if ($woosvi['data']['slider-position']) {
                do_action('woocommerce_product_thumbnails');
            }

            if ($woosvi['data']['slider']) {
                $woosvi_class->build_mainswiper();
            }  else {
                if ($woosvi['data']['display_mainimage']) {
                    $woosvi_class->build_mainimage();
                }
            }

            if (!$woosvi['data']['slider-position']) {
                do_action('woocommerce_product_thumbnails');
            }

        ?>
        </div>
    </li>
</ul>
