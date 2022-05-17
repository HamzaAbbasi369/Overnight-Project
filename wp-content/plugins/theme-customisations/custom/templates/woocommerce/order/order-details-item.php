<?php
/**
 * Order Item Details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details-item.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see    https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
    return;
}
?>
<div class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item ', $item, $order)); ?> flex-wrap">
    <div class="col-wrap-1">
        <?php
        $is_visible = $product && $product->is_visible();
        $product_permalink = apply_filters('woocommerce_order_item_permalink', $is_visible ? $product->get_permalink($item) : '', $item, $order);
        echo apply_filters('woocommerce_order_item_name', '<p class="product-item-name">' . $item['name'], $item, $is_visible);
        echo apply_filters('woocommerce_order_item_quantity_html', '<span class="product-quantity">' . sprintf('&times; %s', $item['qty']) . '</span></p>', $item);
        ?>

        <div class="shopping--content-top shopping--content-top-1">
            <?php echo $product->get_image(); ?>
        </div>
    </div>


    <div class="shopping--content-top shopping--content-top-1">
        <?php
        do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
        wc_display_item_meta($item);
        wc_display_item_downloads($item);
        do_action('woocommerce_order_item_meta_end', $item_id, $item, $order);
        ?>

        <!-- for button to show "view label" -->
        <?php if (apply_filters('need_display_shipping_label', false, $order->get_id())): ?>
            <div class="variation-Frame-dt">Shipping label for "Use Your Frame":</div>
            <div class="variation-Frame-dd">
                <a href="<?= get_site_url() ?>/index.php?shipping-label=<?=$order->get_order_key()?>"
                   class="view-label">View Label</a>
            </div>
        <?php endif ?>
        <!-- END block for button to show "view label" -->

        <p class="product-total">
            <?php echo $order->get_formatted_line_subtotal($item); ?>
        </p>
    </div>
</div>
<?php if ($show_purchase_note && $purchase_note) : ?>
    <tr class="product-purchase-note">
        <td colspan="3"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); ?></td>
    </tr>
<?php endif; ?>
