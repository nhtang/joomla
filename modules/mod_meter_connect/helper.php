<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_connect
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModDianbiaoHelper
{
    function crc16($string) {
      $crc = 0xFFFF;
      for ($x = 0; $x < strlen ($string); $x++) {
          $crc = $crc ^ ord($string[$x]);
        for ($y = 0; $y < 8; $y++) {
            if (($crc & 0x0001) == 0x0001) {
              $crc = (($crc >> 1) ^ 0xA001);
            } else { $crc = $crc >> 1; }
        }
      }
      return $crc;
    }
    //$s = pack('H*', '010300090002');
    //$t = crc16($s);
    //printf('=%02x%02x', $t%256, floor($t/256));  //14 09
	//$cs=sprintf('%02x%02x', $t%256, floor($t/256));
    //echo $cs;
	
	public function trimall($str)//删除空格
    {
        $qian=array(" ","　","\t","\n","\r");
		$hou=array("","","","","");
        return str_replace($qian,$hou,$str);    
    }
	
	public function convert_code($str){
      $str1 = substr($str,0,2);
      $str2 = substr($str,2,2);
	  $allstr = $str1." ".$str2;
	  return $allstr;
    }
	
	public function hexStringTo32Float($strHex) {
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
	
	public function getChecklStatus() {
		// read electrical status
		$db = JFactory::getDbo();
		$query = "SELECT * FROM joomla3_electrical_status WHERE  electrical_status = 1 ";
		$db->setQuery($query);
		$row = $db->loadResult();
		return $row;
	}

	public function getElectricalStatus($location_id, $meter_address) {
		// read electrical status
		$db = JFactory::getDbo();
		$query = "SELECT * FROM joomla3_electrical_status WHERE location_id = '$location_id' and meter_address = '$meter_address' ";
		$db->setQuery($query);
		$row = $db->loadAssoc();

		$electrical_status = $row['electrical_status'];
		return $electrical_status;
	}
	
	public function getMeterModelValus($meter_model) {
		  // read MeterModelValus
		  $db = JFactory::getDBO();
          $query = "SELECT * FROM joomla3_metermodel where meter_model = '$meter_model'";
          $db->setQuery($query);
          $result = $db->loadAssocList(); 
		  
		  return $result;
          /*foreach($result as $row)
          {
            // echo ' id is '.$row->meter_model_id.' meter_model is ' . $row->meter_model .'<br/>';
          }*/
	}
	
	public function getMeterInfoValus($info_id) {
		  // read MeterModelValus
		  $db = JFactory::getDBO();
          $query = "SELECT * FROM joomla3_meter_info where info_id = '$info_id' ";
          $db->setQuery($query);
          $rs_info = $db->loadAssocList(); 
		  
		  return $rs_info;
          
	}
	
	
	public function insertElectricalValues($datetime, $location_id, $meter_address, $u1, $i1, $s1, $f1, $u2, $i2, $s2, $f2, $u3, $i3, $s3, $f3) {
		// Create and populate an object.
		$profile = new stdClass();
		$profile->location_id = $location_id;
		$profile->meter_address = $meter_address;
		$profile->datetime = $datetime;
		
		$profile->phase1_voltage = $u1;
		$profile->phase1_current = $i1;
		$profile->phase1_apparent_power = $s1;
		$profile->phase1_frequency = $f1;
		
		$profile->phase2_voltage = $u2;
		$profile->phase2_current = $i2;
		$profile->phase2_apparent_power = $s2;
		$profile->phase2_frequency = $f2;
		
		$profile->phase3_voltage = $u3;
		$profile->phase3_current = $i3;
		$profile->phase3_apparent_power = $s3;
		$profile->phase3_frequency = $f3;

		// Insert the object into the user profile table.
		$result = JFactory::getDbo()->insertObject('joomla3_electrical', $profile);

	}
}
