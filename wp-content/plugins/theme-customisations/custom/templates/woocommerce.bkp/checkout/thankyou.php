<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
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
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="woocommerce-order" id='thankyou--page-wrap'>

	<?php if ( $order ) : ?>

		<?php if ( $order->has_status( 'failed' ) ) : ?>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

			<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
				<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
				<?php if ( is_user_logged_in() ) : ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
				<?php endif; ?>
			</p>

    <?php else : ?>
        <div class="thankyou-order-wrap">
            <div>
                <h2>Thank you! <br/>Your order number is #<?php echo $order->ID ?></h2>
            </div>
            <p class="woocommerce-thankyou-order-received">
                <br/>Your order has been received.
                <br>
                You will receive a confirmation email in a few moments.
            </p>
		<?php

		$delivery = get_estimated_delivery($order);

		if ($delivery['product_id'] != 14010 && $delivery['product_id'] != 48659) {
			echo "<div id='thankyou_shipping'>";
			echo "<br><p class='woocommerce-thankyou-order-received'>";
			if ($delivery['late_day'] == 1) {
				echo "Please note: Orders sent after 12PM PST require an additional production day<br/><br/>";
			}
			echo "Shipping and Production Monday-Friday<br/><br/>";
							
                        echo "Production days: <b>".$delivery['production_days']."</b><br/>";
			echo "Shipping days: <b>".$delivery['shipping_days']."</b><br/><br/>";
			
			if ($delivery['deliver_by'] != "") {
				echo "Your order should be delivered by <b>" . $delivery['deliver_by']."</b></p><br>";
			}
			echo "</div><br/>";

			if ($delivery['rush'] == 1) {
				$order->update_status('rush');
			}

		} else {
			// products is send your frame, update order status to pending frame
			if ($delivery['rush'] == 1) {
				$order->update_status('rushpendingframe');
			} else {
				$order->update_status('pendingframe');
			}
		}
                ?>
        </div>
    <?php endif; ?>

		<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
		<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

		<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>

<?php endif; ?>
</div>

<div id="checkout-form-bottom">
    <p class="checkout-form-bottom-title">Need assistance?</p>
    <a href="/contact-us/" class="checkout-form-bottom-link">Contact our support team</a>
</div>
