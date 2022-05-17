<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\FacetedFilter;

use function GuzzleHttp\Psr7\parse_query;
use OngStore\Core\Helper\Config;
use OngStore\Core\Helper\Template;
use OngStore\FacetedFilter\Helper\BaseShortcode;
use OngStore\FacetedFilter\Helper\Data;
use OngStore\FacetedFilter\Helper\StringHelper;
use OngStore\FacetedFilter\Interfaces\ShortcodeInterface;
use OngStore\FacetedFilter\FilterTypes\OngPaAttributeFilter;
use OngStore\FacetedFilter\FilterTypes\OngPaTaxonomyFilter;

/**
 * @property Api\Extractor             extractor
 * @property \OngStore\Core\Api\Client client
 */
class OngFilter
{

    public static $slug = 'ong-filter';
    public static $separator = ',';
    public static $value_separator = ':';
    public static $group_separator = '--';
    public static $path_separator = '.';
    public static $filter_groups = [
        'pa_taxonomy'  => true,
        'size'  => true,
        'pa_attribute' => true,
        'if_search'    => false
    ];
    public static $displayTypes = [
        'top',
        'mobile'
    ];
    public static $page_param = 'pp';
    public static $id;
//    public $shortcodes = [];
    public $total_count;
    protected $client;
    protected $extractor;
    protected $atts;
    protected $dataHelper;

    /**
     * Construct the plugin object
     *
     * @param Api\ApiFactory $apiFactory
     */
    public function __construct(
        Api\ApiFactory $apiFactory,
        Helper\Data $dataHelper
    ) {
        $this->client     = $apiFactory->getClient();
        $this->extractor  = $apiFactory->getExtractor();
        $this->dataHelper = $dataHelper;

        add_action('plugins_loaded', [$this, 'init']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('init', [&$this, 'init']);

        add_filter('body_class', [$this, 'bodyClass']);
        add_filter('body_class', [$this, 'registerSearch']);
        add_action('after_setup_theme', [__CLASS__, 'removeHooks']);

        add_shortcode('ong_filter', [$this, 'filterCallback']);
        add_shortcode('ong_search_results', [$this, 'searchResultsCallback']);

//        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);

//        add_filter('post_class', [__CLASS__, 'productCatClass'], 25, 3 );

        if (is_admin()) {
            add_action('wp_ajax_ong/if_filter', [$this, 'ongIfFilter']);
            add_action('wp_ajax_nopriv_ong/if_filter', [$this, 'ongIfFilter']);
            // Add other back-end action hooks here
        } else {
//            add_action('woocommerce_product_query', function(){
//                var_dump(func_get_args());
//            });
            // Add non-Ajax front-end action hooks here
        }
    }

    private static function extractSortOrder(&$atts)
    {
        if (isset($_REQUEST["sort_options"])) {
            $atts['sort_options'] = $_REQUEST["sort_options"];
        }

        if (!is_array($atts['sort_options'])) {
            $sort =  preg_split('~\s*' . OngFilter::$separator . '\s*~', $atts['sort_options']);

            $sort_field = $sort[0];
            $direction = empty($sort[1]) ? 'desc' : ($sort[1]==='asc' ? 'asc': 'desc');
            $atts['sort_options'] = ['type' => $sort_field, 'direction' => $direction];
        }

        return $atts['sort_options'];
    }

    public function registerSearch($classes)
    {
        $slug = get_post_field('post_name');

        if ($slug!==Data::SEARCH_RESULT_PAGE_SLUG) {
            add_action('ong_theme_top_bar_menu', [$this, 'search_form'], 20);
        }
        return $classes;
    }

    public static function removeHooks()
    {
        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

        remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9);
        remove_action('woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10);
        remove_action('woocommerce_after_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_after_shop_loop', 'woocommerce_pagination', 30);
        remove_action('woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31);

        remove_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 10);
        remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
        remove_action('woocommerce_before_shop_loop', 'storefront_woocommerce_pagination', 30);
        remove_action('woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31);
    }

    public static function enqueueScripts()
    {
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';//;

        Template::enqueueStyle(
            'ong-filter-css',
            sprintf("%ssrc/FacetedFilter/view/css/%s%s.css", ONG_INSTANT_FILTER_PLUGIN_URL, OngFilter::$slug, $suffix)
        );

        Template::enqueueScript(
            OngFilter::$slug,
            sprintf("%ssrc/FacetedFilter/view/js/%s%s.js", ONG_INSTANT_FILTER_PLUGIN_URL, OngFilter::$slug, $suffix),
            ['jquery'],
            ONG_INSTANT_FILTER_PLUGIN_VERSION,
            true
        );
    }

    /**
     * This generates shortcode's body
     *
     * @param array $atts
     *
     * @return string
     */
    public function filterCallback($atts, $content = null, $tag = '')
    {
        global $wp_query, $wp_taxonomies;

	

        $atts['tag'] = $tag;
        $_REQUEST = apply_filters('ong_initial_filter', $_REQUEST);
        $current_url = $this->getCurrentUrlFreeOfFilterVars();
        $atts     = $this->mormalizeAtts($atts, $tag);

        if (empty($atts['current_url'])) {
            $atts['current_url'] = $current_url;
        }
        $this->atts = $atts;
        $search_only = filter_var($this->atts['search_only'], FILTER_VALIDATE_BOOLEAN);
        unset($this->atts['search_only']);

        $shortcode_id   = $this->atts['id'];
        $draw_products  = !!$this->atts['draw_products'];
        $draw_paginator = !!$this->atts['draw_paginator'];

        $initial_filter = $this->getInitialFilter();



        if ($draw_products){
            $products = $this->doOnePageOfProducts($_REQUEST);
	    try {
                $filter_blocks = $this->getFilterBlocks();
                $filter_blocks_json =  json_encode( $filter_blocks, JSON_HEX_APOS|JSON_HEX_QUOT );
                $js = /** @lang JavaScript */ <<<JS
                    jQuery(function($) {
                        "use strict";
                        setTimeout(function() {
                            var new_event = jQuery.Event( 'ong_filter_renewed' );
                            new_event.params = JSON.parse('{$filter_blocks_json}');
                            jQuery( document.body ).trigger(new_event);
                        }, 0);
                    });
JS;
                wp_add_inline_script( OngFilter::$slug, $js);

            } catch (\Throwable $e) {
                $message = $e->getMessage();
                $trace   = $e->getTraceAsString();
            }
        }
        if($draw_paginator){
            try {
                $pagination    = $this->getPagination($_REQUEST);
                wp_add_inline_script( OngFilter::$slug, "jQuery('.ong-filter-wrapper div.ong-filter-pagination')
                    .html('$pagination');");

            } catch (\Throwable $e) {
                $message = $e->getMessage();
                $trace   = $e->getTraceAsString();
            }
        }
        $content = $this->drawShortcodeContent($initial_filter);
        $sort_controls = $this->drawSortSection();

        $this->doLocalizeScripts($atts, $current_url, $search_only, $initial_filter);

        ob_start();
        include ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/" . $this->atts['base_template_type'] . "FilterForm.php";
        return ob_get_clean();
    }

    private static function buildDefaultFiltersList($list = [])
    {
        foreach (OngFilter::$filter_groups as $filter_group => $visible) {
            if (!$visible) {
                continue;
            }
            /** @var OngPaAttributeFilter|OngPaTaxonomyFilter $shortcode_class */
            $shortcode_class = 'OngStore\\FacetedFilter\\FilterTypes\\Ong' .
                               StringHelper::underscoreToCamel($filter_group) . 'Filter';
            $members         = $shortcode_class::$listOfMembers;
            foreach ($members as $member => $member_name) {
                $list[] = $filter_group . self::$path_separator . $member;
            }
        }

        return implode(self::$separator, $list);
    }

    private static function extractFilterList($filter_group, $items)
    {
        $result = [];
        $list   = preg_split('~\s*' . OngFilter::$separator . '\s*~', $items);
        $list   = array_filter($list);
        foreach ($list as $item) {
            $segments = explode(self::$path_separator, $item);
            if (count($segments) !== 2) {
                // error
            } else {
                list($group, $member) = $segments;
                if ($group == $filter_group) {
                    $result[] = $member;
                }
            }
        }

        return $result;
    }

    public static function extractPreFilteredValues(string $items): array
    {
        $result = [];

        $list   = preg_split('~\s*' . OngFilter::$separator . '\s*~', $items);
        $list   = array_filter($list);
        $result = [];
        foreach ($list as $item) {
            $segments = explode(self::$value_separator, $item);
            if (count($segments) !== 2) {
                continue;
            }
            list($memberAndGroup, $value) = $segments;

            $segments = explode(self::$path_separator, $memberAndGroup);
            if (count($segments) !== 2) {
                continue;
            }

            list($group, $member) = $segments;
            $result[ $group ][ $member ] = $value;
        }

        return $result;
    }

    private function drawShortcodeContent($initial_filter)
    {
        ob_start();
        foreach (OngFilter::$filter_groups as $filter_group => $visible) {
            /** @var OngPaAttributeFilter|OngPaTaxonomyFilter $shortcode_class */
            $shortcode_class = 'OngStore\\FacetedFilter\\FilterTypes\\Ong' .
                               StringHelper::underscoreToCamel($filter_group) . 'Filter';
            $shortcodeClass = new $shortcode_class($this->client, $this->extractor);
            echo $shortcodeClass->run($this->atts, $initial_filter);
        }
        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }



    private function validateFilter($filter)
    {
        return $filter;
    }

    public function searchResultsCallback()
    {
        echo do_shortcode('[ong_filter
            items="if_search.search"
            search_only=false,
            draw_sort_controls=false,
            base_template_type=search_results
        ]');
    }

    public function search_form()
    {
        $slug = Data::SEARCH_RESULT_PAGE_SLUG;
        echo do_shortcode('[ong_filter
            items="if_search.search"
            search_only=true
            draw_products=false
            draw_paginator=false
            current_url="/'.$slug.'/"
            base_template_type=nowrap
        ]');
    }

    /**
     * This will respond on Ajax filtering requests
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function ongIfFilter()
    {
        echo $this->doOnePageOfProducts($_REQUEST);
        try {
            $filter_blocks = $this->getFilterBlocks();
            $pagination    = $this->getPagination($_REQUEST);
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $trace   = $e->getTraceAsString();
        }
        ?>
        <script>
            // setTimeout(function() {
            var new_event = jQuery.Event( 'ong_filter_renewed' );
            new_event.params = JSON.parse('<?= json_encode( $filter_blocks, JSON_HEX_APOS|JSON_HEX_QUOT )?>');
            jQuery( document.body ).trigger(new_event);
            jQuery( document.body ).trigger("ong_filter_content_reloaded");
            jQuery('.ong-filter-wrapper div.ong-filter-pagination').html('<?=$pagination?>');
            // });
        </script>
        <?php
	// In PHP 5.3+, make sure we are not sending a Last-Modified header.
        if ( function_exists( 'header_remove' ) ) {
        //    @header_remove( 'Last-Modified' );
        //    @header_remove( 'Expires' );
        //    @header_remove( 'Cache-Control' );
	//    @header_remove( 'Pragma' );
        }
        wp_die();
    }

    /**
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function restoreAtts($request)
    {
        if (!isset($this->atts)) {

            $atts = (!empty($request['x_params'])) ? unserialize(base64_decode($request['x_params'])) : [];
//            $this->atts = unserialize(StringHelper::sslDec($_REQUEST['x_params']));

            $tag = (!empty($atts['tag'])) ? $atts['tag'] : 'if_filter';
            unset($atts['tag']);

            $this->atts     = $this->mormalizeAtts($atts, $tag);
        }

        return $this->atts;
    }

    private function operateBaseFilters()
    {
        if (!empty($this->atts['base_filter'])) {
            array_push($this->extractor->pipeline, $this->atts['base_filter']);
        }
    }

    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function operatePresetFilters()
    {
        //checking preset filters
        foreach (OngFilter::$filter_groups as $filter_group => $visible) {
            /** @var OngPaAttributeFilter|OngPaTaxonomyFilter $shortcode_class */
            $shortcode_class = 'OngStore\\FacetedFilter\\FilterTypes\\Ong' .
                               StringHelper::underscoreToCamel($filter_group) . 'Filter';
            if (!array_key_exists($filter_group, $this->atts)) {
                continue;
            }
            foreach ((array) $this->atts[ $filter_group ] as $key => $group_member) {
                $shortcodeClass = new $shortcode_class($this->client, $this->extractor);
                $shortcodeClass->setName($group_member);
                $values = $shortcodeClass::extractValues($this->atts, $filter_group, $group_member);
                try {
                    if (!empty($values) && $shortcode_class::isValid($group_member)) {
                        $this->extractor->addQuery($shortcodeClass, (array) $values);
                    }
                } catch (\Throwable $e) {
                    var_dump($e->getMessage());
                }
            }
        }
    }

    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function operateUserSpecifiedFilters($request)
    {
        if (!empty($request['filter']) && is_array($request['filter'])) {
            try {
            	$filter = stripslashes_deep($request['filter']);
                //first pass, to combine full set of $match conditions
                foreach ($filter as $type => $shortcodes) {
                    $shortcode_class = 'OngStore\\FacetedFilter\\FilterTypes\\Ong' .
                                       StringHelper::underscoreToCamel($type) . 'Filter';
                    if (class_exists($shortcode_class)) {
                        foreach ($shortcodes as $shortcode => $values) {
                            if ($shortcode_class::isValid($shortcode)) {

                                /** @var ShortcodeInterface $shortcode_class */
                                $shortcodeClass = new $shortcode_class($this->client, $this->extractor);
                                $shortcodeClass->setName($shortcode);
                                /** @var ShortcodeInterface $shortcode_class */

                                if ($shortcodeClass instanceof ShortcodeInterface) {
                                    $this->extractor->addQuery($shortcodeClass, $values);
                                    //                        $this->extractor->addGroup($shortcodeClass);
                                }
                            }
                        }
                    }
                }
            } catch (\Throwable $e) {
                var_dump($e->getMessage());
            }
        }
    }

    /**
     * @param array|null $pipeline
     *
     * @return array|mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getTotalCount(array &$pipeline = null)
    {
        if (!isset($pipeline)) {
            $pipeline = &$this->extractor->pipeline;
        }
        if (!isset($this->total_count)) {
            $cursor            = $this->extractor->getResults($pipeline, false);
            $this->total_count = iterator_count($cursor);
        }

        return $this->total_count;
    }

    /**
     * @param array|null $pipeline
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function getProductCards(array &$pipeline = null)
    {
        if (!isset($pipeline)) {
            $pipeline = $this->extractor->pipeline;
        }

        $this->extractor->setLimitSkip(null, null, $pipeline);

        $projected = false;
        foreach ($pipeline as &$line) {
            if (array_key_exists('$project', $line)) {
                $line['$project']['blocks.productcard'] = 1;
                $line['$project']['product_id']         = 1;
                $projected                              = true;
                break;
            }
        }

        if (!$projected) {
            array_push($pipeline, [
                '$project' => [
                    'blocks.productcard' => 1,
                    'product_id'         => 1
                ]
            ]);
        }
        $results = $this->extractor->getResults($pipeline);

        return iterator_to_array($results);
    }

    private function getFilterBlocks(): array
    {
        $filter_blocks = [];
        try {
            foreach (OngFilter::$filter_groups as $filter_group => $visible) {
                /** @var OngPaAttributeFilter|OngPaTaxonomyFilter $shortcode_class */
                $shortcode_class = 'OngStore\\FacetedFilter\\FilterTypes\\Ong' .
                                   StringHelper::underscoreToCamel($filter_group) . 'Filter';
                if (!array_key_exists($filter_group, $this->atts)) {
                    continue;
                }
                foreach ((array) $this->atts[ $filter_group ] as $key => $group_member) {
                    if (class_exists($shortcode_class)) {
                        $shortcodeClass = new $shortcode_class($this->client, $this->extractor);
                        $shortcodeClass->setName($group_member);
                        $values = $shortcodeClass::extractValues($this->atts, $filter_group, $group_member);
                        foreach (explode(self::$separator, (string) $group_member) as $shortcode) {
                            if ($shortcode_class::isValid($shortcode)) {
                                /** @var ShortcodeInterface $shortcode_class */
                                if ($shortcodeClass instanceof ShortcodeInterface) {
                                    $this->extractor->setFacetByShortcode($shortcodeClass);
                                }
                            }
                        }
                    }
                }
            }

            if (empty($this->extractor->pipeline[0]['$match']['$text'])) {
                $facets = (array)$this->extractor->getNewFilters();
                $facets = reset( $facets );

                foreach ( $facets as $key => $facet ) {
                    if($key=="pa_attribute--pa_frame-attribute"){
                        continue;
                    }
                    if($key=="pa_taxonomy--product_cat"){
                        foreach ( $facet as $key3 => $cat ) {
                            if($cat->_id->slug == 'clearance' || $cat->_id->slug == 'designer' || $cat->_id->slug == 'eyeglasses' ||
                             $cat->_id->slug == 'home-best' || $cat->_id->slug == 'home-new' || $cat->_id->slug == 'men' ||
                             $cat->_id->slug == 'men-designer' || $cat->_id->slug == 'men-unisex' || $cat->_id->slug == 'unisex' || 
                             $cat->_id->slug == 'women-designer' || $cat->_id->slug == 'women' || $cat->_id->slug == 'women-unisex'
                             ){
                                unset($facet[$key3]);
                            }
                            
                        }
                    }
                    if($key=="pa_taxonomy--pa_color"){

                        foreach ( $facet as $key2 => $color ) {
                            /*echo "OngFilter.php getFilterBlocks  FACET<br>$key -- ";
                            echo"<pre>";print_r($facet);echo"</pre>";
                            echo "<br>".$this->get_color_image_from_slug($color->_id->slug);*/
                            $color->image = $this->get_color_image_from_slug($color->_id->slug);
                            $facet[$key2] = $color;
                            //$facet->$key2->image = $this->get_color_image_from_slug($color->_id->slug);
                        }
                    }
                    list ( $filter_group, $group_member ) = explode( OngFilter::$group_separator, $key );
                    $filter_blocks[ $filter_group ][ $group_member ] = $facet;
                }
            }
        } catch (\Throwable $e) {
            var_dump($e->getMessage());
        }

//        echo"<pre>";print_r($filter_blocks);echo"</pre>";die();

        return $filter_blocks;

    }

    private function getPagination($request): string
    {
        $total_count = $this->getTotalCount();

//        $get = $request;
//        unset($get["action"]);
//        unset($get["pp"]);
//        ksort($get);
        $page     = !empty ($request["pp"]) ? intval($request["pp"]) : 1;

        $x_params    = !empty($request["x_params"]) ? htmlspecialchars($request["x_params"]) : null;
        $filter      = !empty($request["filter"]) ? $request["filter"] : '';
        $per_page    = !empty($this->atts["per_page"]) ? intval($this->atts["per_page"]) : 18;
        $columns     = !empty($request["columns"]) ? intval($request["columns"]) : 3;
        $current_url = !empty($this->atts['current_url']) ? $this->atts['current_url'] : '';
        $parsed_url = parse_url($current_url);
        $parsed_url['query'] = build_query($request);
        $url = Template::unparse_url($parsed_url);// $current_url . ($current_url'?':'&') . $query;
        ob_start();
        include ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/pagination.php";
        $pagination = ob_get_clean();

        return $pagination;
    }

    public function init()
    {
        // If woocommerce class exists and woocommerce version is greater than required version.
        if (class_exists('woocommerce') && WC()->version >= 2.1) {
            // plugin action links
            add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'pluginActionLinks']);
        }
    }

    /**
     * @param $filters
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     * @return array
     */
    public function reNewFilters($filters)
    {
        $shortcodes = [];
        foreach ($filters as $key => $filter) {
            $filter = (array) $filter;
            $filter = reset($filter);
            foreach ($filter as $filter_param => $value) {
                if ($filter_param == '_id') {
                    continue;
                }
                if (preg_match('~^([^@$]+)(?:@([^$]+))?$~', $filter_param, $matches)) { //
                    if (!empty($matches[2])) { //shortcode has several arguments
                        $shortcodes[ $matches[1] ][ $matches[2] ] = $value;
                    }
                }
            }
        }
        $filter_blocks = [];

        return $shortcodes;
    }

    /**
     * @param array $classes Body classes
     *
     * @return array Body classes
     */
    public function bodyClass($classes)
    {
        if (!in_array('woocommerce', $classes)) {
            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-page';
            $classes[] = 'instant-filter';
        }
        return $classes;
    }

    public function activate()
    {
        $this->dataHelper->getSearchResultPage();
//        add_option(self::RedirectOption, true);
    }

    /**
     * Deactivate the plugin
     */
    public function deactivate()
    {
//        $this->auth->remove(\OngStore\Search\Api\Config::PRODUCT_CODE);
        $page = $this->dataHelper->getSearchResultPage();
        wp_delete_post($page->ID, true);
    }

    /**
     * @return string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function getCurrentUrlFreeOfFilterVars(): string
    {

        $current_url = set_url_scheme('http'.(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] ==='on'?'s':'').'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        $current_url = remove_query_arg(self::$page_param, $current_url);
        $current_url = remove_query_arg('x_params', $current_url);
        $current_url = remove_query_arg('filter', $current_url);
        $current_url = remove_query_arg('action', $current_url);
        $current_url = remove_query_arg('per_page', $current_url);
        $current_url = remove_query_arg('columns', $current_url);
        $current_url = remove_query_arg('sort_options', $current_url);
        $current_url = remove_query_arg('PageSpeed', $current_url);
        $current_url = remove_query_arg('PageSpeedFilters', $current_url);

        return $current_url;
    }

    /**
     * @param array $atts
     * @param $current_url
     * @param $search_only
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function doLocalizeScripts(array $atts, $current_url, $search_only, $initial_filter)
    {
        wp_localize_script(OngFilter::$slug, 'initial_filter', $initial_filter);
        wp_localize_script(OngFilter::$slug, 'ong_filter_shortcode_x_params', base64_encode(serialize($atts)));
        wp_localize_script(
            OngFilter::$slug,
            'ong_filter_shortcode_params',
            array_intersect_key($this->atts, OngFilter::$filter_groups)
        );
        wp_localize_script(OngFilter::$slug, 'ong_filter_shortcode_groups', OngFilter::$filter_groups);
        wp_localize_script(OngFilter::$slug, 'ong_filter_params', [
            'ajaxurl'              => admin_url('admin-ajax.php'),
            'current_url'          => $current_url,
            'per_page'             => $this->atts['per_page'],
            'columns'              => $this->atts['columns'],
            'sort_options'         => $this->atts['sort_options'],
            'page_param'           => OngFilter::$page_param,
            OngFilter::$page_param => (!empty($_REQUEST[ OngFilter::$page_param ]) ? $_REQUEST[ OngFilter::$page_param ] : 1),
            'search_only'          => $search_only,
            'no_results'           => $this->getNoResultsText()
        ]);
    }

    /**
     * @param $atts
     * @param $tag
     * @param $current_url
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function mormalizeAtts($atts, $tag): array
    {

        // normalize attribute keys, lowercase
        $atts = array_change_key_case((array) $atts, CASE_LOWER);

        $defaults = [
	        'type'               => 'top',
	        'cssclass'           => OngFilter::$slug,
	        'per_page'           => 18,
	        'columns'            => 3,
	        'sort_options'       => 'default,desc',
	        'items'              => self::buildDefaultFiltersList(),
	        'id'                 => OngFilter::$slug . '-' . ++ self::$id,
	        'filters'            => '',
	        'base_filter'        => json_encode([
		        '$match' => [
			        'product_type' => 'variable',
			        'stock_status' => 'instock'
		        ]
	        ]),
	        'draw_products'      => true,
	        'draw_paginator'     => true,
	        'draw_sort_controls'     => true,
	        'base_template_type' => StringHelper::is_mobile() ? 'mobile' : 'desktop',
	        'search_only'        => false,
            'current_url'        => ''
        ];


	// CLEARANCE CHECK
	if ($atts['clearance'] != '') {
		$arr = array_map('intval', explode(",",$atts['clearance']));
		$defaults['base_filter'] = json_encode(['$match' => ['product_type' => 'variable', 'stock_status' => 'instock', 'product_id' => [ '$in' => $arr ]]]);
	}

        $atts = shortcode_atts($defaults, $atts, $tag);

        if (!in_array($atts['type'], self::$displayTypes)) {
            $atts['type'] = reset(self::$displayTypes);
        }

        $atts['draw_products']  = filter_var($atts['draw_products'], FILTER_VALIDATE_BOOLEAN);
        $atts['draw_paginator'] = filter_var($atts['draw_paginator'], FILTER_VALIDATE_BOOLEAN);
        $atts['draw_sort_controls'] = filter_var($atts['draw_sort_controls'], FILTER_VALIDATE_BOOLEAN);

        foreach (self::$filter_groups as $filter_group => $visible) {
            $atts[ $filter_group ] = self::extractFilterList($filter_group, $atts['items']);
        }

        if (is_string($atts['filters'])){
            $atts['filters']     = empty($atts['filters']) ? [] : self::extractPreFilteredValues($atts['filters']);
        }
        $atts['sort_options']  = self::extractSortOrder($atts);
	    $atts['base_filter'] = $this->processBaseFilter( $atts, $defaults );

        return $atts;
    }

    /**
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function getInitialFilter(): array
    {
        $initial_filter = [];
        if (!defined('DOING_AJAX')) {
            if (!empty($_REQUEST) && array_key_exists('filter', $_REQUEST)) {
                $get = stripslashes_deep($_REQUEST);
                $filter = $get['filter'];
                $filter = $this->validateFilter($filter);
                $initial_filter['filter'] = $filter;
            }
        }

        return $initial_filter;
    }

    /**
     * @return string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function getNoResultsText(): string
    {
        ob_start();
        wc_get_template('loop/no-products-found.php');
        $no_results = ob_get_clean();

        return $no_results;
    }

    private function setSortOrder(array &$pipeline = null)
    {
        if (!isset($pipeline)) {
            $pipeline = &$this->extractor->pipeline;
        }
        if (!empty($pipeline[0]['$match']['$text'])) {
	        $this->extractor->setSort(['score' => ['$meta' => 'textScore']], 1, $pipeline);
        } else {
	        $sort_options  = self::extractSortOrder($this->atts);
	        $field = $this->getSortField($sort_options['type']);
	        $direction = ($sort_options['direction']==='asc') ? 1 : -1;

	        $this->extractor->setSort($field, $direction, $pipeline);
        }
    }

    private function drawSortSection()
    {
        if (!$this->atts['draw_sort_controls']) {
           return '';
        }

        $shortcode_id   = $this->atts['id'];
        $current_sort_options = $this->atts['sort_options'];
        
        ob_start();
        include ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/sort_section.php";
        
        return ob_get_clean(); 
    }

    private function getSortField($data)
    {
        $map = [
            'default' => 'menu_order',
            'price' => 'price',
            'date_arrival' => 'post_date'
        ];

        if (array_key_exists($data, $map)) {
            return $map[$data];
        } else {
            return $map['default'];
        }
    }

	/**
	 * @param $atts
	 * @param $defaults
	 *
	 * @return array|mixed|object
	 * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
	 */
	protected function processBaseFilter( $atts, $defaults ) {
	    if(!empty($atts['base_filter']) ) {
	        if (is_array($atts['base_filter'])){
                $base_filter = $atts['base_filter'];
            } else {
                $base_filter = json_decode( (string) $atts['base_filter'], true );
                if ( JSON_ERROR_NONE !== json_last_error() ) {
                    throw new \RuntimeException( 'Unable to parse response body into JSON: ' . json_last_error() );
                }
            }
        }
		return $base_filter === null ? $defaults['base_filter'] : $base_filter;
	}

	private function normalisePipeline() {
		if (!isset($pipeline)) {
			$pipeline = &$this->extractor->pipeline;
		}

		foreach ($pipeline as $key => &$line) {
			if ($key && array_key_exists('$match', $line) && array_key_exists('$text', $line['$match'])) {
				//we are here if ['$match']['$text'] not on a first position in the pipeline
				$temp = $line['$match']['$text'];
				unset($pipeline[$key]);
				break;
			}
		}
		if (isset($temp)) {
			array_unshift($pipeline, ['$match' => ['$text' => $temp]]);
		}
	}

    /**
     * @param $request
     *
     * @return string
     * @author Eugene Odokiienko <eugene@overnightglasses.com>
     */
    private function doOnePageOfProducts($request)
    {
        $this->extractor->startBatch(Config::PLATFORM, \OngStore\Core\Api\Config::ENTITY_PRODUCT);

        $this->restoreAtts($request);
        $this->operateBaseFilters();
        $this->operatePresetFilters();
        $this->operateUserSpecifiedFilters($request);
        $this->normalisePipeline();

        try {
            $total_count = $this->getTotalCount();
            $this->setSortOrder();
            $product_cards = $this->getProductCards();
            $columns       = $this->atts['columns'];
        } catch (\Throwable $e) {
            $message = $e->getMessage();
            $trace   = $e->getTraceAsString();
        }

        ob_start();
        include ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/filter-ajax-response.php";
        $out = ob_get_clean();

        return $out;
    }

    private function get_color_image_from_slug( $slug ) {
        global $woocommerce;

        //echo"$attribute xxx";print_r($terms);echo"xxx";
        $term = get_term_by( 'slug', $slug, esc_attr( str_replace( 'attribute_', '', "pa_color" ) ) );
        if ( ! is_wp_error( $term ) ) {
            $image = get_term_meta($term->term_id, 'image', true);
            $image = $image ? wp_get_attachment_image_src($image) : '';
        }
        $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
        return $image;
    }
}
