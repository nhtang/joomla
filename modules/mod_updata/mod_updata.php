﻿<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_updata
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
jimport('joomla.log.log');
 JLog::addLogger(array());
 
defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
 
//JHTML::stylesheet('styles.css','modules/mod_updata/css/');
	
 JLog::add(JText::_('Inside mod_updata start:JRequest::getVar '), JLog::ALL, 'jerror');

require(JModuleHelper::getLayoutPath('mod_updata', 'default'));
