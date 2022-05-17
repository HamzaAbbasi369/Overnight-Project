<?php
add_action('template_redirect', function() {
    global $wp_query;
    if (is_archive()) {
        $product = wc_get_product($wp_query->post);





    }
}, 50);


add_action('get_footer', 'ong_archive_product_woocommerce_before_shop_loop_item', 5);
function ong_archive_product_woocommerce_before_shop_loop_item($name)
{

    if (is_archive()) {
       

?>

<!-- BRAND CAROUSEL BLOCK -->
<?php ong_get_template_part('template-parts/home/home', 'brand-carousel'); ?>
<!-- END BRAND CAROUSEL BLOCK -->
<?php
    }
}


add_action( 'woocommerce_no_products_found', 'ong_archive_product_' );
