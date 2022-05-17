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

    <?php
    do_action('woocommerce_order_item_meta_start', $item_id, $item, $order);
    //wc_display_item_meta($item);
    $item_meta = wc_display_item_meta($item, array('before' => "", 'separator' => ", ", 'after' => "", 'echo' => false, 'autop' => false));

    //shit html fix
    $item_meta = str_replace("<p>", "", $item_meta);
    $item_meta = str_replace("</p>", "", $item_meta);
    $item_meta = str_replace("</div>, <", "</div><", $item_meta);
    $item_meta = str_replace("<strong class=\"wc-item-meta-label\">Prescription:</strong>", "", $item_meta);
    $item_meta = str_replace("<strong class=\"wc-item-meta-label\">Lens Package:</strong>", "", $item_meta);



    /*
            echo '<pre>';
            echo htmlspecialchars($item_meta);
            echo '</pre>';
    */


    $d = new DOMDocument();
    $d->loadHTML($item_meta);

    $finder = new DomXPath($d);
    $classname="info-title-pd";
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    foreach ($nodes as $node){
        $info_pd = $node->ownerDocument->saveHTML( $node );
        $node->parentNode->removeChild($node);
        //$node->textContent="";
    }
    $classname="right-eye-od";
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    foreach ($nodes as $node){
	//$attr = $d->createAttribute('data-name');
	//$attr_va; = $attr->value = 'od_sphere';
        /*$node->childNodes->item(2)->childNodes->item(1)->setAttribute('data-name', 'od-sphere');
	$node->childNodes->item(2)->childNodes->item(3)->setAttribute('data-name', 'od-cylinder');
        $node->childNodes->item(2)->childNodes->item(5)->setAttribute('data-name', 'od-axis');
        $node->childNodes->item(2)->childNodes->item(7)->setAttribute('data-name', 'od-add');*/
	$right_eye = $node->ownerDocument->saveHTML( $node );
		
        $node->parentNode->removeChild($node);
        //$node->textContent="";
    }
    $classname="left-eye-os";
    $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
    foreach ($nodes as $node){
        /*$node->childNodes->item(2)->childNodes->item(1)->setAttribute('data-name', 'od-sphere');
        $node->childNodes->item(2)->childNodes->item(3)->setAttribute('data-name', 'od-cylinder');
        $node->childNodes->item(2)->childNodes->item(5)->setAttribute('data-name', 'od-axis');
        $node->childNodes->item(2)->childNodes->item(7)->setAttribute('data-name', 'od-add');*/

	$left_eye = $node->ownerDocument->saveHTML( $node );
        $node->parentNode->removeChild($node);
        //$node->textContent="";
    }

    /*

     $right_eye =  $d->getElementsByTagName(".right-eye-od")->item(0)->nodeValue;
    $left_eye =  $d->getElementsByTagName(".left-eye-os")->item(0)->nodeValue;


    $d->getElementsByTagName(".right-eye-od")->item(0)->nodeValue = "";
    $d->getElementsByTagName(".left-eye-os")->item(0)->nodeValue = "";
    */
    $d->getElementsByTagName("a")->item(0)->nodeValue = "";


    do_action('woocommerce_order_item_meta_end', $item_id, $item, $order);
    ?>


    <div class="shopping--content-top shopping--content-top-1 col-wrap-1">
        <?php
        echo $info_pd ;
        echo $right_eye ;
        echo $left_eye ;
        //wc_display_item_downloads($item);
        ?>
    </div>

    <div class="shopping--content-top shopping--content-top-1 col-wrap-1">
        <?php
        $d->formatOutput=true;
        echo $d->saveHtml();
        ?>
    </div>
    <div>
        <!-- for button to show "view label" -->
        <?php if (apply_filters('need_display_shipping_label', false, $order->get_id())): ?>
            <div class="variation-Frame-dt">Shipping label for "Use Your Frame":</div>
            <div class="variation-Frame-dd">
                <a href="<?= get_site_url() ?>/index.php?shipping-label=<?= $order->get_order_key() ?>"
                   class="view-label">View Label</a>
            </div>
        <?php endif ?>
        <!-- END block for button to show "view label" -->

    </div>
    <div>
        <p class="product-total">
            <span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">$</span><?= $item->get_total(); ?></span>

            <?php //echo $order->get_formatted_line_subtotal($item); ?>
        </p>
    </div>
</div>
<?php if ($show_purchase_note && $purchase_note) : ?>
    <tr class="product-purchase-note">
        <td colspan="3"><?php echo wpautop(do_shortcode(wp_kses_post($purchase_note))); ?></td>
    </tr>
<?php endif; ?>
