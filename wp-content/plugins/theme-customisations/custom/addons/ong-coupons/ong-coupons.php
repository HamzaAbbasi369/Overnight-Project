<?php

define('APPLY_TO_FRAME', 1);
define('APPLY_TO_LENSES', 2);
define('APPLY_TO_RUSH_FEE', 4);
define('COUPON_APPLY_DEFAULTS', APPLY_TO_FRAME | APPLY_TO_LENSES);

require_once(dirname(__FILE__) . '/register-coupon-options.php');
require_once(dirname(__FILE__) . '/shortcodes.php');


//add_action('woocommerce_cart_collaterals', 'sticky_coupons_block_modal');
add_action('woocommerce_cart_collaterals', 'filter_sticky_coupons_block_modal');

function sticky_coupons_block_modal()
{
    $show_modal = do_shortcode('[ong_coupon_for_container count=10 meta="for_cart" orderby="rand"]');

    if (!empty($show_modal)) {
        ?>
        <div class="reveal sticky_coupons_block_modal" id="stickyCoupons_block" aria-labelledby="exampleModalHeader11" data-reveal>
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center">
                <h2>Limited Time Specials</h2>
            </div>
            <div class="row text-center">
                <div class="sticky_coupons_block_wrap">
                    <?php echo $show_modal; ?>
                </div>
            </div>
        </div>
        <?php
    }

    $show = do_shortcode('[ong_coupon_for_container count=3 meta="for_cart" orderby="rand"]');
    if (!empty($show)) {
        ?>
        <div class="sticky sticky_coupons_block stickyCart">
            <div><h2>Limited Time Specials</h2></div>
            <?php echo $show ?>

        </div>
        <?php
    }
}

add_action('filter-form-before-products', 'filter_sticky_coupons_block_modal');
function filter_sticky_coupons_block_modal()
{
    $show_modal = do_shortcode('[ong_coupon_for_container count=10 meta="for_cart" orderby="rand"]');
    if (!empty($show_modal)) {
        ?>
        <div class="reveal sticky_coupons_block_modal" id="stickyCoupons_filter" aria-labelledby="stickyCoupons_filter" data-reveal>
            <button class="close-button" data-close aria-label="Close modal" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <div class="text-center">
                <h2>Limited Time Specials</h2>
            </div>
            <div class="row text-center">
                <div class="sticky_coupons_block_wrap">
                    <?php echo $show_modal; ?>
                </div>
            </div>
        </div>
        <div class="sticky_coupons_block_modal_button_first">
            <div class="modal_button_wrap">
                <div class="sticky_coupons_content_wrap" data-open="stickyCoupons_filter">
                    <div class="icon_wrap hide-for-small-only">
                        <svg width="33px" height="27px" viewBox="0 0 33 27" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <!-- Generator: Sketch 46.2 (44496) - http://www.bohemiancoding.com/sketch -->
                            <title>icon</title>
                            <desc>Created with Sketch.</desc>
                            <defs></defs>
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="ONG_Coupons_aside_desktop_NewDesign" transform="translate(-1567.000000, -836.000000)" fill-rule="nonzero" fill="#FFFFFF">
                                    <g id="!!!Button" transform="translate(1515.000000, 790.000000)">
                                        <g id="text+icon" transform="translate(17.000000, 46.000000)">
                                            <g id="icon" transform="translate(35.000000, 0.000000)">
                                                <path d="M1.394245,12.051328 C1.81990558,11.2489827 2.39747436,10.5295039 3.10443402,9.94249153 L3.04848604,9.72764253 C2.46547618,7.48879301 3.75670589,5.18753353 5.93252831,4.58763561 L22.0493806,0.144044046 C24.225203,-0.455853874 26.4616785,0.872778908 27.0446883,3.11162842 L27.1006363,3.32647743 C29.7292365,3.75012994 31.9487713,5.72889554 32.663779,8.47463751 C33.0859863,10.0959799 32.9239235,11.7556193 32.29407,13.1969814 C32.7474681,14.1427059 33,15.2038823 33,16.3172413 C33,19.1598425 31.3538186,21.6622815 28.9213483,22.7715374 C28.9213483,25.1210295 27.0952738,27 24.8426966,27 L8.15730337,27 C5.90472625,27 4.07865169,25.1210295 4.07865169,22.8032019 C1.64618143,21.6622815 0,19.1598425 0,16.3172413 C0,14.7274755 0.514887206,13.2441045 1.394245,12.051328 Z M27.1831276,21.5178482 C29.4708652,20.9467594 31.1666667,18.8649804 31.1666667,16.3842872 C31.1666667,13.9035941 29.4708652,11.8218151 27.1831276,11.2507263 L27.1831276,10.001422 C27.1831276,8.59135831 26.0481786,7.44827586 24.6481481,7.44827586 L8.35185185,7.44827586 C6.95182137,7.44827586 5.81687243,8.59135831 5.81687243,10.001422 L5.81687243,11.2507263 C3.52913482,11.8218151 1.83333333,13.9035941 1.83333333,16.3842872 C1.83333333,18.8649804 3.52913482,20.9467594 5.81687243,21.5178482 L5.81687243,22.5847849 C5.81687243,23.9948486 6.95182137,25.137931 8.35185185,25.137931 L24.6481481,25.137931 C26.0481786,25.137931 27.1831276,23.9948486 27.1831276,22.5847849 L27.1831276,21.5178482 Z" id="Combined-Shape"></path>
                                                <path d="M16.8995053,15.4345532 L16.8995053,12.7113759 C18.2782155,12.7408794 18.2679553,14.2735887 19.0772261,14.2735887 C19.500458,14.2735887 19.8640528,13.9453617 19.8640528,13.3862695 C19.8640528,11.9819007 17.8703738,11.2804539 16.8995053,11.2509504 L16.8995053,10.4875461 C16.8995053,10.2434043 16.7366251,10 16.5237266,10 C16.3133932,10 16.1524368,10.2434043 16.1524368,10.4875461 L16.1524368,11.2509504 C14.5954562,11.3062695 13.1872481,12.3116028 13.1872481,14.2588369 C13.1872481,15.8498156 14.3056064,16.7813901 16.1524368,17.1671489 L16.1524368,20.1624965 C14.0818065,20.0621844 15.1668193,18.0847092 13.7714364,18.0847092 C13.3007512,18.0847092 13,18.4158865 13,18.9875177 C13,20.1204539 14.0471785,21.5676028 16.1524368,21.6266099 L16.1524368,22.5131915 C16.1524368,22.7565957 16.3133932,23 16.5237266,23 C16.7366251,23 16.8995053,22.7565957 16.8995053,22.5131915 L16.8995053,21.6266099 C18.754672,21.4975319 20,20.5217021 20,18.6017589 C20,16.394156 18.5443386,15.836539 16.8995053,15.4345532 Z M16.5,15.5714286 C15.3303899,15.3368842 14.75,14.8878079 14.75,14.1081281 C14.75,13.4405172 15.4363532,12.817734 16.5,12.7857143 L16.5,15.5714286 Z M16.5,20.2142857 L16.5,17.4285714 C17.2655793,17.5958881 18.25,17.8776465 18.25,18.8337419 C18.25,19.7623133 17.3519448,20.1585135 16.5,20.2142857 Z" id="Shape"></path>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </div>
                    <p class="sticky_coupons--title">
                        My Special Deals
                    </p>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * @param $key
 *
 * @return int
 * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 */
function getDefaultApplyingByKey( $key ): int {
	$needed  = constant( strtoupper( $key ) );
	$default = ( ( COUPON_APPLY_DEFAULTS & $needed ) ? 1 : 0 );
	return $default;
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
function frame_lens_rush_discount($discount, $discounting_amount, $cart_item, $single, WC_Coupon $coupon)
{
    if (!is_array($cart_item) || !array_key_exists('wdm_package_price_value', $cart_item)) {
        return $discount;
    }

    $coupon_apply_keys = ['apply_to_frame', 'apply_to_lenses', 'apply_to_rush_fee'];

    $get_coupon_options = function(WC_Coupon $coupon) use ($coupon_apply_keys) {
        $result = [];
	    foreach ($coupon_apply_keys as $key) {
	        $value = get_post_meta($coupon->get_id(), $key, true);
            $result[$key] = $value !== '' ? $value : getDefaultApplyingByKey($key);
        }
        return $result;
    };

    if ($coupon_options = $get_coupon_options($coupon)) {
        if (!array_key_exists('_all_lens_data', $cart_item) || !isset(
                $cart_item['_all_lens_data']['rush_price'],
                $cart_item['_all_lens_data']['lens_price'],
                $cart_item['_all_lens_data']['total_price']
            )) {
            return false;
        }

        $new_discount_amount = 0;
        if ($coupon_options['apply_to_frame']) {
            $new_discount_amount += $cart_item['_all_lens_data']['total_price']
                            - $cart_item['_all_lens_data']['lens_price']
                            - $cart_item['_all_lens_data']['rush_price'];
        }

        if ($coupon_options['apply_to_lenses']) {
            $new_discount_amount += $cart_item['_all_lens_data']['lens_price'];
        }

        if ($coupon_options['apply_to_rush_fee']) {
            $new_discount_amount += $cart_item['_all_lens_data']['rush_price'];
        }

        if ($discounting_amount != $new_discount_amount) {
            $discount = $coupon->get_discount_amount( $new_discount_amount, $cart_item, true );
        }
    }
    return $discount;
}
add_filter('woocommerce_coupon_get_discount_amount', 'frame_lens_rush_discount', 10, 5);
