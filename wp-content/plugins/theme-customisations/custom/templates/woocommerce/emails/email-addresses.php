<?php
/**
 * Email Addresses
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-addresses.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates/Emails
 * @version     3.2.1
 */


if (!defined('ABSPATH')) {
    exit;
}

?>
<div class="thank-you-text" id="addresses">
    <div style="width: 49%;display: inline-block;border: 1px solid #e4e4e4;">
        <p style="Margin:10px 0;color:#666;font-family:Tahoma;
                                                font-size:15px;font-weight:400;line-height:22px;margin:10px 0;padding:0;
                                                text-align:left">
            <span style="color:#333;opacity:.8;font-weight:700">Billing address</span></p>
        <div class="little-grey-text" style="Margin:0;Margin-bottom:5px;color:#7F7F7F;
                                                   font-family:Tahoma;font-size:15px;font-weight:400;line-height:22px;margin:0;padding:0;text-align:left">
        <address class="address" style="border:none;"><?php echo $order->get_formatted_billing_address(); ?></address>
        </div>
    </div>

    <?php if (!wc_ship_to_billing_address_only() && $order->needs_shipping_address() && ($shipping = $order->get_formatted_shipping_address())) : ?>
        <div style="width: 49%;display: inline-block;border: 1px solid #e4e4e4;">
            <p style="Margin:10px 0;color:#666;font-family:Tahoma;
                                                font-size:15px;font-weight:400;line-height:22px;margin:10px 0;padding:0;
                                                text-align:left">
                <span style="color:#333;opacity:.8;font-weight:700">Shipping address</span></p>
            <div class="little-grey-text" style="Margin:0;Margin-bottom:5px;color:#7F7F7F;
                                                   font-family:Tahoma;font-size:15px;font-weight:400;line-height:22px;margin:0;padding:0;text-align:left">
            <address class="address" style="border:none;"><?php echo $shipping; ?></address>
            </div>
        </div>
    <?php endif; ?>
</div>
</th>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
