<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_dianbiao
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

JHTML::stylesheet('styles.css','modules/mod_dianbiao/css/');

//$electrical_status = JRequest::getVar('electrical_status', '-1');
//$quantity = JRequest::getVar('quantity', '0');

//system("gpio")

$device_id = "01"; // unique id address of individual biao
$biao_command_code = "03";  // command code to read holding register

$u2_address = "00 19"; // address of voltage variable
$i2_address = "00 25"; // address of voltage variable
$s2_address = "00 43"; // address of voltage variable
$f2_address = "00 4b"; // address of voltage variable


$var_len = "00 02"; // length of variable

$u2_checksum = "15 cc"; // checksum for u2
$i2_checksum = "d5 c0"; // checksum for i2
$s2_checksum = "35 df"; // checksum for s2
$f2_checksum = "b4 1d"; // checksum for f2



//$send = exec("sudo /usr/bin/./mod_dianbiao 01 03 00 19 00 02 15 cc", $output);

$electrical_status = ModDianBiaoHelper::getElectricalStatus();
//$electrical_status=1;
$k = 0;
//while ($k<5){
while ( ($k<5) && ($electrical_status) ) {
$k++;
//echo "$k <br>";
//$electrical_status = ModDianBiaoHelper::getElectricalStatus();
//sleep(1);

$var_address = $u2_address;
$checksum = $u2_checksum;
unset($u2_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $u2_output);
foreach($u2_output AS $u2_temp){
//echo " $u2_temp ";
}

$hexString = $u2_output[3] . $u2_output[4] . $u2_output[5] . $u2_output[6];
$u2 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $i2_address;
$checksum = $i2_checksum;
unset($i2_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $i2_output);
$hexString = $i2_output[3] . $i2_output[4] . $i2_output[5] . $i2_output[6];
$i2 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $s2_address;
$checksum = $s2_checksum;
unset($s2_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $s2_output);
$hexString = $s2_output[3] . $s2_output[4] . $s2_output[5] . $s2_output[6];
$s2 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $f2_address;
$checksum = $f2_checksum;
unset($f2_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $f2_output);
$hexString = $f2_output[3] . $f2_output[4] . $f2_output[5] . $f2_output[6];
$f2 = ModDianBiaoHelper::hexStringTo32Float($hexString);


echo "u2 is $u2 <br>";
echo "i2 is $i2 <br>";
echo "s2 is $s2 <br>";
echo "f2 is $f2 <br>";
//echo "f2 is $f2_output[3] . $f2_output[4] . $f2_output[5] . $f2_output[6] <br>";


$voltage1 = $u2;
$current1 = $i2;
$power1 = $s2;
$frequency1 = $f2;

//voltage1 = 230;
//$current1 = 1.3;
//$power1 = 0.55;
//$frequency1 = 50;

date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;

// insert to database
ModDianBiaoHelper::insertElectricalValues($datetime,$u2, $i2, $s2, $f2);


}

// call new web page, then exit

if ($electrical_status) {
  $lines = file("http://192.168.0.211/joomla/index.php/connect-meter");
}

require(JModuleHelper::getLayoutPath('mod_dianbiao', 'default'));
