<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2016 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

namespace OngStore\FacetedFilter\Helper;
use \OngStore\Core\Api\Client;

/**
 * Class ExtractorResponse
 */
class ExtractorResponse
{

    public $type ;

    public $params = [];

    /**
     * ExtractorResponse constructor.
     *
     * @param string $type
     * @param array  $params
     */
    public function __construct(array $params, $type = Client::METHOD_GET)
    {
        $this->type    = $type;

        $this->params  = $params;
    }
}
