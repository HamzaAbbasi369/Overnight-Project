<?php

//fix syntax in the woocommerce - data-attribute_name="attribute_
if (!function_exists('ong_wc_dropdown_variation_attribute_options')) {

    /**
     * Output a list of variation attributes for use in the cart forms.
     *
     * @param array $args
     * @since 2.4.0
     */
    function ong_wc_dropdown_variation_attribute_options($args = array())
    {
        static $num;

        $args = wp_parse_args(apply_filters('woocommerce_dropdown_variation_attribute_options_args', $args), array(
            'options' => false,
            'attribute' => false,
            'product' => false,
            'selected' => false,
            'name' => '',
            'id' => '',
            'class' => '',
            'show_option_none' => __('Choose an option', 'woocommerce')
        ));

        $options = $args['options'];
        $product = $args['product'];
        $attribute = $args['attribute'];
        $name = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title($attribute);
        $id = $args['id'] ? $args['id'] : sanitize_title($attribute) .'-'. ++$num;
        $class = $args['class'];
        $show_option_none = $args['show_option_none'] ? true : false;
        $show_option_none_text = $args['show_option_none'] ? $args['show_option_none'] : __('Choose an option', 'woocommerce'); // We'll do our best to hide the placeholder, but we'll need to show something when resetting options.

        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options = $attributes[$attribute];
        }

        $html = '
        <select id="'
                . esc_attr($id)
                . '" class="'
                . esc_attr($class)
                . '" name="'
                . esc_attr($name)
                . '" data-attribute_name="attribute_'
                . esc_attr(sanitize_title($attribute))
                . '" data-show_option_none="'
                . ($show_option_none ? 'yes' : 'no') . '">';

        $html .= '<option value="">' . esc_html($show_option_none_text) . '</option>';

        if (!empty($options)) {
            if ($product && taxonomy_exists($attribute)) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms($product->get_id(), $attribute, array('fields' => 'all'));

                foreach ($terms as $term) {
                    if (in_array($term->slug, $options)) {
                        $html .= '
                        <option value="'
                                 . esc_attr($term->slug)
                                 . '" '
                                 . selected(sanitize_title($args['selected']), $term->slug, false)
                                 . '>'
                                 . esc_html(apply_filters('woocommerce_variation_option_name', $term->name))
                                 . '</option>';
                    }
                }
            } else {
                foreach ($options as $option) {
                    // This handles < 2.4.0 bw compatibility where text attributes were not sanitized.
                    $selected = sanitize_title($args['selected']) === $args['selected'] ? selected($args['selected'],
                        sanitize_title($option), false) : selected($args['selected'], $option, false);

                    $html .= '<option value="'
                             . esc_attr($option)
                             . '" '
                             . $selected
                             . '>'
                             . esc_html(apply_filters('woocommerce_variation_option_name', $option))
                             . '</option>';
                }
            }
        }

        $html .= '</select>';

        echo apply_filters('woocommerce_dropdown_variation_attribute_options_html', $html, $args);
    }
}
