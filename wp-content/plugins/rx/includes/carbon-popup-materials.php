<?php

if (!function_exists('carbon_get_comment_meta')) {
	return;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'Rx Options')
    ->set_page_parent('themes.php')
    ->add_tab('LENS TYPE', [
        Field::make('rich_text', 'i_popup_for_rx_lens_type_single_vision', 'Single Vision')->set_width(50),
	Field::make('rich_text', 'i_popup_for_rx_lens_type_bifocal', 'Bifocal')->set_width(50),
	Field::make('rich_text', 'i_popup_for_rx_lens_type_nolinebifocal', 'No Line Bifocal')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_type_progressive', 'Progressive')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_type_impact_resistant', 'Impact Resistant')->set_width(50),
    ])
    ->add_tab('ENTER YOUR PRESCRIPTION', [
        Field::make('rich_text', 'i_popup_for_rx_eyp_sphere', 'SPHERE')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_eyp_cylinder', 'CYLINDER')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_eyp_axis', 'AXIS')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_eyp_add', 'ADD')->set_width(50),

        Field::make("separator", "i_popup_for_rx_eyp_s1", "Pupillary Distance block"),
        Field::make('rich_text', 'i_popup_for_rx_eyp_pupillary_distance_pd', 'Pupillary Distance (PD)')->set_width(100),
        Field::make('rich_text', 'i_popup_for_rx_eyp_prism_value', 'Prism Value')->set_width(100),
        Field::make('rich_text', 'i_popup_for_rx_eyp_add_prism', 'Add Prism')->set_width(100),

        Field::make("separator", "i_popup_for_rx_eyp_s2", "Add Prism block"),
        Field::make('rich_text', 'i_popup_for_rx_eyp_vertical', 'Vertical')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_eyp_base_direction', 'Base Direction')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_eyp_horizontal', 'Horizontal')->set_width(50),
    ])
    ->add_tab('PACKAGES', [
        Field::make("separator", "i_popup_for_rx_package_separator", "For all hints in the packages")
            ->set_width(100)->help_text('
                <p><b>%meterial%</b> - name of the material in the package</i></p>'),
        Field::make('rich_text', 'i_popup_for_rx_package_for_other', 'For other packages')->set_width(100),
        Field::make('rich_text', 'i_popup_for_rx_package_indoors', 'Indoors')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_package_sun_protection', 'Sun Protection')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_package_indoors_outdoors', 'Indoors/Outdoors')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_package_office_blue_light_defense', 'Office/Blue light Defense')->set_width(50),
    ])
    ->add_tab('LENS TINT OPTION', [
        Field::make('rich_text', 'i_popup_for_rx_lens_tint_opt_lear_lenses', 'Clear Lenses')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_tint_opt_tinted_lenses', 'Tinted Lenses')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_tint_opt_light_responsive', 'Light Responsive')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_tint_opt_polarized', 'Polarized')->set_width(50),
    ])
    ->add_tab('LENS MATERIAL & THICKNESS', [
        Field::make('rich_text', 'i_popup_for_rx_lens_material_150_standard', '1.50 Standard')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_pc_advanced', 'PC Advanced')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_16_thin_lenses', '1.6 Thin Lenses')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_167_super_thin', '1.67 Super Thin')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_174_ultra_thin', '1.74 Ultra Thin')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_trivex_hd_performance', 'Trivex HD Performance')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_lens_material_157_standard', '1.57 Standard')->set_width(50),
    ])
    ->add_tab('LENS COATING', [
        Field::make("checkbox", "is_show_diamond_anti_glare_coating_rx", "Show Diamond Anti-Glare Coating on Rx")
            ->set_option_value('yes')->set_width(50),
        Field::make("checkbox", "is_show_diamond_anti_glare_coating_review_order_rx", "Show Diamond Anti-Glare Coating (REVIEW ORDER) on Rx")
            ->set_option_value('yes')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_three_one_three_in_one', 'Three in One')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_three_one_regular_anti_glare', 'Regular Anti-Glare')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_three_one_premium_anti_glare', 'Premium Anti-Glare')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_three_one_computer_anti_glare', 'Computer Anti-Glare')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_three_one_easy_clean_lenses', 'Easy Clean Lenses')->set_width(50),
    ])
    ->add_tab('Special Processing', [
        Field::make('rich_text', 'i_popup_for_rx_special_processing_next_day_rush_service', 'Next Day Rush Service')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_special_processing_3_days_rush_service', '3 Days Rush Service')->set_width(50),
        Field::make('rich_text', 'i_popup_for_rx_special_processing_diamond', 'Diamond in REVIEW ORDER')->set_width(100),
    ])
    ->add_tab('Promote coupons on Rx', [
        Field::make("checkbox", "is_show_promote_coupons_rx", "Show Promote coupons on Rx")
            ->set_option_value('yes')
            ->help_text('
                <p><b>%coupon_amount%</b> - Coupon mount</i> (necessary for the script)</p>
                <p><b>%coupon_code%</b> - Coupon Code</i> (necessary for the script)</p>
                '),
        Field::make("checkbox", "is_show_auto_coupons_rx", "Automatically apply coupons in cart (Logic as in RX)")
             ->set_option_value('yes'),
        
        Field::make('textarea', 'promote_coupons_cyber30_message', 'Promote coupons for Frame')
//        Field::make('text', 'promote_coupons_get25_code', 'Coupon for Frame')->set_width(20)
            ->help_text('<a href="/wp-admin/edit.php?s=cyber30&post_status=all&post_type=shop_coupon&action=-1&m=0&coupon_type&paged=1&action2=-1">View Coupon - cyber30</a>'),

        Field::make('textarea', 'promote_coupons_dcm30_message', 'Promote coupons for Frame')
//        Field::make('text', 'promote_coupons_get25_code', 'Coupon for Frame')->set_width(20)
            ->help_text('<a href="/wp-admin/edit.php?s=dcm30&post_status=all&post_type=shop_coupon&action=-1&m=0&coupon_type&paged=1&action2=-1">View Coupon - dcm30</a>'),


        Field::make('textarea', 'promote_coupons_lens25_message', 'Promote coupons for Lens')
//        Field::make('text', 'promote_coupons_lens25_code', 'Coupon for Lens')->set_width(20)
             ->help_text('<a href="/wp-admin/edit.php?s=lens25&post_status=all&post_type=shop_coupon&action=-1&m=0&coupon_type&paged=1&action2=-1">View Coupon - lens25</a>'),

        Field::make('textarea', 'promote_coupons_get25_message', 'Promote coupons for Frame')
//        Field::make('text', 'promote_coupons_get25_code', 'Coupon for Frame')->set_width(20)
            ->help_text('<a href="/wp-admin/edit.php?s=get25&post_status=all&post_type=shop_coupon&action=-1&m=0&coupon_type&paged=1&action2=-1">View Coupon - get25</a>'),



        Field::make('textarea', 'promote_coupons_getbrand_message', 'Promote coupons for Designer')
//        Field::make('text', 'promote_coupons_getbrand_code', 'Coupon for Designer')->set_width(20)
             ->help_text('<a href="/wp-admin/edit.php?s=getbrand&post_status=all&post_type=shop_coupon&action=-1&m=0&coupon_type&paged=1&action2=-1">View Coupon - getbrand</a>'),
    ]);
