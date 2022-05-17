<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

abstract class Ong_Addon_Reports_sales_date extends WC_Admin_Report
{

    public static $fileCsv = 'sales_by_package.csv';
    protected $res;

    public function __construct()
    {

        $this->res = $this->getValue();
        if (empty($this->res)) {
            echo '<h2>There is no data in the report!</h2>';
            return false;
        }

        return true;
    }

    abstract public function getValue();

    public static function getWere($column = 'data')
    {
        if (!isset($_GET['data'])) {
            $_GET['data'] = 'last_month';
        }
        $where = '';
        switch ($_GET['data']) {
            case 'this_week':
                $where = "YEARWEEK({$column}) = YEARWEEK(CURRENT_DATE())";
                break;

            case 'last_week':
                $where = "YEARWEEK({$column}) = YEARWEEK(DATE_SUB(CURRENT_DATE(), INTERVAL 1 WEEK))";
                break;

            case 'last_7_days':
                $where = "{$column} > DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY) ";
                break;

            case 'this_month':
                $where = "EXTRACT(YEAR_MONTH FROM {$column}) = EXTRACT(YEAR_MONTH FROM DATE_SUB(CURRENT_DATE(), INTERVAL 0 MONTH))"; // This Month
                break;

            case 'last_month':
                $where = "EXTRACT(YEAR_MONTH FROM {$column}) = EXTRACT(YEAR_MONTH FROM DATE_SUB(CURRENT_DATE(), INTERVAL 1 MONTH))"; // This Month
                break;
        }
        return $where;
    }

    public static function writeButton()
    {
        $URI = get_site_url() . $_SERVER['REQUEST_URI'];

        if (!isset($_GET['data'])) {
            $_GET['data'] = 'last_month';
        }

        $this_week = $last_week = $last_7_days = $this_month = $last_month = '';
        $style = " active";
        switch ($_GET['data']) {
            case 'this_week':
                $this_week = $style;
                break;
            case 'last_week':
                $last_week = $style;
                break;
            case 'last_7_days':
                $last_7_days = $style;
                break;
            case 'this_month':
                $this_month = $style;
                break;
            case 'last_month':
                $last_month = $style;
                break;
            default:
                $last_month = $style;
        }
        ?>

        <br>
        <a href="<?=add_query_arg(['data' => 'this_week'], $URI)?>" class="button<?=$this_week?>">This Week</a>
        <a href="<?=add_query_arg(['data' => 'last_week'], $URI)?>" class="button<?=$last_week?>">Last Week</a>
        <a href="<?=add_query_arg(['data' => 'last_7_days'], $URI)?>" class="button<?=$last_7_days?>">Last 7 days</a>
        <a href="<?=add_query_arg(['data' => 'this_month'], $URI)?>" class="button<?=$this_month?>">This Month</a>
        <a href="<?=add_query_arg(['data' => 'last_month'], $URI)?>" class="button<?=$last_month?>">Last Month</a>
<!--        <a href="--><?//= THEME_CUSTOMIZATION_URL ?><!--/custom/addons/reports--><?//=static::$fileCsv?><!--" class="button"-->
<!--           style="float: right" download="">Export CSV</a>-->
        <br><br>
        <?php
    }
}
