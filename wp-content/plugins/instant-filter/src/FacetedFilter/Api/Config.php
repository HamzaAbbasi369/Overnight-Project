<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\FacetedFilter\Api;

class Config
{
    const PRODUCT_CODE = 'search';

    public function __construct(
        \OngStore\Core\Api\Config $config
    ) {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getApiUrl()
    {
        return $this->config->getBaseApiUrl();
    }

    /**
     * @return string
     */
    public function getAUid()
    {
        return $this->config->getAUid(self::PRODUCT_CODE);
    }
}