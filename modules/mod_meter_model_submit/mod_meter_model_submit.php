<?php 
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_submit
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
 
 //header('Content-type:text/html;charset=utf8');

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
//require_once __DIR__ . '/conn.php';

JHTML::stylesheet('override.css','modules/mod_meter_model_submit/css/');
JHTML::stylesheet('style.css','modules/mod_meter_model_submit/css/');

@$meter_model = trim($_POST['meter_model']); 

if($meter_model==""){
         mysql_close();
         echo "<script>alert('请先填写电表型号表格，然后按提交按钮提交数据！');history.back(); </script>";
}else{
	   
$meter_factory = trim($_POST['meter_factory']);
$command_code =trim($_POST['command_code']);
$var_len = trim($_POST['var_len']);
$address_code =trim($_POST['address_code']);
$function_code = trim($_POST["function_code"]);
$storage_start_address =trim($_POST['storage_start_address']); 
$storage_numbers =trim($_POST['storage_numbers']);
$check_code = trim($_POST['check_code']);
$data_index = trim($_POST['data_index']);

date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$datetime_create = $datetime;


// insert to database
ModMeterModelSubmitHelper::insertMetermodelValues($datetime_create, $meter_model, $meter_factory, $command_code, $var_len, $address_code, $function_code, $storage_start_address, $storage_numbers, $check_code, $data_index);

  
	   
require(JModuleHelper::getLayoutPath('mod_meter_model_submit', 'default'));
	
}
?>