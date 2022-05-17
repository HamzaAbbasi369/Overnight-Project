<?php

add_action( 'woocommerce_before_single_product_summary', 'ong_woocommerce_show_product_images_wrap_start', 12 );
function ong_woocommerce_show_product_images_wrap_start() {
    echo <<<'HTML'
    <div class="product--right-content large-8 columns">
HTML;
}

add_action('template_redirect', function() {
    global $wp_query;
    if (is_singular( 'product' )) {
        $product = wc_get_product( $wp_query->post );
        if (is_a($product, 'WC_Product_Variable')) {

            add_action( 'woocommerce_before_single_product_summary', 'ong_single_product_brand_image', 16 );
            function ong_single_product_brand_image() {
                global $product;
                $url_image_brands = '';
                if ( $terms = get_the_terms( $product->get_id(), 'pa_brands' ) ) {
                    $term             = reset( $terms );
                    $image            = get_term_meta( $term->term_id, 'image', true );
                    $url_image        = wp_get_attachment_image_src( $image, 'ong_brand_overlay' );
                    $url_image_brands = $url_image[0];
                }
                if ( $url_image_brands ):
                    echo '<img class="product--brand-img" src="'.$url_image_brands.'">';
                endif;
            }

            add_action( 'woocommerce_before_single_product_summary', 'ong_single_product_fullscreen_link', 23 );
            function ong_single_product_fullscreen_link() {
                echo <<<'HTML'
    <div class="float-left">
                <a href="#" data-open="fullScreen" class="full--screen-button">full screen</a>
				
                <div class="reveal" id="fullScreen" data-reveal>
                    <button class="close-button" data-close aria-label="Close modal" type="button">
					
                        <span aria-hidden="true">&times;</span>
                    </button>
					
                </div>
				
            </div>
HTML;
            }
        }
    }
}, 50);

add_action( 'woocommerce_before_single_product_summary', 'ong_product_glasseslinks_wrap_start', 22 );
function ong_product_glasseslinks_wrap_start() {
    echo <<<'HTML'
    <div class="product--glasses-links clearfix">
HTML;
}

add_action( 'woocommerce_before_single_product_summary', 'ong_product_glasseslinks_wrap_end', 26 );
function ong_product_glasseslinks_wrap_end() {
    echo <<<'HTML'
    </div>
HTML;
}

add_action( 'woocommerce_before_single_product_summary', 'ong_woocommerce_show_product_images_wrap_end', 28 );
function ong_woocommerce_show_product_images_wrap_end() {
    echo <<<'HTML'
    </div>
HTML;
}


