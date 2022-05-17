<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Extractor;

class Categories extends AbstractExtractor
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
                    'taxonomy'   => 'category',
                    'hide_empty' => false,
                ]);
            }

            foreach ($categories as $category) {
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

        return $category;
    }

    /**
     * @return int
     */
    public function countCategories()
    {
        return count(get_categories([
            'taxonomy'   => 'category',
            'hide_empty' => false,
        ]));
    }

    public function getFilter()
    {
        return function ($record) {
            return ['term_id' => $record['term_id']];
        };
    }
}