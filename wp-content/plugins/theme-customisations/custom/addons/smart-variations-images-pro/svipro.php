<?php

/*
  Plugin Name: Smart Variations Images Pro
  Plugin URI: http://www.rosendo.pt
  Description: This is a WooCommerce extension plugin, that allows the user to add any number of images to the product images gallery and be used as variable product variations images in a very simple and quick way, without having to insert images p/variation.
  Author: David Rosendo
  Version: 3.0.5
  Author URI: http://www.rosendo.pt
 */

if (!defined('ABSPATH'))
    exit; // Exit if accessed directly

if (!class_exists('woocommerce_svi')) {

    class woocommerce_svi {

        public $api_url;
        public $plugin;
        public $current_version;

        /**
         * init
         *
         * @access public
         * @since 1.0.0
         * @return bool
         */
        function __construct() {

            define('SL_VERSION', '3.0.5');

            add_action('init', array($this, 'load_plugin_textdomain'));
            add_action('admin_init', array($this, 'activate'));

            if ($this->is_woocommerce_active()) {
                if (is_admin()) {
                    include_once( 'lib/class-svi-admin.php' );
                }
                include_once( 'lib/Mobile_Detect.php' );
                include_once( 'lib/class-svi-frontend.php' );
            } else {
                add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            }

            return true;
        }

        public function activate() {

            // Check fro free SVI and Deactivate it.
            if (is_plugin_active('smart-variations-images/svi.php')) {
                deactivate_plugins('smart-variations-images/svi.php');
            }
        }

        /**
         * load the plugin text domain for translation.
         *
         * @since 1.0.0
         * @return bool
         */
        public function load_plugin_textdomain() {
            $locale = apply_filters('svi_locale', get_locale(), 'woocommerce-svi');

            load_plugin_textdomain('wc-svi', false, dirname(plugin_basename(__FILE__)) . '/languages');

            return true;
        }

        /**
         * SVI fallback notice.
         *
         * @return string
         */
        public function woocommerce_missing_notice() {
            echo '<div class="error"><p>' . sprintf(__('Smart Variations Images requires WooCommerce to be installed and active. You can download %s here.', 'wc-svi'), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">WooCommerce</a>') . '</p></div>';
        }

        function is_woocommerce_active() {
            if (class_exists('woocommerce') && WC()->version >= 2.1) {
                return true;
            }
            return false;
        }

    }

    add_action('plugins_loaded', 'svi_init', 0);

    /**
     * init function
     *
     * @package  woocommerce_svi
     * @since 1.0.0
     * @return bool
     */
    function svi_init() {
        new woocommerce_svi();

        return true;
    }

    /**
     * print array
     */
    function svipre($arg) {
        echo "<pre>" . print_r($arg, true) . "</pre>";
    }

}

add_action('init', 'svi_init', - 1);
