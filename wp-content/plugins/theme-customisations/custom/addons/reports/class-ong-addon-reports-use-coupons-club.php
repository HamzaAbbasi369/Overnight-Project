<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_use_coupons_club extends WC_Admin_Report
{
    const PERCENT = 0.05;  //5%

    public function __construct()
    {
        $res = $this->getValue();
        $resReport = $this->getArrayValue($res);
        $rewards_earned = 0;
        $count = 0;
        ?>
        <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px">
            <tr>
                <th>Order ID</th>
                <th>Coupon ID</th>
                <th>Coupon code</th>
                <th>Email</th>
                <th>Count completed</th>
                <th>Rewards earned</th>
                <th>Type coupon</th>
                <th>Date created</th>
            </tr>
            <?php

            usort($resReport, function($a, $b){
                return -($a['rewards_earned'] - $b['rewards_earned']);
            });

            foreach ($resReport as $val) : ?>
                <tr style="text-align: center;">
                    <td><a href="/wp-admin/post.php?post=<?=$val['order_id']?>&action=edit"><?=$val['order_id']?></a></td>
                    <td><?=$val['coupon_id']?></td>
                    <td><a href="/wp-admin/post.php?post=<?=$val['coupon_id']?>&action=edit"><?=$val['coupon']?></a></td>
                    <td><?=$val['email']?></td>
                    <td><?=$val['count_completed']?></td>
                    <td>$ <?= number_format($val['rewards_earned'], 2, ',', ' ') ?></td>
                    <td><?=$val['type']?></td>
                    <td><?=$val['data']?></td>
                </tr>
            <?php $rewards_earned += $val['rewards_earned']?>
            <?php $count += $val['count_completed']?>
            <?php endforeach; ?>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th><?=$count?></th>
                <th>$ <?= number_format($rewards_earned, 2, ',', ' ') ?></th>
                <th></th>
                <th></th>
            </tr>
        </table>

        <?php
    }

    /**
     * @param $wpdb
     * @param $coupon_name
     * @return float|string
     */
    public static function revards_earned($coupon_name)
    {
        global $wpdb;
        $count = $wpdb->get_col("
            SELECT order_id from wp_woocommerce_order_items o
            JOIN wp_posts p on (o.order_id  = p.ID)
            WHERE order_item_name = '{$coupon_name}'
            ");

        $sum = '';
        foreach ($count as $value) {
            $sum += get_post_meta($value, '_order_total')[0];
        }
        $rewards_earned = $sum * self::PERCENT;
        return $rewards_earned;
    }


    /**
     * @param $wpdb
     * @param $coupon_name
     * @return mixed
     */
    public function count_completed($coupon_name)
    {
        global $wpdb;
        $count_completed = $wpdb->get_col("
            SELECT order_id from wp_woocommerce_order_items cc
            JOIN wp_posts p on (cc.order_id  = p.ID)
            WHERE order_item_name = '{$coupon_name}'
        ");
        return $count_completed;
    }

    /**
     * @param $wpdb
     * @return mixed
     */
    public function getValue()
    {
        global $wpdb;
        $res = $wpdb->get_results(
            "SELECT * FROM coupon_customer"
        );

//        $res = $wpdb->get_results(
//            "SELECT cc.*, p.post_title
//                FROM coupon_customer AS cc
//             JOIN wp_posts p on (cc.coupon_id  = p.ID)"
//        );

//        $res = $wpdb->get_results(
//            "SELECT p.post_title, cc.*
//                FROM coupon_customer cc
//                JOIN wp_posts p on (p.ID = cc.coupon_id)
//                JOIN wp_woocommerce_order_items oi
//                      on (p.post_title = oi.order_item_name and oi.order_item_type = 'coupon')"
//        );
        return $res;
    }

    /**
     * @param $res
     * @param $wpdb
     * @return array
     */
    public function getArrayValue($res): array
    {
        $resReport = [];
        foreach ($res as $val) {
            $count_completed = count($this->count_completed($val->coupon));
            $rewards_earned = self::revards_earned($val->coupon);

            if ($count_completed !== 0) {
                $resReport[] = [
                    'order_id'=>$val->order_id,
                    'coupon_id'=>$val->coupon_id,
                    'coupon'=>$val->coupon,
                    'email'=>$val->email,
                    'count_completed'=>$count_completed,
                    'rewards_earned'=>$rewards_earned,
                    'type'=>$val->type,
                    'data'=>$val->data
                ];
            }
        }
        return $resReport;
    }
}
