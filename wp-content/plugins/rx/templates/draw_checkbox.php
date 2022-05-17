<!--Impact Resistant-->

<div id="<?=$id?>" class="selector-wrapper">
    <div class="selector_tile">
        <div class="selector_check selector_check_passive ong_checkbox"
            <?php if($group!=false): ?> data-group="<?=$group?>" <?php endif; ?>
             id="div_<?=$id?>_check" onclick=<?=$action?>>
        </div>

        <div>
	    <p class="rx-product-name" id="div_<?=$id?>_check_label">
		<span class="diamond-content"><?=$title ?></span>
                <a href="#" class="popup-rx-tooltip" data-open="RxTooltip_<?=$id?>"></a>
            </p>
            <input type="hidden" value="0" id="div_<?=$id?>_check_state" />
        </div>
        <p class="rx-product-description"><?=$description?></p>
        <p class="rx-product-price"><?=$html?></p>

        <div class="reveal" id="RxTooltip_<?=$id?>" data-reveal>
            <button class="close-button" data-close="" aria-label="Close modal" type="button">
                <span aria-hidden="true">×</span>
            </button>

            <p class='popup-rx-title'><?=$title?></p>
            <hr class='gold-line'>
            <div class='popup-rx-text'>
                <p class="rx-product-description IR_<?=$id?>"><?=$tooltip?></p>
            </div>

        </div>
    </div>
</div>
