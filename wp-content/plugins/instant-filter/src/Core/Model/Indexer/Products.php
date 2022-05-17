<?php
/**
 * ONG Store
 *
 * Licence: MIT https://opensource.org/licenses/MIT
 * Copyright: odokienko
 */
namespace OngStore\Core\Model\Indexer;

use Monolog\Formatter\MongoDBFormatter;
use Monolog\Formatter\ScalarFormatter;

class Products extends AbstractPostIndexer
{
    public function getData($id = 0)
    {
    }

    /**
     * @param \WP_Post $post
     *
     * @return array
     */
    public function prepareToSync($post)
    {
        /**
         * @global \WP_Query $wp_query
         */
        global $wpdb, $product;

        $data = [];

        $blog_id = get_current_blog_id();
        $product = wc_get_product($post->ID);
        $post    = get_post($product->get_id());

        $this->getStockStatus($product, $available_variations, $total_stock, $stock_status);
        


        $is_sticky                 = is_sticky($post->ID) ? 1 : 0;
        $data['product_type']      = $product->get_type();
        $data['shipping_class']    = $product->get_shipping_class();
        $data['shipping_class_id'] = $product->get_shipping_class_id();
        $data['total_stock']       = $total_stock;
        $data['backorders_allowed']= $product->backorders_allowed();
        $data['managing_stock']    = $product->managing_stock();
        $data['no_variation_available']       = empty($available_variations) && false !== $available_variations;
        $data['stock_status']      = $stock_status;
        $data['sku']               = $product->get_sku();
	$data['final_price']       = (float)$product->get_sale_price();
	//$test = wc_get_product(150524);
	//$test = wc_get_product(127097);
	//print_r($test);
	$data['price']             = (float)wc_get_price_to_display($product);
	//$data['price']             = (float)$test->get_price();
	//echo "######################### price ##############: ".$data['price'];
        $data['category_ids']      = $this->getCategoryIds($post->ID);
        $data['image_url']         = $this->getImage($product);
        $data['small_image_url']   = $this->getImage($product, 'medium');
        $data['thumbnail_url']     = $this->getImage($product, 'thumbnail');
        $data['visibility']        = \OngStore\Core\Helper\Post::is_product_active($product);

        $data['product_id']        = $post->ID;
        $data['blog_id']           = $blog_id;
        $data['post_title']        = apply_filters('the_title_rss', $post->post_title);
        $data['pub_date']          = mysql2date('D, d M Y H:i:s +0000', get_post_time('Y-m-d H:i:s', true), false);
        $data['creator']           = get_the_author_meta('login');
        $data['guid']              = get_the_guid();
        $data['url']               = get_permalink($post);
        $data['content']           = apply_filters('the_content', $post->post_content);
        $data['excerpt']           = apply_filters('the_excerpt', $post->post_excerpt);
        $data['post_date']         = $post->post_date;
        $data['post_date_gmt']     = $post->post_date_gmt;
        $data['post_modified']     = $post->post_modified;
        $data['post_modified_gmt'] = $post->post_modified_gmt;
        $data['comment_status']    = $post->comment_status;
        $data['ping_status']       = $post->ping_status;
        $data['post_name']         = $post->post_name;
        $data['post_status']       = $post->post_status;
        $data['post_parent']       = $post->post_parent;
        $data['menu_order']        = $post->menu_order;
        $data['post_type']         = $post->post_type;
        $data['post_password']     = $post->post_password;
        $data['is_sticky']         = $is_sticky;
        $data['taxonomy']          = $this->get_wxr_post_taxonomy($post);


	$size_status = Array();
        if ($attributes = $this->getAttributes($product)) {
            $data['attributes_data'] = $attributes;
	    foreach ($attributes as $attribute) {
		// if attribute is color check for stock status of the variation, so that it doesn't get added to the sync
		    if ($attribute['code'] == 'pa_color') {
			 //echo "attribute color";
		         foreach ( $product->get_children() as $child_id ) {
                                 //echo "get stock status for $child_id $total_stock $stock_status";
			 	 $stock = get_post_meta( $child_id, '_stock_status');
				 //print_r(get_post_meta($child_id));
				 $color = get_post_meta( $child_id, 'attribute_pa_color');
				 //echo "STOCK: #####"; print_r($stock);
				 if ($stock[0] == "instock") {
					//echo "Stock available for $child_id ".$color[0]." with qty $stock"; 
					$data['attributes'][ $attribute['code'] ] = $attribute['value'];
				 } else {
					 // remove the outofstock from the value
					 $str = str_replace('-',' ', ucwords($color[0], '-'));
					 $idx = 0;
					 foreach ($attribute['value'] as $c_atr) {
						 if ($c_atr == $str) {
							 unset($attribute['value'][$idx]);
							 // remove also from Taxonomy
							 $idx2 = 0;
							 foreach ($data['taxonomy']['pa_color'] as $tax) {
								 if ($tax['name'] == $str || $tax['original-slug'] == $color[0]) {
									 unset($data['taxonomy']['pa_color'][$idx2]);
								 }
								 $idx2++;
					                 }
					         }
						 $idx++;
				         }
					 //echo "[$str] Stock$stock for $child_id ".$color[0]. " --- ".$attribute['value'];
					 //print_r($attribute['value']);
		                 }
			 }
	            } else {
			    $data['attributes'][ $attribute['code'] ] = $attribute['value'];
		    }

		    // size check
		    if ($attribute['code'] == 'pa_size') {
			foreach ( $product->get_children() as $child_id ) {
				$stock = get_post_meta( $child_id, '_stock_status');
				$att_size = get_post_meta($child_id, 'attribute_pa_size');
				if ($stock[0] == "outofstock") {
					$size_status[$att_size[0]] .= "0";
				} else {
					$size_status[$att_size[0]] .= "1";
				}
			}
		    }

		    if ($attribute['code'] == 'pa_size') { 
		    	$i = 0;
		    	$remove = "";
		    	foreach ($data['taxonomy']['pa_size'] as $sizes) {
			    if (strpos($size_status[$sizes['name']], "1") === false) {
				    unset($data['taxonomy']['pa_size'][$i]);
				    foreach ($data['attributes'][$attribute['code']] as $k => $v) {
					    if ($v == $sizes['name']) {
						    unset($data['attributes'][$attribute['code']][$k]);
					    }
			            }
				    $remove .= $sizes['name']."#".$i;
		            }
			    $i = $i + 1;
		    	}
		    }
            }
	}

        $attachment_id       = get_post_meta($post->ID, '_thumbnail_id', true);
        $attachment          = get_post($attachment_id);
        $attr                = [
            'src' => '',
            'alt' => trim(strip_tags(get_post_meta($attachment_id, '_wp_attachment_image_alt', true))),
        ];
        $attr                = apply_filters('wp_get_attachment_image_attributes', $attr, $attachment, 'thumbnail');
        $attr                = array_map('esc_attr', $attr);
        $data['image_label'] = $attr['alt'];

        if ($post->post_type == 'attachment') {
            $data['attachment_url'] = wp_get_attachment_url($post->ID);
        }

        $postmeta = $wpdb->get_results($wpdb->prepare(/** @lang MySQL */"
          SELECT * FROM $wpdb->postmeta WHERE post_id = %d", $post->ID));
        foreach ($postmeta as $meta) {
            if (apply_filters('wxr_export_skip_postmeta', false, $meta->meta_key, $meta)) {
                continue;
            }
            $data['postmeta']['meta_key']   = $meta->meta_key;
            $data['postmeta']['meta_value'] = $meta->meta_value;
        }

        return $data;
    }

    /**
     * @param int $post_id
     *
     * @return array
     */
    private function getCategoryIds($post_id)
    {
        $categories = get_the_terms($post_id, 'product_cat');

        $return = [];
        if ($categories) {
            foreach ($categories as $category) {
                $return[] = $category->term_id;
            }
        }

        return $return;
    }

    /**
     * Returns the main product image.
     *
     * @param \WC_Product $product
     * @param string      $size (default: 'shop_thumbnail')
     *
     * @return string
     */
    private function getImage($product, $size = 'shop_thumbnail')
    {
        if (has_post_thumbnail($product->get_id())) {
            $image = $this->getThePostThumbnailUrl($product->get_id(), $size);
        } elseif (($parent_id = wp_get_post_parent_id($product->get_id())) && has_post_thumbnail($parent_id)) {
            $image = $this->getThePostThumbnailUrl($parent_id, $size);
        } else {
            $image = '';
        }

        return $image;
    }

    /**
     * @param int    $post_id
     * @param string $size
     *
     * @return bool|false|string
     */
    private function getThePostThumbnailUrl($post_id, $size)
    {
        if (function_exists('get_the_post_thumbnail_url')) {
            return get_the_post_thumbnail_url($post_id, $size);
        } else {
            $post_thumbnail_id = get_post_thumbnail_id($post_id);
            if (!$post_thumbnail_id) {
                return false;
            }
            $image = wp_get_attachment_image_src($post_thumbnail_id, $size);

            return isset($image['0']) ? $image['0'] : false;
        }
    }

    /**
     * @param \WC_Product $product
     *
     * @return array
     */
    private function getAttributes($product)
    {
        $blog_id = get_current_blog_id();
        if (!defined('WC_DELIMITER')) {
            $delimiter = '|';
        } else {
            $delimiter = WC_DELIMITER;
        }

        $data       = [];
        $attributes = $product->get_attributes();
        foreach ($attributes as $code => $attribute) {
            if ($attribute) {
                if ($attribute['is_taxonomy']) {
                    $value    = wc_get_product_terms($product->get_id(), $code, ['fields' => 'names']);
                    $taxonomy = get_taxonomy($code);
                    $label    = $taxonomy->label;
                } else {
                    $label = $attribute['name'];
                    $value = explode($delimiter, $attribute['value']);
                }
                $data[ $code ] = [
                    'id'            => crc32($code),
                    'blog_id'       => $blog_id,
                    'name'          => $label,
                    'code'          => $code,
                    'value'         => $value,
                    'is_searchable' => true,
                ];
            }
        }

        return $data;
    }

    public function getFilter()
    {
        return function ($record) {
            return ['product_id' => (key_exists('product_id', $record)) ? $record['product_id'] : -1];
        };
    }

    /**
     * Get Product Attribute Value
     *
     * @param $id
     * @param $name
     *
     * @return mixed
     */
    public function getAttributeValue($id, $name)
    {
        if (strpos($name, 'attribute_pa') !== false) {
            $taxonomy = str_replace("attribute_","",$name);
            $meta = get_post_meta($id,$name, true);
            $term = get_term_by('slug', $meta, $taxonomy);
            return $term->name;
        }else{
            return get_post_meta($id, $name, true);
        }

    }

    /**
     * @param $product
     * @param $available_variations
     * @param $total_stock
     * @param $stock_status
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function getStockStatus($product, &$available_variations, &$total_stock, &$stock_status)
    {
        /**
         * @param \WC_Product_Variable $product
         * @param $total_stock
         * @param $available_variations
         *
         * @return string
         * @author Eugene Odokiienko <eugene@overnightglasses.com>
         */
        $check_stock_status = function ($product, $total_stock, $available_variations) {
            $status = $product->is_in_stock();
//            if ($product->managing_stock() && !$product->backorders_allowed() && $total_stock <= get_option( 'woocommerce_notify_no_stock_amount' ) ) {
//                $status = false;
//            }
//            if (!$status && !) {
//                $status = true;
//            }
            if ($status && empty($available_variations) && false !== $available_variations) {
                $status = false;
            }
            return $status ? 'instock' : 'outofstock';
        };
        /**
         * @var \WC_Product_Variable $product
         */
        $get_variations = count( $product->get_children() ) <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );

        $available_variations = ($product->get_type() === 'variable') && $get_variations ? $product->get_available_variations() : false;

        if ( sizeof( $product->get_children() ) > 0 ) {
            $total_stock = max( 0, $product->get_stock_quantity() );
            foreach ( $product->get_children() as $child_id ) {
                if ( 'yes' === get_post_meta( $child_id, '_manage_stock', true ) ) {
                    $stock = get_post_meta( $child_id, '_stock', true );
                    $total_stock += max( 0, wc_stock_amount( $stock ) );
                }
            }
        } else {
            $total_stock = $product->get_stock_quantity();
        }

        $stock_status = $check_stock_status($product, $total_stock, $available_variations);
    }
}
