<?php

use WP_CLI\ExitException;
/*if(!class_exists('WC_Product_Variable')){
    include(plugin_url.'/woocommerce/includes/class-wc-product-variable.php');// adjust the link
}*/


/**
 * Manage Products.
 *
 * @package  ONG/CLI
 * @category CLI
 */
class ONG_Addon_Product_CLI extends WC_CLI_Product {

    use ONG_Addon_Product_Check;
    use ONG_Addon_Product_AssignProductLines;
    use ONG_Addon_Product_ExportImport;

	/**
	 * Check products.
	 *
	 * ## OPTIONS
	 *
	 * [--<field>=<value>]
	 * : Filter products based on product property.
	 *
	 * [--field=<field>]
	 * : Prints the value of a single field for each product.
	 *
	 * [--fields=<fields>]
	 * : Limit the output to specific product fields.
	 *
	 * [--format=<format>]
	 * : Accepted values: table, csv, json, count, ids. Default: table.
	 *
	 * ## AVAILABLE FIELDS
	 *
	 * These fields will be displayed by default for each product:
	 *
	 * * id
	 * * title
	 * * sku
	 * * in_stock
	 * * price
	 * * sale_price
	 * * categories
	 * * tags
	 * * type
	 * * created_at
	 *
	 * These fields are optionally available:
	 *
	 * * updated_at
	 * * status
	 * * downloadable
	 * * virtual
	 * * permalink
	 * * regular_price
	 * * sale_price_dates_from
	 * * sale_price_dates_to
	 * * price_html
	 * * taxable
	 * * tax_status
	 * * tax_class
	 * * managing_stock
	 * * stock_quantity
	 * * backorders_allowed
	 * * backordered
	 * * backorders
	 * * sold_individually
	 * * stock_quantity
	 * * featured
	 * * visible
	 * * catalog_visibility
	 * * on_sale
	 * * weight
	 * * shipping_required
	 * * shipping_taxable
	 * * shipping_class
	 * * shipping_class_id
	 * * description
	 * * enable_html_description
	 * * short_description
	 * * enable_html_short_description
	 * * reviews_allowed
	 * * average_rating
	 * * rating_count
	 * * related_ids
	 * * upsell_ids
	 * * cross_sell_ids
	 * * parent_id
	 * * featured_src
	 * * download_limit
	 * * download_expiry
	 * * download_type
	 * * purchase_note
	 * * total_sales
	 * * parent
	 * * product_url
	 * * button_text
	 *
	 * There are some properties that are nested array. In such case, if array.size
	 * is zero then listing the fields with `array.0.some_field` will results
	 * in error that field `array.0.some_field` does not exists.
	 *
	 * Dimensions fields:
	 *
	 * * dimensions.length
	 * * dimensions.width
	 * * dimensions.height
	 * * dimensions.unit
	 *
	 * Images is an array in which element can be accessed by specifying its index:
	 *
	 * * images
	 * * images.size
	 * * images.0.id
	 * * images.0.created_at
	 * * images.0.updated_at
	 * * images.0.src
	 * * images.0.title
	 * * images.0.alt
	 * * images.0.position
	 *
	 * Attributes is an array in which element can be accessed by specifying its index:
	 *
	 * * attributes
	 * * attributes.size
	 * * attributes.0.name
	 * * attributes.0.slug
	 * * attributes.0.position
	 * * attributes.0.visible
	 * * attributes.0.variation
	 * * attributes.0.options
	 *
	 * Downloads is an array in which element can be accessed by specifying its index:
	 *
	 * * downloads
	 * * downloads.size
	 * * downloads.0.id
	 * * downloads.0.name
	 * * downloads.0.file
	 *
	 * Variations is an array in which element can be accessed by specifying its index:
	 *
	 * * variations
	 * * variations.size
	 * * variations.0.id
	 * * variations.0.created_at
	 * * variations.0.updated_at
	 * * variations.0.downloadable
	 * * variations.0.virtual
	 * * variations.0.permalink
	 * * variations.0.sku
	 * * variations.0.price
	 * * variations.0.regular_price
	 * * variations.0.sale_price
	 * * variations.0.sale_price_dates_from
	 * * variations.0.sale_price_dates_to
	 * * variations.0.taxable
	 * * variations.0.tax_status
	 * * variations.0.tax_class
	 * * variations.0.managing_stock
	 * * variations.0.stock_quantity
	 * * variations.0.in_stock
	 * * variations.0.backordered
	 * * variations.0.purchaseable
	 * * variations.0.visible
	 * * variations.0.on_sale
	 * * variations.0.weight
	 * * variations.0.dimensions -- See dimensions fields
	 * * variations.0.shipping_class
	 * * variations.0.shipping_class_id
	 * * variations.0.images -- See images fields
	 * * variations.0.attributes -- See attributes fields
	 * * variations.0.downloads -- See downloads fields
	 * * variations.0.download_limit
	 * * variations.0.download_expiry
	 *
	 * Fields for filtering query result also available:
	 *
	 * * q              Filter products with search query.
	 * * created_at_min Filter products whose created after this date.
	 * * created_at_max Filter products whose created before this date.
	 * * updated_at_min Filter products whose updated after this date.
	 * * updated_at_max Filter products whose updated before this date.
	 * * limit          The maximum returned number of results.
	 * * offset         Offset the returned results.
	 * * order          Accepted values: ASC and DESC. Default: DESC.
	 * * orderby        Sort retrieved products by parameter. One or more options can be passed.
	 *
	 * ## EXAMPLES
	 *
	 *     vendor/bin/wp ong product check
	 *
	 *     vendor/bin/wp ong product check --rule=price
	 *
	 *     wp ong product check --rules=price,dimensions,manage_stock --format=json
	 *
	 * @subcommand check
	 * @since      2.5.0
	 */
	public function check_( $args, $assoc_args ) {
		$query_args = $this->merge_wp_query_args( $this->get_list_query_args( $assoc_args ), $assoc_args );

        $validator  = $this->get_validator( $assoc_args );
		$formatter  = $this->get_formatter( $assoc_args );


        $query = new WP_Query( $query_args );

        $items = $this->format_posts_to_items( $query->posts );

        $items = $validator->validate($items);
        foreach ($items as &$item) {
            $item = $this->flatten_array( $item );
        }

        if ( 'ids' === $formatter->format ) {
            //todo make ids from items
            //echo implode( ' ', $query->posts );
        } else {
            $formatter->display_items( $items );
        }
	}

    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public function assign_product_lines()
    {
        $this->registerAttribute();

        foreach ((array)PRODUCT_LINES_DATA as $attribute_name => $data) {
            $title = $data['title'];
            $conditions = $data['conditions'];
            $conditions['tax_query']['relation'] = 'AND';
            $conditions['tax_query'][] = [
                'taxonomy' => 'pa_product-line',
                'field' => 'slug',
                'terms' => array_keys(PRODUCT_LINES_DATA),
                'operator' => 'NOT IN'
            ];
            $this->updateAttributeByConditions($attribute_name, $title, $conditions);
        }

        echo "\r\nDone!\r\n";
    }

    public function remove_product_lines() {
        $this->deleteOldTerms();
    }

    /**
     * Format posts from WP_Query result to items in which each item contain
     * common properties of item, for instance `post_title` will be `title`.
     *
     * @since  2.5.0
     * @param  array $posts Array of post
     * @return array Items
     */
    protected function format_posts_to_items( $posts ) {
        $items = array();
        foreach ( $posts as $post ) {
            $product = wc_get_product( $post->ID );
            if ( ! $product ) {
                continue;
            }
            $items[] = $this->
            get_product_data( $product );
        }

        return $items;
    }

    private function get_product_data( $product ) {

        /** @var WC_Product|WC_Product_Variable|WC_Product_External $product */
        if ( $product->is_type( 'variation' ) ) {
            $post = get_post( $product->get_parent_id() );
        } else {
            $post = get_post( $product->get_id() );
        }

        // Add data that applies to every product type.
        $product_data = array(
            'title'              => $product->get_title(),
            'id'                 => (int) $product->get_id(),
            'created_at'         => $this->format_datetime( $post->post_date_gmt ),
            'updated_at'         => $this->format_datetime( $post->post_modified_gmt ),
            'type'               => $product->product_type,
            'status'             => $post->post_status,
            'downloadable'       => $product->is_downloadable(),
            'virtual'            => $product->is_virtual(),
            'permalink'          => $product->get_permalink(),
            'sku'                => $product->get_sku(),
            'price'              => $product->get_price(),
            'regular_price'      => $product->get_regular_price(),
            'sale_price'         => $product->get_sale_price() ? $product->get_sale_price() : null,
            'price_html'         => $product->get_price_html(),
            'taxable'            => $product->is_taxable(),
            'tax_status'         => $product->get_tax_status(),
            'tax_class'          => $product->get_tax_class(),
            'managing_stock'     => $product->managing_stock(),
            'stock_quantity'     => $product->get_stock_quantity(),
            'in_stock'           => $product->is_in_stock() ? 'yes' : 'no',
            'backorders_allowed' => $product->backorders_allowed(),
            'backordered'        => $product->is_on_backorder(),
            'sold_individually'  => $product->is_sold_individually(),
            'purchaseable'       => $product->is_purchasable(),
            'featured'           => $product->is_featured(),
            'visible'            => $product->is_visible(),
            'catalog_visibility' => $product->visibility,
            'on_sale'            => $product->is_on_sale(),
            'product_url'        => $product->is_type( 'external' ) ? $product->get_product_url() : '',
            'button_text'        => $product->is_type( 'external' ) ? $product->get_button_text() : '',
            'weight'             => $product->get_weight() ? $product->get_weight() : null,
            'dimensions'         => array(
                'length' => $product->length,
                'width'  => $product->width,
                'height' => $product->height,
                'unit'   => get_option( 'woocommerce_dimension_unit' ),
            ),
            'shipping_required'  => $product->needs_shipping(),
            'shipping_taxable'   => $product->is_shipping_taxable(),
            'shipping_class'     => $product->get_shipping_class(),
            'shipping_class_id'  => ( 0 !== $product->get_shipping_class_id() ) ? $product->get_shipping_class_id() : null,
            'description'        => wpautop( do_shortcode( $post->post_content ) ),
            'short_description'  => apply_filters( 'woocommerce_short_description', $post->post_excerpt ),
            'reviews_allowed'    => ( 'open' === $post->comment_status ),
            'average_rating'     => wc_format_decimal( $product->get_average_rating(), 2 ),
            'rating_count'       => (int) $product->get_rating_count(),
            'related_ids'        => implode( ', ', $product->wc_get_related_products() ),
            'upsell_ids'         => implode( ', ', $product->get_upsell_ids() ),
            'cross_sell_ids'     => implode( ', ', $product->get_cross_sell_ids() ),
            'parent_id'          => $product->post->post_parent,
            'categories'         => implode( ', ', wp_get_post_terms( $product->get_id(), 'product_cat', array( 'fields' => 'names' ) ) ),
            'tags'               => implode( ', ', wp_get_post_terms( $product->get_id(), 'product_tag', array( 'fields' => 'names' ) ) ),
            'images'             => $this->get_images( $product ),
            'featured_src'       => wp_get_attachment_url( get_post_thumbnail_id( $product->is_type( 'variation' ) ? $product->variation_id : $product->get_id() ) ),
            'attributes'         => $this->get_attributes( $product ),
            'downloads'          => $this->get_downloads( $product ),
            'download_limit'     => (int) $product->download_limit,
            'download_expiry'    => (int) $product->download_expiry,
            'download_type'      => $product->download_type,
            'purchase_note'      => wpautop( do_shortcode( wp_kses_post( $product->purchase_note ) ) ),
            'total_sales'        => metadata_exists( 'post', $product->get_id(), 'total_sales' ) ? (int) get_post_meta( $product->get_id(), 'total_sales', true ) : 0,
            'variations'         => array(),
            'parent'             => array(),
        );

        // add variations to variable products
        if ( $product->is_type( 'variable' ) && $product->has_child() ) {
            $product_data['variations'] = $this->get_variation_data( $product );
        }

        // add the parent product data to an individual variation
        if ( $product->is_type( 'variation' ) ) {
            $product_data['parent'] = $this->get_product_data( $product->parent );
        }

        return $this->flatten_array( $product_data );
    }

    /**
     * Get the images for a product or product variation
     *
     * @since  2.5.0
     * @param  WC_Product|WC_Product_Variation $product
     * @return array
     */
    private function get_images( $product ) {

        $images = $attachment_ids = array();

        if ( $product->is_type( 'variation' ) ) {

            if ( has_post_thumbnail( $product->get_id() ) ) {

                // Add variation image if set
                $attachment_ids[] = get_post_thumbnail_id( $product->get_id() );
                $images = array_merge($images, $this->extractImagesByIds( $attachment_ids, 'variation' ));
            } elseif ( has_post_thumbnail( $product->get_id() ) ) {

                // Otherwise use the parent product featured image if set
                $attachment_ids[] = get_post_thumbnail_id( $product->get_id() );
                $images = array_merge($images, $this->extractImagesByIds( $attachment_ids, 'parent' ));
            }


        } else {

            // Add featured image
            if ( has_post_thumbnail( $product->get_id() ) ) {
                $attachment_ids[] = get_post_thumbnail_id( $product->get_id() );
                $images = array_merge($images, $this->extractImagesByIds( $attachment_ids, 'featured' ));
            }

            // Add gallery images
            $images = array_merge($images, $this->extractImagesByIds( $product->get_gallery_image_ids(), 'gallery' ));
        }

        return $images;
    }

    /**
     * Get the attributes for a product or product variation
     *
     * @since  2.5.0
     * @param  WC_Product|WC_Product_Variation $product
     * @return array
     */
    protected function get_attributes( $product ) {

        $attributes = array();

        if ( $product->is_type( 'variation' ) ) {
            // variation attributes
            foreach ( $product->get_variation_attributes() as $attribute_name => $attribute ) {

                // taxonomy-based attributes are prefixed with `pa_`, otherwise simply `attribute_`
                $attributes[] = array(
                    'name'   => wc_attribute_label( str_replace( 'attribute_', '', $attribute_name ) ),
                    'slug'   => str_replace( 'attribute_', '', str_replace( 'pa_', '', $attribute_name ) ),
                    'option' => $attribute,
                );
            }
        } else {
            foreach ( $product->get_attributes() as $code => $attribute ) {
                // taxonomy-based attributes are comma-separated, others are pipe (|) separated
                if ( $attribute['is_taxonomy'] ) {
                    $terms = wp_get_post_terms($product->get_id(), $code, 'all');
                    $value = [];
                    $taxonomy = get_taxonomy($code);
                    if (!empty($terms)) {
                        foreach ($terms as $term) {
                            $value[] = [
                                'name' => $term->name,
//                                'taxonomy' => $taxonomy,
                                'slug' => $term->slug
                            ];
                        }
                    }
                    $options = explode( ',', $product->get_attribute( $attribute['name'] ) );
                    $label    = $taxonomy->label;
                } else {
                    $options = explode( '|', $product->get_attribute( $attribute['name'] ) );
                    $label = $attribute['name'];
                    $value = explode('|', $attribute['value']);
                }
                $attributes[] = array(
                    'code'      => $code,
                    'id'            => crc32($code),
                    'name'      => wc_attribute_label( $attribute['name'] ),
                    'value'     => $value,
                    'slug'      => str_replace( 'pa_', '', $attribute['name'] ),
                    'position'  => (int) $attribute['position'],
                    'visible'   => (bool) $attribute['is_visible'],
                    'variation' => (bool) $attribute['is_variation'],
                    'options'   => array_map( 'trim', $options ),
                );
            }
        }
        return $attributes;
    }
}
