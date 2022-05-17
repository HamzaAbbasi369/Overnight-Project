<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */
use Webmozart\Assert\Assert;


/**
 * Class OngProductPriceRule
 */
class ONG_Addon_Product_Check_Price_Rule implements ONG_Addon_Product_Validation_Rule_Interface
{
    const NAME = 'price';

    public function getName()
    {
        return self::NAME;
    }

    public function check($item)
    {
        if ($item['type']==='variable') {
            if (is_array($item['variations'])) {
                foreach ($item['variations'] as $variation) {
                    Assert::notEmpty(
                        $variation['price'],
                        sprintf('Variations #%s: Price should be specified.', $variation['id'])
                    );
                }
            }
        }
    }
}
