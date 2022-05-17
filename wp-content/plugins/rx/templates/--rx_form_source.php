<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
$d_mode = (isset($_REQUEST['d_mode'])) ? $_REQUEST['d_mode'] : '';
require(dirname(__FILE__) . '../includes/rx_fees.php');
require(dirname(__FILE__) . '/rx_functions.php');
?>

<link rel="stylesheet" type="text/css" href="<?=WcRx::get_assets_url()?>css/rx_style.css" />
<?php
if ($offer != 'groupon_value') {
    ?>
    <script type="text/javascript" src="<?= WcRx::plugin_url() ?>js/rx_jscript.js"></script>
    <?php
} else {
    ?>
    <script type="text/javascript" src="<?= WcRx::plugin_url() ?>js/rx_jscript_groupon.js"></script>
    <?php
}
?>

<div id="rx_container">
    <div id="rx_center" class="rx_center column">
        <?php
        if ($offer != 'groupon_value') {
            require(dirname(__FILE__) . '/rx_progress.php');

            if ($d_mode != 'fashion') {
                require(dirname(__FILE__) . '/rx_usage.php');
                require(dirname(__FILE__) . '/rx_core.php');
            }
            require(dirname(__FILE__) . '/rx_tint.php');
            if ($d_mode != 'fashion') {
                require(dirname(__FILE__) . '/rx_material.php');
                require(dirname(__FILE__) . '/rx_coating.php');
            }
            require(dirname(__FILE__) . '/rx_review.php');
        } else {
            // hardcoding for groupon pilot
            ?>
            <h1 id="progress-step-name"
                style="text-align: center; font-size: 15px; margin-bottom: 20px; margin-top: 15px;">PLEASE ENTER
                PRESCRIPTION</h1>
            <?php
            require(dirname(__FILE__) . '/rx_core.php');
        }

        ?>

        <?php
        if ($offer != 'groupon_value') {
            draw_navigation_control($d_mode);
        } else {
            draw_navbar("nb_step3", "Frame", "rx.navigation.hideRx()", "Add To Cart", "ToCart()");
        }
        ?>
    </div>
    <div id="rx_right" class="column">
        <?php
        require(dirname(__FILE__) . '/rx_package_data.php');
        ?>
    </div>
    <div style="display: block; clear: both;"></div>
</div>
