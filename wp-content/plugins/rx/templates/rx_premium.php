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

global $wpdb;
$sql = 'SELECT * FROM rx_lens_designs order by design';
$result = $wpdb->get_results($sql);
?>

<!-- PURPOSE -->
<div id='step250'  style='display: none'>
    <div id="container_premium">
<?php
if($result){
?>			
		<div>
			<p class="rx-product-delimiter">Recommended Designs</p>
		</div>
		<div>
<?php		
	$style = "height:125px;";
	$tooltip = "";
	$html = '';
		
		
	
	foreach( $result as $key => $row) {
		if($row->recommended == 'Y'){
			$description = $row->description;			
			$submenu = "<p id='" . "rx_premium_" . $row->id."_price'>+ ".
					   (!empty($row->sale_price)
						   ? sprintf('<span class="old-price">%1$s</span> <span class="new-price">%2$s</span>', to_price($row->price, $pfactor), to_price($row->sale_price, $pfactor))
						   : to_price($row->price, $pfactor)) .
					   "</p> ";			
			draw_text_selector(
				"premium_".$row->id,
				$tooltip,
				$row->design,
				"rdo_premium",
				"rx.premium.set('".$row->design."#".$row->price.(!empty($row->sale_price)? "#".$row->sale_price: "")."')", //action
				$html,
				$description,
				$submenu,
				$style,
				null,
				null,
				[
					'price' => $row->price,
					'sale_price' => $row->sale_price
				]
			);
		}
	}
        ?>		

		</div>
		<div>
			<p class="rx-product-delimiter">Popular Branded Designs</p>
		</div>
		<div>
<?php
	foreach( $result as $key => $row) {
		if($row->recommended != 'Y'){
			$description = $row->description;			
			$submenu = "<p id='" . "rx_premium_" . $row->id."_price'>+ ".
					   (!empty($row->sale_price)
						   ? sprintf('<span class="old-price">%1$s</span> <span class="new-price">%2$s</span>', to_price($row->price, $pfactor), to_price($row->sale_price, $pfactor))
						   : to_price($row->price, $pfactor)) .
					   "</p> ";			
			draw_text_selector(
				"premium_".$row->id,
				$tooltip,
				$row->design,
				"rdo_premium",
				"rx.premium.set('".$row->design."#".$row->price.(!empty($row->sale_price)? "#".$row->sale_price: "")."')", //action
				$html,
				$description,
				$submenu,
				$style,
				null,
				null,
				[
					'price' => $row->price,
					'sale_price' => $row->sale_price
				]
			);
		}
	}
?>	
		
		</div>
<?php
}
?>		
	</div>
</div>
