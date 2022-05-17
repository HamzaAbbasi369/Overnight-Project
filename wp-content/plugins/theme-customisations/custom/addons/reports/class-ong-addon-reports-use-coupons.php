<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_use_coupons extends Ong_Addon_Reports_sales_date
{

    public function __construct()
    {

        static::writeButton();
        if (parent::__construct()) {
            static::getWere();
            $this->writeChart($this->res);
            $this->writeTable($this->res);
        }
    }

    public function writeChart(array $res)
    {

        $type = [__('Coupons', 'woocommerce')];
        $value = [__('Used', 'woocommerce')];

        $data = [
            [$type, $value]
        ];

        foreach ($res as $val) {
            $data[] = [$val->coupon, intval($val->total_price)];
        }

        wp_enqueue_script('charts');
        wp_enqueue_script('admin_custom.js', plugins_url('/assets/reports_use_coupons.js', __FILE__), ['charts']);
        wp_localize_script('admin_custom.js', 'rx_admin_params', [
            'reports_use_coupons' => json_encode($data),
        ]);

        echo "<div id=\"chart_div\" style=\"width: 1000px; height: 400px; float: right\"></div>";
    }

    public function writeTable(array $res)
    {
        ?>
        <br> <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px; float: left;">
            <tr>
                <th>Coupon</th>
                <th>Count</th>
                <th>Discount</th>
                <th>Total price</th>
                <th>Percentage of Total</th>
            </tr>

            <?php
            $total = 0;
            $used_total = 0;
            $discount_total = 0;
            $total_price = 0;

            foreach ($res as $val) {
                $total += $val->total_price;
                $used_total += $val->used;
                $discount_total += $val->discount;
                $total_price += $val->total_price;
            }
            ?>

            <?php foreach ($res as $val) : ?>
                <?php
                $round_total = round((($val->total_price * 100) / $total), 2);
                $style = ($val->coupon == ONG_CLUB_COUPON)  ? 'font-size: 17px; font-weight: 600; color: red;' : '';
                ?>
                <tr style="text-align: center;<?=$style?>">
                    <td><?= $val->coupon ?></td>
                    <td><?= $val->used ?></td>
                    <td>$ <?= number_format($val->discount, 2, ',', ' ') ?></td>
                    <td>$ <?= number_format($val->total_price, 2, ',', ' ') ?></td>
                    <td><?= $round_total ?></td>
                </tr>
            <?php endforeach; ?>

            <tr style="text-align: center;">
                <th></th>
                <th><?= $used_total ?></th>
                <th>$ <?= number_format($discount_total, 2, ',', ' ') ?></th>
                <th>$ <?= number_format($total_price, 2, ',', ' ') ?></th>
                <th></th>
            </tr>
        </table>

        <?php
    }

    public function getValue()
    {
        global $wpdb;
        $getWere = static::getWere('rx.data');
        $ong_coupon = ONG_CLUB_COUPON;
        $res = $wpdb->get_results(
            "SELECT
                      if (not isnull(cc.id),'{$ong_coupon}', rx.coupon_code) as coupon,
                      count(rx.coupon_code) as used,
                      sum(total_price) AS total_price,
                      sum(rx.discount) AS discount
                    FROM rx_step rx
                    LEFT JOIN coupon_customer cc on (rx.coupon_code = cc.coupon)
                      JOIN sales_results sr ON ( sr.rx_step_id = rx.id)
                    WHERE rx.coupon_code IS NOT NULL AND rx.coupon_code !=''AND {$getWere}
                    GROUP BY 1
                    ORDER BY used DESC;"
        );
        return $res;
    }
}
