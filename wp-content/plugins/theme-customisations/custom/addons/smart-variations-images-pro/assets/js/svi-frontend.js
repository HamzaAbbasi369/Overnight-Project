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
    var $woosvi_strap = $("div.woosvi_strap");
    var $variations = [];
    var $woosvi_lightbox = $woosvi_strap.hasClass('woosvi_lightbox');
    var $woosvi_lens = $woosvi_strap.hasClass('woosvi_lens');
    var $woosvi_swselect = $woosvi_strap.hasClass('woosvi_swselect');
    var $svihide_thumbs = $woosvi_strap.hasClass('svihide_thumbs');
    var $svivariation_swap = $woosvi_strap.hasClass('svivariation_swap');
    // var $sviforce = $woosvi_strap.hasClass('sviforce');
    var $svi_type = true;
    return{NAME: "Application initialize module", VERSION: 3.7, init: function () {
        this.loadInits();
    },
        loadInits: function () {
            $("div.product a").unbind('click.prettyphoto');
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
                $('div.svithumbnails,a.woocommerce-main-image').hide();
            }
        },
        preventDefault: function () {
            $('div.woosvi_strap a').click(function (e) {
                e.preventDefault();
            });
        },
        /*DEFAULT HANDLER*/
        initImages: function () {
            if ($form.find('.variations select').length <= 0) {
                $svi_type = false;
            }
            $('.woosvi_strap').show();
            if ($svihide_thumbs && $svi_type) {
                $('div.svithumbnails').removeClass('hidden');
            }
            $('div.svithumbnails a').click(function () {
                if ($woosvi_swselect) {
                    WOOSVI.STARTS.swselect(this);
                }
                WOOSVI.STARTS.initSwap(this,'initImages');
            });
            setTimeout(function () {
                WOOSVI.STARTS.runInits();
                WOOSVI.STARTS.preventDefault();
            }, 0);
        },
        swselect: function (v) {
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
        initRebuild: function ($variation, $this_attr, $context) {
            var $columns, $classes, $variations_choosen, $size;

             //if ($this_attr !== undefined && $("body.single-product #single-product-wrap").length > 0){
            //      var $svithumbnailsWrap = $("#" + $this_attr);
             //}else{
                 $svithumbnailsWrap = $context.find('div.svithumbnails,ul.thumbnails');
             //}

             $columns = $svithumbnailsWrap.data('columns');
             $size = $svithumbnailsWrap.find('img[data-woosvi="' + $variation + '"]').length - 1;
             $variations_choosen = $svithumbnailsWrap.find('img[data-woosvi="' + $variation + '"]').closest('a');
             // $svithumbnailsWrap.find('img').closest('a').show(); // display all photo
             // $svithumbnailsWrap.find('img').closest('a').attr('class', '');

             //only one
             $svithumbnailsWrap.find('img:not([data-woosvi="' + $variation + '"])').closest('li.thumb-item,div.thumb-item').hide();
             // $svithumbnailsWrap.find('img[data-woosvi="' + $variation + '"]').closest('li.thumb-item,div.thumb-item').css('display','list-item');
             $svithumbnailsWrap.find('img[data-woosvi="' + $variation + '"]').closest('li.thumb-item,div.thumb-item').show();

             // console.log('img[data-woosvi="\' + $variation + \'"]', $svithumbnailsWrap.find('img[data-woosvi="' + $variation + '"]').closest('a'));


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
             //         WOOSVI.STARTS.initSwap(v,'initRebuild');
             //     }
             // });

            WOOSVI.STARTS.preventDefault();
        },
        initSwap: function (v, action) {
            var callback  = function(){
                $element = $(v);
                return function(e) {

                    console.log('callback', e, $element);
                    $element.show();
                    // $clonemain.attr('class', 'woocommerce-main-image zoom');
                    // console.log($('div#woosvimain'), $('div#woosvimain a'));
                    // $('div#woosvimain a').remove();
                    // $('div#woosvimain').prepend($clonemain); //?

                    // setTimeout(function(){

                        $clonemain.show(); //?
                        var $wrap = $('body.single-product #single-product-wrap');
                        if ($wrap.length === 0) {
                            $wrap = $element;
                        }
                        var $svithumbnails = $wrap.find('div.svithumbnails, ul.thumbnails');
                        if ($svihide_thumbs && $svi_type) {
                            if (action !== 'initReset'){
                                $svithumbnails.show().removeClass('hidden');
                            }
                            else {
                                $svithumbnails.hide().addClass('hidden');
                            }
                        }
                        $wrap.find('div.svithumbnails, ul.thumbnails').show();
                        $wrap.find('a.woocommerce-main-image').show();

                    // },0);

                    WOOSVI.STARTS.preventDefault();
                    WOOSVI.STARTS.loader();
                }
            }();



            var $clonemain = $(v).clone();

            var image = new Image();

            var src = $clonemain.find('img').attr("src");

            $(image).on('load', callback)
                .on('error', function(e) {
                    console.log('error this - ', e, this );
                    // do stuff on smth wrong (error 404, etc.)
                })
                .attr('src', src);
        },
        initReset: function () {
            var $columns, $classes, $variations_choosen;
            var $thumbnails = $('div.svithumbnails,ul.thumbnails');
            $columns = $thumbnails.data('columns');
            $variations_choosen = $thumbnails.find('img').closest('a');

            $thumbnails.find('img').closest('a').attr('class', '').show();
            $variations_choosen.each(function ($loop, v) {

                $classes = ['zoom'];
                if ($loop === 0 || $loop % $columns === 0) {
                    $classes.push('first');
                }
                if (($loop + 1) % $columns === 0) {
                    $classes.push('last');
                }

                $(v).attr('class', $classes.join(' '));
                if ($loop === 0) {
                    WOOSVI.STARTS.initSwap(v, 'initReset');
                }
            });
            WOOSVI.STARTS.preventDefault();
            WOOSVI.STARTS.loader();
        },
        /*END DEFAULT HANDLER*/
        /*VARIATIONS HANDLER*/
        getVariations: function () {
            $.each($product_variations, function (i, v) {
                $variations.push(v.attributes.attribute_pa_color);
            });
        },
        variationLoad: function () {
            var varexist = false;
            $.each($form.find('.variations.general select'), function (ic, vc) {
                // get the active color when loading the page
                var $variation = $(this).val().replace(/ /g, '').toLowerCase();
                if ($variation) {
                    varexist = $('div.svithumbnails,ul.thumbnails').find('img[data-woosvi="' + $variation + '"]').length;
                    if (varexist === 0){ return; }
                    WOOSVI.STARTS.initRebuild($variation);
                }
            });
        },
        variationChange: function () {
            var varexist, varexist_vis, varexist_total;
            $(document).ajaxSend(function (event, jqxhr, settings) {
                if (settings.url.indexOf("wc-ajax=get_variation") >= 0)
                    $('div.svithumbnails,ul.thumbnails').fadeTo("slow", 0.4);
                }
            ).ajaxComplete(function (event, xhr, settings) {
                if (settings.url.indexOf("wc-ajax=get_variation") >= 0) {
                    $('div.svithumbnails,ul.thumbnails').fadeTo("fast", 1);
                }
            });
            $form.on('found_variation', function (event, variation) {
                var $self = $(this);
                var $wrapper = $self.closest('div.look-item,#single-product-wrap');
                var $this_attr = $self.find(".variation-selector select option:selected").val();

                $.each(variation.attributes, function (attribute, value) {

                    if ( attribute !== 'attribute_pa_color' ) {
                        return;
                    }

                    var $variation = value.replace(/ /g, '').toLowerCase();
                    if ($variation === '') {
                        WOOSVI.STARTS.variationLoad();
                        return false;
                    } else {
                        if (!$variation) {
                            $variation = $('select[name="' + attribute + '"]').val();
                        }
                        varexist = $wrapper.find('div.svithumbnails,ul.thumbnails').find('img[data-woosvi="' + $variation + '"]').length;
                        // varexist_vis = $wrapper.find('div.svithumbnails,ul.thumbnails').find('img[data-woosvi="' + $variation + '"]').closest('a:visible').length;
                        // varexist_total = $wrapper.find('div.svithumbnails,ul.thumbnails').find('img').closest('a:visible').length;

                        if (varexist === 0) {
                            return;
                        }
                        // if (varexist_vis === varexist && varexist_vis === varexist_total) {
                        //     return;
                        // }
                        WOOSVI.STARTS.initRebuild($variation, $this_attr, $wrapper);
                    }
                });
            });
        },
        /*PRETTY PHOTO*/
        prettyPhoto: function () {
        
             if (!$woosvi_lightbox) {
                 WOOSVI.STARTS.preventDefault();
                 return;
             }
	     console.log('pretty photo lightbox');
             $('div#svi_mainslider a,div.svithumbnails a').on('click', function (e) {
                 e.preventDefault();
                 var click_url = $(this).attr('href');
                 var click_title = $(this).attr('title');
                 var api_images = [];
                 var api_titles = [];
                 $('div#svi_mainslider a,div.svithumbnails a:visible').each(function () {
                     var href = $(this).attr('href');
                     if (href === "") {
                         href = $(this).data('o_href');
                     }
        
                     api_images.push(href);
                     api_titles.push($(this).attr('title'));
                 });
                 if ($.isEmptyObject(api_images)) {
                     api_images.push(click_url);
                     api_titles.push(click_title);
                 }
       		  console.log("Pretty photo open"); 
                 $.prettyPhoto.open(api_images, api_titles);
                 $('div.pp_gallery').find('img[src="' + click_url + '"]').parent().trigger('click');
             });
         },
        /*LOAD LENS*/
         LoadLens: function () {
             //return false;
        
              //$("div.sviZoomContainer").remove();
              var ez, lensdata, lensoptions;
              ez = $("div.woosvi_strap, div#svi_mainslider .swiper-slide.swiper-slide-active img,div#woosvimain img");
              lensdata = $("div.woosvi_lens").data();
              lensoptions = {
                  cursor: 'pointer',
                  galleryActiveClass: 'active',
                  containLensZoom: true,
                  loadingIcon: $("div#woosvi_strap").data('spinner'),
              };
              $.each(lensdata, function (i, v) {
                  var $key;
                 switch (i) {
                      case 'svilensshape':
                          $key = 'lensShape';
                          break;
                      case 'svilenssize':
                          $key = 'lensSize';
                          break;
                     case 'svizoomtype':
                          $key = 'sviZoomType';
                         break;
                      case 'sviscrollzoom':
                          $key = 'scrollsviZoom';
                          break;
                      case 'svilensfadein':
                         $key = 'lensFadeIn';
                          break;
                      case 'svilensfadeout':
                          $key = 'ensFadeOut';
                          break;
                }
             
                  lensoptions[$key] = v;
              });
              ez.ezPlus(lensoptions);
        },
        /*END LOAD LENS*/

        variationChangeOnSelect: function () {
            if (!$svivariation_swap)
                return;
            $form.find('.variations select').on('change', function () {
                WOOSVI.STARTS.variationLoad();
            })
        },
        variationReset: function () {
            $form.on('click', '.reset_variations', function (event) {
                WOOSVI.STARTS.initReset();
            });
        },
        loader: function () {
            if ($woosvi_lightbox)
                WOOSVI.STARTS.prettyPhoto();
            /*if ($woosvi_lens) {
                WOOSVI.STARTS.LoadLens();
	    }*/
        }
        /*END VARIATIONS HANDLER*/
    }
}(jQuery.noConflict());
jQuery(document).ready(function () {
    WOOSVI.STARTS.init();
});
