<?php

$message = carbon_get_theme_option('ong_club_page_my_account_club');

$message = str_replace('%personal_reward_code%', $post->post_title, $message);
$message = str_replace('%pending_orders%', $PendingOrders, $message);
$message = str_replace('%count_number_completed%', $CountNumberCompleted, $message);
$message = str_replace('%rewards_accrued%', $RewardsAccrued, $message);

echo $message;
