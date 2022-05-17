<?php
/**
 * rx
 *
 * @author     Eugene Odokiienko <eugene@overnightglasses.com>
 * @copyright  Copyright (c) 2018 Vision Care Services LLC. (http://www.overnightglasses.com)
 */
$price = isset($_REQUEST['price']) ? $_REQUEST['price'] : '0';
$fprice = isset($_REQUEST['fprice']) ? $_REQUEST['fprice'] : '0';
$frame_regular_price = isset($_REQUEST['frame_regular_price']) ? $_REQUEST['frame_regular_price'] : '0';

$pfactor = isset($_REQUEST['pfactor']) ? $_REQUEST['pfactor'] : 1;
//$ffactor = isset($_REQUEST['ffactor']) ? $_REQUEST['ffactor'] : 1;

$pimage = isset($_REQUEST['pimage']) ? ($_REQUEST['pimage']) : '';
$offer =isset($_REQUEST['offer']) ?  ($_REQUEST['offer']) : '';
