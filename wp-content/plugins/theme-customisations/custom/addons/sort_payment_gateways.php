<?php

add_filter( 'woocommerce_available_payment_gateways', function($gateways) {
    ksort($gateways);
    return $gateways;
});
