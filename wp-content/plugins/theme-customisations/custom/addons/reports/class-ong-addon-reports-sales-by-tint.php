<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_sales_by_tint extends Ong_Addon_Reports_sales_date
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

        $tint = [__('Tint', 'woocommerce')];
        $amount = [__('Amount', 'woocommerce')];

        $data  = [
            [$tint, $amount]
        ];

        foreach ($res as $val) {
            $data[] = [$val->tint, intval($val->total_price)];
        }

        wp_enqueue_script('charts');
        wp_enqueue_script('admin_custom.js', plugins_url('/assets/reports_sales_by_tint.js', __FILE__), ['charts']);
        wp_localize_script('admin_custom.js', 'rx_admin_params', [
            'reports_sales_by_tint' => json_encode($data),
        ]);

        echo "<div id=\"chart_div\" style=\"width: 1000px; height: 400px; float: right\"></div>";
    }

    public function writeTable(array $res)
    {
        ?>
        <br> <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px; float: left">
            <tr>
                <th>Tint</th>
                <th>Amount</th>
                <th>Percentage of Total</th>
                <th>Count</th>
                <th>ARPU</th>
            </tr>

            <?php

            $total = '';
            $count = '';
            foreach ($res as $val) {
                $total += $val->total_price;
                $count += $val->count;
            }

            $count_arpu = round(($total / $count), 2);

            foreach ($res as $val) :
                $round_total = round((($val->total_price * 100) / $total), 2);
                $arpu = round(($val->total_price / $val->count), 2);
                ?>
                <tr style="text-align: center;">
                    <td><?= $val->tint; ?></td>
                    <td>$ <?=number_format($val->total_price, 2, ',', ' ') ?></td>
                    <td><?=number_format($round_total, 2, ',', ' ') ?></td>
                    <td><?=$val->count?></td>
                    <td>$ <?=$arpu?></td>
                </tr>

            <?php endforeach; ?>
            <tr style="text-align: center;">
                <th>Total</th>
                <th>$ <?= number_format($total, 2, ',', ' ')?></th>
                <th></th>
                <th><?=$count?></th>
                <th>$ <?=$count_arpu?></th>
            </tr>
        </table>

        <?php
    }

    public function getValue()
    {
        global $wpdb;
        $getWere = static::getWere();

        $res = $wpdb->get_results(
            "SELECT
                count(ltint) as count,
                gerTint(ltint) AS tint,
                    sum(discounted_price) AS total_price
                    FROM sales_results_details
                    WHERE  {$getWere}
                        GROUP BY tint
                        ORDER BY total_price DESC;"
        );

        return $res;
    }

    public function getCsv() {
//        global $wpdb;
//        $res = $this->getValue($wpdb);

//        header("Content-disposition: attachment; filename=1111.txt");
//        header('Content-type: text/plain');
//
//        echo "'это первая строка скачиваемого файла \r\n";
//        echo " это ВТОРАЯ строка скачиваемого файла";


//        header("Content-type: text/csv");
//        header("Content-Disposition: attachment; filename=file.csv");
//        header("Pragma: no-cache");
//        header("Expires: 0");
//
//        $array = [
//            0 => ['Nikolay','nikolay@email.ru'],
//            1 => ['Maria', 'maria@email.ru']
//        ];
//
//        $out = fopen('php://output', 'w');
//
//        foreach ($array as $item)
//        {
//            fputcsv($out, [$item[0],$item[1]]);
//        }
//
//        fclose($out);






//        $row[] = ['Order Date; Frame Price; Lenses Price; Rush Price; Total Discount; Total Price'];
//        foreach ($res as $val){
//            $row[] = [
//            $val->order_date . ';' .
//            $val->frame_price . ';' .
//            $val->lens_price . ';' .
//            $val->rush_price . ';' .
//            round($val->discount_applied, 2) . ';' .
//            $val->total_price
//            ];
//        }

//        echo "Order Date; Frame Price; Lenses Price; Rush Price; Total Discount; Total Price \r\n";
//        foreach ($res as $val) {
//            echo $val->order_date . ";" .
//            $val->frame_price . ";" .
//            $val->lens_price . ";" .
//            $val->rush_price . ";" .
//            round($val->discount_applied, 2) . ";" .
//            $val->total_price. " \r\n";
//        }

    }
}
