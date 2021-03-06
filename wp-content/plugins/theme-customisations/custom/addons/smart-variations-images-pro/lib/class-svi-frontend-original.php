<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class woocommerce_svi_frontend {

    private static $_this;

    protected static $number=1;

    /**
     * init
     *
     * @since 1.0.0
     * @return bool
     */
    public function __construct() {
        global $woosvi;


        $woosvi_options = get_option('woosvi_options');
        $this->attr = array();

        $defaults = array(
            'default' => false,
            'lens' => false,
            'lens-type' => 'round',
            'lens-size' => 150,
            'lens-zoomtype' => 'lens',
            'lens-scrollzoom' => false,
            'lens-fade' => false,
            'columns' => 4,
            'lightbox' => false,
            'slider' => false,
            'slider-position' => false,
            'slider-navigation' => false,
            'custom-class' => '',
            'sviforce' => false,
            'swselect' => false,
            'svicart' => false,
            'hide_thumbs' => false,
            'variation_swap' => false,
            'sviforce_image' => false,
            'display_mainimage' => false
        );

        $this->woosvi_options = wp_parse_args($woosvi_options, $defaults);
        $this->detect = new Mobile_Detect;
        $class = array('woosvi_images');
        $lens = array();
        $this->getMobile();

//            if ($this->woosvi_options['lens'] && !$this->isMobile) {
        if ($this->woosvi_options['lens']) {
            array_push($class, 'woosvi_lens');
            if ($this->woosvi_options['lens-type']) {
                array_push($lens, 'data-svilensShape="' . $this->woosvi_options['lens-type'] . '"');
            }
            if ($this->woosvi_options['lens-size']) {
                array_push($lens, 'data-svilensSize="' . $this->woosvi_options['lens-size'] . '"');
            }
            if ($this->woosvi_options['lens-zoomtype']) {
                array_push($lens, 'data-svizoomType="' . $this->woosvi_options['lens-zoomtype'] . '"');
            }
            if ($this->woosvi_options['lens-scrollzoom']) {
                array_push($lens, 'data-sviscrollZoom="' . $this->woosvi_options['lens-scrollzoom'] . '"');
            }
            if ($this->woosvi_options['lens-fade']) {
                array_push($lens, 'data-svilensFadeIn="500"');
                array_push($lens, 'data-svilensFadeOut="500"');
            }
        }
        if ($this->woosvi_options['lightbox']) {
            array_push($class, 'woosvi_lightbox');
        }
        if ($this->woosvi_options['slider']) {
            array_push($class, 'woosvi_slider');
            if ($this->woosvi_options['force-slider-position'])
                $this->isMobile = false;
            if (!$this->isMobile) {
                if ($this->woosvi_options['slider-position'] == 1 || $this->woosvi_options['slider-position'] == 2) {
                    array_push($class, 'woosvi_slider-left');
                }
                if ($this->woosvi_options['slider-position'] == 2) {
                    array_push($class, 'svislider-right');
                }
                $this->getMobile();
            } else {
                $this->woosvi_options['slider-position'] = false;
            }
        }

        if ($this->woosvi_options['sviforce']) {
            array_push($class, 'sviforce');
        }

        if ($this->woosvi_options['swselect']) {
            array_push($class, 'woosvi_swselect');
        }

        if ($this->woosvi_options['hide_thumbs']) {
            array_push($class, 'svihide_thumbs');
        }
        if ($this->woosvi_options['variation_swap']) {
            array_push($class, 'svivariation_swap');
        }


        array_push($class, $this->woosvi_options['custom-class']);

        $woosvi = array(
            'data' => $this->woosvi_options,
            'class' => implode(' ', $class),
            'lens' => implode(' ', $lens)
        );

        add_action('wp_enqueue_scripts', array($this, 'load_scripts'), 150, 1);

        if (is_admin() || is_ajax()) {
            add_action('admin_init', array($this, 'remove_gallery_and_product_images'), 10);
        } else {
            add_action('init', array($this, 'remove_gallery_and_product_images'), 10);
            add_action('template_redirect', array($this, 'remove_gallery_and_product_images'), 10);
        }

        add_filter('wp_get_attachment_image_attributes', array($this, 'add_woosvi_attribute'), 10, 2);

        if ($this->woosvi_options['svicart']) {
            add_filter('woocommerce_cart_item_thumbnail', array($this, 'filter_woocommerce_cart_item_thumbnail'), 10, 3);
        }
        return $this;
    }

    function getMobile() {
        if ($this->detect->isMobile())
            $this->isMobile = true;
        if ($this->detect->isTablet())
            $this->isMobile = false;
    }

    /**
     * public function to get instance
     *
     * @since 1.1.1
     * @return instance object
     */
    public function get_instance() {
        return self::$_this;
    }

    /**
     * load front-end scripts
     *
     * @since 1.0.0
     * @return bool
     */
    function load_scripts() {
        global $wp_styles, $woocommerce;

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        $loads = array(
            'jquery'
        );

//        if ($this->woosvi_options['lens'] && !$this->isMobile) {
        if ($this->woosvi_options['lens']) {
            wp_enqueue_script('sviezlens', plugins_url('assets/js/jquery.ez-plus' . $suffix . '.js', dirname(__FILE__)), array('jquery'), null, true);
            array_push($loads, 'sviezlens');
        }

        if ($this->woosvi_options['slider']) {
            wp_enqueue_script('sviswiper', '//cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.1/js/swiper.jquery.min.js', null, true);
            wp_enqueue_style('sviswiper', '//cdnjs.cloudflare.com/ajax/libs/Swiper/3.3.1/css/swiper.min.css', null);
            array_push($loads, 'sviswiper');
        }

        if ($this->woosvi_options['lightbox']) {
            # prettyPhoto
            $handle = 'prettyPhoto' . $suffix . '.js';
            $list = 'enqueued';

            if (!wp_script_is($handle, $list)) {
                wp_enqueue_script('prettyPhoto', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto' . $suffix . '.js', array('jquery'), $woocommerce->version, true);
                wp_enqueue_script('prettyPhoto-init', $woocommerce->plugin_url() . '/assets/js/prettyPhoto/jquery.prettyPhoto.init' . $suffix . '.js', array('jquery'), $woocommerce->version, true);
                wp_enqueue_style('woocommerce_prettyPhoto_css', $woocommerce->plugin_url() . '/assets/css/prettyPhoto.css');
                array_push($loads, 'prettyPhoto', 'prettyPhoto-init');
            }
        }


        # add-to-cart-variation
        $handle = 'add-to-cart-variation' . $suffix . '.js';
        $list = 'enqueued';
        if (!wp_script_is($handle, $list)) {
            array_push($loads, 'wc-add-to-cart-variation');
        }

        wp_enqueue_script('woo_svijs', plugins_url('assets/js/svi-frontend' . $suffix . '.js', dirname(__FILE__)), $loads, THEME_CUSTOMIZATION_VERSION, true);

        $styles = null;
        $srcs = array_map('basename', (array) wp_list_pluck($wp_styles->registered, 'src'));
        $key_woocommerce = array_search('woocommerce.css', $srcs);

        if ($key_woocommerce) {
            $styles = array(
                $key_woocommerce,
            );
        }

        wp_enqueue_style('woo_svicss', plugins_url('assets/css/woo_svi' . $suffix . '.css', dirname(__FILE__)), $styles, THEME_CUSTOMIZATION_VERSION);
    }

    /**
     * Add 1st match of variation image to cart
     *
     * @since 1.0.0
     * @return html
     */
    function filter_woocommerce_cart_item_thumbnail($product_get_image, $cart_item, $cart_item_key) {

        if ($cart_item['variation_id'] > 0) {

            $found = false;
            $product = wc_get_product($cart_item['product_id']);
            $attachment_ids = $product->get_gallery_image_ids();
            foreach ($cart_item['variation'] as $key => $value) {
                if (!$found) {
                    foreach ($attachment_ids as $attachment_id) {
                        $woo_svi = $this->get_woosvi_attribute_thumb($attachment_id);
                        if (strtolower($value) == $woo_svi) {
                            $image_title = $product->get_title();
                            $product_get_image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = array(
                                'title' => $image_title,
                                'alt' => $image_title
                            ));

                            if (preg_match('-FRONT-', $product_get_image)) {
                                $found = true;
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $product_get_image;
    }

    /**
     * Add SVI slug to images
     *
     * @since 1.0.0
     * @return html
     */
    function add_woosvi_attribute($html, $post) {
        if (is_product() || is_archive()) {

            if (function_exists('icl_object_id')) {
                global $sitepress;
                $original = get_post_meta(apply_filters('wpml_object_id', $post->ID, 'attachment', FALSE, $sitepress->get_default_language()), 'woosvi_slug', true);

                $current = $this->getTranslated($original);
            } else {
                $current = get_post_meta($post->ID, 'woosvi_slug', true);
            }

//            if ($this->woosvi_options['lens'] && !$this->isMobile) {
//                $img = wp_get_attachment_image_src($post->ID, 'full');
//                $html['data-svizoom-image'] = $img[0];
//            }

            if ($this->woosvi_options['lens']) {
                $img = wp_get_attachment_image_src($post->ID, 'full');
                if ($img) {
                    $html['data-svizoom-image'] = $img[0];
                }
            }

            $html['data-woosvi'] = $current;
        }
        return $html;
    }

    /**
     * Get SVI slug to images
     *
     * @since 1.0.0
     * @return html
     */
    function get_woosvi_attribute($id) {
        if (is_product() || is_archive()) {

            if (function_exists('icl_object_id')) {
                global $sitepress;
                $original = get_post_meta(apply_filters('wpml_object_id', $id, 'attachment', FALSE, $sitepress->get_default_language()), 'woosvi_slug', true);

                $current = $this->getTranslated($original);
            } else {
                $current = get_post_meta($id, 'woosvi_slug', true);
            }
        }
        return $current;
    }

    /**
     * Get woosvi variation slug for cart image
     *
     * @since 1.0.0
     * @return html
     */
    function get_woosvi_attribute_thumb($id) {


        if (function_exists('icl_object_id')) {
            global $sitepress;
            $original = get_post_meta(apply_filters('wpml_object_id', $id, 'attachment', FALSE, $sitepress->get_default_language()), 'woosvi_slug', true);

            $current = $this->getTranslated($original);
        } else {
            $current = get_post_meta($id, 'woosvi_slug', true);
        }

        return $current;
    }

    /**
     * Get the translated text for WPML
     *
     * @since 1.0.0
     * @return html
     */
    function getTranslated($current) {
        if (!empty($this->attr)) {
            foreach ($this->attr as $key => $attribute) {
                if ($attribute['is_taxonomy'] == 1) {
                    $current_term = get_term_by('slug', $current, $key);
                    if ($current_term) {
                        $current = $current_term->slug;
                        break;
                    }
                } else {
                    foreach ($attribute['value_original'] as $k => $v) {
                        if (strtolower($current) == strtolower($v)) {
                            $current = strtolower($attribute['value'][$k]);
                            break;
                        }
                    }
                }
            }
        }
        return $current;
    }

    /**
     * Remove default theme Product Images
     *
     */
    function remove_gallery_and_product_images() {
        if (function_exists('icl_object_id')) {
            $this->buildWPML();
        }
        if (!$this->woosvi_options['default']) {
            add_filter('woocommerce_product_thumbnails_columns', array($this, 'woo_svi_filter_woocommerce_product_thumbnails_columns'), 11, 1);
            add_filter('wc_locate_template', array($this, 'woo_svi_locate_template'), 1, 3);
            add_filter('wc_get_template', [$this, 'wc_get_template'], 12, 5);
            add_filter('single_product_large_thumbnail_size', function(){return "shop_single";});
            add_filter('single_product_small_thumbnail_size', function(){return "shop_single";});
        }
    }

    /**
     * Build correct WPML matches for translations
     *
     * @since 1.0.0
     * @return html
     */
    function buildWPML() {
        global $sitepress;
        $attr = maybe_unserialize(get_post_meta(get_the_ID(), '_product_attributes', true));
        $attr_original = maybe_unserialize(get_post_meta(icl_object_id(get_the_ID(), 'product', false, $sitepress->get_default_language()), '_product_attributes', true));

        foreach ($attr as $key => $attribute) {
            if ($attribute['is_taxonomy'] == 0) {

                $values = str_replace(" ", "", $attribute['value']);
                $terms = explode('|', $values);
                $attr[$key]['value'] = $terms;

                $values_original = str_replace(" ", "", $attr_original[$key]['value']);
                $terms_original = explode('|', $values_original);

                $attr[$key]['value_original'] = $terms_original;
            }
        }

        $this->attr = $attr;
    }

    /**
     * Get collumns for product
     *
     * @since 1.0.0
     * @return html
     */
    function woo_svi_filter_woocommerce_product_thumbnails_columns($number) {
        $number = $this->woosvi_options['columns'];
        if (empty($number) || $number < 1)
            $number = 3;

        return $number;
    }

    /**
     * Plugin path
     *
     * @since 1.0.0
     * @return html
     */
    function woo_svi_plugin_path() {
        return untrailingslashit(plugin_dir_path(dirname(__FILE__)));
    }

    /**
     * Load SVI Templates
     *
     * @since 1.0.0
     * @return html
     */
    function woo_svi_locate_template($template, $template_name, $template_path) {

        global $woocommerce;

        $_template = $template;

        if (!$template_path)
            $template_path = $woocommerce->template_url;

        $plugin_path = $this->woo_svi_plugin_path() . '/woocommerce/';
        // Look within passed path within the theme - this is priority

        $template = locate_template(
                array(
                    $template_path . $template_name,
                    $template_name
                )
        );

        // Modification: Get the template from this plugin, if it exists

        if (file_exists($plugin_path . $template_name)) {
            $template = $plugin_path . $template_name;
        }

        // Use default template

        if (!$template)
            $template = $_template;

        // Return what we found
        return $template;
    }

    /**
     * Look in this plugin for WooCommerce template overrides.
     *
     * For example, if you want to override woocommerce/templates/cart/cart.php, you
     * can place the modified template in <plugindir>/custom/templates/woocommerce/cart/cart.php
     *
     * @param string $located       is the currently located template, if any was found so far.
     * @param string $template_name is the name of the template (ex: cart/cart.php).
     *
     * @return string $located is the newly located template if one was found, otherwise
     *                         it is the previously found template.
     */
    public function wc_get_template($located, $template_name, $args, $template_path, $default_path)
    {
        $plugin_template_path = $this->woo_svi_plugin_path() . '/woocommerce/' . $template_name;

        if (file_exists($plugin_template_path)) {
            $located = $plugin_template_path;
        }

        return $located;
    }

    /**
     * Main image slider
     *
     * @since 1.0.0
     * @return html
     */
    function build_mainswiper() {
        global $post, $product, $woosvi;

        $cols = array();

        if ($woosvi['data']['slider-position'])
            $cols = array('woosvi8');

        $main_class = esc_attr(implode(' ', $cols));

        $attachment_ids = $product->get_gallery_image_ids();

        if (has_post_thumbnail() || !empty($attachment_ids)) {
            $attachment_ids = $product->get_gallery_image_ids();

            ?>
            <?php if ($woosvi['data']['slider-position']) { ?>

                <div class="<?php echo $main_class; ?>">
                <?php }?>
                <div id="svi_mainslider" class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        if (!empty($attachment_ids)) {
                            foreach ($attachment_ids as $attachment_id) {
                                $classes = array();
                                $image_link = wp_get_attachment_url($attachment_id);
                                if (!$image_link)
                                    break;

                                $image_title = esc_attr(get_the_title($attachment_id));
                                $image_caption = esc_attr(get_post_field('post_excerpt', $attachment_id));

                                $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_large_thumbnail_size', 'shop_single'), 0, $attr = array(
                                    'title' => $image_title,
                                    'alt' => $image_title,
                                    'data-big' => $image_link
                                ));


                                if ($woosvi['data']['lightbox'])
                                    $lightbox = 'gallery';
                                else
                                    $lightbox = $this->get_woosvi_attribute($attachment_id);

                                $image_class = esc_attr(implode(' ', $classes));

                                $img = apply_filters(
                                        'woocommerce_single_product_image_html', sprintf(
                                                '<div class="swiper-slide"><a href="%s" class="woocommerce-main-image %s" title="%s" data-rel="prettyPhoto[%s]" data-o_href="%s">%s</a></div>', $image_link, $image_class, $image_caption, $lightbox, $image_link, $image
                                        ), $attachment_id, $post->ID, $image_class
                                );
                                $clone[] = $img;
                                echo $img;
                            }
                        } else {

                            $classes = array();
                            $image_caption = get_post(get_post_thumbnail_id())->post_excerpt;
                            $image_link = wp_get_attachment_url(get_post_thumbnail_id());

                            if (!$image_link)
                                return;

                            $image_title = $image_caption;

                            $image = wp_get_attachment_image(get_post_thumbnail_id(), apply_filters('single_product_large_thumbnail_size', 'shop_single'), 0, $attr = array(
                                'title' => $image_title,
                                'alt' => $image_title,
                                'data-big' => $image_link
                            ));


                            if ($woosvi['data']['lightbox'])
                                $lightbox = 'gallery';
                            else
                                $lightbox = $this->get_woosvi_attribute(get_post_thumbnail_id());

                            $image_class = esc_attr(implode(' ', $classes));

                            $img = apply_filters(
                                    'woocommerce_single_product_image_html', sprintf(
                                            '<div class="swiper-slide"><a href="%s" class="woocommerce-main-image %s" title="%s" data-rel="prettyPhoto[%s]" data-o_href="%s">%s</a></div>', $image_link, $image_class, $image_caption, $lightbox, $image_link, $image
                                    ), get_post_thumbnail_id(), $post->ID, $image_class
                            );
                            $clone[] = $img;
                            echo $img;
                        }
                        ?>
                    </div>
                    <?php if ($woosvi['data']['slider-navigation']) { ?>
                        <!-- Add Arrows -->
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    <?php } ?>
                </div>
                <?php if ($woosvi['data']['slider-position']) { ?>
                </div>

            <?php } ?>
            <div id="svi_mainslider_cloner" class="hidden">
                <?php
                foreach ($clone as $key => $value) {
                    echo $value;
                }
                ?>
            </div>
            <?php
        } else {

            echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $post->ID);
        }
    }

    /**
     * Thumbs image slider
     *
     * @since 1.0.0
     * @return html
     */
    function build_thumbswiper() {
        global $post, $product, $woosvi;
        $cols = array();

        if ($woosvi['data']['slider-position'])
            $cols = array('woosvi2');

        $main_class = esc_attr(implode(' ', $cols));
        $attachment_ids = $product->get_gallery_image_ids();

        if ($attachment_ids) {
            ?>
            <?php if ($woosvi['data']['slider-position']) { ?>
                <div class="<?php echo $main_class; ?>">
                <?php } ?>
                <div id="svi_thumbslider" class="swiper-container" data-columns="<?php echo $woosvi['data']['columns']; ?>">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($attachment_ids as $attachment_id) {

                            $classes = array('');

                            $image_link = wp_get_attachment_url(get_post_thumbnail_id());

                            if (!$image_link)
                                break;

                            $image_title = esc_attr(get_the_title($attachment_id));
                            $image_caption = esc_attr(get_post_field('post_excerpt', $attachment_id));

                            $image = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = array(
                                'title' => $image_title,
                                'alt' => $image_title
                            ));

                            if ($woosvi['data']['lightbox'])
                                $lightbox = 'gallery';
                            else
                                $lightbox = $this->get_woosvi_attribute($attachment_id);

                            $image_class = esc_attr(implode(' ', $classes));

                            $img = apply_filters('woocommerce_single_product_image_thumbnail_html', sprintf('<div class="swiper-slide">%s</div>', $image), $attachment_id, $post->ID, $image_class);
                            $clone[] = $img;
                            echo $img;
                        }
                        ?>
                    </div>

                </div>
                <?php if ($woosvi['data']['slider-position']) { ?>
                </div>
            <?php } ?>
            <div id="svi_thumbslider_cloner" class="hidden">
                <?php
                foreach ($clone as $key => $value) {
                    echo $value;
                }
                ?>
            </div>
            <?php
        }
    }

    /**
     * Default image
     *
     * @since 1.0.0
     * @return string html
     */
    function build_mainimage() {
        global $post, $woocommerce, $product, $woosvi;

        if (has_post_thumbnail()) {
            $image_caption = get_post(get_post_thumbnail_id())->post_excerpt;
            $image_link = wp_get_attachment_url(get_post_thumbnail_id());
            $image = get_the_post_thumbnail($post->ID, apply_filters('single_product_large_thumbnail_size', 'shop_single'), array(
                'title' => get_the_title(get_post_thumbnail_id())
            ));

            $attachment_count = count($product->get_gallery_image_ids());

            if ($attachment_count > 0) {
                $gallery = '[product-gallery]';
            } else {
                $gallery = '';
            }

            echo apply_filters('woocommerce_single_product_image_html', sprintf('<div id="woosvimain"><a href="%s" itemprop="image" class="woocommerce-main-image hidden" title="%s" data-rel="prettyPhoto' . $gallery . '" data-o_href="%s">%s</a></div>', $image_link, $image_caption, $image_link, $image), $post->ID);
        } else {

            echo apply_filters('woocommerce_single_product_image_html', sprintf('<img src="%s" alt="%s" />', wc_placeholder_img_src(), __('Placeholder', 'woocommerce')), $post->ID);
        }
    }

    /**
     * Default thumbs
     *
     * @since 1.0.0
     * @return html
     */
    function build_thumbimage($args = []) {

        $columns = apply_filters('woocommerce_product_thumbnails_columns', 3);
        $number = self::$number++;

        $defaults   = array(
            'context'              => 'single',
            'columns'              => $columns,
            'wrapper_tag'          => 'div',
            'item_tag'          => 'div',
            'wrapper_class'        => "svithumbnails columns-$columns hidden",
            'id'                   => "svithumbnails_$number"
        );
        $args       = wp_parse_args( $args, $defaults );
        global $post, $product, $woocommerce, $woosvi;
        $attachment_ids = $product->get_gallery_image_ids();

//        $selected = $product->get_variation_default_attribute($attribute_name);
        if ( $product->is_type( 'variable' ) ) {
            $default_attributes = $product->get_default_attributes();
        }

        if ($attachment_ids) {
            $loop = 0;

            ?>
            <div class="woosvi_strap">
            <<?=$args['wrapper_tag']?> id="<?=$args['id']?>" class="<?=$args['wrapper_class']?>">
            <?php

            foreach ($attachment_ids as $attachment_id) {

                $classes = [''];

                if ($loop === 0 || $loop % $columns === 0) {
                    $classes[] = 'first';
                }

                if (($loop + 1) % $columns === 0) {
                    $classes[] = 'last';
                }

                $image_link = wp_get_attachment_url($attachment_id);

                if (!$image_link) {
                    continue;
                }

                $image_title   = esc_attr(get_the_title($attachment_id));
                $image_caption = esc_attr(get_post_field('post_excerpt', $attachment_id));
                $image         = wp_get_attachment_image($attachment_id, apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail'), 0, $attr = [
                    'title' => $image_title,
                    'alt'   => $image_title
                ]);

//                $props       = wc_get_product_attachment_props( $attachment_id, $post );

                $image_class = esc_attr(implode(' ', $classes));

                $woosvi_attribute = $this->get_woosvi_attribute($attachment_id);

                if ($woosvi['data']['lightbox']) {
                    $lightbox = 'gallery';
                }else{
                    $lightbox = $woosvi_attribute;
                }

                $selected = false;
                if (
                    is_array($default_attributes)
                    && array_key_exists('pa_color', $default_attributes)
                    && $woosvi_attribute
                    && $default_attributes['pa_color'] === $woosvi_attribute
                ) {
                    $selected = true;
                }

                $out = apply_filters('woocommerce_single_product_image_thumbnail_html',
                    ($args['context']!=='single' ? "<{$args['item_tag']} class=\"thumb-item\" style=\"display: " . ($selected ?'list-item':'none') . ';">' : '<div class="thumb-item" style="display: ' . ($selected ?'block':'none') . ';">') .
                    sprintf(
                        '<a href="%s" class="%s" title="%s" data-rel="prettyPhoto[%s]" data-o_href="%s">%s</a>',
                        $image_link,
                        $image_class,
                        $image_caption,
                        $lightbox,
                        $image_link,
                        $image
                    ).
                    ("</{$args['item_tag']}>"),
                    $attachment_id,
                    $post->ID,
                    $image_class
                );

                echo $out;

                $loop++;
            }

            echo "</{$args['wrapper_tag']}>";
            echo "</div>";
        }
    }
}

function woosvi_class() {
    global $woosvi_class;

    if (!isset($woosvi_class)) {
        $woosvi_class = new woocommerce_svi_frontend();
    }

    return $woosvi_class;
}

// initialize
woosvi_class();
