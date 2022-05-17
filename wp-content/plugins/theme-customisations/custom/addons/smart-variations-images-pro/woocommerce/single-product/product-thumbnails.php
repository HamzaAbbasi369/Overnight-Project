<?php

/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see 	    http://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.3.0
 */

/** @var woocommerce_svi_frontend $woosvi_class */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $post, $product, $woocommerce, $woosvi, $woosvi_class;

if ($woosvi['data']['slider']) {
    $woosvi_class->build_thumbswiper();
} else {

    $context_temp['is_archive'] = is_archive() ? 'archive' : '';
    $context_temp['is_product'] = is_product() ? 'single' : '';
    $context_temp['loop_name']  = (in_the_loop() && wc_get_loop_prop('name')) ? wc_get_loop_prop('name') : '';
    $context_temp               = array_filter($context_temp);
    $context               = implode('_', $context_temp);

    if ($context === 'single') {
        $woosvi_class->build_thumbimage();
    }else {
        $woosvi_class->build_thumbimage([
            'context' => $context,
            'wrapper_tag' => 'ul',
            'item_tag' => 'li',
            'wrapper_class' => 'thumbnails large-12 product--glasses-prev-block',
        ]);
    }
}
