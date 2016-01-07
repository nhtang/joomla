<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_dianbiao
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModDianbiaoHelper
{

	function hexStringTo32Float($strHex) {
	    $v = hexdec($strHex);
	    $x = ($v & ((1 << 23) - 1)) + (1 << 23) * ($v >> 31 | 1);
	    $exp = ($v >> 23 & 0xFF) - 127;
	    return $x * pow(2, $exp - 23);
	}

	public function String2Hex($string){
	    $hex='';
	    for ($i=0; $i < strlen($string); $i++){
	        $hex .= dechex(ord($string[$i]));
	    }
	    return $hex;
	}
 
 
	public function Hex2String($hex){
	    $string='';
	    for ($i=0; $i < strlen($hex)-1; $i+=2){
	        $string .= chr(hexdec($hex[$i].$hex[$i+1]));
	    }
	    return $string;
	}
	
	

	public function getElectricalStatus() {
		// read electrical status
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('electrical_status');
		$query->from($db->quoteName('joomal3_electrical_status'));
		$query->where($db->quoteName('location_id')." = ".$db->quote(1));

		$db->setQuery($query);
		$row = $db->loadAssoc();

		$electrical_status = $row['electrical_status'];
		return $electrical_status;
	}
	
	public function getMeterModelValus($meter_model) {
		  // read MeterModelValus
		  $db = JFactory::getDBO();
          $query = 'SELECT * FROM #__metermodel where meter_model = '.$meter_model ;
          $db->setQuery($query);
          $result = $db->loadObjectList();  
          /*foreach($result as $row)
          {
            // echo ' id is '.$row->meter_model_id.' meter_model is ' . $row->meter_model .'<br/>';
          }*/
	}
	
	
	public function insertElectricalValues($datetime, $location_id, $meter_address, $u, $i, $s, $f) {
		// Create and populate an object.
		$profile = new stdClass();
		$profile->location_id = $location_id;
		$profile->meter_address = $meter_address;
		$profile->datetime = $datetime;
		$profile->phase1_voltage = $u;
		$profile->phase1_current = $i;
		$profile->phase1_apparent_power = $s;
		$profile->phase1_frequency = $f;

		// Insert the object into the user profile table.
		$result = JFactory::getDbo()->insertObject('joomal3_electrical', $profile);

	}
}
