<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Helper;

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
        $version = ONG_INSTANT_FILTER_PLUGIN_VERSION,
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
        $version = ONG_INSTANT_FILTER_PLUGIN_VERSION,
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
        $version = ONG_INSTANT_FILTER_PLUGIN_VERSION,
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
        $version = ONG_INSTANT_FILTER_PLUGIN_VERSION,
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

    /**
     * @return string
     */
    public function render_product_block()
    {
        $url = $_SERVER['REQUEST_URI']; //for correct urls for add to cart button
        $_SERVER['REQUEST_URI']    = "";
        $GLOBALS['current_screen'] = $this;
        ob_start();
        wc_get_template_part('content', 'product');
        $product_html = ob_get_contents();
        ob_end_clean();
//        echo $product_html;die;
        $words = ["first", "last"];
        foreach ($words as $word) {
            $product_html = str_replace(" $word ", "", $product_html);
            $product_html = str_replace("\"$word ", "\"", $product_html);
            $product_html = str_replace("'$word ", "'", $product_html);
            $product_html = str_replace(" $word\"", "\"", $product_html);
            $product_html = str_replace(" $word'", "'", $product_html);
        }
        $product_html = str_replace("type-product", "type-product om_firstlast", $product_html);

        $_SERVER['REQUEST_URI'] = $url;;

        return $product_html;
    }

    public static function unparse_url($parsed_url) {
        $scheme   = !empty($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host     = !empty($parsed_url['host']) ? $parsed_url['host'] : '';
        $port     = !empty($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        $user     = !empty($parsed_url['user']) ? $parsed_url['user'] : '';
        $pass     = !empty($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
        $pass     = ($user || $pass) ? "$pass@" : '';
        $path     = !empty($parsed_url['path']) ? $parsed_url['path'] : '';
        $query    = !empty($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
        $fragment = !empty($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
        return "$scheme$user$pass$host$port$path$query$fragment";
    }
}
