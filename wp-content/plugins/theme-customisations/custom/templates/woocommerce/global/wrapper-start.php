<?php
/**
 * Content wrappers
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/wrapper-start.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$template = wc_get_theme_slug_for_templates();

switch ( $template ) {
	case 'foundationpress' :
	    if (is_archive()){
            echo ' <div id="quick-look-section" class="quick-look-section middle-block-wrap row middle--row shopping--middle-block">';
        }
	    if (!empty($_REQUEST['forcelrxtype'])) {
	
	        echo '            <div id="single-product-wrap" style="visibility: hidden;" class="row middle--row">';
	    } else {
		echo '            <div id="single-product-wrap" class="row middle--row">';
            }
		break;
	default :
		echo '<div id="primary" class="content-area"><main id="main" class="site-main" role="main">';
		break;
}
