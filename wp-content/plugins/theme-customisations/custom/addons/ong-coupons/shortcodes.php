<?php

//echo do_shortcode("[ong_coupon_for_main]");

add_shortcode('ong_coupon_for_main', 'ong_coupon_for_main');
function ong_coupon_for_main()
{
    $args = [
        'orderby' => 'rand',
        'numberposts' => 1,
        'order'       => 'DESC',
        'post_type'   => 'shop_coupon',
        'suppress_filters' => true,
        'meta_key' => 'for_main',
        'meta_value' => 1,
    ];

    $result = '';
    foreach (get_posts($args) as $post) {
        $id = $post->ID;
        $div_html = get_post_meta($id, 'div_html', true);
        $div_css = get_post_meta($id, 'div_css', true);
        $image_coupon = wp_get_attachment_url(get_post_meta($id, 'image_coupon', true));

        if ($image_coupon != null) {
            $image_coupon = "style='background: url(" . $image_coupon . ")' no-repeat center";
        } else {
            $image_coupon = '';
        }

        $result .= showCssForCoupon($div_css);
        $result .="
        <div class='m-kontent-item--home m-kontent-item-1 small-12 medium-12 large-4 columns post-{$id}' 
             {$image_coupon}>
            <a href='/special-eyeglasses-deals/' class='m-kontent-item--wrap'>
                <div class='news--text main-coupon' style='bottom: 50px; bottom: 5rem;'>
                    {$div_html}
                </div>
            </a>
        </div>
        ";
    }
    return $result;
}


//<?php echo do_shortcode('[ong_coupon_for_container count=9 meta="for_main" orderby="rand"]');

add_shortcode('ong_coupon_for_container', 'ong_coupon_for_container');
function ong_coupon_for_container($attr)
{
    $count      = !empty($attr['count'])    ? $attr['count']    : '3';
    $meta       = !empty($attr['meta'])     ? $attr['meta']     : 'for_cart';
    $orderby    = !empty($attr['orderby'])  ? $attr['orderby']  : '';

    $args = [
        'orderby'           => $orderby, // rand
        'numberposts'       => $count,
        'order'             => 'DESC',
        'post_type'         => 'shop_coupon',
        'suppress_filters'  => true,
        'meta_key'          => $meta, // for_cart, for_main
        'meta_value'        => 1,
    ];

    $result = '';
    foreach (get_posts($args) as $post) {
        $id = $post->ID;
        $result .= showCssForCoupon(get_post_meta($id, 'div_css', true));
        $result .= get_post_meta($id, 'div_html', true);
    }
    return $result;
}

function showCssForCoupon($div_css)
{
    $result = '';
    if ($div_css != null) {
        $result = "<style type=\"text/css\" media=\"screen\">$div_css;</style>";
    }

//    if ($div_css != null) {
//    add_action('wp_enqueue_scripts', function () use ($div_css) {
//        wp_add_inline_style('custom-css', $div_css);
//    }, 1000);

    return $result;

}
