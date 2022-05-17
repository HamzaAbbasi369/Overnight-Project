<?php

if (function_exists('wc_apa')):
    $wpa = wc_apa();
    remove_action('woocommerce_proceed_to_checkout', [$wpa, 'checkout_button'], 25);
    add_action('woocommerce_proceed_to_checkout', [$wpa, 'checkout_button'], 15);
endif;
