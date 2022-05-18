<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

wc_print_notices();
do_action( 'woocommerce_before_cart' );
?>
<style>
	.not_in_rush{
	 padding: 15px 30px;
    border: 1px dotted #92844D;
    margin: 10px 0;
	}
	#not_in_rush{
    position: relative;
    padding-left: 25px;
    padding-left: 2.5rem;
}
#not_in_rush label:before {
    content: '';
    position: absolute;
    left: -10px;
    display: inline-block;
    border: 1px solid #92844D;
    width: 20px;
    height: 20px;
    margin: 0 4px 0 0;
    vertical-align: middle;
    cursor: pointer;
}
	#not_in_rush input[type="checkbox"]{
    display: none !important;
}
</style>
<form action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

    <?php do_action( 'woocommerce_before_cart_table' ); ?>


	
	
	
<div id="shopping--content-wrap-id" class="shopping--content-wrap row middle--row">

    <div class="shopping--content-left large-9 medium-12 small-12 column show-for-large-up show-for-large">
        <div class="row middle--row shopping--content-wrap shopping--content-title-block">
            <div class="large-12 text-left">
                <p class="shopping--content-title">SHOPPING CART</p>
					
            </div>
            asa123
			<?php
				if ( get_field( 'is_not_rushed', 'option' ) ) :

					$coupon_json = '';
					if ( $coupon = get_field('discounted_coupon', 'option') ) {
						$coupon_json = base64_encode( get_the_title($coupon) );
					}
			?>
		<script>
			$(document).ready( function(){
				if ( sessionStorage.getItem("discount_offer") && sessionStorage.getItem("discount_offer").length > 0  && sessionStorage.getItem("discount_offer") == "<?php echo get_the_title($coupon); ?>" ) {
					
					$('#not_in_rush_checkbox').attr('checked', 'checked');
					$('#not_in_rush').addClass('chk');
					$('.free_shipping').css('display','none');


				} else {
					$('#not_in_rush_checkbox').removeAttr('checked');
					$('#not_in_rush').removeClass('chk');
					$('.free_shipping').css('display','block');


				}
				
				if ( $('.woocommerce-error li').text().indexOf("Sorry, coupon") > 0 ) {
					sessionStorage.setItem("discount_offer", "")
					$('#not_in_rush_checkbox').removeAttr('checked');
					$('#not_in_rush').removeClass('chk');
				}
			})

		</script>
	
			<?php
				$label = 'Not in a Rush? Check this box For 20% OFF your order and FREE 2-4 days shipping';
			
				if ( $text = get_field('label', 'option') ) {
					$label = $text;
				}
			?>	
			  <div class="not_in_rush">
			  <h3 id="not_in_rush">
                        <label class="checkbox">
                                <input id="not_in_rush_checkbox" name="discount_offer" class="input-checkbox"  type="checkbox" data-coupon-json='<?php echo $coupon_json; ?>'> <span><?php echo $label; ?></span>
            </label>
                </h3>
				  </div>
			
			<?php endif; ?>
        </div>
        <ul class="menu vertical">
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                $_product = apply_filters(
                    'woocommerce_cart_item_product',
                    $cart_item['data'],
                    $cart_item,
                    $cart_item_key
                );
                $product_id = apply_filters(
                    'woocommerce_cart_item_product_id',
                    $cart_item['product_id'],
                    $cart_item,
                    $cart_item_key
                );
                if ($_product &&
                    $_product->exists() &&
                    $cart_item['quantity'] > 0 &&
                    apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
                ) :
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink',
                        $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                        $cart_item,
                        $cart_item_key
                    );
                    ?>
        <li>
            <div class="large-12 shopping--content-wrap-block clearfix">
            <div class="large-12 clearfix shopping--content-top-menu">
                <div class="large-3 shopping--content-top-1 columns">
                    <p class="shopping--content-top-title">
                        FRAME
                    </p>
                </div>
                <div class="large-12 shopping--content-top-2 columns">
                    <p class="shopping--content-top-title">
                        LENS
                    </p>
                </div>
            </div>
                    <?php do_action( 'woocommerce_before_cart_contents' ); ?>

            <div class="large-3 shopping--content-top shopping--content-top-1 column">
                <div class="card">

                    <!--photo cart-->
                    <?php echo $_product->get_image(); ?>
                    <div class="card-section" style="text-align: left; padding-left: 20px;">
                        <?php
                        if (!$product_permalink) {
                            echo "Model: ". apply_filters(
                                    'woocommerce_cart_item_name',
                                    $_product->get_title(),
                                    $cart_item, $cart_item_key) . '&nbsp;';
                        } else {
                            echo "Model: ".$_product->get_title() . '<br>';
                            echo "Size: ".$cart_item['variation']['attribute_pa_size'] ??  '';
                            echo "<br/>Color: ".Ong_String_Helper::underscoreToCamel($cart_item['variation']['attribute_pa_color'] ??  '');


                        }
                        // Meta data
                        echo wc_get_formatted_cart_item_data($cart_item);
                        ?>

                    </div>
                    <div class="callout show-for-small-only"
                         style="max-width: 100px; display: inline-block; padding-right: 25px;">
                        <a href="#">
                            <button class="close-button" aria-label="Close alert" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            Remove
                        </a>
                    </div>
                </div>
            </div>
            <div class="large-12 shopping--content-top shopping--content-top-2 column">
                <ul class="menu vertical shopping--menu">
                    <?= apply_filters('rx_cart_item_lens', '', $cart_item, $cart_item_key);
                    ?>
                </ul>
            </div>
            <?php
                endif;
            ?>
            </div>
        </li>
        <li>
            <div class="large-12 shopping--content-wrap-remove text-right clearfix">
                <div class="shopping--content-wrap-checkbox large-6 column">
                    <!--                <input id="checkbox1" type="checkbox"><label for="checkbox1">Add backup pair</label>-->
                    <!--                <input id="checkbox2" type="checkbox"><label for="checkbox2">Add case: Starting at <strong>$3.50</strong></label>-->
                </div>
                <div class="large-6 column">
                    <div class="callout">
                        <?php
                        echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                            '<a href="%s" title="%s" data-product_id="%s" data-product_sku="%s">
                                <button class="close-button" aria-label="Close alert" type="button">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                Remove
                            </a>',
                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                            __('Remove this item', 'woocommerce'),
                            esc_attr($product_id),
                            esc_attr($_product->get_sku())
                        ), $cart_item_key);
                        ?>
                    </div>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
        </ul>
    </div>
    <?php do_action('woocommerce_cart_collaterals'); ?>


    <div id="shopping-content-mobile-wrap" class="hide-for-large">
            <!--        <ul class="accordion" data-accordion>-->
            <div class="large-12 text-center shopping--content-title-wrap">
                <p class="shopping--content-title">SHOPPING CART</p>
            </div>
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                $_product = apply_filters(
                    'woocommerce_cart_item_product',
                    $cart_item['data'],
                    $cart_item,
                    $cart_item_key
                );
                $product_id = apply_filters(
                    'woocommerce_cart_item_product_id',
                    $cart_item['product_id'],
                    $cart_item,
                    $cart_item_key
                );
                if ($_product &&
                    $_product->exists() &&
                    $cart_item['quantity'] > 0 &&
                    apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
                ) :
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink',
                        $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                        $cart_item,
                        $cart_item_key
                    );
                    ?>
                    <div class="shopping-mobile-accordion-wrap">

                    <div class="shop_table shop_table_responsive cart mobile">

                                    <!--photo cart-->
                        <?php echo $_product->get_image(); ?>
                        <div class="card-section">
                                        <?php
                                        if (!$product_permalink) {
                                            echo apply_filters(
                                                    'woocommerce_cart_item_name',
                                                    $_product->get_title(),
                                                    $cart_item, $cart_item_key) . '&nbsp;';
                                        } else {
                                            echo "Model: ".$_product->get_title()."<br/>";

                                        }
                                        #echo  $_product->get_type().'<br>';
                                        if( $_product->get_type()== 'variation' ){
                                            echo "Size: ".$cart_item['variation']['attribute_pa_size'].'<br>';
					    echo "Color: ".Ong_String_Helper::underscoreToCamel($cart_item['variation']['attribute_pa_color'] ??  '').'<br/>';
                                        }
                                        // Meta data
                                        echo wc_get_formatted_cart_item_data($cart_item);
                                        ?>

                                    </div>
                                    <div class="text-left">
                                        <div class="callout" >
                                            <?php
                                            echo apply_filters('woocommerce_cart_item_remove_link', sprintf(
                                                '<a href="%s" title="%s" data-product_id="%s" data-product_sku="%s">
                                                    <button class="close-button" aria-label="Close alert" type="button">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                    Remove
                                                </a>',
                                                esc_url(wc_get_cart_remove_url($cart_item_key)),
                                                __('Remove this item', 'woocommerce'),
                                                esc_attr($product_id),
                                                esc_attr($_product->get_sku())
                                            ), $cart_item_key);
                                            ?>
                                        </div>
                                    </div>
                                </div>

                    <ul class="accordion" data-accordion data-allow-all-closed="true">
                            <li>
                            </li>
                            <li class="accordion-item" data-accordion-item>
                                <a href="#" class="accordion-title">Lens Package</a>
                                <div class="accordion-content" data-tab-content>
                                    <div class="menu vertical shopping--menu-prescription">
                                        <!--PRESCRIPTION-->
                                        <?php
                                        echo apply_filters('rx_cart_item_lens', '', $cart_item, $cart_item_key);
                                        ?>

                                    </div>
                                </div>
                            </li>
                            <?php
                                endif;
                            ?>
                            <li class="accordion-item" data-accordion-item>
                                <a href="#" class="accordion-title">PRESCRIPTION</a>
                                <div class="accordion-content" data-tab-content>
                                    <ul class="menu vertical shopping--menu">
                                        <?=apply_filters('rx_cart_item_prescription', '', $cart_item, $cart_item_key) ?>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
            <?php endforeach; ?>
        <?php do_action( 'woocommerce_after_cart_contents' ); ?>
    </div>

</div>
    <?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>
<?php do_action( 'woocommerce_after_cart' ); ?>
