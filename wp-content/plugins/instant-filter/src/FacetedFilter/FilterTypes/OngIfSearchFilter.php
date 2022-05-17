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
 * Class Search Filter
 *
 * @property Extractor $extractor
 * @property           $client
 */
class OngIfSearchFilter extends BaseShortcode
{

    public static $group = 'if_search';

    public static $listOfMembers = [
        'search'       => 'Search',
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
        ob_start();
        if (array_key_exists(self::$group, $atts)) {
            $group_items = $atts[ self::$group ];

            $shortcode = $this;

            if (!empty($group_items)) {
                $group_member = reset($group_items);
                $this->setName($group_member);
                $shortcode_id = $atts['id'];
                $shortcode_code  = $this->getSlagName();
                $shortcode_title = $this->getAttributeTitle();
                $shortcode_name  = $this->getName();

                $current_value = $this->getInitialValue($initial_filter);

                $action_url = $atts['current_url'];
                $x_params = base64_encode(serialize($atts));



                $templateName = apply_filters(
                    'ong_attribute_template_path',
                    ONG_INSTANT_FILTER_PLUGIN_PATH . "src/FacetedFilter/view/templates/searchForm.php"
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

    public function fillQuerySection(array &$pipeline, $values)
    {
	    $search_value = implode('', $values);
	    if (empty($search_value)) {
	        return;
        }
	    if (strpos('"', $search_value) === false) {
		   // $search_value = "\"$search_value\"";
	    }
        array_push($pipeline, [
            '$match' => [
                '$text' => [
                    '$'.$this->getName() => $search_value
                ]
            ]
        ]);
	    $projected = false;
	    foreach ($pipeline as &$line) {
		    if (array_key_exists('$project', $line)) {
			    $line['$project']['score'] = ['$meta' => 'textScore'];
			    $projected                              = true;
			    break;
		    }
	    }

	    if (!$projected) {
		    array_push($pipeline, [
			    '$project' => [
				    'score' => ['$meta' => 'textScore'],
			    ]
		    ]);
	    }
    }

    public function fillSortSection(&$sort)
    {
        $this->extractor->setSort(['score' => ['$meta' => 'textScore']]);
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
