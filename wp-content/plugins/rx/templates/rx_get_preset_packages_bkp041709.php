<?php
/** @var float $usage_reading */
/** @var float $usage_distance */
/** @var float $usage_bifocal */
/** @var float $usage_progressive */
/** @var float $impact_resistant_fee */
/** @var float $tint_sun_tint */
/** @var float $tint_polarized */
/** @var float $tint_lr_p */
/** @var float $tint_lr_t */
/** @var float $tint_lr_xtr_a */
/** @var float $rush_service_fee */
/** @var float $rush_service_ge150_fee */
/** @var float $easy_clean_fee */
/** @var float $anti_glare_fee */
/** @var float $premium_anti_glare_fee */
/** @var float $computer_anti_glare_fee */
/** @var float prism_fee */
include WcRx::plugin_path() . '/includes/rx_fees.php';
require_once(dirname(__FILE__) . '/rx_functions.php');
global $wpdb;

$rxtype              = (!empty($_REQUEST['rxtype']) ? $_REQUEST['rxtype'] : null);
$impact_resistant    = (!empty($_REQUEST['impact_resistant']) ? $_REQUEST['impact_resistant'] : null);
$material            = (!empty($_REQUEST['material']) ? $_REQUEST['material'] : null);
$premium             = (!empty($_REQUEST['premium']) ? $_REQUEST['premium'] : null);
$pricetoremove       = (!empty($_REQUEST['pricetoremove']) ? $_REQUEST['pricetoremove'] : 0);
$rx_progressive_type = (!empty($_REQUEST['rx_progressive_type']) ? $_REQUEST['rx_progressive_type'] : null);

$rx_strength          = (!empty($_REQUEST['strength']) ? $_REQUEST['strength'] : 0);

$need167andTrivexTabs = ($rx_strength <= 4);
$need174Tabs = ($rx_strength >= 6.1) && ($rx_strength <= 8);
$noTabs = !$need167andTrivexTabs && !$need174Tabs;


$usage_fee = 0;
$impact_fee = 0;
if ( $rxtype == 'Single Vision Reading' || $rxtype == 'Single Vision Distance') {
    $rxtype    = "Single Vision";
    $usage_fee = $usage_reading;
}
if ( $rxtype == "Bifocal") {
    $rxtype    = "Bifocal";
    $usage_fee = $usage_bifocal;
}
if ( $rxtype == "Progressive") {
    $rxtype    = "Progressive Freeform";
    $usage_fee = $usage_progressive;
}

$materialSQL  = '';
$recommendSQL = ' recommend="Y" ';
$min_maxSQL   = sprintf(' and rx_min<= %1$g and rx_max>= %1$g ', $rx_strength) ;
$rxtypeSQL   = sprintf(' and rx_type="%1$s"  ', $rxtype) ;

if (empty($premium) || $premium == 'false') {
    $premium = 'Premium';
}
$premiumSQL = sprintf(' and design="%s"', $premium);

$rx_progressive_typeSQL = '';
if (!empty($rx_progressive_type) ) {
    $rx_progressive_typeSQL = sprintf(' and rx_progressive_type="%s"', $rx_progressive_type);
}




if ($impact_resistant == 1) {
    $materialSQL = 'and ( material like "Polycarbonate%" or material like "Trivex%" ) ';
    $recommendSQL = '';
    $min_maxSQL = '';
    $impact_fee = $impact_resistant_fee;

    $sql = sprintf('SELECT package_id, package_name, package_description, package_option_name, material, 
b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend,
CASE TRUE
WHEN material = "Trivex HD Lenses Package" THEN "last"
ELSE "first"
END AS tabs
FROM rx_packages a, rx_package_options b 
WHERE a.package_option_id=b.package_option_id 
%1$s 
%2$s
%3$s
%4$s
ORDER BY package_name',
        $rxtypeSQL,
        $materialSQL,
        $rx_progressive_typeSQL,
        $premiumSQL
    );
    $sql_tabs='CASE TRUE
WHEN material = "Trivex HD Lenses Package" THEN "last"
ELSE "first" 
END AS tabs';
} else {
    if ($need174Tabs) {
        $materialSQL = '(material = "1.74 Ultra Thin Lenses Package")';
        $sql = sprintf('SELECT package_id, package_name, package_description, package_option_name, material, 
b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend,
CASE TRUE
WHEN (material = "1.74 Ultra Thin Lenses Package") THEN "middle"
ELSE "first"
END AS tabs
FROM rx_packages a, rx_package_options b 
WHERE a.package_option_id=b.package_option_id 
%1$s
AND ((%2$s %4$s) OR %3$s)
%5$s
%6$s
ORDER BY package_name',
            $rxtypeSQL,
            $recommendSQL,
            $materialSQL,
            $min_maxSQL,
            $rx_progressive_typeSQL,
            $premiumSQL
        );
        $sql_tabs='CASE TRUE
WHEN (material = "1.74 Ultra Thin Lenses Package") THEN "middle"
ELSE "first"
END AS tabs';
    } elseif ($need167andTrivexTabs){
	echo "1.67 and TRIVEX   ";
	if ($rx_strength <= 2.50) {
        	$materialSQL = '(material in ("Trivex HD Lenses Package", "1.67 Super Thin Lenses Package","1.50 Lenses Package"))';
	} else {
		$materialSQL = '(material in ("Trivex HD Lenses Package", "1.67 Super Thin Lenses Package"))';
	}

        $sql = sprintf('SELECT package_id, package_name, package_description, package_option_name, material, 
b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend,
CASE TRUE
WHEN (material = "1.67 Super Thin Lenses Package") THEN "middle"
WHEN (material = "Trivex HD Lenses Package") THEN "last"
ELSE "first"
END AS tabs
FROM rx_packages a, rx_package_options b 
WHERE a.package_option_id=b.package_option_id AND a.package_option_id != 99
%1$s
AND ((%2$s %4$s) OR %3$s)
%5$s
%6$s
ORDER BY package_name',
            $rxtypeSQL,
            $recommendSQL,
            $materialSQL,
            $min_maxSQL,
            $rx_progressive_typeSQL,
            $premiumSQL
        );
        //$sql_tabs='CASE TRUE
//WHEN (material = "1.67 Super Thin Lenses Package") THEN "middle"
//WHEN (material = "Trivex HD Lenses Package") THEN "last"
//ELSE "first"
//END AS tabs';
    } else { // it $noTabs
	echo "NOTABS";

	$materialSQL = '(material in ("1.67 Super Thin Lenses Package"))';

	$sql_tabs='CASE TRUE
WHEN (material = "1.67 Super Thin Lenses Package") THEN "middle"
ELSE "first"
END AS tabs';
        $sql = sprintf('SELECT package_id, package_name, package_description, package_option_name, material,
    b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend,
    %7$s    
    FROM rx_packages a, rx_package_options b
    WHERE a.package_option_id=b.package_option_id AND a.package_option_id != 99
    %1$s
    AND ((%2$s %4$s) OR %3$s)
    %5$s
    %6$s
    ORDER BY package_name',
            $rxtypeSQL,
            $recommendSQL,
	    $materialSQL,
            $min_maxSQL,
            $rx_progressive_typeSQL,
            $premiumSQL,
            $sql_tabs
        );
        $sql_tabs='"first" AS tabs';
    }
}

$result = $wpdb->get_results($sql, ARRAY_A);

$materials = array();

while (list($key, $res) = each($result)) {

    if(!in_array( $res['material'] , $materials, true)){
        array_push($materials,  $res['material']);
    }
}

$materials = join("','",$materials);

$sql_add = "SELECT package_id, package_name, package_description, package_option_name, material,
    b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend, ".
    $sql_tabs." , a.package_option_id "
    ." FROM rx_packages a, rx_package_options b
    WHERE a.package_option_id=b.package_option_id AND a.package_option_id=99 $rxtypeSQL $rx_progressive_typeSQL $premiumSQL
    AND material IN ('$materials','Polycarbonate Lenses Package') $min_maxSQL UNION SELECT package_id, package_name, package_description, package_option_name, material, b.anti_glare coating,b.easy_clean, tint, tint_options, price, sale_price, b.uv_protection, b.scratch_protection, recommend, $sql_tabs , a.package_option_id FROM rx_packages a, rx_package_options b WHERE a.package_option_id=b.package_option_id AND a.package_option_id=100 $rxtypeSQL $min_maxSQL ORDER BY PACKAGE_OPTION_ID, package_name ASC";
$result_add = $wpdb->get_results($sql_add, ARRAY_A);




print_r($sql);
//print_r($sql_add);
/**/

function printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, $tab_key = null)
{
    ob_start();
    drawPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, $tab_key);
    return ob_get_clean();
}

/**
 * @param $usage_fee
 * @param $impact_fee
 * @param $pfactor
 * @param $rxtype
 */
function drawPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, $tab_key = null)
{
    ob_start();
    $i = 0;
    reset($result);
    
    while (list($key, $res) = each($result)) {
        if (isset($tab_key) && $res['tabs'] !== $tab_key) {
            continue;
        }
	$i++;
        $style = "height:125px;";
        $tooltip = "";
        $package_price = $res['price'] - $usage_fee - $impact_fee - $pricetoremove;
//        var_dump($res['price'],$usage_fee,$impact_fee,$pricetoremove);
        $sale_price = (!empty($res['sale_price']) ? $res['sale_price'] - $usage_fee - $impact_fee - $pricetoremove: null);
        $html = "";

        $image_file = WcRx::get_assets_url() . 'image/PremiumFeatures_ic.svg';
        $image_info = "<p class='image-info'>Includes Premium Features</p>";
        $desc =  str_replace('%meterial%', $res['material'], carbon_get_theme_option('i_popup_for_rx_package_office_blue_light_defense'));
        $html .= get_feature_icon($desc, $image_file) . $image_info;



        $l2 = "#div_rx_ps" . $res['package_id'] . "_l2";

        $submenu = "<p id='" . "rx_ps" . $res['package_id'] . "_price'>+ " .
            (!empty($sale_price)
                ? sprintf('<span class="old-price">%1$s</span> <span class="new-price">%2$s</span>', to_price($package_price, $pfactor), to_price($sale_price, $pfactor))
                : to_price($package_price, $pfactor)) .
            "</p> ";

        #$desc =  str_replace('%meterial%', $res['package_description'], carbon_get_theme_option('i_popup_for_rx_package_for_other'));
        $tooltip .= draw_tag('li class="rx-product-description"', $res['package_description']);

//        if (array_key_exists('tint_options', $res) && $res['tint_options'] == 'blue armor') {
//            $tooltip = $tooltip . draw_tag('li class="rx-product-description"', $desc);
//            $image_file = WcRx::get_assets_url() . 'image/Blue-Light-glasses.png';
//            $desc =  str_replace('%meterial%', $res['material'], carbon_get_theme_option('i_popup_for_rx_package_office_blue_light_defense'));
//            $html .= get_feature_icon($desc, $image_file);
//
//        }
//        if (array_key_exists('tint', $res) && $res['tint'] == 'Transitions &reg;') {
//            $image_file = WcRx::get_assets_url() . "image/Outdoors-indoors-glasses.png";
//            $desc =  str_replace('%meterial%', $res['material'], carbon_get_theme_option('i_popup_for_rx_package_indoors_outdoors'));
//            $tooltip = $tooltip . draw_tag('li class="rx-product-description"', $desc);
//            $html .= get_feature_icon($desc, $image_file);
//        }
//        if (array_key_exists('tint', $res) && $res['tint'] == 'Polarized') {
//            $image_file = WcRx::get_assets_url() . 'image/Polarized-glasses.png';
//            $desc =  str_replace('%meterial%', $res['material'], carbon_get_theme_option('i_popup_for_rx_package_sun_protection'));
//            $tooltip = $tooltip . draw_tag('li class="rx-product-description"', $desc);
//            $html .= get_feature_icon($desc, $image_file);
//        }
//        if (array_key_exists('coating', $res) && $res['coating'] == 'Standard') {
//            $image_file = WcRx::get_assets_url() . 'image/Anti-Glare-glasses.png';
//            $desc =  str_replace('%meterial%', $res['material'], carbon_get_theme_option('i_popup_for_rx_package_indoors'));
//            $tooltip = $tooltip . draw_tag('li class="rx-product-description"', $desc);
//            $html .= get_feature_icon($desc, $image_file);
//        }

        draw_text_selector(
            "rx_ps" . $res['package_id'],
            $tooltip,
            $res['package_name'],
            "material",
            null,
            $html,
            $res['tint'],
            $submenu,
            $style,
            null,
            print_color_select('', '', '', $res['tint']),
            [
                'l2id' => $l2,
                'rxtype' => $rxtype,
                'material' => $res['material'],
                'price' => $package_price,
                'sale_price' => $sale_price,
                'tint' => $res['tint'],
                'coating' => $res['coating'] . ' Anti-Glare',
                'easy_clean' => $res['easy_clean'],
                'uv_protection' => $res['uv_protection'],
                'scratch_protection' => $res['scratch_protection']
            ]
        );

        if ($i == 4) {
            echo "</div> <!--close row -->";
            echo "<div>";
            $i = 0;
        }
    }

    $content = ob_get_clean();
    if ($content) {
        echo "<div>{$content}</div>";
    }
}
?>

<?php if ($result) : ?>
    <?php     if ($need174Tabs): ?>
        <ul class="tabs" data-tabs id="example-tabs" style='border: none; margin-top: 17px' >
            <?php
            $contentFirstTab    = printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'first');
	    $contentMiddleTab   = printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'middle');
	    ?>

            <?php if($contentFirstTab): ?>
                <li class="tabs-title is-active" onClick="panel1();">
                    <a class='rx-product-name-package' href="#panel-tabs-1" aria-selected="true">Standard lenses</a>
                    <img src="<?= WcRx::get_assets_url() ?>/image/Standart.png" alt="Standart">
                </li>
            <?php endif;?>

            <?php if($contentMiddleTab): ?>
                <li class="tabs-title" onClick="panel2();">
                    <a class='rx-product-name-package' data-tabs-target="panel-tabs-2" href="#panel-tabs-2">Thinner lenses</a>
                    <img src="<?= WcRx::get_assets_url() ?>/image/Index.png" alt="Index">
                </li>
            <?php endif;?>
        </ul>

        <div class="tabs-content" data-tabs-content="example-tabs">

            <?php if ($contentFirstTab) :?>
                <div class="tabs-panel is-acti-ve" id="panel-tabs-1">
                    <p class="rx-product-info-package">Standard recommendation for your prescription. Balanced lens thickness and optical characteristics.</p>


                    <div class="package_frame  std_pack_list">
                        <?=$contentFirstTab?>
                    </div>

                    <div class="package_frame special_pack_list">
                        <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'first'); ?>
                    </div>
                    </div>
                </div>
            <?php endif;?>

            <?php if ($contentMiddleTab) :?>
                <div class="tabs-panel" id="panel-tabs-2">
                    <p class="rx-product-info-package">1.74 Ultra Thin Lenses Package recommended for your
                        prescription.</p>

                    <div class="package_frame  std_pack_list">
                        <?=$contentMiddleTab?>
                    </div>
                    <div class="package_frame  special_pack_list">
                        <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'middle'); ?>
                    </div>
                </div>
            <?php endif;?>

        </div>
    <?php elseif ($need167andTrivexTabs) : ?>
        <ul class="tabs" data-tabs id="example-tabs" style='border: none; margin-top: 17px' >
        <?php
        $contentFirstTab    = printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'first');
        $contentMiddleTab   = printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'middle');
        $contentLastTab     = printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'last');
        ?>

        <?php if($contentFirstTab): ?>
        <li class="tabs-title is-active" onClick="panel1();">
            <a class='rx-product-name-package' href="#panel-tabs-1" aria-selected="true">Standard lenses</a>
            <img src="<?= WcRx::get_assets_url() ?>/image/Standart.png" alt="Standart">
        </li>
        <?php endif;?>

        <?php if($contentMiddleTab): ?>
            <li class="tabs-title" onClick="panel2();">
                <a class='rx-product-name-package' data-tabs-target="panel-tabs-2" href="#panel-tabs-2">Thinner lenses</a>
                <img src="<?= WcRx::get_assets_url() ?>/image/Index.png" alt="Index">
            </li>
        <?php endif;?>

        <?php if($contentLastTab): ?>
        <li class="tabs-title" onClick="panel3();">
            <a class='rx-product-name-package' data-tabs-target="panel-tabs-3" href="#panel-tabs-3">Best lenses</a>
            <img src="<?= WcRx::get_assets_url() ?>/image/Trivex.png" alt="Trivex HD">
        </li>
        <?php endif;?>

    </ul>

    <div class="tabs-content" data-tabs-content="example-tabs">

        <?php if ($contentFirstTab) :?>
        <div class="tabs-panel is-active" id="panel-tabs-1">
            <p class="rx-product-info-package">Standard recommendation for your prescription. Balanced lens thickness and optical characteristics.</p>
            <div class="package_frame std_pack_list">
                <?=$contentFirstTab?>
            </div>
            <div class="package_frame special_pack_list">
                <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'first'); ?>
            </div>
        </div>
        <?php endif;?>

        <?php if ($contentMiddleTab) :?>
        <div class="tabs-panel" id="panel-tabs-2">
            <p class="rx-product-info-package">1.67 High index are the thinnest lenses recommended for your prescription.</p>
            <div class="package_frame  std_pack_list">
                <?=$contentMiddleTab?>
            </div>
            <div class="special_pack_list">
                <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'middle'); ?>
            </div>
        </div>
        <?php endif;?>

        <?php if ($contentLastTab) :?>
        <div class="tabs-panel" id="panel-tabs-3">
            <div class="panel-tabs-3-content">
    <!--                <img src="--><?//= WcRx::get_assets_url() ?><!--/image/Trivex.jpg" alt="Trivex HD" class="trivex-logo">-->
                <p class="rx-product-info-package">Trivex HD - The best lenses on the market for your prescription. Light, Shatter Proof and Premium Clarity.</p>

                <div class="std_pack_list">
                    <?=$contentLastTab ?>
                </div>
                <div class="special_pack_list">
                    <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'last'); ?>
                </div>
            </div>
        </div>
        <?php endif;?>

    </div>
    <?php else: ?>
    <div>
        <div class="std_pack_list">
            <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result, $pricetoremove, 'first'); ?>
        </div>
        <div class="special_pack_list">
            <?= printPackages($usage_fee, $impact_fee, $pfactor, $rxtype, $result_add, $pricetoremove, 'first'); ?>
        </div>
    </div>
    <?php endif; ?>
<?php endif; ?>
<!--
    <div class="clearfix">
        <div class="large-12 columns text-center customize-package clear-padding">
            <p class="rx-product-name">OR</p>
            <button type="button" class="btn btn-warning footer" onclick="rx.package.customize()">CUSTOMIZE A LENS PACKAGE</button>
        </div>
    </div>
    -->

<div class="clearfix">
    <div class="large-12 columns text-center customize-package clear-padding">
        <p class="rx-product-name">&nbsp; </p>
        <div type="button" class="btn btn-warning footer switch_std_special">OTHER SPECIALTY PACKAGES</div>
    </div>
</div>

    <script type="text/javascript">
        jQuery(document).trigger('rx-packages-loaded');
    </script>

<script type="text/javascript">
    $('.special_pack_list').hide();
    // check if specialty package available
    if (!$.trim($("#panel-tabs-1 div.special_pack_list").html())) {
	$('.switch_std_special').hide();
    } else {
	$('.switch_std_special').show();
    }

    function panel1() {
	$('#panel-tabs-1').css('display', 'block');
	$('#panel-tabs-2').css('display', 'none');
	$('#panel-tabs-3').css('display', 'none');
        if (!$.trim($("#panel-tabs-1 div.special_pack_list").html())) {
                $('.switch_std_special').hide();
        } else {
                $('.switch_std_special').show();
        }
    }

    function panel2() {
    	$('#panel-tabs-1').css('display', 'none');
        $('#panel-tabs-2').css('display', 'block');
        $('#panel-tabs-3').css('display', 'none');
	
	if (!$.trim($("#panel-tabs-2 div.special_pack_list").html())) {
	        $('.switch_std_special').hide();
    	} else {
        	$('.switch_std_special').show();
    	}
    }

    function panel3() {
	$('#panel-tabs-1').css('display', 'none');
        $('#panel-tabs-2').css('display', 'none');
        $('#panel-tabs-3').css('display', 'block');

        if (!$.trim($("#panel-tabs-3 div.special_pack_list").html())) {
                $('.switch_std_special').hide();
        } else {
                $('.switch_std_special').show();
        }
    }

    console.log('add class special_pkg_b');
    $('.switch_std_special').addClass('special_pkg_b');


    $('.switch_std_special').on('click', function() {
        console.log('Switch Packages');
        if ($('.std_pack_list').is(":visible")){
	    $('.special_pack_list').addClass('special_pkg_brd');
	    $('.switch_std_special').removeClass('special_pkg_b');
            $('.std_pack_list').slideUp();
            $('.special_pack_list').show("slow");
	    $('.rx-product-info-package').hide("slow");
            $('.switch_std_special').html('BACK TO RECOMMENDED PACKAGES');


	    if (!$.trim($("#panel-tabs-2 div.special_pack_list").html())) {
		$(".tabs .tabs-title").eq(1).hide();
	    }

            if (!$.trim($("#panel-tabs-3 div.special_pack_list").html())) {
                $(".tabs .tabs-title").eq(2).hide();
            }
        } else {

            $(".tabs .tabs-title").eq(0).show();
	    $(".tabs .tabs-title").eq(1).show();
	    $(".tabs .tabs-title").eq(2).show();
	 
	    $('.special_pack_list').removeClass('special_pkg_brd');
	    $('.switch_std_special').addClass('special_pkg_b');
	    $('.rx-product-info-package').show();
            $('.special_pack_list').slideUp();
            $('.std_pack_list').show("slow");
            $('.switch_std_special').html('OTHER SPECIALTY PACKAGES');
        }
    });

    

    function clear_close() {
	//console.log('Clear Lens clicked');
	if ($('.wrap-free-packages').is(":visible")) {
		$('.wrap-free-packages').hide();
		$('#c_material li').removeClass('rx-package-free');	
	} 
    }
</script>
