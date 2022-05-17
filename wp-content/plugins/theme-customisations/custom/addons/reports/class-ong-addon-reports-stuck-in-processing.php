<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_stuck_in_processing extends Ong_Addon_Reports_sales_date
{

    public function __construct()
    {

        if (parent::__construct()) {
            $this->writeTable($this->res);
        }
    }

    public function writeTable($res)
    {
        ?>
        <br> <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px; float: left">
            <tr>
                <th>Date</th>
                <th>Order Id</th>
                <th>Product Id</th>
                <th>Order item name</th>
                <th>Price</th>
                <th>Color</th>
            </tr>

            <?php

            $total_order_value = '';
            foreach ($res as $val) {
                $total_order_value += $val->order_value;
            }

            $total_order_value = number_format($total_order_value, 2, ',', ' ');

            foreach ($res as $val) :
                ?>
                <tr style="text-align: center;">
                    <td><?= $val->order_date; ?></td>
                    <td><a href="/wp-admin/post.php?post=<?=$val->order_id;?>&action=edit">
                            <?=$val->order_id;?>
                        </a></td>
                    <td><a href="/wp-admin/post.php?post=<?=$val->product_id;?>&action=edit">
                            <?=$val->product_id;?>
                        </a></td>
                    <td><?= $val->order_item_name; ?></td>
                    <td>$ <?= $val->order_value?></td>
                    <td><?= $val->color; ?></td>
                </tr>

            <?php endforeach; ?>
            <tr style="text-align: center;">
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>$ <?= $total_order_value ?></th>
                <th></th>
            </tr>
        </table>

        <?php
    }

    public function getValue()
    {
        global $wpdb;
        $res = $wpdb->get_results(
            "SELECT
              order_id,
              order_item_name,
              DATE_FORMAT( b.post_date,  '%d-%m-%Y' ) order_date,
              post_status order_status, 	
              FORMAT(g.meta_value, 2) order_value,
              d.meta_value product_id,     
              f.meta_value color
            FROM
              wp_woocommerce_order_items a,
              wp_posts b,
              wp_woocommerce_order_itemmeta d,
              wp_woocommerce_order_itemmeta f,
              wp_woocommerce_order_itemmeta g
            WHERE  a.order_id=b.ID
                   and a.order_item_id=d.order_item_id
                   and a.order_item_id=f.order_item_id
                   and a.order_item_id=g.order_item_id
                   and a.order_item_type='line_item'
                   and d.meta_key='_product_id'
                   and f.meta_key='pa_color'
                   and g.meta_key='_line_total'
                   and  b.post_status='wc-processing'
                   and post_date<DATE_ADD(now(), INTERVAL -7 DAY)
            ORDER BY order_date DESC ;"
        );

        return $res;
    }
}
