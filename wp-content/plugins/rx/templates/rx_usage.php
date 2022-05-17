<?php
/** @var float $usage_reading */
/** @var float $usage_distance */
/** @var float $usage_bifocal */
/** @var float $usage_nolinebifocal */
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
include WcRx::plugin_path().'/includes/rx_fees.php';
?>
<div id='step1'>
    <div id="c_prescription_type">

        <?php

        $submenu = "";
		$style = "height:125px;";
		$extraclass = "selector_purpose";

		$id = "rdo_sv_reading";
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_single_vision');
		$title = "Single Vision";
        $description = "Near / Reading";
		$action = "";
		$image = "single-vison-lenses-for-near.png";
		$style="background-image: url('/content/plugins/rx/assets/image/".$image."');";
        $submenu = "<b>+&nbsp" . to_price($usage_reading, $pfactor) . "</b>";
        draw_image_selector(
            $id , //id
            $tooltip,
            $title, //title
            "prescription_type", //name
            $action,
            null,  //html
            $description,
            $image ,
            $submenu , //submenu
			null , // size
			null , //checked
			null , //extra
            ['usage' => 'Single Vision Reading'] , //data
			$style,
			$extraclass
        );
/*2*/
		$id = "rdo_sv_distance";
		$title = "Single Vision";
        $description = "Distance";
		$action = "";
		$image = "single-vision-lenses-for-distance.png";
		$style="background-image: url('/content/plugins/rx/assets/image/".$image."');";
        $submenu = "<b>+&nbsp" . to_price($usage_distance, $pfactor) . "</b>";
        draw_image_selector(
            $id , //id
            $tooltip,
            $title, //title
            "prescription_type", //name
            $action,
            null,  //html
            $description,
            $image ,
            $submenu , //submenu
			null , // size
			null , //checked
			null , //extra
            ['usage' => 'Single Vision Distance'] , //data
			$style,
			$extraclass
        );
/*3*/
		$id = "rdo_b_reading";
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_bifocal');
		$title = "Bifocal With Line";
        $description = "Near And Distance";
		$action = "";
		$image = "Bifocal-With-a-Line-For-Near-And-Distance.png";
		$style="background-image: url('/content/plugins/rx/assets/image/".$image."');";
		$submenu = "<b>+&nbsp" . to_price($usage_bifocal, $pfactor) . "</b>";
        draw_image_selector(
            $id , //id
            $tooltip,
            $title, //title
            "prescription_type", //name
            $action,
            null,  //html
            $description,
            $image ,
            $submenu , //submenu
			null , // size
			null , //checked
			null , //extra
            ['usage' => 'Bifocal'] , //data
			$style,
			$extraclass
        );
        /*3.1*/
		$id = "rdo_b_reading_no_line";
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_nolinebifocal');
		$title = "No Line Bifocal";
        $description = "Near And Distance";
		$action = "";
		$image = "no-line-bifocals-for-near-and-distance.png";
		$style="background-image: url('/content/plugins/rx/assets/image/".$image."');";
		$submenu = "<b>+&nbsp" . to_price($usage_nolinebifocal, $pfactor) . "</b>";
        draw_image_selector(
            $id , //id
            $tooltip,
            $title, //title
            "prescription_type", //name
            $action,
            null,  //html
            $description,
            $image ,
            $submenu , //submenu
			null , // size
			null , //checked
			null , //extra
            ['usage' => 'No Line Bifocal'] , //data
			$style,
			$extraclass
        );
/*4*/
		$id = "rdo_p_allday";
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_progressive');
		$title = "Progressive";
        $description = "Near And Distance";
		$action = "";
		$image = "progressive-For-Near-intermediate-distance.png";
		$style="background-image: url('/content/plugins/rx/assets/image/".$image."');";
        $submenu = "<b>+&nbsp" . to_price($usage_progressive, $pfactor) . "</b>";
        draw_image_selector(
            $id , //id
            $tooltip,
            $title, //title
            "prescription_type", //name
            $action,
            null,  //html
            $description,
            $image ,
            $submenu , //submenu
			null , // size
			null , //checked
			null , //extra
            ['usage' => 'Progressive'] , //data
			$style,
			$extraclass
        );
        ?>
    </div>



	<div id="step1_2" style="display: none;">
	 <?php
        draw_image_selector(
            "rdo_sv_reading",
            "",
            "Single Vision",
            "prescription_type",
            "rx.usage.set('Single Vision Reading')",
            null,
            "Near",
            "new_pt_near.jpg",
            ""
        );
        draw_image_selector(
            "rdo_sv_distance",
            "",
            "Single Vision",
            "prescription_type",
            "rx.usage.set('Single Vision Distance')",
            null,
            "Distance",
            "new_pt_distance.jpg",
            ""
        );
        draw_image_selector(
            "rdo_b_reading",
            "",
            "Bifocal",
            "prescription_type",
            "rx.usage.set('Bifocal')",
            null,
            "Near AND Distance",
            "new_1_pt_distance_near.jpg",
            ""
        );
        draw_image_selector(
            "rdo_p_allday",
            "",
            "Progressive",
            "prescription_type",
            "rx.usage.set('Progressive')",
            null,
            "ALL ranges",
            "new_1_pt_progressive.jpg",
            ""
        );
        ?>
	</div>
    <div>
        <p class="rx-product-delimiter">EXTRAS</p>
    </div>
    <div>
        <?php
        $tooltip = carbon_get_theme_option('i_popup_for_rx_lens_type_impact_resistant');
        $title = "Impact Resistant";
        $html = "<b>" . to_price($impact_resistant_fee, $pfactor) . "</b>";
        $action = "rx.impactResistant.change();";
        $id = "impact_resistant";
        $description = 'For Children under 18, sport and active users';
        draw_checkbox($id, $action, $tooltip, $title, $html, $description);
        ?>
    </div>
</div>
