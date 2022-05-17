<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Extractor;

abstract class AbstractPostExtractor extends AbstractExtractor
{
    /**
     *
     * @param bool   $return_me
     * @param string $meta_key
     *
     * @return bool
     */
    public function wxr_filter_postmeta($return_me, $meta_key)
    {
        if ('_edit_lock' == $meta_key) {
            $return_me = true;
        }

        return $return_me;
    }

    /**
     * @param WP_Post $post
     *
     * @return array
     */
    protected function get_wxr_post_taxonomy($post)
    {
        $return = [];
        $post   = get_post($post);

        $taxonomies = get_object_taxonomies($post->post_type);
        if (empty($taxonomies)) {
            return $return;
        }

        $terms = wp_get_object_terms($post->ID, $taxonomies);

        foreach ((array) $terms as $term) {
            $return[] = [
                'taxonomy' => $term->taxonomy,
                'slug'     => $term->slug,
                'name'     => $term->name,
            ];
        }

        return $return;
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function get_posts($type)
    {
        global $wpdb;

        $where = $wpdb->prepare("{$wpdb->posts}.post_type = %s", $type);

        $where .= " AND ({$wpdb->posts}.post_status != 'auto-draft' AND {$wpdb->posts}.post_status != 'draft')";

//        if ($args['author']) {
//            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_author = %d", $args['author']);
//        }
//
//        if ($args['start_date']) {
//            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_date >= %s", date('Y-m-d', strtotime($args['start_date'])));
//        }
//
//        if ($args['end_date']) {
//            $where .= $wpdb->prepare(" AND {$wpdb->posts}.post_date < %s", date('Y-m-d', strtotime('+1 month', strtotime($args['end_date']))));
//        }

        // Grab a snapshot of post IDs, just in case it changes during the export.
        return $wpdb->get_col("SELECT ID FROM {$wpdb->posts} WHERE $where");
    }
}