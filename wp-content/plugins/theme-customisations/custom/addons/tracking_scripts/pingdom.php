<?php
/**
 * theme-customisations
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2018 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

add_action('wp_head', 'ong_pingdom_header_tracking_scripts', 1);
function ong_pingdom_header_tracking_scripts()
{
    ?>
    <script>
        var _prum = [['id', '57fb13623bb6049bcaf9cce4'],
            ['mark', 'firstbyte', (new Date()).getTime()]];
        (function () {
            var s = document.getElementsByTagName('script')[0]
                , p = document.createElement('script');
            p.async = 'async';
            p.src = '//rum-static.pingdom.net/prum.min.js';
            s.parentNode.insertBefore(p, s);
        })();
    </script>
    <?php

}