<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
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
 * @version     2.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

global $post, $product;

echo "<div id='checkout-form-wrap'>";

wc_print_notices();

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout
if (!$checkout->enable_signup && !$checkout->enable_guest_checkout && !is_user_logged_in()) {
    echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
    return;
}

?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>


<!--                <div id="payment--info-block">-->
<!--                    <p class="checkout--details-title">PAYMENT INFO</p>-->
<!--                    --><?php
//                    if ( WC()->cart->needs_payment() ) {
//                        $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
//                        WC()->payment_gateways()->set_current_gateway( $available_gateways );
//                    } else {
//                        $available_gateways = array();
//                    }
//                    wc_get_template( 'checkout/payment.php', array(
//                        'checkout'           => WC()->checkout(),
//                        'available_gateways' => $available_gateways,
//                        'order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) )
//                    ) );
//                    ?>
<!--                </div>-->
                <?php do_action('woocommerce_checkout_billing'); ?>
                <?php do_action('woocommerce_checkout_shipping'); ?>



                <div class="text-center continue--payment-btn-wrap">
                    <?php $order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ); ?>
                    <?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt hopping-checkout checkout-button" name="woocommerce_checkout_place_order" id="continue--payment-btn" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>
                </div>
            </div>
        </div>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>

<!--    <h3 id="order_review_heading">--><?php //_e( 'Your order', 'woocommerce' ); ?><!--</h3>-->

    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

    <div id="order_review_bottom" class="woocommerce-checkout-review-order">
        <?php do_action( 'woocommerce_checkout_order_review' ); ?>
    </div>

    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<div id="checkout-form-bottom">
    <p class="checkout-form-bottom-title">Need assistance?</p>
    <a href="/contact-us/" class="checkout-form-bottom-link">Contact our support team</a>
</div>