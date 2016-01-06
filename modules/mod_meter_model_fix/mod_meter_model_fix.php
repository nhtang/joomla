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
//require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

@$meter_model_id = trim($_GET['meter_model_id']); 

if($meter_model_id == ""){
    mysql_close();
    echo "<script>alert('请先选择要更新的记录！');history.back(); </script>";
}else{
    $sq = "select * from joomla3_metermodel where meter_model_id = '$meter_model_id' ";
	$rst = mysql_query($sq);
	//$rsnum = mysql_num_rows($rst);
    $row = mysql_fetch_array($rst);
   
    if($row == ""){
      mysql_close();
      echo " <script>alert('不存在的记录项！');history.back(); </script>";
    }else{
	  $meter_model = $row['meter_model'];
	  $meter_factory = $row['meter_factory'];
	  $command_code = $row['command_code'];
	  $var_len = $row['var_len'];
	  $address_code = $row['address_code'];
	  $function_code = $row['function_code'];
	  $storage_start_address = $row['storage_start_address'];
	  $storage_numbers = $row['storage_numbers'];
	  $check_code = $row['check_code'];
		
	  date_default_timezone_set('Asia/Singapore');
      $datetime = date('Y-m-d H:i:s');
      $datetime_change = $datetime;
 
	}  
	   
	   
	require(JModuleHelper::getLayoutPath('mod_meter_model_fix', 'default'));
	
}
?>