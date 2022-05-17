<?php

add_filter('woocommerce_admin_reports', function ($reports) {

    $reports['club'] = [
        'title' => __('ONG Club', 'woocommerce'),
        'reports' => [
            "use_coupons" => [
                'title' => __('Use coupons', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
            "use_coupons_club" => [
                'title' => __('Club. Use coupons', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ]
        ]
    ];

    return $reports;
});


add_filter('woocommerce_admin_reports', function ($reports) {

    $reports['sales'] = [
        'title' => __('Sales Reports', 'woocommerce'),
        'reports' => [
            "sales_by_package" => [
                'title' => __('Sales by Package', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
            "sales_by_prescription_type" => [
                'title' => __('Sales by Prescription Type', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
            "sales_by_brand" => [
                'title' => __('Sales by Brand', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
            "sales_by_tint" => [
                'title' => __('Sales by Tint', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
            "stuck_in_processing" => [
                'title' => __('Orders Stuck In Processing', 'woocommerce'),
                'description' => '',
                'hide_title' => true,
                'callback' => ['Ong_Addon_Reports', 'get_report']
            ],
        ]
    ];

    return $reports;
});

if (is_admin()) {
//    add_action('wp_ajax_ong_picker', ['Ong_Addon_Reports_sales_by_prescription_type', 'ong_picker']);
//    add_action('wp_ajax_nopriv_ong_picker', ['Ong_Addon_Reports_sales_by_prescription_type', 'ong_picker']);

    add_action('admin_enqueue_scripts', function () {
        wp_register_script('charts', 'https://www.gstatic.com/charts/loader.js', [], '', false);
    });

}
