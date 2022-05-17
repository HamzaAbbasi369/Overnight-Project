<?php

if (!function_exists('carbon_get_comment_meta')) {
    return;
}

use Carbon_Fields\Container;
use Carbon_Fields\Field;

Container::make('theme_options', 'ONG Club')
    ->set_page_parent('themes.php')
    ->add_fields([
        Field::make("checkbox", "ong_club_show", "Show Overnight Glasses Club")
            ->set_option_value('yes')->set_width(20),
//        Field::make('text', 'ong_club_amount_redeemed', 'Amount for Redeemed')
//            ->set_width(80),
        Field::make("separator", "ong_club_show_separator", ""),
        Field::make('textarea', 'ong_club_email_after_first_order', 'Email after first order')
            ->set_width(50)->help_text('
                <p><b>%name%</b> - name</i></p>
                <p><b>%coupon%</b> - coupon</i></p>
                <p><b>%url%</b> - url site</i></p>
                <p><b>%read_online%</b> - read online</i></p>'),

        Field::make('textarea', 'ong_club_page_my_account_club', 'Page my-account/club/ ')
            ->set_width(50)->help_text('
                <p><b>%personal_reward_code%</b> - personal reward code</i></p>
                <p><b>%pending_orders%</b> - pending orders</i></p>
                <p><b>%count_number_completed%</b> - count number completed</i></p>
                <p><b>%rewards_accrued%</b> - rewards accrued</i></p>'),

        Field::make('textarea', 'ong_club_page_read_online', 'Page Read online ')
            ->set_width(100)->help_text('
                <p><b>%coupon%</b> - coupon</i></p>
                <p><b>%name%</b> - name</i></p>'),
    ]);
