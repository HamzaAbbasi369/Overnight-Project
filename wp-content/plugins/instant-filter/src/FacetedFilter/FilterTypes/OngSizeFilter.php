<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2016 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

namespace OngStore\FacetedFilter\FilterTypes;

use Iterator;
use OngStore\Core\Api\Client;
use OngStore\Core\Helper\Config;
use OngStore\Core\Helper\Template;
use OngStore\Core\Model\Extractor\Products;
use OngStore\FacetedFilter\Api\Extractor;
use OngStore\FacetedFilter\Helper\BaseShortcode;
use OngStore\FacetedFilter\Helper\StringHelper;
use OngStore\FacetedFilter\OngFilter;

/**
 * Class ong_color_filter
 *
 * @property Extractor $extractor
 * @property           $client
 */
class OngSizeFilter extends BaseShortcode
{

    public static $group = 'size';

    public static $listOfMembers = [
        'pa_lens-width' => 'Lens Width',
        'pa_bridge'    => 'Bridge Width',
        'pa_temple'   => 'Temple Length',
        'pa_frame-width'   => 'Frame Width',
    ];

    /**
     * @param array $atts
     *
     * @return string
     */
    public function run($atts, $initial_filter)
    {
        $shortcode_id = $atts['id'];

        ob_start();

        if (array_key_exists(self::$group, $atts)) {
            $group_items = $atts[ self::$group ];

            $shortcode = $this;

            if (!empty($group_items)) {
                $templateName = apply_filters(
                    'ong_attribute_template_path',
                    ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/".self::$group."Form.php"
                );

                if (file_exists($templateName)) {
                    include $templateName;
                } else {
                    echo 'Template "' . $templateName . '" Not Found';
                }
            }
        }

        $return = ob_get_contents();
        ob_end_clean();

        return $return;
    }


    public function getTaxonomyTitle()
    {
        return self::$listOfMembers[ $this->getName() ];
    }

    public function __construct($client, Extractor $extractor)
    {

        wp_enqueue_script("jquery-ui-slider", array('jquery-ui-core'));
//        $this->setName($name);
        $entityExtractor = new Products;
        $extractor->setEntityExtractor($entityExtractor);
        parent::__construct($client, $extractor);
    }

    public function fillQuerySection(array &$pipeline, $values)
    {

        $values = (array)$values;

        if (count($values) === 2) {
            array_push($pipeline, [
                '$match' => [
                    'taxonomy.'.$this->getName().'.cast' => [
                        '$gte' => (int)$values[0],
                        '$lte' => (int)$values[1]
                    ]
                ]
            ]);
        }
    }

    public function fillFacetSection(array &$pipeline)
    {
//        if (!isset($pipeline)) {
//            $pipeline = $this->extractor->pipeline;
//        }
//
//        $facet = [];
//        array_push($facet, ['$unwind' => '$taxonomy']);
//        array_push($facet, ['$match' => ['taxonomy.taxonomy' => $this->getName()]]);
//        array_push($facet, [
//            '$bucketAuto' => [
//                "groupBy"   => '$taxonomy.name',
////                "groupBy" => '$_id',
//                "buckets"   => 1,
//                "output" => [
//                    "count" => ['$sum' => 1]
//                ]
//            ]
//        ]);
//        array_push($facet, ['$sort' => ['_id' => 1]]);
//
//        $faceted = false;
//        foreach ($pipeline as &$line) {
//            if (array_key_exists('$facet', $line)) {
//                $line['$facet'][ self::$group . OngFilter::$group_separator . $this->getName() ] = $facet;
//                $faceted                                                                         = true;
//                break;
//            }
//        }
//
//        if (!$faceted) {
//            array_push($pipeline, [
//                '$facet' => [
//                    self::$group . OngFilter::$group_separator . $this->getName() => $facet
//                ]
//            ]);
//        }
    }

    public function fillSortSection(&$sort)
    {
//        $this->extractor->setSort('_id');
    }

    /**
     * @param $params
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function getFiltered($params)
    {
        $ops = [
            [
                '$unwind' => '$taxonomy'
            ],
            [
                '$project' => [
                    'taxonomy_' . $this->getName() => [
                        '$cond' => [
                            ['$eq' => ['$taxonomy.taxonomy', $this->getName()]],
                            '$taxonomy.name',
                            null
                        ]
                    ]
                ]
            ],
            [
                '$group' => [
                    "_id"   => '$taxonomy_' . $this->getName(),
                    "count" => ['$sum' => 1],
                ]
            ],
            [
                '$sort' => [
                    '_id' => 1,
//                    'count' => -1,
                ]
            ],
        ];

        return $this->client->request(Client::METHOD_AGGREGATE, $this->extractor->baseUrl, $ops, ['useCursor' => false]);//
    }
}
