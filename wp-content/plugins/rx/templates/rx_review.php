<?php
/** @var float $usage_reading */
/** @var float $usage_distance */
/** @var float $usage_bifocal */
/** @var float $usage_progressive */
/** @var float $impact_resistant_fee */
/** @var float $tint_sun_tint */
/** @var float $tint_polarized */
/** @var float $tint_lr_p */
/** @var float $tint_lr_t */
/** @var float $tint_lr_xtr_a */
/** @var float $rush_service_fee */
/** @var float $rush_service_ge150_fee */
/** @var float $rush_3day_service_fee */
/** @var float $easy_clean_fee */
/** @var float $anti_glare_fee */
/** @var float $premium_anti_glare_fee */
/** @var float $computer_anti_glare_fee */
/** @var float $diamond_fee */
include WcRx::plugin_path() . '/includes/rx_fees.php';
?>

<div id="step6" style="display:none">
    <div class="row padding-for-step-6">
        <div class="rx-product-header-name">
            <h1 class="rx-product-header-name">REVIEW ORDER</h1>
        </div>
    </div>
    <div class="golden-box">

        <div class="row padding-for-step-6">
            <!--                <p class="rx-product-description">Eyeglass Frames</p>-->
            <img src="<?= $pimage ?>" alt="">
        </div>
        <div class="row padding-for-step-6">
            <p class="rx-product-header-name" id="frame_title">Eyeglass Frames</p>
            <p class="rx-product-review">Premium Microfiber Cloth</p>
            <p class="rx-product-review">Free Eyeglass Case</p>
            <p class="rx-product-review">Money Back Guarantee</p>
            <p class="rx-product-review">Next Day Shipping Available</p>
        </div>


        <div class="row padding-for-step-6 description-review-order">
            <p style="margin-top: 15px;" class="rx-product-header-name">Prescription:</p>
            <div class="rx-product-review" id="rx_placeholder">
                RX goes here
            </div>
        </div>
        <div class="clearfix description-review-order">
            <div class="large-6 columns right-eye-review rx-eye-review"></div>
            <div class="large-6 columns left-eye-review rx-eye-review"></div>
        </div>

    </div>


    <div class="rx_placeholder rush_placeholder">
        <p style="margin-top: 15px; margin-bottom:0px;" class="rx-product-header-name">Diamond Coating:</p>

        <?php

        if (get_option('is_show_diamond_anti_glare_coating_review_order_rx') === 'yes') {

            $title = "";
            $tooltip = get_option('i_popup_for_rx_special_processing_diamond');
            $html = "<b>" . to_price(($diamond_fee), $pfactor) . "</b>";
            $action = "rx.diamond.change();";
            $id = "diamond";
            $description = '&nbsp';
            draw_checkbox($id, $action, $tooltip, $title, $html, $description);
        }
        ?>
<!--        <p style="margin-top: 15px;margin-bottom:0px;" class="rx-product-header-name">Faster Delivery Options:</p>-->
<!--        <a class="rx-product-description" id="3_days_rush_message" target="_new" href="/shipping/">Due to lens complexity, Progressive and Bifocal lenses require 2 more production days.</a>-->
        <?php

        $two_way_rush = false;
        if (!isset($_REQUEST['fprice'])) {
            $fprice = '0';
        } else {
            $fprice = $_REQUEST['fprice'];
        }
        if (isset($_REQUEST['product_id'])) {
            $product_id = $_REQUEST['product_id'];
            $product = wc_get_product($product_id);
            $product_title = $product->get_title();
            if ($product_title == "Your Frames") {
                $two_way_rush = true;
            }
            //print_r($_REQUEST);
        }
        $tooltip = "";
        if ($two_way_rush) {
            ?>
            <br><a class="rx-product-description" id="your_frame_rush_message" target="_new" href="/shipping/">Lens Replacement Rush Service, includes two-way UPS Next Day Air shipping and faster processing. Get your new lenses in 2 days.</a>
            <?php
            $html = "<b>" . to_price($rx_fees['your_frame_rush_fee'], $pfactor) . "</b>";
        } else {
            $html = "<b>" . to_price((($fprice >= 150) ? $rush_service_ge150_fee : $rush_service_fee), $pfactor) . "</b>";
        }
//        $title = "Next Day Rush Service";
//        $action = "rx.previewOrder.rushClicked();";
//        $id = "rush";
//		  $description = '&nbsp';
//        draw_checkbox($id, $action, $tooltip, $title, $html, $description,"check_rush");
        ?>
        <?php
        /* 3 days rush service */

//        $tooltip = "";
//        $title = "3-4 Days Guaranteed";
//        $action = "rx.previewOrder.rush3dayClicked();";
//        $id = "rush3day";
//        $description = '&nbsp';
//        $html = "<span class='old-price'><span style='font-size: 1.7rem; '>$29</span></span>&nbsp; <b>" . to_price( $rush_3day_service_fee, $pfactor) . "</b>";
//        draw_checkbox($id, $action, $tooltip, $title, $html, $description,"check_rush");

		 /* Not in rush service 20% off */

//        $tooltip = "";
//        $title = "Not in rush? 20% off when you pick UPS 2nd day";
//        $action = "rx.previewOrder.notrush20Clicked();";
//        $id = "notrush20";
//        $description = '&nbsp';
//        $html = "<span class='old-price'><span style='font-size: 1.7rem; '>$40</span></span>&nbsp; <b>" . to_price( $notrush20_service_fee, $pfactor) . "</b>";
//        draw_checkbox($id, $action, $tooltip, $title, $html, $description,"check_rush");


        ?>
        <div class="commentbox">
            <p id="commenttitle" style="margin-top: 15px; margin-bottom:0px;" class="rx-product-header-name" onclick="rx.comment.togle();">Comments</p>
            <textarea type="textarea" id="lcomment" name="lcomment" maxlength="300" rows="5" cols="40" wrap="hard" style='font-size: 14px; font-family: Kelson;' placeholder="Write here your comments"></textarea>
        </div>
        <div class="row"></div>
    </div>


</div>
