<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Helper;

class Post
{
    const VISIBILITY_NOT_VISIBLE = 1;
    const VISIBILITY_IN_CATALOG = 2;
    const VISIBILITY_IN_SEARCH = 3;
    const VISIBILITY_BOTH = 4;

    /**
     * @param \WC_Product $product
     *
     * @return bool
     */
    public static function is_product_active($product)
    {
        $post = get_post($product->get_id());
        return
            self::is_active($post) &&
            in_array($product->get_catalog_visibility(), ['visible', 'search']) ?
                self::VISIBILITY_BOTH :
                self::VISIBILITY_NOT_VISIBLE;
    }

    /**
     * @param \WP_Post $post
     *
     * @return bool
     */
    public static function is_active(\WP_Post $post)
    {
        $post_status_obj = get_post_status_object($post->post_status);

        return
            $post_status_obj && !$post_status_obj->exclude_from_search &&
            !in_array($post->post_status, self::get_hidden_statuses());
    }

    /**
     * @return array
     */
    public static function get_hidden_statuses()
    {
        return ['draft', 'auto-draft', 'hidden', 'private'];
    }
}
