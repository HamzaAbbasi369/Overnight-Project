<?php

add_action('woocommerce_after_shop_loop_item', 'ong_variable_color_category', 50);

function ong_variable_color_category()
{
    /** @var WC_Product_Simple|WC_Product_Variable $product */
    global $product;

    if ($product->get_type() !== 'variable') {
        return;
    }

    // Enqueue variation scripts
    wp_enqueue_script('wc-add-to-cart-variation');

    // Get Available variations?
    $get_variations = sizeof($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);

    // Load the template

    ong_get_template('color_category.php', array(
        'available_variations' => $get_variations ? $product->get_available_variations() : false,
        'attributes' => $product->get_variation_attributes(),
        'selected_attributes' => $product->get_default_attributes()
    ));
}
