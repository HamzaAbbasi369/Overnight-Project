<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\FacetedFilter\Api;

use OngStore\Core\Api\Client;

class ApiFactory
{

    /**
     * ApiFactory constructor.
     *
     * @param \OngStore\Core\Api\ApiFactory $coreApi
     */
    public function __construct(
        \OngStore\Core\Api\ApiFactory $coreApi
    ) {
        $coreConfig         = $coreApi->getConfig();
        $this->client         = new Client($coreConfig);
        $config             = new Config(
            $coreConfig
        );
        $this->autocomplete = new Autocomplete(
            $config
        );
        $this->extractor = new Extractor(
            $this->client
        );
    }

    /**
     * @return Autocomplete
     */
    public function getAutocomplete()
    {
        return $this->autocomplete;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return Extractor
     */
    public function getExtractor()
    {
        return $this->extractor;
    }
}