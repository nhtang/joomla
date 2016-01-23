<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_updata
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';
 
JHTML::stylesheet('styles.css','modules/mod_updata/css/');

//function getChartDataAjax(){


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

if ($allarr != ""){
	// Create and populate an object.
			$var_name = "allarr";

			date_default_timezone_set('Asia/Singapore');
            $change_time = date('Y-m-d H:i:s');	
			
			// Put var  fresh_time into table 
            $profile_fresh = new stdClass();
			$profile_fresh->var_name = $var_name;
			$profile_fresh->var_value = $Arr_fields["meter_address-0"];
			$profile_fresh->change_time = $change_time;
               
            // Update the object from the user profile table.
            $fresh_update = JFactory::getDbo()->updateObject('joomla3_varitely', $profile_fresh, 'var_name');
			//return $time;
}



if ($num_records>0) {
	for ($n=0; $n<$num_records; $n++){

		//check record is new

		$controller_electrical_id = $Arr_fields["controller_electrical_id-$n"];
		$location_id = $Arr_fields["location_id-$n"];
		$meter_address = $Arr_fields["meter_address-$n"];

echo "controller_electrical_id is $controller_electrical_id <br>";
echo "location_id is $location_id <br>";
	
		if ( ($controller_electrical_id >0) && ($location_id > 0) ) {
			$new = ModElectromFormHelper::isDataNew($controller_electrical_id, $location_id, $meter_address);
echo "new is $new <br>";
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
}

return 1;
//}//function
require(JModuleHelper::getLayoutPath('mod_updata', 'default'));
