<?php

function get_yotpo_reviews($product) {
 // get yotpo review data
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, 'https://api.yotpo.com/v1/widget/BLzyzHJflGfWjWBrTmBt3yZyqKZh309qGGOgy82u/products/'.$product->get_id().'/reviews.json');
               curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 5);
               curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
               curl_setopt($ch, CURLOPT_TIMEOUT, 6);
               curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json', 'Accept: application/json'));
               $res = curl_exec($ch);
               $data = json_decode($res, true);
               curl_close($ch);
               if (array_key_exists('response', $data)) {
                  return $data['response']['bottomline'];
               } else {
                  return '';
               }
}

// product schema fix
add_filter('woocommerce_structured_data_product','add_product_schema_yotpo', 10 , 2);
function add_product_schema_yotpo($markup, $product) {
        global $wpdb;
        if (!empty($markup) && isset($product)) {

                // check if product slug already exist in the database
                $results = $wpdb->get_results("SELECT * FROM product_schema_cache WHERE slug = '".$product->get_slug()."' ");
                if ($wpdb->num_rows > 0) {
                        // product already exist
                        // check the date if it was not sync synce a week
                        $check_date = $wpdb->get_var("SELECT count(*) FROM product_schema_cache WHERE cache_date < NOW() - INTERVAL 1 WEEK AND slug = '".$product->get_slug()."'");
                        if ($check_date == 1) {
                                // refresh yotpo data
                                $reviews = get_yotpo_reviews($product);
                                if ($reviews != '') {
                                        $avg_score = $reviews['average_score'];
                                        $review_count = $reviews['total_review'];
                                        $wpdb->query( $wpdb->prepare( "UPDATE product_schema_cache set review_count = %d, rating_value=%f, cache_date = NOW() WHERE slug = %s", $review_count, $avg_score, $product->get_slug() ) );
                                } else {
                                        $review_count = 0;
                                        $avg_score = 0;
                                }
                        } else {
                                // just get the current yotpo data in the DB
                                $avg_score = $reviews['rating_value'];
                                $review_count = $reviews['review_count'];
                        }
                } else {
                        // get Yotpo data and add reviews to database
                        $reviews = get_yotpo_reviews($product);
                        $avg_score = $reviews['average_score'];
                        $review_count = $reviews['total_review'];
                        $wpdb->query($wpdb->prepare("INSERT INTO product_schema_cache(slug,review_count,rating_value) VALUES(%s,%d,%f)", $product->get_slug(), $review_count, $avg_score));
                }

		// markup add sku

                if ($avg_score == 0 && $review_count == 0) {
                        //$markup['aggregateRating'] = array("@type" => "AggregateRating", "reviewCount" => 1, "ratingCount" => 1);
                	$markup_offer = array(
                                        '@type'              => 'Offer',
                                        'price'              => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
                                        'priceSpecification' => array(
                                                'price'                 => wc_format_decimal( $product->get_price(), wc_get_price_decimals() ),
                                                'priceCurrency'         => get_woocommerce_currency(),
                                                'valueAddedTaxIncluded' => wc_prices_include_tax() ? 'true' : 'false',
                                        ),
                                );
			if (!array_key_exists("offers", $markup)) {
				$markup['offers'] = $markup_offer;
			}

		} else {
                        $markup['aggregateRating'] = array("@type" => "AggregateRating", "ratingValue" => number_format($avg_score, 1), "reviewCount" => $review_count);
                }
                #$markup['review'] = $data['response']['bottomline']['total_review'];
        }
        //return $markup;
        //WC()->structured_data->set_data( apply_filters( 'woocommerce_structured_data_product', $markup, $product ), true );
        return $markup;
}

