<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\Core\Api;

use OngStore\Core\Model\Indexer\AbstractIndexer;

class Indexer
{

    protected $baseUrl;
    protected $currentNumber;
    protected $totalSize;
    protected $storeId;
    protected $batchSize = 30;
    protected $batch = [];
    protected $entityIndexer;
    protected $filter_template = null;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param string $platform
     * @param string $entity
     * @param array  $data
     * @param int    $storeId
     *
     * @return void
     */
    public function saveEntity($platform, $entity, array $data, $storeId)
    {
        $this->startBatch($platform, $entity, 1);
        $this->saveBatch($data, $storeId);
        $this->finishBatch();
    }

    /**
     * @param string $platform
     * @param string $entity
     * @param int    $totalSize
     * @param int    $storeId -  used to display reindexing progress
     *
     * @return void
     */
    public function startBatch($platform, $entity, $totalSize, $storeId = 0)
    {
        $this->baseUrl         = $this->getBaseUrl($platform, $entity);
        $this->currentNumber   = 0;
        $this->totalSize       = $totalSize;
        $this->storeId         = (int) $storeId;
    }

    /**
     * @param string $platform
     * @param string $entity
     *
     * @return string
     */
    protected function getBaseUrl($platform, $entity): string
    {
        return '/' . $platform . '/' . $entity;
    }

    /**
     * @param array  $data
     * @param int    $storeId
     * @param string $method
     *
     * @return void
     */
    public function saveBatch(array $data, $storeId, $method = Client::METHOD_POST)
    {
//        $data             = $this->prepareEntityData($data);
        $data['store_id'] = (string) $storeId;//we must have string in json
        $this->batch[]    = $data;
        $this->currentNumber ++;
        if (count($this->batch) >= $this->batchSize) {
            $this->finishBatch($method);
        }
    }

    /**
     * @param string $method
     *
     * @return void
     */
    public function finishBatch($method = Client::METHOD_POST)
    {
        $data   = $this->getData();
        $params = [];

        if ($method != Client::METHOD_POST) {
            if ($this->getEntityIndexer()) {
                $params['filter_cb'] = $this->getEntityIndexer()->getFilter();
            };
        }
        $this->client->request($method, $this->baseUrl, $params, $data);
        $this->batch = [];
    }

    /**
     * @param array $data
     *
     * @return array
     */
    protected function prepareEntityData(array $data)
    {
        $r = [];
        foreach ($data as $k => $v) {
            if (!is_object($v) && !is_array($v)) {
                $r[ $k ] = (string) $v;
            }
        }
        if (isset($data['options'])) {
            $r['options'] = [];

            foreach ($data['options'] as $option) {
                $r['options'][] = $this->prepareEntityData($option);
            }
        }
        if (isset($data['attributes'])) {
            $r['attributes'] = $data['attributes'];
        }
        if (isset($data['blocks'])) {
            $r['blocks'] = $data['blocks'];
        }

        return $r;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->batch;
    }

    /**
     * Clear search index data for store
     *
     * @param string $platform
     * @param string $entity
     * @param int    $storeId
     *
     * @return void
     */
    public function clearIndex($platform, $entity, $storeId)
    {
        $this->client->request(
            Client::METHOD_DELETE,
            $this->getBaseUrl($platform, $entity),
            [
//                'store_id' => $storeId
            ]
        );
    }

    /**
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function getEntityIndexer()
    {
        return $this->entityIndexer;
    }

    /**
     * @param AbstractIndexer $entityIndexer
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function setEntityIndexer(AbstractIndexer $entityIndexer)
    {
        $this->entityIndexer = $entityIndexer;
    }
}