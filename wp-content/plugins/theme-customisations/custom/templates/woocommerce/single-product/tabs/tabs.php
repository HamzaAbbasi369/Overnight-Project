<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
 

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs_desktop = apply_filters('woocommerce_product_tabs', []);
$tabs_mobile = apply_filters('woocommerce_product_tabs', []);

unset($tabs_desktop['reviews']);
unset($tabs_desktop['additional_information']);
if (!empty($tabs_desktop)) : ?>
<!--    <div class="product-description-block hide-for-small-only">-->
<!--    --><?php //foreach ($tabs_desktop as $key => $tab) : ?>
<!--        --><?php //call_user_func($tab['callback'], $key, $tab); ?>
<!--    --><?php //endforeach; ?>
<!--    </div>-->
<?php endif; ?>

<!-- removed related_prodcts shortcode -->

<div class="secure-icons-wrap text-center secure-icons-wrap-single-desktop single-product-icons">
    <div class="secure-icons-wrap-block">
        <i class="spriteic-satisfaction"></i>
        <i class="spriteic-made-in-usa"></i>
        <i class="spriteic-free-shipping"></i>
        <i class="spriteic-free-transactions"></i>
        <i class="spriteic-free-genuine-brand"></i>
        <i class="spriteic-free-money-back"></i>
        <i class="spriteic-free-best-price"></i>
    </div>
</div>

<!--<div class="secure-icons-wrap secure-icons-wrap-single-mobile text-center single-product-icons show-for-medium-only">-->
<!--    <div class="secure-icons-wrap-block">-->
<!--        <i class="mobileic-satisfaction"></i>-->
<!--        <i class="mobileic-made-in-usa"></i>-->
<!--        <i class="mobileic-free-shipping"></i>-->
<!--        <i class="mobileic-free-transactions"></i>-->
<!--        <i class="mobileic-free-genuine-brand"></i>-->
<!--        <i class="mobileic-free-money-back"></i>-->
<!--        <i class="mobileic-free-best-price"></i>-->
<!--    </div>-->
<!--</div>-->

    <div class="product--shipping-returns hide-for-small-only">
        <p class="product--shipping-title">
            FREE SHIPPING AND RETURNS. SATISFACTION GUARANTEED
        </p>
        <p class="product--shipping">
           Free USPS first class shipping and returns with any order. No questions asked return policy within 7 days of receiving your new glasses. 2 years warranty on any Diamond Anti-Glare coating upgrades. Progressive glasses are covered for any issue such as non-adaptation and design changes.
	</p>
    </div>
<?php if ( ! empty( $tabs_mobile ) ) :
    
global $product;
$productlinedescription = "";
$terms = get_the_terms($product->get_id(), 'pa_product-line');?>

    <!--    for mobile version-->
    <ul id="accordion--for-small" class="accordion show-for-small-only" data-accordion data-allow-all-closed="true">
        <?php foreach ($tabs_mobile as $key => $tab) : 
            
           if($key != 'description'){ ?>
            <li class="accordion-item" data-accordion-item>
                <a href="#" class="accordion-title">
                    <?php echo apply_filters('woocommerce_product_'.$key.'_tab_title', esc_html($tab['title']), $key); ?>
                </a>
                <div class="accordion-content" data-tab-content>
                    <?php call_user_func($tab['callback'], $key, $tab); ?>
                </div>
            </li>
           <?php } else { ?>
            <div class="custom_single_discription">
            <?php call_user_func($tab['callback'], $key, $tab); ?>
            </div>
            <?php } ?>
        <?php endforeach; ?>

        <?php

    if ($terms) {
        foreach ($terms as $term) {
            if ($term->description) {
                    $productlinedescription .="
                                <li class='accordion-item' data-accordion-item=''>
                                    <a href='#' class='accordion-title'>
                                    CATEGORY Description
                                    </a>
                                    <div class='accordion-content' data-tab-content='' role='tabpanel' aria-labelledby='3q931e-accordion-label' aria-hidden='true' id='3q931e-accordion'>
                                        <h2>CATEGORY DESCRIPTIONssss</h2>
                                        {$term->description}
                                    </div>
                                </li>";
            }
        }
    }
    echo $productlinedescription;?>


        <li class="accordion-item" data-accordion-item>
            <a href="#" class="accordion-title"  aria-controls="111ibzciq-accordion" role="tab" id="111ibzciq-accordion-label" aria-expanded="false" aria-selected="false">FREE SHIPPING AND RETURNS. SATISFACTION GUARANTEED</a>
            <div class="accordion-content" data-tab-content="" role="tabpanel" aria-labelledby="111ibzciq-accordion-label" aria-hidden="true" id="111ibzciq-accordion" style="display: none;">
                <div class="product--shipping-returns">
                    <p class="product--shipping">
                        Free USPS first class shipping and returns with any order. No questions asked return policy within 7 days of receiving your new glasses. 2 years warranty on any Diamond Anti-Glare coating upgrades. Progressive glasses are covered for any issue such as non-adaptation and design changes.
                    </p>
                </div>
            </div>
        </li>
    </ul>





    <div class="secure-icons-wrap text-center single-product-icons show-for-small-only">
        <div class="secure-icons-wrap-block">
		<img src="/content/plugins/theme-customisations/custom/assets/img/ic_shoping_cart_mobile.png" />
	</div>
    </div>

<?php endif; ?>

