<?php
/**
 * wp-composer
 *
 * @author     Eugene Odokiienko <eugene.odokienko@agilefuel.com>
 * @copyright  Copyright (c) 2017 Overnightglasses LLC. (http://www.overnightglasses.com)
 */

if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_picture-of-color',
        'title' => 'Picture of Color',
        'fields' => array (
            array (
                'key' => 'field_58bd691368e26',
                'label' => 'Color\'s Picture',
                'name' => 'picture_of_color',
                'type' => 'image',
                'save_format' => 'object',
                'preview_size' => 'thumbnail',
                'library' => 'all',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'ef_taxonomy',
                    'operator' => '==',
                    'value' => 'pa_color',
                    'order_no' => 0,
                    'group_no' => 0,
                ),
            ),
        ),
        'options' => array (
            'position' => 'normal',
            'layout' => 'no_box',
            'hide_on_screen' => array (
            ),
        ),
        'menu_order' => 0,
    ));
}
