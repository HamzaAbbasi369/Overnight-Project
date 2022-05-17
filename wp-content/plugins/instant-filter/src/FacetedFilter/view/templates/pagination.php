<?php
/** @var string $current_url */
use OngStore\Core\Helper\Pagination\Pagination;


/** @var array $get */
/** @var integer $total_count */
/** @var integer $per_page */
/** @var string $url */
$pagination = new Pagination([
    'items' => $total_count, // or 'total'
    'per_page' => $per_page,
    'proximity' => 3,
    'uri' => $url, //'http://example.com/show/page:6',
    'pattern' => OngStore\FacetedFilter\OngFilter::$page_param . '={page}',
    'page' => (array_key_exists(OngStore\FacetedFilter\OngFilter::$page_param, $request)
        ? $request[OngStore\FacetedFilter\OngFilter::$page_param]
        : 1
    ),
]);

echo $pagination->render();
