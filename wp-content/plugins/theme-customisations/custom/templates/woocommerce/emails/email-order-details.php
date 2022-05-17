<?php
/**
 * Order details table shown in emails.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-order-details.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.3.1
 */


if (!defined('ABSPATH')) {
    exit;
}

//do_action('woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email); ?>

    <table class="row"
           style="border-collapse:collapse;border-spacing:0;display:table;padding:0;position:relative;text-align:left;vertical-align:top;width:100%">
    <tbody>
	<?php   if ($order->get_status() == "onhold" || $order->get_status() == "pendingframe" || $order->get_status() == "processing" || $order->get_status() == "rush" || $order->get_status() == "rushpendingframe") {
?>
    	<tr style="padding:0;text-align:left;vertical-align:top">
        	<th style="Margin:0;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px;font-weight:400;line-height:1.3;margin:0;padding:0;text-align:left">
            		<a href="https://overnightglasses.com" style="cursor: pointer;display: block;"><img
                        src="https://overnightglasses.com/content/uploads/2017/10/top_picture.jpg"
                        alt="Overnightglasses"
                        style="-ms-interpolation-mode:bicubic;clear:both;display:block;height:265px;max-width:100%;outline:0;text-decoration:none;width:100%"></a>
		</th>
	</tr>
	<tr>
    	<th style="Margin:0;padding:20px;color:#0a0a0a;font-family:Helvetica,Arial,sans-serif;font-size:16px; font-weight:400;line-height:1.3;margin:0;text-align:left;">
    
     <h1 class="title-h1"
        style="color:#333;font-family:Tahoma;font-size:20px;font-weight:400;line-height:30px;margin:0;margin-bottom:10px;margin-top:25px;padding:0;text-align:left;word-wrap:normal">
        <span style="font-weight:700">Dear customer, <?=$order->get_formatted_billing_full_name()?></span>
    </h1>
    <p style="color:#666;font-family:Tahoma;font-size:15px;font-weight:400;line-height:22px;margin:0;margin-bottom:10px;padding:0;text-align:left;">
        Your order has been received and is now being processed. Your order details are shown below for your
        reference:
    </p>
	</tr>

	<tr>
		<th>

        <p>
            <?php

	    //if ($order->get_status() == "onhold" || $order->get_status() == "pendingframe" || $order->get_status() == "processing" || $order->get_status() == "rush" || $order->get_status() == "rushpendingframe") {
	
		    $delivery = get_estimated_delivery($order);
		    if ($delivery['product_id'] != 14010 && $delivery['product_id'] != 48659) {
			echo "<div id='thankyou_shipping'>";
			echo "<br><p class='woocommerce-thankyou-order-received'>";
			if ($delivery['late_day'] == 1) {
			    echo "Please note: Orders sent after 12PM PST require an additional production day<br/><br/>";
			}
			echo "Shipping and Production Monday-Friday<br/><br/>";

			echo "Production days: <b>" . $delivery['production_days'] . "</b><br/>";
			echo "Shipping days: <b>" . $delivery['shipping_days'] . "</b><br/><br/>";

			if ($delivery['deliver_by'] != "" && $delivery['deliver_by'] != '01/05/1970') {
			    echo "Your order should be delivered by <b>" . $delivery['deliver_by'] . "</b></p><br>";
			}
			echo "</div><br/>";
		    }

	    }

     if (!$sent_to_admin) : ?>
        <p style="color:#333;opacity: 0.8;font-family:Tahoma;font-size:15px;font-weight:700;line-height:22px;margin:0;margin-bottom:10px;padding:0;text-align:left;">

            <?php printf(__('Order #%s', 'woocommerce'), $order->get_order_number()); ?>
        </p>
    <?php else : ?>
        <p style="color:#333;opacity: 0.8;font-family:Tahoma;font-size:15px;font-weight:700;line-height:22px;margin:0;margin-bottom:10px;padding:0;text-align:left;">

            <a class="link"
               href="<?php echo esc_url(admin_url('post.php?post=' . $order->get_id() . '&action=edit')); ?>"><?php printf(__('Order #%s', 'woocommerce'), $order->get_order_number()); ?></a>
            (<?php printf('<time datetime="%s">%s</time>)', $order->get_order_number(), $order->get_date_created()->format('c'), wc_format_datetime( $order->get_date_created())); ?>)
        </p>
    <?php endif;


            ?>
        </p>

    <table class="td" cellspacing="0" cellpadding="6"
           style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <thead style="background-color: rgba(216,216,216,0.5);opacity: 0.8;color: #333333;font-family: Tahoma;font-size: 15px;line-height: 18px;">
        <tr>
            <th class="td" scope="col" style="border: 1px solid #E4E3E3;padding:8px;width:50%;"><?php _e('Product', 'woocommerce'); ?></th>
            <th class="td" scope="col" style="border: 1px solid #E4E3E3; padding:8px;width:15%;"><?php _e('Quantity', 'woocommerce'); ?></th>
            <th class="td" scope="col" style="border: 1px solid #E4E3E3; padding:8px;width:35%;"><?php _e('Price', 'woocommerce'); ?></th>
        </tr>
        </thead>
        <tbody>
        <!--order details-->

        <?php $tmp= wc_get_email_order_items($order, [
            'show_sku' => $sent_to_admin,
            'show_image' => false,
            'image_size' => [32, 32],
            'plain_text' => $plain_text,
            'sent_to_admin' => $sent_to_admin
        ]);


        $d = new DOMDocument();
        $d->loadHTML($tmp);
        $d->getElementsByTagName("a")->item(0)->nodeValue = "";


        $finder = new DomXPath($d);
        $classname="right-eye-od";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        $rightnode = "";
        foreach ($nodes as $node){
            $rightnode = $node;
            break;
        }

        $classname="left-eye-os";
        $nodes = $finder->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $classname ')]");
        $leftnode = "";
        foreach ($nodes as $node){
            $leftnode = $node;
            break;
        }
        $leftrx = $leftnode->nodeValue;
        $rightrx = $rightnode->nodeValue;
        $rxtable = "<table><tbody><tr><td style='margin-right: 5px;'>$rightrx</td><td>$leftrx</td></tr></tbody></table>";
        $leftnode->nodeValue="";
        $rightnode->nodeValue = "RXTABHERE";
        //$rightnode->appendXML($rxtable);

        $html = $d->saveHTML();

        $html= str_replace("RXTABHERE",$rxtable,$html);
        $html= str_replace("Details &gt;&gt;","",$html);
        $html= str_replace("<a>","<p>",$html);
        $html= str_replace("<a ","<p ",$html);
        $html= str_replace("</a>","</p>",$html);

        echo $html;
        ?>


        </tbody>
        <tfoot>

        <?php if (apply_filters('need_display_shipping_label', false, $order->get_id())): ?>
            <tr>
                <th class="td" scope="row" colspan="2"
                    style="text-align:left; border-top-width: 4px;opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:500;font-size: 15px;line-height: 23px;">Shipping label for "Use Your Frame"
                </th>
                <td class="td"
                    style="text-align:left; border-top-width: 4px; opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:500;font-size: 15px;line-height: 23px;">
                    <a href="<?=get_site_url()?>/index.php?shipping-label=<?=$order->get_order_key() ?>">View Label</a>
                </td>
            </tr>
        <?php endif ?>

        <?php
        if ($totals = $order->get_order_item_totals()) {
            $i = 0;
            foreach ($totals as $total) {
                $i++;
                ?>
                <?php if($total['label'] ==='Total:'): ?>
                    <tr>
                        <th class="td" scope="row" colspan="2"
                            style="text-align:left;opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:700;font-size: 15px;line-height: 23px;  <?php if ($i === 1) echo 'border-top-width: 4px;'; ?>"><?php echo wp_kses_post($total['label']); ?></th>
                        <td class="td"
                            style="text-align:left;opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:700;font-size: 15px;line-height: 23px;; <?php if ($i === 1) echo 'border-top-width: 4px;'; ?>"><?php echo wp_kses_post($total['value']); ?></td>
                    </tr>
                <?php else : ?>
                    <tr>
                        <th class="td" scope="row" colspan="2"
                            style="text-align:left;opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:500;font-size: 15px;line-height: 23px;  <?php if ($i === 1) echo 'border-top-width: 4px;'; ?>"><?php echo wp_kses_post($total['label']); ?></th>
                        <td class="td"
                            style="text-align:left;opacity: 0.8;color: #333333;font-family:Tahoma;font-weight:500;font-size: 15px;line-height: 23px;; <?php if ($i === 1) echo 'border-top-width: 4px;'; ?>"><?php echo wp_kses_post($total['value']); ?></td>
                    </tr>
                <?php endif; ?>
                <?php
            }
        }
        ?>
        </tfoot>
    </table>
<?php //die(); ?>
<?php do_action('woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email); ?>
