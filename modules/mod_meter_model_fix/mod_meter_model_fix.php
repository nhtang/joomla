<?php 
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_fix
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
 
 //header('Content-type:text/html;charset=utf8');

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

$meter_model_id = trim(JRequest::getVar('meter_model_id', "-1")); 

if($meter_model_id == "-1"){
    echo "<script>alert('请先选择要更新的记录！');history.back(); </script>";
}else{
	
	$result = ModMeterModelFixHelper::getMetermodel($meter_model_id);
	
    /*$sq = "select * from joomla3_metermodel where meter_model_id = '$meter_model_id' ";
	$rst = mysql_query($sq);
	//$rsnum = mysql_num_rows($rst);
    $row = mysql_fetch_array($rst);*/
   
    
	  foreach ($result as $row){	
	  $meter_model = $row['meter_model'];
	  $meter_factory = $row['meter_factory'];
	  $command_code = $row['command_code'];
	  $command_code2 = $row['command_code2'];
	  $var_len = $row['var_len'];
	  //$address_code = $row['address_code'];
	  $function_code = $row['function_code'];
	  $storage_start_address = $row['storage_start_address'];
	  $storage_numbers = $row['storage_numbers'];
	  //$check_code = $row['check_code'];
	  $data_index = $row['data_index'];
	  }
	  
	  date_default_timezone_set('Asia/Singapore');
      $datetime = date('Y-m-d H:i:s');
      $datetime_change = $datetime;
 
	 
	   
	   
	require(JModuleHelper::getLayoutPath('mod_meter_model_fix', 'default'));
	
}
?>
