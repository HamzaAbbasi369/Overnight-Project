<?php

$subject = 'Welcome to Overnight Glasses';
$headers = 'From: Overnight Glasses team <support@overnightglasses.com>' . "\r\n";

$message = carbon_get_theme_option('ong_club_email_after_first_order');

$message = str_replace('%name%', $name, $message);
$message = str_replace('%coupon%', $coupon['post_title'], $message);
$message = str_replace('%url%', get_site_url(), $message);
$message = str_replace('%read_online%', $read_online, $message);
