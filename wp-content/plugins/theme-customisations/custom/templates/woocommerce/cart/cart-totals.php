<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates
 * @version     2.3.6
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="shopping--content-summary-wrap large-3 medium-12 small-12 column cart_totals <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
    <p class="shopping-sum-title">Order summary</p>
    <div class="shopping--content-summary">
            <div class="shopping--content-summary-block">
               <p>
                   <a class="view-available-coupon view_available_specials" data-open="stickyCoupons_block">view available specials</a>
               </p>
					
               <!--coupon-->
               <p class="coupone--title">Coupon code</p>
               <?php if (wc_coupons_enabled()) { ?>
                   <form action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
                       <table cellspacing="0" class="shop_table ">
                           <tr>
                               <th><input type="text"
                                          name="coupon_code"
                                          class="input-text"
                                          id="coupon_code"
                                          value=""
                                          style="width: 120%; margin-bottom: 0rem;"
                                          placeholder="<?php esc_attr_e('Coupon code', 'woocommerce'); ?>"/></th>
                               <td><input type="submit" class="button" name="apply_coupon"
                                          value="<?php esc_attr_e('Apply', 'woocommerce'); ?>"/>
                                   <?php do_action('woocommerce_cart_coupon'); ?>
                               </td>
                           </tr>
                       </table>
                   </form>
					
               <?php } ?>


               <?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
                   <tr class="fee">
                       <th><?php echo esc_html( $fee->name ); ?></th>
                       <td data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></td>
                   </tr>
               <?php endforeach; ?>

               <div class="shopping-sum-content">
                   <!--            <a href="#" class="shopping-need-help">Need help</a>-->
                   <!--        </div>-->


                   <?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
                       $taxable_address = WC()->customer->get_taxable_address();
                       $estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
                           ? sprintf( ' <small>(' . __( 'estimated for %s', 'woocommerce' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
                           : '';



                       if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
                           <?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
                               <p class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?> clearfix">
                                   <span class="float-left"><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
                                   <span class="float-right"><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
                               </p>
                           <?php endforeach; ?>
                       <?php else : ?>
                           <p class="tax-total clearfix">
                               <span class="float-left"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></span>
                               <span class="float-right"><?php wc_cart_totals_taxes_total_html(); ?></span>
                           </p>
                       <?php endif; ?>
                   <?php endif; ?>

                   <?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
               </div>

           </div>




            <p id="shopping-sub-total" class="shopping-sub-total clearfix" style=" padding: 0px 13px 1px;">
                <span class="float-left">Subtotal:</span>
                <span class="float-right"><?= strip_tags(WC()->cart->get_cart_subtotal()) ?></span>
            </p>
		 		
            <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
		     <div class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
		     <p id="shopping-coupon" class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>" style=" padding: 5px 13px 1px;">
			<span class="float-left"><?php wc_cart_totals_coupon_label($coupon); ?></span>
			<span class="float-right"><?php custom_coupon_html($coupon);?></span>
		    </p>
                    </div>
	    <?php endforeach; ?>
	    <br/>
		 <?php
				$title = 'Free Overnight Shipping';
			
				if ( $text = get_field('free_overnight_shipping', 'option') ) {
					$title = $text;
				}
			?>
					<h4 class="free_shipping" style="font-family:'kelson';font-weight:600;padding-left:13px;"><?php echo $title; ?></h4>
            <p id="shopping-total-order" class="shopping-total-order clearfix"><span class="float-left"><?php _e( 'Total', 'woocommerce' ); ?></span> <span class="float-right"> <?php wc_cart_totals_order_total_html(); ?></span></p>
            
	    <?php do_action( 'ong_after_cart_subtotal' ); ?>

            <div class="wc-proceed to-checkout" style="display: none;">
                <?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
            </div>

        <?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
    </div>
</div>
