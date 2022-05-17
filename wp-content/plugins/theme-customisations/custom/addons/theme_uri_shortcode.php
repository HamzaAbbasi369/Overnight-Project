<?php

/* Plugin Name: Theme URI Shortcode */
add_shortcode('theme_uri', 'ong_theme_uri_shortcode_custom');
function ong_theme_uri_shortcode_custom()
{
    $theme_uri = is_child_theme()
        ? get_stylesheet_directory_uri()
        : get_template_directory_uri();

    return trailingslashit($theme_uri);
}
