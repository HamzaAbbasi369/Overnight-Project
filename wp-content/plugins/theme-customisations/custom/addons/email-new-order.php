<?php

add_action('woocommerce_order_items_meta_display', function ($output) {
    if (strpos($output, '<dt class="variation-')===false) {
        $output = str_replace("class=\"info-cart-title\"", "style=\"float: left;margin: 0 0 5px;opacity: 0.8;color: #333333;font-family: Tahoma;font-size: 15px;line-height: 23px;\"", $output);
        $output = str_replace("class=\"info-cart-value\"", "style=\"float: right;margin: 0 0 5px;opacity: 0.8;color: #333333;font-family: Tahoma;font-size: 15px;line-height: 23px;\"", $output);
        $output = str_replace("span", "p", $output);
        $output = str_replace("</div>", "<div style=\"clear:both;\"></div>", $output);
        $output = str_replace("Lenses: ", "<p style=\"opacity: 0.8;font-weight: 700;color: #333333;font-family: Tahoma;font-size: 15px;line-height: 18px;margin: 0 0 5px;\">Lenses:</p>", $output);
        $output = str_replace("Recommended Package", "<p style=\"opacity: 0.8;font-weight: 700;color: #333333;font-family: Tahoma;font-size: 15px;line-height: 18px;margin: 0 0 5px;\">Recommended Package</p>", $output);
        $output = str_replace("style=\"color:#557da1;font-weight:normal;text-decoration:underline\"", "style=\"color:#92844d;text-decoration:underline\"", $output);
    }
    return $output;
}, 5);
