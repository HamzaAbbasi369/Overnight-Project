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
class ONG_Addon_Product_Check_Images_Rule implements ONG_Addon_Product_Validation_Rule_Interface
{
    const NAME = 'images';

    public function getName()
    {
        return self::NAME;
    }

    public function check($item)
    {
        //for now check only variable products
        if ($item['type']==='variable') {
            Assert::notEmpty($item['images'],'Each product should have Images attached');
            Assert::isArray($item['images'],'Each product should have Images attached');

            $sortedVariations = $this->checkVariableAttributes( $item );
            $SVIImages = $this->organizeImagesBySVISlug( $item );

            $VariationErrors = [];
            foreach ($sortedVariations as $variation) {

                try {
                    Assert::keyExists($SVIImages, $variation,'Variation %s does not have images linked to SVI tags');
                    $attachments = $SVIImages[$variation];

                    unset($SVIImages[$variation]);
                    $orientations = [];
                    foreach ($attachments as $attachment) {
                        $attachment['src'];

                        if (!empty($attachment['type']) && $attachment['type']=='featured') {
                            $orientation = $attachment['type'];
                        } else {
                            $pattern = '~-(front|45)$~i';
                            $name = pathinfo($attachment['src'], PATHINFO_FILENAME);
                            Assert::regex($name, $pattern);
                            preg_match($pattern, $name, $matches);
                            $orientation = strtolower($matches[1]);
                        }

                        if (!array_key_exists($orientation, $orientations)) {
                            $orientations[$orientation] = 1;
                        }else{
                            $orientations[$orientation] = $orientations[$orientation]+1;
                        }
                    }

//                    Assert::keyExists($orientations, 'featured','Expected variation '.$variation.' has %s image');
//                    Assert::eq($orientations['featured'], 1, 'Expected variation '.$variation.' to contain %2$d "featured" attachment. Got: %1$d.');

                    Assert::keyExists($orientations, 'front','Expected variation '.$variation.' has %s image');
                    Assert::eq($orientations['front'], 1, 'Expected variation '.$variation.' to contain %2$d "front" attachment. Got: %1$d.');

                    Assert::keyExists($orientations, '45','Expected variation '.$variation.' has %s image');
                    Assert::eq($orientations['45'], 1, 'Expected variation '.$variation.' to contain %2$d "45" attachment. Got: %1$d.');

                } catch (\InvalidArgumentException $e) {
                    $VariationErrors[] = $e->getMessage();
                }

            }

            if (!empty($VariationErrors)) {
                throw new InvalidArgumentException(implode("\r\n",$VariationErrors));
            }

            //images without 'woosvi_slug'
            if (array_key_exists('',$SVIImages)) {
                $attachments = $SVIImages[''];
                unset($SVIImages['']);
                Assert::count($attachments, 0,'Expected %2$d non-SVI Images attached. Got: %1$d.'/*, $key. ' should contain %d attached images. Got: %d.'*/);
            }

            Assert::isEmpty($SVIImages, 'Product has extra images attached, since Product doesn\'t have such variations: ' . json_encode(array_keys($SVIImages)));

//                $variationsWithImages = array_keys($tempArray);
//                sort($variationsWithImages);
//                Assert::eq(json_encode($sortedVariations), json_encode($variationsWithImages), 'Mismatch list of variations and "woosvo_slug" used. Expected to %2$s. Got: %s');

        }
    }

    /**
     * @param $item
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function checkVariableAttributes( $item ): array {
        $variable_attributes = array_filter( (array) $item['attributes'], function ( $item ) {
            return ! ! $item['variation'];
        } );

        $variations = [];
        foreach ( (array) $variable_attributes as $variable_attribute ) {
            if ($variable_attribute['code']!=='pa_color') {
                continue;
            }
            foreach ( (array) $variable_attribute['value'] as $value ) {
                $variations[ $value['slug'] ] = $value['name'];
            }
        }
        Assert::notEmpty( $variations, 'There is no variable attributes in this product' );

        $sortedVariations = array_keys( $variations );
        sort( $sortedVariations );

        return $sortedVariations;
    }

    /**
     * @param $item
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function organizeImagesBySVISlug( $item ): array {
        $SVIImages = [];
        foreach ( $item['images'] as $image ) {
            $SVIImages[ ( ! empty( $image['woosvi_slug'] ) ? $image['woosvi_slug'] : '' ) ][] = $image;
        }

        return $SVIImages;
    }
}
