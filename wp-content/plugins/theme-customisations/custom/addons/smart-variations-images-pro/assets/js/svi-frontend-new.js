if (!WOOSVI) {
    var WOOSVI = {};
} else {
    if (WOOSVI && typeof WOOSVI !== "object") {
        throw new Error("WOOSVI is not an Object type");
    }
}
WOOSVI.isLoaded = false;
WOOSVI.STARTS = function ($) {

    var $form = $('.variations_form');
    var $product_variations = $form.data('product_variations');
    var $woosvi_strap = $("div#woosvi_strap");
    var $variations = [];
    var $sync1, $sync2;
    // var $woosvi_slider = $woosvi_strap.hasClass('woosvi_slider');
    // var $woosvi_sliderleft = $woosvi_strap.hasClass('woosvi_slider-left');
    // var $woosvi_sliderdirection;
    // var $woosvi_lightbox = $woosvi_strap.hasClass('woosvi_lightbox');
    // var $woosvi_lens = $woosvi_strap.hasClass('woosvi_lens');
    var $woosvi_swselect = $woosvi_strap.hasClass('woosvi_swselect');
    var $svihide_thumbs = $woosvi_strap.hasClass('svihide_thumbs');
    var $svivariation_swap = true; //$woosvi_strap.hasClass('svivariation_swap');
    // var $sviforce = $woosvi_strap.hasClass('sviforce');
    var $svi_type = true;
    return{NAME: "Application initialize module", VERSION: 3.7, init: function () {


            WOOSVI.STARTS.initImages();
        },
        runInits: function () {

            if ($form.find('.variations select').length > 0) {
                WOOSVI.STARTS.getVariations(); //Carrega todas as variações
                WOOSVI.STARTS.variationLoad();
                WOOSVI.STARTS.variationChangeOnSelect();
                WOOSVI.STARTS.variationChange();
                WOOSVI.STARTS.variationReset();
            } else {
                // debugger;
                $('div.svithumbnails,a.woocommerce-main-image').hide();
            }

        },
        preventDefault: function () {
            $('div#woosvi_strap a').click(function (e) {
                e.preventDefault();
// console.log('1');
            });
        },
        /*DEFAULT HANDLER*/
        initImages: function () {
            if ($form.find('.variations select').length <= 0) {
                $svi_type = false;
            }
// console.log('2');
            $('#woosvi_strap').show();
            // if ($svihide_thumbs && $svi_type) {
            //     $('div.svithumbnails').hide().removeClass('hidden');
            // }


            $('div.svithumbnails a').click(function () {
// console.log('3');
                if ($woosvi_swselect) {
                    WOOSVI.STARTS.swselect(this);
                    WOOSVI.STARTS.initSwap(this);
                }

            });

            setTimeout(function () {
                WOOSVI.STARTS.runInits();
                WOOSVI.STARTS.preventDefault();
            }, 0);
        },
        swselect: function (v) {
// console.log('4');
            var cur_sel, opt_val;
            var woosvi_attr = $(v).find('img').data('woosvi');
            $.each($form.find('option'), function (i, v) {
                opt_val = $(v).val().replace(/ /g, '').toLowerCase();
                if (String(opt_val) === String(woosvi_attr)) {
                    cur_sel = $(v).closest('select');
                    woosvi_attr = $(v).val();
                    return false;
                }
            });
            if (cur_sel)
                cur_sel.val(woosvi_attr).trigger('change');
        },
        initRebuild: function ($variation) {
// console.log('5');
            var $columns, $classes, $variations_choosen, $size;
            // $columns = $('div.svithumbnails').data('columns');
            // $size = $('div.svithumbnails').find('img[data-woosvi="' + $variation + '"]').size() - 1;
            // $variations_choosen = $('div.svithumbnails').find('img[data-woosvi="' + $variation + '"]').closest('a');

            $('div.svithumbnails').find('img').closest('a').hide(); // hide all photo
            $('div.svithumbnails').find('img').closest('a').attr('class', '');
            //only one
            $('div.svithumbnails').find('img[data-woosvi="' + $variation + '"]').show().closest('a').show();
            $('div.svithumbnails').removeClass('hidden');

            // $variations_choosen.each(function ($loop, v) {
            //     $classes = [''];
            //
            //     if ($loop === 0 || $loop % $columns === 0) {
            //         $classes.push('first');
            //     }
            //     if (($loop + 1) % $columns === 0) {
            //         $classes.push('last');
            //     }
            //     if ($loop === $size)
            //         $classes.push('last');
            //
            //     $(v).attr('class', $classes.join(' '));
            //     if ($loop === 0) {
            //         WOOSVI.STARTS.initSwap(v);
            //     }
            // });
            WOOSVI.STARTS.preventDefault();
        },
        initSwap: function (v, action) {
// debugger;

// console.log('initSwap v', v);
// console.log('6');
            // var $clonemain = $(v).clone();

            // var image = new Image();

            // var src = $clonemain.find('img').attr("src");

            // $(image).on('load', function(e) {

                // $clonemain.attr('class', 'woocommerce-main-image zoom');
                // $('div#woosvimain a').remove();
                // $('div#woosvimain').prepend($clonemain);
return;
                setTimeout(function(){
                    // $clonemain.show();
                    var $wrap = $('#single-product-wrap');
                    var $svithumbnails = $wrap.find('div.svithumbnails');
                    if ($svihide_thumbs && $svi_type) {
                        if (action !== 'initReset'){
                            $svithumbnails.show().removeClass('hidden');
                        }
                        else {
                            $svithumbnails.hide().addClass('hidden');
                        }
                    }
                    $wrap.find('div.svithumbnails').show();
                    $wrap.find('a.woocommerce-main-image').show();

                },0);

                WOOSVI.STARTS.preventDefault();
                WOOSVI.STARTS.loader();
            // })
            // .on('error', function(e) {
            //     console.log('error this - ', e, this );
            //     do stuff on smth wrong (error 404, etc.)
            // })
            // .attr('src', src);
        },
        initReset: function () {
// console.log('7');
            var $columns, $classes, $variations_choosen;
            $columns = $('div.svithumbnails').data('columns');
            $variations_choosen = $('div.svithumbnails').find('img').closest('a');

            $('div.svithumbnails').find('img').closest('a').show();
            $('div.svithumbnails').find('img').closest('a').attr('class', '');
            $variations_choosen.each(function ($loop, v) {

                $classes = ['zoom'];
                if ($loop === 0 || $loop % $columns === 0) {
                    $classes.push('first');
                }
                if (($loop + 1) % $columns === 0) {
                    $classes.push('last');
                }

                $(v).attr('class', $classes.join(' '));
                if ($loop == 0) {
                    WOOSVI.STARTS.initSwap(v, 'initReset');
                }
            });
            WOOSVI.STARTS.preventDefault();
            WOOSVI.STARTS.loader();
        },
        /*END DEFAULT HANDLER*/
        /*SWIPER HANDLER*/
        // initSwiper: function () {
        //     // $('#woosvi_strap').fadeIn();
        //     $('#woosvi_strap').hide();
        //     $woosvi_sliderdirection = 'horizontal';
        //     $sync1 = new Swiper('#svi_mainslider', {
        //         spaceBetween: 0,
        //         pagination: '.swiper-pagination',
        //         nextButton: '.swiper-button-next',
        //         onImagesReady: function (e) {
        //             if ($woosvi_sliderleft) {
        //                 $woosvi_sliderdirection = 'vertical';
        //                 $('#svi_thumbslider').css('max-height', e.height);
        //                 WOOSVI.STARTS.initSwiper2();
        //             }
        //             if ($('#svi_thumbslider').length === 0) {
        //                 setTimeout(function () {
        //                     WOOSVI.STARTS.runInits();
        //                     WOOSVI.STARTS.preventDefault();
        //                 }, 50);
        //             }
        //         },
        //         onSlideChangeStart: function (swiper, e) {
        //             $sync2.slideTo(swiper.activeIndex, 500);
        //         },
        //         onSlideChangeEnd: function () {
        //             WOOSVI.STARTS.loader();
        //         }
        //     });
        //     if (!$woosvi_sliderleft) {
        //         WOOSVI.STARTS.initSwiper2();
        //     }
        // },
        // initSwiper2: function () {
        //     $sync2 = new Swiper('#svi_thumbslider', {
        //         pagination: '.swiper-pagination',
        //         slidesPerView: $('#svi_thumbslider').data('columns'),
        //         spaceBetween: 1,
        //         slideToClickedSlide: true,
        //         paginationClickable: true,
        //         setWrapperSize: true,
        //         direction: $woosvi_sliderdirection,
        //         onImagesReady: function (e) {
        //             setTimeout(function (e) {
        //                 WOOSVI.STARTS.runInits();
        //                 WOOSVI.STARTS.preventDefault();
        //                 WOOSVI.STARTS.loader();
        //             }, 50);
        //         },
        //         onClick: function (swiper) {
        //             if ($woosvi_swselect && typeof swiper.clickedSlide !== "undefined") {
        //                 WOOSVI.STARTS.swselect(swiper.clickedSlide);
        //             }
        //             $sync1.slideTo(swiper.clickedIndex, 200);
        //             WOOSVI.STARTS.loader();
        //         },
        //         onSlideChangeStart: function (swiper, e) {
        //             $sync1.slideTo(swiper.activeIndex, 500);
        //         },
        //         onSlideChangeEnd: function () {
        //             WOOSVI.STARTS.loader();
        //         }
        //     });
        // },
        // rebuildSwiper: function ($variation) {
        //
        //     var slideIndex;
        //     var mainslider_cloner = $('div#svi_mainslider_cloner').find('img[data-woosvi="' + $variation + '"]').closest('.swiper-slide').clone();
        //     var thumbslider_cloner = $('div#svi_thumbslider_cloner').find('img[data-woosvi="' + $variation + '"]').closest('.swiper-slide').clone();
        //     var check = $('div#svi_mainslider').find('img[data-woosvi="' + $variation + '"]');
        //     var not = $('div#svi_mainslider').find('img:not([data-woosvi="' + $variation + '"])');
        //     if (check.size() > 0) {
        //         $.each(not, function (i, v) {
        //             slideIndex = $(v).closest('.swiper-slide').index();
        //             $sync1.removeSlide(slideIndex);
        //             $sync2.removeSlide(slideIndex);
        //         });
        //     } else {
        //         $sync1.removeAllSlides();
        //         $sync2.removeAllSlides();
        //         $sync1.appendSlide(mainslider_cloner);
        //         $sync2.appendSlide(thumbslider_cloner);
        //     }
        //     WOOSVI.STARTS.preventDefault();
        //     WOOSVI.STARTS.loader();
        // },
        // resetSwiper: function () {
        //     var mainslider_cloner = $('div#svi_mainslider_cloner').find('.swiper-slide').clone();
        //     var thumbslider_cloner = $('div#svi_thumbslider_cloner').find('.swiper-slide').clone();
        //     $sync1.removeAllSlides();
        //     $sync2.removeAllSlides();
        //     $sync1.appendSlide(mainslider_cloner);
        //     $sync2.appendSlide(thumbslider_cloner);
        //     WOOSVI.STARTS.preventDefault();
        //     WOOSVI.STARTS.loader();
        // },
        /*END SWIPER HANDLER*/
        /*PRETTY PHOTO*/
        // prettyPhoto: function () {
        //
        //     if (!$woosvi_lightbox) {
        //         WOOSVI.STARTS.preventDefault();
        //         return;
        //     }
        //     $('div#svi_mainslider a,div#woosvimain a').on('click', function (e) {
        //         e.preventDefault();
        //         var click_url = $(this).attr('href');
        //         var click_title = $(this).attr('title');
        //         var api_images = [];
        //         var api_titles = [];
        //         $('div#svi_mainslider a,div.svithumbnails a:visible').each(function () {
        //             var href = $(this).attr('href');
        //             if (href === "") {
        //                 href = $(this).data('o_href');
        //             }
        //
        //             api_images.push(href);
        //             api_titles.push($(this).attr('title'));
        //         });
        //         if ($.isEmptyObject(api_images)) {
        //             api_images.push(click_url);
        //             api_titles.push(click_title);
        //         }
        //
        //         $.prettyPhoto.open(api_images, api_titles);
        //         $('div.pp_gallery').find('img[src="' + click_url + '"]').parent().trigger('click');
        //     });
        // },
        /*END PRETTY PHOTO*/
        /*LOAD LENS*/
        // LoadLens: function () {
        //     return false;
        //
        //     // $("div.sviZoomContainer").remove();
        //     // var ez, lensdata, lensoptions;
        //     // ez = $("div#woosvi_strap div#svi_mainslider .swiper-slide.swiper-slide-active img,div#woosvimain img");
        //     // lensdata = $("div#woosvi_strap").data();
        //     // lensoptions = {
        //     //     cursor: 'pointer',
        //     //     galleryActiveClass: 'active',
        //     //     containLensZoom: true,
        //     //     loadingIcon: $("div#woosvi_strap").data('spinner'),
        //     // };
        //     // $.each(lensdata, function (i, v) {
        //     //     var $key;
        //     //     switch (i) {
        //     //         case 'svilensshape':
        //     //             $key = 'lensShape';
        //     //             break;
        //     //         case 'svilenssize':
        //     //             $key = 'lensSize';
        //     //             break;
        //     //         case 'svizoomtype':
        //     //             $key = 'sviZoomType';
        //     //             break;
        //     //         case 'sviscrollzoom':
        //     //             $key = 'scrollsviZoom';
        //     //             break;
        //     //         case 'svilensfadein':
        //     //             $key = 'lensFadeIn';
        //     //             break;
        //     //         case 'svilensfadeout':
        //     //             $key = 'ensFadeOut';
        //     //             break;
        //     //     }
        //     //
        //     //     lensoptions[$key] = v;
        //     // });
        //     // ez.ezPlus(lensoptions);
        // },
        /*END LOAD LENS*/
        /*VARIATIONS HANDLER*/
        getVariations: function () {
// console.log($variations);
//             console.log('$product_variations - ', $product_variations);
            $.each($product_variations, function (i, v) {

                $variations.push(v.attributes.attribute_pa_color);
            });
// console.log('getVariations- ', $variations);
        },
        variationLoad: function () {
// console.log('8', $form);
// console.log('8');
            var varexist = false;

//получаем выбранный цвет на катигории
//             $("a.look--item-wrap").each(function() {
//                 var url = this.href;
//                 // console.log(url);
//                 var hashes = url.substring(url.indexOf('#') + 1);
//                 // console.log(hashes);
//
//                 var hash = hashes.split('=');
//                 var vars = hash[1];
//
//                 if(vars) {
//                  console.log('vars', vars);
//                 }
//
//             });





            // only for single page
            $.each($form.find('.variations.general select'), function (ic, vc) {

                // get the active color when loading the page
                var $variation = $(this).val().replace(/ /g, '').toLowerCase();

                console.log('$variation (active color) - ', $variation);

                if ($variation) {

                    varexist = $('div.svithumbnails').find('img[data-woosvi="' + $variation + '"]').length;

                        if (varexist === 0){ return; }

                        WOOSVI.STARTS.initRebuild($variation);
                }
            });
        },
        variationChange: function () {
// console.log('9');
            var varexist, varexist_vis, varexist_total;
            $(document).ajaxSend(function (event, jqxhr, settings) {
                // debugger;
                if (settings.url.indexOf("wc-ajax=get_variation") >= 0)
                    $('div.svithumbnails,div#svi_thumbslider').fadeTo("slow", 0.4);
            }
            ).ajaxComplete(function (event, xhr, settings) {
                // debugger;
                if (settings.url.indexOf("wc-ajax=get_variation") >= 0) {
                    $('div.svithumbnails,div#svi_thumbslider').fadeTo("fast", 1);
                }
            });
            $form.on('found_variation', function (event, variation) {

                var $parent = $(event.target).closest('.product-type-variable');
 console.log('$parent', $parent);


// console.log('10');
                $.each(variation.attributes, function (i, v) {

                    var $variation = v.replace(/ /g, '').toLowerCase();
                    if ($variation === '') {
                        WOOSVI.STARTS.variationLoad();
                        return false;
                    } else {
                        if (!$variation)
                            $variation = $('select[name="' + i + '"]').val();
                        // if (!$woosvi_slider) {

                        // console.log($parent.find('div.svithumbnails'));

                            varexist = $parent.find('img[data-woosvi="' + $variation + '"]').length;
                            varexist_vis = $parent.find('img[data-woosvi="' + $variation + '"]').closest('a:visible').length;
                            varexist_total = $parent.find('img').closest('a:visible').length;
                        // } else {
                        //     varexist = $('div#svi_mainslider_cloner').find('img[data-woosvi="' + $variation + '"]').length;
                        //     varexist_vis = $('div#svi_thumbslider').find('img[data-woosvi="' + $variation + '"]').length;
                        //     varexist_total = $('div#svi_thumbslider').find('img').length;
                        // }

                        if (varexist === 0)
                            return;
                        if (varexist_vis === varexist && varexist_vis === varexist_total)
                            return;
                        // if ($woosvi_slider) {
                        //     WOOSVI.STARTS.rebuildSwiper($variation);
                        // } else {
                            WOOSVI.STARTS.initRebuild($variation);
                        // }
                    }
                });
            });
        },
        variationChangeOnSelect: function () {
// console.log('11');
            if (!$svivariation_swap)
                return;
            $form.find('.variations select').on('change', function () {
                WOOSVI.STARTS.variationLoad();
            })
        },
        variationReset: function () {
            $form.on('click', '.reset_variations', function (event) {
                // console.log('12');
                // if ($woosvi_slider) {
                //     if ($('#svi_thumbslider').length > 0) {
                //         WOOSVI.STARTS.resetSwiper();
                //     }
                // } else {

                    WOOSVI.STARTS.initReset();
                // }
            });
        },
        loader: function () {
// console.log('13');
            // if ($woosvi_lightbox)
            //     WOOSVI.STARTS.prettyPhoto();
            // if ($woosvi_lens)
            //     WOOSVI.STARTS.LoadLens();
        }
        /*END VARIATIONS HANDLER*/
    }
}(jQuery.noConflict());
jQuery(document).ready(function () {
    WOOSVI.STARTS.init();
});
