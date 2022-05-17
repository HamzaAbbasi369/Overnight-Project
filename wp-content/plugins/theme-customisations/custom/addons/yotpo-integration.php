<?php

add_action('wp_ajax_ong_data_sync', function () {
    if (function_exists('wc_yotpo_get_degault_settings')) {
        $yotpo_settings = get_option('yotpo_settings', wc_yotpo_get_degault_settings());
        if(!empty($yotpo_settings['app_key']) && wc_yotpo_compatible()) {
            wc_yotpo_front_end_init();
        }
    }
}, 5);
