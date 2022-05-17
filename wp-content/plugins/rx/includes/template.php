<?php

class Template
{
    public static $styles = [];
    public static $scripts = [];

    /**
     * Register and enqueue a script for use.
     *
     * @uses   wp_enqueue_script()
     * @access private
     *
     * @param  string   $handle
     * @param  string   $path
     * @param  string[] $deps
     * @param  string   $version
     * @param  boolean  $in_footer
     */
    public static function enqueueScript(
        $handle,
        $path = '',
        $deps = ['jquery'],
        $version = WC_RX_VERSION,
        $in_footer = true
    ) {
        if (!in_array($handle, self::$scripts) && $path) {
            self::registerScript($handle, $path, $deps, $version, $in_footer);
        }
        wp_enqueue_script($handle);
    }

    /**
     * Register a script for use.
     *
     * @uses   wp_register_script()
     * @access private
     *
     * @param  string   $handle
     * @param  string   $path
     * @param  string[] $deps
     * @param  string   $version
     * @param  boolean  $in_footer
     */
    public static function registerScript(
        $handle,
        $path,
        $deps = ['jquery'],
        $version = WC_RX_VERSION,
        $in_footer = true
    ) {
        self::$scripts[] = $handle;
        wp_register_script($handle, $path, $deps, $version, $in_footer);
    }

    /**
     * Register and enqueue a styles for use.
     *
     * @uses   wp_enqueue_style()
     * @access private
     *
     * @param  string   $handle
     * @param  string   $path
     * @param  string[] $deps
     * @param  string   $version
     * @param  string   $media
     */
    public static function enqueueStyle(
        $handle,
        $path = '',
        $deps = [],
        $version = WC_RX_VERSION,
        $media = 'all'
    ) {
        if (!in_array($handle, self::$styles) && $path) {
            self::registerStyle($handle, $path, $deps, $version, $media);
        }
        wp_enqueue_style($handle);
    }

    /**
     * Register a style for use.
     *
     * @uses   wp_register_style()
     * @access private
     *
     * @param  string   $handle
     * @param  string   $path
     * @param  string[] $deps
     * @param  string   $version
     * @param  string   $media
     */
    public static function registerStyle(
        $handle,
        $path,
        $deps = [],
        $version = WC_RX_VERSION,
        $media = 'all'
    ) {
        self::$styles[] = $handle;
        wp_register_style($handle, $path, $deps, $version, $media);
    }

    /**
     * Here we overwrite wp function
     * @return bool
     */
    public function in_admin()
    {
        return false;
    }
}
