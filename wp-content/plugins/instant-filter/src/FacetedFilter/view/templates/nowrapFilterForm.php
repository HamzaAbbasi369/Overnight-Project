<li class="ong-filter-wrapper" id="<?=$shortcode_id?>">
    <ul class="menu filter--menu-wrap ong-filter">
        <?=$content?>
    </ul>
</li>
<?php if ($draw_products): ?>
    <div id="quick-look-section" class="quick-look-section middle-block-wrap row middle--row shopping--middle-block">
        <?php do_action('filter-form-before-products', $shortcode_id); ?>
        <ul class="products"></ul>
    </div>
<?php endif; ?>
<?php if ($draw_paginator): ?>
    <div class="ong-filter-pagination"></div>
<?php endif;