<?php
if (!function_exists('carbon_get_comment_meta')) {
	return;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'Product Lines')
     ->set_page_parent( 'themes.php' )
     ->add_fields([

         Field::make("separator", "product_lines_catalog", "Product lines catalog"),
         Field::make('textarea', 'prestige', 'Prestige'),
         Field::make('textarea', 'premium', 'Premium'),
         Field::make('textarea', 'economy', 'Economy'),

     ]);

Container::make('term_meta', 'pa_product-line Term')
     ->show_on_taxonomy('pa_product-line')
     ->add_fields([
         Field::make('text', 'color', 'Color')
     ]);

