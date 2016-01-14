<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterInfoHelper
{

    function getMeterInfoValues() {
		  // read meter_model values
		   $db = JFactory::getDBO();

          $query = 'SELECT * FROM joomla3_meter_info order by info_id desc';
          $db->setQuery($query);
          $result = $db->loadAssocList();
          return $result;
	}
	
	public function getMeterStatus($location_id, $meter_address) {
		  // read meter_model values
		  $db = JFactory::getDBO();

          $query = "SELECT * FROM joomla3_electrical_status where location_id = '$location_id' and meter_address = '$meter_address' ";
          $db->setQuery($query);
          //$result = $db->loadAssocList();
          //return $result;
		  $row = $db->loadAssoc();

		  $electrical_status = $row['electrical_status'];
		  return $electrical_status;
	}
	

}
