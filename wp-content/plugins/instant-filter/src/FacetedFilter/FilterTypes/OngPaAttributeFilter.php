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
class OngPaAttributeFilter extends BaseShortcode
{

    public static $group = 'pa_attribute';

    public static $listOfMembers = [
//        'pa_color' => 'Color',
//        'pa_brands' => 'Brand',
        'pa_shape'       => 'Shape',
//        'pa_size' => 'Frame Size',
//        'pa_gender' => 'Gender',
        'pa_frame-style' => 'Style',
        'pa_material'    => 'Material',
        'pa_frame-attribute'    => 'Frame Attribute',
//		 'pa_clip-on' => 'Clip on'
//        'pa_fit_category' => 'Fit Category',
    ];

    public function __construct($client, Extractor $extractor)
    {
//        $this->setName($name);
        $entityExtractor = new Products;
        $extractor->setEntityExtractor($entityExtractor);
        parent::__construct($client, $extractor);
    }

    /**
     * ong_color_filter
     *
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
                            ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/" . self::$group . "Form.php"
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

        return $return;
    }

    public function fillQuerySection(array &$pipeline, $values)
    {
//        array_push($pipeline, ['$unwind' => '$attributes.'.$this->getName()]);
        array_push($pipeline, [
            '$match' => [
                'attributes.' . $this->getName() => ['$in' => (array) $values]
            ]
        ]);
    }

    public function fillSortSection(&$sort)
    {
        $this->extractor->setSort('_id');
    }

    public function fillFacetSection(array &$pipeline)
    {
        if (!isset($pipeline)) {
            $pipeline = $this->extractor->pipeline;
        }

        $facet = [
            ['$unwind' => '$attributes.' . $this->getName()],
            [
                '$group' => [
                    "_id"   => '$attributes.' . $this->getName(),
                    "count" => ['$sum' => 1],
                ]
            ],
            ['$sort' => ['_id' => 1]]
        ];

        $faceted = false;
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
                '$unwind' => '$attributes.' . $this->getName()
            ],
            [
                '$group' => [
                    "_id"   => '$attributes.' . $this->getName(),
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
