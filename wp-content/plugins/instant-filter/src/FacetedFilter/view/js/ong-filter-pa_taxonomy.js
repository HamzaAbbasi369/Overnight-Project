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

    var filter_type = 'pa_taxonomy';

    var $filter = $('ul.ong-filter');

    $( document.body ).on('ong_filter_start_search', function(e){
        var taxonomy_changes = 0;
        $.each($filter.find('ul.filter--ong-filter-group[data-filter-type="'+filter_type+'"]'), function(){

            var $group = $(this);
            var filter_name = $group.data('filter-name');

            if (filter_name !== 'undefined'){
                if (typeof ong_all_filters[filter_type] === 'undefined'){
                    ong_all_filters[filter_type] = {};
                }
                if (typeof ong_all_filters[filter_type][filter_name] === 'undefined'){
                    ong_all_filters[filter_type][filter_name] = [];
                }
            }

            var old_filter_values = JSON.stringify(ong_all_filters[filter_type][filter_name]);

            var values = [];
            var $checked = $group.find('input[type=checkbox]:checked');

            $.each($checked, function() {
                values.push($(this).val());
            });
            if (values.length>0){
                values = values.sort();
            }

            var new_filter_values = JSON.stringify(values);

            if (old_filter_values !== new_filter_values) {
                ong_all_filters[filter_type][filter_name] = values;
                taxonomy_changes++;
            }
        });
        if (taxonomy_changes) {
            ong_runned_filters++;
            $( document.body ).trigger('ong_filter_updated');
        }
    });

    $( document.body ).bind('ong_filter_renewed', function (event) {
        var filters = event.params;

        var $ong_filter_items = $filter.find('li.filter--ong-filter-item ul[data-filter-type='+filter_type+']').parent();

        $ong_filter_items
            .attr('data-has-something-inside',false)
            .removeClass('has-something-inside')
            .hide();
        $ong_filter_items.find('.accordion-title').removeAttr( "data-checked-count" );
        $ong_filter_items.find('ul.filter--ong-filter-group li').remove();

        if (typeof filters[filter_type] === 'object') {
            $.each(filters[filter_type], function(filter_key, filter_content) {
                var $select = $filter.find('ul.filter--ong-filter-group[data-filter-type='+filter_type+'][data-filter-name='+filter_key+']');
                var $parent_wrapper = $select.closest('li.filter--ong-filter-item');
                var hasSomethingChecked = false;
                var checkedCount=0;
                $.each(filter_content, function (key, value) {

                    var option_id = filter_type+'_'+filter_key+'_'+key;
                    var filter_name = $select.data('filter-name');
                    var option_key = value._id.slug;
                    var option_name = value._id.name;
                    var option_count = value.count;

                    var checked = (typeof ong_all_filters[filter_type] !== 'undefined'
                        && typeof ong_all_filters[filter_type][filter_name] !== 'undefined'
                        && ong_all_filters[filter_type][filter_name].indexOf(option_key) > -1
                    );

                    if (checked){
                        ++checkedCount;
                        hasSomethingChecked = true;
                    }

		    if (filter_name == 'pa_brands') {
			    if (option_count == 1) {
				option_count = 0;
	                    }
			    if (option_count == 0) {
				    /*$select.append(
					'<li><input' +
					' type="checkbox"' +
					' name="['+filter_type+']['+filter_name+'][]"' +
					' value="' + option_key  + '"' +
					' id="'+option_id+'"'+
					//todo update with Title attribute
					' disabled="disabled"'+
					' data-count="' + value.count + '"' +
					' data-filter-type="'+filter_type+'"' +
					' data-filter-name="'+filter_name+'"' +
					' data-filter-key="'+filter_key+'"' +
					'><label class="check_disabled" for="'+option_id+'" style="color: #ddd">' + option_name + ' ('+option_count+')</label></li>'
				    );*/
			    } else {
				    $select.append(
					'<li><input' +
					' type="checkbox"' +
					' name="['+filter_type+']['+filter_name+'][]"' +
					' value="' + option_key  + '"' +
					' id="'+option_id+'"'+
					//todo update with Title attribute
					( checked ? ' checked="checked" ' : '') +
					' data-count="' + value.count + '"' +
					' data-filter-type="'+filter_type+'"' +
					' data-filter-name="'+filter_name+'"' +
					' data-filter-key="'+filter_key+'"' +
					'><label for="'+option_id+'">' + option_name + ' ('+option_count+')</label></li>'
				    );
			    }

                    } else if (filter_name == 'pa_color') {
                        //view only primary color ( not include spaces ore /
                        if (option_name) {
				if (!option_name.includes(" ") && !option_name.includes("/")) {
                        		var option_image = value.image;

                        		var colordot = '<img class="colordot" src="' + option_image + '">';
                        
					$select.append(
					    '<li><input' +
					    ' type="checkbox"' +
					    ' name="[' + filter_type + '][' + filter_name + '][]"' +
					    ' value="' + option_key + '"' +
					    ' id="' + option_id + '"' +
					    //todo update with Title attribute
					    (checked ? ' checked="checked" ' : '') +
					    ' data-count="' + value.count + '"' +
					    ' data-filter-type="' + filter_type + '"' +
					    ' data-filter-name="' + filter_name + '"' +
					    ' data-filter-key="' + filter_key + '"' +
					    '><label for="' + option_id + '">' + colordot + option_name + '</label></li>'
					);
				}
			}
		    } else {
			    $select.append(
				'<li><input' +
				' type="checkbox"' +
				' name="['+filter_type+']['+filter_name+'][]"' +
				' value="' + option_key  + '"' +
				' id="'+option_id+'"'+
				//todo update with Title attribute
				( checked ? ' checked="checked" ' : '') +
				' data-count="' + value.count + '"' +
				' data-filter-type="'+filter_type+'"' +
				' data-filter-name="'+filter_name+'"' +
				' data-filter-key="'+filter_key+'"' +
				'><label for="'+option_id+'">' + option_name + '</label></li>'
			    );
		    }	

                });
                if (hasSomethingChecked) {
                    // setTimeout(function(){
                    $parent_wrapper
                        .attr('data-has-something-inside',true)
                        .addClass('has-something-inside')
                    ;
                    $parent_wrapper.find('.accordion-title').attr('data-checked-count',checkedCount);
                    // },0);
                }

                $parent_wrapper.show();
            });
        }
        var data = [];
    });
});
