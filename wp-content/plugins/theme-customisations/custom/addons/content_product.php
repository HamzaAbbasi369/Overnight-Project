<?php

add_action('init', 'ong_content_product_init', 99);
add_action('admin_init', 'ong_content_product_init', 99);
function ong_content_product_init() {
    add_action( 'woocommerce_before_shop_loop_item', 'ong_content_product_woocommerce_before_shop_loop_item', 1 );
    //add_action( 'woocommerce_before_shop_loop_item_title',  'ong_content_product_woocommerce_before_shop_loop_item_title_');

    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
    //remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
    add_action( 'woocommerce_before_shop_loop_item_title',  'ong_content_product_product_sale', 5);

    remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    //remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
    add_action( 'woocommerce_before_shop_loop_item', 'ong_content_product_woocommerce_template_loop_product_link_open', 10 );

    add_filter('woocommerce_product_get_image', 'ong_content_product_svi_image', 10, 6);

    add_action( 'woocommerce_after_shop_loop_item', 'ong_content_product_woocommerce_after_shop_loop_item_title_hover_wrap_start', 40 );
    add_action( 'woocommerce_after_shop_loop_item', 'ong_content_product_woocommerce_after_shop_loop_item_title_right_wrap_start', 60 );
    add_action( 'woocommerce_after_shop_loop_item', 'ong_content_product_woocommerce_after_shop_loop_item_title_right_wrap_end', 90 );
    add_action( 'woocommerce_after_shop_loop_item', 'ong_content_product_woocommerce_after_shop_loop_item_title_hover_wrap_end', 95 );
    add_action( 'woocommerce_after_shop_loop_item', 'ong_content_product_woocommerce_after_shop_loop_item', 100 );
}


function ong_content_product_woocommerce_before_shop_loop_item() {
    echo <<<'HTML'
    <div class="item-row">
        <div class="look-item">
HTML;
}

function ong_content_product_product_sale() {
    global $product;
    if ($product->is_on_sale()) {
        echo '<div class="parent-sale content-sale"><span class="onsale">Sale</span></div>';
    }
}

function ong_content_product_woocommerce_template_loop_product_link_open() {
    global $product;

    $link = apply_filters( 'woocommerce_loop_product_link', get_the_permalink(), $product );

    echo '<a href="' . esc_url( $link ) . '" class="look--item-wrap"></a>';
}

function ong_content_product_svi_image ($url, $product, $size, $attr, $placeholder, $image) {
    global /** @var woocommerce_svi_frontend $woosvi_class */
    $woosvi, $woosvi_class, $post;
    if (isset($woosvi, $woosvi_class) && is_archive()) {
        ob_start();
        if ($product->get_type() === 'simple') {
            echo get_the_post_thumbnail(
                $post->ID,
                apply_filters('single_product_large_thumbnail_size', 'shop_single'),
                [
                    'title' => get_the_title(get_post_thumbnail_id())
                ]
            );
        }
        do_action('woocommerce_product_thumbnails');
        $url = ob_get_clean();
    }
    return $url;
}

function ong_content_product_woocommerce_after_shop_loop_item_title_hover_wrap_start() {
    echo <<<HTML
            <div class="hover--bottom-out-wrap">
                <div class="clearfix hover--bottom-wrap">
HTML;
}

function ong_content_product_woocommerce_after_shop_loop_item_title_right_wrap_start() {
    echo <<<HTML
                    <div class="right-menu float-right">
HTML;
}


function ong_content_product_woocommerce_after_shop_loop_item_title_right_wrap_end() {
    echo <<<HTML
                    </div>
HTML;
}


function ong_content_product_woocommerce_after_shop_loop_item_title_hover_wrap_end() {
    echo <<<HTML
                </div>
            </div>
HTML;
}

function ong_content_product_woocommerce_after_shop_loop_item() {
    echo <<<'HTML'
        </div>
    </div>
HTML;
}
