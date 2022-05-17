<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

add_shortcode('ong_products_slider', 'ong_products_slider');

function ong_products_slider($args, $content = '')
{
    global $woocommerce, $smof_data;

    $defaults = array_merge(
        [
            'class'           => '',
            'id'              => '',
            'autoplay'        => 'no',
            'carousel_layout' => 'title_on_rollover',
            'cat_slug'        => '',
            'columns'         => '5',
            'column_spacing'  => '13',
            'mouse_scroll'    => 'no',
            'number_posts'    => 10,
            'picture_size'    => 'fixed',
            'scroll_items'    => '',
            'show_buttons'    => 'yes',
            'show_cats'       => 'yes',
            'show_nav'        => 'yes',
            'show_price'      => 'yes',

        ], (array) $args
    );

    ($defaults['show_cats'] == "yes") ? ($defaults['show_cats'] = 'enable') : ($defaults['show_cats'] = 'disable');
    ($defaults['show_price'] == "yes") ? ($defaults['show_price'] = true) : ($defaults['show_price'] = false);
    ($defaults['show_buttons'] == "yes") ? ($defaults['show_buttons'] = true) : ($defaults['show_buttons'] = false);

    extract($defaults);

    /** @var string $show_cats */
    /** @var boolean $show_price */
    /** @var boolean $show_buttons */

    $html    = '';
    $buttons = '';

    if (class_exists('Woocommerce')) {

        /** @var integer $number_posts */
        $number_posts = (int) $number_posts;

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => $number_posts,
            'meta_query'     => [
                [
                    'key'     => '_thumbnail_id',
                    'compare' => '!=',
                    'value'   => null
                ]
            ]
        ];

        /** @var string $cat_slug */
        if ($cat_slug) {
            $cat_id            = explode('|', $cat_slug);
            $args['tax_query'] =
                [
                    [
                        'taxonomy' => 'product_cat',
                        'field'    => 'slug',
                        'terms'    => $cat_id
                    ]
                ];
        }

        /** @var string $picture_size */
        if ($picture_size == 'fixed') {
            $featured_image_size = 'related-img';
        } else {
            $featured_image_size = 'full';
        }

        $products     = new \WP_Query($args);
        $product_list = '';

        if ($products->have_posts()) {
            while ($products->have_posts()) {
                $products->the_post();

                $image = $price_tag = $terms = '';

                // Title on rollover layout
                /** @var string $carousel_layout */
                if ($carousel_layout == 'title_on_rollover') {
                    $image = ong_render_first_featured_image_markup(
                        get_the_ID(),
                        $featured_image_size,
                        get_permalink(get_the_ID()),
                        true,
                        $show_price,
                        $show_buttons,
                        $show_cats
                    );
                    // Title below image layout
                } else {
                    $image = ong_render_first_featured_image_markup(
                        get_the_ID(),
                        $featured_image_size,
                        get_permalink(get_the_ID()),
                        true,
                        false,
                        $show_buttons,
                        'disable',
                        'disable'
                    );

                    // Get the post title
                    $image .= sprintf(
                        '<h4 class="%s"><a href="%s" target="%s">%s</a></h4>',
                        'glasses--slider-info',
                        get_permalink(get_the_ID()),
                        '_self',
                        get_the_title()
                    );

                    $image .= '<div class="ong-carousel-meta">';

                    // Get the terms
                    /** @var TYPE_NAME $show_cats */
                    if ($show_cats == 'enable') {
                        $image .= get_the_term_list(get_the_ID(), 'product_cat', '', ', ', '');
                    }

                    // Check if we should render the woo product price
                    /** @var TYPE_NAME $show_price */
                    if ($show_price) {
                        ob_start();
                        wc_get_template('loop/price.php');
                        $image .= sprintf('<div class="ong-carousel-price">%s</div>', ob_get_clean());
                    }

                    $image .= '</div>';
                }

                $product_list .= sprintf('<div>%s</div>', $image);
            }
        }

        $html = sprintf('<div class="%s">', 'glasses-carousel hide-for-small-only');
        $html .= sprintf('<div class="%s">', 'owl-carousel glasses-top middle--row');
        $html .= $product_list;
        // Check if navigation should be shown
        /** @var string $show_nav */
        if ($show_nav == 'yes') {
            $html .= sprintf(
                '<div %s><span %s></span><span %s></span></div>',
                'ong-carousel-nav',
                'ong-nav-prev',
                'ong-nav-next'
            );
        }
        $html .= '</div>';
        $html .= '</div>';
    }

    return $html;

}

if (!function_exists('ong_render_first_featured_image_markup')) {
    /**
     * Render the full markup of the first featured image, incl. image wrapper and rollover
     *
     * @param  string  $post_id                  ID of the current post
     * @param  string  $post_featured_image_size Size of the featured image
     * @param  string  $post_permalink           Permalink of current post
     * @param  boolean $display_post_title       Set to yes to show post title on rollover
     * @param  boolean $display_post_categories  Set to yes to show post categories on rollover
     *
     * @return string Full HTML markup of the first featured image
     **/
    function ong_render_first_featured_image_markup(
        $post_id,
        $post_featured_image_size = '',
        $post_permalink = '',
        $display_placeholder_image = false,
        $display_woo_price = true,
        $display_woo_buttons = false,
        $display_post_categories = 'default',
        $display_post_title = 'default',
        $type = '',
        $gallery_id = ''
    ) {

        global $product;

        // Add a class for fixed image size, to restrict the image rollovers to the image width
        $image_size_class = '';
        if ($post_featured_image_size != 'full') {
            $image_size_class = ' ong-image-size-fixed';
        }

        $html = '<div class="ong-image-wrapper' . $image_size_class . '" aria-haspopup="true">';
        // Get the featured image
        ob_start();
        // If there is a featured image, display it
        if (has_post_thumbnail($post_id)) {
            echo get_the_post_thumbnail($post_id, $post_featured_image_size);

            // If there is no featured image setup a placeholder
        } elseif ($display_placeholder_image) {
            /**
             * avada_placeholder_image hook
             *
             * @hooked avada_render_placeholder_image - 10 (outputs the HTML for the placeholder image)
             */
            do_action('avada_placeholder_image', $post_featured_image_size);
        }
        $featured_image = ob_get_clean();


        $html .= sprintf('<a href="%s">%s</a>', $post_permalink, $featured_image);

        $html .= '<div class="slider-card-section"><span class="slider-card-title">'.$product->get_title();
        $html .= '</span><span class="slider-card-price">'. $product->get_price_html() . '<span></div>';

        $html .= '</div>';

        return $html;
    }
}
