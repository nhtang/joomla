<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model2
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */


defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

JHTML::stylesheet('style.css','modules/mod_meter_model/css/');

ModMeterModelHelper2::getMeterModel2();

require(JModuleHelper::getLayoutPath('mod_meter_model2', 'default'));
?>


 

