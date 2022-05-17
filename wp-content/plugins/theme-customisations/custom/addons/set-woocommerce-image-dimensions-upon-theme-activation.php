<?php

/**
 * Define image sizes
 */
function ong_woocommerce_image_dimensions()
{
    global $pagenow;

    if (!isset($_GET['activated']) || $pagenow != 'themes.php') {
        return;
    }
    $catalog   = [
        'width'  => '360',    // px
        'height' => '360',    // px
        'crop'   => 0        // false
    ];
    $single    = [
        'width'  => '739',    // px
        'height' => '739',    // px
        'crop'   => 0        // false
    ];
    $thumbnail = [
        'width'  => '283',    // px
        'height' => '283',    // px
        'crop'   => 0        // false
    ];
// Image sizes
    update_option('shop_catalog_image_size', $catalog);        // Product category thumbs
    update_option('shop_single_image_size', $single);        // Single product image
    update_option('shop_thumbnail_image_size', $thumbnail);    // Image gallery thumbs
}

add_action('after_switch_theme', 'ong_woocommerce_image_dimensions', 1);
add_action('after_setup_theme', 'ong_woocommerce_image_dimensions', 1);

if ( function_exists( 'add_theme_support' ) ) {
    add_theme_support( 'post-thumbnails' );
}

if ( function_exists( 'add_image_size' ) ) {
    add_image_size( 'ong_brand_overlay', 600, 100 );
}
