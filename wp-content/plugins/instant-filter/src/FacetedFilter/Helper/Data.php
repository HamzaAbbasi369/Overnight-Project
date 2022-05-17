<?php
namespace OngStore\FacetedFilter\Helper;

class Data
{
    const SEARCH_RESULT_PAGE_SLUG = 'search-results';
    /**
     * @return \WP_Post
     */
    public function getSearchResultPage()
    {
        $page = get_page_by_path(self::SEARCH_RESULT_PAGE_SLUG);
        if ($page) {
            return $page;
        }
        wp_insert_post(array(
            'post_title' => __('Search Results'),
            'post_type' => 'page',
            'post_content' => '[ong_search_results]',
            'post_name' => self::SEARCH_RESULT_PAGE_SLUG,
            'post_status' => 'publish',
        ));
        return get_page_by_path(self::SEARCH_RESULT_PAGE_SLUG);
    }
}