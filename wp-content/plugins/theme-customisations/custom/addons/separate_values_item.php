<?php
/**
 * Display separate values for Frame, Lens and Rush Fee in Order on Admin Side
 * Date: 12.01.18
 */

/**
 * @param int $rx_step_id
 * @return string
 */

function getResult(int $rx_step_id): string
{
    global $wpdb;

    $separate_values = '';
    $val = $wpdb->get_row("SELECT * FROM rx_step WHERE id = {$rx_step_id}");

    if ($val) {
        $frame = $val->total_price - $val->rush_price - $val->lens_price - $val->frame_discount_amount;
        $lens = $val->lens_price - $val->lens_discount_amount;
        $rush = $val->rush_price - $val->rush_discount_amount;

        $lens_price = wc_price($lens);
        $frame_price = wc_price($frame);
        $rush_price = wc_price($rush);

        if (!empty($rx_step_id)){
            $separate_values = "
                <nobr>Lens:    <b>{$lens_price}</b></nobr><br>
                <nobr>Frame:   <b>{$frame_price}</b></nobr><br>
                <nobr>Rush:    <b>{$rush_price}</b></nobr>";
        }
    }
    return $separate_values;
}

add_action('woocommerce_admin_order_item_values', 'ong_add_order_item_cost', 10, 3);

/**
 * @param $product
 * @param $item
 * @param $item_id
 */
function ong_add_order_item_cost($product, $item, $item_id)
{
    global $pagenow;

    // do not add for orders being created manually and not saved yet
    if ('post-new.php' === $pagenow) {
        return;
    }

    // empty cell for refunds or where product is null
    if (!$item || !$product instanceof WC_Product) {
        echo '<td width="2%">&nbsp;</td>';
    } else {
        if (is_array($item)) {
            $rx_step_id = isset($item['rx_step_id']) ? $item['rx_step_id'] : null;
        } elseif ($item instanceof WC_Order_Item) {
            $rx_step_id = $item['rx_step_id'];
        }

        $html = !empty($rx_step_id) ? getResult($rx_step_id) : '';

        ?>
        <td class="item_separate_values" width="2%">
            <?=$html?>
        </td>
        <?php

    }
}

add_action('woocommerce_admin_order_item_headers', 'ong_add_order_item_cost_column_headers');

/**
 * @param $order
 */
function ong_add_order_item_cost_column_headers($order)
{
    global $pagenow;
    // Do not add for orders being created manually and not saved yet
    if ('post-new.php' === $pagenow) {
        return;
    }

    ?>
    <th class="item_separate_values" data-sort="float">
        <?php esc_html_e('Separate Values', 'ong-theme-castomisations'); ?>
    </th>
    <?php
}
