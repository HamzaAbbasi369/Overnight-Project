<?php

/**
 * Plugin Name: WooCommerce Rx
 * Description: Adds Rx functionality to WooCommerce.
 * Author: Vision Care Services LLC
 * Version: 20190621
 * License: GPLv2 or later
 * Text Domain: woocommerce-rx
 * Domain Path: languages/
 */


if (!defined('ABSPATH')) {
    exit;
}

session_start();
/** ovveride cart totals
* @deprecated
* function woocommerce_cart_totals() {
	* if ( is_checkout() ) {
		* return;
	* }
	* $template = 'woocommerce/cart/cart-totals.php';
	* include $template;
* }
*/

/**
 * WooCommerce Rx main class.
 */
class WcRx
{


    /**
     * Plugin version.
     *
     * @var string
     */
    const VERSION = '20190621';


	const LENS_PACKAGES_CATEGORY = 'lens-packages';
    public static $carbon_container = null;
    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the plugin.
     */
    private function __construct()
    {
        // Load plugin text domain
        add_action('init', [$this, 'load_plugin_textdomain']);
        add_action('init', function(){
	        include_once 'includes/carbon-fields.php';
        }, 999);
        add_action('template_redirect', function(){
            if (is_singular( 'product' )) {
                add_action('wp_enqueue_scripts', [$this, 'scripts']);
            }
        }, 999);

        // Checks with WooCommerce is installed.
        if (defined('WC_VERSION') && version_compare(WC_VERSION, '2.3', '>=')) {
            $this->define_constants();
            $this->includes();
            $this->init_hooks();
            do_action('woocommerce_rx_loaded');
        } else {
            add_action('admin_notices', [$this, 'woocommerce_missing_notice']);
        }
    }

    /**
     * Define WC Constants.
     */
    private function define_constants()
    {
        $this->define('WC_RX_VERSION', self::VERSION);
        $this->define('WC_RX_PLUGIN_FILE', __FILE__);
        $this->define('WC_RX_PLUGIN_BASENAME', plugin_basename(__FILE__));
        $this->define('WC_RX_DEFAULT_PD', '62.00');
        $this->define('WC_RX_META_ATTRIBUTES', [
            '_wdm_user_custom_data' => 'wdm_user_custom_data',
            'Prescription' => 'wdm_user_custom_data_prescription',
            'Lens Package' => 'wdm_user_custom_data_package',
            '_rx_step_id' => 'rx_step_id',
            '_all_lens_data' => '_all_lens_data',
            'price' => 'wdm_package_price_value'
        ]);
        $this->define('WC_RX_POST_SESSION_ATTRIBUTES', [
            'all_data' => 'all_lens_data',
            'user_data' => 'wdm_user_custom_data',
            'user_data_prescription' => 'wdm_user_custom_data_prescription',
            'user_data_lenses' => 'wdm_user_custom_data_package',
            'total_price' => 'wdm_package_price'
        ]);
        $this->define('WC_RX_SESSION_CART_ITEM_DATA', [
            'wdm_user_custom_data' => 'wdm_user_custom_data',
            'wdm_package_price' => 'wdm_package_price_value',
            'wdm_user_custom_data_prescription' => 'wdm_user_custom_data_prescription',
            'wdm_user_custom_data_package' => 'wdm_user_custom_data_package',
            'all_lens_data' => '_all_lens_data',
        ]);
        $this->define('WC_RX_CART_ITEM_DATA_ORDER_ITEM_ATTRIBUTES', [
            'wdm_user_custom_data' => '_wdm_user_custom_data',
            'wdm_user_custom_data_prescription' => 'Prescription',
            'wdm_user_custom_data_package' => 'Lens Package',
            'rx_step_id' => '_rx_step_id',
            '_all_lens_data' => '_all_lens_data',
            'wdm_package_price_value' => '_line_total'
        ]);
    }

    /**
     * Define constant if not already set.
     *
     * @param  string $name
     * @param  mixed $value
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * Includes.
     */
    private function includes()
    {
        include_once 'includes/class-rx-customizer.php';
	    include_once 'includes/class-rx-install.php';
	    include_once 'includes/functions.php';
	    include_once 'includes/template.php';
        include_once 'includes/carbon-popup-materials.php';
        include_once 'includes/groupon.php';
        include_once 'includes/sunglasses.php';
        include_once 'includes/rx-step.php';

        if (get_option('is_show_promote_coupons_rx') === 'yes') {
            include_once 'includes/promote-coupons.php';
        }
//        include_once 'includes/rx-packages/simple-table-manager/init.php';
        include_once 'includes/order-again.php';
        include_once 'includes/change-prescription.php';

    }

    /**
     * Hook into actions and filters.
     */
    private function init_hooks()
    {
        // Plugin install.
        register_activation_hook(__FILE__, ['WcRxInstall', 'install']);

        if (is_admin()) {
            add_action('wp_ajax_rx_form', [$this, 'rx_form']);
            add_action('wp_ajax_nopriv_rx_form', [$this, 'rx_form']);

            add_action('wp_ajax_rx_get_packages', [$this, 'rx_get_packages']);
            add_action('wp_ajax_nopriv_rx_get_packages', [$this, 'rx_get_packages']);

                add_action('wp_ajax_rx_get_preset_packages', [$this, 'rx_get_preset_packages']);
                add_action('wp_ajax_nopriv_rx_get_preset_packages', [$this, 'rx_get_preset_packages']);

                add_action('wp_ajax_rx_tint_color', [$this, 'rx_tint_color']);
                add_action('wp_ajax_nopriv_rx_tint_color', [$this, 'rx_tint_color']);

            add_action('wp_ajax_pdinfo', [$this, 'pdinfo']);
            add_action('wp_ajax_nopriv_pdinfo', [$this, 'pdinfo']);

            /**
             * Add to Cart Code Start
             */

            add_action('wp_ajax_wdm_add_user_custom_data_options', [$this, 'wdm_add_user_custom_data_options_callback']);
            add_action('wp_ajax_nopriv_wdm_add_user_custom_data_options', [$this, 'wdm_add_user_custom_data_options_callback']);

            // hide the _wc_cog_item_cost and _wc_cog_item_total_cost item meta on the Order Items table
            add_filter( 'woocommerce_hidden_order_itemmeta',  [$this, 'hide_order_item_all_data']  );
            add_action('rx_form_main_section', [$this, 'rx_form_main_section_callback']);
            // Add other back-end action hooks here
        } else {
            // moving cart after RX

            /** todo сделать скрытиым и потом отобразить после окончания RX */
            add_action('woocommerce_single_product_summary', [$this, 'ppo']);
            add_action('woocommerce_single_product_summary', [$this, 'ong_color_select'], 12);
            add_action('woocommerce_before_add_to_cart_button', [$this, 'woocommerceGetLenses'], 15);
            add_action('woocommerce_before_single_product_summary', [$this, 'woocommerce_lenses_placeholder'], 2);

            // Add non-Ajax front-end action hooks here
            add_filter('woocommerce_add_cart_item_data', [$this, 'wdm_add_item_data'], 1, 2); //move data to wp session
            add_filter('woocommerce_cart_item_price', [$this, 'woocommerce_cart_item_price'], 1, 3);
            add_filter('rx_cart_item_lens', [$this, 'rx_cart_item_lens'], 1, 3);
            add_filter('rx_cart_item_prescription', [$this, 'rx_cart_item_prescription'], 1, 3);
            add_action('woocommerce_add_order_item_meta', [$this, 'wdm_add_values_to_order_item_meta'], 1, 3);
            add_action('woocommerce_before_cart_item_quantity_zero', [$this, 'wdm_remove_user_custom_data_options_from_cart'], 1, 1);
            add_action('woocommerce_before_calculate_totals', [$this, 'add_custom_price']);


			//cart
            //add_action('woocommerce_checkout_after_customer_details', [$this, 'rx_checkout_review'], 30);
            //remove_action('woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 500);
			//add_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals_rx', 10 );
			//remove_action('woocommerce_cart_collaterals', [$this, 'rx_checkout_cart_totals'], 15 );

            //checkout
            add_action('woocommerce_checkout_after_customer_details', [$this, 'rx_checkout_review'], 30);
            remove_action('woocommerce_checkout_order_review', 'woocommerce_order_review', 10);


        }

        add_shortcode('ipopuprx', [$this, 'i_popup_rx']);// [ipopuprx iblock="i_popup_for_rx_eyp_pupillary_distance_pd"]



        /**************************** In Production Order Status *****************************/
        add_action('init', [$this, 'register_in_production_order_status']);
        add_filter('wc_order_statuses', [$this, 'add_in_production_to_order_statuses']);





    }

	/*
    public function rx_checkout_cart_totals()
    {
        $template = WcRx::get_template('xxx');
        include $template;
    }*/

    /**
     * Get the plugin url.
     * @return string
     */
    public static function plugin_url()
    {
        return untrailingslashit(plugins_url('/', __FILE__));
    }

    public static function template_path()
    {
        return apply_filters('rx_template_path', 'rx_templates/');
    }

    /**
     *
     */
    public static function rx_form()
    {
        WcRx::render_template('rx_form');
    }

    // Add the JS

    /**
     * @param $template
     */
    public static function render_template($template)
    {
        $template = WcRx::get_template($template);

        if (is_wp_error($template)) {
            /** @var WP_Error $template */
            wp_die($template->get_error_message() . '</p> <p><a href="#" onclick="document.location.reload(); return false;">' . __('Try again') . '</a>');
        }
        load_template($template);
        wp_die();
    }

    /**
     * Load a template.
     *
     * Handles template usage so that we can use our own templates instead of the themes.
     *
     * @param $slug
     *
     * @return string
     * @internal param mixed $template
     */
    public static function get_template($slug)
    {
        $find = [];
        $file = $slug . '.php';
        $find[] = $file;
        $find[] = $file;
        $template = '';

        if ($file) {
            $template = locate_template(array_unique($find));

            if (!$template || WC_TEMPLATE_DEBUG_MODE) {
                $template = WcRx::plugin_path() . '/templates/' . $file;
            }
        }
        if (empty($template)) {
            return new WP_Error('not_found', __('Template not found', 'woocommerce_rx'));
        }
        if (!is_readable($template)) {
            return new WP_Error('not_found', __('Template is not readable', 'woocommerce_rx'));
        }

        return apply_filters('wc_rx_get_template', $template, $slug, $find);
    }

    /**
     * Get the plugin path.
     * @return string
     */
    public static function plugin_path()
    {
        return untrailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     *
     */
    public static function rx_get_packages()
    {
        WcRx::render_template('rx_get_packages');
    }

    public static function rx_get_preset_packages()
    {
        WcRx::render_template('rx_get_preset_packages');
    }

    /**
     *
     */
    public static function rx_tint_color()
    {
        WcRx::render_template('rx_tint_color');
    }

    /**
     *
     */
    public static function pdinfo()
    {
        WcRx::render_template('pdinfo');
    }

    /**
     * Return an instance of this class.
     *
     * @return object A single instance of this class.
     */
    public static function getInstance()
    {
        // If the single instance hasn't been set, set it now.
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function preInit()
	{
		include_once 'includes/feature-lens-packages.php';
		include_once 'includes/your-frame.php';
	}

    /**
     * Install method.
     */
    public static function install()
    {
        //do nothing
    }

    public static function is_your_frame()
    {
	$url = wp_get_referer();
	$post_id = url_to_postid($url);
	$product = wc_get_product($post_id);
	return $product->get_sku();
    }

    public function i_popup_rx($atts, $content = null, $tag = '')
    {
        $atts = array_change_key_case((array)$atts, CASE_LOWER);

        $defaults = [
            'iblock' => '',
        ];

        $atts = shortcode_atts($defaults, $atts, $tag);
        return carbon_get_theme_option($atts['iblock']);
    }

    public function hide_order_item_all_data($hidden_fields)
    {
        return array_merge($hidden_fields, ['_all_lens_data', '_wdm_user_custom_data']);
    }

    /**
     *
     */
    public function wpse_182357_enqueue_scripts()
    {
        wp_deregister_script('bootstrap');
        wp_deregister_style('bootstrap');
    }

    /**
     *
     */
    public function scripts()
    {
	    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        Template::enqueueStyle(
            'fx-form-css',
            self::get_assets_url(). 'css/rx_style' . $suffix . '.css'
        );



	    $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';
        wp_enqueue_script(
            'wp-js-hooks',
            WP_CONTENT_URL . '/plugins/wp-js-hooks/'.(SCRIPT_DEBUG ? 'src' : 'dist').'/event-manager'. $suffix .'.js',
            [],
            '1.0.0',
            true
        );
        wp_enqueue_script('rx_jscript', self::get_assets_url() . 'js/rx_jscript.js', ['jquery', 'wp-js-hooks'], WcRx::VERSION, true);
        wp_localize_script('rx_jscript', 'rx_params', [
            // URL to wp-admin/admin-ajax.php to process the request
            'url' => admin_url('admin-ajax.php'),
            'asset' => self::get_assets_url(),
            // generate a nonce with a unique ID "myajax-post-comment-nonce"
            // so that you can check it later when an AJAX request is sent
            'security' => wp_create_nonce('st3MSa8qAEs5cZ4E'),
            'fees' => include('includes/rx_fees.php'),
            'default_pd' => WC_RX_DEFAULT_PD,
            'rx_i_popup_next_day_rush_service' => carbon_get_theme_option('i_popup_for_rx_special_processing_next_day_rush_service'),
            'rx_i_popup_3_days_rush_service' => carbon_get_theme_option('i_popup_for_rx_special_processing_3_days_rush_service')
        ]);
    }

    /**
     * Get assets url.
     *
     * @return string
     */
    public static function get_assets_url()
    {
        return plugins_url('assets/', __FILE__);
    }

    /**
     *
     */
    public function rx_checkout_review()
    {
        $template = WcRx::get_template('draw_checkout_review');
        include $template;
    }

    /**
     *
     */
    public function cw_woocommerce_cart()
    {
        echo '<div id="before_description" style"clear:both;></div>';
        echo '<div id="single-cart">' . do_shortcode('[woocommerce_cart]') . '</div>';
    }

    /**
     *
     */
    public function ppo()
    {
        global $product;
        if ($product->is_type('simple')) {
            remove_action('woocommerce_single_product_summary', 'woocommerce_before_add_to_cart_button', 30);
        }
    }

    /**
     *
     */
    public function ong_color_select()
    {
        ?>
        <div id="ong_color" style=" margin:20px 0px 20px 0px; display:none">
            <p style="font-size:16px; font-weight: bold;">Color:
                <select style="margin-left: 30px" name="Ong_color_select" id="ong_color_select"
                        onchange="SetColor()"></select>
            </p>
        </div>
        <?php
    }

    /**
     *
     */
    public function woocommerceGetLenses()
    {

        $RxLabel = 'Prescription Lenses';
        global $woocommerce, $product;

	    $show_rx = apply_filters('woocommerce_before_add_to_cart_button_show_rx', 1, $product) ;
	    $show_fashion = apply_filters('woocommerce_before_add_to_cart_button_show_fashion', 1, $product) ;
	    $show_cart = apply_filters('woocommerce_before_add_to_cart_button_show_cart', 0, $product) ;
	    $show_submit = 0;

        $price = $product->get_price();
        $regular_price = $product->get_regular_price();
       // echo '<pre>'; print_r($product->get_image()); exit;
       // echo phpinfo();
//$dome = '';
        $dom = simplexml_load_string($product->get_image());
        if ($dom) {
		$pimage = $dom->attributes()->src;
        	$fprice = $price;
        	if (isset($_SESSION['DISCOUNT'])) {
            		$price = $price * $_SESSION['DISCOUNT'];
        	}
	}



        if ($show_rx || $show_fashion || $show_cart) {
            ?>
                <div class="product--lenses-wrapper">
                <label>Lenses</label>
            <?php
        }
        if ($show_rx) {
            ?>
            <div class="product--prescription">
                <button class="hollow button warning" id="order_rx"
                        data-ltype="Rx">Add Prescription lenses</button>
            </div>
            <?php
        }
        if ($show_fashion) {
            ?>
            <div class="product--feshion-lenses">
                <button class="hollow button warning" id="order_fashion"
                        data-ltype="fashion">NONE RX LENSES</button>
            </div>
            <?php
        }
        if ($show_cart) {
            ?>
            <div class="product--feshion-lenses">
                <button onclick="SubmitToCart()" class="hollow button btn-black">Add to Cart</button>
                <input type="hidden" id="total_price" value="<?= $price ?>">
            </div>
            <?php
        }
        if ($show_rx || $show_fashion || $show_cart) {
            echo '</div>';
        }


        wp_localize_script('rx_jscript', 'rx_cart_params', [
            'price' => $price,
            'fprice' => $fprice,
            'frame_regular_price' => $regular_price,
            'show_rx' => $show_rx,
            'show_fashion' => $show_fashion,
            'show_cart' => $show_cart,
        ]);

        if ($show_cart == 1) {
            return;
        }
    }

    /**
     *
     */
    public function woocommerce_lenses_placeholder()
    {
        global $product;
        $description = $product->get_description();
        ?>
        <div id="p_desc" style="display:none">
            <?= $description ?>
        </div>
        <div id="get_lenses" style="width: 100%;">

        </div>
        <?php
    }

    /**
     *
     */
    public function register_in_production_order_status()
    {
        register_post_status('wc-in-production', array(
            'label' => 'In Production',
            'public' => true,
            'exclude_from_search' => false,
            'show_in_admin_all_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop('In Production <span class="count">(%s)</span>', 'In Production <span class="count">(%s)</span>')
        ));
    }

    /**
     * @param $order_statuses
     *
     * @return array
     *
     */
    public function add_in_production_to_order_statuses($order_statuses)
    {
        $new_order_statuses = array();
        // add new order status after processing
        foreach ($order_statuses as $key => $status) {
            $new_order_statuses[$key] = $status;
            if ('wc-processing' === $key) {
                $new_order_statuses['wc-in-production'] = 'In Production';
            }
        }
        return $new_order_statuses;
    }

    /**
     * Load the plugin text domain for translation.
     */
    public function load_plugin_textdomain()
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'woocommerce-rx');

        load_textdomain('woocommerce-rx', trailingslashit(WP_LANG_DIR) . 'woocommerce-rx/woocommerce-rx-' . $locale . '.mo');
        load_plugin_textdomain('woocommerce-rx', false, dirname(plugin_basename(__FILE__)) . '/languages/');
    }

    /**
     * WooCommerce fallback notice.
     *
     * @return string
     */
    public function woocommerce_missing_notice()
    {
        echo '<div class="error"><p>' . sprintf(__('ONG Base depends on the last version of %s or later to work!', 'woocommerce-rx'), '<a href="http://www.woothemes.com/woocommerce/" target="_blank">' . __('WooCommerce 2.3', 'woocommerce-rx') . '</a>') . '</p></div>';
    }

    public function wdm_add_user_custom_data_options_callback()  // fetch AJAX post into custom session
    {
        if (!session_id()) {
            session_start();
        }

        foreach (WC_RX_POST_SESSION_ATTRIBUTES as $post_key => $session_key) {
            $_SESSION[$session_key] = isset($_REQUEST[$post_key]) ? $_REQUEST[$post_key] : '';
        }
        wp_die();
    }

    /**
     * @param $cart_item_data
     * @param $product_id
     * @return array
     */
    function wdm_add_item_data($cart_item_data, $product_id)
    {
        global $woocommerce;
        session_start();
        if (isset($_SESSION['wdm_user_custom_data_prescription'])) {
            if (empty($cart_item_data)) {
                $cart_item_data = [];
            }
            foreach (WC_RX_SESSION_CART_ITEM_DATA as $session_key => $cart_item_data_key) {
                $value = isset($_SESSION[$session_key]) ? $_SESSION[$session_key] : '';
                if ($cart_item_data_key==='_all_lens_data') {
                    $value = json_decode(stripslashes($value), true);
                }
                $cart_item_data[$cart_item_data_key] = $value;
                unset($_SESSION[$session_key]);
            }
        }
        return $cart_item_data;
    }

    function woocommerce_cart_item_price($product_name, $values, $cart_item_key)
    {
        if (array_key_exists('wdm_user_custom_data_prescription', $values)) {
            $values['data']->set_price($values['wdm_package_price_value']);
            $return_string = $values['wdm_user_custom_data_prescription'];
            return stripslashes($return_string); //Deletes characters screening
        } else {
            return $product_name;
        }
    }

    function rx_cart_item_lens($dummy, $values, $cart_item_key)
    {
        if (array_key_exists('wdm_user_custom_data_package',$values)) {
            $return_string = $values['wdm_user_custom_data_package'];
            return stripslashes($return_string); //Deletes characters screening
        } else {
            return $dummy;
        }
    }

    function rx_cart_item_prescription($dummy, $values, $cart_item_key)
    {
        if (array_key_exists('wdm_user_custom_data_prescription',$values)) {
            $return_string = $values['wdm_user_custom_data_prescription'];
            return stripslashes($return_string); //Deletes characters screening
        } else {
            return $dummy;
        }
    }

    function wdm_add_values_to_order_item_meta($item_id, $values, $key)
    {
        global $woocommerce, $wpdb;
        if (isset($values['wdm_user_custom_data'])) {
            foreach (WC_RX_META_ATTRIBUTES as $meta_key => $values_key) {
			    $value = $values[$values_key];
			    if ($meta_key==='price') {
                    continue;
                }
                wc_add_order_item_meta($item_id, $meta_key, $value);
			}
        }
    }

    function wdm_remove_user_custom_data_options_from_cart($cart_item_key)
    {
        global $woocommerce;
        // Get cart
        $cart = $woocommerce->cart->get_cart();
        // For each item in cart, if item is upsell of deleted product, delete it
        foreach ($cart as $key => $values) {
            if ($values['wdm_user_custom_data'] == $cart_item_key)
                unset($woocommerce->cart->cart_contents[$key]);
        }
    }

    function add_custom_price($cart_object)
    {
        foreach ($cart_object->cart_contents as $key => $value) {
            if (array_key_exists('wdm_package_price_value', $value)){
                $value['data']->set_price($value['wdm_package_price_value']);
            }
        }
    }

//    public function rx_form_main_section_callback ($d_mode) {
//        include WcRx::get_template('rx_functions_variables');
//        require WcRx::get_template('rx_progress');
//        if ($d_mode != 'fashion') {
//            require WcRx::get_template('rx_usage');
//            require WcRx::get_template('rx_purpose');  /* load purpose template */
//            require WcRx::get_template('rx_distance');  /* load distance template */
//            require WcRx::get_template('rx_core');
//            require WcRx::get_template('rx_premium');  /* load type template */
//        }
//        require WcRx::get_template('rx_tint');
//        if ($d_mode != 'fashion') {
//            require WcRx::get_template('rx_material');
//            require WcRx::get_template('rx_coating');
//        }
//        require WcRx::get_template('rx_review');
//
//        draw_navigation_control($d_mode);
//    }

}


WcRx::preInit();
add_action('plugins_loaded', ['WcRx', 'getInstance']);

/**
 * @return object
 *
 */
function WcRx()
{
    return WcRx::getInstance();
}

require_once("checkout-files-upload-woocommerce.php");


add_action( 'wp_ajax_add_rush_to_cart', 'add_rush_to_cart' );
add_action( 'wp_ajax_nopriv_add_rush_to_cart', 'add_rush_to_cart' );

function add_rush_to_cart() {
    static $no_calls = 0;
    if($no_calls%2==0){
        remove_rush_3day_to_cart(1);
    }
	++$no_calls;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        if( $cart_item['_all_lens_data']['type']<>'' ){
            if($cart_item['_all_lens_data']['rush']!=1){
                $cart_item['_all_lens_data']['rush'] = 1;
                $cart_item['_all_lens_data']['rush_price'] = 59;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']+ 59;
                
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']+ 59;
                $cart_item['line_total'] = $cart_item['line_total']+ 59;

                $cart_item['lenses']['lrush'] = 1;

                $cart_item['Lens Package'] = $cart_item['Lens Package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>";
                $cart_item['wdm_user_custom_data'] = $cart_item['wdm_user_custom_data']."<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>";
                $cart_item['wdm_user_custom_data_package'] = $cart_item['wdm_user_custom_data_package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>";

                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] + 59;

                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) );

}

add_action( 'wp_ajax_remove_rush_to_cart', 'remove_rush_to_cart' );
add_action( 'wp_ajax_nopriv_remove_rush_to_cart', 'remove_rush_to_cart' );

function remove_rush_to_cart($p='') {
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        if( $cart_item['_all_lens_data']['type']<>'' ){
            if($cart_item['_all_lens_data']['rush']==1){
                $cart_item['_all_lens_data']['rush'] = '';
                $cart_item['_all_lens_data']['rush_price'] = 0;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']- 59;
                
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']- 59;
                $cart_item['line_total'] = $cart_item['line_total']- 59;
                $cart_item['lenses']['lrush'] = 0;

                $cart_item['Lens Package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>","",$cart_item['Lens Package']);
                $cart_item['wdm_user_custom_data'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>","",$cart_item['wdm_user_custom_data']);
                $cart_item['wdm_user_custom_data_package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">NEXT DAY RUSH SERVICE</div>","",$cart_item['wdm_user_custom_data_package']);

                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] - 59;

                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
    if($p ==''){
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) );
    } 

}

// 3d day rush service

add_action( 'wp_ajax_add_rush_3day_to_cart', 'add_rush_3day_to_cart' );
add_action( 'wp_ajax_nopriv_add_rush_3day_to_cart', 'add_rush_3day_to_cart' );

function add_rush_3day_to_cart() {
    static $no_calls = 0;
    if($no_calls%2==0){
        remove_rush_to_cart(1);
    }
	++$no_calls;

    $rush_3day_price = 9;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        //echo "<pre>";print_r($cart_item);echo "</pre>";
        if( $cart_item['_all_lens_data']['type']<>'' ){
            //echo 'Add 3day'; 
            if($cart_item['_all_lens_data']['rush_3day']!=1){
                $cart_item['_all_lens_data']['rush_3day'] = 1;
                $cart_item['_all_lens_data']['rush_price'] = $rush_3day_price;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']+ $rush_3day_price;
                
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']+ $rush_3day_price;
                $cart_item['line_total'] = $cart_item['line_total']+ $rush_3day_price;

                $cart_item['lenses']['lrush_3day'] = 1;

                $cart_item['Lens Package'] = $cart_item['Lens Package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";
                $cart_item['wdm_user_custom_data'] = $cart_item['wdm_user_custom_data']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";
                $cart_item['wdm_user_custom_data_package'] = $cart_item['wdm_user_custom_data_package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";

                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] + $rush_3day_price;

                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
    //unset($_SESSION['count']);
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) );

}


add_action( 'wp_ajax_remove_rush_3day_to_cart', 'remove_rush_3day_to_cart' );
add_action( 'wp_ajax_nopriv_remove_rush_3day_to_cart', 'remove_rush_3day_to_cart' );

function remove_rush_3day_to_cart($p ='') {
    $rush_3day_price = 9;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        if( $cart_item['_all_lens_data']['type']<>'' ){
            if($cart_item['_all_lens_data']['rush_3day']==1){
                $cart_item['_all_lens_data']['rush_3day'] = '';
                $cart_item['_all_lens_data']['rush_price'] = 0;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']- $rush_3day_price;
                
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']- $rush_3day_price;
                $cart_item['line_total'] = $cart_item['line_total']- $rush_3day_price;
                $cart_item['lenses']['lrush_3day'] = 0;

                $cart_item['Lens Package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['Lens Package']);
                $cart_item['wdm_user_custom_data'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['wdm_user_custom_data']);
                $cart_item['wdm_user_custom_data_package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['wdm_user_custom_data_package']);

                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] - $rush_3day_price;

                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
    if($p ==''){
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) );
    } 

}


//Two Way Rush service

add_action( 'wp_ajax_add_two_way_rush_to_cart', 'add_two_way_rush_to_cart' );
add_action( 'wp_ajax_nopriv_add_two_way_rush_to_cart', 'add_two_way_rush_to_cart' );

function add_two_way_rush_to_cart() {
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        if( $cart_item['_all_lens_data']['type']<>'' ){
            if($cart_item['_all_lens_data']['two_rush']!=1){
                $cart_item['_all_lens_data']['two_rush'] = 1;
                $cart_item['_all_lens_data']['rush_price'] = 94;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']+ 94;
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']+ 94;
                $cart_item['line_total'] = $cart_item['line_total']+ 94;
                $cart_item['lenses']['ltwo_rush'] = 1;
                $cart_item['Lens Package'] = $cart_item['Lens Package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";
                $cart_item['wdm_user_custom_data'] = $cart_item['wdm_user_custom_data']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";
                $cart_item['wdm_user_custom_data_package'] = $cart_item['wdm_user_custom_data_package']."<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>";
                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] + 94;
                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) );

}


add_action( 'wp_ajax_remove_two_way_rush_to_cart', 'remove_two_way_rush_to_cart' );
add_action( 'wp_ajax_nopriv_remove_two_way_rush_to_cart', 'remove_two_way_rush_to_cart' );

function remove_two_way_rush_to_cart() {
    $rush_3day_price = 94;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $cart_item_key => $cart_item) {
        if( $cart_item['_all_lens_data']['type']<>'' ){
            if($cart_item['_all_lens_data']['two_rush']==1){
                $cart_item['_all_lens_data']['two_rush'] = '';
                $cart_item['_all_lens_data']['rush_price'] = 0;
                $cart_item['_all_lens_data']['total_price'] = $cart_item['_all_lens_data']['total_price']- 94;
                $cart_item['line_subtotal'] = $cart_item['line_subtotal']- 94;
                $cart_item['line_total'] = $cart_item['line_total']- 94;
                $cart_item['lenses']['ltwo_rush'] = 0;
                $cart_item['Lens Package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['Lens Package']);
                $cart_item['wdm_user_custom_data'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['wdm_user_custom_data']);
                $cart_item['wdm_user_custom_data_package'] = str_replace("<br/><div class=\"info-title\" id=\"c_rush_cart\">3-4 DAYS GUARANTEED</div>","",$cart_item['wdm_user_custom_data_package']);
                $cart_item['wdm_package_price_value'] = $cart_item['wdm_package_price_value'] - 94;
                $woocommerce->cart->cart_contents[$cart_item_key] = $cart_item;
            }
        }
    }
     WC()->cart->set_session();
     wp_send_json( array( 'success' => 1 ) ); 

}




add_action( 'wp_ajax_track_order', 'track_order' );
add_action( 'wp_ajax_nopriv_track_order', 'track_order' );


function track_order() {

    $succes = 0;
    $message = '';
    $order_data = '';

    $ordern = base64_decode($_REQUEST['o']);
    $email = base64_decode($_REQUEST['e']);

    /* TO-DO: control if ordern is number > 0  */
    /* TO-DO: control if valid email  */
    //$ordern = 51024;
    $order = wc_get_order( $ordern );

    $message = 'Order not found. Please make sure number is correct';
    if($order != false ){
        //order ok

        $message = 'The email entered with your order number does not match with our records';
        if(
                strtolower($email)==strtolower($order->get_user()->user_email) ||
                strtolower($email)==strtolower( $order->get_billing_email() )
        ) {
            //email ok
	    // check order status
	   
	    $order_status = $order->get_status();
        $message = 'Your order is processing';
	    if ($order_status == 'completed') {
		    $message = 'Completed';
		    $shipping_info = $order->get_meta('ups_shipment_ids') ;
            
		    if ($shipping_info != ""){
			// ups
			$ups_tarcking	=	new WF_Shipping_UPS_Tracking();
			$shipment_info = $ups_tarcking->get_shipment_info( $ordern, $shipping_info );
			$message = "";
			foreach ( $shipment_info as $shipment_id => $msg ) {
			    $message .= '<strong id="track_ups_number">'.$shipment_id.': </strong>'.$msg.'</br>';
			}
			$succes = 1;
		    }else{
                
			//$wftrackingmsg = get_post_meta( $post->ID, "wfstampstrackingmsg", true);

			$usps_tarcking	=	new WF_Shipping_UPS_Tracking();
			/*$usps_tarcking->wf_display_admin_track_shipment();*/
            
			$stamps_usps_label_details_array    = array_values($order->get_meta('wf_stamps_labels', true ));
			$shipment_id_cs		= $stamps_usps_label_details_array[0]['tracking_number']?$stamps_usps_label_details_array[0]['tracking_number']:'';

			if ( $shipment_id_cs =! '' ){
			    $shipping_service	= "stamps-com-usps";
			    $order_date			= '';

			    $shipment_source_data	= WfTrackingUtil::prepare_shipment_source_data( $ordern, $shipment_id_cs, $shipping_service, $order_date );
			    $shipment_result 		= $usps_tarcking->get_shipment_info( $ordern, $shipment_source_data );
			    //$message = $shipment_result;


			    if ( null != $shipment_result && is_object( $shipment_result ) ) {
				$shipment_result_array = WfTrackingUtil::convert_shipment_result_obj_to_array ( $shipment_result );
				update_post_meta( $ordern, 'wf_stamps_shipment_result', $shipment_result_array );
			    }
			    else {
				update_post_meta( $ordern, 'wf_stamps_shipment_result', '' );
			    }

			    $shipping_info = $order->get_meta('wfstampstrackingmsg') ;
			    if ($shipping_info != ""){
				$message = $shipping_info;
				$succes = 1;
			    }
			}
		    }
		} else {
			// order processing
			if ($order_status == 'processing' || $order_status == 'rush') {
				$message = 'Order received';
			} elseif ($order_status == 'pendingframe') {
				$message = 'Order received pending frame for processing';
			} elseif ($order_status == 'rushpendingframe') {
				$message = 'Order received pending frame for processing';
			} elseif ($order_status == 'cancelled') {
				$message = 'Order cancelled';
			} elseif ( $order_status == 'productioncoating') {
				$message = 'Order in production';
			} else {
				$message = "Order $order_status";
			}
		}
    	
    }else{
            //email not valid
            $succes = 0;  
        }
         //wp_send_json( array( 'success' => 1 ) );
   }
   $replace = ["via Stamps.com", "To track shipment, please follow the shipment ID(s)"];
   $replaceBy   = ["with <strong style='color:#008000'>USPS</strong>", "Tracking number:"];
   
   $message = str_replace($replace, $replaceBy, $message);


    //$message = $order->get_user()->user_email;

     wp_send_json( array(
         'success' => $succes,
         'message' =>  $message
            ) );

exit;
}
?>
