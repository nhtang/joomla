<?php 
/**
 * @package     electromonitor.com
 * @subpackage  mod_param_update
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
 
 //header('Content-type:text/html;charset=utf8');

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

$fresh_time = trim(JRequest::getVar('fresh_time', '-1')); 
echo "fresh_time : $fresh_time";
if($fresh_time == "-1"){
    mysql_close();
    echo "<script>alert('请先修改参数！');history.back(); </script>";
}else{
	  $wait_time = trim(JRequest::getVar('wait_time', '-1'));
	  
		
	  
	if($fresh_time != "-1"){ModParamUpdateHelper::updateFreshTime($fresh_time);} 
	if($wait_time != "-1"){ModParamUpdateHelper::updateWaitTime($wait_time);}

  
	require(JModuleHelper::getLayoutPath('mod_param_update', 'default'));
	
}
?>