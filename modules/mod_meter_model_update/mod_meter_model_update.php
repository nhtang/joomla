<?php 
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_update
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
 
 //header('Content-type:text/html;charset=utf8');

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

$meter_model_id = trim(JRequest::getVar('meter_model_id', '-1')); 

if($meter_model_id == "-1"){
    mysql_close();
    echo "<script>alert('请先修改记录！');history.back(); </script>";
}else{
	  $meter_model = trim(JRequest::getVar('meter_model', '-1'));
	  //$meter_model = trim(JRequest::getVar('meter_model' '-1'));
	  $meter_factory = trim(JRequest::getVar('meter_factory', '-1'));
	  $command_code = trim(JRequest::getVar('command_code', '-1'));
	  $var_len = trim(JRequest::getVar('var_len', '-1'));
	  $address_code = trim(JRequest::getVar('address_code', '-1'));
	  $function_code = trim(JRequest::getVar('function_code', '-1'));
	  $storage_start_address = trim(JRequest::getVar('storage_start_address', '-1'));
	  $storage_numbers = trim(JRequest::getVar('storage_numbers', '-1'));
	  $check_code = trim(JRequest::getVar('check_code', '-1'));
	  $data_index = trim(JRequest::getVar('data_index', '-1'));
		
	  date_default_timezone_set('Asia/Singapore');
      $datetime = date('Y-m-d H:i:s');
      $datetime_change = $datetime;
	  
  ModMeterModelUpdateHelper::updateMetermodelValues($datetime_change, $meter_model_id, $meter_model, $meter_factory, $command_code, $var_len, $address_code, $function_code, $storage_start_address, $storage_numbers, $check_code, $data_index);
	  
	  
	/*
    $sq = "select * from joomla3_metermodel where meter_model_id = '$meter_model_id' ";
	$rst = mysql_query($sq);
	//$rsnum = mysql_num_rows($rst);
    $row = mysql_fetch_array($rst);
   
    if($row == ""){
      mysql_close();
      echo " <script>alert('不存在的记录项！');history.back(); </script>";
    }else{
	  $meter_model = $_POST['meter_model'];
	  $meter_factory = $_POST['meter_factory'];
	  $command_code = $_POST['command_code'];
	  $var_len = $_POST['var_len'];
	  $address_code = $_POST['address_code'];
	  $function_code = $_POST['function_code'];
	  $storage_start_address = $_POST['storage_start_address'];
	  $storage_numbers = $_POST['storage_numbers'];
	  $check_code = $_POST['check_code'];
		
	  date_default_timezone_set('Asia/Singapore');
      $datetime = date('Y-m-d H:i:s');
      $datetime_change = $datetime;
	  
      $sql = "UPDATE joomla3_metermodel SET 
	            meter_model='$meter_model',
	            meter_factory='$meter_factory',
				command_code='$command_code',
				var_len='$var_len',
				address_code='$address_code',
				function_code='$function_code',
				storage_start_address='$storage_start_address',
				storage_numbers='$storage_numbers',
				check_code='$check_code'	
	          WHERE 
			    meter_model_id = '$meter_model_id'";
	   
	   $result = mysql_query($sql);
	   
	   if($result==false){
         mysql_close();
         echo "<script>alert('写入数据表时出错！');history.back(); </script>";
       }else{
        
	       mysql_close();
           echo "<script>alert('更新成功！');history.back(); </script>";

       } 
	   
	} */ 
	
  
	require(JModuleHelper::getLayoutPath('mod_meter_model_update', 'default'));
	
}
?>