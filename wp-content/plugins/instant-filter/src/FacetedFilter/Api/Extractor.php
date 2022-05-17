<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\FacetedFilter\Api;

use OngStore\Core\Api\Client;
use OngStore\Core\Model\Extractor\AbstractExtractor;
use OngStore\FacetedFilter\Helper\BaseShortcode;
use OngStore\FacetedFilter\Helper\ExtractorResponse;
use OngStore\FacetedFilter\Interfaces\ShortcodeInterface;
use OngStore\FacetedFilter\OngFilter;
use OngStore\FacetedFilter\FilterTypes\OngPaAttributeFilter;
use OngStore\FacetedFilter\FilterTypes\OngPaTaxonomyFilter;

class Extractor
{

    public $baseUrl;

    protected $currentNumber;
    protected $totalSize;
    protected $storeId;
    protected $batchSize = 30;
    protected $batch = [];
    protected $filter_template = null;
    protected $entityExtractor;

    public $pipeline = [];

    public static $limit = 18;

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
     *
     * @return void
     * @internal param int $totalSize
     * @internal param int $storeId -  used to display reindexing progress
     *
     */
    public function startBatch($platform, $entity)
    {
        $this->baseUrl = $this->getBaseUrl($platform, $entity);
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
            if ($this->getEntityExtractor()) {
                $params['filter_cb'] = $this->getEntityExtractor()->getFilter();
            }
        }
        $this->client->request($method, $this->baseUrl, $params, $data);
        $this->batch = [];
    }



    /**
     * @param ShortcodeInterface|OngPaAttributeFilter|OngPaTaxonomyFilter $shortcodeClass
     * @param $values
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function addQuery(ShortcodeInterface $shortcodeClass, $values)
    {
        $shortcodeClass->fillQuerySection($this->pipeline, $values);
    }

    /**
     * @param ShortcodeInterface $shortcodeClass
     *
     * @param               $direction
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function setSortByShortcode(ShortcodeInterface $shortcodeClass, $direction = 1)
    {
        $shortcodeClass->fillSortSection($this->pipeline);
    }

    /**
     * @param ShortcodeInterface    $shortcodeClass
     * @param array|null $pipeline
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function setFacetByShortcode($shortcodeClass, array &$pipeline = null)
    {
        $shortcodeClass->fillFacetSection($this->pipeline);
    }

    public function setSort($sort, $direction = 1, array &$pipeline = null)
    {
        if (!isset($pipeline)) {
            $pipeline = &$this->pipeline;
        }
        if (is_array($sort)) {
	        $value = $sort;
        }else {
	        $value = [$sort => $direction];
        }
        array_push($pipeline, ['$sort' => $value]);
    }

    public function setLimitSkip($limit = null, $page = null, array &$pipeline = null)
    {
        if (!isset($pipeline)) {
            $pipeline = $this->pipeline;
        }

//        if (!isset($limit) && !empty($_REQUEST['per_page'])) {
//            $limit = (int) $_REQUEST['per_page'];
//        } else {
            $limit = self::$limit;
//        }

        if (!isset($page) && !empty($_REQUEST[OngFilter::$page_param])) {
            $page = (int) $_REQUEST[OngFilter::$page_param];
        } else {
            $page = 1;
        }

        array_push($pipeline, ['$skip' => ($page-1)*$limit]);
        array_push($pipeline, ['$limit' => $limit]);
    }

    /**
     * Applies search conditions and returns Product Cards (ordered and limited)
     *
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function getResults(array &$pipeline = null, $useCursor = false)
    {

        if (!isset($pipeline)) {
            $pipeline = $this->pipeline;
	}
	$use = true;
        /** @var ExtractorResponse $criteria */
	array_push($pipeline, ['$limit' => 600]);
        $criteria = new ExtractorResponse($pipeline, Client::METHOD_AGGREGATE);
	$criteria = apply_filters('extractor_get_results', $criteria);
	#$criteria->params[0]['$match']['total_stock'] = ['$gt' => 0];
	#print_r($criteria);
	#exit;
	//print_r($res);       
	return $this->client->request($criteria->type, $this->baseUrl, $criteria->params, ['useCursor'=> $useCursor]);
    }

    /**
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function getNewFilters()
    {
	//logical AND for all filters
        /** @var ExtractorResponse $criteria */
        $criteria = new ExtractorResponse($this->pipeline, Client::METHOD_AGGREGATE);
	$criteria = apply_filters('extractor_compose_search_criteria', $criteria);
	$use = true;

        return $this->client->request($criteria->type, $this->baseUrl, $criteria->params, ['useCursor'=> false]);
    }

    public function setSkip($null, $pipeline)
    {
        if (!isset($pipeline)) {
            $pipeline = $this->pipeline;
        }
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
            ['storeID' => $storeId]
        );
    }

    /**
     * @return mixed
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function getEntityExtractor()
    {
        return $this->entityExtractor;
    }

    /**
     * @param AbstractExtractor $entityExtractor
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function setEntityExtractor(AbstractExtractor $entityExtractor)
    {
        $this->entityExtractor = $entityExtractor;
    }
}
