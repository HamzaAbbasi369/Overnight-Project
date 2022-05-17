
/**
 * Created by odokienko on 12/20/16.
 */

/* global ong_filter_params */
/* global initial_filter */
/* global ong_filter_shortcode_x_params */
/* global ong_filter_shortcode_groups */
/* global ong_filter_shortcode_params */

var ong_all_filters = ong_all_filters || {};
var ong_sort_options;
var ong_runned_filters = 0;

$ = jQuery;

window.onpopstate = function(event) {
    if (event.state && typeof event.state.filter !== 'undefined') {
        var new_event = jQuery.Event('ong_filter_updated');
        new_event.params = unpack_filter(event.state.filter);
        new_event.original_event = event;
        jQuery( document.body ).trigger(new_event);
        console.info('window.onpopstate', new_event);
    }
};
window.onhashchange = function(event) {
    console.log("onhashchange: location: " + document.location.href);
};

    // close menu when clicking outside
    jQuery(document).click(function(e) {
        jQuery = jQuery;
        var container = jQuery(".filter--menu-wrap");

        if (!container.is(e.target) && container.has(e.target).length === 0) {
                var opened = jQuery('ul.ong-filter .is-accordion-submenu').hasClass('is-active');
                if ( opened == true ) {
                    jQuery('ul.ong-filter .is-accordion-submenu').hide();
                }
        }
    });



var search_only = !!ong_filter_params['search_only'];
if (!search_only){
    // ong_runned_filters++;
    // var new_event = jQuery.Event('ong_filter_updated');
    // new_event.page = ong_filter_params[ong_filter_params.page_param];
    if (typeof initial_filter !=='undefined' && typeof initial_filter.filter !=='undefined' && initial_filter.filter ) {
        ong_all_filters = initial_filter.filter;
    }
    // else{
    //     ong_all_filters = {};
    //     new_event.params = [];
    // }
    // setTimeout(function(){
    //     //$( document.body ).trigger(new_event);
    // },0);
}

jQuery(function($) {
    "use strict";
    var old_products = jQuery(':not(.single-product) ul.products');
    var products = jQuery('.ong-filter-wrapper ul.products');
    // var pagination = jQuery('.ong-filter-wrapper div.ong-filter-pagination');

    jQuery(document.body).on('ong_filter_content_reloaded', function(){
        // console.log('ong_filter_content_reloaded');
        jQuery( '.variations_form' ).each( function() {
            jQuery( this ).wc_variation_form();
        });
    });

    var get_loop_class = function (loop, columns) {
        if ( 0 === ( loop - 1 ) % columns || 1 === columns ) {
            return 'first';
        } else if ( 0 === loop % columns ) {
            return 'last';
        } else {
            return '';
        }
    };

    var shorten_filter = function (filter) {
        var result = {};
        jQuery.each(filter, function(key,attribute){
            if (!jQuery.isArray(attribute) || (jQuery.isArray(attribute) && attribute.length>0)){
                result[key] = jQuery.isArray(attribute) ? attribute.join() : attribute;
            }
        });
        return result;
    };

    var unpack_filter = function (f) {
        var result = {};
        jQuery.each(f, function(key,attribute){
            if (!jQuery.isArray(attribute) || (jQuery.isArray(attribute) && attribute.length>0)){
                result[key] = jQuery.isArray(attribute) ? attribute.join() : attribute;
            }
        });
        return result;
    };

    if (typeof ong_filter_shortcode_params==='undefined' || ong_filter_shortcode_groups==='undefined'){
        return;
    }

    var ong_filters_working = false;
    var $filter = jQuery('ul.ong-filter');
    var $filterDesktop = jQuery("ul.ong-filter[data-menu-id='desktopMenuFilter']");
    var $filterMobile = jQuery("ul.ong-filter[data-menu-id='mobileMenuFilter']");
    var $filter_button = jQuery('.filter-button');

    if ($filterDesktop.hasClass('menu')) {
        // filter_button.css('margin-left', '100px');
        // filter_button.css('vertical-align', 'top');
        $filter_button.on('click', function(){
            $filterDesktop.foundation('hideAll');
        });
    }
    if ($filterMobile.hasClass('accordion')) {
        // filter_button.css('margin-left', '100px');
        // filter_button.css('margin-top', '-60px');
        $filter_button.on('click', function(){
            $filterMobile.foundation('up', $filter.find('.is-active .accordion-content'));
        });
    }

    $filter_button.on('click', function(e){
        jQuery( document.body ).trigger('ong_filter_start_search');
    });

    $('.ong-filter-dropdown-pane').on('hide.zf.dropdown',function(e){
        jQuery( document.body ).trigger('ong_filter_start_search');
    });

    $filter.on('up.zf.accordionMenu', function(e) {
        jQuery( document.body ).trigger('ong_filter_start_search');
    });

    jQuery( document.body ).on('ong_filter_start_search', function(e){
        var sort_changes = 0;

        jQuery.each($filter.find('li.filter-sort-wrapper .radio-container input[type=radio]:checked'), function(){
            var $group = $(this);
            var sort_type = $group.data('sortType');
            var sort_direction = $group.data('sortDirection');
            var old_ong_sort_options = JSON.stringify(ong_sort_options);
            var sort_options = [sort_type, sort_direction];
            var new_sort_options = JSON.stringify(sort_options);
            if (old_ong_sort_options !== new_sort_options) {
                ong_sort_options = sort_options;
                sort_changes++;
            }
        });
        if (sort_changes) {
            ong_runned_filters++;
            jQuery( document.body ).trigger('ong_filter_updated');
        }
    });

    jQuery( document.body ).bind( 'ong_filter_updated', function( event) {

        $filter.find('ul input[type=checkbox]').disable = true;
        if (event.filter_name === 'undefined' && event.filter_type === 'undefined'){
            var filter_name = event.filter_name;
            var filter_type = event.filter_type;
            var filter_key = event.filter_key;

            if (typeof ong_all_filters[filter_type] === 'undefined'){
                ong_all_filters[filter_type] = {};
            }

            ong_all_filters[filter_type][filter_name] = event.params;
        }

        ong_runned_filters--;

        if ( ong_runned_filters > 0 ) {
            return;
        }

        setTimeout(function(){
            var request_data = {
                'filter': shorten_filter(ong_all_filters), //filter
                'result': result_filter(ong_all_filters),
                // 'per_page' : ong_filter_params.per_page,                //per page
                // 'columns' : ong_filter_params.columns,                  //columns
                'x_params' : ong_filter_shortcode_x_params,
                'action' : 'ong/if_filter',
		'PageSpeed' : 'on',
		'PageSpeedFilters' : 'rewrite_images,convert_jpeg_to_webp,extend_cache,responsive_images,resize_images;'
            };
            request_data[ong_filter_params.page_param] = (event.page ? event.page : 1);


            if(typeof ong_sort_options ==='undefined'){
                ong_sort_options = [ong_filter_params.sort_options.type,ong_filter_params.sort_options.direction];
            }

            if (typeof ong_sort_options !=='undefined') {
                request_data['sort_options'] = ong_sort_options.join();
            }

            jQuery.ajax({
                url: ong_filter_params.ajaxurl,
                type: 'GET',
                dataType: 'html',
                data: request_data
            })
            .done(function(data, textStatus, jqXHR) {
                products.html(data);
                //reload svi , product image change on color click
                //initSviFontend();
		//console.log('SVI init')
	        //WOOSVI.STARTS.init();
		//console.log(WOOSVI);
		//WOOSVI.STARTS;
		//console.log(WOOSVI);
		//console.log('SVI init rebuild');
		//WOOSVI.STARTS.initReset();
		//console.log('done rebuild');
		//$('ul.thumbnails').each(function() { $(this).find('li:lt(2)').show(); });$('ul.thumbnails').each(function() { $(this).find('li:lt(2)').show(); });
	        // display first two images 
		//svi_init();
            })
            .fail(function() {
                alert("Error: " + xhr.status + ": " + xhr.statusText);
                products.html(ong_filter_params.no_results);
            })
            .always(function() {
                delete request_data["action"];
                if (typeof event.original_event === 'undefined'){
                    history.pushState(request_data, '', ong_filter_params.current_url+'?'+jQuery.param( request_data ));
                }
            });
            // products.load(
            //     ong_filter_params.ajaxurl, request_data, function(responseTxt, statusTxt, xhr){
            //     // try {
            //     //     response = jQuery.parseJSON(response);
            //         delete request_data["action"];
            //         if (typeof event.original_event === 'undefined'){
            //             history.pushState(request_data, '', ong_filter_params.current_url+'?'+$.param( request_data ));
            //         }
            //     // } catch (_error) {
            //     //     if (typeof console !== "undefined" && console !== null) {
            //     //         console.error('Malformed response', response);
            //     //     }
            //     //     return;
            //     // }
            //
            //     // if (typeof response.records !== 'undefined' && response.records.length>0) {
            //     //     old_products.html('');
            //         // products.html('');
            //         // jQuery.each(response.records, function(key, val) {
            //         //     if (typeof this.blocks !== 'undefined'
            //         //      && typeof this.blocks.productcard !== 'undefined') {
            //         //         var product_class = get_loop_class(key+1, ong_filter_params.columns);
            //         //         products.append(jQuery(this.blocks.productcard).addClass(product_class));
            //         //     }
            //         // });
            //
            //         // setTimeout(function(){
            //         //     $(document.body).trigger("ong_filter_content_reloaded");
            //         // },0);
            //
            //     // if(statusTxt === "success")
            //     //     alert("External content loaded successfully!");
            //     if(statusTxt === "error"){
            //
            //     }
            //
            //     // if (typeof response.filters !== 'undefined' /*&& response.filters.length>0*/) {
            //     //     var new_event = jQuery.Event( 'ong_filter_renewed' );
            //     //     new_event.params = response.filters;
            //     //     $( document.body ).trigger(new_event);
            //     // }
            //     // if (typeof response.pagination !== 'undefined') {
            //     //     pagination.html(response.pagination);
            //     // }
            //
            //
            //
            //
            //
            //     $filter.find('ul input[type=checkbox]').disable = false;
            // });

            // $.ajax({
            //     method: "GET",
            //     url: ong_filter_params.ajaxurl,
            //     data: request_data,
            //     cache: true
            // }).done(function (response) {
            //     try {
            //         response = jQuery.parseJSON(response);
            //         delete request_data["action"];
            //         if (typeof event.original_event === 'undefined'){
            //             history.pushState(request_data, '', ong_filter_params.current_url+'?'+$.param( request_data ));
            //         }
            //     } catch (_error) {
            //         if (typeof console !== "undefined" && console !== null) {
            //             console.error('Malformed response', response);
            //         }
            //         return;
            //     }
            //
            //     if (typeof response.records !== 'undefined' && response.records.length>0) {
            //         old_products.html('');
            //         products.html('');
            //         jQuery.each(response.records, function(key, val) {
            //             if (typeof this.blocks !== 'undefined'
            //                 && typeof this.blocks.productcard !== 'undefined') {
            //                 var product_class = get_loop_class(key+1, ong_filter_params.columns);
            //                 products.append(jQuery(this.blocks.productcard).addClass(product_class));
            //             }
            //         });
            //
            //         setTimeout(function(){
            //             $(document.body).on('ong_filter_content_reloaded', function(){
            //                 $( '.variations_form' ).each( function() {
            //                     $( this ).wc_variation_form();
            //                 });
            //             });
            //             $(document.body).trigger("ong_filter_content_reloaded");
            //         },0);
            //     } else {
            //         products.html(ong_filter_params.no_results);
            //     }
            //     if (typeof response.filters !== 'undefined' /*&& response.filters.length>0*/) {
            //         var new_event = jQuery.Event( 'ong_filter_renewed' );
            //         new_event.params = response.filters;
            //         $( document.body ).trigger(new_event);
            //     }
            //     if (typeof response.pagination !== 'undefined') {
            //         pagination.html(response.pagination);
            //     }
            // }).always(function() {
            //     $filter.find('ul input[type=checkbox]').disable = false;
            // });
        });

        //output filters value
        var result_filter = function (e) {
            var btn_wrap = jQuery('.btn-wrap');
            var container_result = jQuery(".container-result-filter");
            var filter_wrap = jQuery(".filter-result-wrap");

            filter_wrap.empty();
            container_result.hide();

            jQuery.each($filter.find('.accordion-content li input[type=checkbox]:checked'), function(){
                var resultElement = [];
                resultElement.push($(this).val());

                var li = jQuery("<li>" + $(this).val() + "</li>");
                var a = jQuery("<a class='clear-item' data-result-li='" + jQuery(this).val() + "'><i class='fa fa-times' aria-hidden='true'></i></a>")
                    .on('click', function () {
                        var data_attr = $(this).attr('data-result-li');
                        jQuery('input[value="' + data_attr + '"]:checked').prop('checked', false);
                        li.remove();
                        jQuery( document.body ).trigger('ong_filter_start_search');
                    });

                li.append(a);
                filter_wrap.append(li);
                btn_wrap.show();
                container_result.show();


                jQuery('#clearAll').on('click', function(){
                    var $ong_filter_items = $filter.find('li.filter--ong-filter-item ul[data-filter-type='+filter_type+']').parent();

                    $ong_filter_items
                        .attr('data-has-something-inside',false)
                        .removeClass('has-something-inside')
                        .hide();
                    $filter.find('.accordion-title').removeAttr( "data-checked-count" );
                    $ong_filter_items.find('ul.filter--ong-filter-group li').remove();


                    $('input:checked').prop('checked', false);
                    $( document.body ).trigger('ong_filter_start_search');

                    filter_wrap.empty();
                    btn_wrap.hide();
                    container_result.hide();
                });
            });

            var countLi = jQuery('ul.filter-result-wrap li').size();
            var countOfFilter = countLi;
            $('.current-filter').attr('current-count-of-filter', countOfFilter);
        };
    });

});

jQuery(window).on('load', function() {
    //$(".filter--ong-filter-group li").click(function () { jQuery(document.body).trigger('ong_filter_start_search'); });
    //$(".ong-filter label").click(function() { jQuery( document.body ).trigger('ong_filter_start_search'); });
    //$(".ong-filter input").click(function() { jQuery( document.body ).trigger('ong_filter_start_search'); });
    jQuery( ".ong-filter" ).children().click(function () { jQuery(document.body).trigger('ong_filter_start_search'); });
});
