<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelHelper
{

    function getMeterInfo() {
		  // read meter_model values
		   $db = JFactory::getDBO();

          $query = 'SELECT * FROM joomla3_meter_info order by info_id desc';
          $db->setQuery($query);
          $result = $db->loadObjectList();
          /*foreach($result as $row)
          {
           // echo ' id is '.$row->meter_info.' meter_model is ' . $row->meter_model .'<br/>';
          }*/
	}
	

}
