<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\Core\Model\Indexer;

abstract class AbstractPostIndexer extends AbstractIndexer
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

        $list_of_numeric_taxonomies = [
            'pa_lens-height',
            'pa_lens-width',
            'pa_bridge',
            'pa_frame-width',
            'pa_temple'
        ];

        $return = [];
        $post   = get_post($post);

        $taxonomies = get_object_taxonomies($post->post_type);
        if (empty($taxonomies)) {
            return $return;
        }

        $terms = wp_get_object_terms($post->ID, $taxonomies);

        foreach ((array) $terms as $term) {
            $name = $term->name;
            if ($term->parent) {
                $ancestor_names = $this->term_ancestors($term->term_id, $term->taxonomy);
                $name           .= ' (' . implode(' / ', $ancestor_names) . ')';
            }
            if ($term->taxonomy == 'pa_color') {
                $basic_colors = get_field('basic_colors', $term);
                if ($basic_colors) {
                    foreach ($basic_colors as $basic_color) {
                        $return[ $basic_color->taxonomy ][] = [
                            'slug'          => $basic_color->slug,
                            'name'          => $basic_color->name,
                            'original-slug' => $term->slug,
                            'original-name' => $name,
                        ];
                    }
                } else {
                    $return[ $term->taxonomy ][] = [
                        'slug' => $term->slug,
                        'name' => $name,
                    ];
                }
            } elseif (in_array($term->taxonomy, $list_of_numeric_taxonomies)) {
                $return[ $term->taxonomy ][] = [
                    'slug' => $term->slug,
                    'name' => $name,
                    'cast' => (int) $term->slug
                ];
            } else {
                $return[ $term->taxonomy ][] = [
                    'slug' => $term->slug,
                    'name' => $name,
                ];
            }
        }

        return $return;
    }

    private function term_ancestors($term_id, $taxonomy)
    {
        $ancestors = get_ancestors($term_id, $taxonomy);
        $ancestors = array_reverse($ancestors);


        $names = [];
        foreach ($ancestors as $ancestor) {
            $ancestor = get_term($ancestor, $taxonomy);

            if (!is_wp_error($ancestor) && $ancestor) {
//                var_dump($ancestor);
                array_push($names, $ancestor->name);
//                $this->add_crumb( $ancestor->name, get_term_link( $ancestor ) );
            }
        }

        return $names;
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

    /**
     * @param $term
     * @param $return
     * @param $name
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function convertSizeToRanges($term, $return, $name): array
    {
        $lens_sizes = self::generateRanges(32, 59, 2, false);
        $size_name  = $this->findInRange($term->slug, $lens_sizes);

        if ($size_name) {
            $return[] = [
                'taxonomy' => 'size_range',
                'slug'     => strtolower($size_name),
                'name'     => $size_name,
            ];
        }
        //preserve original size
        $return[] = [
            'taxonomy' => $term->taxonomy,
            'slug'     => $term->slug,
            'name'     => $name,
        ];

        return $return;
    }

    /**
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected static function generateRanges($from, $to, $step = 2, $wideBoundaries = true): array
    {
        $lens_sizes = [];
        if ($wideBoundaries) {
            $lens_sizes[ $from . '-' ] = [0, $from];
        }
        for ($i = $from; $i < $to; $i = $i + $step) {
            $lens_sizes[ $i . '-' . ($i + $step - 1) ] = [$i, ($i + $step - 1)];
        }
        if ($wideBoundaries) {
            $lens_sizes[ $to . '+' ] = [$to, 1000];
        }

        return $lens_sizes;
    }

    /**
     * @param $number
     * @param $array
     *
     * @return int|null|string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function findInRange($number, $array)
    {
        foreach ($array as $key => $value) {
            list($min, $max) = $value;

            if ($number >= $min && $number <= $max) {
                return $key;
            }
        }

        return null;
    }
}