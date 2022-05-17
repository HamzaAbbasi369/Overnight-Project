<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2016 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

namespace OngStore\FacetedFilter\Helper;

use OngStore\Core\Helper\Template;
use OngStore\FacetedFilter\Interfaces\ShortcodeInterface;
use OngStore\FacetedFilter\OngFilter;

/**
 * Class baseShortcode
 */
abstract class BaseShortcode implements ShortcodeInterface
{

    public static $group;
    public static $listOfMembers = [];
    protected $client;
    protected $extractor;
    public $name;

    public function __construct($client, $extractor)
    {
        $this->client       = $client;
        $this->extractor    = $extractor;
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';//;
        Template::enqueueScript(
            OngFilter::$slug.'-'.static::$group,
            sprintf("%ssrc/FacetedFilter/view/js/%s%s.js", ONG_INSTANT_FILTER_PLUGIN_URL, OngFilter::$slug.'-'.static::$group, $suffix),
            ['jquery'],
            ONG_INSTANT_FILTER_PLUGIN_VERSION,
            true
        );
    }

    public static function isValid($name)
    {
        $is = in_array($name, array_keys(static::$listOfMembers));

        return $is;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getAttributeTitle()
    {
        return static::$listOfMembers[$this->getName()];
    }

    public function getSlagName()
    {
        return StringHelper::underscore($this->name);
    }

    /**
     * @param $name
     *
     * @author Eugene Odokiienko <eugene@overnightglasses.com>
     */
    public function setName($name)
    {
        if (self::isValid($name)) {
            $this->name = $name;
        }
    }

    protected function getInitialValue($initial_filter)
    {
        $result = [];
        if (!empty($initial_filter) && is_array($initial_filter) && array_key_exists('filter', $initial_filter)) {
            if (array_key_exists(static::$group, $initial_filter['filter'])) {
                return $initial_filter['filter'][static::$group];
            }
        }
        return $result;
    }

    /**
     * @param $atts
     * @param $group
     * @param $member
     *
     * @return array
     * @author Eugene Odokiienko <eugene@overnightglasses.com>
     */
    public static function extractValues($atts, $group, $member)
    {
        $value = [];
        if (!empty($atts['filters'][ $group ][ $member ])) {
            $value = $atts['filters'][ $group ][ $member ];
        }
        return $value;
    }

    /**
     * @param $filter_blocks
     * @param $group
     * @param $member
     *
     * @return array
     * @author Eugene Odokiienko <eugene@overnightglasses.com>
     */
    public static function getFilterContent($filter_blocks, $group, $member)
    {
        $value = [];
        if (!empty($filter_blocks[ $group ][ $member ])) {
            $value = $filter_blocks[ $group ][ $member ];
        }

        return $value;
    }

	public function fillFacetSection(array &$pipeline)	{}

    /**
     * @param $atts
     * @param $group
     * @param $member
     *
     * @return bool
     * @author Eugene Odokiienko <eugene@overnightglasses.com>
     */
    public static function checkAttsForFilters($atts, $group, $member)
    {
        return !empty($atts['filters'][ $group ][ $member ]);
    }
}
