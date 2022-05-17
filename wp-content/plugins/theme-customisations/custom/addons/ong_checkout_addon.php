<?php


//remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

add_action('woocommerce_checkout_before_customer_details',function(){
    echo <<<'HTML'
    <div class="col2-set" id="customer_details">
        <div class="col-1">
            <div id="payment--info-block">
                <p class="checkout--details-title">SHIPPING ADDRESS</p>
HTML;
}, 10);

add_action('woocommerce_checkout_before_customer_details',function(){
    echo "</div>";
},30);

//add_action('woocommerce_checkout_after_customer_details', 'ong_place_order_button',10);
//add_action('woocommerce_checkout_after_order_review', 'ong_place_order_button',10);
function ong_place_order_button()
{
?>
<div class="text-center continue--payment-btn-wrap">
    <?php $order_button_text = apply_filters( 'woocommerce_order_button_text', __( 'Place order', 'woocommerce' ) ); ?>
    <?php echo apply_filters( 'woocommerce_order_button_html', '<input type="submit" class="button alt shopping-checkout checkout-button" name="woocommerce_checkout_place_order" id="continue--payment-btn" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '" />' ); ?>
</div>
<?php
}

//add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 100 );

add_action('woocommerce_checkout_after_customer_details',function(){
    echo "</div>";
},20);

add_action('woocommerce_checkout_after_customer_details',function(){
    echo "</div>";
},40);





