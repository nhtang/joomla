<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */


defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
require_once __DIR__ . '/conn.php';

JHTML::stylesheet('style.css','modules/mod_meter_info/css/');



//$result = ModMeterInfoHelper::getMeterStatus($location_id, $meter_address);

//
require(JModuleHelper::getLayoutPath('mod_meter_info', 'default'));
?>


 

