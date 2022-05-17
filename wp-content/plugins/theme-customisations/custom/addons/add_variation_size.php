<?php

// Add Variation Custom fields

//Display Fields in admin on product edit screen
add_action( 'woocommerce_product_after_variable_attributes', 'woo_variable_fields', 10, 3 );

//Save variation fields values
add_action( 'woocommerce_save_product_variation', 'save_variation_fields', 10, 2 );

// Create new fields for variations
function woo_variable_fields( $loop, $variation_data, $variation ) {

    echo '<div class="variation-custom-fields clearfix">';
    woocommerce_wp_text_input(
        [
            'id'          => '_size_lens_height['. $loop .']',
            'label'       => __( 'Lens Height', 'woocommerce' ),
            'placeholder' => 'Lens Height',
            //'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-first',
            //'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, '_size_lens_height', true)
        ]
    );

    woocommerce_wp_text_input(
        [
            'id'          => '_size_lens_width['. $loop .']',
            'label'       => __( 'Lens Width', 'woocommerce' ),
            'placeholder' => 'Lens Width',
            //'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-first',
            //'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, '_size_lens_width', true)
        ]
    );

    woocommerce_wp_text_input(
        [
            'id'          => '_size_bridge['. $loop .']',
            'label'       => __( 'Bridge', 'woocommerce' ),
            'placeholder' => 'Bridge',
            //'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-first',
            //'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, '_size_bridge', true)
        ]
    );

    woocommerce_wp_text_input(
        [
            'id'          => '_size_frame_width['. $loop .']',
            'label'       => __( 'Frame Width', 'woocommerce' ),
            'placeholder' => 'Frame Width',
            //'desc_tip'    => true,
            'wrapper_class' => 'form-row form-row-first',
//            'description' => __( 'Enter the custom value here.', 'woocommerce' ),
            'value'       => get_post_meta($variation->ID, '_size_frame_width', true)
        ]
    );
	woocommerce_wp-text_input(
	[
		'id' 				 => '_sku['.$loop.']',
		'lable' 			 => __('Variation Sku', 'woocommerce'),
		'placeholder' 	 => 'Variation Sku',
		'wrapper_class' => 'form-row form-row-first',
		'value' 			 => get_post_meta($variation->ID, '_sku', true)
	]
	
	
	);


    echo "</div>";

}

/** Save new fields for variations */
function save_variation_fields( $variation_id, $i) {

    $text_field = stripslashes( $_POST['_size_frame_width'][$i] );
    update_post_meta( $variation_id, '_size_frame_width', esc_attr( $text_field ) );

    $text_field = stripslashes( $_POST['_size_lens_height'][$i] );
    update_post_meta( $variation_id, '_size_lens_height', esc_attr( $text_field ) );

    $text_field = stripslashes( $_POST['_size_lens_width'][$i] );
    update_post_meta( $variation_id, '_size_lens_width', esc_attr( $text_field ) );

    $text_field = stripslashes( $_POST['_size_bridge'][$i] );
    update_post_meta( $variation_id, '_size_bridge', esc_attr( $text_field ) );

    $text_field = stripslashes( $_POST['_size_lens_height'][$i] );
    update_post_meta( $variation_id, '_size_lens_height', esc_attr( $text_field ) );
	
	$text_field = stripslashes($_POST['_sku'][$i]);
	 update_post_meta($variation_id, '_sku', esc_atte($text_field));

}

add_filter('woocommerce_available_variation', function ($arr, $variation ){

    if(array_key_exists('variation_id', $arr)){
        $arr['size_lens_height']    = get_post_meta($arr['variation_id'], '_size_lens_height', true);
		  $arr['sku'] 						= get_post_meta($arr['variation_id'], '_sku', true);
        $arr['size_lens_width']     = get_post_meta($arr['variation_id'], '_size_lens_width', true);
        $arr['size_frame_width']    = get_post_meta($arr['variation_id'], '_size_frame_width', true);
        $arr['size_bridge']         = get_post_meta($arr['variation_id'], '_size_bridge', true);
    }

    return $arr;

}, 10, 2);

add_action('ong_after_product_attributes', function (){
    echo "<table class=\"shop_attributes size_lens_variations\"><tbody></tbody></table>";
}, 10);
