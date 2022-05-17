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
class ONG_Addon_Product_Check_Options_Rule implements ONG_Addon_Product_Validation_Rule_Interface
{
    const NAME = 'options';

    public function getName()
    {
        return self::NAME;
    }

    public function check($item)
    {
        if ($item['type']==='variable') {
            if (is_array($item['variations'])) {
                foreach ($item['variations'] as $variation) {
                    Assert::notEmpty($variation['attributes'], 'Variation attributes should not be null.');
                    foreach ($variation['attributes'] as $attribute) {
                        Assert::notEmpty(
                            $attribute['option'],
                            sprintf('Variations #%s: %s should be specified.', $variation['id'], $attribute['name'])
                        );
                    }
                }
            }
        }
    }
}
