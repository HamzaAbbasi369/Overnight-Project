<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Controller\Admin;

use OngStore\Core\Helper\Config;

class Application
{
    public function __construct(
        Config $config
//,
//        \OngStore\Core\Api\Iframe $iframe
    )
    {
        $this->config = $config;
//        $this->iframe = $iframe;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if (!$product_code = $this->config->prepareProductCode(sanitize_text_field($_REQUEST['page']))) {
            throw new \Exception(__("Product is not set", Config::LANG_DOMAIN));
        }
        try {
//            echo $this->iframe->setProductCode($product_code)->setPlatform(Config::PLATFORM)->toHtml();
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
            echo $error_message;
        }
    }
}