<?php

global $product;

$productlinename = "";
$productlinedescription = "";
$terms = get_the_terms($product->get_id(), 'pa_product-line');
if ($terms) {
    foreach ($terms as $term) {
        $bgColor = carbon_get_term_meta($term->term_id, 'color');
        $productlinename .= "<div class='product-category'>
                                <p class='item-text'  style='background-color:$bgColor'>{$term->name}</p>
                            </div>";
        if ($term->description) {
            if(isMobile()){
                // $productlinedescription .=
                // "<div class='description-product-line'>
                //         <p class='description-text'>
                //          <ul id='accordion--for-small' class='accordion show-for-small-only' data-accordion='8h38ew-accordion' data-allow-all-closed='true' role='tablist'>
                //             <li class='accordion-item' data-accordion-item=''>
                //                 <a href='#' class='accordion-title' aria-controls='3q931e-accordion' role='tab' id='3q931e-accordion-label' aria-expanded='false' aria-selected='false'>
                //                 CATEGORY Description
                //                 </a>
                //                 <div class='accordion-content' data-tab-content='' role='tabpanel' aria-labelledby='3q931e-accordion-label' aria-hidden='true' id='3q931e-accordion'>
                //                     <h2>CATEGORY DESCRIPTIONssss</h2>
                //                     {$term->description}
                //                 </div>
                //             </li>
                //         </ul>
                //         </p>
                //     </div>";
            }else {
                $productlinedescription .=
                "<div class='description-product-line'>
                        <p class='description-text'>
                            {$term->description}
                        </p>
                    </div>";
            }
            
        }
    }
}

echo $productlinedescription;

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}
