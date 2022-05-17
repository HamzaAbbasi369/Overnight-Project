<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */




if (!function_exists('ong_get_template_part')) :
    /**
     * Get template part (for templates like the shop-loop).
     *
     * @access public
     *
     * @param mixed  $slug
     * @param string $name (default: '')
     */
    function ong_get_template_part($slug, $name = '')
    {
        $template = '';


        // Get default slug-name.php
        if ($name) {
            $template = ong_locate_template("{$slug}-{$name}.php");
        }

        // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/ong/slug.php
        if (!$template) {
            $template = ong_locate_template("{$slug}.php");
        }

        // Allow 3rd party plugins to filter template file from their plugin.
        $template = apply_filters('ong_get_template_part', $template, $slug, $name);

        if ($template) {
            load_template($template, false);
        }
    }
endif;

if (!function_exists('ong_get_template')) :
    /**
     * Get other templates (e.g. product attributes) passing attributes and including the file.
     *
     * @access public
     *
     * @param string $template_name
     * @param array  $args          (default: array())
     * @param string $template_path (default: '')
     * @param string $default_path  (default: '')
     */
    function ong_get_template($template_name, $args = [], $template_path = '', $default_path = '')
    {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }

        $located = ong_locate_template($template_name, $template_path, $default_path);

        if (!file_exists($located)) {
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $located), '2.1');

            return;
        }

        // Allow 3rd party plugin filter template file from their plugin.
        $located = apply_filters('ong_get_template', $located, $template_name, $args, $template_path, $default_path);

        do_action('ong_before_template_part', $template_name, $template_path, $located, $args);

        include($located);

        do_action('ong_after_template_part', $template_name, $template_path, $located, $args);
    }
endif;

if (!function_exists('ong_get_template_html')) :
    /**
     * @param string $template_name
     * @param array  $args
     * @param string $template_path
     * @param string $default_path
     *
     * @return string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    function ong_get_template_html($template_name, $args = [], $template_path = '', $default_path = '')
    {
        ob_start();
        ong_get_template($template_name, $args, $template_path, $default_path);

        return ob_get_clean();
    }
endif;

if (!function_exists('ong_locate_template')) :
    /**
     * Locate a template and return the path for inclusion.
     *
     * This is the load order:
     *
     *        yourtheme        /    $template_path    /    $template_name
     *        yourtheme        /    $template_name
     *        $default_path    /    $template_name
     *
     * @access public
     *
     * @param string $template_name
     * @param string $template_path (default: '')
     * @param string $default_path  (default: '')
     *
     * @return string
     */
    function ong_locate_template($template_name, $template_path = '', $default_path = '')
    {
        if (!$template_path && defined('THEME_CUSTOMIZATION_PATH')) {
            $template_path = THEME_CUSTOMIZATION_PATH . '/custom/templates/';
        }

        if (!$default_path) {
            $default_path = STYLESHEETPATH . '/';
        }

        $template = '';

        // Get default template/
        if (file_exists($template_path . $template_name)) {
            $template = $template_path . $template_name;
        }

        if (!$template) {
            // Look within passed path within the theme - this second priority.
            $template = locate_template(
                [
                    trailingslashit($default_path) . $template_name,
                    $template_name
                ]
            );
        }

        if (!$template) {
            _doing_it_wrong(__FUNCTION__, sprintf('<code>%s</code> does not exist.', $template), '2.1');

            return;
        }

        // Return what we found.
        return apply_filters('ong_locate_template', $template, $template_name, $template_path);
    }
endif;
