<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\FacetedFilter\Widget;

use OngStore\Core\Helper\Config;

class SearchForm extends \WP_Widget
{
    /**
     * {@inheritdoc}
     */
    function __construct()
    {
        parent::__construct(
            'ong-search-form',
            esc_html__('ONG Filter Form', Config::LANG_DOMAIN),
            ['description' => esc_html__('A ONG Filter Form Widget', Config::LANG_DOMAIN),]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        echo $this->getSearchForm();
        echo $args['after_widget'];
    }


    public function getSearchForm()
    {
        ob_start();

        include ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/searchForm.php";
        $html = ob_get_contents();

        ob_end_clean();

        return $html;
    }
}