<?php

// Delivery estimator 
function observed_date($holiday){
    $day = date("w", strtotime($holiday));
    if($day == 6) {
        $observed_date = $holiday -1;
    } elseif ($day == 0) {
        $observed_date = $holiday +1;
    } else {
        $observed_date = $holiday;
    }
    return $observed_date;
}

function get_holiday($holiday_name) {

    $currentYear = date('Y');

    switch ($holiday_name) {
        // New Years Day
        //case "new_year":
        //    $holiday = observed_date(date('Y-m-d', strtotime("first day of january $currentYear")));
        //    break;
        // Martin Luther King, Jr. Day
        case "mlk_day":
            $holiday = date('Y-m-d', strtotime("january $currentYear third monday"));
            break;
        // President's Day
        case "presidents_day":
            $holiday = date('Y-m-d', strtotime("february $currentYear third monday"));
            break;
        // Memorial Day
        case "memorial_day":
            $holiday = (new DateTime("Last monday of May"))->format("Y-m-d");
            break;
        // Independence Day
        case "independence_day":
            $holiday = observed_date(date('Y-m-d', strtotime("july 4 $currentYear")));
	    break;
        // Labor Day
        case "labor_day":
            $holiday = date('Y-m-d', strtotime("september $currentYear first monday"));
            break;
        // Columbus Day
        case "columbus_day":
            $holiday = date('Y-m-d', strtotime("october $currentYear second monday"));
            break;
        // Veteran's Day
        case "veterans_day":
            $holiday = observed_date(date('Y-m-d', strtotime("november 11 $currentYear")));
            break;
        // Thanksgiving Day
        case "thanksgiving_day":
            $holiday = date('Y-m-d', strtotime("november $currentYear fourth thursday"));
            break;
        // Christmas Day
        case "christmas_day":
        $holiday = observed_date(date('Y-m-d', strtotime("december 25 $currentYear")));
            break;

        default:
            $holiday = "";
            break;
    }
    return $holiday;
}

function addDays($timestamp, $days, $skipdays = array("Saturday", "Sunday"), $skipdates = array()) {
     // $skipdays: array (Monday-Sunday) eg. array("Saturday","Sunday")
     // $skipdates: array (YYYY-mm-dd) eg. array("2012-05-02","2015-08-01");
     // timestamp is strtotime of ur $startDate
     $i = 1;
     /*if (date("m/d/Y",$timestamp) == '07/03/2020') {
        $days = $days + 3;
     }*/
     /*if (date("m/d/Y",$timestamp) == '07/04/2020') {
        $days++;
     }*/
     while ($days >= $i) {
	 $timestamp = strtotime("+1 day", $timestamp);
	 if ( (in_array(date("l", $timestamp), $skipdays)) || (in_array(date("Y-m-d", $timestamp), $skipdates)) )
	 {
	     $days++;
	 }
	 $i++;
     }
     //return $timestamp;
    return date("m/d/Y",$timestamp);
}

function getShippingName($order) {
	// UPS Next Day Air (1 Day)
	// Free USPS Shipping (2-5 Days)
	// UPS Ground
	// UPS 2nd Day Air (2 Days)
	// UPS Next Day Air
	$shipping_methods = $order->get_shipping_methods();
	$shipping_name = "";
	foreach($shipping_methods as $shipping_method) {
		$shipping_name = $shipping_method->get_name();
	}
	return $shipping_name;
}

function get_production_days($rx_type, $late_day, $rimless, $second_day_delivery) {
        if ($rx_type == "single_vision") {
                $min_prod_days = 2;
                $max_prod_days = 4;
        } else {
		$min_prod_days = 3;
                $max_prod_days = 5;
	}
                
        if ($late_day == 1) {
                $max_prod_days += 1;
        }
        if ($rimless == 1) {
                $max_prod_days += 1;
        }
        if ($second_day_delivery == 1) {
                $shipping_days_d = "2";
        } else {
                $shipping_days_d = "2-5";
        }

        return array("$min_prod_days-$max_prod_days (Business Days)", "$shipping_days_d (Business Days)");
}

function get_estimated_delivery($order) {
	date_default_timezone_set('America/Los_Angeles');
	$late_day = 0;
	if ( (date("H") == 12 && date("i") != 00) || (date("H") >= 13) )  {
		$late_day = 1;
	}
        $holidays = array(get_holiday('new_year'),get_holiday('mlk_day'),get_holiday('presidents_day'),get_holiday('memorial_day'),get_holiday('independence_day'),get_holiday('labor_day'),get_holiday('columbus_day'),get_holiday('columbus_day'),get_holiday('veterans_day'),get_holiday('thanksgiving_day'),get_holiday('christmas_day'));

	$progressive = 0;
	$single_vision = 0;
	$bifocal = 0;
	$rush = 0;
	$rimless = 0;

	$product_id = 0;
	
        foreach ($order->get_items() as $item_id => $item_data) {
                    $product_id = $item_data->get_product_id();
                    $product = $item_data->get_product();
                    $product = $product->get_parent_id() > 0 ? wc_get_product( $product->get_parent_id() )  : $item_data->get_product();
                    if ( strtoupper($product->get_attribute( 'pa_frame-style' )) == strtoupper("Rimless") ){
                        $rimless = 1;
                    }
                    //print_r($item_data['_all_lens_data']);
                    if (strpos($item_data['_all_lens_data']['type'], 'Single Vision') !== false) {
                        $single_vision = 1;
                    } elseif (strpos($item_data['_all_lens_data'], 'Bifocal') !== false) {
                        $bifocal = 1;
                    } elseif (strpos($item_data['_all_lens_data'], 'Progressive') !== false) {
                        $progressive = 1;
                    }

                    if ($item_data['_all_lens_data']['rush'] == 1 ){
                        $rush = 1;
                    };

        }
	
	$today_day = date("D");
        $today_tmp = isset($_GET['odate']) ? strptime($_GET['odate'], '%m-%d-%Y') : time();
        $today = isset($_GET['odate']) ? mktime(0, 0, 0, $today_tmp['tm_mon']+1, $today_tmp['tm_mday'], $today_tmp['tm_year']+1900) : $today_tmp;


	// FOR TESTING
	$late_day = isset($_GET['late']) ? $_GET['late'] : $late_day;
	$today_tmp = isset($_GET['odate']) ? strptime($_GET['odate'], '%m-%d-%Y') : time();
	$today = isset($_GET['odate']) ? mktime(0, 0, 0, $today_tmp['tm_mon']+1, $today_tmp['tm_mday'], $today_tmp['tm_year']+1900) : $today_tmp;
	$rimless = isset($_GET['rimless']) ? 1 : $rimless;
	$today_day = isset($_GET['day']) ? $_GET['day'] : date("D", $today);
	//echo $today_day;
	//echo "DEBUG: Late: $late_day, current hour(".date("H").") ".date_default_timezone_get()." Rush: $rush<br/>";
	//echo "DEBUG: single vision: $single_vision  bifocal: $bifocal progressive: $progressive Rimless: $rimless<br/>";
	$mon = $today_tmp['tm_mon']+1;
	$t = $mon."-".$today_tmp['tm_mday']."-2020";
	//echo "DEBUG: date: $t day: $today_day<br/>";*/
	// END TESTING

	$deliver_by = time();

	$production_days = 0;
	$shipping_days = 0;

	$shipping_name = getShippingName($order);


        if ($single_vision == 1) {
		if ($rush == 1) {
			if ($today_day == "Fri" && $late_day == 0) {
				if ($rimless == 0) {
					$production_days = "Ships today";
					$shipping_days = 1;
					$deliver_by = addDays($today,1,"",$holidays);#strftime((strtotime("+1 day", $deliver_by)), "%m/%d/%Y");
				} else {
					$production_days = 2;
					$shipping_days = 2;
					$deliver_by = addDays($today,($production_days + $shipping_days),"",$holidays);
				}
			} elseif ($today_day == "Fri" && $late_day == 1) {
				if ($rimless == 0) {
					$production_days = "Ships Monday";
					$shipping_days = 1;
					$deliver_by = addDays($today, 3 + $shipping_days, "", $holidays);
				} else {
					$production_days = 4;
					$shipping_days = 2;
					$deliver_by = addDays($today, 4 + 2, "", $holidays);
				}
			} elseif ($today_day == "Sat") {
				if ($rimless == 0) {
					$production_days = "3 (Ships on Monday)";
					$shipping_days = 1;
					$deliver_by = addDays($today, 3, "", $holidays);
				} else {
					$production_days = 4;
					$shipping_days = 2;
					$deliver_by = addDays($today, $production_days + $shipping_days, "", $holidays);
				}
			} elseif ($today_day == "Sun") {
				if ($rimless == 0) {
					$production_days = "2 (Ships on Monday)";
					$shipping_days = 1;
					$deliver_by = addDays($today, 2, array("Sunday"), $holidays);
				} else {
					$production_days = 3;
					$shipping_days = 2;
					$deliver_by = addDays($today, $production_days + $shipping_days, array("Sunday"), $holidays);
				}
			} else { // Monday - Thursday
				if ($late_day == 0) {
					if ($rimless == 0) {
						$production_days = 1;
						$shipping_days = 1;
						$deliver_by = addDays($today, 1, array("Sunday"), $holidays);
					} else {
						$production_days = 2;
						$shipping_days = 2;
						$deliver_by = addDays($today, $production_days, array("Sunday"), $holidays);
					}
				} else {
					if ($rimless == 0) {
						$production_days = 2;
						$shipping_days = 1;
						$deliver_by = addDays($today, $production_days, array("Sunday"), $holidays);
					} else {
						$production_days = 3;
						$shipping_days = 3;
						$deliver_by = addDays($today, $production_days, array("Sunday"), $holidays);
					}
				}
			}

		} else { //not rush single vision
			if ($shipping_name == "UPS 2nd Day Air (2 Days)" || $shipping_name == "UPS 2nd Day Air") {
				$production_days_data = get_production_days("single_vision", $late_day, $rimless, 1);
				$production_days = $production_days_data[0];
				$shipping_days = $production_days_data[1];
				$deliver_by = "";
			} else {
				$production_days_data = get_production_days("single_vision", $late_day, $rimless, 0);
				$production_days = $production_days_data[0];
				$shipping_days = $production_days_data[1];
				$deliver_by = "";
			}	
		}
	} else { //eiend of single vision check          

	// bifocal or progressive
                if ($rush == 1) {
                        if ($today_day == "Fri" && $late_day == 0) {
                                if ($rimless == 0) {
                                        $production_days = "4 (Ships on Monday)";
                                        $shipping_days = 1;
                                        $deliver_by = addDays($today,5,"",$holidays);#strftime((strtotime("+1 day", $deliver_by)), "%m/%d/%Y");
                                } else {
                                        $production_days = 5;
                                        $shipping_days = 2;
                                        $deliver_by = addDays($today,($production_days + $shipping_days),"",$holidays);
                                }
                        } elseif ($today_day == "Fri" && $late_day == 1) {
                                if ($rimless == 0) {
                                        $production_days = "5 (Ships on Tuesday)";
                                        $shipping_days = 1;
                                        $deliver_by = addDays($today,($production_days + $shipping_days) - 1, "", $holidays);
                                } else {
                                        $production_days = 6;
                                        $shipping_days = 3;
                                        $deliver_by = addDays($today, ($production_days + $shipping_days) - 1, "", $holidays);
                                }
                        } elseif ($today_day == "Sat") {
                                if ($rimless == 0) {
                                        $production_days = "4 (Ships on Tuesday)";
                                        $shipping_days = 1;
                                        $deliver_by = addDays($today, 4, "", $holidays);
                                } else {
                                        $production_days = 5;
                                        $shipping_days = 2;
                                        $deliver_by = addDays($today, $production_days + $shipping_days, "", $holidays);
                                }
                        } elseif ($today_day == "Sun") {
                                if ($rimless == 0) {
                                        $production_days = "3 (Ships on Tuesday)";
                                        $shipping_days = 1;
                                        $deliver_by = addDays($today, 3, "", $holidays);
                                } else {
                                        $production_days = 4;
                                        $shipping_days = 2;
                                        $deliver_by = addDays($today, $production_days + $shipping_days, "", $holidays);
                                }
                        } else { // Monday - Thursday
                                if ($late_day == 0) {
                                        if ($rimless == 0) {
                                                $production_days = 2;
                                                $shipping_days = 1;
                                                $deliver_by = addDays($today, ($production_days + $shipping_days), array("Sunday"), $holidays);
                                        } else {
                                                $production_days = 3;
                                                $shipping_days = 2;
                                                $deliver_by = addDays($today, $production_days + $shipping_days, array("Sunday"), $holidays);
                                        }
                                } else {
                                        if ($rimless == 0) {
                                                $production_days = 3;
                                                $shipping_days = 1;
                                                $deliver_by = addDays($today, $production_days + $shipping_days, array("Sunday"), $holidays);
                                        } else {
                                                $production_days = 4;
                                                $shipping_days = 3;
                                                $deliver_by = addDays($today, $production_days + $shipping_days, array("Sunday"), $holidays);
                                        }
                                }
                        }

                } else { //not rush progressive / bifocal
                        if ($shipping_name == "UPS 2nd Day Air (2 Days)" || $shipping_name == "UPS 2nd Day Air") {
                                $production_days_data = get_production_days("progressive_bifocal", $late_day, $rimless, 1);
                                $production_days = $production_days_data[0];
                                $shipping_days = $production_days_data[1];
                                $deliver_by = "";
                        } else {
                                $production_days_data = get_production_days("progressive_bifocal", $late_day, $rimless, 0);
                                $production_days = $production_days_data[0];
                                $shipping_days = $production_days_data[1];
                                $deliver_by = "";
                        }
                }



	}


	return array("product_id" => $product_id, 
		     "late_day" => $late_day, 
		     "production_days" => $production_days, 
		     "shipping_days" => $shipping_days,
		     "deliver_by" => $deliver_by,
		     "rush" => $rush);

}


