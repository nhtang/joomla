﻿<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model2
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelHelper2
{

    function getMeterModel2() {
		  // read meter_model values
		  $db = JFactory::getDBO();

          $query = 'SELECT * FROM joomla3_metermodel order by meter_model_id desc';
          $db->setQuery($query);
          $result = $db->loadAssocList();
          /*foreach($result as $row)
          {
           // echo ' id is '.$row->meter_model_id.' meter_model is ' . $row->meter_model .'<br/>';
          }*/
	}
	

}