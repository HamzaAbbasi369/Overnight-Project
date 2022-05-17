<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Extractor;

abstract class AbstractExtractor
{
    /**
     * @param int $id
     *
     * @return array
     */
    abstract public function getData($id = 0);


    abstract public function getFilter();

    /**
     * @return array
     */
    protected function getSitesData()
    {
        if (is_multisite()) {
            if (function_exists('get_sites')) {
                $sites_data = get_sites();
            } else {
                $sites_data = wp_get_sites();
            }
        } else {
            $sites_data = [
                [
                    'blog_id' => get_current_blog_id(),
                ]
            ];
        }

        return $sites_data;
    }

    /**
     * @param $blog_id
     */
    protected function switchToBlog($blog_id)
    {
        if (function_exists('switch_to_blog')) {
            switch_to_blog($blog_id);
        }
    }

    /**
     *
     */
    protected function restoreCurrentBlog()
    {
        if (function_exists('restore_current_blog')) {
            restore_current_blog();
        }
    }
}
