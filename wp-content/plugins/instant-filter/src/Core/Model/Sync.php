<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model;

use OngStore\Core\Api\Client;
use OngStore\Core\Helper\Config;
use OngStore\Core\Api\Config as ApiConfig;
use OngStore\Core\Model\Indexer\Products;

class Sync
{
    /**
     * @var array
     */
    private $types = [
        'post',
        'page',
        'product',
    ];

    /**
     * @var array
     */
    private $category_types = [
        'category',
        'product_cat',
    ];

    private $need_remove = [];

    /**
     * @var int
     */
    private $step = 1;

    /**
     * @var int
     */
    private $limit = 10;

    public function __construct(
        \OngStore\Core\Helper\Config $config,
        \OngStore\Core\Helper\Template $templateHelper,
        \OngStore\Core\Api\ApiFactory $apiFactory
    ) {
        $this->config         = $config;
        $this->apiFactory     = $apiFactory;
        $this->templateHelper = $templateHelper;

        foreach ($config->get_sync_actions() as $action => $method) {
            add_action($action, [&$this, $method], 10, 4);
        }
    }

    /**
     * @param int $step
     *
     * @return array
     */
    public function run($step = 1)
    {
        ob_start();//fix for WP 4.1
        $this->step = $step;

        $return = $this->init_return();

        $sites = $this->get_sites();
        $this->sync_blogs($return);
        foreach ($sites as $site) {
            $this->switch_to_blog($site['blog_id']);
            foreach ($this->types as $type) {
                $this->sync_posts($return, $type);
            }
            foreach ($this->category_types as $type) {
                $this->sync_categories($return, $type);
            }
        }

        $this->restore_current_blog();
        ob_clean();

        return $return;
    }

    /**
     * @return array
     */
    private function init_return()
    {
        return [
            'grand_total' => 0
        ];
    }

    /**
     * @return array
     */
    protected function get_sites()
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
     * @param array $return
     * @param int   $id
     *
     * @return void
     */
    public function sync_blogs(&$return, $id = 0)
    {
        $blog_label  = '';
        $blogIndexer     = new Indexer\Blogs();
        $data        = $blogIndexer->getData($id, $this->limit, $this->limit * ($this->step - 1));
        $totalNumber = count($this->get_sites());

        $return[ $blog_label ][0]['synced'] = 0;
        $return[ $blog_label ][0]['name']   = __('Synchronization progress', Config::LANG_DOMAIN);
        $return[ $blog_label ][0]['total']  = $totalNumber;
        if (count($data)) {
            $return['grand_total'] += count($data);
            $return[ $blog_label ][0]['synced'] += $this->limit * ($this->step - 1) + count($data);
            $indexer = $this->apiFactory->getIndexer();
            $indexer->setEntityIndexer($blogIndexer);
            $indexer->startBatch(Config::PLATFORM, ApiConfig::ENTITY_STORE, $totalNumber);
            foreach ($data as $blog) {
                $indexer->saveBatch($blog, 0, Client::METHOD_PUT);
            }
            $indexer->finishBatch(Client::METHOD_PUT);
        }
    }

    /**
     * @param $blog_id
     */
    protected function switch_to_blog($blog_id)
    {
        if (function_exists('switch_to_blog')) {
            switch_to_blog($blog_id);
        }
    }

    /**
     * @param array  $return
     * @param string $type
     * @param int    $id
     *
     * @return void
     */
    public function sync_posts(&$return, $type = 'post', $id = 0)
    {
        global $post, $wp_query;

        if (!$this->config->isWooCommerceActive() && $type == 'product') {
            return;
        }

        $post_label = $type . 's';
        $blogId     = get_current_blog_id();
        $index_name = '\\OngStore\\Core\\Model\\Indexer\\' . ucfirst($type) . 's';
        $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_' . strtoupper($type);

        /** @var \OngStore\Core\Model\Indexer\AbstractPostIndexer $post_indexer */
        $post_indexer = new $index_name;
        $indexer      = $this->apiFactory->getIndexer();
        $indexer->setEntityIndexer($post_indexer);

        if ($this->step === 1 && empty($id)) {
            $indexer->clearIndex(Config::PLATFORM, constant($const_name), $blogId);
        }

        $totals                                     = $this->count_posts($id, $type);
        $return[ $post_label ][ $blogId ]['synced'] = 0;
        $return[ $post_label ][ $blogId ]['name']   = get_bloginfo('name');
        $return[ $post_label ][ $blogId ]['total']  = $totals;

        $attributes = [];
        $posts      = $this->get_posts($id, $type);
        if (count($posts)) {
            $return['grand_total'] += count($posts);
            $return[ $post_label ][ $blogId ]['synced'] += $this->limit * ($this->step - 1) + count($posts);
            $indexer->startBatch(Config::PLATFORM, constant($const_name), count($posts));
            foreach ($posts as $post) {
                the_post();
                /** @var Products $post_indexer */
		$data = $post_indexer->prepareToSync($post);
		// REMOVE
		//print_r($data);
                if (isset($data['attributes_data'])) {
                    $attributes = array_merge($attributes, $data['attributes_data']);
                    unset($data['attributes_data']);
                }
                if ($type == 'product') {
                    $data['blocks']['productcard'] = $this->templateHelper->render_product_block();
                }
                $indexer->saveBatch($data, $data['blog_id'], Client::METHOD_PUT);
            }
            $indexer->finishBatch(Client::METHOD_PUT);
        }
        if ($attributes) {
            $indexer->startBatch(Config::PLATFORM, \OngStore\Core\Api\Config::ENTITY_ATTRIBUTE, count($attributes));
            foreach ($attributes as $attribute) {
                $indexer->saveBatch($attribute, $attribute['blog_id'], Client::METHOD_PUT);
            }
            $indexer->finishBatch(Client::METHOD_PUT);
        }
    }

    /**
     * Count posts of type $type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return array
     */
    public function count_posts($id = 0, $type = 'post')
    {
        global $wp_query;

        $this->build_query($id, $type);

        return $wp_query->found_posts;
    }

    /**
     * Query for select posts by type
     *
     * @param int    $id
     * @param string $type
     */
    private function build_query($id = 0, $type = 'post')
    {
        global $wp_query;

        $args = [
            'post_status'    => 'publish', // select all and make hidden if not published
            'post_type'      => $type,
            'posts_per_page' => $this->limit,
            'paged'          => $this->step,
        ];
//        if ($type == 'product') {
//            $args['meta_query'] = [
//                [
//                    'key'     => '_stock_status',
//                    'value'   => 'instock',
//                    'compare' => '='
//                ]
//            ];
//        }
        if ($id) {
            $args['post__in'] = [$id];
        }

        $wp_query            = new \WP_Query($args);
        $wp_query->is_search = true;
    }

    /**
     * Select posts of type $type.
     *
     * @param int    $id
     * @param string $type
     *
     * @return array
     */
    public function get_posts($id = 0, $type = 'post')
    {
        global $wp_query;

        $this->build_query($id, $type);

        return $wp_query->get_posts();
    }

    /**
     * @param array  $return
     * @param string $type
     * @param int    $id
     *
     * @return void
     */
    public function sync_categories(&$return, $type = 'categories', $id = 0)
    {
        $blogId = get_current_blog_id();
        if ($type != 'product_cat') {
            $post_label = 'blog categories';
            $index_name = '\\OngStore\\Core\\Model\\Indexer\\Categories';
            $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_BLOG_CATEGORY';
        } else {
            if (!$this->config->isWooCommerceActive() && $type == 'product_cat') {
                return;
            }
            $post_label = 'WC categories';
            $index_name = '\\OngStore\\Core\\Model\\Indexer\\WCCategories';
            $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_CATEGORY';
        }

        /** @var \OngStore\Core\Model\Indexer\Categories $category_indexer */
        $category_indexer = new $index_name;
        $indexer          = $this->apiFactory->getIndexer();
        $indexer->setEntityIndexer($category_indexer);

        $return[ $post_label ][ $blogId ]['synced'] = 0;
        $return[ $post_label ][ $blogId ]['name']   = get_bloginfo('name');
        $return[ $post_label ][ $blogId ]['total']  = $category_indexer->countCategories();

        $categories = $this->get_categories($id, $type);
        if (count($categories)) {
            $return['grand_total'] += count($categories);
            $return[ $post_label ][ $blogId ]['synced'] += $this->limit * ($this->step - 1) + count($categories);
            $indexer->startBatch(Config::PLATFORM, constant($const_name), count($categories));
            foreach ($categories as $category) {
                $data           = $category_indexer->prepareToSync($category);
                $data['active'] = true;
                $indexer->saveBatch($data, $data['blog_id'], Client::METHOD_PUT);
            }
            $indexer->finishBatch(Client::METHOD_PUT);
        }
    }

    /**
     * Select categories.
     *
     * @param int    $id
     * @param string $type
     *
     * @return array
     */
    public function get_categories($id = 0, $type = 'category')
    {
        $number = $this->limit;
        $offset = $this->limit * ($this->step - 1);
        $args   = [
            'taxonomy'   => $type,
            'hide_empty' => false,
            'number'     => $number,
            'offset'     => $offset,
        ];
        if (version_compare(get_bloginfo('version'), '4.6') < 0) {
            unset($args['offset'], $args['number']);
        }

        if ($id) {
            $args['include'] = $id;
        }

        $categories = get_categories($args);
        if (version_compare(get_bloginfo('version'), '4.6') < 0) {
            $categories = array_slice($categories, $offset, $number);
        }

        return $categories;
    }

    /**
     *
     */
    protected function restore_current_blog()
    {
        if (function_exists('restore_current_blog')) {
            restore_current_blog();
        }
    }

    /**
     * @param int $id
     */
    public function sync_item($id)
    {
        $return = $this->init_return();
        $post   = get_post($id);
        if (in_array($post->post_type, $this->types)) {
            
            $this->sync_posts($return, $post->post_type, $id);
        }
    }

    /**
     * @param int $id
     */
    public function prepareRemoveItem($id)
    {
        $post = get_post($id);

        if (in_array($post->post_type, $this->types)) {
            $index_name               = '\\OngStore\\Core\\Model\\Indexer\\' . ucfirst($post->post_type) . 's';
            $post_indexer             = new $index_name;
            $indexer          = $this->apiFactory->getIndexer();
            $indexer->setEntityIndexer($post_indexer);

            $data                     = $post_indexer->prepareToSync($post);
            $this->need_remove[ $id ] = $data;
        }
    }

    /**
     * @param int $id
     */
    public function syncRemoveItem($id)
    {
        if (isset($this->need_remove[ $id ])) {
            $this->removePost($id, $this->need_remove[ $id ]['post_type']);
        }
    }

    /**
     * @param int    $id
     * @param string $type
     *
     * @return void
     */
    public function removePost($id, $type = 'post')
    {
        if (!$this->config->isWooCommerceActive() && $type == 'product') {
            return;
        }

        $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_' . strtoupper($type);
        $indexer    = $this->apiFactory->getIndexer();

        if (isset($this->need_remove[ $id ])) {
            $indexer->startBatch(Config::PLATFORM, constant($const_name), 1);
            $data = $this->need_remove[ $id ];
            if ($type == 'product') {
                $data['blocks']['productcard'] = $this->templateHelper->render_product_block();
            }
            $data['active']     = false;
            $data['visibility'] = false;
            $indexer->saveBatch($data, $data['blog_id'], Client::METHOD_DELETE);
            $indexer->finishBatch(Client::METHOD_DELETE);
        }
    }

    /**
     * Sync products.
     *
     * @param int $id
     */
    public function sync_product($id = 0)
    {
        if (!$this->config->isWooCommerceActive()) {
            return;
        }

        $return = $this->init_return();
        $this->sync_posts($return, 'product', $id);
    }

    /**
     * @param \WC_Order $order
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function stock_reduced( \WC_Order $order ) {
        $items = $order->get_items();
        foreach ($items as $key => $item) {
            $this->sync_posts($return, 'product', $item['product_id']);
        }
    }

    /**
     * @param int      $termId
     * @param int      $taxonomyTermId
     * @param \WP_Term $deletedTerm
     * @param array    $objectIds
     */
    public function syncRemoveCategory($termId, $taxonomyTermId, $deletedTerm, $objectIds)
    {
        $type = $deletedTerm->taxonomy;
        if ($type != 'product_cat') {
            $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_BLOG_CATEGORY';
        } else {
            if (!$this->config->isWooCommerceActive() && $type == 'product_cat') {
                return;
            }
            $const_name = '\\OngStore\\Core\\Api\\Config::ENTITY_CATEGORY';
        }

        $data                = (array) $deletedTerm;
        $category['blog_id'] = get_current_blog_id();
        $category['active']  = false;
        $indexer             = $this->apiFactory->getIndexer();
        $indexer->startBatch(Config::PLATFORM, constant($const_name), 1);
        $indexer->saveBatch($data, $data['blog_id'], Client::METHOD_DELETE);
        $indexer->finishBatch(Client::METHOD_DELETE);
    }

    /**
     * Sync categories.
     *
     * @param int $term_id Term ID.
     */
    public function sync_category($term_id = 0)
    {
        $return = $this->init_return();
        $this->sync_categories($return, 'category', $term_id);
    }

    /**
     * Sync WC categories.
     *
     * @param int $term_id Term ID.
     */
    public function sync_wc_category($term_id = 0)
    {
        if (!$this->config->isWooCommerceActive()) {
            return;
        }

        $return = $this->init_return();
        $this->sync_categories($return, 'product_cat', $term_id);
    }
}
