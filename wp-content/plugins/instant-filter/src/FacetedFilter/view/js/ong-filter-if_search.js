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

    var filter_type = 'if_search';

    var $filter = $('ul.ong-filter');
    var $filter_button = $('.search-button');
    var $ong_filter_items = $filter.find('li.filter--ong-filter-item [data-filter-type='+filter_type+']').closest('li.filter--ong-filter-item');

    $filter_button.on('click', function(){
        $ong_filter_items.find('form').submit();
    });

    // $filter.bind('ong_filter_renewed', function (event) {
    //     var filters = event.params;
    //
    //     // $ong_filter_items
    //     //     .attr('data-has-something-inside',false)
    //     //     .removeClass('has-something-inside')
    //     //     .hide();
    //     $ong_filter_items.find('.accordion-title').removeAttr( "data-checked-count" );
    //     $ong_filter_items.find('ul.filter--ong-filter-group input[type=search]').val('');
    //
    //     if (typeof filters[filter_type] === 'object') {
    //
    //         $.each(filters[filter_type], function(filter_key, filter_content) {
    //             var $select = $filter.find('ul.filter--ong-filter-group[data-filter-type='+filter_type+'][data-filter-name='+filter_key+']');
    //             var $parent_wrapper = $select.closest('li.filter--ong-filter-item');
    //             var hasSomethingChecked = false;
    //             var checkedCount=0;
    //             $.each(filter_content, function (key, value) {
    //
    //                 var option_id = filter_type+'_'+filter_key+'_'+key;
    //                 var filter_name = $select.data('filter-name');
    //                 var option_key = (filter_type==='pa_attribute' ? value._id : value._id.slug);
    //                 var option_name = (filter_type==='pa_attribute' ? value._id : value._id.name);
    //                 var checked = (typeof ong_all_filters[filter_type] !== 'undefined'
    //                     && typeof ong_all_filters[filter_type][filter_name] !== 'undefined'
    //                     && ong_all_filters[filter_type][filter_name].indexOf(option_key) > -1
    //                 );
    //
    //                 if (checked){
    //                     ++checkedCount;
    //                     hasSomethingChecked = true;
    //                 }
    //
    //                 $select.append(
    //                     '<li><input' +
    //                     ' type="checkbox"' +
    //                     ' name="['+filter_type+']['+filter_name+'][]"' +
    //                     ' value="' + option_key  + '"' +
    //                     ' id="'+option_id+'"'+
    //                     //todo update with Title attribute
    //                     ( checked ? ' checked="checked" ' : '') +
    //                     ' data-count="' + value.count + '"' +
    //                     ' data-filter-type="'+filter_type+'"' +
    //                     ' data-filter-name="'+filter_name+'"' +
    //                     ' data-filter-key="'+filter_key+'"' +
    //                     '><label for="'+option_id+'">' + option_name + '</label></li>'
    //                 );
    //             });
    //             if (hasSomethingChecked) {
    //                 $parent_wrapper
    //                     .attr('data-has-something-inside',true)
    //                     .addClass('has-something-inside')
    //                 ;
    //                 $parent_wrapper.find('.accordion-title').attr('data-checked-count',checkedCount);
    //             }
    //             $parent_wrapper.show();
    //         });
    //     }
    //     var data = [];
    // });
});
