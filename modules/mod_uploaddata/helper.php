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
   
	
    //explode $data_index     
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

    

    if ($num_records>0) {
	for ($n=0; $n<$num_records; $n++){

		//check record is new

		$controller_electrical_id = $Arr_fields["controller_electrical_id-$n"];
		$location_id = $Arr_fields["location_id-$n"];
		$meter_address = $Arr_fields["meter_address-$n"];


	
	
		if ( ($controller_electrical_id >0) && ($location_id > 0) ) {
			$new = ModUploaddataHelper::isDataNew($controller_electrical_id, $location_id, $meter_address);

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


//JLog::add(JText::_("Before return 1"), JLog::ERROR, 'jerror');

        $db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select( $db->quoteName(array('electrical_id', 'location_id', 'datetime', 'phase1_apparent_power', 'phase1_voltage', 'phase1_current', 'phase1_frequency') ) );
			$query->from( $db->quoteName('joomla3_electrical2') );
			$query->where( $db->quoteName('controller_electrical_id')." = ".$db->quote(1) );
			$query->order('datetime DESC');
	
			$db->setQuery($query);
			$rows = $db->loadAssocList();		
	
		//return json_encode($rows);
		
		$str = "{success:true, message:'ִ�гɹ���', messages:'messages', data:json_encode($rows)}";
		return $str
		
		
 } // getUploadData

	

	
	
}
?>
