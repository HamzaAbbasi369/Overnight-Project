<?php

add_filter('woocommerce_add_cart_item_data', 'insert_into_rx_step', 2, 2); //move data to wp session
//add_filter('woocommerce_hidden_order_itemmeta', 'hide_rx_step_id_order_itemmeta');
add_action('woocommerce_init', 'rx_step_init');
add_action('woocommerce_after_calculate_totals', 'remove_coupon_code');
add_action('woocommerce_order_status_processing', 'rx_order_status_processing');

function rx_order_status_processing($order_id)
{
    $order = wc_get_order($order_id);
    $items = $order->get_items();

    $update_data = [
        'order_id' => $order_id,
    ];

    foreach ($items as $key => $val) {
        if (!empty($val['rx_step_id'])) {
            $rx_step_id = $val['rx_step_id'];
            update_rx_step($update_data, $rx_step_id);
        }
    }
}

function remove_coupon_code($cart)
{
    if (empty($cart->applied_coupons)) {
        foreach ($cart->cart_contents as $key => $val) {
            if (!empty($val['rx_step_id'])) {
                $update_data = [
                    'lens_discount_amount' => 0,
                    'frame_discount_amount' => 0,
                    'rush_discount_amount' => 0,
                    'discount' => 0,
                    'coupon_code' => '',
                    'discount_price' => 0,
                ];
                update_rx_step($update_data, $val['rx_step_id']);
            }
        }
    }
}

function rx_step_init()
{
    add_filter('woocommerce_coupon_get_discount_amount', 'add_coupon_in_rx_step_id', 15, 5);
}

/**
 * @param           $discount
 * @param           $discounting_amount
 * @param           $cart_item
 * @param           $single
 * @param WC_Coupon $coupon
 *
 * @return bool|float
 * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 */
function add_coupon_in_rx_step_id($discount, $discounting_amount, $cart_item, $single, WC_Coupon $coupon)
{
    if (is_null($cart_item)) {
        return $discount;
    }

    $coupon_apply_keys = ['apply_to_frame', 'apply_to_lenses', 'apply_to_rush_fee'];

    $get_coupon_options = function (WC_Coupon $coupon) use ($coupon_apply_keys) {
        $result = [];
        foreach ($coupon_apply_keys as $key) {
            $value = get_post_meta($coupon->get_id(), $key, true);
            $result[$key] = $value !== '' ? $value : getDefaultApplyingByKeyR($key);
        }
        return $result;
    };

    $total_prise = $discounting_amount - $discount;
    $coupon_options = $get_coupon_options($coupon);

    $frame_discount_amount = 0;
    $lens_discount_amount = 0;
    $rush_discount_amount = 0;

    if ($coupon_options) {
        if (!array_key_exists('_all_lens_data', $cart_item) ||
            !isset(
                $cart_item['_all_lens_data']['rush_price'],
                $cart_item['_all_lens_data']['lens_price'],
                $cart_item['_all_lens_data']['total_price']
            )) {
            return false;
        }

        if ($coupon_options['apply_to_frame']) {
            $frame_amount = $cart_item['_all_lens_data']['total_price']
                - $cart_item['_all_lens_data']['lens_price']
                - $cart_item['_all_lens_data']['rush_price'];

            if ($discounting_amount !== $frame_amount) {
                $frame_discount_amount = $coupon->get_discount_amount($frame_amount, null, false);
            }
        }

        if ($coupon_options['apply_to_lenses']) {
            $lens_discount_amount = $coupon->get_discount_amount(
                $cart_item['_all_lens_data']['lens_price'],
                null,
                false
            );
        }

        if ($coupon_options['apply_to_rush_fee']) {
            $rush_discount_amount = $coupon->get_discount_amount(
                $cart_item['_all_lens_data']['rush_price'],
                null,
                false
            );
        }


    }

    $update_data = [
        'lens_discount_amount' => $lens_discount_amount,
        'frame_discount_amount' => $frame_discount_amount,
        'rush_discount_amount' => $rush_discount_amount,
        'discount' => $discount,
        'coupon_code' => $coupon->get_code(),
        'discount_price' => $total_prise,
    ];

    update_rx_step($update_data, $cart_item['rx_step_id']);
    return $discount;
}

function update_rx_step($update_data, $rx_step_id)
{
    global $wpdb;

    $wpdb->update(
        'rx_step',
        $update_data,
        ['id' => $rx_step_id]
    );
}

function getDefaultApplyingByKeyR($key): int
{
    $needed = constant(strtoupper($key));
    $default = ((COUPON_APPLY_DEFAULTS & $needed) ? 1 : 0);
    return $default;
}


function hide_rx_step_id_order_itemmeta($hidden_fields)
{
    return array_merge($hidden_fields, ['_rx_step_id']);
}

function insert_into_rx_step($cart_item_data, $product_id)
{
    if (array_key_exists('_all_lens_data', $cart_item_data)) {
        $all_data = $cart_item_data['_all_lens_data'];

        global $wpdb;

        $step_data = toStepData($all_data);
        if (!empty($step_data)) {
            $wpdb->insert(
                'rx_step',
                $step_data
            );
            $cart_item_data['rx_step_id'] = $wpdb->insert_id;
        } else {
            $cart_item_data['rx_step_id'] = 0;
        }
    }
    return $cart_item_data;
}

/**
 * @param array $all_data
 *
 * @return array
 * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 */
function toStepData($all_data)
{


    $step_data = [];
    if (isset(
        $all_data['product_id'],
        $all_data['lenses']['ltype'],
        $all_data['lenses']['ltint'],
        $all_data['lenses']['ltint_option'],
        $all_data['lenses']['lpackage'],
        $all_data['lenses']['enhanceAccuracy'],
        $all_data['lenses']['lrush'],
        $all_data['rush_price'],
        $all_data['lens_price'],
        $all_data['total_price'],
        $all_data['date'],
        $all_data['prescription']['od_sphere'],
        $all_data['prescription']['os_sphere'],
        $all_data['prescription']['pd_1'],
        $all_data['prescription']['pd_2'],
        $all_data['prescription']['chk_pd_2'],
        $all_data['prescription']['prism'],
        $all_data['prescription']['od_vp'],
        $all_data['prescription']['os_vp']
    )) {

        $step_data = [
            'product_id'      => $all_data['product_id'],
            'ltype'           => $all_data['lenses']['ltype'],
            'ltint'           => $all_data['lenses']['ltint'],
            'ltint_option'    => $all_data['lenses']['ltint_option'],
            'lpackage'        => $all_data['lenses']['lpackage'],
            'enhanceAccuracy' => $all_data['lenses']['enhanceAccuracy'],
            'lrush'           => $all_data['lenses']['lrush'],
            'rush_price'      => $all_data['rush_price'],
            'lens_price'      => $all_data['lens_price'],
            'total_price'     => $all_data['total_price'],
            'data'            => $all_data['date'],
            'od_sphere'       => $all_data['prescription']['od_sphere'],
            'os_sphere'       => $all_data['prescription']['os_sphere'],
            'pd_1'            => $all_data['prescription']['pd_1'],
            'pd_2'            => $all_data['prescription']['pd_2'],
            'checked_pd_2'    => $all_data['prescription']['chk_pd_2'],
            'checked_prism'   => $all_data['prescription']['prism'],
            'od_prism'        => $all_data['prescription']['od_vp'],
            'os_prism'        => $all_data['prescription']['os_vp'],
        ];
    }
    return $step_data;
}
