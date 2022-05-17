<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

/**
ONG Add Coupon Custom Fields
 */

if (function_exists("register_field_group")) {
    register_field_group([
        'id' => 'acf_for-coupon',
        'title' => 'for coupon',
        'fields' => [
            [
                'key' => 'field_58ac639333ad6',
                'label' => 'Display on the cart',
                'name' => 'for_cart',
                'type' => 'true_false',
                'message' => '',
                'default_value' => 0,
            ],
            [
                'key' => 'field_58ac639300ad6',
                'label' => 'Display on the main',
                'name' => 'for_main',
                'type' => 'true_false',
                'message' => '',
                'default_value' => 0,
            ],
            [
                'key' => 'field_58ac639610ad6',
                'label' => 'Apply to Frame',
                'name' => 'apply_to_frame',
                'type' => 'true_false',
                'message' => '',
                'default_value' => COUPON_APPLY_DEFAULTS & APPLY_TO_FRAME,
            ],
            [
                'key' => 'field_58ac639620ad6',
                'label' => 'Apply to Lenses',
                'name' => 'apply_to_lenses',
                'type' => 'true_false',
                'message' => '',
                'default_value' => COUPON_APPLY_DEFAULTS & APPLY_TO_LENSES,
            ],
            [
                'key' => 'field_58ac639630ad6',
                'label' => 'Apply to Rush Fee',
                'name' => 'apply_to_rush_fee',
                'type' => 'true_false',
                'message' => '',
                'default_value' => COUPON_APPLY_DEFAULTS & APPLY_TO_RUSH_FEE,
            ],
            [
                'key' => 'field_58ad67deb2aec',
                'label' => 'Block with html',
                'name' => 'div_html',
                'type' => 'textarea',
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
            ],
            [
                'key' => 'field_58ad67deb2wed',
                'label' => 'Block with CSS',
                'name' => 'div_css',
                'type' => 'textarea',
                'default_value' => '',
                'toolbar' => 'full',
                'media_upload' => 'yes',
            ],
//            [
//                'key' => 'field_58ac63fc00ad7',
//                'label' => 'Page url coupon',
//                'name' => 'page_url_coupon',
//                'type' => 'text',
//                'default_value' => '',
//                'placeholder' => '',
//                'prepend' => '',
//                'append' => '',
//                'formatting' => 'html',
//                'maxlength' => '',
//            ],
            [
                'key' => 'field_58ac665ca9e6a',
                'label' => 'Image coupon',
                'name' => 'image_coupon',
                'type' => 'image',
                'save_format' => 'object',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'shop_coupon',
                    'order_no' => 0,
                    'group_no' => 0,
                ],
            ],
        ],
        'options' => [
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => [],
        ],
        'menu_order' => 0,
    ]);
}
