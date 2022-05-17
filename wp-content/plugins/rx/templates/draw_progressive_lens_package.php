<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
?>

<div class="lens-packages-wrapper">
    <div class="progressive-lens-package-banner">
        <div class="middle--row">
            <h2 class="title-banner-progressive">
                Your Life style demands fully personalized <br>
                <span>Free Form Progressive Lenses</span> with <br class="show-for-small-only"> Digital Ray Technology™
            </h2>
			
			
        </div>
    </div>

    <div class="grey-background">
        <div class="middle--row lens-package-wrapper" style="max-width: 1100px;">
            <div class="title-lens-package">
			
			
			  <div id="msgLensInCart" class="alert alert-danger" style="display:none">
				<strong>Warning!</strong> You have already a lense in your <a href="<?php echo wc_get_cart_url(); ?>" class="alert-link">cart</a>, please <a href="/" class="alert-link">combine</a> it with a frame or delete it.
			  </div>
			  
                <h3 class="title-text">The Perfect Progressive <br class="show-for-small-only"> Lenses For You</h3>
<!--                <p class="text-about">Find Your Match in 3 Easy Steps</p>-->
                <p class="lenses-for">I need lenses for:</p>

                <!-- this paragraf visible only with office distance block -->
                <p class="office-text-info" id="officeInfo" style="display: none">Please select your office size or the required distance at which
                    you wish the lenses to have the best performance. <br>
                    <span>IMPORTANT</span> Increased distance will narrow the reading area</p>
            </div>

            <div class="type-lenses-wrapper">
                <div class="lenses-box for-driving lens-package-item" id="forDriving" data-purpose="driving" data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_1.jpg">
                    <img src="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_1.jpg" alt="For driving">
                    <p class="title-lens" data-purpose="driving">For driving</p>
                    <p class="content-lens" data-purpose="driving">
                        Fully personalized progressive lens specially designed for driving.
                        It has a wide clear area of binocular vision in far distance combined
                        with a wide corridor and soft transitions to offer the best comfort while driving.
                    </p>
                </div>

                <div class="lenses-box general-daily-usage lens-package-item" id="generalDailyUsage" data-purpose="general" data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_2.jpg">
                    <img src="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_2.jpg" alt="Daily usage">
                    <p class="title-lens">General daily usage</p>
                    <p class="content-lens">
                        Fully personalized design with
                        a balance between distance and near vision.
                        Highly recommended for experienced and demanding
                        progressive wearers who are looking for an all-purpose,
                        comfortable progressive lens with wider visual fields at all distances.
                    </p>
                </div>

                <div class="lenses-box office-computer" id="officeComputer" data-purpose="office" data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg">
                    <img src="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg" alt="Office computer">
                    <p class="title-lens">Office/Computer</p>
                    <p class="content-lens">
                        Occupational lenses that offer wide intermediate and near visual
                        fields to provide the wearer clear vision at short distances.
                    </p>
                </div>

                <div class="lenses-box sport-activities lens-package-item" id="sportActivities" data-purpose="sports" data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_4.jpg">
                    <img src="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_4.jpg" alt="Sport activities">
                    <p class="title-lens">Sport/Activities</p>
                    <p class="content-lens">
                        Fully personalized progressive lens exclusively for outdoor activities.
                        This design offers a clear area of binocular vision in far distance and it
                        is the ideal lens for dynamic outdoor conditions.
                    </p>
                </div>
            </div>


            <!--  ======================================================================================= -->
            <!--  Step after choose lens Office/Computer (hidden block, show after push Office/Computer) -->
            <!--  ======================================================================================= -->

            <div class="office-package-wrapper" style="display: none">
                <img src="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/distance_picture.jpg" alt="Office distance" class="distance-img">

                <div class="distance-box green-line">
                    <p class="distance-info">Up to <span>4 foot</span> (1.3 m)<br> of clear vision</p>
                    <button class="btn-next-step lens-package-item" data-purpose="office" data-distance="4ft" data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg"> Enter Prescription →</button>
                </div>

                <div class="distance-box blue-line">
                    <p class="distance-info">Up to <span>6.5 foot</span> (2 m)<br>
                        of clear vision</p>
                    <button class="btn-next-step lens-package-item" data-purpose="office"  data-distance="6_5ft"  data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg"> Enter Prescription →</button>
                </div>

                <div class="distance-box red-line">
                    <p class="distance-info">Up to <span>13 foot</span> (4 m)<br> of clear vision</p>
                    <button class="btn-next-step lens-package-item" data-purpose="office"  data-distance="13ft"   data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg"> Enter Prescription →</button>
                </div>

                <div class="distance-box golden-line">
                    <p class="distance-info">Up to <span>19 foot</span> (6 m)<br> of clear vision</p>
                    <button class="btn-next-step lens-package-item" data-purpose="office"  data-distance="19ft"  data-usage="progressive" data-ltype="Rx" data-order-lens-design-img="<?=esc_url(WcRx::get_assets_url()); ?>image/progressive-package/purpose_image_3.jpg"> Enter Prescription →</button>
                </div>
            </div>
<?php
if (!defined('ABSPATH')) {
    exit;
}
$show_combine = 0;
$lens_packages_id = [49715];
foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	$cart_product = $cart_item['data'];
		
	if(in_array($cart_product->get_id(),$lens_packages_id) ){
		$show_combine = 1;
	}		
}
if($show_combine == 1){
    $js = /** @lang JavaScript */ <<<JS
jQuery(document).ready(function($) {
    $( ".lens-package-item,#officeComputer" ).click(function() {
      $('#msgLensInCart').fadeIn('slow');
      $('html, body').animate({scrollTop: $("#msgLensInCart").offset().top}, 2000);
    });
    $('.lens-packages-wrapper').on('click', '.lens-package-item', load_lens_package_options);

    $('#officeComputer').on('click', function(){
        $('.office-package-wrapper').show();
        $('.type-lenses-wrapper').hide();

        $('#officeInfo').show();
        $('.lenses-for').hide();
    });
});

JS;
    wp_add_inline_script( 'rx_jscript', $js, 'before');
}else{
    $js = /** @lang JavaScript */
        <<<'JS'

function load_lens_package_options(e) {
    e.preventDefault();
    // debugger;
    var $this = jQuery(e.target);
    if ($this.is('img') || $this.is('p') ){   //purpose selection, get data from parent div
        $this = $this.parent();
    }
    var ltype = $this.data('ltype');
    var usage = $this.data('usage');
    var purpose = $this.data('purpose');
    var distance = $this.data('distance');
    var url_img = $this.data('order-lens-design-img');
//                var ong_color = rx.prescription.get('ong', 'color_select', false);

    var product_id = jQuery("input[name='add-to-cart']").val();
    
    jQuery("#get_lenses").load(rx_params.url, {
        action: 'rx_form',
        type: 'GET',
        async: false,
        price: rx_cart_params.price,
        fprice: rx_cart_params.fprice,
        frame_regular_price: rx_cart_params.frame_regular_price,
        d_mode: ltype,
        d_source: 'landing_progressive', 
        d_usage: usage,
        d_purpose: purpose,
        d_distance: distance,
//                    description: jQuery('#p_desc').html(),
        product_id:product_id
//                    pimage: pimage
    }, function () {
        // debugger;
        jQuery('.lens-packages-wrapper').hide();
        /* move code into rx_jscript.js  */
        rx.sourcePage.set('landing_progressive');
        rx.usage.set('Progressive');
        rx.purpose.set(purpose);
        rx.distance.set(distance);
        rx.step.set(1); 
        jQuery('#package_frame').hide();
        if (url_img) {
            var img = jQuery("<img src='" + url_img + "' style='width: 100%;'>");
            jQuery("#order_lens_design")
            	.empty()
            	.append(img);
        }
    });
}
                
                jQuery(document).on('rx-loaded', function () {
                                        
                    var rx_form_container = jQuery('.rx-form-container');
                    rx_form_container.on('rx_navigation_set', function (event) {
                        // jQuery("[id^='nb_step']").hide();
                        // jQuery('#step2').show();
                        // jQuery('#nb_step3').show();
                    });                     
                });

                jQuery(document).ready(function($) {
                    
                    $('.lens-packages-wrapper').on('click', '.lens-package-item', load_lens_package_options);
                    
                    $('#officeComputer').on('click', function(){
                        $('.office-package-wrapper').show();
                        $('.type-lenses-wrapper').hide();

                        $('#officeInfo').show();
                        $('.lenses-for').hide();
                    });
                 });

JS;
    wp_add_inline_script( 'rx_jscript', $js, 'before');
}
?>



            <!--  ======================================================================================= -->
            <!--                       END Step after choose lens Office/Computer                        -->
            <!--  ======================================================================================= -->


            <!--  ======================================================================================= -->
            <!--      Step after choose lens General daily usage (show after step with prescription)     -->
            <!--  ======================================================================================= -->

            <!--        <div class="preferred-design-wrapper">-->
            <!--            <div class="premium-box type-one">-->
            <!--                <p class="title-premium">Premium</p>-->
            <!--                <p class="info-premium">Wide Corridor, Low Peripheral distortion and Clear Distance</p>-->
            <!--                <p class="price-premium">$99</p>-->
            <!--                <a href="" class="btn-next-step">Select your lens package</a>-->
            <!--            </div>-->
            <!---->
            <!--            <div class="premium-box type-second">-->
            <!--                <p class="title-premium">Premium Plus</p>-->
            <!--                <p class="info-premium">Premium Features + Extra wide Corridor</p>-->
            <!--                <p class="price-premium">$149</p>-->
            <!--                <a href="" class="btn-next-step">Select your lens package</a>-->
            <!--            </div>-->
            <!---->
            <!--            <div class="premium-box type-third">-->
            <!--                <p class="title-premium">Premium Plus</p>-->
            <!--                <p class="info-premium">For Customers already enjoying specific designs</p>-->
            <!--                <p class="">Please, choose your preference</p>-->
            <!--                <a href="" class="btn-next-step">Select your lens package</a>-->
            <!--            </div>-->
            <!--        </div>-->

            <!--  ======================================================================================= -->
            <!--                          END   General daily usage                                       -->
            <!--  ======================================================================================= -->

        </div>
    </div>
</div>
