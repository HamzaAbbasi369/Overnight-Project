<?php

/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
interface ONG_Addon_Product_Validation_Rule_Interface
{

    public function check($item);

    public function getName();
}
