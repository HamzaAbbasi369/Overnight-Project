var force = false;

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
						$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
						$select.val( value );
					}
					// if product page has variables of price
					var emptyBlock = jQuery('.only-for-item-with-variables');
					var emptyChildrenOfBlock = jQuery('.only-for-item-with-variables .woocommerce-variation-price');
					var dataAtrrSize = jQuery('[data-attribute_name="attribute_pa_size"] .swatch');
					var dataAtrrColor = jQuery('[data-attribute_name="attribute_pa_color"] .swatch');

                    if(jQuery('body').hasClass('single-product')) {
                        setTimeout (function(){
                            if(emptyBlock.val() !== '' || emptyChildrenOfBlock.val() !== '') {
                            	//console.log('clik in val');

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
				} )
				.on( 'click', '.reset_variations', function () {
					$( this ).closest( '.variations_form' ).find( '.swatch.selected' ).removeClass( 'selected' );
					selected = [];
				} )
				.on( 'tawcvs_no_matching_variations', function() {
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

			$form
				.addClass( 'swatches-support' )
				.on( 'click', '.swatch', function ( e ) {
					e.preventDefault();
					var $el = $( this ),
						$select = $el.closest( '.value' ).find( 'select' ),
						attribute_name = $select.data( 'attribute_name' ) || $select.attr( 'name' ),
						value = $el.data( 'value' );
					
					$select.trigger( 'focusin' );
					console.log("deselect color " + value);

					var shouldchangecolor = false;
					// Check if this combination is available
					if ( ! $select.find( 'option[value="' + value + '"]' ).length && !force) {
						//$el.siblings( '.swatch' ).removeClass( 'selected' );
						//$select.val( '' ).change();
						if( attribute_name == "attribute_pa_size"){
							shouldchangecolor = true;
							/*
							var tmp = false;
							$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('span').each(function( index ) {
								//console.log( index + ": " + $( this ).text() );
								if (!tmp){
									$( this ).click();
									$select = $el.closest( '.value' ).find( 'select' );
									if ( $select.find( 'option[value="' + value + '"]' ).length ) {
										tmp = true;
									}
								}
							});
							if(!tmp){
								$form.trigger( 'tawcvs_no_matching_variations', [$el] );
								return;
							}else{

							}*/

						}else{
							$form.trigger( 'tawcvs_no_matching_variations', [$el] );
							return;
						}
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

						if( attribute_name == "attribute_pa_size"  && shouldchangecolor ) {
							//$('select#ong_color_select  option').removeAttr("selected");
							//$('select#pa_color  option').removeAttr("selected");



						}
/*
						if( attribute_name == "attribute_pa_size"){
							if( ! $('#pa_size option[value="'+value+'"]').length ){
								var o = new Option(value, value);
								$(o).addClass('attached');
								$(o).addClass('enabled');
								$(o).html(value);
								//$select.find('option').remove().end().append(o);
								$select.append(o);

								$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('.selected').each(function( index ) {
									$( this ).removeClass('selected');
								});

							}
						}
 /**/
/**/
						if( attribute_name == "attribute_pa_size"  && shouldchangecolor ){
							force = true;
							$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('span').each(function( index ) {
								//console.log( index + ": " + $( this ).text() );

									$( this ).click();
									$select = $el.closest( '.value' ).find( 'select' );
									if ( $select.find( 'option[value="' + value + '"]' ).length ) {


										var o = new Option(value, value);
										$(o).addClass('attached');
										$(o).addClass('enabled');
										$(o).html(value);
										//$select.find('option').remove().end().append(o);
										$select.append(o);

										$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('.selected').each(function( index ) {
											$( this ).removeClass('selected');
										});

										return false;
									}
								
							});
							force = false;
						}
/**/
						$el.addClass( 'selected' ).siblings( '.selected' ).removeClass( 'selected' );
						$select.val( value );

						/*if( attribute_name == "attribute_pa_size"  && shouldchangecolor ){
							var tmp = false;
							$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('span').each(function( index ) {
								//console.log( index + ": " + $( this ).text() );
								if (!tmp){
									$( this ).click();
									$select = $el.closest( '.value' ).find( 'select' );
									if ( $select.find( 'option[value="' + value + '"]' ).length ) {
										tmp = true;
									}
								}
							});
						}*/
/*
						if( attribute_name == "attribute_pa_size"){
							var exists = false;
							$('select#pa_color').each(function(){
								if (this.value == $( '*[data-attribute_name="attribute_pa_color"]  .selected').data('value') ) {
									exists = true;
									return false;
								}
							});
							if(!exists){
								var tmp = false;
								$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('span').each(function( index ) {
									//console.log( index + ": " + $( this ).text() );
									if (!tmp){
										$( this ).click();
										$select = $el.closest( '.value' ).find( 'select' );
										if ( $select.find( 'option[value="' + value + '"]' ).length ) {
											tmp = true;
										}
									}
								});
							}
						}
*/
//						$( '*[data-attribute_name="attribute_pa_color"]  .selected').first().click();

					}
					// if product page has variables of price
					var emptyBlock = jQuery('.only-for-item-with-variables');
					var emptyChildrenOfBlock = jQuery('.only-for-item-with-variables .woocommerce-variation-price');
					var dataAtrrSize = jQuery('[data-attribute_name="attribute_pa_size"] .swatch');
					var dataAtrrColor = jQuery('[data-attribute_name="attribute_pa_color"] .swatch');

                    if(jQuery('body').hasClass('single-product')) {
                        setTimeout (function(){

							/* check if there is color aviable for this size */
							if( attribute_name == "attribute_pa_size"){
								if( ! $('#pa_color option[value="'+$( '*[data-attribute_name="attribute_pa_color"]  .selected').data('value')+'"]').length ){
									var tmp = false;
									$( '.tawcvs-swatches').filter(' *[data-attribute_name="attribute_pa_color"]' ).first().find('span').each(function( index ) {
										if(  $('#pa_color option[value="'+$( this).data('value')+'"]').length ) {
											$( this ).click();
										}
									});
								}
							}

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
				} )
				.on( 'click', '.reset_variations', function () {
					$( this ).closest( '.variations_form' ).find( '.swatch.selected' ).removeClass( 'selected' );
					selected = [];
				} )
				.on( 'tawcvs_no_matching_variations', function() {
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
