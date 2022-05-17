<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\Core\Api;

class Config
{
    const ENTITY_PRODUCT = "product";
    const ENTITY_ATTRIBUTE = "attribute";
    const ENTITY_CATEGORY = "category";
    const ENTITY_BLOG_CATEGORY = "blog_category";
    const ENTITY_PAGE = "page";
    const ENTITY_STORE = "store";
    const ENTITY_POST = "post";


    public function __construct(
        \OngStore\Core\Api\Interfaces\ConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getExtID()
    {
        return $this->config->getValue('ong_api/access/ext_id');
    }

    /**
     * @param string $value
     *
     * @return void
     */
    public function setExtID($value)
    {
        $this->config->saveValue('ong_api/access/ext_id', $value);
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->config->getEncryptedValue('ong_api/access/secret_key');
    }


    /**
     * @param string $value
     *
     * @return void
     */
    public function setSecretKey($value)
    {
        $this->config->saveValueEncrypted('ong_api/access/secret_key', $value);

    }

    /**
     * @return string
     */
    public function getBaseApiUrl()
    {
        return rtrim($this->config->getValue('ong_api/access/base_url'), "/");
    }

    /**
     * @param string $productCode
     *
     * @return string
     */
    public function getApplicationApiUrl($productCode)
    {
        return rtrim($this->config->getValue('ong_api/access/' . $productCode . '_url'), "/");
    }

    /**
     * @param string $productCode
     *
     * @return string
     */
    public function getBackendTitle($productCode)
    {
//        try {
//            $productCode = stripslashes(ucfirst($productCode));
//            $config = $this->objectManager->create('OngStore\\'.$productCode.'\Model\Config');
//            return $config->getBackendTitle();
//        } catch (\Exception $e) {}
        return __("ONG Store", Config::LANG_DOMAIN);
    }


    /**
     * @param string $productCode
     *
     * @return string
     */
    public function getAUid($productCode)
    {
        return $this->config->getValue('ong_api/access/' . $productCode . '_auid');
    }

    /**
     * @param string $productCode
     * @param string $value
     *
     * @return void
     */
    public function setAUid($productCode, $value)
    {
        $this->config->saveValue('ong_api/access/' . $productCode . '_auid', $value);
    }

    /**
     * @return string
     */
    public function getIUid()
    {
        return $this->config->getValue('ong_api/access/iuid');
    }

    /**
     * @param string $value
     *
     * @return void
     */
    public function setIUid($value)
    {
        $this->config->saveValue('ong_api/access/iuid', $value);
    }


    /**
     * @return void
     */
    public function disconnect()
    {
        $this->setIUid("");
        $this->setSecretKey("");
    }
}
