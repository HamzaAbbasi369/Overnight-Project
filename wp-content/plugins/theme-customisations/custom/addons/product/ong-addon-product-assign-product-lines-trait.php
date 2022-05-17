<?php

use WP_CLI\ExitException;

/**
 * instant-filter
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */


trait ONG_Addon_Product_AssignProductLines {



    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function deleteOldTerms()
    {
        $term = get_term_by('slug', 'economy', 'pa_product-line');
        wp_delete_term($term->term_id, 'pa_product-line');
        $term = get_term_by('slug', 'premium', 'pa_product-line');
        wp_delete_term($term->term_id, 'pa_product-line');
        $term = get_term_by('slug', 'prestige', 'pa_product-line');
        wp_delete_term($term->term_id, 'pa_product-line');
        $term = get_term_by('slug', 'designer', 'pa_product-line');
        wp_delete_term($term->term_id, 'pa_product-line');
    }

    /**
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function registerAttribute()
    {
        $insert = self::process_add_attribute([
            'attribute_name'    => 'product-line',
            'attribute_label'   => 'Product Line',
            'attribute_type'    => 'select',
            'attribute_orderby' => 'menu_order',
            'attribute_public'  => false
        ]);

        if (is_wp_error($insert)) {
            echo 'Creating of Product Line attribute failed (' . self::error_to_string($insert) . ')';
//            throw new ExitException( null, 1 );
        }
    }

    public static function process_add_attribute($attribute)
    {
        global $wpdb;

        if (empty($attribute['attribute_type'])) {
            $attribute['attribute_type'] = 'text';
        }
        if (empty($attribute['attribute_orderby'])) {
            $attribute['attribute_orderby'] = 'menu_order';
        }
        if (empty($attribute['attribute_public'])) {
            $attribute['attribute_public'] = 0;
        }

        if (empty($attribute['attribute_name']) || empty($attribute['attribute_label'])) {
            return new WP_Error('error', __('Please, provide an attribute name and slug.', 'woocommerce'));
        } elseif (($valid_attribute_name = self::validAttributeName($attribute['attribute_name']))
                  && is_wp_error($valid_attribute_name)
        ) {
            return $valid_attribute_name;
        } elseif (taxonomy_exists(wc_attribute_taxonomy_name($attribute['attribute_name']))) {
            return new WP_Error('error', sprintf(__('Slug "%s" is already in use. Change it, please.', 'woocommerce'), sanitize_title($attribute['attribute_name'])));
        }

        $wpdb->insert($wpdb->prefix . 'woocommerce_attribute_taxonomies', $attribute);

        do_action('woocommerce_attribute_added', $wpdb->insert_id, $attribute);

        flush_rewrite_rules();
        delete_transient('wc_attribute_taxonomies');

        return true;
    }


    /**
     * @param $attribute_name
     *
     * @return bool|WP_Error
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    public static function validAttributeName($attribute_name)
    {
        if (strlen($attribute_name) >= 28) {
            return new WP_Error('error', sprintf(__('Slug "%s" is too long (28 characters max). Shorten it, please.', 'woocommerce'), sanitize_title($attribute_name)));
        } elseif (wc_check_if_attribute_name_is_reserved($attribute_name)) {
            return new WP_Error('error', sprintf(__('Slug "%s" is not allowed because it is a reserved term. Change it, please.', 'woocommerce'), sanitize_title($attribute_name)));
        }

        return true;
    }

    /**
     * Convert a wp_error into a string
     *
     * @param mixed $errors
     *
     * @return string
     */
    public static function error_to_string($errors)
    {
        if (is_string($errors)) {
            return $errors;
        }

        // Only json_encode() the data when it needs it
        $render_data = function ($data) {
            if (is_array($data) || is_object($data)) {
                return json_encode($data);
            } else {
                return '"' . $data . '"';
            }
        };

        $resultMessage = '';
        if (is_object($errors) && is_a($errors, 'WP_Error')) {
            foreach ($errors->get_error_messages() as $message) {
                if ($errors->get_error_data()) {
                    $resultMessage .= ' ' . $render_data($errors->get_error_data());
                } else {
                    $resultMessage .= $message;
                }
            }
        }
        return $resultMessage;
    }

    /**
     * @param $attribute_name
     * @param $attribute_value
     * @param $conditions
     *
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function updateAttributeByConditions($attribute_name, $attribute_value, $conditions)
    {
        echo "\r\nStart processing of " . $attribute_value . " line ..";
        $term_id = $this->createTerm($attribute_name, $attribute_value);

        $wp_query = new WP_Query($conditions);
        $products = $wp_query->get_posts();

        foreach ($products as $product) {
            wp_add_object_terms($product->get_id(), $term_id, 'pa_product-line');

            // get existing attributes
            $attributes = get_post_meta($product->get_id(), '_product_attributes', true);

            if (!array_key_exists('pa_product-line', $attributes)){
                $attributes[ sanitize_title('pa_product-line') ] = [
                    'name'         => wc_clean('pa_product-line'),
                    'value'        => '',
                    'position'     => count($attributes), // the order in which it is displayed
                    'is_visible'   => true, // this is the one you wanted, set to true
                    'is_variation' => false, // set to true if it will be used for variations
                    'is_taxonomy'  => true // set to true
                ];

                if (!function_exists('attributes_cmp')) {
                    function attributes_cmp($a, $b)
                    {
                        if ($a['position'] == $b['position']) {
                            return 0;
                        }

                        return ($a['position'] < $b['position']) ? - 1 : 1;
                    }
                }

                uasort($attributes, 'attributes_cmp');
                update_post_meta($product->get_id(), '_product_attributes', $attributes);
            }
        }
        echo ".\tProcessed:\t". count($products) ." products.";
    }


    /**
     * @param $attribute_name
     * @param $attribute_value
     *
     * @return array|false|WP_Error|WP_Term
     * @throws ExitException
     * @author Eugene Odokiienko <eugene.odokienko@agilefuel.com>
     */
    protected function createTerm($attribute_name, $attribute_value)
    {
        if ($term = get_term_by('slug', $attribute_name, 'pa_product-line')) {
            $result = $term->term_id;
        } else {
            /**
             * add new product line
             */
            $term = wp_insert_term($attribute_value, 'pa_product-line', [
                'description' => '',
                'slug'        => $attribute_name
            ]);
            $result = $term['term_id'];
        }

        if (is_wp_error($term)) {
            echo 'Creating of '.$attribute_value.' attribute failed (' . self::error_to_string($term) . ')';
            throw new ExitException(null, 1);
        }

        return $result;
    }
}
