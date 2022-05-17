<?php

const PRODUCT_LINES_DATA = [
    'economy' => [
        'title' => 'Economy',
        /**
         *
         * Brand=Capri, price <=$50 add economy
         */
        'conditions' => [
            'post_type'      => 'product',
            'posts_per_page' => - 1,
            'tax_query'      => [
                [
                    'taxonomy' => 'pa_brands',
                    'field'    => 'slug',
                    'terms'    => 'capri'
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_price',
                    'value'   => 50,
                    'compare' => '<='
                ]
            ],
        ]
    ],
    'premium' => [
        'title' => 'Premium',
        /**
         *
         * Brand=Capri, price>50 add premium
         */
        'conditions' => [
            'post_type'      => 'product',
            'posts_per_page' => - 1,
            'tax_query'      => [
                [
                    'taxonomy' => 'pa_brands',
                    'field'    => 'slug',
                    'terms'    => 'capri'
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_price',
                    'value'   => 50,
                    'compare' => '>'
                ]
            ]

        ]
    ],
    'prestige' => [
        'title' => 'Prestige',
        /**
         *
         * has category designers, price > 100, add prestige.
         */
        'conditions' => [
            'post_type'      => 'product',
            'posts_per_page' => - 1,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'designer'
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_price',
                    'value'   => [100, 5000],
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN'
                ]
            ]

        ]
    ],
    /**
     *
     * has category designers
     */
    'designer' => [
        'title' => 'Designer',
        'conditions' => [
            'post_type'      => 'product',
            'posts_per_page' => - 1,
            'tax_query'      => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'slug',
                    'terms'    => 'designer'
                ],
            ],
            'meta_query'     => [
                [
                    'key'     => '_price',
                    'value'   => [0, 99],
                    'type'    => 'numeric',
                    'compare' => 'BETWEEN'
                ]
            ]

        ]
    ]
];

require_once(dirname(__FILE__) . '/carbon-fields/product_line_options.php');

add_action( 'woocommerce_single_product_summary', 'ong_product_lines_template_single_meta', 45 );
function ong_product_lines_template_single_meta () {
    include ong_locate_template( "template-parts/addons/product-lines-description.php" );
}


add_action('woocommerce_product_additional_information', 'ong_product_lines_woocommerce_product_additional_information' , 5);
function ong_product_lines_woocommerce_product_additional_information ($product) {
    $productlinename = "";
    $terms = get_the_terms($product->get_id(), 'pa_product-line');
    if ($terms) {
        /** @var WP_Term $term */
        foreach ($terms as $term) {
            $productlinename .= "<div class='product-category-only-text'>
                                <p class='item-text' >{$term->name}</p>
                            </div>";
        }
    }
    echo $productlinename;
}
//
//add_action('template_redirect', function() {
//    global $wp_query;
//    if (is_singular( 'product' )) {
//        $product = wc_get_product( $wp_query->post );
//
//
//    }
//}, 50);
add_action('woocommerce_before_single_product_summary', 'ong_content_product_output_product_line_name', 14);
add_action( 'woocommerce_before_shop_loop_item_title',  'ong_content_product_output_product_line_name', 5);

function ong_content_product_output_product_line_name() {
    global $product;

    if (is_a($product, 'WC_Product_Variable')) {
        $productLineName = "";
        $terms           = get_the_terms( $product->get_id(), 'pa_product-line' );

        if ( $terms ) {
            if (is_singular('product') && in_the_loop()) {
                $name = wc_get_loop_prop('name');
                foreach ( $terms as $term ) {
                    $bgColor         = carbon_get_term_meta( $term->term_id, 'color' );
                    if ($name) {
                        $productLineName .= "<div class='product-category-all loop-name-{$name}'  >
                                <p class='item-text'  style='background-color:{$bgColor}' >{$term->name}</p>
                            </div>";
                    } else {
                        $productLineName .= "<div class='product-category'  style='background-color:{$bgColor}' >
                                <p class='item-text'  >{$term->name}</p>
                            </div>";
                    }
                }
            } elseif (is_archive()) {
                $name = wc_get_loop_prop('name');
                foreach ( $terms as $term ) {
                    $bgColor         = carbon_get_term_meta( $term->term_id, 'color' );
                    $productLineName .= "<div class='product-category-all loop-name-{$name}' >
                                <p class='item-text' style='background-color:{$bgColor}'>{$term->name}</p>
                            </div>";
                }
            }
        }
        echo $productLineName;
    }
}
