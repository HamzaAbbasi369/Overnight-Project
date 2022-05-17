<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Extractor;

use OngStore\Core\Api\Client;
use OngStore\FacetedFilter\Helper\ExtractorResponse;

class Products extends AbstractPostExtractor
{
    public function getData($id = 0)
    {
    }

    public function getFilter()
    {
        return function ($record) {
            return ['product_id' => $record['product_id']];
        };
    }

    public function price(): ExtractorResponse
    {
    }
}
