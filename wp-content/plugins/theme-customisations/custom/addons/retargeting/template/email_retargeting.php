<?php

$subject = 'Keep your vision accurate with Overnight Glasses';
$headers = 'From: Overnight Glasses team <support@overnightglasses.com>' . "\r\n";

$message = carbon_get_theme_option('ong_email_retargeting');
$message = str_replace('%name%', $name, $message);
