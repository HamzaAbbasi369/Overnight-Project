<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\Core\Api;

class ApiFactory
{
    /**
     * @param Interfaces\ConfigInterface $config
     */
    public function __construct(
        Interfaces\ConfigInterface $config
    ) {
        $this->resourceConfig = $config;
        $this->config         = new Config($this->resourceConfig);
        $this->client         = new Client($this->config);

        $this->indexer = new Indexer(
            $this->client
        );
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return Indexer
     */
    public function getIndexer()
    {
        return $this->indexer;
    }
}
