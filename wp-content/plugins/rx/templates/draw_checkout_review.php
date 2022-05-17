<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
?>
<div id="checkout-info-group-block" class="col-2 hide-for-small-only">
    <h3 id="order_review_heading"
        class="checkout--details-title"><?php _e('ORDER SUMMARY', 'woocommerce'); ?></h3>
    <div id="order_review" class="woocommerce-checkout-review-order">
        <!-- ong_info checkout PRESCRIPTION LENS-->
        <div id="shopping--content-wrap-id" class="shopping--content-wrap row middle--row">
            <div class="shopping--content-left large-12 medium-12 small-12 hide-for-small-only">
                <?php
                $rx_count = 0;
                $rx_count_rush = 0;
		$single_vision = 0;
		$bifocal = 0;
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product = apply_filters(
                        'woocommerce_cart_item_product',
                        $cart_item['data'],
                        $cart_item,
                        $cart_item_key
                    );
                    $product_id = apply_filters(
                        'woocommerce_cart_item_product_id',
                        $cart_item['product_id'],
                        $cart_item,
                        $cart_item_key
                    );

/*
echo '<pre>';
print_r($cart_item);
echo '</pre>';   /**/

                    if ($_product &&
                        $_product->exists() &&
                        $cart_item['quantity'] > 0 &&
                        apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
                    ) :
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink',
                            $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                            $cart_item,
                            $cart_item_key
                        );


                        if( $cart_item['_all_lens_data']['type']<>'' ){
                            $rx_count += 1;

                            if($cart_item['_all_lens_data']['rush']==1 ){
                               $rx_count_rush += 1;
                            }
                            if($cart_item['_all_lens_data']['rush_3day']==1 ){
                                $rx_count_rush += 1;
                            }
                            if (strpos($cart_item['_all_lens_data']['type'], 'Single Vision') !== false) {
                                $single_vision += 1;
                            }
                            if (strpos($cart_item['_all_lens_data']['type'], 'Bifocal') !== false) {
                                $bifocal += 1;
                            }


                        }



                        ?>

                        <div class="large-12 shopping--content-wrap-block clearfix">
                            <div class="large-12 shopping--content-top shopping--content-top-1">
                                <!--photo checkout-->
                                <?php

//                                $thumbnail = apply_filters(
//                                    'woocommerce_cart_item_thumbnail',
//                                    $_product->get_image(150),
//                                    $cart_item,
//                                    $cart_item_key
//                                );

                                echo $_product->get_image();


//                                if (!$product_permalink) {
//                                    echo $thumbnail;
//                                } else {
//                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
//                                }
                                ?>

                                <div class="card-section">

                                    <!--name-->
                                    <?php
                                    if (!$product_permalink) {
                                        echo "Model: <span>" . apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;' . "</span>";
                                    } else {
                                        echo  "Model: ".$_product->get_title().'<br>';
                                    }
                                    if( $_product->get_type()== 'variation' ){
					echo "Size: ".$cart_item['variation']['attribute_pa_size'] ??  '';
					echo "<br/>Color: ".Ong_String_Helper::underscoreToCamel($cart_item['variation']['attribute_pa_color'] ??  '');
                                        //echo $cart_item['variation']['attribute_pa_size'].'<br>';
                                    }
                                    ?>

                                    <!--price-->
                                    <?=""//apply_filters(
                                        //'woocommerce_cart_item_subtotal',
                                        //WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
                                        //$cart_item,
                                        //$cart_item_key
                                    //)?>
                                    <?php
                                    /* color*/
                                    echo wc_get_formatted_cart_item_data($cart_item);
                                    ?>

                                </div>

                            </div>
                            <div class="large-6 shopping--content-top shopping--content-top-2">
                                <!-- LENS-->
                                <p class="checkout--product-title">YOUR LENS</p>
                                <?= apply_filters('rx_cart_item_lens', '', $cart_item, $cart_item_key);?>
                            </div>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
            <div class="shopping--content-left large-12 medium-12 small-12 show-for-small-only">

                <?php
                foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                    $_product = apply_filters(
                        'woocommerce_cart_item_product',
                        $cart_item['data'],
                        $cart_item,
                        $cart_item_key
                    );
                    $product_id = apply_filters(
                        'woocommerce_cart_item_product_id',
                        $cart_item['product_id'],
                        $cart_item,
                        $cart_item_key
                    );

                    if ($_product &&
                        $_product->exists() &&
                        $cart_item['quantity'] > 0 &&
                        apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)
                    ) :
                        $product_permalink = apply_filters('woocommerce_cart_item_permalink',
                            $_product->is_visible() ? $_product->get_permalink($cart_item) : '',
                            $cart_item,
                            $cart_item_key
                        );



                    ?>

                    <div class="large-12 shopping--content-wrap-block clearfix">
                        <div class="large-12 shopping--content-top shopping--content-top-1">
                            <!--photo checkout-->
                            <?php
//                                $thumbnail = apply_filters(
//                                    'woocommerce_cart_item_thumbnail',
//                                    $_product->get_image(150),
//                                    $cart_item,
//                                    $cart_item_key
//                                );

                            echo $_product->get_image();

//                                if (!$product_permalink) {
//                                    echo $thumbnail;
//                                } else {
//                                    printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail);
//                                }
                            ?>

                            <div class="card-section">
                                <!--name-->
                                <?php
                                    if (!$product_permalink) {
                                        echo "<span>" . apply_filters('woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key) . '&nbsp;' . "</span>";
                                    } else {
                                        echo apply_filters('woocommerce_cart_item_name',
                                            sprintf(
                                                '<a href="%s">%s</a>',
                                                esc_url($product_permalink),
                                                $_product->get_title()),
                                            $cart_item, $cart_item_key
                                        );
                                    }
                                ?>

                                <!--price-->
                                <?=apply_filters(
                                    'woocommerce_cart_item_subtotal',
                                    WC()->cart->get_product_subtotal($_product, $cart_item['quantity']),
                                    $cart_item,
                                    $cart_item_key
                                )?>
                                <?=wc_get_formatted_cart_item_data($cart_item);?>
                            </div>

                        </div>

                        <ul class="accordion show-for-small-only" data-accordion data-allow-all-closed="true">
                            <li class="accordion-item" data-accordion-item>
                                <a href="#" class="accordion-title">Lens</a>
                                <div class="large-6 shopping--content-top shopping--content-top-2 accordion-content"
                                     data-tab-content>
                                    <!-- LENS-->
                                    <!--                                        <p class="checkout--product-title">LENS</p>-->
                                    <?php
                                    echo apply_filters('rx_cart_item_lens', '', $cart_item, $cart_item_key);
                                    ?>
                                </div>
                            </li>
                        <?php endif; ?>
                            <li class="accordion-item" data-accordion-item>
                                <a href="#" class="accordion-title">Prescription</a>
                                <div class="large-6 shopping--content-top shopping--content-top-3 accordion-content"
                                     data-tab-content>
                                    <!--PRESCRIPTION-->
                                    <!--                                            <p class="checkout--product-title">PRESCRIPTION</p>-->
                                    <?php echo apply_filters('rx_cart_item_prescription', '', $cart_item, $cart_item_key) ?>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- end checkout PRESCRIPTION LENS-->


        <?php
        
//echo 'XXXX'.$rx_count.'<br>';
//echo 'YYYY'.$rx_count_rush;
      // echo "Hello".$_product->get_sku(); 
            if($rx_count==1 && $rx_count_rush==0 && $_product->get_sku() != 'USEMYFRAMES' && $bifocal == 0){
                ?>

                <div id="rush" class="selector-wrapper">
<!--
                    <div class="selector_tile">
                        <div class="selector_check selector_check_passive ong_checkbox" id="div_rush_check" >
                        
                        </div>

                        <div>
                            <p class="rx-product-name" id="div_rush_check_label">
                                <span class="diamond-content"><img src="/content/plugins/rx/assets/image/ic-delivery.svg" alt="" class="rush-logo-order">&nbsp;Next Day Rush Service </span>
                                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_rush" aria-controls="RxTooltip_rush" aria-haspopup="true" tabindex="0"></a>
                            </p>
                            <input type="hidden" value="0" id="div_rush_check_state">
                        </div>
                        <p class="rx-product-description">&nbsp;</p>
                        <p class="rx-product-price"><b><span>$59.00</span></b></p>


                    </div>
-->
                    

		    <?php 
		        $myframe = $_product->get_sku();
			if($single_vision>0 && $myframe != 'USEMYFRAMES'): ?>
            
<!--
                    <div class="selector_tile">
                        <div class="selector_check selector_check_passive ong_checkbox" id="div_rush_3day_check" >
                        </div>

                        <div>
                            <p class="rx-product-name" id="div_rush_3day_check_label">
                                <span class="diamond-content"><img src="/content/plugins/rx/assets/image/glasses-in-3-days.svg" alt="" class="rush-logo-order">&nbsp;3-4 Days Guaranteed </span>
                                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_rush_3day" aria-controls="RxTooltip_rush_3day" aria-haspopup="true" tabindex="0"></a>
                            </p>
                            <input type="hidden" value="0" id="div_rush_3day_check_state">
                        </div>
                        <p class="rx-product-description">&nbsp;</p>
                        <p class="rx-product-price"><b><span>$9.00</span></b></p>

                    </div>
-->
                   
                   
                    <?php endif; ?>
               
                    </div>  
                <?php
            }

            if($rx_count==1 && $rx_count_rush==0 && $_product->get_sku() == 'USEMYFRAMES'){ ?>
                <div id="rush" class="selector-wrapper">
                    <div class="selector_tile">
                        <div class="selector_check selector_check_passive ong_checkbox" data-group="check_rush" id="two_way_rush_check">
                        </div>

                        <div>
                            <p class="rx-product-name" id="two_way_rush_check_label">
                                <span class="diamond-content"><img src="/content/plugins/rx/assets/image/ic-delivery.svg" alt="" class="rush-logo-order">2 days,Two-way rush service. </span>
                                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_rush" aria-controls="RxTooltip_rush" aria-haspopup="true" tabindex="0"></a>
                            </p>
                            <input type="hidden" value="0" id="two_way_rush_check_state">
                        </div>
                        <p class="rx-product-description">&nbsp;</p>
                        <p class="rx-product-price"><b><span>$ 94.00</span></b></p>                        
                    </div>
                </div>
           <?php }
	
	   // Check for Bifocal , No Line Bifocal rush services
	   if($rx_count==1 && $bifocal>0 && $_product->get_sku() != "USEMYFRAMES") { ?>
                <div id="rush" class="selector-wrapper">
<!--
                    <div class="selector_tile">
                        <div class="selector_check selector_check_passive ong_checkbox" id="div_rush_check" >

                        </div>

                        <div>
                            <p class="rx-product-name" id="div_rush_check_label">
                                <span class="diamond-content"><img src="/content/plugins/rx/assets/image/ic-delivery.svg" alt="" class="rush-logo-order">&nbsp;Next Day Rush Service </span>
                                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_rush" aria-controls="RxTooltip_rush" aria-haspopup="true" tabindex="0"></a>
                            </p>
                            <input type="hidden" value="0" id="div_rush_check_state">
                        </div>
                        <p class="rx-product-description">&nbsp;</p>
                        <p class="rx-product-price"><b><span>$59.00</span></b></p>


                    </div>
-->
<!--
                    <div class="selector_tile">
                        <div class="selector_check selector_check_passive ong_checkbox" id="div_rush_3day_check" >
                        </div>

                        <div>
                            <p class="rx-product-name" id="div_rush_3day_check_label">
                                <span class="diamond-content"><img src="/content/plugins/rx/assets/image/glasses-in-3-days.svg" alt="" class="rush-logo-order">&nbsp;3-4 Days Guaranteed </span>
                                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_rush_3day" aria-controls="RxTooltip_rush_3day" aria-haspopup="true" tabindex="0"></a>
                            </p>
                            <input type="hidden" value="0" id="div_rush_3day_check_state">
                        </div>
                        <p class="rx-product-description">&nbsp;</p>
                        <p class="rx-product-price"><b><span>$9.00</span></b></p>

                    </div>
-->
	<?php
	   }
	?>


        <!--Subtotal and Shipping - checkout/review-order.php -->
        <?php do_action('ong_woocommerce_checkout_order_review'); ?>
    </div>
    <!--                --><?php //do_action( 'woocommerce_checkout_shipping' ); ?>
</div>
