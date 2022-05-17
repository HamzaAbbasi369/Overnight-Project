<?php

error_reporting(E_ALL); 
ini_set('display_errors', 1);
//require_once( realpath(__DIR__ . '/..') . '/wp-config.php' );
//echo 'WP PATH';
//echo  realpath(__DIR__ . '/..') . '/wp-config.php';
//echo 'executing';

define('DB_NAME', 'seeme');

/** MySQL database username */
define('DB_USER', 'seeme');

/** MySQL database password */
define('DB_PASSWORD', 'dBs33M6543xx1');

/** MySQL hostname */
define('DB_HOST', 'localhost:8889');


 $rxtype = $_POST['rxtype'];
 $lens = $_POST['lens'];


 if ($rxtype == 'Reading / Focal' || $rxtype == 'Distance') {
	$rxtype = "Single Vision";
 }
 if ($rxtype == "Reading") {
	$rxtype = "Bifocal";
 }
 if ($rxtype == "All-Day Lens") {
	$rxtype = "Progressive Freeform";
 }

 $od_sphere = $_POST['od_sphere'];
 $od_cylinder = $_POST['od_cylinder'];
 $os_sphere = $_POST['os_sphere'];
 $os_cylinder = $_POST['os_cylinder'];


 $impact_resistant = $_POST['impact_resistant'];
 $prism = $_POST['prism']; 

 $tracking = $_POST['tracking'];

/*$rxtype = 'Progressive Freeform';
 $lens = 'Clear Lens';
 
 $od_sphere = 1;
 $od_cylinder = -2;
 $os_sphere = 0;
 $os_cylinder = 0;
 $impact_resistant = 0;
 $prism = 0;*/

 $material1 = 0; $material2 = 0; $material3 = 0; $material4 = 0; $material5 = 0; $material6 = 0; $material7 = 0; $material8 = 0;

 if ($od_cylinder > 0) {
	$tmp = -$od_cylinder;
	if ($od_sphere > 0) {
		$od_sphere = $od_sphere + $od_cylinder;
	} else {
		$od_sphere = $od_sphere - $od_cylinder;
	}
	$od_cylinder = $tmp;
 }  

 if ($os_cylinder > 0) {
	$tmp2 = -$os_cylinder;
	if ($os_sphere > 0) {
		$os_sphere = $os_sphere + $os_cylinder;
	} else {
		$os_sphere = $os_sphere - $os_cylinder;
	}
	$os_cylinder = $tmp2;
 }  

        ////////////////////////// material1 1.50 standard //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 3.25) {
                if ($od_cylinder >= -3 && $od_cylinder <= 0 ) { $material1 = 1; }
        }
        else if ($od_sphere >= -3.25 && $od_sphere <= 0) {
                if ($od_cylinder >= -3 && $od_cylinder <= 0 ) { $material1 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 3.25) {
                        if ($os_cylinder >= -3 && $os_cylinder <= 0 ) {  }
                        else { $material1 = 0; }
                }
                else if ($os_sphere >= -3.25 && $os_sphere <= 0) {
                        if ($os_cylinder >= -3 && $os_cylinder <= 0 ) {  }
                        else { $material1 = 0; }
                }
                else {
                        $material1 = 0;
                }
        }


        ////////////////////////// material2 Trivex HD Performance //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 5.25) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material2 = 1; }
        }
        else if ($od_sphere >= -5.25 && $od_sphere <= 0) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material2 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 5.25) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material2 = 0; }
                }
                else if ($os_sphere >= -5.25 && $os_sphere <= 0) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material2 = 0; }
                }
                else {
                        $material2 = 0;
                }
        }
 
        ////////////////////////// material3 1.57 Standard //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 5.25) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material3 = 1; }
        }
        else if ($od_sphere >= -5.25 && $od_sphere <= 0) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material3 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 5.25) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material3 = 0; }
                }
                else if ($os_sphere >= -5.25 && $os_sphere <= 0) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material3 = 0; }
                }
                else {
                        $material3 = 0;
                }
        }

        ////////////////////////// material4 PC Advanced //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 6.25) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material4 = 1; }
        }
        else if ($od_sphere >= -7 && $od_sphere <= 0) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material4 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 6.25) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material4 = 0; }
                }
                else if ($os_sphere >= -7 && $os_sphere <= 0) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material4 = 0; }
                }
                else {
                        $material4 = 0;
                }
        }

        ////////////////////////// material5 1.6 Thin Lenses //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 8) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material5 = 1; }
        }
        else if ($od_sphere >= -8 && $od_sphere <= 0) {
                if ($od_cylinder >= -4 && $od_cylinder <= 0 ) { $material5 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 8) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material5 = 0; }
                }
                else if ($os_sphere >= -8 && $os_sphere <= 0) {
                        if ($os_cylinder >= -4 && $os_cylinder <= 0 ) {  }
                        else { $material5 = 0; }
                }
                else {
                        $material5 = 0;
                }
        }

        ////////////////////////// material6 UltraVEX NOT USED //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 4) {
                if ($od_cylinder >= -2 && $od_cylinder <= 0 ) { $material6 = 1; }
        }
        else if ($od_sphere >= -11 && $od_sphere <= 0) {
                if ($od_cylinder >= -2 && $od_cylinder <= 0 ) { $material6 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 4) {
                        if ($os_cylinder >= -2 && $os_cylinder <= 0 ) {  }
                        else { $material6 = 0; }
                }
                else if ($os_sphere >= -11 && $os_sphere <= 0) {
                        if ($os_cylinder >= -2 && $os_cylinder <= 0 ) {  }
                        else { $material6 = 0; }
                }
                else {
                        $material6 = 0;
                }
        }


        ////////////////////////// material7 1.67 Super Thin //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 12) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material7 = 1; }
        }
        else if ($od_sphere >= -12 && $od_sphere <= 0) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material7 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 12) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material7 = 0; }
                }
                else if ($os_sphere >= -12 && $os_sphere <= 0) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material7 = 0; }
                }
                else {
                        $material7 = 0;
                }
        }


       ////////////////////////// material8 1.74 Ultra Thin //////////////////////////
        if ($od_sphere >= 0 && $od_sphere <= 12) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material8 = 1; }
        }
        else if ($od_sphere >= -12 && $od_sphere <= 0) {
                if ($od_cylinder >= -6 && $od_cylinder <= 0 ) { $material8 = 1; }
        }

        if ($os_sphere != 0) {
                if ($os_sphere >= 0 && $os_sphere <= 12) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material8 = 0; }
                }
                else if ($os_sphere >= -12 && $os_sphere <= 0) {
                        if ($os_cylinder >= -6 && $os_cylinder <= 0 ) {  }
                        else { $material8 = 0; }
                }
                else {
                        $material8 = 0;
                }
        }

	if ($impact_resistant == 1) {
		$material1 = 0; $material3 = 0; $material5 = 0; $material7= 0; $material8 = 0;
	}
	
 #$db = new PDO('mysql:host=localhost;dbname=foureyew_newfew;charset=utf8', 'foureyew_opencar', 'Opencart2012');

 $db = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME); // - prod
 //$db = mysql_connect('localhost','root','root','seeme'); // - test
 mysql_select_db('seeme', $db);
 $sql = "SELECT distinct package_name,stock,surface,'price','ar','id' FROM packages WHERE rxtype='$rxtype' and lens='$lens'";
 //echo $sql;
 $result =  mysql_query($sql, $db);
 #$stmt = $db->prepare("SELECT distinct package_name,stock,surface,'price','ar' FROM packages WHERE rxtype=? and lens=?");

// echo "SELECT distinct package_name,stock,surface,'price' FROM packages WHERE rxtype=$rxtype and lens=$lens";

 $packages = array();

 if ($result) {
	#$results= #$stmt->fetchAll(PDO::FETCH_ASSOC);
	//$json=json_encode($results);
	$price = "";
	while ($res = mysql_fetch_assoc($result)) {
		// second price checks
		if ($res['package_name'] == '1.50 Standard') {
                $res['id'] = 'mp_150';
			if ($od_cylinder < -2 || $os_cylinder < -2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$price = $res['surface'];
				$res['ar'] = 'premium';

			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ($res['package_name'] == 'Trivex HD Performance') {
            $res['id'] = 'mp_thp';
			if ($od_cylinder < -2 || $os_cylinder < -2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$price = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ($res['package_name'] == '1.57 Standard') {
            $res['id'] = 'mp_157';
			if ($od_sphere < -2 || $os_sphere < -2 || $od_cylinder < -2 || $os_cylinder < -2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$price = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ($res['package_name'] == 'PC Advanced') {
            $res['id'] = 'mp_pca';
			if ($od_sphere > 6 || $os_sphere > 6 || $od_cylinder < -2 || $os_cylinder < -2 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$price = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ($res['package_name'] == '1.6 Thin Lenses') {
                $res['id'] = 'mp_16';
			if ($od_sphere >= 0 || $os_sphere >= 0 || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$res['ar'] = 'premium';
				$price = $res['surface'];
			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}
		} elseif ($res['package_name'] == '1.67 Super Thin') {
                $res['id'] = 'mp_167';
			if  (($od_sphere >= 0 || $os_sphere >= 0) || ($od_sphere < -9.5 || $os_sphere < -9.5) || $prism == 1 || $rxtype == 'Bifocal' || $rxtype == 'Progressive Freeform') {
				$price = $res['surface'];
				$res['ar'] = 'premium';
			} else {
				$price = $res['stock'];
				$res['ar'] = 'regular';
			}

		} elseif ($res['package_name'] == '1.74 Ultra Thin') {
            $res['id'] = 'mp_174';
			$price = $res['stock'];
			$res['ar'] = 'regular';
		}

		if ($rxtype == "Bifocal" || $rxtype == 'Progressive Freeform') {
			$res['ar'] = 'premium';
			$price = $res['surface'];
		}
		if ($lens == 'Sun Lens Tint') {
			$res['ar'] = 'premium';
			$price = $res['surface'];
		}
		
		if ($lens == 'Polarized') {
			$res['ar'] = 'premium';
		}

		if ($prism == 1) {
			$res['ar'] = 'premium';
		}
	
		// set the price
		$res['price'] = $price;
	
		//echo "$material1 $material2 $material3 $material4 $material5 $material7 $material8\n";
		
		if ($res['package_name'] == '1.50 Standard' && $material1 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == 'Trivex HD Performance' && $material2 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == '1.57 Standard' && $material3 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == 'PC Advanced' && $material4 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == '1.6 Thin Lenses' && $material5 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == '1.67 Super Thin' && $material7 == 1) {
			array_push($packages, $res);
		} elseif ($res['package_name'] == '1.74 Ultra Thin' && $material8 == 1) {
			array_push($packages, $res);
		}

	}
	$json = json_encode($packages);
 }
 echo $json;
