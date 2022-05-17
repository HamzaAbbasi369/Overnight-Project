<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * WooCommerce Ong Base Customizer.
 *
 * @package  WcRx/Customizer
 * @category Class
 * @author   odokienko
 */
class WcRxCustomizer
{

	/**
	 * Section slug.
	 *
	 * @var string
	 */
	public $section_slug = 'woocommerce_rx';

	/**
	 * Initialize the customize actions.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_settings' ) );
		add_action( 'customize_preview_init', array( $this, 'live_preview' ) );
		add_action( 'customize_save_after', array( $this, 'save_after' ) );
	}

	/**
	 * Register the customizer settings.
	 *
	 * @param \WP_Customize_Manager $wp_customize
	 */
	public function register_settings( $wp_customize ) {
		//
	}

	/**
	 * Customizer live preview.
	 */
	public function live_preview() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'woocommerce-rx-customizer', WcRx::get_assets_url() . 'js/customizer' . $suffix . '.js', array( 'jquery', 'customize-preview', 'tinycolor' ), WcRx::VERSION, true );
	}

	/**
	 * Save the colors.
	 *
	 * @param WP_Customize_Manager $customize
	 */
	public function save_after( $customize ) {
		if ( ! isset( $_REQUEST['customized'] ) ) {
			return;
		}

		$customized = json_decode( stripslashes( $_REQUEST['customized'] ), true );
		$save       = false;

		foreach ( $customized as $key => $value ) {
			if ( false !== strpos( $key, $this->section_slug ) ) {
				$save = true;
				break;
			}
		}

		if ( $save ) {
			//  do something
		}
	}
}

new WcRxCustomizer();
