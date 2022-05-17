<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Indexer;

class Blogs extends AbstractPostIndexer
{
    /**
     * {@inheritdoc}
     */
    public function getData($id = 0, $limit = 5, $offset = 0)
    {
        $data_for_sync = [];
        if (is_multisite()) {
            $data_for_sync = $this->get_ms_blog_data($id, $limit, $offset);
        } elseif (!$offset) {
            $data_for_sync = $this->get_blog_data();
        }

        return $data_for_sync;
    }

    /**
     * @param int $id
     *
     * @return array
     */
    private function get_ms_blog_data($id = 0, $limit = 5, $offset = 0)
    {
        $data_for_sync = [];

        if (function_exists('get_sites')) {
            $sites_data = get_sites(['number' => $limit, 'offset' => $offset]);
        } else {
            $sites_data = wp_get_sites(['limit' => $limit, 'offset' => $offset]);
        }
        $sites = [];
        foreach ($sites_data as $site) {
            if ($id) {
                if ($id == $site['blog_id']) {
                    $sites[] = $site;
                }
            } else {
                $sites[] = $site;
            }
        }

        if ($sites) {
            foreach ($sites as $site) {
                $blog_details                                   = get_blog_details($site['blog_id']);
                $data_for_sync[ $site['blog_id'] ]['blog_id']   = $site['blog_id'];
                $data_for_sync[ $site['blog_id'] ]['name']      = $blog_details->blogname;
                $data_for_sync[ $site['blog_id'] ]['is_active'] = $blog_details->public;
                $data_for_sync[ $site['blog_id'] ]['currency']  = get_blog_option($site['blog_id'], 'woocommerce_currency', '');
                switch_to_blog($site['blog_id']);
                $data_for_sync[ $site['blog_id'] ]['locale']          = get_site_option('WPLANG');
                $data_for_sync[ $site['blog_id'] ]['currency_format'] = $this->get_woocommerce_price_format(get_option('woocommerce_currency', ''));
                restore_current_blog();
            }
        }

        return $data_for_sync;
    }

    /**
     * @return array
     */
    private function get_blog_data()
    {
        $data_for_sync = [];

        $blog_id = get_current_blog_id();

        $blog_details                                 = $this->get_blog_details();
        $data_for_sync[ $blog_id ]['blog_id']         = $blog_id;
        $data_for_sync[ $blog_id ]['name']            = $blog_details->blogname;
        $data_for_sync[ $blog_id ]['is_active']       = $blog_details->public;
        $data_for_sync[ $blog_id ]['locale']          = get_site_option('WPLANG');
        $data_for_sync[ $blog_id ]['currency']        = get_option('woocommerce_currency', '');
        $data_for_sync[ $blog_id ]['currency_format'] = $this->get_woocommerce_price_format(get_option('woocommerce_currency', ''));

        return $data_for_sync;
    }

    /**
     * @return \stdClass
     */
    private function get_blog_details()
    {
        $details             = new \stdClass();
        $details->blogname   = get_option('blogname');
        $details->siteurl    = get_option('siteurl');
        $details->post_count = get_option('post_count');
        $details->home       = get_option('home');
        $details->public     = true;

        return $details;
    }

    /**
     * @param string $currency
     *
     * @return string
     */
    function get_woocommerce_price_format($currency)
    {
        if (!function_exists('get_woocommerce_currency_symbol')) {
            return '';
        }
        $currency_pos = get_option('woocommerce_currency_pos');
        $decimal_sep  = get_option('woocommerce_price_decimal_sep');
        $format       = '%1$s%2$s';

        switch ($currency_pos) {
            case 'left' :
                $format = '%1$s%2$s';
                break;
            case 'right' :
                $format = '%2$s%1$s';
                break;
            case 'left_space' :
                $format = '%1$s&nbsp;%2$s';
                break;
            case 'right_space' :
                $format = '%2$s&nbsp;%1$s';
                break;
        }

        $currency_pos    = apply_filters('woocommerce_price_format', $format, $currency_pos);
        $currency_symbol = get_woocommerce_currency_symbol($currency);

        return str_replace(['%1$s', '%2$s'], [$currency_symbol, '0' . $decimal_sep . '00'], $currency_pos);
    }

    public function getFilter()
    {
        return function ($record) {
            return ['blog_id' => (key_exists('blog_id', $record)) ? $record['blog_id'] : -1];
        };
    }
}
