<!--<div id="package_data_header" >-->
<!--<b>YOUR EYEGLASSES</b>-->
<!--</div>-->

<div class="rx-product-header-name" style="margin-top: 10px">Order summary</div>
<div id="package_data" style="width:100% margin:15px; overflow:hidden;">

    <div id="package_image" style="width:100%; text-align:center;">
        <image id="order_fimage" src="<?= $pimage ?>" style="width:auto; height:auto;"></image>
    </div>
    <div id="package_title" class="rx-product-header-name">
        <p id="order_ftype">Package Preview</p>
    </div>
	<div class="rx-product-header-name" style="font-size:2rem;margin-top: 10px">All Lens Packages Include:</div>
	<div style='margin-top: 20px;' id="right-box-order-det">
	<p class="rx-product-header-name" style="border-bottom:1px solid #92844d;margin-bottom:5px;font-weight:500;padding-bottom:10px;">UV Protection</p>
	<p class="rx-product-header-name" style="border-bottom:1px solid #92844d;margin-bottom:5px;font-weight:500;padding-bottom:10px;">Premium Anti-Glare</p>
	<p class="rx-product-header-name" style="border-bottom:1px solid #92844d;margin-bottom:5px;font-weight:500;padding-bottom:10px;">Easy-Clean Coating</p>
	<p class="rx-product-header-name" style="border-bottom:1px solid #92844d;margin-bottom:5px;font-weight:500;padding-bottom:10px;">Anti-Smudge Coating</p>
	<p class="rx-product-header-name" style="border-bottom:1px solid #92844d;margin-bottom:5px;font-weight:500;padding-bottom:10px;">Water Replant Coating</p>
	<p class="rx-product-header-name" style="margin-bottom:5px;font-weight:500;">Scratch Resistant Coating</p>
	</div>
<!--    <p id="order_lens_design"></p>-->

    <div style="clear:both"></div>
    <div style='margin-top: 50px;' id="right-box-order-det">
        <div id="package_frame" style="padding:0; list-style-type:none; line-height:18px;">
            <div class="package_bold float-left rx-package-description">Frame</div>
            <div class="package_bold float-right rx-product-name rx-product-custom-price"><?= to_price($fprice,
                    $frame_regular_price) ?></div>
            <div class="package_frame_clear" style="clear:both;"></div>
        </div>

        <span class="rx-product-header-name">Lenses</span><br/>
        <ul style="margin:0; padding:0; list-style-type:none; line-height:18px;">
            <li id="c_prescription_type" style="display:none;">
                <div class="float-left">Type:</div>
                <div class="float-right rx-product-name" id="lens_type"></div>
                <div style="clear:both;"></div>
            </li>
            <li class="rx-package-description" id="c_frame_style"></li>
            <li class="rx-package-description" id="c_enhance_rx_accuracy"></li>
            <li class="rx-package-description" id="c_impact_resistant"></li>
            <li class="rx-package-description" id="c_ptype"></li>
            <li class="rx-package-description" id="c_prism"></li>
            <li class="rx-package-description" id="c_purpose"></li>
            <li class="rx-package-description" id="c_distance"></li>
            <li class="rx-package-description" id="c_premium"></li>

            <li class="rx-package-description" id="c_material"></li>
            <li class="rx-package-description" id="c_tint"></li>
            <li class="rx-package-description" id="c_diamond"></li>
            <li class="rx-package-description" id="c_blue_diamond"></li>
            <li class="rx-package-description" id="c_diamond_warranty"></li>

            <div class="wrap-free-packages">
                <li class="rx-package-description" id="include"></li>
                <li class="rx-package-description" id="c_coating"></li>
                <li class="rx-package-description" id="c_easy_clean"></li>
                <li class="rx-package-description" id="c_uv"></li>
                <li class="rx-package-description" id="c_scratch"></li>
            </div>
            <li class="rx-package-description" id="c_subtotal_wrapper">
                <div class="package_bold float-left rx-package-description">Lens Subtotal:</div>
                <div class="package_bold float-right rx-product-name rx-product-custom-price" id="c_lens_subtotal">$0</div>
                <div class="package_frame_clear" style="clear:both;"></div>
            </li>
            <li class="rx-package-description" id="c_rush"></li>
            <li id="c_subtotal_wrapper">
                <div class="package_bold float-left rx-package-description">Total:</div>
                <div class="float-right rx-product-name rx-product-custom-price" id="price_subtotal">$<?= number_format($price, 2) ?></div>
                <div style="clear:both;"></div>
            </li>
            <li id="c_discount" style="display:none">
                <div class="float-left" style="font-weight:bold; font-size:14px">You save:</div>
                <div class="float-right rx-product-name" id="price_discount"><span class="new-price">$<?= number_format($price, 2) ?></span></div>
                <div style="clear:both;"></div>
            </li>
        </ul>
        <!-- value -->
        <input type="hidden" name="rx_package" id="rx_package" value=""/>
        <input type="hidden" name="quantity" id="quantity" value="1"/>
        <input type="hidden" name="dipsplay_mode" id="d_mode" value="<?= $d_mode ?>"/>
        <input type="hidden" name="lsourcePage" id="lsourcePage" value=""/>
        <input type="hidden" name="lusage" id="lusage" value=""/>
        <input type="hidden" name="lpurpose" id="lpurpose" value=""/>
        <input type="hidden" name="ldistance" id="ldistance" value=""/>
        <input type="hidden" name="lpremium" id="lpremium" value=""/>
        <input type="hidden" name="lfreeform" id="lfreeform" value="0"/>
        <input type="hidden" name="limpac" id="limpact" value="0"/>
        <input type="hidden" name="ltint" id="ltint" value=""/>
        <input type="hidden" name="ltint_option" id="ltint_option" value="No Tint"/>
        <input type="hidden" name="lpackage" id="lpackage" value=""/>
        <input type="hidden" name="lcoating" id="lcoating" value=""/>
        <input type="hidden" name="leasyclean" id="leasyclean" value=""/>
        <input type="hidden" name="lrush" id="lrush" value=""/>
        <input type="hidden" name="lrush_3day" id="lrush_3day" value=""/>
        <input type="hidden" name="ldiamond" id="ldiamond" value=""/>
        <input type="hidden" name="lpackagetype" id="lpackagetype" value=""/>
        <!-- price -->
        <input type="hidden" name="type_price" id="type_price" value="0"/>
        <input type="hidden" name="prism_price" id="prism_price" value="0"/>
        <input type="hidden" name="purpose_price" id="purpose_price" value="0"/>
        <input type="hidden" name="distance_price" id="distance_price" value="0"/>
        <input type="hidden" name="premium_price" id="premium_price" value="0"/>
        <input type="hidden" name="enhance_rx_accuracy_price" id="enhance_rx_accuracy_price" value="0"/>
        <input type="hidden" name="impact_price" id="impact_price" value="0"/>
        <input type="hidden" name="total_price" id="total_price" value=""/>
        <input type="hidden" name="easy_clean_price" id="easy_clean_price" value="0"/>
        <input type="hidden" name="frame_subtotal" id="frame_subtotal" value="<?= $price ?>"/>
        <input type="hidden" name="pfactor" id="pfactor" value="<?= $pfactor ?>"/>
        <input type="hidden" name="material_price" id="material_price" value="0"/>
        <input type="hidden" name="coating_price" id="coating_price" value="0"/>
        <input type="hidden" name="tint_price" id="tint_price" value="0"/>
        <input type="hidden" name="diamond_price" id="diamond_price" value="0"/>
        <input type="hidden" name="rush_price" id="rush_price" value="0"/>
        <input type="hidden" name="lens_price" id="lens_price" value="0"/>
        <input type="hidden" name="product_id" size="2" value="1155"/>
    </div>
</div>
