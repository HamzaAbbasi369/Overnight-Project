<?php

$subject = 'Welcome to Overnight Glasses';
$headers = 'From: My Name <support@overnightglasses.com>' . "\r\n";
$message = 'Dear %name%,';
$message .= '<br /><br />';
$message .= 'Thank you for subscribing to special promotions from Overnight Glasses. You will receive your first newsletter shortly.';
$message .= '<br />';
$message .= 'As a subscriber you can get 35% off your first order with code <b>SEE35</b>.';
$message .= '<br /><br />';
$message .= 'Overnight Glasses Team.';
$message .= '<br /><br />';
$message .= 'Click here to ';
$message .= "<a href='".site_url()."/unsubscribe?email=%email%&nonce=".$nonce."'>Unsubscribe</a>.";

//$message .= '<a href="'. bloginfo('url') . '">overnightglasses.com</a>';
//$message .= "<a href='". bloginfo('url') . "'>www.overnightglasses.com</a>";
//$message = nl2br(str_replace('%name%', $name, $message));

$message = str_replace('%name%', $name, $message);
$message = str_replace('%email%', $email, $message);
