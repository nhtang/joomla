<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_connect
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
//error_reporting( E_ALL&~E_NOTICE );
defined('_JEXEC') or die;
sleep(0.5);
// Include the functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

$wait_time = "C8";

JHTML::stylesheet('styles.css','modules/mod_meter_connect/css/');

//$electrical_status = JRequest::getVar('electrical_status', '-1');
//$quantity = JRequest::getVar('quantity', '0');

$info_id = JRequest::getVar('info_id', '-1');  //get info_id 
$location_id = JRequest::getVar('location_id', '-1');  //get location_id 
$meter_address = JRequest::getVar('meter_address', '-1');  //get meter_address 
$meter_model = JRequest::getVar('meter_model', '-1');  //get meter_model 

$get_time = JRequest::getVar('fresh_time', '-1');  //get fresh_time 
//echo "get_time:$get_time";

$fresh_time = ModDianBiaoHelper::checkFreshTime($get_time);
//echo "fresh_time:$fresh_time";


//system("gpio")
$getChecklStatus = ModDianBiaoHelper::getChecklStatus();
if($getChecklStatus == ""){
	echo "<h3><font color=#FF2200 >全部电表状态为 ：OFF !  暂停采集数据！</font></h3>";	
}
   
$sql = "select * from joomla3_meter_info  order by info_id Asc";
$rs = mysql_query($sql);
//$rsnum = mysql_num_rows($rs);
//$row_loop = mysql_fetch_array($rs);
 while($row_loop=mysql_fetch_array($rs)){
	$location_id = $row_loop['location_id'];
	$meter_address = $row_loop['meter_address'];
	$meter_model = $row_loop['meter_model'];
	
	//list meter_model for model_key
	switch($meter_model){
		
		case "PD1688E-ASY":
		  $model_key = 1;
		  break;
		  
		case "1688E-ASY":
		  $model_key = 2;
		  break;
		  
		default:
          break;		
	}
	
	
	
	$electrical_status = ModDianBiaoHelper::getElectricalStatus($location_id, $meter_address);
	$switch = $electrical_status;  //The $switch For  default.php
    //$electrical_status = 1 or 0;
if($electrical_status == "1"){  //check electrical_status 
    //sleep(1);
	
	
  $result = ModDianBiaoHelper::getMeterModelValus($meter_model);
	
if($result == ""){
    echo "<script>alert('数据库中还没有此电表型号为：[  $meter_model ]  的记录！请先录入型号！');history.back();</script>";
}else{
	  
    foreach($result as $row){
	$device_id = $meter_address ;
	$biao_command_code = $row['function_code'];
	$var_len = $row['storage_numbers'];
	
    $all_code = $row['command_code'];
	$all_code2 = $row['command_code2'];

	$meter_model_id = $row['meter_model_id'];
	$meter_model = $row['meter_model'];
	//$check_code = $row['check_code'];
    $data_index = $row['data_index'];
	}
	
  // Get CRC check code  A-------------------------------------------
  $merger =$device_id . $biao_command_code . $all_code ;
  $merger_return = ModDianbiaoHelper::trimall($merger); 
  //echo "<br>merger_return : $merger_return<br>";  
  $P_merger = pack('H*', $merger_return);
  $crc = ModDianbiaoHelper::crc16($P_merger); 
  $ch_A=sprintf('%02x%02x', $crc%256, floor($crc/256));
  $check_A = ModDianbiaoHelper::convert_code($ch_A);
  //echo "<br>check_A : $check_A<br>";
  
  
  // Get CRC check code  B------------------------------------------
  $merger2 =$device_id . $biao_command_code . $all_code2 ;
  $merger_return2 = ModDianbiaoHelper::trimall($merger2); 
  //echo "<br>merger_return : $merger_return<br>";  
  $P_merger2 = pack('H*', $merger_return2);
  $crc2 = ModDianbiaoHelper::crc16($P_merger2); 
  $ch_B=sprintf('%02x%02x', $crc2%256, floor($crc2/256));
  $check_B = ModDianbiaoHelper::convert_code($ch_B);
  //echo "<br>check_B : $check_B<br>";


$all_A = $device_id ." ". $biao_command_code ." ". $all_code ." ". $check_A ;
$all_B = $device_id ." ". $biao_command_code ." ". $all_code2 ." ". $check_B ;

//Classify meter_model retun code
if($model_key == 1){   //meter_model == PD1688E-ASY

 unset($all_output);
 //echo "<br>all code: $all_code";
 //$send_all =  exec("sudo /usr/bin/./mod_dianbiao $all_code $wait_time", $all_output);

 echo "<br>all codeA: $all_A";
 $send_A =  exec("sudo /usr/bin/./mod_dianbiao $all_A $wait_time", $all_output);
 sleep(0.3);

  $all_nums1 = sizeof($all_output);

  echo "<br>all_arr 1: ".$all_nums1;
  echo "<br>all return code 1:<br>";
  
  $all_temp ="";
    foreach($all_output AS $temp){
	  $all_temp = $all_temp . $temp;
        // echo "$all_temp + ";
    }
	  echo "$all_temp";
  
  
  //$all_temp="020330BEAE436CBC92436F81964371503E43CE386943D0339643CF014241587714403AA0A94049745944881FA6440C421C4366EC4C";
  
  // Check return CRC -------------------------------------------
  $temp_len = strlen($all_temp);
  $beforeCode = substr($all_temp , 0, $temp_len-4);
  $behindcode = substr($all_temp , $temp_len-4, $temp_len);
  echo "<br>beforeCode : $beforeCode";
  echo "<br>behindcode : $behindcode";
  
  $P_Code = pack('H*', $beforeCode);
  $crc_Code = ModDianbiaoHelper::crc16($P_Code); 
  $crc_Code=sprintf('%02x%02x', $crc_Code%256, floor($crc_Code/256));
  $crc_Code = strtoupper($crc_Code) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
  
    if($crc_Code != $behindcode){
	  $i = 1;
	  while ( $i<3 && ($crc_Code != $behindcode)){
		unset($all_output);  
	    $send_A = exec("sudo /usr/bin/./mod_dianbiao $all_A $wait_time", $all_output);
        sleep(0.5);  //set more time sleep  0.3 -> 0.5
	  
	    $all_nums1 = sizeof($all_output);

  	    echo "<br>all_arr 1: ".$all_nums1;
  	    echo "<br>all return code 1:<br>";

 	    $all_temp ="";
        foreach($all_output AS $temp){
	        $all_temp = $all_temp . $temp;
            //echo "$all_temp + ";
        }
		 echo "$all_temp";
  
  	    // Check return CRC in while -------------------------------------------
  	    $temp_len = strlen($all_temp);
  	    $beforeCode = substr($all_temp , 0, $temp_len-4);
  	    //$behindcode = substr($all_temp , $temp_len-4, $temp_len);
		$behindcode = $all_output[$all_nums1 - 1].$all_output[$all_nums1]; 
  	    echo "<br>beforeCode : $beforeCode";
  	    echo "<br>behindcode : $behindcode";
  
  	    $P_Code = pack('H*', $beforeCode);
  	    $crc_Code = ModDianbiaoHelper::crc16($P_Code); 
  	    $crc_Code=sprintf('%02x%02x', $crc_Code%256, floor($crc_Code/256));
  	    $crc_Code = strtoupper($crc_Code) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
	  
	    $i++;
	  }//while
	  if (($i = 3)&&($crc_Code != $behindcode)){ echo "<font color=#ff2200>表 [$device_id] 电压、电流、功率 返回数据有误，暂停采集 表 [$device_id] 数据，请检查！</font>";}
    }else{
	  


	  
// get return code first time ----------------------------------------------------------------*/
$hex_u1 =  $all_output[5] . $all_output[6]. $all_output[3] . $all_output[4];      //Ua
$hex_u2 = $all_output[9] . $all_output[10] . $all_output[7] . $all_output[8] ;     //Ub
$hex_u3 =  $all_output[13] . $all_output[14] . $all_output[11] . $all_output[12] ;  //Uc

$hex_Uab =  $all_output[17] . $all_output[18] . $all_output[15] . $all_output[16] ;  //Uab
$hex_Ubc =  $all_output[21] . $all_output[22] . $all_output[19] . $all_output[20] ;  //Ubc
$hex_Uca =  $all_output[25] . $all_output[26] . $all_output[23] . $all_output[24] ;  //Uca

$hex_i1 =  $all_output[29] . $all_output[30] . $all_output[27] . $all_output[28] ;  //Ia
$hex_i2 =  $all_output[33] . $all_output[34] . $all_output[31] . $all_output[32] ;  //Ib
$hex_i3 =  $all_output[37] . $all_output[38] . $all_output[35] . $all_output[36] ;  //Ic

$hex_p1 = $all_output[41] . $all_output[42] . $all_output[39] . $all_output[40] ;  //Pa
$hex_p2 = $all_output[45] . $all_output[46] .  $all_output[43] . $all_output[44] ;  //Pb
$hex_p3 = $all_output[49] . $all_output[50] . $all_output[47] . $all_output[48] ;  //Pc

echo "<br>";


//time waitout for meter retrun data

unset($all_output2);
//send code the second time
//echo "<br>all code 2 : $all_code2";
//$send_all2 =  exec("sudo /usr/bin/./mod_dianbiao $all_code2 $wait_time", $all_output2);

echo "<br>all codeB: $all_B";
$send_B =  exec("sudo /usr/bin/./mod_dianbiao $all_B $wait_time", $all_output2);
sleep(0.3);

  $all_nums2 = sizeof($all_output2);
  echo "<br>all_arr 2: ".$all_nums2;
  echo "<br>all return code 2:<br>";

  $all_temp2="";
  foreach($all_output2 AS $temp2){
	  $all_temp2 = $all_temp2 . $temp2;
      //echo "$all_temp2 + ";
  }
    echo "$all_temp2";
  
  //$all_temp2 ="020330BEAE436CBC92436F81964371503E43CE386943D0339643CF014241587714403AA0A94049745944881FA6440C421C4366EC4C";
  
  // Check return CRC 2 -------------------------------------------
  $temp_len2 = strlen($all_temp2);
  $beforeCode2 = substr($all_temp2 , 0, $temp_len2-4);
  $behindcode2 = substr($all_temp2 , $temp_len2-4, $temp_len2);
  echo "<br>beforeCode2 : $beforeCode2";
  echo "<br>behindcode2 : $behindcode2";
  
  $P_Code2 = pack('H*', $beforeCode2);
  $crc_Code2 = ModDianbiaoHelper::crc16($P_Code2); 
  $crc_Code2=sprintf('%02x%02x', $crc_Code2%256, floor($crc_Code2/256));
  $crc_Code2 = strtoupper($crc_Code2) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
  
    if($crc_Code2 != $behindcode2){
	  $i = 1;
	  while ( $i<3 && ($crc_Code2 != $behindcode2)){
		unset($all_output2);
	    $send_B = exec("sudo /usr/bin/./mod_dianbiao $all_B $wait_time", $all_output2);
        sleep(0.5);  //set more time sleep  0.3 -> 0.5
	  
	    $all_nums2 = sizeof($all_output2);

  	    echo "<br>all_arr 2: ".$all_nums2;
  	    echo "<br>all return code 2:<br>";

 	    $all_temp2="";
        foreach($all_output2 AS $temp2){
	        $all_temp2 = $all_temp2 . $temp2;
            //echo "$all_temp2 + ";
        }
		  echo "$all_temp2";
  
  	    // Check return CRC in while -------------------------------------------
  	    $temp_len2 = strlen($all_temp2);
  	    $beforeCode2 = substr($all_temp2 , 0, $temp_len2-4);
  	    $behindcode2 = substr($all_temp2 , $temp_len2-4, $temp_len2);
  	    echo "<br>beforeCode2 : $beforeCode2";
  	    echo "<br>behindcode2 : $behindcode2";
  
  	    $P_Code2 = pack('H*', $beforeCode2);
  	    $crc_Code2 = ModDianbiaoHelper::crc16($P_Code2); 
  	    $crc_Code2 = sprintf('%02x%02x', $crc_Code2%256, floor($crc_Code2/256));
  	    $crc_Code2 = strtoupper($crc_Code2) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
	  
	    $i++;
	  }//while
	  if (($i = 3)&&($crc_Code2 != $behindcode2)){ echo "<font color=#ff2200>表 [$device_id]  频率、电能、总功率 返回数据有误，暂停采集 表 [$device_id]  数据！，请检查！</font>";}
	  
    }else{
     
$hex_pE =  $all_output2[3] . $all_output2[4] . $all_output2[5] . $all_output2[6] ;      //PE

$hex_Q1 =  $all_output2[7] . $all_output2[8] . $all_output2[9] . $all_output2[10] ;     //Ub
$hex_Q2 = $all_output2[11] . $all_output2[12] . $all_output2[13] . $all_output2[14] ;  //Uc
$hex_Q3 = $all_output2[15] . $all_output2[16] . $all_output2[17] . $all_output2[18] ;  //Uab

$hex_QE = $all_output2[19] . $all_output2[20] . $all_output2[21] . $all_output2[22] ;  //Ubc
$hex_SE =  $all_output2[23] . $all_output2[24] . $all_output2[25] . $all_output2[26] ;  //Uca
$hex_cosQ = $all_output2[27] . $all_output2[28] . $all_output2[29] . $all_output2[30] ;  //Ia

$hex_F =  $all_output2[31] . $all_output2[32] . $all_output2[33] . $all_output2[34] ;  //Ib

$hex_Ep1 = $all_output2[35] . $all_output2[36] . $all_output2[37] . $all_output2[38] ;  //Ic
$hex_Ep2 = $all_output2[39] . $all_output2[40] .  $all_output2[41] . $all_output2[42] ;  //Pa

$hex_Eq1 = $all_output2[43] . $all_output2[44] . $all_output2[45] . $all_output2[46] ;  //Pb
$hex_Eq2 = $all_output2[47] . $all_output2[48] . $all_output2[49] . $all_output2[50] ;  //Pc




// covernt hex to 32Float string ------------------------------------------------------------*/
$all_u1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u1), 4);
$all_u2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u2), 4);
//$all_u2 = ModDianBiaoHelper::hexStringTo32Float("270F4361");
$all_u3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_u3), 4);

$all_i1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i1), 4);
$all_i2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i2), 4);
$all_i3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_i3), 4);

$all_s1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p1), 4);
$all_s2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p2), 4);
$all_s3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_p3), 4);

$all_f1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_F ), 4); //F
$all_f2 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_f2), 4);
$all_f3 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_f3), 4);

$all_pE = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_pE), 4);
$all_Ep1 = number_format(ModDianBiaoHelper::hexStringTo32Float($hex_Ep1), 4);

echo "<br>";

$voltage1 = $all_u1;
$current1 = $all_i1;
$power1 = $all_s1;
$frequency1 = $all_f1;


/*
echo "<br>all_1:";
echo "<br>voltage1 : $voltage1 <br>";
echo " current1 : $current1 <br>";
echo " power1 : $power1 <br>";
echo " frequency1 : $frequency1 <br>";
*/

$voltage2 = $all_u2;
$current2 = $all_i2;
$power2 = $all_s2;
$frequency2 = $all_f2;

/*
echo "<br>all_2:";
echo "<br>voltage2 : $voltage2 <br>";
echo " current2 : $current2 <br>";
echo " power2 : $power2 <br>";
echo " frequency2 : $frequency2 <br>";
*/

$voltage3 = $all_u3;
$current3 = $all_i3;
$power3 = $all_s3;
$frequency3 = $all_f3;

/*
echo "<br>all_3:";
echo "<br>voltage3 : $voltage3 <br>";
echo " current3 : $current3 <br>";
echo " power3 : $power3 <br>";
echo " frequency3 : $frequency3 <br>";
*/

$pE = $all_pE;
$Ep1 = $all_Ep1;

echo "<br>pE: $pE";
echo "<br>Ep+ : $Ep1";


date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;

// insert to database ------------------------------------------*/
//ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $u2, $i2, $s2, $f2);
ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $voltage1, $current1, $power1, $frequency1, $voltage2, $current2, $power2, $frequency2, $voltage3, $current3, $power3, $frequency3, $pE, $Ep1);

       }// Check Return CRCB end if
	  }// Check Return CRCA end if




}else{   // /*Classify meter_model----------------------------------------------------*/
  unset($all_output);
  //echo "<br>all code: $all_code";
  //$send_all =  exec("sudo /usr/bin/./mod_dianbiao $all_code", $all_output);

  echo "<br>all codeA: $all_A";
  $send_A =  exec("sudo /usr/bin/./mod_dianbiao $all_A $wait_time", $all_output);
  sleep(0.3);

  $all_nums1 = sizeof($all_output);

  echo "<br>all_arr Classify: ".$all_nums1;
  echo "<br>all return code Classify:<br>";
  
  $all_temp ="";
  foreach($all_output AS $temp){
	  $all_temp = $all_temp . $temp;
      //echo "$all_temp + ";
  }
    echo "$all_temp";
  
  // Check return CRC model_key == 2-------------------------------------------
  $temp_len = strlen($all_temp);
  $beforeCode = substr($all_temp , 0, $temp_len-4);
  $behindcode = substr($all_temp , $temp_len-4, $temp_len);
  echo "<br>beforeCode : $beforeCode";
  echo "<br>behindcode : $behindcode";
  
  $P_Code = pack('H*', $beforeCode);
  $crc_Code = ModDianbiaoHelper::crc16($P_Code); 
  $crc_Code=sprintf('%02x%02x', $crc_Code%256, floor($crc_Code/256));
  $crc_Code = strtoupper($crc_Code) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
  
    if($crc_Code != $behindcode){
	  $i = 1;
	  while ( $i<3 && ($crc_Code != $behindcode)){
		unset($all_output);  
	    $send_A = exec("sudo /usr/bin/./mod_dianbiao $all_A $wait_time", $all_output);
        sleep(0.5);  //set more time sleep  0.3 -> 0.5
	  
	    $all_nums1 = sizeof($all_output);

  	    echo "<br>all_arr Classify while: ".$all_nums1;
  	    echo "<br>all return code Classify while:<br>";

 	    $all_temp ="";
        foreach($all_output AS $temp){
	        $all_temp = $all_temp . $temp;
            //echo "$all_temp + ";
        }
		 echo "$all_temp";
  
  	    // Check return CRC in while -------------------------------------------
  	    $temp_len = strlen($all_temp);
  	    $beforeCode = substr($all_temp , 0, $temp_len-4);
		$behindcode2 = substr($all_temp , $temp_len-4, $temp_len); 
  	    echo "<br>beforeCode : $beforeCode";
  	    echo "<br>behindcode : $behindcode";
  
  	    $P_Code = pack('H*', $beforeCode);
  	    $crc_Code = ModDianbiaoHelper::crc16($P_Code); 
  	    $crc_Code=sprintf('%02x%02x', $crc_Code%256, floor($crc_Code/256));
  	    $crc_Code = strtoupper($crc_Code) ;  //UPPER string $crc_Code :(e4c4 -> E4C4)
	  
	    $i++;
	  }//while
	  if (($i = 3)&&($crc_Code != $behindcode)){ echo "<font color=#ff2200>表 [$device_id] 电压、电流、功率 返回数据有误，暂停采集 表 [$device_id] 数据，请检查！</font>";}
    }else{
  
// get return code  model_key == 2  ------------------------------------------------------------------*/
$p_u =  $all_output[3];  //Power of U
$p_i =  $all_output[4];  //Power of I
$p_s =  $all_output[5];  //Power of P
$p_E =  $all_output[6];  //Power of E

$hex_u1 =  $all_output[7] . $all_output[8];      //Ua
$hex_u2 =  $all_output[9] . $all_output[10];     //Ub
$hex_u3 =  $all_output[11] . $all_output[12];  //Uc

$hex_Uab =  $all_output[13] . $all_output[14];  //Uab
$hex_Ubc =  $all_output[15] . $all_output[16];  //Ubc
$hex_Uca =  $all_output[17] . $all_output[18];  //Uca

$hex_i1 =  $all_output[19] . $all_output[20];  //Ia
$hex_i2 =  $all_output[21] . $all_output[22];  //Ib
$hex_i3 =  $all_output[23] . $all_output[24];  //Ic

$hex_p1 = $all_output[25] . $all_output[26];  //Pa
$hex_p2 = $all_output[27] . $all_output[28];  //Pb
$hex_p3 = $all_output[29] . $all_output[30];  //Pc

$hex_pE = $all_output[31] . $all_output[32];  //Ps\pE

$hex_Qa = $all_output[33] . $all_output[34];  //Qa
$hex_Qb = $all_output[35] . $all_output[36];  //Qb
$hex_Qc = $all_output[37] . $all_output[38];  //Qc

$hex_Qs = $all_output[39] . $all_output[40];  //Qs

$hex_PFa = $all_output[41] . $all_output[42];  //PFa
$hex_PFb = $all_output[43] . $all_output[44];  //PFb
$hex_PFc = $all_output[45] . $all_output[46];  //PFc

$hex_PFs = $all_output[47] . $all_output[48];  //PFs

$hex_Sa = $all_output[49] . $all_output[50];  //Sa
$hex_Sb = $all_output[51] . $all_output[52];  //Sb
$hex_Sc = $all_output[53] . $all_output[54];  //Sc

$hex_Ss = $all_output[55] . $all_output[56];  //Ss

$hex_FR = $all_output[57] . $all_output[58];  //FR

$hex_WPP = $all_output[59] . $all_output[60] . $all_output[61] . $all_output[62];  //WPP
$hex_WPN = $all_output[63] . $all_output[64] . $all_output[65] . $all_output[66];  //WPN
$hex_WQP = $all_output[67] . $all_output[68] . $all_output[69] . $all_output[70] ;  //WQP
$hex_WQN = $all_output[71] . $all_output[72] . $all_output[73] . $all_output[74] ;  //WQN

$hex_Ep1 = $all_output[75] . $all_output[76] . $all_output[77] . $all_output[78];  //EPP \ Ep+
$hex_Ep2 = $all_output[79] . $all_output[80] . $all_output[81] . $all_output[82];  //EPN \ Ep-
$hex_EQP = $all_output[83] . $all_output[84] . $all_output[85] . $all_output[86];  //EQP
$hex_EQN = $all_output[87] . $all_output[88] . $all_output[89] . $all_output[90];  //EQN
 
echo "<br>";	
		

// covernt hex to 32Float string ------------------------------------------------------------*/
$all_u1 =hexdec($hex_u1);
$all_u2 =hexdec($hex_u2);
//$all_u2 =hexdec("270F4361";
$all_u3 =hexdec($hex_u3);

$all_i1 =hexdec($hex_i1);
$all_i2 =hexdec($hex_i2);
$all_i3 =hexdec($hex_i3);

$all_s1 =hexdec($hex_p1);
$all_s2 =hexdec($hex_p2);
$all_s3 =hexdec($hex_p3);

$all_f1 =hexdec($hex_FR )/100; //F

$all_pE =hexdec($hex_pE);
$all_Ep1 =ModDianBiaoHelper::hexStringTo32Float($hex_Ep1);

echo "<BR>all_u1: $all_u1";
echo "<BR>all_u2: $all_u2";
echo "<BR>all_u3: $all_u3";
echo "<BR>all_i1: $all_i1";
echo "<BR>all_i2: $all_i2";
echo "<BR>all_i3: $all_i3";
echo "<BR>all_s1: $all_s1";
echo "<BR>all_s2: $all_s2";
echo "<BR>all_s3: $all_s3";
echo "<BR>all_f1: $all_f1";
echo "<BR>all_pE: $all_pE";
echo "<BR>all_Ep1: $all_Ep1";

//clsssify model_key for U、I、P data-----------------------------------------
    
      $Mu1 = pow(10, $p_u);
      $Mu2 = pow(10, 4);
      $all_u1 = $all_u1 / $Mu2 * $Mu1;
      $all_u2 = $all_u2 / $Mu2 * $Mu1;
	  $all_u3 = $all_u3 / $Mu2 * $Mu1;
	
	  $Mi1 = pow(10, $p_i);
      $Mi2 = pow(10, 4);
      $all_i1 = $all_i1 / $Mi2 * $Mi1;
	  $all_i2 = $all_i2 / $Mi2 * $Mi1;
	  $all_i3 = $all_i3 / $Mi2 * $Mi1;
	
	  $Ms1 = pow(10, $p_s);
      $Ms2 = pow(10, 4);
      $all_s1 = $all_s1 / $Ms2 * $Ms1;
  	  $all_s2 = $all_s2 / $Ms2 * $Ms1;
	  $all_s3 = $all_s3 / $Ms2 * $Ms1;	
	  $all_pE = $all_pE / $Ms2 * $Ms1;	

echo "<br>";

$voltage1 = $all_u1;
$current1 = $all_i1;
$power1 = $all_s1;
$frequency1 = $all_f1;


/*
echo "<br>all_1:";
echo "<br>voltage1 : $voltage1 <br>";
echo " current1 : $current1 <br>";
echo " power1 : $power1 <br>";
echo " frequency1 : $frequency1 <br>";
*/

$voltage2 = $all_u2;
$current2 = $all_i2;
$power2 = $all_s2;
$frequency2 = $all_f2;

/*
echo "<br>all_2:";
echo "<br>voltage2 : $voltage2 <br>";
echo " current2 : $current2 <br>";
echo " power2 : $power2 <br>";
echo " frequency2 : $frequency2 <br>";
*/

$voltage3 = $all_u3;
$current3 = $all_i3;
$power3 = $all_s3;
$frequency3 = $all_f3;

/*
echo "<br>all_3:";
echo "<br>voltage3 : $voltage3 <br>";
echo " current3 : $current3 <br>";
echo " power3 : $power3 <br>";
echo " frequency3 : $frequency3 <br>";
*/

$pE = $all_pE;
$Ep1 = $all_Ep1;

echo "<br>pE: $pE";
echo "<br>Ep+ : $Ep1";


date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;

// insert to database ------------------------------------------*/
//ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $u2, $i2, $s2, $f2);
ModDianBiaoHelper::insertElectricalValues($datetime, $location_id, $meter_address, $voltage1, $current1, $power1, $frequency1, $voltage2, $current2, $power2, $frequency2, $voltage3, $current3, $power3, $frequency3, $pE, $Ep1);

  }// Check Return CRC model_key=2 end if
	
} ///*end if  Classify meter_model----------------------------------------------------*/	  
	  
} //else check no meter_model	


}  // if check $electrical_status 
require(JModuleHelper::getLayoutPath('mod_meter_connect', 'default'));

// call new web page, then exit

     if ($electrical_status) {
        //$lines = file("http://127.0.0.1/joomla/index.php/meter-connect");
     }


}//while meter_info 
	
// Fresh_page script----------------------------------------------*/	
$fresh_time = $fresh_time * 1000 ;
echo ("<script type=\"text/javascript\">");
echo ("function fresh_page()");    
echo ("{");
echo ("window.location.reload();");
echo ("}"); 
echo ("setTimeout('fresh_page()',".$fresh_time.");");      
echo ("</script>");
	













/*---------------------------------------------*/
/*
//echo "start get getMeterInfoValus:<br>";
$rs_info = ModDianBiaoHelper::getMeterInfoValus($info_id);
//select get which electricl_data-----------------------------------------------------------------
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
		
*/



/*------This is Test code ---------------------------------------*/
/*---------------------------------------------*/
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






/*---------------------------------------------*/
?>
