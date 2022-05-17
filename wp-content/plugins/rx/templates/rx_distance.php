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
include WcRx::plugin_path().'/includes/rx_fees.php';
?>

<!-- DISTANCE -->
<div id='step170'  style='display: none'>
    <div id="container_distance">
	<img id="office_distances" src="/content/plugins/rx/assets/image/office_distances.jpg" >
	 <?php	
		
		$style = "";
				
        draw_text_selector(
            "4ft",
            '', //tooltip
            '', //"Up to <b>4 foot</b> (1.3 m) of clear vision",
            "rdo_purpose",  //name
            "rx.distance.set('4ft')", //action
			'', //html			
			'Up to <b>4 foot</b> (1.3 m) of clear vision', //description	
			'',//'Up to <b>4 foot</b> (1.3 m) of clear vision', //submenu	
            $style
        );		
        draw_text_selector(
            "6_5ft",
            '', //tooltip
            '',
            "rdo_purpose",  //name
            "rx.distance.set('6_5ft')", //action
			'', //html			
			'Up to <b>6.5 foot</b> (2 m) of clear vision', //description	
			'', //submenu	
            $style
        );
        draw_text_selector(
            "13ft",
            '', //tooltip
            '',
            "rdo_purpose",  //name
            "rx.distance.set('13ft')", //action
			'', //html			
			'Up to <b>13 foot</b> (4 m) of clear vision', //description	
			'', //submenu	
            $style
        );
        draw_text_selector(
            "19ft",
            '', //tooltip
            '',
            "rdo_purpose",  //name
            "rx.distance.set('19ft')", //action
			'', //html			
			'Up to <b>19 foot</b> (6 m) of clear vision', //description	
			'', //submenu	
            $style
        );
	
	
	 
        ?>		
	</div>
</div>