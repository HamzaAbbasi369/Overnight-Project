// (function(){!function(a,b){"use strict";b.fn.stickToTop=function(c){var d,e,f;return c=b.extend({scrollParent:window,offset:{top:0,left:0},minWindowHeight:!1,minWindowWidth:!1,preserveLayout:!0,bottomBound:!1,onStick:null,onDetach:null},c,!0),f=c.scrollParent,d=0,e=b(f===window?f.document.body:f).offset(),b(this).each(function(){var g,h,i,j,k,l,m,n,o,p;n=this,h=b(n),o=!1,m=!1,p=!1,g=c.preserveLayout?h.wrap('<div class="stickToTopLayout"></div>').parent():void 0,j=function(){var b;return b={width:0,height:0},"number"==typeof window.innerWidth?(b.width=window.innerWidth,b.height=window.innerHeight):a.documentElement&&(a.documentElement.clientWidth||a.documentElement.clientHeight)&&(b.width=a.documentElement.clientWidth,b.height=a.documentElement.clientHeight),b},i=function(){var a;return a=!1,(c.minWindowWidth&&j().width>=c.minWindowWidth||!c.minWindowWidth)&&(a={offset:h.offset(),position:h.css("position"),width:h.outerWidth(!0),height:h.outerHeight(!0),marginTop:parseInt(h.css("margin-top"),10),marginLeft:parseInt(h.css("margin-left"),10)}),a},l=function(){var k,l,m,p,q,r,s,t,u,v;return o||(o=i()),u=f.scrollTop||b(a).scrollTop(),v=j(),r=f===window?v.height:f.offsetHeight,s=f===window?v.width:f.offsetWidth,p=c.bottomBound&&r-c.bottomBound-o.height,k=!!p&&u>p,l=u>=o.offset.top-c.offset.top-o.marginTop+e.top,m=!l,c.minWindowWidth&&s<c.minWindowWidth&&(m=!0,l=!1),l=l&&!k,k&&1!==d?(q=h.offset(),h.css({position:"absolute",top:p+"px",left:q.left+"px"}),d=1,void(c.onDetach&&c.onDetach.call(n))):m&&2!==d||c.minWindowHeight&&r<c.minWindowHeight?(t={position:o.position},"static"===o.position||"relative"===o.position?(h.removeAttr("style"),g&&g.removeAttr("style")):b.extend(t,{top:o.offset.top,left:o.offset.left}),d=2,void(c.onDetach&&c.onDetach.call(n))):void(l&&3!==d&&v.height>o.height+c.offset.top&&(h.css({position:"fixed",top:e.top+(c.offset.top||0),left:e.left+o.left+(c.offset.left-o.marginLeft||0),width:o.width,"z-index":1e3}),c.preserveLayout&&g.css({position:o.position,width:o.width,height:o.height,"margin-top":o.marginTop,"margin-left":o.marginLeft}),d=3,c.onStick&&c.onStick.call(n)))},k=function(){m||(m=!0,window.setTimeout(function(){c.minWindowWidth&&j().width<c.minWindowWidth||p||(h.removeAttr("style"),c.preserveLayout&&g.removeAttr("style"),o=i(),d="",l(),m=!1)},50))},b(window).on("resize",k),b(c.scrollParent).on("scroll",l),this.unstickToTop=function(){p=!0,b(c.scrollParent).off("scroll",l),b(window).off("resize",k)}})}}(window.document,window.jQuery)}).call(this);


//** ong_info изменяем выбранный цвет после перехода с категории*/
// получаем параметры
jQuery.extend({
    getUrlVars: function () {
        var vars = [], hash;
        // var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        var hashes = window.location.hash.slice(window.location.hash.indexOf('#') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function (name) {
        return jQuery.getUrlVars()[name];
    }
});

/**
 *
 * @returns {*}
 */
function  getColorByHash () {
    return jQuery.getUrlVar('color');
}

function removeHash () {
    var scrollV, scrollH, loc = window.location;
    if ("replaceState" in history){
        history.replaceState("", document.title, document.location.href.replace(location.hash , "" ));
    }else {
        // Prevent scrolling by storing the page's current scroll offset
        scrollV = document.body.scrollTop;
        scrollH = document.body.scrollLeft;

        loc.href = document.location.href.replace(location.hash , "" );

        // Restore the scroll offset, should be flicker free
        document.body.scrollTop = scrollV;
        document.body.scrollLeft = scrollH;
    }
}

/**
 *
 * @param color
 */
function highlight_color (color) {
    var callback = function(){
        var $color = jQuery("span.swatch.swatch-image.swatch-" + color+':not(.selected)');
            $color.trigger('click');
    };

    //имитация клика
    setTimeout(callback,0);
}

function getColorByFilter() {
    var color = false;
    /* global ong_all_filters */
    if(typeof ong_all_filters !== 'undefined' &&
        typeof ong_all_filters['pa_attribute'] !== 'undefined' &&
        typeof ong_all_filters['pa_attribute']['pa_color'] !== 'undefined' &&
        ong_all_filters['pa_attribute']['pa_color'].length > 1
    ) {
         color = ong_all_filters['pa_attribute']['pa_color'][0];
    }
    return color;
}

jQuery(document).ready(function($){




    // (function ($) {
    //     $.fn.sticky = function (options) {
    //         var scrollY = 0, elements = [], disabled = false, $window = $(window);
    //
    //         options = options || {};
    //
    //         var recalculateLimits = function () {
    //             for (var i=0, len=elements.length; i<len; i++) {
    //                 var $this = elements[i];
    //
    //                 if (options.minWidth && $window.width() <= options.minWidth) {
    //                     if ($this.parent().is(".button-wrapper")) { $this.unwrap(); }
    //                     $this.css({width: "", left: "", top: "", bottom: "", position: ""});
    //                     if (options.activeClass) { $this.removeClass(options.activeClass); }
    //                     disabled = true;
    //                     continue;
    //                 } else {
    //                     disabled = false;
    //                 }
    //
    //                 var $container = options.containerSelector ? $this.closest(options.containerSelector) : $(document.body);
    //                 var offset = $this.offset();
    //                 var containerOffset = $container.offset();
    //                 var parentOffset = $this.offsetParent().offset();
    //
    //                 if (!$this.parent().is(".button-wrapper")) {
    //                     $this.wrap("<div class='button-wrapper'>");
    //                 }
    //
    //                 var pad = $.extend({
    //                     top: 0,
    //                     bottom: 0
    //                 }, options.padding || {});
    //
    //                 $this.data("sticky", {
    //                     pad: pad,
    //                     from: (options.containerSelector ? containerOffset.top : offset.top) - pad.top,
    //                     to: containerOffset.top + $container.height() - $this.outerHeight() - pad.bottom,
    //                     end: containerOffset.top + $container.height(),
    //                     parentTop: parentOffset.top
    //                 });
    //
    //                 $this.css({width: $this.outerWidth()});
    //                 $this.parent().css("height", "auto");
    //             }
    //         };
    //
    //         var onScroll = function () {
    //             if (disabled) { return; }
    //
    //             scrollY = $window.scrollTop();
    //
    //             var elmts = [];
    //             for (var i=0, len=elements.length; i<len; i++) {
    //                 var $this = $(elements[i]),
    //                     data  = $this.data("sticky");
    //
    //                 if (!data) { // Removed element
    //                     continue;
    //                 }
    //
    //                 elmts.push($this);
    //
    //                 var from = data.from - data.pad.bottom,
    //                     to = data.to - data.pad.top;
    //
    //                 if (from + $this.outerHeight() > data.end) {
    //                     $this.css('position', '');
    //                     continue;
    //                 }
    //
    //                 if (from < scrollY && to > scrollY) {
    //                     !($this.css("position") == "fixed") && $this.animate({bottom: 9 + "%"}, 1000).css({
    //                         left: $this.offset().left,
    //                         top: "auto"
    //                     }).css("position", "fixed");
    //                     if (options.activeClass) { $this.addClass(options.activeClass); }
    //                 } else if (scrollY >= to) {
    //                     $this.animate({top: to - data.parentTop + data.pad.top}, 1500).css({
    //                         left: "",
    //                         bottom: to - data.parentTop + data.pad.top
    //                     }).css("position", "absolute");
    //                     if (options.activeClass) { $this.addClass(options.activeClass); }
    //                 } else {
    //                     $this.css({position: "", top: "", bottom: "", left: ""});
    //                     if (options.activeClass) { $this.removeClass(options.activeClass); }
    //                 }
    //             }
    //             elements = elmts;
    //         };
    //
    //         var update = function () { recalculateLimits(); onScroll(); };
    //
    //         this.each(function () {
    //             var $this = $(this),
    //                 data  = $(this).data('sticky') || {};
    //
    //             if (data && data.update) { return; }
    //             elements.push($this);
    //             $("img", this).one("load", recalculateLimits);
    //             data.update = update;
    //             $(this).data('sticky', data);
    //         });
    //
    //         $window.scroll(onScroll);
    //         $window.resize(function () { recalculateLimits(); });
    //         recalculateLimits();
    //
    //         $window.load(update);
    //
    //         return this;
    //     };
    // })(jQuery);
    //
    // function windowWidth(){
    //     var windowWidthMax = jQuery(window).width();
    //     if(windowWidthMax >= 1450){
    //         jQuery(".sticky_coupons_block_modal_button_first").sticky({
    //             containerSelector: ".quick-look-section"
    //         });
    //
    //     }else{
    //         jQuery('.sticky_coupons_block_modal_button_first,.sticky_coupons_block_modal_button_first *').unbind().removeData();
    //     }
    //     jQuery(window).resize(function(){
    //         var $this = jQuery(this);
    //         if($this.width() >= 1450){
    //             jQuery(".sticky_coupons_block_modal_button_first").sticky({
    //                 containerSelector: ".quick-look-section"
    //             });
    //
    //         }else{
    //             jQuery('.sticky_coupons_block_modal_button_first,.sticky_coupons_block_modal_button_first *').unbind().removeData();
    //         }
    //     });
    // }

    jQuery( 'ul.products' ).on('change', '.variations_form .value select', function () {

        var $self = $(this),
            colorValue = $self.val(),
            $selfParent = $self.closest('.look-item'),
            $selectLink = $selfParent.find('.look--item-wrap');

        if ($selectLink.attr('href').indexOf('#') !== -1){
            $selectLink.attr('href',$selectLink.attr('href').substring(0, $selectLink.attr('href').indexOf('#')));
        }

        if (colorValue && colorValue != $selfParent.find('span.swatch-image:first-child').data('value')) {
            $selectLink.attr('href', $selectLink.attr('href') + '#color=' + colorValue);
        }
    });


    /**
     * Change the selected items on the page depending on the parameters
     * wordpress.local/product-category/eyeglasses/men/#color=gunmetal
     */
    var colorInHash = getColorByHash();
    if (colorInHash) {
        removeHash();
        highlight_color(colorInHash);
    }

    /**
     * отображениия цвета товара после выбора в фильтре
     */

    function reInitYotpo(){
        if (typeof Yotpo !== 'undefined' ) {
            Yotpo.ready(function(){
                yotpo.initialized = false;
                yotpo.init();
            });
        }
    }

    function productList() {
        var $productsList = jQuery(".products > li"),
            $sticky_coupons_block = jQuery(".sticky_coupons_block");
        if($productsList.lenght != 0){
            $sticky_coupons_block.removeClass("hide");
        }else if($productsList.lenght === 0){
            $sticky_coupons_block.addClass("hide");
        }
    }

    $(document.body).on('ong_filter_content_reloaded', function(){
        var colorInFilter = getColorByFilter();

        if(colorInFilter) {
            highlight_color(colorInFilter.replace(/ /g, '').toLowerCase());
        }
        reInitYotpo();
        // productList();

        // windowWidth();

    });

});

jQuery(document).ready(function(){


    jQuery('body.single-product .summary.entry-summary .variations_form').on('found_variation', function (event, variation) {
        jQuery('.size_lens_variations').text('');
		 if ( variation.sku ) {
            jQuery(".size_lens_variations").append('<tr><th>Variation Sku</th><td>'+variation.sku+'</td></tr>');
        }
        if ( variation.size_lens_height ) {
            jQuery(".size_lens_variations").append('<tr><th>Lens Height</th><td>'+variation.size_lens_height+'</td></tr>');
        }
        if ( variation.size_lens_width ) {
            jQuery(".size_lens_variations").append('<tr><th>Lens Width</th><td>'+variation.size_lens_width+'</td></tr>');
        }
        if ( variation.size_frame_width ) {
            jQuery(".size_lens_variations").append('<tr><th>Frame Width</th><td>'+variation.size_frame_width+'</td></tr>');
        }
        if ( variation.size_bridge ) {
            jQuery(".size_lens_variations").append('<tr><th>Bridge</th><td>'+variation.size_bridge+'</td></tr>');
        }
    });

    /**
     * Depending on 'Out of Stock' - show or hide buttons - 'Prescription lenses' and 'Fashion lenses'
     */
    jQuery('body.single-product .summary.entry-summary .variations_form').on('show_variation', function (event, variation, purchasable) {
        showHidePrescriptionButtons(purchasable);
    });

    function showHidePrescriptionButtons(purchasable) {
        if(!purchasable){
            hidePrescriptionButtons();
        } else {
            showPrescriptionButtons();
        }
    }

    function showPrescriptionButtons() {
        jQuery(".product--prescription").show();
        jQuery(".product--feshion-lenses").show();
    }

    function hidePrescriptionButtons() {
        jQuery(".product--prescription").hide();
        jQuery(".product--feshion-lenses").hide();
    }

    // jQuery('.woocommerce').on("click", '.shopping-checkout.checkout-button.button.continue--shop-button', function () {
    //     jQuery(".amazon-payment-button-loaded").click();
    // });

    if(jQuery(".coupon--title-wrap").is(":visible")){
        jQuery(".shop_table.shop_table_responsive").addClass("hide");
        jQuery(".woocommerce-remove-coupon").text("");
    }else{
        jQuery("#coupon_code").removeClass("hide");
    }

    jQuery(".woocommerce-remove-coupon").on("click", function(){
        jQuery(".shop_table.shop_table_responsive").removeClass("hide");
    });

    jQuery(".woocommerce-remove-coupon").on("click", function(){
        jQuery(".shop_table.shop_table_responsive").removeClass("hide");
    });

    // jQuery("input[type='submit']#place_order").on("click", function(e){
    //     e.preventDefault();
    //     return false;
    // });


    //form validation
// changed billing to shipping
    var $shipping_first_name = jQuery("#shipping_first_name"),
        $shipping_last_name = jQuery("#shipping_last_name"),
        $shipping_email = jQuery("#shipping_email"),
        $shipping_phone = jQuery("#shipping_phone"),
        $s2id_shipping_country = jQuery("#s2id_billing_country"),
        $shipping_country = jQuery("#shipping_country"),
        $shipping_company = jQuery("#shipping_company"),
        $shipping_address_1 = jQuery("#shipping_address_1"),
        $shipping_address_2 = jQuery("#shipping_address_2"),
        $shipping_city = jQuery("#shipping_city"),
        $shipping_state = jQuery("#shipping_state"),
        $shipping_postcode = jQuery("#shipping_postcode"),
        $checkoutForm = jQuery("#checkout-form"),
        $checkoutInput = $checkoutForm.find("input[id^='billing']");
       
    var $billing_first_name = jQuery("#billing_first_name"),
        $billing_last_name = jQuery("#billing_last_name"),
        $billing_email = jQuery("#billing_email"),
	$billing_phone = jQuery("#billing_phone"),
	$billing_company = jQuery("#billing_company"),
        $billing_address_1 = jQuery("#billing_address_1"),
	$billing_address_2 = jQuery("#billing_address_2"),
        $billing_city = jQuery("#billing_city"),
        $billing_state = jQuery("#billing_state"),
        $billing_postcode = jQuery("#billing_postcode");
 
	// changed from billing to shipping
	$shipping_first_name.attr({"placeholder":"First Name*"});
        $shipping_last_name.attr({"placeholder":"Last Name*"});
        $shipping_email.attr({"placeholder":"E-mail Address*"});
        $shipping_phone.attr({"placeholder":"Phone Number*"});
        $shipping_company.attr({"placeholder":"Company Name"});
        $shipping_address_1.attr({"placeholder":"Address*"});
        $shipping_address_2.attr({"placeholder":"Apt/Suite*"});
        $shipping_city.attr({"placeholder":"City*"});
        $shipping_state.attr({"placeholder":"State*"});
        $shipping_postcode.attr({"placeholder":"Zip Code*"});

        $billing_first_name.attr({"placeholder":"First Name*"});
        $billing_last_name.attr({"placeholder":"Last Name*"});
        $billing_email.attr({"placeholder":"E-mail Address*"});
        $billing_phone.attr({"placeholder":"Phone Number*"});
        $billing_company.attr({"placeholder":"Company Name"});
        $billing_address_1.attr({"placeholder":"Address*"});
        $billing_address_2.attr({"placeholder":"Apt/Suite*"});
        $billing_city.attr({"placeholder":"City*"});
        $billing_state.attr({"placeholder":"State*"});
        $billing_postcode.attr({"placeholder":"Zip Code*"});

    // by default check the checkbox

    console.log('check check');

       // check if we are in a mobile
var getBrowserWidth = function(){
    if(window.innerWidth < 768){
        // Extra Small Device
        return "xs";
    } else if(window.innerWidth < 991){
        // Small Device
        return "sm"
    } else if(window.innerWidth < 1199){
        // Medium Device
        return "md"
    } else {
        // Large Device
        return "lg"
    }
};
    var device2 = getBrowserWidth();
    if (device2 == "xs" || device2 == "sm" || device2 == "md") {
	console.log("On mobile show billing info");	
    } else {
	console.log("not on mobile");
    	jQuery('#ship-to-different-address-checkbox').prop('checked', true);
    	jQuery(".woocommerce-billing-fields").hide();
    	//document.styleSheets[0].addRule('#ship-to-different-address label:before','background-color: #92844D;');
    	jQuery("#ship-to-different-address").addClass("chk");
    }


    jQuery("#shipping_postcode_field").after(jQuery("#billing_phone_field"));
    jQuery("#billing_phone_field").after(jQuery("#billing_email_field"));
    $terms = jQuery("#terms");
    $terms.prop('checked', true);
	
	var coupon = '';
	if ( $('#not_in_rush_checkbox').length > 0 ) {	
		coupon = atob($('#not_in_rush_checkbox').data('coupon-json'));
	}
	
	var coupons = $('.cart-discount')
	var couponCheck = couponClass = coupon.replace(/%/g,"");
	var couponClass = 'coupon-' + couponCheck.replace(/\s+/g, '-').toLowerCase();
	
	var couponExist = false;
	if ( coupons.length > 0 ) {
		$.each(coupons, function (i, element) {
			console.log($(element).hasClass(couponClass))
			if( $(element).hasClass(couponClass)) {
				couponExist = true;
				
			}
		})	
	}
	
	console.log(couponExist);
	
	if ( couponExist ) {
		sessionStorage.setItem("discount_offer", coupon)
		$('#not_in_rush_checkbox').attr('checked', 'checked');
		$('#not_in_rush').addClass('chk');
	} else {
		sessionStorage.setItem("discount_offer", "")
		$('#not_in_rush_checkbox').removeAttr('checked');
		$('#not_in_rush').removeClass('chk');
	}
	
	$(document).on('click', '#not_in_rush_checkbox', function(e){
		e.preventDefault()
		console.log(coupon);
		if ( jQuery("#not_in_rush_checkbox").is(":checked") ) {
			
			 jQuery("#not_in_rush").addClass("chk")
			$('#coupon_code').val(coupon)
			$('input[name=apply_coupon]').trigger('click')
			
			if ( $('.woocommerce-error').length == 0 ) {
			 sessionStorage.setItem("discount_offer", coupon)
			}
		} else {
			 sessionStorage.setItem("discount_offer", "")
			 jQuery("#not_in_rush").removeClass("chk")
			 
			if ( $('.cart-discount a.fa-trash').data('coupon') ) {
			  window.location.replace($('.cart-discount a.fa-trash').attr('href'));
			}
		}
});
	
	$('input[name=apply_coupon]').bind('click', function(e){

		if ( $('#coupon_code').val() == coupon) {
			console.log( $('.woocommerce-error li').text().indexOf("Sorry") )
			if ( $('.woocommerce-error').length == 0 ) {
			 sessionStorage.setItem("discount_offer", coupon)
			}
		}
	})
			
		
		$( '.cart-discount a.fa-trash' ).on('click', function(event) {
			event.preventDefault();
			var $this = $(this)
			
			if(  $this.closest('#shopping-coupon').hasClass(couponClass)) {
				sessionStorage.setItem("discount_offer", "")
				window.location.replace($this.attr('href'));
			} else {
				window.location.replace($this.attr('href'));
			}
			
// 			if ( $(this).data('coupon', coupon) ) {
// 			  sessionStorage.setItem("discount_offer", "")
// 				 window.location.replace($this.attr('href'));
// 			}
			
		}) 

    jQuery("#ship-to-different-address-checkbox").on('click', function() {
        if (jQuery("#ship-to-different-address-checkbox").is(":checked")) {
                console.log("checked");
                jQuery("#ship-to-different-address").removeClass("chkno");
                jQuery("#ship-to-different-address").addClass("chk");
                jQuery( '.woocommerce-billing-fields' ).hide();
                //document.styleSheets[0].addRule('#ship-to-different-address label:before','background-color: #92844D;');
                jQuery("#billing_first_name").val($("#shipping_first_name").val());
                jQuery("#billing_last_name").val($("#shipping_last_name").val());
                jQuery("#billing_email").val($("#billing_email").val());
                jQuery( '#billing_country' ).val($("#shipping_country").val());
                jQuery( '#billing_state' ).val($( '#shipping_state' ).val());
                jQuery( 'input#billing_postcode' ).val($( 'input#shipping_postcode' ).val());
                jQuery( '#billing_city' ).val($( '#shipping_city' ).val());
                jQuery( 'input#billing_address_1' ).val($( 'input#shipping_address_1' ).val());
                jQuery( 'input#billing_address_2' ).val($( 'input#shipping_address_2' ).val());
        } else {
                console.log('not checked');
                jQuery( '.woocommerce-billing-fields' ).slideDown();
                jQuery("#ship-to-different-address").removeClass("chk");
			  		 jQuery("#ship-to-different-address").addClass("chkno");
                jQuery(".shipping_address").show();
                //document.styleSheets[0].addRule('#ship-to-different-address label:before','background-color: #fff;');
        }
    });


    jQuery("#continue_to_payment").on('click', function(){
	console.log("clicked payment");
        $shipping_first_name.attr({'required': "required"});
        $shipping_last_name.attr({'required': "required"});
        $shipping_email.attr({'required': "required"});
        $shipping_phone.attr({'required': "required"});
        // $billing_company.attr({'required': "required"});
        $shipping_address_1.attr({'required': "required"});
        // $billing_address_2.attr({'required': "required"});
        $shipping_city.attr({'required': "required"});
        $shipping_state.attr({'required': "required"});
        $shipping_postcode.attr({'required': "required"});
	$billing_email.attr({'required': "required"});
	$billing_phone.attr({'required': "required"});


	if(jQuery("#ship-to-different-address-checkbox").is(":checked") || jQuery("#ship-to-different-address-checkbox").attr('checked', true)) {
		console.log('nchk');
                $("#billing_first_name").val($("#shipping_first_name").val());
                $("#billing_last_name").val($("#shipping_last_name").val());
                $( '#billing_country' ).val($("#shipping_country").val());
                $( '#billing_state' ).val($( '#shipping_state' ).val());
		//$('#billing_email').val($('#shipping_email').val());
                $( 'input#billing_postcode' ).val($( 'input#shipping_postcode' ).val());
                $( '#billing_city' ).val($( '#shipping_city' ).val());
                $( 'input#billing_address_1' ).val($( 'input#shipping_address_1' ).val());
                $( 'input#billing_address_2' ).val($( 'input#shipping_address_2' ).val());

	} else {
		console.log('chk');
		$billing_first_name.attr({'required': "required"});
		$billing_last_name.attr({'required': "required"});
		$billing_address_1.attr({'required': "required"});
		$billing_city.attr({'required': "required"});
		$billing_state.attr({'required': "required"});
		$billing_postcode.attr({'required': "required"});	
		//$('#billing_email').val($('#shipping_email').val());

	}


        if (!jQuery("form[name='checkout']")[0].checkValidity()) {
	    console.log('form not valid');
            // return false;
        }else {
            console.log('successfull');
            $("#payment--info-block .checkout--details-title").html("PAYMENT INFO");
            jQuery(".billing_shipping_wrap").addClass("is-in-active");
            // jQuery(document).ajaxComplete(function () {
            jQuery(document).ajaxSuccess(function () {
                jQuery("#payment").addClass("is-active");
            });
	    console.log('add other info');
            jQuery("#payment").addClass("is-active");
            // });
            jQuery("#continue_to_payment").addClass("is-in-active");
	    $('html, body').animate({scrollTop: $('#checkout-form-wrap').offset().top}, 400);
            $("#checkout-form-wrap").get(0).scrollIntoView();
            // return true;
        }
    });



    // place order clicked
    jQuery("#place_order").on('click', function(e){
        console.log('place order click');
        $shipping_first_name.attr({'required': "required"});
        $shipping_last_name.attr({'required': "required"});
        $shipping_email.attr({'required': "required"});
        $shipping_phone.attr({'required': "required"});
        // $billing_company.attr({'required': "required"});
        $shipping_address_1.attr({'required': "required"});
        // $billing_address_2.attr({'required': "required"});
        $shipping_city.attr({'required': "required"});
        $shipping_state.attr({'required': "required"});
        $shipping_postcode.attr({'required': "required"});
        $billing_email.attr({'required': "required"});
        $billing_phone.attr({'required': "required"});

        if(jQuery("#ship-to-different-address-checkbox").is(":checked") || jQuery("#ship-to-different-address-checkbox").attr('checked', true)){
                console.log('nchk');
                $("#billing_first_name").val($("#shipping_first_name").val());
                $("#billing_last_name").val($("#shipping_last_name").val());
                $( '#billing_country' ).val($("#shipping_country").val());
                $( '#billing_state' ).val($( '#shipping_state' ).val());
                //$('#billing_email').val($('#shipping_email').val());
                $( 'input#billing_postcode' ).val($( 'input#shipping_postcode' ).val());
                $( '#billing_city' ).val($( '#shipping_city' ).val());
                $( 'input#billing_address_1' ).val($( 'input#shipping_address_1' ).val());
                $( 'input#billing_address_2' ).val($( 'input#shipping_address_2' ).val());
        } else {
                console.log('chk');
                $billing_first_name.attr({'required': "required"});
                $billing_last_name.attr({'required': "required"});
                $billing_address_1.attr({'required': "required"});
                $billing_city.attr({'required': "required"});
                $billing_state.attr({'required': "required"});
                $billing_postcode.attr({'required': "required"});
                //$('#billing_email').val($('#shipping_email').val());
        }

        console.log('place order');
        // console.log("fsdfsdfdf");
        //e.preventDefault();
        //return false;
        console.log('continue');
        /*$billing_first_name.attr({'required': "required"});
        $billing_last_name.attr({'required': "required"});
        $billing_email.attr({'required': "required"});
        $billing_phone.attr({'required': "required"});
        // $billing_company.attr({'required': "required"});
        $billing_address_1.attr({'required': "required"});
        // $billing_address_2.attr({'required': "required"});
        $billing_city.attr({'required': "required"});
        $billing_state.attr({'required': "required"});
        $billing_postcode.attr({'required': "required"});*/
        if (!jQuery("form[name='checkout']")[0].checkValidity()) {
            return false;
        }else {

            return true;
        }
    });





    jQuery("input[name='billing_credircard']").attr({'placeholder': "Card Number*"});
    jQuery("input[name='billing_ccvnumber']").attr({'placeholder': "CVV"});
    function shippingDisable(){
        if(jQuery("#ship-to-different-address-checkbox").is(":checked")){
            jQuery("#shipping_first_name").attr("disabled","disabled");
            jQuery("#shipping_last_name").attr("disabled","disabled");
            jQuery("#shipping_company").attr("disabled","disabled");
            jQuery("#shipping_country").attr("disabled","disabled");
            jQuery("#shipping_address_1").attr("disabled","disabled");
            jQuery("#shipping_address_2").attr("disabled","disabled");
            jQuery("#shipping_city").attr("disabled","disabled");
            jQuery("#shipping_state").attr("disabled","disabled");
            jQuery("#shipping_postcode").attr("disabled","disabled");
        }else{
            jQuery("#shipping_first_name").attr("disabled",false);
            jQuery("#shipping_last_name").attr("disabled",false);
            jQuery("#shipping_company").attr("disabled",false);
            jQuery("#shipping_country").attr("disabled",false);
            jQuery("#shipping_address_1").attr("disabled",false);
            jQuery("#shipping_address_2").attr("disabled",false);
            jQuery("#shipping_city").attr("disabled",false);
            jQuery("#shipping_state").attr("disabled",false);
            jQuery("#shipping_postcode").attr("disabled",false);
        }
    }
    //shippingDisable();
    jQuery("label[for='ship-to-different-address-checkbox']").on('click', function(){
      //  shippingDisable();
    });

    if(jQuery("#amazon_customer_details").is(":visible")){
        jQuery("#continue_to_payment").addClass("hide");
        jQuery(document).ajaxSuccess(function () {
            jQuery("#payment").addClass("is-active-for-paypal");
        });
    }

    //checkout placeholders
    var checkoutUrlDefault = "checkout",
        checkoutUrl = window.location.pathname.substring(1, 9);

    if(checkoutUrl === checkoutUrlDefault){
        jQuery( document ).ajaxComplete(function() {
            jQuery("input[name='billing_credircard']").attr({'placeholder': "Card Number *"});
            jQuery("input[name='billing_ccvnumber']").attr({'placeholder': "CVV2 *"});

            //payment info
            var paymentInfo = jQuery("#payment--info-block #amazon_customer_details");
            if(paymentInfo.is(":visible")){
                jQuery(".billing_shipping_wrap").css("display", "none");
                jQuery("#payment").css({"position":"inherit", "margin-top": 25 + "px"});
            }
        });
    }
    (function($) {
        if($(".stickyCart").is(":visible")){
            $.fn.stickyMenu = function(dur) {
                var floating = $(this);
                var originalTop = parseInt($(this).css('top'));
                dur = 1000;
                var $stickyrStopper = $('.sticky-stopper');
                var $sticky = $(".stickyCart");
                var generalSidebarHeight = $sticky.outerHeight();
                var stickyTop = $sticky.offset().top;
                var stickOffset = 0;
                var stickyStopperPosition = $stickyrStopper.offset().top;
                var stopPoint = stickyStopperPosition - generalSidebarHeight - stickOffset;
                var diff = stopPoint - stickOffset - 100;


                stickyMenu();

                $(window).scroll(function() {
                    stickyMenu();
                });

                function stickyMenu() {
                    var windowTop = $(window).scrollTop();
                    var scrollTop = $(this).scrollTop();
                    if (stopPoint < windowTop) {
                        floating.stop().animate({
                            top: diff
                        }, {
                            duration: dur,
                            queue: false
                        }).css({position: 'absolute', right: "-22%"});

                    } else if (stickyTop < windowTop + stickOffset) {
                        floating.stop().animate({
                            top: originalTop + scrollTop - 45
                        }, {
                            duration: dur,
                            queue: false
                        }).css({position: 'absolute', right: "-22%"});

                    } else {
                        floating.stop().animate({
                            top: 3 + "%"
                        }, {
                            duration: dur,
                            queue: false
                        }).css({position: 'absolute', right: "-22%"});
                    }


                }
                if(generalSidebarHeight >= 525){
                    $sticky.css({'overflow-y': 'scroll'})
                }

            };

            jQuery(".stickyCart").stickyMenu();
        }

    })(jQuery);

    //coupons for filter page



    jQuery(document).on(
        'open.zf.reveal', '[data-reveal]', function () {
            jQuery(".sticky_coupons_block_modal_button_first").addClass("hide");
        }
    );
    jQuery(document).on(
        'closed.zf.reveal', '[data-reveal]', function () {
            jQuery(".sticky_coupons_block_modal_button_first").removeClass("hide");
        }
    );

    // function windowSize(){
    //     var widthWindow = jQuery( window ).width();
    //     if(widthWindow > 1680){
    //         jQuery(".sticky_coupons_block.float-panel").css({"right": -16 + "%"});
    //         jQuery(window).scroll(function (event) {
    //             var scroll = jQuery(window).scrollTop();
    //             if(scroll <= 593){
    //                 jQuery(".sticky_coupons_block.float-panel").css({"position": "absolute", "right": -16 + "%"});
    //             }else if(scroll > 593){
    //                 jQuery(".sticky_coupons_block.float-panel").css({"right": 8.4 + "%"})
    //             }
    //         });
    //     }else if(widthWindow <= 1680){
    //         jQuery(".sticky_coupons_block.float-panel").css({"right": -16 + "%"});
    //         jQuery(window).scroll(function (event) {
    //             var scroll = jQuery(window).scrollTop();
    //             if(scroll <= 593){
    //                 jQuery(".sticky_coupons_block.float-panel").css({"position": "absolute", "right": -16 + "%"});
    //             }else if(scroll > 593){
    //                 jQuery(".sticky_coupons_block.float-panel").css({"right": 0 + "%"});
    //             }
    //         });
    //     }
    // }
    // windowSize();
    //
    //
    // jQuery( window ).resize(function(){
    //     windowSize();
    //
    // });

    var $parentModal = jQuery("#shopping--content-wrap-id"),
        $shoppingContentSummary = jQuery(".shopping--content-summary-block"),
        $shoppingContent_coupon_code = $shoppingContentSummary.find("input#coupon_code");
        $shoppingContent_coupon_button = $shoppingContentSummary.find("input[name='apply_coupon']");
    jQuery(".code-to-coupon").on('click', function(){
       var $this = jQuery(this),
           $thisText = $this.parent().attr('data-text-coupon');
           $shoppingContent_coupon_code.val($thisText);
           jQuery(".sticky_coupons_block_modal .close-button").click();
           // $shoppingContent_coupon_button.click();
    });

    var stickyCoupon = jQuery(".sticky_coupons_block .sticky_coupons_wrap"),
        view_available_specials = jQuery(".view_available_specials");
    if(stickyCoupon.length === 0){
        jQuery(".sticky_coupons_block").addClass("hide");
        view_available_specials.addClass("hide");
    }else if(stickyCoupon.length > 0){
        jQuery(".sticky_coupons_block").removeClass("hide");
        view_available_specials.removeClass("hide");
    }

    // for icons near the li in my account UI

    var liMyAccount = jQuery(".overnight-account-list li");

    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--dashboard')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--dashboard i").addClass('fa-tachometer');
    }
    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--orders')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--orders i").addClass('fa-shopping-basket');
    }
    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--downloads')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--downloads i").addClass('fa-file-archive-o');
    }
    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--edit-address')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--edit-address i").addClass('fa-home');
    }
    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--edit-account')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--edit-account i").addClass('fa-user');
    }
    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--customer-logout')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--customer-logout i").addClass('fa-sign-out');
    }

    if (liMyAccount.hasClass('woocommerce-MyAccount-navigation-link--club')){
        jQuery(".overnight-account-list .woocommerce-MyAccount-navigation-link--club i").addClass('fa-id-card');
    }

    //home page popup
    function setCookie(cname,cvalue,exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays*24*60*60*1000));
        var expires = "expires=" + d.toGMTString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
        var name = cname + "=";
        var decodedCookie = decodeURIComponent(document.cookie);
        var ca = decodedCookie.split(';');
        for(var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }

    // function for popup Cookie on pages will be commented while we dont't need cookie
    // UI 31 november 2017

    // function checkCookie() {
    //     var is_open_popup = getCookie("ongShowPopup");
    //     if (is_open_popup != "") {
    //         return true;
    //     } else{
    //         return false;
    //     }
    // }
    // checkCookie();
    //
    // if(jQuery(".ong-popup").length > 0 && !checkCookie()){
    //     jQuery(".ong-popup").foundation("open");
    //     jQuery(".ong-popup").on('click', ".close-button, .view-link, .coupon-text", function(){
    //         setCookie("ongShowPopup", true, 1);
    //     });
    // }

    // Add one new condition for open and close pooup  UI 31 november 2017

    if(jQuery(".ong-popup").length > 0 ){
            jQuery(".ong-popup").foundation("open");
            jQuery(".ong-popup").on('click', ".close-button, .view-link, .coupon-text", function(){
                setCookie("ongShowPopup", true, 1);
            });
        };

    jQuery('div[id*="product-"]').addClass('clearfix');

    // Function for filter
    // when page is reload or we come from links with filter
    var $filter = jQuery('ul.ong-filter');
    var filter_type = ['size', 'pa_taxonomy', 'pa_attribute'];
    var btn_wrap = jQuery('.btn-wrap');
    var container_result = jQuery(".container-result-filter");
    var filter_wrap = jQuery(".filter-result-wrap");

    container_result.hide();

    var reloaded  = function(){
        filter_wrap.empty();

        jQuery.each($filter.find('input:checkbox:checked'), function(){
            if( filter_wrap.is(':empty')) {
                jQuery('input:checkbox:checked').each(function(){
                    var resultElement = [];
                    resultElement.push(jQuery(this).val());

                    var li = jQuery("<li>" + jQuery(this).val() + "</li>");
                    var a = jQuery("<a class='clear-item' data-result-li='" + jQuery(this).val() + "'><i class='fa fa-times' aria-hidden='true'></i></a>")
                        .on('click', function () {
                            var data_attr = jQuery(this).attr('data-result-li');
                            jQuery('input[value="' + data_attr + '"]:checked').prop('checked', false);
                            li.remove();
                            jQuery( document.body ).trigger('ong_filter_start_search');
                        });

                    li.append(a);
                    filter_wrap.append(li);
                    container_result.show();
                    btn_wrap.show();

                    jQuery('#clearAll').on('click', function(){
                        var $ong_filter_items = $filter.find('li.filter--ong-filter-item ul[data-filter-type="'+filter_type+'"]').parent();

                        $ong_filter_items
                            .attr('data-has-something-inside',false)
                            .removeClass('has-something-inside')
                            .hide();
                        $filter.find('.accordion-title').removeAttr( "data-checked-count" );
                        $ong_filter_items.find('ul.filter--ong-filter-group li').remove();

                        jQuery('input:checked').prop('checked', false);

                        jQuery( document.body ).trigger('ong_filter_start_search');

                        filter_wrap.empty();
                        btn_wrap.hide();
                        container_result.hide();
                    });
                });
            }
        });

        var countLi = jQuery('ul.filter-result-wrap li').size();
        var countOfFilter = countLi;
        jQuery('.current-filter').attr('current-count-of-filter', countOfFilter);
    };

    window.onload = function() {
        var loaded = sessionStorage.getItem('loaded');
        if(loaded) {
            reloaded();
        } else {
            sessionStorage.setItem('loaded', true);
        }
    };
    
    // when item has variations of price changed view of price block on priduct page
    var priceCheck  = (function(){
        if(jQuery('body').hasClass('single-product')) {
            var emptyChildBlock = jQuery('.only-for-item-with-variables .woocommerce-variation-price');

            setTimeout (function(){ // without setTimeout doesn't work

                jQuery('.woocommerce-variation-price').detach().prependTo('.only-for-item-with-variables');

                if(emptyChildBlock.val() !== '') {
                    jQuery('p.price').hide();

                    if (jQuery('.only-for-item-with-variables .woocommerce-variation-price span.price').length == 0) {
                        jQuery('p.price').show();
                    }
                }
                 varianceCheck();
            }, 100);

            // if no variation selected
            function varianceCheck() {
                var ong_color = rx.prescription.get('ong', 'color_select', false);

                if ((jQuery('#pa_color').length > 0) && (ong_color === null || ong_color === '')) {
                    jQuery('p.price').hide();
                    jQuery('.only-for-item-with-variables').append('<p class="stock select-price">Select size and color to see price</p>');
                    return false;
                }
            };
        }
    }());

});


window.alert = function () {
    console.log('alert', arguments);
};


var TO_FRACTION_64 = 0.015625;
var TO_FRACTION_32 = TO_FRACTION_64 * 2;
var TO_FRACTION_16 = TO_FRACTION_32 * 2;

var MM_TO_INCH = 25.4;
var simplifyFraction = function (numerator, _denominator) {
    var denominator = _denominator || 64;
    // if there is no denominator then there is no fraction
    if (numerator < 1) {
        return ''
    }
    // fraction can't be broken further down:
    if (
        // a) if numerator is 1
    numerator === 1 ||
    // b) if numerator is prime number
    !((numerator % 2 === 0) || Math.sqrt(numerator) % 1 === 0)
    ) {
        return numerator + '/' + denominator
    }

    var newNumerator = numerator / 2;
    var newDenominator = denominator / 2;
    return simplifyFraction(newNumerator, newDenominator)
};

function mmToInch (_input, _to_fraction) {
    var to_fraction = _to_fraction || 64;
    var rawInches = Number(_input) / MM_TO_INCH;
    // integers
    var integers = Math.floor(rawInches);
    // limit to 6 decimals to avoid conflicts
    var decimals = Number((rawInches % 1).toFixed(6));
    // fractionize for denominator 64
    var fraction = Math.round(decimals / (1 / to_fraction));
    var simplifiedFraction = simplifyFraction(fraction, to_fraction);
    var result = [integers, simplifiedFraction];
    return result.filter(function (r) {
        return r
    }).join(' ');
}

function getIntegerAndFractionInches(millimeters, to_fractions){
    var inches = mmToInch(millimeters, to_fractions);
    var integers_and_fractions = inches.split(' ');
    if (integers_and_fractions.length === 1) {
        if (integers_and_fractions[0].indexOf("/")===-1) {
            integers_and_fractions[1] = '0/'+to_fractions;
        } else {
            integers_and_fractions[1] = integers_and_fractions[0];
            integers_and_fractions[0] = 0;
        }
    }
    var integers = parseInt(integers_and_fractions[0]);
    var fractions = integers_and_fractions[1].split('/');
    var multiplier = to_fractions / fractions[1];
    fractions[0] *=  multiplier;
    fractions[1] *=  multiplier;

    return [integers, fractions];
}

jQuery(document).ready(function()  {
            jQuery("#shipping_first_name").removeAttr("disabled");
            jQuery("#shipping_last_name").removeAttr("disabled");
            jQuery("#shipping_company").removeAttr("disabled")
            jQuery("#shipping_country").removeAttr("disabled");
            jQuery("#shipping_address_1").removeAttr("disabled");
            jQuery("#shipping_address_2").removeAttr("disabled");
            jQuery("#shipping_city").removeAttr("disabled");
            jQuery("#shipping_state").removeAttr("disabled");
            jQuery("#shipping_postcode").removeAttr("disabled");


	    jQuery('.payment_box.payment_method_eh_stripe_pay').css('display', 'none');

});



/* fb like popup */

/*window.fbAsyncInit = function() {
    FB.init({
        appId      : '651347708261563', // App ID
        //channelUrl : '//WWW.YOUR_DOMAIN.COM/channel.html', // Channel File
        status     : true, // check login status
        cookie     : true, // enable cookies to allow the server to access the session
        xfbml      : true  // parse XFBML
    });
    // Additional initialization code here
    FB.Event.subscribe('edge.create', function(url) {
        // Like was created for `url`
        document.getElementById('fbpromocode').style.visibility = "visible";
	console.log('like created');
    });
    FB.Event.subscribe('edge.remove', function(url) {
        // Like was removed for `url`
	console.log('like removed');
        document.getElementById('fbpromocode').style.visibility = "hidden";
    });
};

// Load the SDK Asynchronously
(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));*/

/*
(function(d, s, id) {
   var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=510509449056378";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
 */
