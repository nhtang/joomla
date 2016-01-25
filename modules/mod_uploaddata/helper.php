<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_uploaddata
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

class modUploaddataHelper
{
	public static function isDataNew($controller_electrical_id, $location_id, $meter_address) {
		// check if data is new based on controller_electrical_id and location_id
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select($db->quoteName(array('electrical_id', 'controller_electrical_id', 'meter_address')));
		$query->from($db->quoteName('joomla3_electrical2'));
		$query->where("controller_electrical_id = " . $db->quote($controller_electrical_id) . " AND "
		      . "location_id =  " . $db->quote($location_id). " AND "
		      . "meter_address =  " . $db->quote($meter_address) );
			  
	    //$query = "SELECT * FROM joomla3_electrical2 WHERE controller_electrical_id = $controller_electrical_id  AND location_id =  $location_id ";		  

		$db->setQuery($query);
		$db->execute();
		$num_rows = $db->getNumRows();
	    //$row = $db->loadAssoc();

		if ($num_rows == 0) {
			return 1; // new
		} else {
			return 0; // not new
		}
	}
	
  public static function getUploadDataAjax(){
	
	$allarr = JRequest::getVar('allarr', '-1');
    $num_records = JRequest::getVar('num_records', '-1');
    $fields = JRequest::getVar('fields', '-1');

	$arr_len = strlen($allarr);
    $allarr = substr($allarr , 1, $arr_len-2);
    $data_index = str_replace('"' , '', $allarr);
   
	
  //explode $data_index    //example :$data_index = "num_records:2,controller_electrical_id-0:291,location_id-0:1,meter_address-0:04,datetime-0:2016-01-19 16:49:23,phase1_voltage-0:222.5077,phase1_current-0:40.6247,phase1_apparent_power-0:7.000000,phase1_frequency-0:50.0000";  
	$strArr=explode(',',$data_index); 
	$arr_num = sizeof($strArr); //cout array numbers or // $arr_num = count($strArr);
	for($i = 0; $i<$arr_num ; $i++){
        //echo $i.':'.$strArr[$i].'<br/>';
    }
    
    unset($Arr_fields);
	$Arr_fields_nums = $fields * $num_records ;
    for ($j = 0; $j < $Arr_fields_nums; $j++){
	  $var = $strArr[$j]; 
	  $arr = explode(":",$var);
	  $var_name = $arr[0];
	  $var_vaule = $arr[1];   // explode Array value
	  $Arr_fields[$var_name] = $var_vaule ;
    }

    /*if ($allarr != ""){  //Update TABLE varitely for test receive data
	// Create and populate an object.
			$var_name = "allarr";

			date_default_timezone_set('Asia/Singapore');
            $change_time = date('Y-m-d H:i:s');	
			
			// Put var  fresh_time into table 
            $profile_fresh = new stdClass();
			$profile_fresh->var_name = $var_name;
			$profile_fresh->var_value = $allarr;
			$profile_fresh->change_time = $change_time;
               
            // Update the object from the user profile table.
            $fresh_update = JFactory::getDbo()->updateObject('joomla3_varitely', $profile_fresh, 'var_name');
			//return $time;
    }*/

    

    if ($num_records>0) {
	for ($n=0; $n<$num_records; $n++){

		//check record is new

		$controller_electrical_id = $Arr_fields["controller_electrical_id-$n"];
		$location_id = $Arr_fields["location_id-$n"];
		$meter_address = $Arr_fields["meter_address-$n"];

//echo "controller_electrical_id is $controller_electrical_id <br>";
//echo "location_id is $location_id <br>";

    //jimport('joomla.log.log');
	//JLog::addLogger(array());
	
	
		if ( ($controller_electrical_id >0) && ($location_id > 0) ) {
			$new = ModUploaddataHelper::isDataNew($controller_electrical_id, $location_id, $meter_address);
//echo "new is $new <br>";
			if ( $new ) {
             
				// Create and populate an object.
				$electrical = new stdClass();
				$electrical->controller_electrical_id = $controller_electrical_id;
				$electrical->location_id = $location_id;
				$electrical->meter_address = $meter_address;
				$electrical->phase1_frequency = $Arr_fields["phase1_frequency-$n"];
				$electrical->datetime = $Arr_fields["datetime-$n"];
				$electrical->phase1_apparent_power = $Arr_fields["phase1_apparent_power-$n"];
				$electrical->phase1_voltage = $Arr_fields["phase1_voltage-$n"];
				$electrical->phase1_current = $Arr_fields["phase1_current-$n"];
		
				// Insert the object into the user profile table.
				$result = JFactory::getDbo()->insertObject('joomla3_electrical2', $electrical);
			} //if
		} // if
	}// for
    }//if ($num_records>0) 

	    //JLog::add(JText::_("RETURN AJAX"), JLog::ERROR, 'jerror');////
		return 1;
 } // getUploadData

	

	
	
}
?>
