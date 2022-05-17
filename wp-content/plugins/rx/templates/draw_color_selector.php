<?php

if (strpos($id, 'mirror') !== false) {
    ?>
    <div onclick="<?=$onclick?> ; mirror_select(this);" class="mirror_box mirror_option selection_option text-center">	
    <img width="95" src="/content/plugins/rx/assets/image/mirror_<?=strtolower(str_replace(' ', '', $color))?>.png" 
         data-color-name=<?=$color_data?>
         data-package=<?=$is_package?>
         data-group=<?=$tint?>
         id=<?=$id?>
         data-id=<?=$tint.'_'.$color_data?>
         title="<?=$color?>" /><br/><span style="font-size: 15px;"><?=$color?></span>
    </div>
<?php


} else { ?>
    <div onclick="<?=$onclick?> ; option_select(this);"
         class="selector_check_passive option_select_rx selector_option mirror_option text-center selector_option rx_tooltip"
         style="background: <?=$color_hash?>;"
         data-color-name=<?=$color_data?>
         data-package=<?=$is_package?>
         data-group=<?=$tint?>
         id=<?=$id?>
         data-toggle="tooltip" data-placement="right"
         data-id=<?=$tint.'_'.$color_data?>
         title="<?=$color?>"><span class="tooltiptext"><?=$color?></span>
    </div>


<?php } ?>
