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
class OngPaTaxonomyFilter extends BaseShortcode
{

    public static $group = 'pa_taxonomy';

    public static $listOfMembers = [
        'pa_product-line' => 'Collection',
        'product_cat' => 'Gender',
        'pa_color'    => 'Color',
        'pa_brands'   => 'Brand',
//        'size_range'  => 'Lens Width',
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
            foreach ((array) $atts[ self::$group ] as $key => $group_member) {
                $this->setName($group_member);
                if (self::checkAttsForFilters($atts, self::$group, $group_member)) {
                    continue;
                }
                try {
                    if ($this::isValid($group_member)) {
                        $shortcode_name  = $this->getName();
                        $shortcode_code  = $this->getSlagName();
                        $shortcode_title = $this->getAttributeTitle();

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
                } catch (\Throwable $e) {
                    var_dump($e->getMessage());
                }
            }
        }

        $return = ob_get_contents();
        ob_end_clean();
    //echo"OngPaTaxonomyFilter.php Rum::><pre>";print_r($return);echo"</pre>";
        return $return;
    }


    public function getTaxonomyTitle()
    {
        return self::$listOfMembers[ $this->getName() ];
    }

    public function __construct($client, Extractor $extractor)
    {
//        $this->setName($name);
        $entityExtractor = new Products;
        $extractor->setEntityExtractor($entityExtractor);
        parent::__construct($client, $extractor);
    }

    public function fillQuerySection(array &$pipeline, $values)
    {
        array_push($pipeline, [
            '$match' => [
                'taxonomy.'.$this->getName().'.slug' => ['$in' => (array) $values]
            ]
        ]);
    }

    public function fillFacetSection(array &$pipeline)
    {
//echo"OngPaTaxonomyFilter.php fillFacetSection::><pre>";print_r($pipeline);echo"</pre>";
        if (!isset($pipeline)) {
            $pipeline = $this->extractor->pipeline;
        }

        $facet = [];
        array_push($facet, ['$unwind' => '$taxonomy.'.$this->getName()]);
        array_push($facet, ['$project' => [
            'taxonomy.'.$this->getName().'.name' => 1,
            'taxonomy.'.$this->getName().'.slug' => 1,
        ]]);
        array_push($facet, [
            '$group' => [
                "_id"   => '$taxonomy.'.$this->getName(),
                "count" => ['$sum' => 1],
                "image" => ['$sum' => 1],
            ]
        ]);
        array_push($facet, ['$sort' => ['_id.name' => 1]]);

        $faceted = false;
//echo"OngPaTaxonomuFilter.php 1<pre>";print_r($pipeline['pa_taxonomy--pa_color']);echo"</pre>";
//echo"OngPaTaxonomuFilter.php 2<pre>";print_r($pipeline);echo"</pre>";

        foreach ($pipeline as &$line) {
            if (array_key_exists('$facet', $line)) {
                $line['$facet'][ self::$group . OngFilter::$group_separator . $this->getName() ] = $facet;
                $faceted                                                                         = true;
                break;
            }
        }

        if (!$faceted) {
            array_push($pipeline, [
                '$facet' => [
                    self::$group . OngFilter::$group_separator . $this->getName() => $facet
                ]
            ]);
        }
        //echo"OngPaTaxonomuFilter.php 3<pre>";print_r($pipeline);echo"</pre>";
    }

    public function fillSortSection(&$sort)
    {
        $this->extractor->setSort('_id');
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
                '$group' => [
                    "_id"   => '$taxonomy.' . $this->getName(),
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
//echo"OngPaTaxonomyFilter.php getFiltered::><pre>";print_r($this->client->request(Client::METHOD_AGGREGATE, $this->extractor->baseUrl, $ops, ['useCursor' => false]));echo"</pre>";
        return $this->client->request(Client::METHOD_AGGREGATE, $this->extractor->baseUrl, $ops, ['useCursor' => false]);//
    }
}
