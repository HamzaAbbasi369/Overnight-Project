<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene@overnightglasses.com>
 * @copyright  Copyright (c) 2018 Vision Care Services LLC. (http://www.overnightglasses.com)
 */
function ong_ga_dynamic_remarketing() {
    ?>
    <script>
        window.addEventListener('load',function(){
            try {
                ga('set', 'dimension1', window.google_tag_params.ecomm_prodid.toString());
            } catch (e) {}
            try {
                ga('set', 'dimension2', window.google_tag_params.ecomm_pagetype.toString());
            } catch (e) {}
            try {
                ga('set', 'dimension3', window.google_tag_params.ecomm_totalvalue.toString());
            } catch (e) {}
            ga('send', 'event', 'page', 'visit', window.google_tag_params.ecomm_pagetype.toString(), {
                'nonInteraction': 1
            });
        })
    </script>
    <?php
}
add_action('wp_footer', 'ong_ga_dynamic_remarketing');
