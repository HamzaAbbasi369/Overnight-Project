<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Indexer;

class WCCategories extends AbstractIndexer
{
    /**
     * @param int $id
     *
     * @return array
     */
    public function getData($id = 0)
    {
        $data = [];

        $sites = $this->getSitesData();
        foreach ($sites as $site) {
            $this->switchToBlog($site['blog_id']);
            if ($id) {
                $categories = [get_term($id)];
            } else {
                $categories = get_categories([
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                ]);
            }

            foreach ($categories as $category) {
                $thumbnail_id = get_woocommerce_term_meta($category->term_id, 'thumbnail_id', true);
                $image        = wp_get_attachment_url($thumbnail_id);
                if ($image) {
                    $category->img_url = $image;
                }
                $category->blog_id    = $site['blog_id'];
                $data['categories'][] = (array) $category;
            }

            $this->restoreCurrentBlog();
        }

        return $data;
    }

    /**
     * @param \WP_Term $category
     *
     * @return array
     */
    public function prepareToSync($category)
    {
        $category            = (array) $category;
        $category['blog_id'] = get_current_blog_id();

        $thumbnail_id = get_woocommerce_term_meta($category['term_id'], 'thumbnail_id', true);
        $image        = wp_get_attachment_url($thumbnail_id);
        if ($image) {
            $category['img_url'] = $image;
        }
        $category['url'] = get_term_link($category['cat_ID'], 'product_cat');

        return $category;
    }

    /**
     * @return int
     */
    public function countCategories()
    {
        return count(get_categories([
            'taxonomy'   => 'product_cat',
            'hide_empty' => false,
        ]));
    }

    public function getFilter()
    {
        return function ($record) {
            return ['term_id' => (key_exists('term_id', $record)) ? $record['term_id'] : -1];
        };
    }
}
