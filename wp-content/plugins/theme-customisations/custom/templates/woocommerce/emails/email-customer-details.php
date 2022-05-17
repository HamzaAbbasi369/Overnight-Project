<?php
/**
 * Additional Customer Details
 *
 * This is extra customer data which can be filtered by plugins. It outputs below the order item table.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-customer-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @author        WooThemes
 * @package    WooCommerce/Templates/Emails
 * @version     2.5.0
 */

if (!defined('ABSPATH')) {
    exit;
}

?>
<tr class="content-text" style="padding:0;text-align:left;vertical-align:top;">
    <th style="Margin:0;box-shadow:inset 0 -1px 0 0 rgba(0,0,0,.1);color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:28px 30px;text-align:left">

        <div class="thank-you-text" style="margin-bottom: 10px;">
            <p style="Margin:0;Margin-bottom:10px;color:#666;font-family:Tahoma;font-size:15px;font-weight:400;line-height:22px;margin:0;margin-bottom:10px;padding:0;text-align:left">
                <span style="color:#333;opacity:.8;font-weight:700">Customer details</span>
            </p>

            <?php foreach ($fields as $field) : ?>
                <p class="little-grey-text"
                   style="Margin:0;Margin-bottom:5px;color:#7F7F7F;font-family:Tahoma;font-size:15px;font-weight:400;line-height:22px;margin:0;padding:0;text-align:left">
                    <?php echo wp_kses_post($field['label']); ?>:
                    <span class="text"><?php echo wp_kses_post($field['value']); ?></span>
                </p>
            <?php endforeach; ?>
        </div>