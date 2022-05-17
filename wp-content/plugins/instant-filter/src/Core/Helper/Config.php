<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Helper;

class Config implements \OngStore\Core\Api\Interfaces\ConfigInterface
{

    const PLATFORM = 'woocommerce';

    const LANG_DOMAIN = 'ongsearch';

    const IS_SYNCED = 'ong_api/is_synced';

    /**
     * @return array
     */
    public function get_sync_actions()
    {
        return [
            'woocommerce_api_create_product' => 'sync_product',
            'woocommerce_api_edit_product'   => 'sync_product',
            'woocommerce_reduce_order_stock' => 'stock_reduced',
            'save_post'                      => 'sync_item',
            'created_category'               => 'sync_category',
            'edited_category'                => 'sync_category',
            'delete_category'                => 'syncRemoveCategory',
            'created_product_cat'            => 'sync_wc_category',
            'edited_product_cat'             => 'sync_wc_category',
            'delete_product_cat'             => 'syncRemoveCategory',
            'wp_trash_post'                  => 'prepareRemoveItem',
            'before_delete_post'             => 'prepareRemoveItem',
            'trashed_post'                   => 'syncRemoveItem',
            'after_delete_post'              => 'syncRemoveItem',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getValue($path)
    {
        return get_option($this->convertToWpStyle($path), '');
    }

    /**
     * {@inheritdoc}
     */
    public function saveValue($path, $value)
    {
        update_option($this->convertToWpStyle($path), $value);
    }

    /**
     * {@inheritdoc}
     */
    public function getEncryptedValue($path)
    {
        return get_option($this->convertToWpStyle($path), '');
    }

    /**
     * {@inheritdoc}
     */
    public function saveValueEncrypted($path, $value)
    {
        update_option($this->convertToWpStyle($path), $value);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function convertToWpStyle($path)
    {
        return str_replace('/', '_', $path);
    }

    /**
     * @param string $code
     *
     * @return string
     */
    public function prepareProductCode($code)
    {
        return ('ong_store' == $code) ? 'search' : str_replace('ong_store_', '', $code);
    }

    /**
     * @return bool
     */
    public function isWooCommerceActive()
    {
        return /*is_plugin_active('woocommerce/woocommerce.php') &&*/ class_exists('\WC_Product');
    }
}