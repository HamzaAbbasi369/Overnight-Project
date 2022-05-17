<?php

error_reporting( E_ALL );
ini_set( 'display_errors', 1 );

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
/** @var float $easy_clean_fee */
/** @var float $anti_glare_fee */
/** @var float $premium_anti_glare_fee */
/** @var float $computer_anti_glare_fee */
include WcRx::plugin_path() . '/includes/rx_fees.php';
//require( dirname( __FILE__ ) . '/rx_fees_functions.php' );
require_once( dirname( __FILE__ ) . '/rx_functions.php' );

$usage_fee  = 0;
$tint_fee   = 0;
$impact_fee = 0;
$opt_ind    = 0;

$rxtype = (!empty($_REQUEST['rxtype']) ? $_REQUEST['rxtype'] : null);
$lens   = (!empty($_REQUEST['lens']) ? $_REQUEST['lens'] : null);
switch ($lens) {
    case 'Clear Lens':
        $tint_fee = 0;
        break;
    case 'Sun Lens Tint':
        $tint_fee = $tint_sun_tint[ $opt_ind ];
        break;
    case 'Photochromic':
        $tint_fee = $tint_lr_p[ $opt_ind ];
        break;
    case 'Transitions':
        $tint_fee = $tint_lr_t[ $opt_ind ];
        break;
    case 'Transitions Extra Dark':
        $tint_fee = $tint_lr_xtr_a[ $opt_ind ];
        break;
    case 'Polarized':
        $tint_fee = $tint_polarized[ $opt_ind ];
        break;
    case 'Polarized Mirror Coated':
	$tint_fee = $tint_polarized[ $opt_ind ];
	break;
    case 'Mirror Coated':
	$tint_fee = $tint_polarized[ $opt_ind ];
	break;
    default:
        $tint_fee = 0;
}

if ($rxtype == 'Single Vision Reading' || $rxtype == 'Single Vision Distance') {
    $rxtype    = "Single Vision";
    $usage_fee = $usage_reading;
}
if ($rxtype == "Bifocal") {
    $rxtype    = "Bifocal";
    $usage_fee = $usage_bifocal;
}
if ($rxtype == "No Line Bifocal") {
    $rxtype    = "No Line Bifocal";
    $usage_fee = $usage_nolinebifocal;
}


if ($rxtype == "Progressive") {
    $rxtype    = "Progressive Freeform";
    $usage_fee = $usage_progressive;
}

$od_sphere   = (!empty($_REQUEST['od_sphere']) ? $_REQUEST['od_sphere'] : null);
$od_cylinder = (!empty($_REQUEST['od_cylinder']) ? $_REQUEST['od_cylinder'] : null);
$os_sphere   = (!empty($_REQUEST['os_sphere']) ? $_REQUEST['os_sphere'] : null);
$os_cylinder = (!empty($_REQUEST['os_cylinder']) ? $_REQUEST['os_cylinder'] : null);


$impact_resistant = (!empty($_REQUEST['impact_resistant']) ? $_REQUEST['impact_resistant'] : null);
$prism            = (!empty($_REQUEST['prism']) ? $_REQUEST['prism'] : null);

$tracking = (!empty($_REQUEST['tracking']) ? $_REQUEST['tracking'] : null);

/*$rxtype = 'Progressive Freeform';
 $lens = 'Clear Lens';
 
 $od_sphere = 1;
 $od_cylinder = -2;
 $os_sphere = 0;
 $os_cylinder = 0;
 $impact_resistant = 0;
 $prism = 0;*/

$material1 = 0;
$material2 = 0;
$material3 = 0;
$material4 = 0;
$material5 = 0;
$material6 = 0;
$material7 = 0;
$material8 = 0;

if ($od_cylinder > 0) {
    $tmp = - $od_cylinder;
    if ($od_sphere > 0) {
        $od_sphere = $od_sphere + $od_cylinder;
    } else {
        $od_sphere = $od_sphere - $od_cylinder;
    }
    $od_cylinder = $tmp;
}

if ($os_cylinder > 0) {
    $tmp2 = - $os_cylinder;
    if ($os_sphere > 0) {
        $os_sphere = $os_sphere + $os_cylinder;
    } else {
        $os_sphere = $os_sphere - $os_cylinder;
    }
    $os_cylinder = $tmp2;
}

////////////////////////// material1 1.50 standard //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 3.25 ) {
	if ( $od_cylinder >= - 3 && $od_cylinder <= 0 ) {
		$material1 = 1;
	}
} else if ( $od_sphere >= - 3.25 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 3 && $od_cylinder <= 0 ) {
		$material1 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 3.25 ) {
		if ( $os_cylinder >= - 3 && $os_cylinder <= 0 ) {
		} else {
			$material1 = 0;
		}
	} else if ( $os_sphere >= - 3.25 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 3 && $os_cylinder <= 0 ) {
		} else {
			$material1 = 0;
		}
	} else {
		$material1 = 0;
	}
}


////////////////////////// material2 Trivex HD Performance //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 5.25 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material2 = 1;
	}
} else if ( $od_sphere >= - 5.25 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material2 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 5.25 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material2 = 0;
		}
	} else if ( $os_sphere >= - 5.25 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material2 = 0;
		}
	} else {
		$material2 = 0;
	}
}

////////////////////////// material3 1.57 Standard //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 5.25 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material3 = 1;
	}
} else if ( $od_sphere >= - 5.25 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material3 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 5.25 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material3 = 0;
		}
	} else if ( $os_sphere >= - 5.25 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material3 = 0;
		}
	} else {
		$material3 = 0;
	}
}

////////////////////////// material4 PC Advanced //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 6.25 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material4 = 1;
	}
} else if ( $od_sphere >= - 7 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material4 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 6.25 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material4 = 0;
		}
	} else if ( $os_sphere >= - 7 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material4 = 0;
		}
	} else {
		$material4 = 0;
	}
}

////////////////////////// material5 1.6 Thin Lenses //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 8 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material5 = 1;
	}
} else if ( $od_sphere >= - 8 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 4 && $od_cylinder <= 0 ) {
		$material5 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 8 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material5 = 0;
		}
	} else if ( $os_sphere >= - 8 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 4 && $os_cylinder <= 0 ) {
		} else {
			$material5 = 0;
		}
	} else {
		$material5 = 0;
	}
}

////////////////////////// material6 UltraVEX NOT USED //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 4 ) {
	if ( $od_cylinder >= - 2 && $od_cylinder <= 0 ) {
		$material6 = 1;
	}
} else if ( $od_sphere >= - 11 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 2 && $od_cylinder <= 0 ) {
		$material6 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 4 ) {
		if ( $os_cylinder >= - 2 && $os_cylinder <= 0 ) {
		} else {
			$material6 = 0;
		}
	} else if ( $os_sphere >= - 11 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 2 && $os_cylinder <= 0 ) {
		} else {
			$material6 = 0;
		}
	} else {
		$material6 = 0;
	}
}


////////////////////////// material7 1.67 Super Thin //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 12 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material7 = 1;
	}
} else if ( $od_sphere >= - 12 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material7 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 12 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material7 = 0;
		}
	} else if ( $os_sphere >= - 12 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material7 = 0;
		}
	} else {
		$material7 = 0;
	}
}


////////////////////////// material8 1.74 Ultra Thin //////////////////////////
if ( $od_sphere >= 0 && $od_sphere <= 12 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material8 = 1;
	}
} else if ( $od_sphere >= - 12 && $od_sphere <= 0 ) {
	if ( $od_cylinder >= - 6 && $od_cylinder <= 0 ) {
		$material8 = 1;
	}
}

if ( $os_sphere != 0 ) {
	if ( $os_sphere >= 0 && $os_sphere <= 12 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material8 = 0;
		}
	} else if ( $os_sphere >= - 12 && $os_sphere <= 0 ) {
		if ( $os_cylinder >= - 6 && $os_cylinder <= 0 ) {
		} else {
			$material8 = 0;
		}
	} else {
		$material8 = 0;
	}
}

if ( $impact_resistant == 1 ) {
	$material1  = 0;
	$material3  = 0;
	$material5  = 0;
	$material7  = 0;
	$material8  = 0;
	$impact_fee = $impact_resistant_fee;
	//echo "impact fee=" . $impact_resistant;
}

global $wpdb;

$sql = "SELECT distinct package_name,stock,surface,'price','ar','id' FROM packages WHERE rxtype='$rxtype' and lens='$lens'";
$result = $wpdb->get_results( $sql, ARRAY_A );

$packages = [];

if ( $result ) {
	#$results= #$stmt->fetchAll(PDO::FETCH_ASSOC);
	//$json=json_encode($results);
	$price = "";
	while ( list($key,$res) = each( $result ) ) {

		// second price checks
		if ( $res['package_name'] == '1.50 Standard' ) {
			$res['id'] = 'mp_150';
			if ( $od_cylinder < - 2 || $os_cylinder < - 2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'No Line Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$price     = $res['surface'];
				$res['ar'] = 'premium';

			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ( $res['package_name'] == 'Trivex HD Performance' ) {
			$res['id'] = 'mp_thp';
			if ( $od_cylinder < - 2 || $os_cylinder < - 2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'No Line Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$price     = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ( $res['package_name'] == '1.57 Standard' ) {
			$res['id'] = 'mp_157';
			if ( $od_sphere < - 2 || $os_sphere < - 2 || $od_cylinder < - 2 || $os_cylinder < - 2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'No Line Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$price     = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ( $res['package_name'] == 'PC Advanced' ) {
			$res['id'] = 'mp_pca';
			if ( $od_sphere > 6 || $os_sphere > 6 || $od_cylinder < - 2 || $os_cylinder < - 2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'No Line Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$price     = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ( $res['package_name'] == '1.6 Thin Lenses' ) {
			$res['id'] = 'mp_16';
			if ( $od_sphere >= 0 || $os_sphere >= 0 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'No Line Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$res['ar'] = 'premium';
				$price     = $res['surface'];
			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ( $res['package_name'] == '1.67 Super Thin' ) {
			$res['id'] = 'mp_167';
			if ( ( $od_sphere >= 0 || $os_sphere >= 0 ) || ( $od_sphere < - 9.5 || $os_sphere < - 9.5 ) || $prism == 1 || $rxtype == 'No Line Bifocal' || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform' ) {
				$price     = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price     = $res['stock'];
				$res['ar'] = 'regular';
			}

		} elseif ( $res['package_name'] == '1.74 Ultra Thin' ) {
			$res['id'] = 'mp_174';
			$price     = $res['stock'];
			$res['ar'] = 'regular';
		}

		if ( $rxtype == "Bifocal" || $rxtype == 'Progressive Freeform' || $rxtype == 'No Line Bifocal' ) {
			$res['ar'] = 'premium';
			$price     = $res['surface'];
		}
		if ( $lens == 'Sun Lens Tint' ) {
			$res['ar'] = 'premium';
			$price     = $res['surface'];
		}

		if ( $lens == 'Polarized' || $lens == 'Mirror Coated' || $lens == 'Polarized Mirror Coated') {
			$res['ar'] = 'premium';
		}

		if ( $prism == 1 ) {
			$res['ar'] = 'premium';
		}

		// set the price
		$res['price'] = $price;


		if ( $res['package_name'] == '1.50 Standard' && $material1 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == 'Trivex HD Performance' && $material2 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == '1.57 Standard' && $material3 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == 'PC Advanced' && $material4 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == '1.6 Thin Lenses' && $material5 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == '1.67 Super Thin' && $material7 == 1 ) {
			array_push( $packages, $res );
		} elseif ( $res['package_name'] == '1.74 Ultra Thin' && $material8 == 1 ) {
			array_push( $packages, $res );
		}

	}
}

$package_meta = [
	"mp_150" => [
	    "Basic Package",
        carbon_get_theme_option('i_popup_for_rx_lens_material_150_standard') ],
	"mp_thp" => [
		"The best lenses!",
        carbon_get_theme_option('i_popup_for_rx_lens_material_trivex_hd_performance')
    ],
	"mp_157" => [
		"Thinner Lenses",
        carbon_get_theme_option('i_popup_for_rx_lens_material_157_standard')
	],
	"mp_pca" => [
		"Light and Strong!",
        carbon_get_theme_option('i_popup_for_rx_lens_material_pc_advanced')
	],
	"mp_16"  => [
		"Thinner Lenses",
        carbon_get_theme_option('i_popup_for_rx_lens_material_16_thin_lenses')
	],
	"mp_167" => [
		"Thinner Lenses",
        carbon_get_theme_option('i_popup_for_rx_lens_material_167_super_thin')
	],
	"mp_174" => [
		"The thinnest!",
        carbon_get_theme_option('i_popup_for_rx_lens_material_174_ultra_thin')
	]
];

foreach ( $packages as $package ) {
	$style   = "height:125px;";
	$tooltip = $package_meta[ $package['id'] ][1];
	$package['price'] = $package['price'] - $usage_fee - $tint_fee - $impact_fee;
	echo "PACKAGE PRICE: ".$package['price'];
	$submenu          = "<p id='" . $package['id'] . "_price'>" . to_price( $package['price'], $pfactor ) . "</p> ";
    $description             = $package_meta[ $package['id'] ][0];
    $html = '';

	draw_text_selector(
        $package['id'],
        $tooltip,
        $package['package_name'],
        "rdo_material",
        "rx.lensPackage.setMaterial('" . $package['package_name'] . "#" . $package['price'] . "#" . $package['ar'] . "')",
        $html,
        $description,
        $submenu,
        $style,
        null,
        print_color_select('', '', '', $package['package_name'])
    );
}
