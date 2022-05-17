<?php
/**
 * Export Field Groups
 * Date: 16.03.17
 */

if(function_exists("register_field_group"))
{
    register_field_group(array (
        'id' => 'acf_size',
        'title' => 'Size',
        'fields' => array (
            array (
                'key' => 'field_58ca8d9159519',
                'label' => 'x-size',
                'name' => 'x-size',
                'type' => 'radio',
                'instructions' => 'Choose the size',
                'required' => 1,
                'choices' => array (
                    'S' => 'S',
                    'M' => 'M',
                    'L' => 'L',
                ),
                'other_choice' => 0,
                'save_other_choice' => 0,
                'default_value' => 'M',
                'layout' => 'horizontal',
            ),
        ),
        'location' => array (
            array (
                array (
                    'param' => 'ef_taxonomy',
                    'operator' => '==',
                    'value' => 'pa_size',
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
