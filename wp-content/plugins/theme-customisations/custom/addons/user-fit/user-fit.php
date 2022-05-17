<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

add_action( 'wp_enqueue_scripts', 'ong_user_fit_enqueue_scripts' );
function ong_user_fit_enqueue_scripts() {
    wp_enqueue_script('jquery-cookie');
    $js = /** @lang JavaScript */
        <<<'JS'
    
    var user_fit = jQuery.cookie("user_fit");

    user_fit = (typeof user_fit !== 'undefined') ? JSON.parse(user_fit) : {};
    user_fit.userSize = user_fit.userSize || {};
    user_fit.useMySize = user_fit.useMySize || false;
    
    function checkFrameFitsMe(){
        if (!userFitHasSize()) {
            var product_id = jQuery('input[name="product_id"]').val();
            var cookie_name = jQuery.cookie("product_"+product_id+"_fits_me");
            if( cookie_name !== 'fitsMe'){
                jQuery('#fitsMeMessage').show();
            }    
        } else {
            jQuery('#fitsMeMessage').hide();
        }
    }
        
    function userFitHasSize(){
        return (user_fit.userSize.lensWidth
           && user_fit.userSize.bridgeWidth
           && user_fit.userSize.templeLength);
    }
    
    jQuery(function($) {
        function fillInUserSize() {
            jQuery('.userSizeLens').val(user_fit.userSize.lensWidth);
            jQuery('.changeSizeLens').val(user_fit.userSize.lensWidth);
            jQuery('.user-frame-size-is .lensWidth').html(user_fit.userSize.lensWidth);
            jQuery('#user_pa_lens_width').val(user_fit.userSize.lensWidth);
            
            jQuery('.userSizeBridge').val(user_fit.userSize.bridgeWidth);
            jQuery('.changeSizeBridge').val(user_fit.userSize.bridgeWidth);
            jQuery('.user-frame-size-is .bridgeWidth').html(user_fit.userSize.bridgeWidth);
            jQuery('#user_pa_bridge').val(user_fit.userSize.bridgeWidth);
            
            jQuery('.userSizeTemple').val(user_fit.userSize.templeLength);
            jQuery('.changeSizeTemple').val(user_fit.userSize.templeLength);
            jQuery('.user-frame-size-is .templeLength').html(user_fit.userSize.templeLength);    
            jQuery('#user_pa_temple').val(user_fit.userSize.templeLength);
            
            var lensWidthInches = getIntegerAndFractionInches(user_fit.userSize.lensWidth, 16);
            var BridgeInches = getIntegerAndFractionInches(user_fit.userSize.bridgeWidth, 16);
            var TempleInches = getIntegerAndFractionInches(user_fit.userSize.templeLength, 16);
            
            jQuery('.userSizeLensInches').val(lensWidthInches[0]);
            jQuery('.userSizeLensOne16Inches').val(lensWidthInches[1][0]);
            jQuery('.userSizeBridgeInches').val(BridgeInches[0]);
            jQuery('.userSizeBridgeOne16Inches').val(BridgeInches[1][0]);
            jQuery('.userSizeTempleInches').val(TempleInches[0]);
            jQuery('.userSizeTempleOne16Inches').val(TempleInches[1][0]);
            
        }
        fillInUserSize();
        
        var change_my_size_modal = jQuery('#change-my-size-modal'); 
        change_my_size_modal.find('input[type=button]').click(function(){
            var lensWidth = jQuery('.changeSizeLens').val();
            var bridgeWidth = jQuery('.changeSizeBridge').val();
            var templeLength = jQuery('.changeSizeTemple').val();
            changeSizeCb (lensWidth, bridgeWidth, templeLength);
            change_my_size_modal.foundation('close');
        });
        
        var use_my_size_user_sizes = jQuery('.filter-size-wrapper .text-input-container'); 
        use_my_size_user_sizes.find('input[type=button]').click(function(){
            var lensWidth = jQuery('#user_pa_lens_width').val();
            var bridgeWidth = jQuery('#user_pa_bridge').val();
            var templeLength = jQuery('#user_pa_temple').val();
            changeSizeCb (lensWidth, bridgeWidth, templeLength);
            use_my_size_user_sizes.foundation('close');
        });
        
        jQuery('.btn-edit-my-size').on('click', function(e) {
            e.preventDefault();
            change_my_size_modal.foundation('open');
        });
        
        var modalSize = jQuery('#modalSize');
        modalSize.find('.panel-inches input[type=number]').change(function(){
            var parent = jQuery(this).closest('.panel-inches');
            var lensWidth = (
                parseInt(parent.find('.userSizeLensInches').val() || 0) +
                parseInt(parent.find('.userSizeLensOne16Inches').val() || 0) / 16
            ) * MM_TO_INCH;
            
            var bridgeWidth = (
                parseInt(parent.find('.userSizeBridgeInches').val() || 0) +
                parseInt(parent.find('.userSizeBridgeOne16Inches').val() || 0) / 16
            ) * MM_TO_INCH;
          
            var templeLength = (
                parseInt(parent.find('.userSizeTempleInches').val() || 0) +
                parseInt(parent.find('.userSizeTempleOne16Inches').val() || 0) / 16
            ) * MM_TO_INCH;
            
            if (!isNaN(lensWidth)) {
                var lensWidthInches = getIntegerAndFractionInches(lensWidth, 16);
                jQuery('.userSizeLensInches').val(lensWidthInches[0]);
                jQuery('.userSizeLensOne16Inches').val(lensWidthInches[1][0]);
                jQuery('.userSizeLens').val(Math.round(lensWidth));
            }
            if (!isNaN(bridgeWidth)) {
                var BridgeInches = getIntegerAndFractionInches(bridgeWidth, 16);
                jQuery('.userSizeBridgeInches').val(BridgeInches[0]);
                jQuery('.userSizeBridgeOne16Inches').val(BridgeInches[1][0]);
                jQuery('.userSizeBridge').val(Math.round(bridgeWidth));
            }
            if (!isNaN(templeLength)) {
                var TempleInches = getIntegerAndFractionInches(templeLength, 16);
                jQuery('.userSizeTempleInches').val(TempleInches[0]);
                jQuery('.userSizeTempleOne16Inches').val(TempleInches[1][0]);    
                jQuery('.userSizeTemple').val(Math.round(templeLength));
            }
        });
        
        modalSize.find('.panel-ci input[type=number]').change(function(){
            var lensWidth = jQuery('.userSizeLens').val();
            var bridgeWidth = jQuery('.userSizeBridge').val();
            var templeLength = jQuery('.userSizeTemple').val();
            
            var lensWidthInches = getIntegerAndFractionInches(lensWidth, 16);
            var BridgeInches = getIntegerAndFractionInches(bridgeWidth, 16);
            var TempleInches = getIntegerAndFractionInches(templeLength, 16);
            
            jQuery('.userSizeLensInches').val(lensWidthInches[0]);
            jQuery('.userSizeLensOne16Inches').val(lensWidthInches[1][0]);
            jQuery('.userSizeBridgeInches').val(BridgeInches[0]);
            jQuery('.userSizeBridgeOne16Inches').val(BridgeInches[1][0]);
            jQuery('.userSizeTempleInches').val(TempleInches[0]);
            jQuery('.userSizeTempleOne16Inches').val(TempleInches[1][0]);
        });
        
        modalSize.find('input[type=button]').click(function(){
            var lensWidth = jQuery('.userSizeLens').val();
            var bridgeWidth = jQuery('.userSizeBridge').val();
            var templeLength = jQuery('.userSizeTemple').val();
            changeSizeCb (lensWidth, bridgeWidth, templeLength);
            modalSize.foundation('close');
        });
        
        function changeSizeCb (lensWidth, bridgeWidth, templeLength) {
            var hadOldUserSize = userFitHasSize();
            if (!hadOldUserSize) {
                user_fit.useMySize = true;
            }
            user_fit.userSize.lensWidth = parseInt(lensWidth);
            user_fit.userSize.bridgeWidth = parseInt(bridgeWidth);
            user_fit.userSize.templeLength = parseInt(templeLength);
            jQuery.cookie("user_fit", JSON.stringify(user_fit), { path: '/', expires: 365 });
            fillInUserSize();
            doesItFitsMe();
        }
        
        var this_frame_size = jQuery('.this-frame-size');
        function howItFitsMe() {
            var result = 'may-not-fit';
            if (userFitHasSize()) {
                //lensWidth
                var lensWidthDiff = Math.abs(parseInt(this_frame_size.find('.lensWidth').html()) - user_fit.userSize.lensWidth);
                var templeLengthDiff = Math.abs(parseInt(this_frame_size.find('.templeLength').html()) - user_fit.userSize.templeLength);
                
                if (lensWidthDiff <= 2 && templeLengthDiff <= 5) {
                    return 'fits-well'; 
                }
            }
            return result;
        }
        
        function doesItFitsMe() {
            jQuery(".message-about-fit").hide();
            if (userFitHasSize()) {
                jQuery(".message-about-fit." + howItFitsMe()).show();
                jQuery(".perfect-fit").addClass('has-size');
                jQuery(".user-frame-size-is").show();
                jQuery('#fitsMeMessage').hide();
                jQuery('#attentionMessage').hide();
            } else {
                jQuery(".perfect-fit").removeClass('has-size');
                jQuery(".user-frame-size-is").hide();
                jQuery('#attentionMessage').show();
            }
            checkFrameFitsMe();
        }
        doesItFitsMe();
        
        var attentionMessageOk = jQuery.cookie('fitFeatureAttentionMessage');
        if(!userFitHasSize() && attentionMessageOk !== 'none'){
            jQuery('#attentionMessage').show();
        }
        
        // There's no need to close a previous modal before you
        // open another one.
        jQuery('.open-first').on('click', function(e) {
            e.preventDefault();
            modalSize.foundation('open');
        });
        
        jQuery('#modalSize a.close').on('click', function() {
            modalSize.foundation('close');
        });

        jQuery('#change-my-size-modal a.close').on('click', function() {
            change_my_size_modal.foundation('close');
        });
        
        jQuery('#attention-modal a.close').on('click', function() {
            jQuery('#attention-modal').foundation('close');
        });

        jQuery('a.sizefilter').on('click', function(e) {
            e.preventDefault();
            modalSize.foundation('open');
        });

        //script for cookie for this frame fits me       
        jQuery('#thisFrameFitsMe').on('click', function(){
            jQuery('#fitsMeMessage').slideUp(300);
            jQuery.cookie("product_"+product_id+"_fits_me", 'fitsMe', {
                expires: 90
            });
        });
    
        jQuery('#skipHref').on('click', function(){
            jQuery('#attentionMessage').slideUp(300);
            jQuery.cookie('fitFeatureAttentionMessage', 'none', {
                expires: 1
            });
        });
        
        // change input value, validation
        var user_inputs = jQuery("input.user-size-input");
        user_inputs.change(function(event){            
            var code = jQuery(this).data('code');
            var slider = jQuery(this).closest('.filter-size-wrapper').find('.slider-box[data-filter-key="'+code+'"] .size-slider');

            var value0 = slider.slider("option", 'min');
            var value1 = slider.slider("option", 'max');
            var value = parseInt(jQuery(this).val());
            var slider_value0, slider_value1;
            
            if(  value >= value0 && value <= value1 ){
                slider_value0 = Math.max(value - 2, value0);
                slider_value1 = Math.min(value + 2, value1);
                if (user_fit.useMySize){
                    slider.slider("values",[slider_value0,slider_value1]);
                } else { 
                    slider.slider("values",[value0,value1]);            
                }
                jQuery(this).removeClass('error');
            } else {
                jQuery(this).addClass('error');
            }
        });
    
        var useMySizeCheckbox = jQuery('#useMySize');
        useMySizeCheckbox.prop( "checked", user_fit.useMySize ).change(function(event, ui){
            user_fit.useMySize = jQuery(this).is(':checked');
            jQuery.cookie("user_fit", JSON.stringify(user_fit), { path: '/', expires: 365 });
            user_inputs.change();
        });
        
        $( document.body ).on('ong_filter_slider_created', function(event) {
            setTimeout(function(){
                useMySizeCheckbox.change();    
            },0);
        });
            
        jQuery(document).on('rx-loaded', function () {
            jQuery('#fitsMeMessage').hide();
            var rx_form_container = jQuery('.rx-form-container');
            rx_form_container.on('rx-hidden', function () {
                checkFrameFitsMe();
            });
        });

        jQuery('body.single-product .summary.entry-summary .variations_form').on('found_variation', function (event, variation) {
            if ( variation.attributes && variation.attributes.attribute_pa_size ) {
                var segments = variation.attributes.attribute_pa_size.split('-');
                if (segments.length===3) {
                    this_frame_size.find('.lensWidth').html(segments[0]);
                    this_frame_size.find('.bridgeWidth').html(segments[1]);
                    this_frame_size.find('.templeLength').html(segments[2]);
                }    
            }
        });
    
    });
JS;
    wp_add_inline_script( 'jquery-cookie', $js );
}
//add_action( 'init', 'user_fit_init'  );
function user_fit_init () {
    add_filter('ong_initial_filter', function($filter){
        if( isset( $_COOKIE['user_fit'] ) ) {
            $user_fit = json_decode( stripslashes( $_COOKIE['user_fit'] ), true );
            if (!empty($user_fit['useMySize']) && empty($filter['filter']['size']) && isset(
                    $user_fit['userSize']['lensWidth'],
                    $user_fit['userSize']['bridgeWidth'],
                    $user_fit['userSize']['templeLength']
                )) {
                    $filter['filter']['size']['pa_lens-width'][0] = $user_fit['userSize']['lensWidth'] - 2;
                    $filter['filter']['size']['pa_lens-width'][1] = $user_fit['userSize']['lensWidth'] + 2;
                    $filter['filter']['size']['pa_bridge'][0] = $user_fit['userSize']['bridgeWidth'] - 2;
                    $filter['filter']['size']['pa_bridge'][1] = $user_fit['userSize']['bridgeWidth'] + 2;
                    $filter['filter']['size']['pa_temple'][0] = $user_fit['userSize']['templeLength'] - 2;
                    $filter['filter']['size']['pa_temple'][1] = $user_fit['userSize']['templeLength'] + 2;
            }
        }
        return $filter;
    });
}

add_action('template_redirect', function() {

    if ( is_admin() ) {
        return false;
    }

    if (apply_filters('user-fit-feature-could-be-displayed', true)) {
        $user_fit_size = null;

        add_action( 'size-form-after-main-controls', function ( $atts, $group_items, $shortcode ) use ( $user_fit_size ) {
            include ong_locate_template( "template-parts/addons/user-fit-usemysize.php" );
        }, 10, 3 );

        add_action( 'woocommerce_single_product_summary', 'ong_size_template_single_meta', 35 );
        function ong_size_template_single_meta() {
            include ong_locate_template( "template-parts/addons/user-fit-meta.php" );
        }

        add_action( 'woocommerce_single_product_summary', 'ong_change_size_template_single_meta', 35 );
        function ong_change_size_template_single_meta() {
            include ong_locate_template( "template-parts/addons/user-fit-change-my-size.php" );
        }
    }
}, 50);
