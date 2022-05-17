<?php
/** @var string $name */
/** @var integer $id */
/** @var string $action */
/** @var string $image */
/** @var string $title */
/** @var string $icon */
/** @var string $description */
/** @var string $diamond */
/** @var string $extra */
/** @var boolean $checked */
/** @var array $data */

include WcRx::plugin_path().'/includes/rx_fees.php';
$pfactor = isset($_REQUEST['pfactor']) ? $_REQUEST['pfactor'] : 1;

$formatted_data = '';
$is_on_sale = false;
if (!empty($data) && is_array($data)) {
    if (!empty($data['sale_price']) && !empty($data['price']) && intval($data['price'] > intval($data['sale_price']))) {
	    $is_on_sale = true;
    }

    $flatten_data = flattenWithKeys($data,'-','data-');

    $formatted_data = array_reduce(
        array_keys($flatten_data),                       // We pass in the array_keys instead of the array here
        function ($carry, $key) use ($flatten_data) {    // ... then we 'use' the actual array here
            return $carry . ' ' . $key . '="' . htmlspecialchars( $flatten_data[$key] ) . '"';
        },
        ''
    );
}

$sale_html = '';
$sale_class = '';
if ($is_on_sale) {
	$sale_html .= '<div class="parent-sale"><span class="onsale">Sale!</span></div>';
	$sale_class .= ' selector-is-on-sale';
}

if($description == 'Blue Armor &reg;'){
    $name_diamond = 'to Blue Diamond Anti-Glare Coating';
} else {
    $name_diamond = 'to Diamond Anti-Glare Coating';
}

if (get_option('is_show_diamond_anti_glare_coating_rx') === 'yes') {
    $diamond = "<div class='wrapper-diamond' style='display: none'>
            <img src='/content/plugins/rx/assets/image/diamond-coatings-ito-coatings.png' alt='' class='diamond-logo'>
            <p class='title-upgrade'>Upgrade:
                <i class='fa fa-check'></i>
            </p>
            <button class='content-diamond' onclick='rx.diamond.change();'>
                <p class='diamond-content'>$name_diamond<span class='red-price'>".to_price($diamond_fee, $pfactor)."</span></p>
            </button>
        </div>";
} else {
    $diamond = '';
}
?>

<!--LENS TYPE-->
<div class="selector-wrapper  <?php if(!empty($extraclass) || $extraclass !=''){ echo $extraclass; } ?>">
	<?php if (!empty($image)):?><img src='/content/plugins/rx/assets/image/<?=$image?>' class="selector_image" ><?php endif;?>
    <div data-group="<?=$name?>_tile" class="selector_tile <?=$name?>-selector-tile<?=$sale_class?>" id="div_<?=$id?>_tile" <?php if (!empty($style) || $style !='') { ?> style="<?=$style?>" <?php } ?>>
        <div data-group="<?=$name?>" class="selector <?=$name?>-selector" id="div_<?=$id?>" <?=$formatted_data?>
        <?php if (!empty($action)): ?>
            onclick="<?=$action?>"
        <?php endif; ?> >
	
	    <?php if ($title != "Clear Lens No Tint") { ?>


            <div data-group="check_<?=$name?>" class="selector_check <?=($checked?'option_selected':'selector_check_passive')?>  <?=($icon?'on_text_tile':'on_tile')?> center-block"
                 <?php if (!empty($image)):?>
                 data-order-lens-design-img="<?= WcRx::get_assets_url() ?>image/<?=$image?>"
                 <?php endif;?>
                 id="div_<?=$id?>_check"

                    <?php //onclick="--><?//=$action ?>
            >
            </div>

	   <?php } else { ?>
            <div data-group="check_<?=$name?>" data-open="RxTooltip_clear" aria-controls="RxTooltip_clear" aria-haspopup="true" class="selector_check <?=($checked?'option_selected':'selector_check_passive')?>  <?=($icon?'on_text_tile':'on_tile')?> center-block"
                 <?php if (!empty($image)):?>
                 data-order-lens-design-img="<?= WcRx::get_assets_url() ?>image/<?=$image?>"
                 <?php endif;?>
                 id="div_<?=$id?>_check"

                    <?php //onclick="--><?//=$action ?>
            >
            </div>

	   <?php } ?>

        </div>

        <p class="rx-product-name"><?=$title?>
            <?php if (!empty($tooltip) || $tooltip !='') { ?><a href="#" class="popup-rx-tooltip" data-open="RxTooltip_<?=$id?>"></a> <?php } ?>
        </p>
        <p class="rx-product-description"><?=$description?></p>	
		<?php if (!empty($submenu) || $submenu !='') { ?><div class="rx-product-price"><?=$submenu?></div> <?php } ?>
       
	<?php if ($title != "Clear Lens No Tint") { ?> 
        <div class="rx-product-icon">
            <?=$icon?>
        </div>
	<?php } ?>
        <div id="div_<?=$id?>_l2" class="l2_select lens_tint_color rx-extra-section_div_<?=$id?>" style="display: none">
            <div id="div_<?=$id?>_l2_content"><?=$extra?></div>
            <?=$diamond?>
        </div>

        <div class="reveal" id="RxTooltip_clear" data-reveal>
            <button class="close-button" data-close="" aria-label="Close modal" type="button" onClick="clear_close();">
                <span aria-hidden="true">×</span>
            </button>
                <p class='popup-rx-title'>Clear Lens No Tint</p>
                <hr class='gold-line'>
                <div class='popup-rx-text'>Please note this lens is only recommended for backup and does not have Anti-Glare</div>
        </div>


        <div class="reveal" id="RxTooltip_<?=$id?>" data-reveal>
            <button class="close-button" data-close="" aria-label="Close modal" type="button">
                <span aria-hidden="true">×</span>
            </button>
                <p class='popup-rx-title'><?=$title?></p>
                <hr class='gold-line'>
                <div class='popup-rx-text'><?=$tooltip ?></div>
        </div>
	
        <?=$sale_html?>
    </div>
</div>

<script>
    jQuery('.selector-wrapper').foundation();
</script>
