;(function ( $ ) {
	'use strict';

	/**
	 * @TODO Code a function the calculate available combination instead of use WC hooks
	 */
	$.fn.tawcvs_variation_swatches_form = function () {
		return this.each( function() {
			var $form = $( this ),
				clicked = null,
				selected = [];
				var pa_size_form = $('#pa_size').html();
				var selected_pa_size = $('#pa_size :selected').val();
				var current_pa_size = '';
			$form
				.addClass( 'swatches-support' )
				.on( 'click', '.swatch', function ( e ) {
					e.preventDefault();
					var $el = $( this ),
						$select = $el.closest( '.value' ).find( 'select' ),
						attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
						value = $el.data( 'value' );

					$select.trigger( 'focusin' );

					// Check if this combination is available
					if ( ! $select.find( 'option[value="' + value + '"]' ).length ) {
						//$el.siblings( '.swatch' ).removeClass( 'selected' );
						//$select.val( '' ).change();
						$form.trigger( 'tawcvs_no_matching_variations', [$el] );
						return;
					}

					clicked = attribute_name;

					if ( selected.indexOf( attribute_name ) === -1 ) {
						selected.push(attribute_name);
					}

					if ( $el.hasClass( 'selected' ) ) {
						// $select.val( '' );
						// $el.removeClass( 'selected' );
                        //
						// delete selected[selected.indexOf(attribute_name)];
					} else {
						console.log('before select');
						$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
						$select.val( value );
						console.log('after select');
						console.log("Selected value: "+value+ "attr: "+attribute_name+ "selected pa_size val: "+selected_pa_size);
						current_pa_size = selected_pa_size;
						selected_pa_size = value;
					}
					// if product page has variables of price
					var emptyBlock = jQuery('.only-for-item-with-variables');
					var emptyChildrenOfBlock = jQuery('.only-for-item-with-variables .woocommerce-variation-price');
					var dataAtrrSize = jQuery('[data-attribute_name="attribute_pa_size"] .swatch');
					var dataAtrrColor = jQuery('[data-attribute_name="attribute_pa_color"] .swatch');

                    if(jQuery('body').hasClass('single-product')) {
                        setTimeout (function(){
                            if(emptyBlock.val() !== '' || emptyChildrenOfBlock.val() !== '') {
                            	// console.log('clik in val');

                                if ((jQuery('#pa_size').length > 0) && (!dataAtrrColor.hasClass('selected'))) { // if only the size was selected
                                    jQuery('p.price').hide();
                                    emptyBlock.empty();
                                    jQuery('.only-for-item-with-variables').append('<p class="stock select-price">Select color to see price</p>');
                                    return false;
                                }

                                jQuery('p.price').hide();
                                emptyBlock.empty();
                                jQuery('.woocommerce-variation-price').detach().prependTo('.only-for-item-with-variables');
                                if (jQuery('.only-for-item-with-variables .woocommerce-variation-price span.price').length == 0) {
                                    jQuery('p.price').show();
                                }
                                emptyChildrenOfBlock.empty();
                            } else if (emptyBlock.html().trim() !== '' || emptyChildrenOfBlock.html().trim() !== '') { // check for another condition for some products
                                // console.log('clik in trim');

                                if ((jQuery('#pa_size').length > 0) && (!dataAtrrColor.hasClass('selected'))) {
                                    jQuery('p.price').hide();
                                    emptyBlock.empty();
                                    jQuery('.only-for-item-with-variables').append('<p class="stock select-price">Select color to see price</p>');
                                    return false;
                                }

                                jQuery('p.price').hide();
                                emptyBlock.empty();
                                jQuery('.woocommerce-variation-price').detach().prependTo('.only-for-item-with-variables');
                                if (jQuery('.only-for-item-with-variables .woocommerce-variation-price span.price').length == 0) {
                                    jQuery('p.price').show();
                                }
                                emptyChildrenOfBlock.empty();
							}
                        }, 10);
                    }
					
					$select.change();
					/*console.log('after change');
					$("#pa_size").html(pa_size_form);
					console.log("Selected Pa SIZE after click: "+selected_pa_size);
					if (attribute_name == 'attribute_pa_size') {
						console.log('clicked attribute size');
						$("#pa_size option:selected").removeAttr("selected");
						$("#pa_size option[value='"+value+"']").attr('selected', 'selected');
						//$("#pa_color option:selected").removeAttr("selected");
					} else {
						console.log('color select pa size: '+current_pa_size);
						$("#pa_size option:selected").removeAttr("selected");
						$("#pa_size option[value='"+current_pa_size+"']").attr('selected', 'selected');
					}*/
					//$select.change();
						
				} )
				.on( 'click', '.reset_variations', function () {
					$( this ).closest( '.variations_form' ).find( '.swatch.selected' ).removeClass( 'selected' );
					selected = [];
				} )
				.on( 'tawcvs_no_matching_variations', function() {
					//$(".woocommerce-variation-availability").html("<p class='stock outofstock'>Color not available</p>");
					console.info( 'no_matching_variations', wc_add_to_cart_variation_params.i18n_no_matching_variations_text );
				} );
		} );
	};

	$( function () {
		$( '.variations_form' ).tawcvs_variation_swatches_form();
		$( document.body ).trigger( 'tawcvs_initialized' );
		$( document.body ).on('ong_filter_content_reloaded', function(){
            $( '.variations_form' ).tawcvs_variation_swatches_form();
            $( document.body ).trigger( 'tawcvs_initialized' );
		});

	} );
})( jQuery );
