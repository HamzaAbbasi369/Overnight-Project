<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */

namespace OngStore\FacetedFilter\Interfaces;

interface ShortcodeInterface
{

    public function getName();

    public function setName($name);

    public function run($atts, $initial_filter);

    public function fillSortSection(&$sort);

    public function getFiltered($params);

    public function fillQuerySection(array &$pipeline, $values);

    public function fillFacetSection(array &$pipeline);
}
