<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_connect
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
//error_reporting( E_ALL&~E_NOTICE );
defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

JHTML::stylesheet('styles.css','modules/mod_meter_connect/css/');

//$electrical_status = JRequest::getVar('electrical_status', '-1');
//$quantity = JRequest::getVar('quantity', '0');

$info_id = JRequest::getVar('info_id', '-1');  //get location_id 
$location_id = JRequest::getVar('location_id', '-1');  //get location_id 
$meter_address = JRequest::getVar('meter_address', '-1');  //get location_id 
$meter_model = JRequest::getVar('meter_model', '-1');  //get meter_model 

//echo  $location_id ."-". $meter_address ."-". $meter_address;
if($meter_model == "-1"){
	echo "<script>alert('请先选择要采集的电表！');history.back(); </script>";
}else{

//system("gpio")
  

    
	/*$sql = "select * from joomla3_metermodel where meter_model = '$meter_model' order by meter_model_id desc";
	$rs = mysql_query($sql);
	//$rsnum = mysql_num_rows($rs);
	$row = mysql_fetch_array($rs);*/
	
$result = ModDianBiaoHelper::getMeterModelValus($meter_model);
	
  if($result == ""){
		echo "<script>alert('数据库中还没有此电表型号记录！请录入！');history.back();</script>";
  }else{
	  
    foreach($result as $row){
	$device_id = $row['address_code'];
	$biao_command_code = $row['function_code'];
	$var_len = $row['storage_numbers'];
	
    $all_code = $row['command_code'];
	$all_code2 = $row['command_code2'];

	$meter_model_id = $row['meter_model_id'];
	$meter_model = $row['meter_model'];
	$check_code = $row['check_code'];
    $data_index = $row['data_index'];
	}
	
    
	

$electrical_status = ModDianBiaoHelper::getElectricalStatus();
//$electrical_status=1;
$k = 0;
//while ($k<5){
while ( ($k<1) && ($electrical_status) ) {
$k++;
//echo "$k <br>";
//$electrical_status = ModDianBiaoHelper::getElectricalStatus();
//sleep(1);




echo "<br>all code: $all_code";

$send_all =  exec("sudo /usr/bin/./mod_dianbiao $all_code", $all_output);
//sleep(1);
if(is_array($all_output)==""){
	echo "<script>alert('返回的数据为空！');history.back();</script>";
}else{
 
  $all_nums1 = sizeof($all_output);
  echo "<br>all_arr 1: ".$all_nums1;
  echo "<br>all return code 1:<br>";

  foreach($all_output AS $all_temp){
  echo "$all_temp";
  }

// get return code first time
$hex_u1 = $all_output[3] . $all_output[4] . $all_output[5] . $all_output[6];      //Ua
echo "<br>hex_u1: ".$hex_u1;
$hex_u2 = $all_output[7] . $all_output[8] . $all_output[9] . $all_output[10];     //Ub
echo "<br>hex_u2: ".$hex_u2;
$hex_u3 = $all_output[11] . $all_output[12] . $all_output[13] . $all_output[14];  //Uc
echo "<br>hex_u3: ".$hex_u3;


$hex_Uab = $all_output[15] . $all_output[16] . $all_output[17] . $all_output[18];  //Uab
echo "<br>hex_Uab: ".$hex_Uab;
$hex_Ubc = $all_output[19] . $all_output[20] . $all_output[21] . $all_output[22];  //Ubc
echo "<br>hex_Ubc: ".$hex_Ubc;
$hex_Uca = $all_output[23] . $all_output[24] . $all_output[25] . $all_output[26];  //Uca
echo "<br>hex_Uca: ".$hex_Uca;

$hex_i1 = $all_output[27] . $all_output[28] . $all_output[29] . $all_output[30];  //Ia
echo "<br>hex_i1: ".$hex_i1;
$hex_i2 = $all_output[31] . $all_output[32] . $all_output[33] . $all_output[34];  //Ib
echo "<br>hex_i2: ".$hex_i2;
$hex_i3 = $all_output[35] . $all_output[36] . $all_output[37] . $all_output[38];  //Ic
echo "<br>hex_i3: ".$hex_i3;

$hex_p1 = $all_output[39] . $all_output[40] . $all_output[41] . $all_output[42];  //Pa
echo "<br>hex_p1: ".$hex_p1;
$hex_p2 = $all_output[43] . $all_output[44] . $all_output[45] . $all_output[46];  //Pb
echo "<br>hex_p2: ".$hex_p2;
$hex_p3 = $all_output[47] . $all_output[48] . $all_output[49] . $all_output[50];  //Pc
echo "<br>hex_p3: ".$hex_p3;

echo "<br>";

//send code the second time
$send_all2 =  exec("sudo /usr/bin/./mod_dianbiao $all_code2", $all_output2);
//sleep(1);
if(is_array($all_output2)==""){
	echo "<script>alert('all_output2 返回的数据为空！');history.back();</script>";
    exit;
}
 
  $all_nums2 = sizeof($all_output2);
  echo "<br>all_arr 2: ".$all_nums2;
  echo "<br>all return code 2:<br>";

  foreach($all_output2 AS $all_temp2){
  echo "$all_temp2";
  }
// get return code second time
$hex_pE = $all_output2[3] . $all_output2[4] . $all_output2[5] . $all_output2[6];      //PE
echo "<br>hex_pE: ".$hex_pE;

$hex_Q1 = $all_output2[7] . $all_output2[8] . $all_output2[9] . $all_output2[10];     //Ub
echo "<br>hex_Q1: ".$hex_Q1;
$hex_Q2 = $all_output2[11] . $all_output2[12] . $all_output2[13] . $all_output2[14];  //Uc
echo "<br>hex_Q2: ".$hex_Q2;
$hex_Q3 = $all_output2[15] . $all_output2[16] . $all_output2[17] . $all_output2[18];  //Uab
echo "<br>hex_Q3: ".$hex_Q3;

$hex_QE = $all_output2[19] . $all_output2[20] . $all_output2[21] . $all_output2[22];  //Ubc
echo "<br>hex_QE: ".$hex_QE;
$hex_SE = $all_output2[23] . $all_output2[24] . $all_output2[25] . $all_output2[26];  //Uca
echo "<br>hex_SE: ".$hex_SE;
$hex_cosQ = $all_output2[27] . $all_output2[28] . $all_output2[29] . $all_output2[30];  //Ia
echo "<br>hex_cosQ: ".$hex_cosQ;

$hex_F = $all_output2[31] . $all_output2[32] . $all_output2[33] . $all_output2[34];  //Ib
echo "<br>hex_F: ".$hex_F;

$hex_Ep1 = $all_output2[35] . $all_output2[36] . $all_output2[37] . $all_output2[38];  //Ic
echo "<br>hex_Ep1: ".$hex_Ep1;
$hex_Ep2 = $all_output2[39] . $all_output2[40] . $all_output2[41] . $all_output2[42];  //Pa
echo "<br>hex_Ep2: ".$hex_Ep2;

$hex_Eq1 = $all_output2[43] . $all_output2[44] . $all_output2[45] . $all_output2[46];  //Pb
echo "<br>hex_Eq1: ".$hex_Eq1;
$hex_Eq2 = $all_output2[47] . $all_output2[48] . $all_output2[49] . $all_output2[50];  //Pc
echo "<br>hex_Eq2: ".$hex_Eq2;

echo "<br>";



//echo "start get getMeterInfoValus:<br>";
$rs_info = ModDianBiaoHelper::getMeterInfoValus($info_id);
//select get which electricl_data
    foreach($rs_info as $row_info){
	    $data_select = $row_info['data_select'];
	}
	  //explode $data_select    //example :$data_select = "u1,u2,u3,i1"; 
	  $selArr=explode(',',$data_select); 
	  $sel_num = sizeof($selArr); //cout array numbers or // $sel_num = count(selArr);
	  $sel_var = "";
	  for($l = 0; $l<$sel_num ; $l++){
        //echo $l.':'.$selArr[$l].'<br/>';
		$sel_var = $sel_var.$selArr[$l];
	  }
       
    $sel_u1 =  strstr($sel_var, 'u1');  //$sel_u1 
    if( ($sel_u1 != "") && ($data_select != "") ){
		  // echo "<br>u1--------------------".$sel_u1; 
		   $sel_u1 = 1 ;
	} 
	$sel_u2 =  strstr($sel_var, 'u2');
    if( ($sel_u2 != "") && ($data_select != "") ){
		   //echo "<br>u2--------------------".$sel_u2; 
		   $sel_u2 = 2 ;
	} 
    $sel_u3 =  strstr($sel_var, 'u3');
    if( ($sel_u3 != "") && ($data_select != "") ){
		  // echo "<br>u3--------------------".$sel_u3; 
		   $sel_u3 = 3 ;
	} 
	
	
	$sel_i1 =  strstr($sel_var, 'i1');   //$sel_i1 
    if( ($sel_i1 != "") && ($data_select != "") ){
		   //echo "<br>i1--------------------".$sel_i1; 
		   $sel_i1 = 1 ;
	} 
	$sel_i2 =  strstr($sel_var, 'i2');
    if( ($sel_i2 != "") && ($data_select != "") ){
		   //echo "<br>i2--------------------".$sel_i2; 
		   $sel_i2 = 2 ;
	} 
    $sel_i3 =  strstr($sel_var, 'i3');
    if( ($sel_i3 != "") && ($data_select != "") ){
		   //echo "<br>i3--------------------".$sel_i3; 
		   $sel_i3 = 3 ;
	} 
	
	
	$sel_s1 =  strstr($sel_var, 's1');  //$sel_s1 
    if( ($sel_s1 != "") && ($data_select != "") ){
		   //echo "<br>s1--------------------".$sel_s1; 
		   $sel_s1 = 1 ;
	} 
	$sel_s2 =  strstr($sel_var, 's2');
    if( ($sel_s2 != "") && ($data_select != "") ){
		   //echo "<br>s2--------------------".$sel_s2; 
		   $sel_s2 = 2 ;
	} 
    $sel_s3 =  strstr($sel_var, 's3');
    if( ($sel_s3 != "") && ($data_select != "") ){
		   //echo "<br>s3--------------------".$sel_s1; 
		   $sel_s3 = 3 ;
	}	
	
	$sel_f1 =  strstr($sel_var, 'f1');  //$sel_f1 
    if( ($sel_f1 != "") && ($data_select != "") ){
		   //echo "<br>f1--------------------".$sel_f1; 
		   $sel_f1 = 1 ;
	} 
	$sel_f2 =  strstr($sel_var, 'f2');
    if( ($sel_f2 != "") && ($data_select != "") ){
		   //echo "<br>f2--------------------".$sel_f2; 
		   $sel_f2 = 2 ;
	} 
    $sel_f3 =  strstr($sel_var, 'f3');
    if( ($sel_f3 != "") && ($data_select != "") ){
		   //echo "<br>f3--------------------".$sel_f1; 
		   $sel_f3 = 3 ;
	}
		
//if (in_array("u1", $selArr, true)) {
         //echo "Got u1";
//} 	


$all_u1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u1), 2);
$all_u2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u2), 2);
$all_u3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u3), 2);

$all_i1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i1), 2);
$all_i2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i2), 2);
$all_i3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i3), 2);

$all_s1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p1), 2);
$all_s2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p2), 2);
$all_s3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p3), 2);

$all_f1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_F ), 2); //F
$all_f2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_f2), 2);
$all_f3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_f3), 2);

echo "<br>";

$voltage1 = $all_u1;
$current1 = $all_u1;
$power1 = $all_u1;
$frequency1 = $all_u1;

echo "<br>all_1:";
echo "<br>voltage1 : $voltage1 <br>";
echo " current1 : $current1 <br>";
echo " power1 : $power1 <br>";
echo " frequency1 : $frequency1 <br>";

$voltage2 = $all_u2;
$current2 = $all_i2;
$power2 = $all_s2;
$frequency2 = $all_f2;

echo "<br>all_2:";
echo "<br>voltage2 : $voltage2 <br>";
echo " current2 : $current2 <br>";
echo " power2 : $power2 <br>";
echo " frequency2 : $frequency2 <br>";

$voltage3 = $all_u3;
$current3 = $all_i3;
$power3 = $all_s3;
$frequency3 = $all_f3;

echo "<br>all_3:";
echo "<br>voltage3 : $voltage3 <br>";
echo " current3 : $current3 <br>";
echo " power3 : $power3 <br>";
echo " frequency3 : $frequency3 <br>";

}


  

	  
	  
	  
//$devsce_id = "01";  //address_code  // unique id address of individual biao 
//$biao_command_code = "03";  //function_code  // command code to read holding register  

//$u2_address = "00 19"; // address of voltage variable
//$i2_address = "00 25"; // address of voltage variable
//$s2_address = "00 43"; // address of voltage variable
//$f2_address = "00 4b"; // address of voltage variable


//$var_len = "00 02"; // length of variable

/*//explode $data_index    //example :$data_index = "u1-10, u2-15, u3-20, i1-11, i2-17, i3-23, s1-xx, s2-xx, s3-xx, f1-xx, f2-xx, f3-xx";  
	$strArr=explode(',',$data_index); 
	$arr_num = sizeof($strArr); //cout array numbers or // $arr_num = count($strArr);
	for($i = 0; $i<$arr_num ; $i++){
        //echo $i.':'.$strArr[$i].'<br/>';
    }
	
    
	$var_u1 = $strArr[0]; 
	$u1_arr = explode("-",$var_u1);
	$u1_address = $u1_arr[1];   // explode $u1_address
	
	
	$var_u2 = $strArr[1]; 
	$u2_arr = explode("-",$var_u2);
	$u2_address = $u2_arr[1];
	
	
	$var_u3 = $strArr[2]; 
	$u3_arr = explode("-",$var_u3);
	$u3_address = $u3_arr[1];
    
	
    $var_i1 = $strArr[3]; 
	$i1_arr = explode("-",$var_i1);
	$i1_address = $i1_arr[1];   // explode $i1_address
	
	$var_i2 = $strArr[4]; 
	$i2_arr = explode("-",$var_i2);
	$i2_address = $i2_arr[1]; 
	
	$var_i3 = $strArr[5]; 
	$i3_arr = explode("-",$var_i3);
	$i3_address = $i3_arr[1];	
	
	
    $var_s1 = $strArr[6]; 
	$s1_arr = explode("-",$var_s1);
	$s1_address = $s1_arr[1];   // explode $s1_address 
	
	$var_s2 = $strArr[7]; 
	$s2_arr = explode("-",$var_s2);
	$s2_address = $s2_arr[1]; 
	
	$var_s3 = $strArr[8]; 
	$s3_arr = explode("-",$var_s3);
	$s3_address = $s3_arr[1];	
	
	$var_f1 = $strArr[6]; 
	$f1_arr = explode("-",$var_f1);
	$f1_address = $f1_arr[1];   // explode $f1_address 
	
	$var_f2 = $strArr[7]; 
	$f2_arr = explode("-",$var_f2);
	$f2_address = $f2_arr[1]; 
	
	$var_f3 = $strArr[8]; 
	$f3_arr = explode("-",$var_f3);
	$f3_address = $f3_arr[1];	



//echo "start explode/------------------------------------------ ";
   
//explode $check_code   //example :$check_code = "u1-10, u2-15, u3-20, i1-11, i2-17, i3-23, s1-xx, s2-xx, s3-xx, f1-xx, f2-xx, f3-xx"; 
	$strArr_check=explode(',',$check_code); 
	$arr_num_check = sizeof($strArr_check); //cout array numbers or // $arr_num_check = count($strArr_check);
	for($i = 0; $i<$arr_num_check ; $i++){
        //echo $i.':'.$strArr_check[$i].'<br/>';
    }
	
    
	$var_u1_check = $strArr_check[0]; 
	$u1_arr_check = explode("-",$var_u1_check);
	$u1_checksum = $u1_arr_check[1];   // explode $u1_checksum
	
	
	$var_u2_check = $strArr_check[1]; 
	$u2_arr_check = explode("-",$var_u2_check);
	$u2_checksum = $u2_arr_check[1];
	
	
	$var_u3_check = $strArr_check[2]; 
	$u3_arr_check = explode("-",$var_u3_check);
	$u3_checksum = $u3_arr_check[1];
    
	
    $var_i1_check = $strArr_check[3]; 
	$i1_arr_check = explode("-",$var_i1_check);
	$i1_checksum = $i1_arr_check[1];   // explode $i1_checksum
	
	$var_i2_check = $strArr_check[4]; 
	$i2_arr_check = explode("-",$var_i2_check);
	$i2_checksum = $i2_arr_check[1]; 
	
	$var_i3_check = $strArr_check[5]; 
	$i3_arr_check = explode("-",$var_i3_check);
	$i3_checksum = $i3_arr_check[1];	
	
	
    $var_s1_check = $strArr_check[6]; 
	$s1_arr_check = explode("-",$var_s1_check);
	$s1_checksum = $s1_arr_check[1];   // explode $s1_checksum 
	
	$var_s2_check = $strArr_check[7]; 
	$s2_arr_check = explode("-",$var_s2_check);
	$s2_checksum = $s2_arr_check[1]; 
	
	$var_s3_check = $strArr_check[8]; 
	$s3_arr_check = explode("-",$var_s3_check);
	$s3_checksum = $s3_arr_check[1];	
	
	$var_f1_check = $strArr_check[6]; 
	$f1_arr_check = explode("-",$var_f1_check);
	$f1_checksum = $f1_arr_check[1];   // explode $f1_checksum 
	
	$var_f2_check = $strArr_check[7]; 
	$f2_arr_check = explode("-",$var_f2_check);
	$f2_checksum = $f2_arr_check[1]; 
	
	$var_f3_check = $strArr_check[8]; 
	$f3_arr_check = explode("-",$var_f3_check);
	$f3_checksum = $f3_arr_check[1];	

//$u1_checksum = "15 cc"; // checksum for u2
//$i1_checksum = "d5 c0"; // checksum for i2
//$s1_checksum = "35 df"; // checksum for s2
//$f1_checksum = "b4 1d"; // checksum for f2

//echo $u1_checksum ; // checksum for u2
//echo $i1_checksum ; // checksum for i2
//echo $s1_checksum ; // checksum for s2
//echo $f1_checksum ; // checksum for f2

//$send = exec("sudo /usr/bin/./mod_meter_connect 01 03 00 19 00 02 15 cc", $output);	  
*/	  
	  
/*//-----------------------------------------------
//  frist array data to the table joomla3_electrical u1, i1, s1, f1
$var_address = $u1_address;
$checksum = $u1_checksum;
unset($u1_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $u1_output);
//sleep(1);
if(is_array($u1_output)){
	echo "<script>alert('返回的电压数据为空！');history.back();</script>";
}else{
$hexString = $u1_output[3] . $u1_output[4] . $u1_output[5] . $u1_output[6];
$u1 = ModDianBiaoHelper::hexStringTo32Float($hexString);
}

$var_address = $i1_address;
$checksum = $i1_checksum;
unset($i1_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $i1_output);
if(is_array($i1_output)){
	echo "<script>alert('返回的电流数据为空！');history.back();</script>";
}else{
$hexString = $i1_output[3] . $i1_output[4] . $i1_output[5] . $i1_output[6];
$i1 = ModDianBiaoHelper::hexStringTo32Float($hexString);
}


$var_address = $s1_address;
$checksum = $s1_checksum;
unset($s1_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $s1_output);
if(is_array($s1_output)){
	echo "<script>alert('返回的功率数据为空！');history.back();</script>";
}else{
$hexString = $s1_output[3] . $s1_output[4] . $s1_output[5] . $s1_output[6];
$s1 = ModDianBiaoHelper::hexStringTo32Float($hexString);
}


$var_address = $f1_address;
$checksum = $f1_checksum;
unset($f1_output);
$send = exec("sudo /usr/bin/./mod_dianbiao $device_id $biao_command_code $var_address $var_len $checksum", $f1_output);
if(is_array($f1_output)){
	echo "<script>alert('返回的频率数据为空！');history.back();</script>";
}else{
$hexString = $f1_output[3] . $f1_output[4] . $f1_output[5] . $f1_output[6];
$f1 = ModDianBiaoHelper::hexStringTo32Float($hexString);
}


echo "<br> u1 : $u1 <br>";
echo " i1 : $i1 <br>";
echo " s1 : $s1 <br>";
echo " f1 : $f1 <br>";
*/




/*//-----------------------------------------------
// second array to the table joomla3_electrical u2, i2, s2, f2
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

echo " <br>/ u2 [send code]:".$device_id." ".$biao_command_code." ".$var_address." ".$var_len." ".$checksum;
$num = sizeof($u1_output);
 echo "<br>retrun arr nums:".$num;
 echo "<br>return code:";
 foreach($u2_output AS $u2_temp){
   echo  " $u2_temp ";
 }
echo "<br>u2 : $u2 <br>";
echo " i2 : $i2 <br>";
echo " s2 : $s2 <br>";
echo " f2 : $f2 <br>";
*/




/*-----------------------------------------------
// third array to the table joomla3_electrical  u3, i3, s3, f3
$var_address = $u3_address;
$checksum = $u3_checksum;
unset($u3_output);
$send = exec("sudo /usr/bin/./mod_meter_connect $device_id $biao_command_code $var_address $var_len $checksum", $u3_output);
foreach($u3_output AS $u3_temp){
//echo " $u3_temp ";
}

$hexString = $u3_output[3] . $u3_output[4] . $u3_output[5] . $u3_output[6];
$u3 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $i3_address;
$checksum = $i3_checksum;
unset($i3_output);
$send = exec("sudo /usr/bin/./mod_meter_connect $device_id $biao_command_code $var_address $var_len $checksum", $i3_output);
$hexString = $i3_output[3] . $i3_output[4] . $i3_output[5] . $i3_output[6];
$i3 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $s3_address;
$checksum = $s3_checksum;
unset($s3_output);
$send = exec("sudo /usr/bin/./mod_meter_connect $device_id $biao_command_code $var_address $var_len $checksum", $s3_output);
$hexString = $s3_output[3] . $s3_output[4] . $s3_output[5] . $s3_output[6];
$s3 = ModDianBiaoHelper::hexStringTo32Float($hexString);

$var_address = $f3_address;
$checksum = $f3_checksum;
unset($f3_output);
$send = exec("sudo /usr/bin/./mod_meter_connect $device_id $biao_command_code $var_address $var_len $checksum", $f3_output);
$hexString = $f3_output[3] . $f3_output[4] . $f3_output[5] . $f3_output[6];
$f3 = ModDianBiaoHelper::hexStringTo32Float($hexString);


echo " u3 : $u3 <br>";
echo " i3 : $i3 <br>";
echo " s3 : $s3 <br>";
echo " f3 : $f3 <br>";

$voltage3 = $u3;
$current3 = $i3;
$power3 = $s3;
$frequency3 = $f3; 
*/



/*---------------------------------------------*/

date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;

// insert to database
//ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $u2, $i2, $s2, $f2);
ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $voltage1, $current1, $power1, $frequency1, $voltage2, $current2, $power2, $frequency2, $voltage3, $current3, $power3, $frequency3);


}//while

//}//foreach
// call new web page, then exit

if ($electrical_status) {
  $lines = file("http://127.0.0.1/joomla/index.php/meter-connect");
}

require(JModuleHelper::getLayoutPath('mod_meter_connect', 'default'));
	}//else no meter_model
}//else //meter_model == "-1"
