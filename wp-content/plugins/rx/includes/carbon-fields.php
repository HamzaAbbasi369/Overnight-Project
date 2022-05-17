<?php

/** This code will keep the theme working if the carbon-field is disabled */
if (!function_exists('carbon_get_post_meta')) {
    function carbon_get_post_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_the_post_meta')) {
    function carbon_get_the_post_meta($name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_theme_option')) {
    function carbon_get_theme_option($name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_term_meta')) {
    function carbon_get_term_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_get_user_meta')) {
    function carbon_get_user_meta($id, $name, $type = null)
    {
        return false;
    }
}

if (!function_exists('carbon_fields_missing_notice')) {
    function carbon_fields_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf('Theme Customisations add-on named Carbon Fields depends on the last ' .
                                                'version of %s or later to work!', '<a href="https://wordpress.org/plugins/carbon-fie' .
                                                                                   'lds/" target="_blank">' . 'Carbon Fields v1.6.0' . '</a>') . '</p></div>';
    }
}

if (!function_exists('carbon_get_comment_meta')) {
    function carbon_get_comment_meta($id, $name, $type = null)
    {
        return false;
    }

    add_action('admin_notices', 'carbon_fields_missing_notice');
    return;
}

/** This code will keep the theme working if the carbon is disabled */
