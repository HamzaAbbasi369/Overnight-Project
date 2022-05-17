<div class="filter-menu text-center ong-filter-wrapper" id="<?=$shortcode_id?>" data-sticky-container>

    <ul class="menu filter--menu-wrap ong-filter" data-menu-id="desktopMenuFilter" data-accordion-menu data-multi-open="false" data-close-on-click="true">
        <?=$content?>
        <?php if ($sort_controls) :?>
            <?=$sort_controls?>
        <?php endif; ?>
        <li>
            <!--<a class="filter-button" data-anchor="<?=$shortcode_id?>">Filter</a>-->
        </li>
    </ul>

    <div class="container-result-filter">
        <p class="current-filter">Current filter <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        <ul class="menu filter-result-wrap"></ul>
        <div class="btn-wrap">
            <button class="clear-all" id="clearAll">Clear ALL</button>
        </div>
    </div>

    <?php if ($draw_products): ?>
	    <?php do_action('filter-form-before-products', $shortcode_id); ?>
        <div id="quick-look-section" class="quick-look-section middle-block-wrap row middle--row shopping--middle-block">
            <ul class="products"><?=$products?></ul>
        </div>
    <?php endif; ?>
    <?php if ($draw_paginator): ?>
    <div class="ong-filter-pagination"></div>
    <?php endif; ?>
</div>
