<?php 
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model4
 *
 * @copyright   Copyright (C) 2016 All rights reserved.
 */

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

JHTML::stylesheet('styles.css', 'modules/mod_meter_model4/css/');
$result = ModMeterModelHelper::getMeterModelValues();

	/*foreach($result as $row){
			$meter_model = $row['meter_model'];
			echo $meter_model."<br>";
    } */
	

require(JModuleHelper::getLayoutPath('mod_meter_model4', 'default'));
	

?>
