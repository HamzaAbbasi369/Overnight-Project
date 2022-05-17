<?php

if (!function_exists('carbon_get_comment_meta')) {
	return;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'Category banners')
->set_page_parent( 'themes.php' )
->add_fields([
    Field::make("separator", "eyeglasses_separator", "Eyeglasses"),
    Field::make('textarea', 'eyeglasses', 'Eyeglasses')->set_width(50),
    Field::make('textarea', 'eyeglasses_women', 'Eyeglasses women')->set_width(50),
    Field::make('textarea', 'eyeglasses_men', 'Eyeglasses men')->set_width(50),
    Field::make('textarea', 'eyeglasses_kids', 'Eyeglasses kids')->set_width(50),
    
    Field::make("separator", "sunglasses_separator", "Sunglasses"),
    Field::make('textarea', 'sunglasses', 'Sunglasses')->set_width(50),
    Field::make('textarea', 'sunglasses_women', 'Sunglasses women')->set_width(50),
    Field::make('textarea', 'sunglasses_men', 'Sunglasses men')->set_width(50),
    Field::make('textarea', 'sunglasses_kids', 'Sunglasses kids')->set_width(50),
    
    Field::make("separator", "designers_separator", "Designers"),
    Field::make('textarea', 'designers', 'Designers'),
    
    Field::make("separator", "all-glasses_separator", "All glasses"),
    Field::make('textarea', 'all-glasses', 'All glasses'),
    
]);
