<?php

use OngStore\Core\Helper\Config;

?>

<li class="filter--ong-filter-item accordion-item" data-accordion-item>
    <?php /** @var Iterator $items */ ?>
    <a class="accordion-title"><?=__('Size', Config::LANG_DOMAIN)?></a>

    <ul class="menu vertical nested filter--menu-wrap-menu filter--ong-filter-group accordion-content size-filter "
        data-tab-content

    >
        <div class="fast-size-filter">
            <ul>
                <li data-value="kids" class="small">kids</li>
                <li data-value="xs">XS</li>
                <li data-value="s">S</li>
                <li data-value="m">M</li>
                <li data-value="l">L</li>
                <li data-value="xl">XL</li>
            </ul>
        </div>
        <!--  Filter Size body  -->
        <div class="filter-size-wrapper"> <!-- have-user-size <- this is class for hide button Remember my size -->
            <div class="slider-container">
                <?php foreach ($group_items as $key => $group_member) {
                    $shortcode->setName($group_member);
                    $values = $shortcode::extractValues($atts, $shortcode::$group, $group_member);
                    if (!empty($values)) {
                        continue;
                    }
                    try {
                        if ($shortcode::isValid($group_member)) {

                            $shortcode_name  = $shortcode->getName();
                            $shortcode_code  = $shortcode->getSlagName();
                            $shortcode_title = $shortcode->getAttributeTitle();

                            ?>
                            <div class="slider-box"
                                 data-filter-type="size"
                                 data-filter-name="<?=$shortcode_name?>"
                                 data-filter-key="<?=$shortcode_code?>"
                            >
                                <!-- <p class="<?=$shortcode_code?>-title size-item-title"><?=$shortcode_title?></p> -->
				<img src="/content/themes/theme/assets/img/size_filter/<?=$shortcode_code?>.jpg" style="width: 130px" />
                                <div id="<?=$shortcode_code?>_slider" class="size-slider"></div>
                                <div class="<?=$shortcode_code?>-slider-inputs size-item-slider-inputs">
                                    <input type="text" id="<?=$shortcode_code?>_minCost" value="0" class="size-of-frame-min"/>
                                    <label for="<?=$shortcode_code?>_minCost" class="label-mm">mm</label>
                                    -
                                    <input type="text" id="<?=$shortcode_code?>_maxCost" value="0" class="size-of-frame-max"/>
                                    <label for="<?=$shortcode_code?>_maxCost" class="label-mm">mm</label>
                                </div> <!-- end box-slider-inputs-->
                            </div> <!-- end slider-box -->
                            <?php
                        }
                    } catch (\Throwable $e) {
                        var_dump($e->getMessage());
                    }
                } ?>
            </div> <!-- end slider-container -->

            <?php //do_action('size-form-after-main-controls', $atts, $group_items, $shortcode); ?>

        </div> <!-- end  filter-size-wrapper -->
    </ul>


</li>
