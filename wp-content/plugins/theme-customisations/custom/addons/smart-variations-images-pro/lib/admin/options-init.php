<?php

if (!class_exists('Redux')) {
    return;
}

function sviremoveDemoModeLink() { // Be sure to rename this function to something more unique
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2);
    }
    if (class_exists('ReduxFrameworkPlugin')) {
        remove_action('admin_notices', array(ReduxFrameworkPlugin::get_instance(), 'admin_notices'));
    }
}

add_action('init', 'sviremoveDemoModeLink');

// This is your option name where all the Redux data is stored.
$opt_name = "woosvi_options";

$args = array(
    'opt_name' => 'woosvi_options',
    'use_cdn' => TRUE,
    'dev_mode' => false,
    'forced_dev_mode_off' => false,
    'display_name' => 'SMART VARIATIONS PRO',
    'display_version' => SL_VERSION,
    'page_slug' => 'woocommerce_svi',
    'page_title' => 'Smart Variations Images PRO for WooCommerce',
    'update_notice' => TRUE,
    'admin_bar' => TRUE,
    'menu_type' => 'submenu',
    'menu_title' => 'SVI PRO',
    'page_parent' => 'woocommerce',
    'customizer' => FALSE,
    'default_mark' => '*',
    'hints' => array(
        'icon' => 'el el-adjust-alt',
        'icon_position' => 'right',
        'icon_color' => 'lightgray',
        'icon_size' => 'normal',
        'tip_style' => array(
            'color' => 'light',
        ),
        'tip_position' => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect' => array(
            'show' => array(
                'duration' => '500',
                'event' => 'mouseover',
            ),
            'hide' => array(
                'duration' => '500',
                'event' => 'mouseleave unfocus',
            ),
        ),
    ),
    'output_tag' => TRUE,
    'cdn_check_time' => '1440',
    'page_permissions' => 'manage_woocommerce',
    'save_defaults' => TRUE,
    'database' => 'options',
    'transient_time' => '3600',
    'network_sites' => TRUE,
);

Redux::setArgs($opt_name, $args);

/*
 * ---> END ARGUMENTS
 */


/*
 *
 * ---> START SECTIONS
 *
 */

Redux::setSection($opt_name, array(
    'title' => __('Settings', 'wc_svi'),
    'id' => 'general',
    'desc' => __('Basic field with no subsections.', 'wc_svi'),
    'icon' => 'el el-home',
    'fields' => array(
        array(
            'id' => 'default',
            'type' => 'switch',
            'title' => __('Enable SVI', 'wc_svi'),
            'desc' => __('Deactivate SVI from running on your site.', 'wc_svi'),
            'on' => __('Deactivate', 'wc_svi'),
            'off' => __('Activate', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'lightbox',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Activate Lightbox', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            //'desc' => __('Items set with a fold to this ID will hide unless this is set to the appropriate value.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'svicart',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Cart Image', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Display choosen variation image in cart/checkout instead of default Product image.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'swselect',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Swap Select', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Swap Variation select on thumbnail click.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'variation_swap',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Variation Swap', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('All selects trigger swap images. Don\'t have to wait for select combination.', 'wc_svi'),
            'default' => false,
        ),
    )
));

Redux::setSection($opt_name, array(
    'title' => __('Slider', 'wc_svi'),
    'id' => 'slider-subsection',
    //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'slider',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Activate Slider', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            //'desc' => __('Activate slider', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'slider-navigation',
            'type' => 'switch',
            'required' => array(
                array('slider', '=', '1'),
                array('lens', '=', '0')
            ),
            'title' => __('Navigation', 'wc_svi'),
            'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
            'desc' => __('Incompatible with Magnifier Lens.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'title' => __('Thumbnail Position', 'wc_svi'),
            'subtitle' => __('Select thumnails position. Bottom, Left or right.', 'wc_svi'),
            'desc' => __('Left and Right positions not available on mobile phones, falls back to horizontal.', 'wc_svi'),
            'id' => 'slider-position',
            'required' => array('slider', '=', '1'),
            'default' => 0,
            'type' => 'image_select',
            'options' => array(
                0 => ReduxFramework::$_url . '/assets/img/sviBottom.png',
                1 => ReduxFramework::$_url . '/assets/img/sviLeft.png',
                2 => ReduxFramework::$_url . '/assets/img/sviRight.png',
            )
        ),
        array(
            'title' => __('Force Mobile Thumbnail Position', 'wc_svi'),
            'desc' => __('If activated, selected thumbnail position will be respected in mobile phones.', 'wc_svi'),
            //'desc' => __('Force .', 'wc_svi'),
            'id' => 'force-slider-position',
            'required' => array(
                array('slider', '=', '1'),
                array('slider-position', '!=', '0'),
            ),
            'default' => false,
            'type' => 'switch',
        ),
        array(
            'id' => 'slider-navigation-info',
            'type' => 'info',
            'style' => 'warning',
            'required' => array(
                array('slider', '=', '1'),
                array('lens', '=', '1')
            ),
            'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
            'desc' => __('Slider navigation not available with Magnifier Lens Active.', 'wc_svi')
        ),
    )
));


Redux::setSection($opt_name, array(
    'title' => __('Magnifier Lens', 'wc_svi'),
    'id' => 'lens-subsection',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'lens-navigation-info',
            'type' => 'info',
            'style' => 'warning',
            'required' => array('default', '=', '0'),
            //'subtitle' => __('Add arrow navigation to main image.', 'wc_svi'),
            'desc' => __('Magnifier Lens is disabled on mobile phones.', 'wc_svi')
        ),
        array(
            'id' => 'lens',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Activate Magnifier Lens', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            //'desc' => __('Disabled on mobile phones.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'title' => __('Lens Format', 'wc_svi'),
            //'desc' => __('Select thumnails position. Bottom, Left.',  'wc_svi'),
            'id' => 'lens-type',
            'required' => array('lens', '=', '1'),
            'default' => 'round',
            'type' => 'image_select',
            'options' => array(
                'round' => ReduxFramework::$_url . '/assets/img/sviRound.png',
                'square' => ReduxFramework::$_url . '/assets/img/sviSquare.png',
            )
        ),
        array(
            'id' => 'lens-size',
            'type' => 'text',
            'required' => array('lens', '=', '1'),
            'title' => __('Lens Size', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            //'desc' => __('Number of thumbnails to be displayed, min:1 | max: 10.', 'wc_svi'),
            'validate' => 'preg_replace',
            'preg' => array(
                'pattern' => '/[1-9]',
                'replacement' => 'Only numbers'
            ),
            'default' => '150',
        ),
        array(
            'title' => __('Zoom Type', 'wc_svi'),
            'id' => 'lens-zoomtype',
            'default' => 'lens',
            'type' => 'select',
            'required' => array('lens', '=', '1'),
            'options' => array(
                'lens' => __('Lens', 'wc_svi'),
                'window' => __('Window', 'wc_svi'),
                'inner' => __('Inner', 'wc_svi'),
            ),
        ),
        array(
            'id' => 'lens-scrollzoom',
            'type' => 'switch',
            'required' => array('lens', '=', '1'),
            'title' => __('Zoom Effect', 'wc_svi'),
            'desc' => __('Allows Zoom with mouse scroll.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'lens-fade',
            'type' => 'switch',
            'required' => array('lens', '=', '1'),
            'title' => __('Fade Effect', 'wc_svi'),
            'default' => false,
        ),
    )
));


Redux::setSection($opt_name, array(
    'title' => __('Thumbails', 'wc_svi'),
    'id' => 'thumbs-subsection',
    //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'columns',
            'type' => 'text',
            'required' => array('default', '=', '0'),
            'title' => __('Thumbnail Columns', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Number of thumbnails to be displayed, min:1 | max: 10.', 'wc_svi'),
            'validate' => 'numeric',
            'default' => '4',
        ),
        array(
            'id' => 'hide_thumbs',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Hide Thumbnails', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Thumnails will be hidden until a variation as been selected.', 'wc_svi'),
            'default' => false,
        ),
    )
));

Redux::setSection($opt_name, array(
    'title' => __('Layout Fixes', 'wc_svi'),
    'id' => 'fixes-subsection',
    //'desc' => __('For full documentation on validation, visit: ', 'wc_svi') . '<a href="//docs.reduxframework.com/core/the-basics/required/" target="_blank">docs.reduxframework.com/core/the-basics/required/</a>',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'sviforce',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Force SVI', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Try this option if layout seems broken, if it doesnt work please use a Custom class.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'sviforce_image',
            'type' => 'switch',
            'required' => array('default', '=', '0'),
            'title' => __('Remove Image class', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Some theme force styling on image class that may break the layout.', 'wc_svi'),
            'default' => false,
        ),
        array(
            'id' => 'custom-class',
            'type' => 'text',
            'required' => array('default', '=', '0'),
            'title' => __('Custom Class', 'wc_svi'),
            //'subtitle' => __('Also called a "fold" parent.', 'wc_svi'),
            'desc' => __('Insert custom css class(es) to fit your theme needs.', 'wc_svi'),
        ),
    )
));

Redux::setSection($opt_name, array(
    'title' => __('Support', 'wc_svi'),
    'id' => 'info-svi',
    'desc' => 'All support for my plugins are provided as a free service at <a href="http://www.rosendo.pt" target="_blank">www.rosendo.pt</a>.<br>
Purchasing an addon from this site does not gain you priority over response times on the support system.<br>

<br>
<b>Please note that WordPress has a big history of conflicts between plugins.</b><br>
<br>
The support works, <b>Lisbon, Portugal time zone</b> form <b>9am to 6pm</b>.<br>
I\'m not here full-time so please be patient, I will try my best to help you out as much as I can.<br>
<br>
<h2>Steps:</h2>
<ul>
<li>- Go to <a href="http://www.rosendo.pt" target="_blank">www.rosendo.pt</a> and login</li>
<li>- On the right sidebar you will see an option saying <b><a href="http://www.rosendo.pt/submit-ticket/" target="_blank">Submit Ticket</a></b></li>
<li>- Please supply me with information such <b>credentials</b> to your <b>wp-admin</b> and optionally <b>direct FTP access to my plugin</b>.</li>
</ul>
<br>
<a href="http://www.rosendo.pt/terms-conditions/">Terms & Conditions</a>
',
));

/*
     * <--- END SECTIONS
     */
