<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

class Ong_Addon_Reports_sales_by_package extends Ong_Addon_Reports_sales_date
{

    public $headerTable = <<<'HTML'
    <tr>
        <th>Order Date</th>
        <th>Frame Price</th>
        <th>Lenses Price</th>
        <th>Rush Price</th>
        <th>Rush service</th>
        <th>Rush % Count</th>
        <th>Total Discount</th>
        <th>Total Price</th>
        <th>Count</th>
        <th>ARPU</th>
    </tr>
HTML;

    public function __construct()
    {
        static::writeButton();

        if (parent::__construct()) {
            static::getWere();
            $val_total = $this->getTotalValue();
            $this->writeChart($this->res);
            $this->writeTable($this->res, $val_total);
        }
    }

    public function writeChart(array $res)
    {
        $OrderDate   = __('Order Date', 'woocommerce');
        $FramePrice = __('Frame Price', 'woocommerce');
        $LensesPrice = __('Lenses Price', 'woocommerce');
        $RushPrice = __('Rush Price', 'woocommerce');
        $TotalPrice = __('Total Price', 'woocommerce');

        $caption  = [
                $OrderDate,
                $FramePrice,
                $LensesPrice,
                $RushPrice,
                $TotalPrice,
        ];
        $data = [];
        foreach ($res as $val) {
            $data[] = [
                    $val->order_date_fotmat,
                    intval($val->frame_price),
                    intval($val->lens_price),
                    intval($val->rush_price),
                    intval($val->total_price),
            ];
        }

        array_unshift($data, $caption);


        wp_enqueue_script('charts');
        wp_enqueue_script('admin_custom.js', plugins_url('/assets/reports_sales_by_package.js', __FILE__), ['charts']);
        wp_localize_script('admin_custom.js', 'rx_admin_params', [
            'reports_sales_by_package' => json_encode($data),
        ]);

        if (isset($data)) {
            echo "<div id=\"chart_div\" style=\"width: 100%; height: 700px; float: right\"></div>";
        }
    }

    public function writeTable(array $res, $val_total)
    {
        ?>
        <br> <br>
        <table cellpadding="10" cellspacing="1" border="1" style="font-size: 15px; width: 100%">

            <?php

            echo $this->headerTable;

            $row[] = ['Order Date; Frame Price; Lenses Price; Rush Price; Total Discount; Total Price'
            ];

            $total = 0;
            $count = 0;
            $rush = 0;
            foreach ($res as $val) {
                $total += $val->total_price;
                $count += $val->count;
                $rush += $val->rush;
            }

            $count_arpu = round(($total / $count), 2);

            foreach ($res as $val) :
                $arpu = round(($val->total_price / $val->count), 2);
                ?>
                <?php $row[] = [
                        $val->order_date . ';' .
                        $val->frame_price . ';' .
                        $val->lens_price . ';' .
                        $val->rush_price . ';' .
                        round($val->discount_applied, 2) . ';' .
                        $val->total_price
                ]; ?>

                <tr style="text-align: center;">
                    <td><?= $val->order_date ?></td>
                    <td>$ <?= number_format($val->frame_price, 2, ',', ' ') ?></td>
                    <td>$ <?= number_format($val->lens_price, 2, ',', ' ') ?></td>
                    <td>$ <?= number_format($val->rush_price, 2, ',', ' ') ?></td>
                    <td><?=$val->rush?></td>
                    <td><?=round(($val->rush * 100) / $val->count, 2)?></td>
                    <td>$ <?= number_format(round($val->discount_applied, 2), 2, ',', ' ') ?></td>
                    <td>$ <?= number_format($val->total_price, 2, ',', ' ') ?></td>
                    <td><?=$val->count?></td>
                    <td>$ <?=$arpu?></td>
                </tr>
            <?php endforeach; ?>
            <tr style="text-align: center;">
                <th>Total</th>
                <th>$ <?= number_format($val_total->frame_price, 2, ',', ' ') ?></th>
                <th>$ <?= number_format($val_total->lens_price, 2, ',', ' ') ?></th>
                <th>$ <?= number_format($val_total->rush_price, 2, ',', ' ') ?></th>
                <th><?=$rush?></th>
                <th><?=round(($rush * 100) / $count, 2)?></th>
                <th>$ <?= number_format(round($val_total->discount_applied, 2), 2, ',', ' ') ?></th>
                <th>$ <?= number_format($val_total->total_price, 2, ',', ' ') ?></th>
                <th><?=$count?></th>
                <th>$ <?=$count_arpu?></th>
            </tr>
            <?=$this->headerTable?>
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
                    DATE(data) AS order_date,
                    DATE_FORMAT(data, '%m/%e (%a)') AS order_date_fotmat,
                    count(*) as count,
                    sum(frame_price) AS frame_price, 
                    sum(lens_price) AS lens_price , 
                    sum(rush_price) AS rush_price, 
                    sum(discount_applied) AS discount_applied, 
                    sum(discounted_price) AS total_price, 
                    sum(lrush) AS rush 
            FROM sales_results_details
                WHERE {$getWere}
                    GROUP BY 1 
                    ORDER BY 1"
        );

        return $res;
    }

    public function getTotalValue()
    {
        global $wpdb;
        $getWere = static::getWere();
        $res = $wpdb->get_row(
            "SELECT 
                    sum(frame_price) AS frame_price, 
                    sum(lens_price) AS lens_price , 
                    sum(rush_price) AS rush_price, 
                    sum(discount_applied) AS discount_applied, 
                    sum(discounted_price) AS total_price
            FROM sales_results_details
                WHERE  {$getWere}"
        );
        return $res;
    }
}
