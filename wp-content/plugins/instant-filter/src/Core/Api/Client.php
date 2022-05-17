<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Api;

use MongoCursor;
use MongoDB;

class Client
{
    const METHOD_GET = 'find';
    const METHOD_POST = 'insertOne';
    const METHOD_PUT = 'findOneAndReplace';
    const METHOD_DELETE = 'findOneAndDelete';
    const METHOD_AGGREGATE = 'aggregate';

    public function __construct(
        Config $config
    ) {
        $this->config = $config;
        $this->host   = $config->getBaseApiUrl();
    }

    /**
     * @param string $method
     * @param string $action
     * @param array  $params
     * @param array  $data
     *
     * @return mixed
     * @throws Exception
     */
    public function request($method, $action, $params = [], array $data = [])
    {
        $result = null;

        try {
            /** @var \MongoClient $client */
            $client = new MongoDB\Client($this->host);

            $collection = $client->{$this->getDatabase($action)}->{$this->getCollection($action)};

            if (self::METHOD_POST === $method) {
                $result = $collection->insertMany($data);
            } elseif (self::METHOD_DELETE === $method) {
                $result = $collection->deleteMany($params);
            } elseif (self::METHOD_PUT === $method) {
                foreach ($data as $record) {
                    if (array_key_exists('filter', $params)) {
                        $filter = $params['filter'];
                    } elseif (array_key_exists('filter_cb', $params)) {
                        $filter = call_user_func($params['filter_cb'], $record);
                    }
                    if (empty($filter)) {
                        throw new Exception("[400] Empty Filter", 400);
                    }
                    $result = $collection->findOneAndReplace($filter, $record, ['upsert'=>true]);
                }
            } elseif (self::METHOD_GET === $method) {
                $result = $collection->find($params, $data);
            } elseif (self::METHOD_AGGREGATE === $method) {
                $result = $collection->aggregate($params, $data);
            } else {
                $result = $collection->$method($params, $data);
            }
        } catch (\Throwable $e) {
            throw new Exception("[400] " . $e->getMessage(), 400);
        }

        return $result;
    }

    private function getDatabase(string $action): string
    {
        return preg_replace('~^\/([^\/]+)\/(.+)$~', '$1', $action);
    }

    private function getCollection(string $action): string
    {
        return preg_replace('~^\/([^\/]+)+\/(.+)$~', '$2', $action);
    }
}
