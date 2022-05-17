<?php

add_action('template_redirect', function() {
    global $wp_query;
    if (is_singular( 'product' )) {
        $product = wc_get_product( $wp_query->post );

        if (is_a($product, 'WC_Product_Variable')) {
            add_action('woocommerce_before_single_product_summary', 'ong_woocommerce_virtual_mirror', 24);
        }
    }
}, 50);

function ong_woocommerce_virtual_mirror_plugin_url()
{
    return untrailingslashit(plugins_url('/', __FILE__));
}

function ong_woocommerce_virtual_mirror()
{
    global $product; ?>
    <div class="float-right hide-for-small-only tooltipVirtual"
            id="mirror_placeholder">
        <style>.try_on { background: #87b5e1 !important; color: white !important; width: 90px !important} .try_on:hover { background: white !important; color: #92844D !important; }</style>
        <button class="button try_on show-virtual-mirror mirror-single"
                href="#"
                data-href="<?=ong_woocommerce_virtual_mirror_plugin_url()?>/assets/core/vmjs-0-6-0.min.html?gender=female&frameFile=FRAME-FILE&amp;frameSize=FRAME-SIZE&amp;frameName=<?=urlencode($product->get_title())?>&amp;lang=en"
                rel="nofollow"
                title="Try on <?=$product->get_title()?>"><i class="fa fa-video-camera" aria-hidden="true"></i> TRY ON
        </button>
    </div>
    <?php
}

add_action( 'wp_enqueue_scripts', 'ong_virtual_mirror_enqueue_scripts', 1000);
function ong_virtual_mirror_enqueue_scripts() {
    $js = /** @lang JavaScript */ <<<'JS'
        jQuery(document).ready(function($){     
            var virtual_mirror_button = $(".product--right-content").find('.button.show-virtual-mirror.mirror-single');
            virtual_mirror_button.on('click', function(e){
                var obj = $(e.target);
                if ($('.svithumbnails img').length) {
                    /*var imageUrlTemplate = jQuery('.last').attr('href');*/
		    console.log('SELECT ELEMENT');
                    var imageUrlTemplate = jQuery('.thumb-item:visible').next().find('a').attr('href');
                } else {
                    imageUrlTemplate = jQuery('li[class=flex-active-slide]').children('img').attr('src');
                }
                if (imageUrlTemplate) {
                    var vto_image_b = imageUrlTemplate.replace('-45', '-FRONT');
                    var vto_image = vto_image_b.substr(0, vto_image_b.indexOf('-FRONT') + 6) + '.jpg';
                    if (vto_image === 'https.jpg') {
                        vto_image = vto_image_b.substr(0, vto_image_b.indexOf('-Front') + 6) + '.jpg';
                    }
                    var mirror_url = obj.data('href').replace('FRAME-FILE', vto_image).replace('FRAME-SIZE', obj.data('size-frame-width'));
                    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
                    var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

                    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;
                    var systemZoom = width / window.screen.availWidth;
                    var left = (width - 800) / 2 / systemZoom + dualScreenLeft
		    var top = (height - 870) / 2 / systemZoom + dualScreenTop
		    window.open(mirror_url, '_new', 'top='+top+',left='+left+',height=870,width=800,menubar=0');
                }    
            });
            
            $('body.single-product .summary.entry-summary .variations_form').on('found_variation', function (event, variation) {
                if ( variation.size_frame_width ) {
                    virtual_mirror_button.data('size-frame-width', variation.size_frame_width).show();
                } else {
                    virtual_mirror_button.hide();
                }
            });
        });
JS;
    wp_add_inline_script( 'custom-js', $js );
}
