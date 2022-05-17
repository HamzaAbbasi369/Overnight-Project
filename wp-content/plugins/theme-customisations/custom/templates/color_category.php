<?php
/**
 * Variable product add to cart
 *
 */
if (!defined('ABSPATH')) {
    exit;
}

global $product;
$attribute_keys = array_keys($attributes);

//START re-create $attributes in a way that counts variation's 'is_in_stock'
/** @var array $available_variations */
/** @var array $attributes */

if (is_array($available_variations)) {
    $attributes = [];
    array_walk($available_variations, function ($item) use ($attribute_keys, &$attributes) {
        if ($item['is_in_stock']) {
            foreach ($attribute_keys as $attribute_key) {
                $val = $item['attributes'][ 'attribute_' . $attribute_key ];
                $attributes[ $attribute_key ][] = $val;
            }
        }
    });
}

//END re-create $attributes in a way that counts variation's 'is_in_stock'

?>
<div class="variations_form cart float-left ong_cart" method="post" enctype='multipart/form-data'
      data-product_id="<?php echo absint($product->get_id()); ?>"
      data-product_variations="<?php echo htmlspecialchars(json_encode($available_variations)) ?>">

    <!--wishlist-->
    <?php echo do_shortcode('[yith_wcwl_add_to_wishlist]'); ?>

    <?php if (empty($available_variations) && false !== $available_variations) : ?>
        <p class="stock out-of-stock">
            <?php _e('This product is currently out of stock and unavailable.', 'woocommerce'); ?>
        </p>
    <?php else : ?>

        <div class="variations">
            <?php foreach ($attributes as $attribute_name => $options) : ?>

               <?php if($attribute_name === 'pa_color'): ?>
                    <ul>
                        <li class="value">
                            <?php
                            $selected = isset($_REQUEST['attribute_' . sanitize_title($attribute_name)])
                                ? wc_clean(urldecode((string)$_REQUEST['attribute_' . sanitize_title($attribute_name)]))
                                : $product->get_variation_default_attribute($attribute_name);

                            if (!$selected) {
                                $selected = reset($options);
                            }

                            ong_wc_dropdown_variation_attribute_options([
                                'options' => $options,
                                'attribute' => $attribute_name,
                                'product' => $product,
                                'selected' => $selected]);
                            ?>
                        </li>
                    </ul>
                <?php endif;?>


            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>
