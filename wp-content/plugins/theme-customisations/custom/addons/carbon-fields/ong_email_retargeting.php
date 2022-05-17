<?php

if (!function_exists('carbon_get_comment_meta')) {
    return;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'ONG Retargeting')
    ->set_page_parent('themes.php')
    ->add_fields([
        Field::make('textarea', 'ong_email_retargeting', 'Sending Email. Retargeting after 12 months')->help_text('
                <p><b>%name%</b> - name</i></p>'),
]);
