<?php
/**
 * instant-filter
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */


trait ONG_Addon_Product_Check {

    public $used_rules = '';

    /**
     * Get query args for check subcommand.
     *
     * @since  2.5.0
     * @param  array $args Args from command line
     * @return array
     */

    /**
     * Get default format fields that will be used in `check` subcommands.
     *
     * @since  2.5.0
     * @return string
     */
    protected function get_default_format_fields() {
        return implode(',',['id,title,in_stock',$this->buildRuleFields($this->used_rules)]);
    }

    protected function get_default_validation_rules()
    {
        return 'price,images,options';
    }


    /**
     * Get formatter object based on supplied arguments.
     *
     * @since  2.5.0
     *
     * @param  array $assoc_args Associative args from CLI to determine formatting
     *
     * @return ONG_Addon_Product_Validation
     */
    protected function get_validator( &$assoc_args ) {
        $args = $this->get_validator_args( $assoc_args );
        $this->used_rules = $args['rules'];

        return new ONG_Addon_Product_Validation( $args );
    }

    /**
     * Get format args that will be passed into CLI Formatter.
     *
     * @since  2.5.0
     * @param  array $assoc_args Associative args from CLI
     * @return array Formatter args
     */
    protected function get_validator_args( $assoc_args ) {
        $validator_args = array(
            'rules' => $this->get_default_validation_rules(),
            'rule'  => null
        );

        if ( isset( $assoc_args['rules'] ) ) {
            $validator_args['rules'] = $assoc_args['rules'];
        }

        if ( isset( $assoc_args['rule'] ) ) {
            $validator_args['rule'] = $assoc_args['rule'];
        }

        return $validator_args;
    }



    /**
     * Get standard product data that applies to every product type.
     *
     * @since  2.5.0
     * @param  WC_Product $product
     * @return array
     */





    /**
     * @param $used_rules
     *
     * @return string
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function buildRuleFields($used_rules)
    {
        $fields = explode(',',$used_rules);
        $prefixed_array = preg_filter('/^/', 'validation.', $fields);

        return implode(',',$prefixed_array);
    }

    /**
     * Get an individual variation's data
     *
     * @since  2.5.0
     * @param  WC_Product $product
     * @return array
     */
    private function get_variation_data( $product ) {
        $variations = array();
        foreach ( $product->get_children() as $child_id ) {
            $variation = $product->get_child( $child_id );
            if ( ! $variation->exists() ) {
                continue;
            }

            if ( $product->is_type( 'variation' ) ) {
                $post = get_post( $product->get_parent_id() );
            } else {
                $post = get_post( $product->get_id() );
            }

            $variations[] = array(
                'id'                => $variation->get_variation_id(),
                'created_at'        => $this->format_datetime( $post->post_date_gmt ),
                'updated_at'        => $this->format_datetime( $post->post_modified_gmt ),
                'downloadable'      => $variation->is_downloadable(),
                'virtual'           => $variation->is_virtual(),
                'permalink'         => $variation->get_permalink(),
                'sku'               => $variation->get_sku(),
                'price'             => $variation->get_price(),
                'regular_price'     => $variation->get_regular_price(),
                'sale_price'        => $variation->get_sale_price() ? $variation->get_sale_price() : null,
                'taxable'           => $variation->is_taxable(),
                'tax_status'        => $variation->get_tax_status(),
                'tax_class'         => $variation->get_tax_class(),
                'managing_stock'    => $variation->managing_stock(),
                'stock_quantity'    => $variation->get_stock_quantity(),
                'in_stock'          => $variation->is_in_stock(),
                'backordered'       => $variation->is_on_backorder(),
                'purchaseable'      => $variation->is_purchasable(),
                'visible'           => $variation->variation_is_visible(),
                'on_sale'           => $variation->is_on_sale(),
                'weight'            => $variation->get_weight() ? $variation->get_weight() : null,
                'dimensions'        => array(
                    'length' => $variation->get_length(),
                    'width'  => $variation->get_width(),
                    'height' => $variation->get_height(),
                    'unit'   => get_option( 'woocommerce_dimension_unit' ),
                ),
                'shipping_class'    => $variation->get_shipping_class(),
                'shipping_class_id' => ( 0 !== $variation->get_shipping_class_id() ) ? $variation->get_shipping_class_id() : null,
                'image'             => $this->get_images( $variation ),
                'attributes'        => $this->get_attributes( $variation ),
                'downloads'         => $this->get_downloads( $variation ),
                'download_limit'    => (int) $product->get_download_limit(),
                'download_expiry'   => (int) $product->get_download_expiry(),
            );
        }
        return $variations;
    }

    /**
     * Get the downloads for a product or product variation
     *
     * @since  2.5.0
     * @param  WC_Product|WC_Product_Variation $product
     * @return array
     */
    private function get_downloads( $product ) {
        $downloads = array();
        if ( $product->is_downloadable() ) {
            foreach ( $product->get_downloads() as $file_id => $file ) {
                $downloads[] = array(
                    'id'   => $file_id, // do not cast as int as this is a hash
                    'name' => $file['name'],
                    'file' => $file['file'],
                );
            }
        }
        return $downloads;
    }

    /**
     * @param $attachment_ids
     *
     * @return array
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    private function extractImagesByIds( $attachment_ids, $type ): array {
        $images = [];
        // Build image data
        foreach ( $attachment_ids as $position => $attachment_id ) {
            $attachment_post = get_post( $attachment_id );
            if ( is_null( $attachment_post ) ) {
                continue;
            }
            $attachment = wp_get_attachment_image_src( $attachment_id, 'full' );
            if ( ! is_array( $attachment ) ) {
                continue;
            }
            $images[] = [
                'id'          => (int) $attachment_id,
                'created_at'  => $this->format_datetime( $attachment_post->post_date_gmt ),
                'updated_at'  => $this->format_datetime( $attachment_post->post_modified_gmt ),
                'src'         => current( $attachment ),
                'title'       => get_the_title( $attachment_id ),
                'alt'         => get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ),
                'woosvi_slug' => get_post_meta( $attachment_id, 'woosvi_slug', true ),
                'position'    => (int) $position,
                'type'        => $type,
            ];
        }
        // Set a placeholder image if the product has no images set
        if ( empty( $images ) ) {
            $images[] = [
                'id'         => 0,
                'created_at' => $this->format_datetime( time() ), // Default to now
                'updated_at' => $this->format_datetime( time() ),
                'src'        => wc_placeholder_img_src(),
                'title'      => __( 'Placeholder', 'woocommerce' ),
                'alt'        => __( 'Placeholder', 'woocommerce' ),
                'position'   => 0,
                'type'       => 'placeholder'
            ];
        }
        return $images;
    }
}
