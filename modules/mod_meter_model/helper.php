<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelHelper
{

    function getMeterModel() {
		  // read meter_model values
		  $db = JFactory::getDBO();

          $query = 'SELECT * FROM #__metermodel order by meter_model_id desc';
          $db->setQuery($query);
          $result = $db->loadObjectList();
          /*foreach($result as $row)
          {
           // echo ' id is '.$row->meter_model_id.' meter_model is ' . $row->meter_model .'<br/>';
          }*/
	}
	

}
