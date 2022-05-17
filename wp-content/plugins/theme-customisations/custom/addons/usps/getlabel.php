<?php
error_reporting(E_WARNING);
ini_set('display_errors', 1);
require('USPSLabel.php');
// Your USPS Username
$FromName = $_POST['FromName'];
$FromAddress2 = $_POST['FromAddress2'];
$FromAddress1 = $_POST['FromAddress1'];
$FromFirm = $_POST['FromFirm'];
$FromCity = $_POST['FromCity'];
$FromState = $_POST['FromState'];
$FromZip5 = $_POST['FromZip5'];

if ($FromAddress2 == '') {
    $FromAddress2 = $FromAddress1;
    $FromAddress1 = '';
}

$USPSResponse = USPSLabel($FromName, $FromAddress1, $FromAddress2, $FromFirm, $FromCity, $FromState, $FromZip5);
$USPSLabel = $USPSResponse['DeliveryConfirmationV4.0Response']['DeliveryConfirmationLabel']['VALUE'];
header("Content-type:application/pdf");
header("Content-Disposition:attachment;filename=ong_shipping_label.pdf");
echo base64_decode($USPSLabel);
