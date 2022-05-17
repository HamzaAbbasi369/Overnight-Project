<?php

/************************  Rush Service ***********************/
add_filter( 'woocommerce_package_rates', 'hide_shipping_when_rush', 10, 2 );

function is_rush_service () {
    $is_rush=false;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $key => $values) {
        if (isset($values['wdm_user_custom_data'])){
            $package=$values['wdm_user_custom_data'];
            //print($package);die();
            if (strrpos($package,'RUSH SERVICE')>0) {
                $is_rush=true;
            }
        }
    }
    return $is_rush;
}

function is_rush_3day_service () {
    $is_rush=false;
    global $woocommerce;
    foreach ($woocommerce->cart->get_cart() as $key => $values) {
        if (isset($values['wdm_user_custom_data'])){
            $package=$values['wdm_user_custom_data'];
            //print($package);die();
            if (strrpos($package,'3-4 DAYS GUARANTEED')>0) {
                $is_rush=true;
            }
        }
    }
    return $is_rush;
}

function hide_shipping_when_rush($rates, $package)
{
    if (is_rush_3day_service()) {
        foreach ($rates as $key => $rate) {
            if ($key != 'wf_shipping_ups:02') {
                unset($rates[$key]);
            } else {
                $rate->cost = 0.0;
            }
        }
    } elseif  (is_rush_service()) {
        foreach ($rates as $key => $rate) {
            if ($key != 'wf_shipping_ups:01') {
                unset($rates[$key]);
            } else {
                $rate->cost = 0.0;
            }
        }
    } else {
        foreach ($rates as $key => $rate) {
            if ($key == 'wf_shipping_ups:01') {
                unset($rates[$key]);
            }
        }
    }
    return $rates;
}


/************************ end rush service ********************/
