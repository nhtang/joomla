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

JHTML::stylesheet('style.css','modules/mod_meter_model_submit/css/');

$meter_model = trim(JRequest::getVar('meter_model', '-1')); 

if($meter_model=="-1"){
         mysql_close();
         echo "<script>alert('请先填写电表型号表格，然后按提交按钮提交数据！');history.back(); </script>";
}else{
	   
$meter_factory = trim(JRequest::getVar('meter_factory', '-1'));
$command_code =trim(JRequest::getVar('command_code', '-1'));
$var_len = trim(JRequest::getVar('var_len', '-1'));
$address_code =trim(JRequest::getVar('address_code', '-1'));
$function_code = trim(JRequest::getVar('function_code', '-1'));
$storage_start_address =trim(JRequest::getVar('storage_start_address', '-1')); 
$storage_numbers =trim(JRequest::getVar('storage_numbers', '-1'));
$check_code = trim(JRequest::getVar('check_code', '-1'));
$data_index = trim(JRequest::getVar('data_index', '-1'));

date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$datetime_create = $datetime;


// insert to database
ModMeterModelSubmitHelper::insertMetermodelValues($datetime_create, $meter_model, $meter_factory, $command_code, $var_len, $address_code, $function_code, $storage_start_address, $storage_numbers, $check_code, $data_index);

  
	   
require(JModuleHelper::getLayoutPath('mod_meter_model_submit', 'default'));
	
}
?>