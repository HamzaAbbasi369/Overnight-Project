<?php
/**
 * instant-filter
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2018 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

/** @var int $total_count */
/** @var array $product_cards */
/** @var array $filter_blocks */
/** @var string $pagination */

/**
 * @param $loop
 * @param $columns
 *
 * @return string
 * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 */
$get_loop_class = function ($loop, $columns) {
    if ( 0 === ( $loop - 1 ) % $columns || 1 === $columns ) {
        return 'first';
    } else if ( 0 === $loop % $columns ) {
        return 'last';
    } else {
        return '';
    }
};

if (!empty($product_cards)) {
    foreach ($product_cards as $key => $product_card) {
        $post_id = $product_card->product_id;
        $html = $product_card->blocks->productcard;
        $html = str_replace('post-'.$post_id, 'post-'.$post_id.' '. $get_loop_class($key+1, $columns), $html);
        echo $html;
    }
}

if (!empty($message)) {
    echo $message;
    echo '<pre>';
    echo $trace;
    echo '</pre>';
}
