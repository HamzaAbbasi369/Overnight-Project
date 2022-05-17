<?php

/**
 * Class TA_WC_Variation_Swatches_Frontend
 */
class TA_WC_Variation_Swatches_Frontend {
    /**
     * The single instance of the class
     *
     * @var TA_WC_Variation_Swatches_Frontend
     */
    protected static $instance = null;

    /**
     * Main instance
     *
     * @return TA_WC_Variation_Swatches_Frontend
     */
    public static function instance()
    {
        if (null == self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Class constructor.
     */
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);

        add_filter('woocommerce_dropdown_variation_attribute_options_html', [$this, 'get_swatch_html'], 100, 2);
        add_filter('tawcvs_swatch_html', [$this, 'swatch_html'], 5, 4);
    }

    /**
     * Enqueue scripts and stylesheets
     */
    public function enqueue_scripts()
    {
        wp_enqueue_style('tawcvs-frontend', plugins_url('assets/css/frontend.css', dirname(__FILE__)), array(), THEME_CUSTOMIZATION_VERSION);
        wp_enqueue_script('tawcvs-frontend', plugins_url('assets/js/frontend.js', dirname(__FILE__)), array('jquery'), THEME_CUSTOMIZATION_VERSION, true);
    }

    /**
     * Filter function to add swatches bellow the default selector
     *
     * @param $html
     * @param $args
     *
     * @return string
     */
    public function get_swatch_html($html, $args)
    {
        $swatch_types = TA_WCVS()->types;
        $attr         = TA_WCVS()->get_tax_attribute($args['attribute']);
        // Return if this is normal attribute
        if (empty($attr)) {
            return $html;
        }

        if (!array_key_exists($attr->attribute_type, $swatch_types)) {
            return $html;
        }

        $options   = $args['options'];
        $product   = $args['product'];
        $attribute = $args['attribute'];
        $class     = "variation-selector variation-select-{$attr->attribute_type}";
        $swatches  = '';

        if (empty($options) && !empty($product) && !empty($attribute)) {
            $attributes = $product->get_variation_attributes();
            $options    = $attributes[$attribute];
        }
       
        if (array_key_exists($attr->attribute_type, $swatch_types)) {
            if (!empty($options) && $product && taxonomy_exists($attribute)) {
                // Get terms if this is a taxonomy - ordered. We need the names too.
                $terms = wc_get_product_terms($product->get_id(), $attribute, ['fields' => 'all']);
                foreach ($terms as $term) {
                    if (in_array($term->slug, $options)) {
                        $swatches .= apply_filters('tawcvs_swatch_html', '', $term, $attr, $args);
                    }
                }
            }
           // echo "<pre>"; print_r($swatches); echo "</pre>";
            if (!empty($swatches)) {
                $class .= ' hidden';
                $customClass = '';
                $sizeCustomColor = '';
                if($attribute == 'pa_color'){
                    $customClass = 'custom-color-selector';
                }
                if($attribute == 'pa_size'){
                    $sizeCustomColor = 'custom-size-selector';
                }
                $swatches = '<div class="tawcvs-swatches '.$customClass.' '.$sizeCustomColor.' " data-attribute_name="attribute_' . esc_attr($attribute) . '">' . $swatches . '</div>';
                $html     = '<div class="' . esc_attr($class) . '">' . $html . '</div>' . $swatches;
            }
        }

        return $html;
    }

    /**
     * Print HTML of a single swatch
     *
     * @param $html
     * @param $term
     * @param $attr
     * @param $args
     *
     * @return string
     */
    public function swatch_html($html, $term, $attr, $args)
    {

        $taxnomyColorClass = '';
        if($term->taxonomy == 'pa_color'){
            $taxnomyColorClass = 'swatch-color-custom';
        }


    //     echo '<pre>';
    //     echo '<pre>'; print_r($html);
    //    print_r($term);
    //     print_r($attr);
       
        //$arrProduct = (array)$args['product'];
        //print_r($arrProduct['*variation_attributes']);
        static $calls=0;
        
        $selected = sanitize_title($args['selected']) == $term->slug ? 'selected' : '';
        $name     = esc_html(apply_filters('woocommerce_variation_option_name', $term->name));
    //     global $product;
    //     $terms = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'all'));
    //    // echo $product->get_id()."<br>";
    //     //$attributes = $product->get_attribute( 'pa_color' );
    //     $attributes = $product->get_variation_attributes();
    //     $options = $attributes['pa_color'];
    //     //echo "<pre>"; print_r($options); echo "</pre>";
    //     //echo $name."<br>";
    //     $terms = wc_get_product_terms($product->get_id(), 'pa_color', array('fields' => 'all'));
    //     foreach ($terms as $term) {
    //         if (in_array($term->slug, $options)) {
    //             echo "<pre>"; print_r($term); echo "</pre>";
    //         }
    //     }
        
        
        
        switch ($attr->attribute_type) {
            case 'color':
                $color = get_term_meta($term->term_id, 'color', true);
                list($r, $g, $b) = sscanf($color, "#%02x%02x%02x");
                $html = sprintf(
                    '<span class="swatch swatch-color swatch-%s %s" style="background-color:%s;color:%s;" title="%s" data-value="%s">%s</span>',
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($color),
                    "rgba($r,$g,$b,0.5)",
                    esc_attr($name),
                    esc_attr($term->slug),
                    $name
                );
                break;

            case 'image':
                
                $image = get_term_meta($term->term_id, 'image', true);
                $image = $image ? wp_get_attachment_image_src($image) : '';
                $image = $image ? $image[0] : WC()->plugin_url() . '/assets/images/placeholder.png';
                $idClass = '';
                if($term->taxonomy == 'pa_color'){
                    $idClass = $selected;
                }
                $html  = sprintf(
                    '<span class="swatch change_swatch_color swatch-image swatch-%s %s" id="'.$idClass.'" title="%s" data-value="%s"><img src="%s" alt="%s"><span class="labels-color">%s</span></span>',
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($name),
                    esc_attr($term->slug),
                    esc_url($image),
                    esc_attr($name),
                    esc_attr($name)
                );
                break;
            case 'label':
                $label = get_term_meta($term->term_id, 'label', true);
                $label = $label ? $label : $name;
                $html  = sprintf(
                    '<span class="swatch swatch-label swatch-%s %s" title="%s" data-value="%s">%s</span>',
                    esc_attr($term->slug),
                    $selected,
                    esc_attr($name),
                    esc_attr($term->slug),
                    esc_html($label)
                );
                break;
        }

        return $html;
    }
}
