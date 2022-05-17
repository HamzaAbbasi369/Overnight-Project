<?php
/***************************************************************************************
 * This file defines all fixed fees used in the Rx form
 ****************************************************************************************/
/** @var float $usage_reading */
/** @var float $usage_distance */
/** @var float $usage_bifocal */
/** @var float $usage_nolinebifocal */
/** @var float $usage_progressive */
/** @var float $impact_resistant_fee */
/** @var float $tint_sun_tint */
/** @var float $tint_polarized */
/** @var float $tint_lr_p */
/** @var float $tint_lr_t */
/** @var float $tint_lr_xtr_a */
/** @var float $rush_service_fee */
/** @var float $rush_service_ge150_fee */
/** @var float $rush_3day_service_fee */
/** @var float $easy_clean_fee */
/** @var float $anti_glare_fee */
/** @var float $premium_anti_glare_fee */
/** @var float $computer_anti_glare_fee */
/** @var float $prism_fee */
$rx_fees = [

    'usage_reading'                 => 0.0,     //0.0
    'usage_distance'                => 0.0,
    'usage_bifocal'                 => 70.00,   //  offset
    'usage_nolinebifocal'           => 80.00,
    'usage_progressive'             => 99.00,  //  offset
    'impact_resistant_fee'          => 24.00,
    // Tint Fees are defined as array(rx_lens_fee,fashion_lens_fee)
    'tint_sun_tint'                 => [10.00, 5.00],
    'tint_polarized'                => [50.00, 31.00],
    'tint_lr_p'                     => [35.00, 42.00],
    'tint_lr_t'                     => [51.00, 79.00],
    'tint_lr_xtr_a'                 => [98.00, 139.00],
//    'rush_service_fee'              => 59.00,
	'rush_service_fee'              => 00.00,
    'rush_service_ge150_fee'        => 59.00,
    'rush_3day_service_fee'         => 9.00,
	'notrush20_service_fee'         => 20.00,
    'easy_clean_fee'                => 18.00,
    'anti_glare_fee'                => 12.00,
    'premium_anti_glare_fee'        => 53.00,
    'computer_anti_glare_fee'       => 60.00,
    'premium_fee'                   => 99.00,
    'premium_plus_fee'              => 149.00,
    'prism_fee'                     => 25.00,
    'xtra_active_color_fee'         => 26.00,
    'diamond_fee'                   => 76.00,
    'your_frame_rush_fee'           => 94.00,
    'polarized_mirror_fee'           => 49.00,
];

extract($rx_fees);
return $rx_fees;
