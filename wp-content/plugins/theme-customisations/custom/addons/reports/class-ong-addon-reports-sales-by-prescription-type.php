<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_sales_by_prescription_type extends Ong_Addon_Reports_sales_date
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

        $type = [__('Prescription Type', 'woocommerce')];
        $amount = [__('Amount', 'woocommerce')];

        $data  = [
                [$type, $amount]
        ];

        foreach ($res as $val) {
            $data[] = [$val->type, intval($val->total_price)];
        }

        wp_enqueue_script('charts');
        wp_enqueue_script('admin_custom.js', plugins_url('/assets/reports_sales_by_prescription_type.js', __FILE__ ), ['charts']);
        wp_localize_script('admin_custom.js', 'rx_admin_params', [
            'sales_by_prescription_type' => json_encode($data),
        ]);

        echo "<div id=\"chart_div\" style=\"width: 1000px; height: 400px; float: right\"></div>";
    }

    public function writeTable(array $res)
    {
        ?>
        <br> <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px; float: left">
            <tr>
                <th>Prescription Type</th>
                <th>Amount</th>
                <th>Percentage of Total</th>
                <th>Count</th>
                <th>ARPU</th>
            </tr>

            <?php
//            $row[] = ['Prescription Type; Amount; Percentage of Total'];
            $total='';
            $count = '';

            foreach ($res as $val) {
                $total += $val->total_price;
                $count += $val->count;
            }

            $count_arpu = round(($total / $count), 2);

            foreach ($res as $val) :
                $round_total = round((($val->total_price * 100) / $total), 2);
                $arpu = round(($val->total_price / $val->count), 2);

//                $row[] = [
//                        $val->type . ';' .
//                        $val->total_price . ';' .
//                        $round_total
//                ];
                ?>

                <tr style="text-align: center;">
                    <td><?= $val->type; ?></td>
                    <td>$ <?= number_format($val->total_price, 2, ',', ' ') ?></td>
                    <td><?= number_format($round_total, 2, ',', ' ') ?></td>
                    <td><?=$val->count?></td>
                    <td>$ <?=$arpu?></td>
                </tr>

            <?php endforeach; ?>

            <tr style="text-align: center;">
                <th></th>
                <th>$ <?= number_format($total, 2, ',', ' ') ?></th>
                <th></th>
                <th><?=$count?></th>
                <th>$ <?=$count_arpu?></th>
            </tr>
        </table>
        <?php
//        Ong_String_Helper::ong_create_csv_file($row, dirname(__FILE__) . $this->fileCsv);
    }

    public function getValue()
    {
        global $wpdb;
        $getWere = static::getWere();

        $res = $wpdb->get_results(
            "SELECT
            count(ltype) as count,
            gerType(ltype)  as type,
            sum(discounted_price) as total_price
            FROM sales_results_details
                WHERE  {$getWere}
                    GROUP BY type
                    ORDER BY total_price DESC;"
        );

        return $res;
    }
}
