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
<?php if(is_user_logged_in()==false){ ?>
<div class="u-column2 col-2">

	<!-- woosocial
    <h2><?php //esc_html_e( 'Social', 'woocommerce' ); ?></h2>
    <?php //do_action( 'woo_custom_social_buttons' ); ?>
	-->

</div>
<?php } ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout"
      action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">

    <?php if (sizeof($checkout->checkout_fields) > 0) : ?>

        <?php do_action('woocommerce_checkout_before_customer_details'); ?>

        <div class="billing_shipping_wrap">
            <?php do_action('woocommerce_checkout_shipping'); ?>

	                    <h3 id="ship-to-different-address">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
                                <input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php _e( 'My Billing And Shipping Are The Same', 'woocommerce' ); ?></span>
            </label>
                </h3><br/><br/>
            <?php do_action('woocommerce_checkout_billing'); ?>

        </div>
        <div class="text-center">
            <a id="continue_to_payment">Continue to payment</a>
        	<br/><br/>
	</div>

        <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

    <?php endif; ?>

    <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
    <?php do_action( 'woocommerce_checkout_order_review' ); ?>
    <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

</form>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>

<!--<div class="secure-icons-wrap text-center secure-icons-wrap-checkout">-->
<!--</div>-->

<div class="secure-icons-wrap secure-icons-wrap-mobile text-center">
    <div class="secure-icons-wrap-block">
        <i class="mobileic-satisfaction"></i>
        <i class="mobileic-made-in-usa"></i>
        <i class="mobileic-free-shipping"></i>
        <i class="mobileic-free-transactions"></i>
        <i class="mobileic-free-genuine-brand"></i>
        <i class="mobileic-free-money-back"></i>
        <i class="mobileic-free-best-price"></i>
    </div>
</div>

</div>

<div id="checkout-form-bottom">
    <div id="amzdummy" style="display: none;"></div>

    <div class="secure-icons-wrap secure-icons-wrap-block secure-icons-wrap-checkout">
        <i class="spriteic-satisfaction"></i>
        <i class="spriteic-made-in-usa"></i>
        <i class="spriteic-free-shipping"></i>
        <i class="spriteic-free-transactions"></i>
        <i class="spriteic-free-genuine-brand"></i>
        <i class="spriteic-free-money-back"></i>
        <i class="spriteic-free-best-price"></i>

    </div><br/><br/>
    <a href="https://www.google.com/shopping/ratings/account/lookup?q=www.overnightglasses.com" target="_blank" rel="google reviews"><img src="/content/plugins/theme-customisations/custom/assets/img/google-reviews.png" /><br/>
    <div style="margin-top: -25px;">4.5 Stars Customer Rating</div></a><br/><br/>


    <p class="checkout-form-bottom-title">Need assistance?</p>
    <a href="/contact-us/" class="checkout-form-bottom-link">Contact our support team<br/> 855-830-3339 Mon-Friday 9:30am to 6pm PST</a>
</div>
