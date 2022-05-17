<?php

//delete Related Products
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20); 


/**
 * RELATED PRODUCTS
 */

add_filter('woocommerce_product_related_posts_relate_by_category', '__return_false');
add_filter('woocommerce_product_related_posts_relate_by_tag', '__return_false');
add_filter( 'woocommerce_product_related_posts_force_display', '__return_true' );

add_filter('woocommerce_product_related_posts_query', function ($query, $product_id) {

    global $wpdb;
    $shape = get_the_terms($product_id, 'pa_shape');
    $shape_ids = [];
    foreach ((array)$shape as $item) {
        if (!is_object($item)) {
            continue;
        }
        $shape_ids[] = $item->term_id;
    }

    $gender = get_the_terms($product_id, 'pa_gender');
    $gender_ids = [];
    foreach ((array)$gender as $item) {
        if (!is_object($item)) {
            continue;
        }
        $gender_ids[] = $item->term_id;
    }

    if (!empty($shape_ids) || !empty($gender_ids)) {
        $query['join']  .= " INNER JOIN {$wpdb->postmeta} pm1 ON ( pm1.post_id = p.ID AND pm1.meta_value = 'instock')";


        if (!empty($shape_ids)) {
            $query['join']  .= " INNER JOIN {$wpdb->term_relationships} tr2 ON (p.ID = tr2.object_id)";
            $query['join']  .= " INNER JOIN {$wpdb->term_taxonomy} tt2 ON (tr2.term_taxonomy_id = tt2.term_taxonomy_id)";
            $query['join']  .= " INNER JOIN {$wpdb->terms} t2 ON (t2.term_id = tt2.term_id)";
            $query['where'] .= " AND ( tt2.taxonomy = 'pa_shape' AND t2.term_id IN ( " . implode(',', $shape_ids) . " ) ) ";
        }

        if (!empty($gender_ids)) {
            $query['join']  .= " INNER JOIN {$wpdb->term_relationships} tr1 ON (p.ID = tr1.object_id)";
            $query['join']  .= " INNER JOIN {$wpdb->term_taxonomy} tt1 ON (tr1.term_taxonomy_id = tt1.term_taxonomy_id)";
            $query['join']  .= " INNER JOIN {$wpdb->terms} t1 ON (t1.term_id = tt1.term_id)";
            $query['where'] .= " AND ( tt1.taxonomy = 'pa_gender' AND t1.term_id IN ( " . implode(',', $gender_ids) . " ) ) ";
        }
    }
    return $query;
}, 10, 2);

add_action('template_redirect', function() {
    if (is_product()) {
        remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
        add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_images', 10 );
    }
}, 50);


