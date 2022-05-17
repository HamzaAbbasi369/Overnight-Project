<div class="filter-menu text-center ong-filter-wrapper ong-filter-mobile" id="<?=$shortcode_id?>" data-sticky-container>

    <div id="filter--menu-button-wrap" class="filter--menu-small-wrap icon-top show-for-small-only">
        <button class="button filter--menu-button" type="button" data-toggle="example-dropdown">
            <i class="fi-list"></i>
            Filter by:
        </button>
        <div class="dropdown-pane callout ong-filter-dropdown-pane" id="example-dropdown" data-dropdown data-auto-focus="true" data-close-on-click="true" data-allow-all-closed>
            <div class="mobile-menu-accordion show-for-small-only">
                <div class="callout">
                    <button class="close-button" aria-label="Close alert" type="button" data-close>
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <p>REFINE YOUR SEARCH</p>
                </div>
                <ul class="accordion filter--menu-wrap ong-filter" data-menu-id="mobileMenuFilter" data-accordion data-allow-all-closed="true">
                    <?=$content?>
                    <?php if ($sort_controls) :?>
                        <?=$sort_controls?>
                    <?php endif; ?>
                    <li>
                        <a class="button filter-button" type="button" data-close="example-dropdown">Filter</a>
                    </li>
                </ul>

            </div>
        </div>
    </div>

    <div class="container-result-filter">
        <p class="current-filter">Current filter <i class="fa fa-arrow-right" aria-hidden="true"></i></p>
        <ul class="menu filter-result-wrap"></ul>
        <div class="btn-wrap">
            <button class="clear-all" id="clearAll">Clear ALL</button>
        </div>
    </div> 

    <?php if ($draw_paginator): ?>
    <div class="ong-filter-pagination-mobile ong-filter-pagination"></div>
    <?php endif; ?>

    <?php if ($draw_products): ?>
    <div id="quick-look-section" class="quick-look-section middle-block-wrap row middle--row shopping--middle-block">
        <?php do_action('filter-form-before-products', $shortcode_id); ?>
        <ul class="products"><?=$products?></ul>
    </div>
    <?php endif; ?>

</div>
