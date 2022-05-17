<li class="filter--ong-filter-item" id="<?=$shortcode_id.'-'.$shortcode_code?>">
    <?php /** @var Iterator $items */ ?>
    <form class="filter--ong-filter-group" id="header--search-form" action="<?=$action_url?>">
        <input type="search"
               placeholder="<?=$shortcode_title?>..."
               required="required"
               name="filter[if_search][<?=$shortcode_name?>][]"
               value="<?php echo (array_key_exists($shortcode_name, $current_value)) ? esc_html__(stripcslashes(implode(',', $current_value[$shortcode_name]))): ''?>"
               data-filter-type="if_search"
               data-filter-name="<?=$shortcode_name?>"
               data-filter-key="<?=$shortcode_code?>" >
        <input type="hidden" name="x_params" value="<?=$x_params?>">
        <button type="submit" class="icon-header-search"></button>
    </form>
</li>
