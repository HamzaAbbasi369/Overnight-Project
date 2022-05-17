<?php
/**
 * Order details
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/order/order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 3.3.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if ( ! $order = wc_get_order( $order_id ) ) {
    return;
}

$order_items            = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note     = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$show_customer_details  = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
?>

<div id="checkout-form-wrap">
    <div class="shopping--content-wrap">
        <div class="shopping--content-left">
            <div class="shopping--content-wrap-block">
                <?php
                foreach ($order_items as $item_id => $item) {

                    $product = $item->get_product();

                    ?>
                    <div class="shopping--content-top--wrap">
                        <?php
                        $value = wc_get_template_html( 'order/order-details-item.php', [
                            'order'			     => $order,
                            'item_id'		     => $item_id,
                            'item'			     => $item,
                            'show_purchase_note' => $show_purchase_note,
                            'purchase_note'	     => $product ? $product->get_purchase_note() : '',
                            'product'	         => $product,
                        ]);


                        $d = new DOMDocument();
                        $d->loadHTML($value);

                        $xpathsearch = new DOMXPath($d);
                        //remove prescription
                        /*
                        $nodes = $xpathsearch->query('//a');
                        foreach($nodes as $node) {
                            //$node->parentNode->removeChild($node);
                            break;
                        }*/
                        $d->getElementsByTagName("a")->item(0)->nodeValue = "";
                        /*
                        //remove click event
                        $nodes = $xpathsearch->query('//a');
                        foreach($nodes as $node) {

                            $node->removeAttribute('onclick');
                            $node->removeAttribute('href');
                            $node->removeAttribute('style');

                        }*/
                        echo $d->saveXML();
                        //echo $value;


                        ?>
                        <!--   WAS DELETED BIG PART OF CODE -->

                        <?php if (!empty($item['item_meta']['wdm_user_custom_data'][0])) : ?>
                            <div class="shopping--content-top shopping--content-top-3">
                                <p><?=$item['item_meta']['wdm_user_custom_data'][0]?></p>
                            </div> <!--PRESCRIPTION -->
                        <?php endif; ?>
                    </div>

                <?php } ?>
            </div>


            <?php do_action( 'woocommerce_order_details_after_order_table_items', $order ); ?>

            <div class="block--for-thankyou">
                <p class="need-another">
                    Need another pair?
                </p>
                <a href="<?=esc_url( wc_get_page_permalink( 'shop' ) )?>" class="button">Go to the store</a>
            </div>
        </div>
    </div>
</div>


<?php do_action('woocommerce_order_details_after_order_table', $order); ?>
