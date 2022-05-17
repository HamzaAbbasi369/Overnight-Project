<?php
/**
 * Plugin Name:       ONG Theme Customisations
 * Description:       A handy little plugin to contain your theme customisation snippets.
 * Plugin URI:        http://github.com/woothemes/theme-customisations
 * Version:           20180621
 * Author:            Vision Care Services LLC
 * Requires at least: 3.0.0
 * Tested up to:      4.4.2
 *
 * @package Theme_Customisations
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Main Theme_Customisations Class
 *
 * @class      Theme_Customisations
 * @package    Theme_Customisations
 */
final class Theme_Customisations
{

    /**
     * Set up the plugin
     */
    public function __construct()
    {
        add_action('init', [$this, 'setup'], - 1);
        define('THEME_CUSTOMIZATION_URL', plugins_url(basename(plugin_dir_path(__FILE__)), basename(__FILE__)));
        define('THEME_CUSTOMIZATION_PATH', untrailingslashit(plugin_dir_path(__FILE__)));
        define('THEME_CUSTOMIZATION_VERSION', '20180621');
        require_once('custom/functions.php');
    }

    /**
     * Setup all the things
     */
    public function setup()
    {

        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts'], 999);
        add_filter('template_include', [$this, 'template'], 11);
        add_filter('wc_get_template', [$this, 'wc_get_template'], 11, 5);
        add_filter('wc_get_template_part', [$this, 'wc_get_template_part'], 11, 3);

        remove_filter('the_content', 'wpautop');
        remove_filter('the_content', 'wptexturize');

        remove_filter('the_excerpt', 'wpautop');
        remove_filter('the_excerpt', 'wptexturize');

	    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

	    add_action( 'woocommerce_before_main_content', ['Theme_Customisations', 'ong_output_content_wrapper'], 10 );
	    add_action( 'woocommerce_after_main_content', ['Theme_Customisations', 'ong_output_content_wrapper_end'], 10 );


        require_once('custom/includes/base_functions.php');
    }

    public static function enqueueScripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';//;

        Ong_Template::enqueueStyle(
            'custom-css',
            plugins_url('/custom/style' . $suffix . '.css', __FILE__)
        );

        wp_enqueue_script(
            'wp-js-hooks',
            WP_CONTENT_URL . '/plugins/wp-js-hooks/'.(SCRIPT_DEBUG ? 'src' : 'dist').'/event-manager'. $suffix .'.js',
            [],
            '1.0.0',
            true
        );

        Ong_Template::enqueueScript(
            'custom-js',
	    //plugins_url('/custom/custom' . $suffix . '.js', __FILE__),
	    plugins_url('/custom/custom.js', __FILE__),
            ['ong.main','jquery','wp-js-hooks']
        );
    }

    /**
     * Look in this plugin for template files first.
     * This works for the top level templates (IE single.php, page.php etc). However, it doesn't work for
     * template parts yet (content.php, header.php etc).
     *
     * Relevant trac ticket; https://core.trac.wordpress.org/ticket/13239
     *
     * @param  string $template template string.
     *
     * @return string $template new template string.
     */
    public function template($template)
    {
        if (file_exists(untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/' . basename($template))) {
            $template = untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/' . basename($template);
        }

	    if (file_exists(untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/woocommerce/' . basename($template))) {
		    $template = untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/woocommerce/' . basename($template);
	    }

        return $template;
    }

    /**
     * Look in this plugin for WooCommerce template overrides.
     *
     * For example, if you want to override woocommerce/templates/cart/cart.php, you
     * can place the modified template in <plugindir>/custom/templates/woocommerce/cart/cart.php
     *
     * @param string $located       is the currently located template, if any was found so far.
     * @param string $template_name is the name of the template (ex: cart/cart.php).
     *
     * @return string $located is the newly located template if one was found, otherwise
     *                         it is the previously found template.
     */
    public function wc_get_template($located, $template_name, $args, $template_path, $default_path)
    {
        $plugin_template_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/woocommerce/' . $template_name;

        if (file_exists($plugin_template_path)) {
            $located = $plugin_template_path;
        }

        return $located;
    }


    /**
     * @param $located
     * @param $slug
     * @param $name
     *
     * @return null|string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function wc_get_template_part($located, $slug, $name)
    {
        $plugin_template_path = untrailingslashit(plugin_dir_path(__FILE__)) . '/custom/templates/woocommerce';

        $template = null;

        // Get default slug-name.php
        if ($name && file_exists($plugin_template_path . "/{$slug}-{$name}.php")) {
            $template = $plugin_template_path . "/{$slug}-{$name}.php";
        }

        if (!$template && file_exists($plugin_template_path . "/{$slug}.php")) {
            $template = $plugin_template_path . "/{$slug}.php";
        }

        if ($template) {
            $located = $template;
        }

        return $located;
    }

	public static function ong_output_content_wrapper()
	{
		wc_get_template( 'global/wrapper-start.php' );
	}

	public static function ong_output_content_wrapper_end()
	{
		wc_get_template( 'global/wrapper-end.php' );
	}

} // End Class

/**
 * The 'main' function
 *
 * @return void
 */
function theme_customisations_main()
{
    new Theme_Customisations();
}

/**
 * Initialise the plugin
 */
add_action('plugins_loaded', 'theme_customisations_main');


