<?php

use OngStore\Core\Helper\Config;

?>

<li class="filter--ong-filter-item accordion-item" data-accordion-item>
    <?php /** @var Iterator $items */ ?>
    <a class="accordion-title"><?=__($shortcode_title, Config::LANG_DOMAIN)?></a>

    <ul class="menu vertical nested filter--menu-wrap-menu filter--ong-filter-group accordion-content"
        data-tab-content
        data-filter-type="pa_taxonomy"
        data-filter-name="<?=$shortcode_name?>"
        data-filter-key="<?=$shortcode_code?>"
    ></ul>
</li>
