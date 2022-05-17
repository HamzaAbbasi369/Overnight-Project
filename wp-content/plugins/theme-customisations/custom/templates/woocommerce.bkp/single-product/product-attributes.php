<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
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
 * @version     3.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('ong_get_product_shop_attributes_single', function ($list) {
    $list = array_filter($list, function ($item) {
        return in_array($item['name'], ADDITIONAL_INFORMATION_ATTRIBUTES_LIST);
    });

    return $list;
});

//$attributes = apply_filters('ong_get_product_shop_attributes_single', $product->get_attributes());
$attributes = $product->get_attributes();

?>
<table class="shop_attributes">
	<?php if ( $display_dimensions && $product->has_weight() ) : ?>
		<tr>
			<th><?php _e( 'Weight', 'woocommerce' ) ?></th>
			<td class="product_weight"><?php echo esc_html( wc_format_weight( $product->get_weight() ) ); ?></td>
		</tr>
	<?php endif; ?>

	<?php if ( $display_dimensions && $product->has_dimensions() ) : ?>
		<tr>
			<th><?php _e( 'Dimensions', 'woocommerce' ) ?></th>
			<td class="product_dimensions"><?php echo esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ); ?></td>
		</tr>
	<?php endif; ?>
</table>

<h2>Frame information</h2>
<table class="shop_attributes frame_info">
    <?php //echo '<pre>'; print_r($attributes);echo '</pre>'; //DEBUG::display all attreibutes ?>
	<?php /*
    foreach ( $attributes as $attribute ) : ?>
		<tr class="<?= $attribute->name ?>">
			<th><?php echo wc_attribute_label( $attribute->get_name() ); ?></th>
			<td><?php
				$values = array();

				if ( $attribute->is_taxonomy() ) {
					$attribute_taxonomy = $attribute->get_taxonomy_object();
					$attribute_values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'all' ) );

					foreach ( $attribute_values as $attribute_value ) {
						$value_name = esc_html( $attribute_value->name );

						if ( $attribute_taxonomy->attribute_public ) {
							$values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attribute->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
						} else {
							$values[] = $value_name;
						}
					}
				} else {
					$values = $attribute->get_options();

					foreach ( $values as &$value ) {
						$value = make_clickable( esc_html( $value ) );
					}
				}

				echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
			?></td>
		</tr>
	<?php endforeach;
        */
        ?>

    <?php
    $i=0;
    $listofeat = array();

    $listofeat[$i]="pa_brands";
    $i+=1;
    $listofeat[$i]="pa_color-code";
    $i+=1;
    $listofeat[$i]="pa_frame-style";
    $i+=1;
    $listofeat[$i]="pa_lens-height";
    $i+=1;
    $listofeat[$i]="pa_lens-width";
    $i+=1;
    $listofeat[$i]="pa_frame-width";
    $i+=1;
    $listofeat[$i]="pa_bridge";
    $i+=1;

    foreach ( $listofeat as $key => $feautre ) {
        if(array_key_exists($feautre,$attributes) ){?>
        <tr class="fe_<?= $feautre ?>">
            <th><?php echo wc_attribute_label( $attributes[$feautre]->get_name() ); ?></th>
            <td><?php
                $values = array();

                if ( $attributes[$feautre]->is_taxonomy() ) {
                    $attribute_taxonomy = $attributes[$feautre]->get_taxonomy_object();
                    $attribute_values = wc_get_product_terms( $product->get_id(), $attributes[$feautre]->get_name(), array( 'fields' => 'all' ) );

                    foreach ( $attribute_values as $attribute_value ) {
                        $value_name = esc_html( $attribute_value->name );

                        if ( $attribute_taxonomy->attribute_public ) {
                            $values[] = '<a href="' . esc_url( get_term_link( $attribute_value->term_id, $attributes[$feautre]->get_name() ) ) . '" rel="tag">' . $value_name . '</a>';
                        } else {
                            $values[] = $value_name;
                        }
                    }
                } else {
                    $values = $attribute->get_options();

                    foreach ( $values as &$value ) {
                        $value = make_clickable( esc_html( $value ) );
                    }
                }

                echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                ?></td>
        </tr>
    <?php }//endif
        } //endfoerach ?>




</table>

<?php 
$i=0;
$listofeat = array();

/*$listofeat[$i]="pa_color-code";
$i+=1;
$listofeat[$i]="pa_frame-style";
$i+=1;
$listofeat[$i]="pa_lens-height";
$i+=1;
//$listofeat[$i]="pa_lens-width";
//$i+=1;
$listofeat[$i]="pa_frame-width";
$i+=1;*/
$listofeat[$i]="expert-opinion";

$ex = 0;
foreach ( $listofeat as $key => $feautre ) {
	if(array_key_exists($feautre,$attributes) ){
		$ex=1;
	}
}

if ($ex == 1) {

?>


<h2>Frame attributes</h2>

<table class="shop_attributes frame_info">
<?php

foreach ( $listofeat as $key => $feautre ) {
    if(array_key_exists($feautre,$attributes) ){?>
        <tr class="<?= $attributes[$feautre]->name ?>">
            <th><?php echo wc_attribute_label( $attributes[$feautre]->get_name() ); ?></th>
            <td>
<?php
		echo $attributes[$feautre]->get_options()[0];
                //echo apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
                ?></td>
        </tr>
    <?php }//endif
} //endfoerach ?>
</table>

<?php 
}
//do_action('ong_after_product_attributes', $attributes); ?>
