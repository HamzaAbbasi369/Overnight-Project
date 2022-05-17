/**
 * Created by odokienko on 07/28/17.
 */

/* global ong_filter_params */
/* global initial_filter */
/* global ong_filter_shortcode_x_params */
/* global ong_filter_shortcode_groups */
/* global ong_filter_shortcode_params */

var ong_all_filters = ong_all_filters || {};

jQuery(function($) {
    "use strict";

    var filter_type = 'size';

    var $filter = $('ul.ong-filter');
    var $filter_button = $('.filter-button');
    var $ong_filter_items = $filter.find('li.filter--ong-filter-item [data-filter-type='+filter_type+'] .size-slider');
    var $ong_filter_items_count = $ong_filter_items.length;
    var $ong_filter_parent = $ong_filter_items.closest('.filter--ong-filter-item');

    $ong_filter_parent.attr('data-has-something-inside',false)
        .removeClass('has-something-inside');
    $ong_filter_items.closest('.filter--ong-filter-item')
        .find('.accordion-title')
        .removeAttr( "data-checked-count" );

    // for sliders min/max inputs
    jQuery("input.size-of-frame-min, input.size-of-frame-max").change(function(){
        var $slider = jQuery(this).closest('.slider-box').find('.size-slider');

        var slider_min = $slider.slider("values")[0];
        var slider_max = $slider.slider("values")[1];

        var $min = jQuery(this).parent().find('input.size-of-frame-min');
        var $max = jQuery(this).parent().find('input.size-of-frame-max');

        var user_min = $min.data('value');
        var user_max = $max.data('value');
        var value0=parseInt($min.val());
        var value1=parseInt($max.val());

        if(value0 > value1){
            var temp = value0;
            value0 = value1;
            value1 = temp;
            $min.val(value0);
            $max.val(value1);
        }

        if (user_min!== undefined && value0!==user_min && user_min > value0) {
            value0 = user_min;
        }

        if (user_max!== undefined && value1!==user_max && user_max < value1) {
            value1 = user_max;
        }

        if (slider_min!==value0 || slider_max!==value1) {
            $slider.slider("values",[value0,value1]);
        }
    });

    // UI for jquery sliders
    $ong_filter_items.slider({
        range: true,
        stop: function(event, ui) {
            var $inputs = $(event.target).parent().find('.size-item-slider-inputs');
            $inputs.find('input.size-of-frame-min').data('value',ui['values'][0]);
            $inputs.find('input.size-of-frame-max').data('value',ui['values'][1]);

            $inputs.find('input.size-of-frame-min').val(ui['values'][0]);
            $inputs.find('input.size-of-frame-max').val(ui['values'][1]).change();

        },
        slide: function(event, ui){
            var $inputs = $(event.target).parent().find('.size-item-slider-inputs');
            $inputs.find('input.size-of-frame-min').val(ui['values'][0]);
            $inputs.find('input.size-of-frame-max').val(ui['values'][1]);
        },
        change: function(event, ui){
            var $inputs = $(event.target).parent().find('.size-item-slider-inputs');
            $inputs.find('input.size-of-frame-min').val(ui['values'][0]);
            $inputs.find('input.size-of-frame-max').val(ui['values'][1]);

            var $slider = $(event.target).closest('.slider-box').find('.size-slider');
            var value0 = $slider.slider("option", 'min');
            var value1 = $slider.slider("option", 'max');

            //user values
            var code = $(event.target).parent().data('filterKey');
            var $user_input = $(event.target).parent().parent().parent().find('.text-input-container input[data-code="'+code+'"]');

            $user_input.data('min', value0).data('max',value1);
        },
        create: function( event, ui ) {
            $ong_filter_items_count--;
            if ( $ong_filter_items_count === 0 ) {
                $( document.body ).trigger('ong_filter_slider_created');
            }
        }
    });


    var pa_lens_width_values = [34,60];
    var pa_bridge_values = [14,24];
    var pa_temple_values = [125,150];
    var pa_frame_width_values = [20,175];

    if (typeof initial_filter !== 'undefined' &&
        typeof initial_filter.filter !== 'undefined' &&
        typeof initial_filter.filter.size !== 'undefined' ) {

        if (typeof initial_filter.filter.size['pa_lens-width'] !== 'undefined'){
            pa_lens_width_values = initial_filter.filter.size['pa_lens-width'];
        }

        if (typeof initial_filter.filter.size['pa_bridge'] !== 'undefined'){
            pa_bridge_values = initial_filter.filter.size['pa_bridge'];
        }

        if (typeof initial_filter.filter.size['pa_temple'] !== 'undefined'){
            pa_temple_values = initial_filter.filter.size['pa_temple'];
        }
        if (typeof initial_filter.filter.size['pa_temple'] !== 'undefined'){
            pa_frame_width_values = initial_filter.filter.size['pa_frame-width'];
        }
    }

    jQuery("#pa_lens_width_slider").slider({
        min: 34,
        max: 60,
        values: pa_lens_width_values
    });
    jQuery("#pa_bridge_slider").slider({
        min: 14,
        max: 24,
        values: pa_bridge_values
    });

    jQuery("#pa_temple_slider").slider({
        min: 125,
        max: 150,
        values: pa_temple_values
    });
    jQuery("#pa_frame_width_slider").slider({
        min: 20,
        max: 175,
        values: pa_frame_width_values
    });



    //Input field filtering
    // jQuery('input').keypress(function(event){
    //     var key, keyChar;
    //     if(!event) var event = window.event;
    //
    //     if (event.keyCode) key = event.keyCode;
    //     else if(event.which) key = event.which;
    //
    //     if(key==null || key==0 || key==8 || key==13 || key==9 || key==46 || key==37 || key==39 ) return true;
    //     keyChar=String.fromCharCode(key);
    //
    //     if(!/\d/.test(keyChar))	return false;
    //
    // });

    //for view filter window if we have user size
    // use class "have-user-size"  for "filter-size-wrapper" that show or hide button and href with "I don't know my size"
    // if we have user size
    if (jQuery(".filter-size-wrapper").hasClass("have-user-size")){
        jQuery('input[type=checkbox]').prop('checked', true).checkbox("refresh");
    }

    // filter_button.on('click', function(){
    //     $ong_filter_items.find('form').submit();
    // });

    $( document.body ).on('ong_filter_start_search', function(e){
        var size_changes = 0, size_selected = 0;
        $.each($filter.find('ul.filter--ong-filter-group .slider-box[data-filter-type="'+filter_type+'"]'), function(){

            var $slider_box = $(this);
            var filter_name = $slider_box.data('filterName');

            var $slider = $slider_box.find('.size-slider');
            var $parent_wrapper = $slider.closest('li.filter--ong-filter-item');

            if (filter_name !== 'undefined'){
                if (typeof ong_all_filters[filter_type] === 'undefined'){
                    ong_all_filters[filter_type] = {};
                }
                if (typeof ong_all_filters[filter_type][filter_name] === 'undefined'){
                    ong_all_filters[filter_type][filter_name] = [];
                }
            }

            var old_filter_values = JSON.stringify(ong_all_filters[filter_type][filter_name]);
            var values =  $slider.slider("values");
            var value0 = $slider.slider("option", 'min');
            var value1 = $slider.slider("option", 'max');
            
            if (values[0]===value0 && values[1]===value1) {
                values = [];
            }
            else{
                size_selected++;
            }

            var new_filter_values = JSON.stringify(values);

            if (old_filter_values !== new_filter_values) {
                ong_all_filters[filter_type][filter_name] = values;
                size_changes++;
            }
        });
        // if (size_selected) {
        //     $ong_filter_parent
        //         .attr('data-has-something-inside',true)
        //         .addClass('has-something-inside');
        //     $ong_filter_parent.find('.accordion-title').attr('data-checked-count',size_selected);
        // }else{
        //     $ong_filter_parent.attr('data-has-something-inside',false)
        //         .removeClass('has-something-inside');
        //     $ong_filter_items.closest('.filter--ong-filter-item')
        //         .find('.accordion-title')
        //         .removeAttr( "data-checked-count" );
        // }

        if (size_changes) {
            ong_runned_filters++;
            $( document.body ).trigger('ong_filter_updated');
        }

            // select button if needed
            var width_min = jQuery("#pa_lens_width_minCost").val();
            var width_max = jQuery("#pa_lens_width_maxCost").val();
            if (width_min == 41 && width_max == 45) {
                jQuery('.fast-size-filter ul li[data-value="xs"]').addClass('selected');
            }
            if (width_min == 34 && width_max == 40) {
                jQuery('.fast-size-filter ul li[data-value="kids"]').addClass('selected');
            }

            if (width_min == 46 && width_max == 50) {
                jQuery('.fast-size-filter ul li[data-value="s"]').addClass('selected');
            }
            if (width_min == 51 && width_max == 54) {
                jQuery('.fast-size-filter ul li[data-value="m"]').addClass('selected');
            }
            if (width_min == 55 && width_max == 58) {
                jQuery('.fast-size-filter ul li[data-value="l"]').addClass('selected');
            }
            if (width_min == 58 && width_max == 60) {
                jQuery('.fast-size-filter ul li[data-value="xl"]').addClass('selected');
            }


    });

    $( document.body ).bind('ong_filter_renewed', function (event) {

        var size_selected = 0;

        $ong_filter_items.closest('.filter--ong-filter-item').find('.accordion-title').removeAttr( "data-checked-count" );
        $.each($filter.find('ul.filter--ong-filter-group .slider-box[data-filter-type="'+filter_type+'"]'), function(){

            var $slider_box = $(this);
            var filter_name = $slider_box.data('filterName');

            var $slider = $slider_box.find('.size-slider');

            var values =  $slider.slider("values");
            var value0 = $slider.slider("option", 'min');
            var value1 = $slider.slider("option", 'max');

            if (values[0]===value0 && values[1]===value1) {
                values = [];
            }else{
                size_selected++;
            }
        });

        if (size_selected) {
            $ong_filter_parent
                .attr('data-has-something-inside',true)
                .addClass('has-something-inside');
            $ong_filter_parent.find('.accordion-title').attr('data-checked-count',size_selected);
        }else{
            $ong_filter_parent.attr('data-has-something-inside',false)
                .removeClass('has-something-inside');
            $ong_filter_items.closest('.filter--ong-filter-item')
                .find('.accordion-title')
                .removeAttr( "data-checked-count" );
        }
    });
});


/* fast-size-filter */
jQuery('.fast-size-filter ul li').click(function() {
    //console.log( jQuery(this).data('value') );
    var current_val = jQuery(this).data('value');
    if (jQuery(this).hasClass('selected')) {
	    // already selected
	    //console.log('already selected');
	    jQuery("#pa_lens_width_minCost").val(34).change();
	    jQuery("#pa_lens_width_maxCost").val(60).change();
	    jQuery('.fast-size-filter ul li').removeClass('selected');
	    return
    }

    jQuery('.fast-size-filter ul li').removeClass('selected');
    jQuery(this).addClass('selected');
    // reset other params
    //console.log("reset other params");
    /*jQuery("#pa_bridge_minCost").val(14).change();
    jQuery("#pa_bridge_maxCost").val(24).change();
    jQuery("#pa_temple_minCost").val(125).change();
    jQuery("#pa_template_maxCost").val(126).change();
    jQuery("#pa_frame_width_minCost").val(20).change();
    jQuery("#pa_frame_width_maxCost").val(175).change();*/
    switch (jQuery(this).data('value')) {
        case "kids":

            jQuery("#pa_lens_width_minCost").val(34).change();
            jQuery("#pa_lens_width_maxCost").val(40).change();
            jQuery("#pa_lens_width_minCost").val(34).change();
            break;
        case "xs":

            jQuery("#pa_lens_width_minCost").val(41).change();
            jQuery("#pa_lens_width_maxCost").val(45).change();
            jQuery("#pa_lens_width_minCost").val(41).change();

/*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(15).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(126).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(21).change();
*/
            break;
        case  "s":

            jQuery("#pa_lens_width_minCost").val(46).change();
            jQuery("#pa_lens_width_maxCost").val(50).change();
            jQuery("#pa_lens_width_minCost").val(46).change();

/*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(16).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(127).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(22).change();
            */
            break;
        case "ms":

            jQuery("#pa_lens_width_minCost").val(46).change();
            jQuery("#pa_lens_width_maxCost").val(50).change();
/*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(17).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(128).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(23).change();

 */
            break;
        case "m":

            jQuery("#pa_lens_width_minCost").val(51).change();
            jQuery("#pa_lens_width_maxCost").val(54).change();
            jQuery("#pa_lens_width_minCost").val(51).change();

		    /*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(19).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(130).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(25).change();*/
            break;
        case "ml":

            jQuery("#pa_lens_width_minCost").val(36).change();
            jQuery("#pa_lens_width_maxCost").val(41).change();
/*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(20).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(131).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(26).change();*/
            break;
        case "l":

            jQuery("#pa_lens_width_minCost").val(55).change();
            jQuery("#pa_lens_width_maxCost").val(58).change();
            jQuery("#pa_lens_width_minCost").val(55).change();
/*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(22).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(132).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(27).change();*/
            break;
        case "xl":

            jQuery("#pa_lens_width_minCost").val(58).change();
            jQuery("#pa_lens_width_maxCost").val(70).change();
            jQuery("#pa_lens_width_minCost").val(58).change();

     /*
            jQuery("#pa_bridge_minCost").val(14).change();
            jQuery("#pa_bridge_maxCost").val(24).change();

            jQuery("#pa_temple_minCost").val(125).change();
            jQuery("#pa_template_maxCost").val(150).change();

            jQuery("#pa_frame_width_minCost").val(20).change();
            jQuery("#pa_frame_width_maxCost").val(175).change();*/
            break;
    }
    //console.log("refresh filter");
    //jQuery( document.body ).trigger('ong_filter_updated');
    //jQuery( document.body ).trigger('ong_filter_renewed');
    //jQuery(document.body).trigger('ong_filter_start_search');
});
