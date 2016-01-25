<?php defined('_JEXEC') or die;

/**
 * File       helper.php
 */

class modChartHelper {

	public static function getChartDataAjax(){
		// $table is the table to read from. can have electrical, water, etc
		// $location_id is the location id. unique for each building
		// $meters is an array of the meters to read pertaining to the location
		// $from_datetime is the datetime to start the search from
		// $to_datetime is the datetime to end the search
		// $num_records is the number of records to retrieve
		// $data interval is the interval between records
			// n-t, n is a number, t is the term, like y,m,d,w,h,i,s. Eg. 1-s
	
		$table = JRequest::getVar('table', 'electrical');
		$location_id = JRequest::getVar('location_id', '1');
		$meters = JRequest::getVar('meters', NULL );
		$columns_string = JRequest::getVar('columns', NULL );
		$from_datetime_string = JRequest::getVar('from_datetime', NULL);
		$to_datetime_string = JRequest::getVar('to_datetime', NULL);
		$num_records = JRequest::getVar('num_records', '30');
		$data_interval = JRequest::getVar('data_interval', '1-s');

/*
echo "meters is $meters ---";
echo "columns_string is $columns_string ---";
echo "num_records is $num_records ---";
echo "from_datetime_string is $from_datetime_string ---";
echo "to_datetime_string is $to_datetime_string ---";
echo "data_interval is $data_interval ---";
*/
		date_default_timezone_set('Asia/Singapore');
		if ($to_datetime_string == null) {
			$to_datetime = date('Y-m-d H:i:s');
		} else {
			$to_datetime = date($to_datetime_string);
		}

		$data_interval_array = explode('-',$data_interval);
		if ($data_interval_array[1] == 's') { // seconds
			$t = 1 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'i') { // minutes
			$t = 60 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'h') { //hour
			$t = 60 * 60 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'd') { //day
			$t = 24 * 60 * 60 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'w') { //week
			$t = 7 * 24 * 60 * 60 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'm') { // month
			$t = 30 * 24 * 60 * 60 * $data_interval_array[0]; // convert to seconds
		} elseif ($data_interval_array[1] == 'y') { // year
			$t = 365 * 24 * 60 * 60 * $data_interval_array[0]; // convert to seconds
		} 

		if ($from_datetime_string == null) {
			$time = strtotime($to_datetime);
			$time = $time - (1 * 60); // 1 minute in seconds
			$from_datetime = date("Y-m-d H:i:s", $time);
		} else {
			$from_datetime = date($from_datetime_string);
		}

		if ($columns_string == null) {
			if ($t == 1) {
				$columns = array('electrical_id', 'location_id', 'datetime', 'phase1_apparent_power', 'phase1_voltage', 'phase1_current', 'phase1_frequency');
				$select_string = '`electrical_id`, `location_id`, `datetime`, `phase1_apparent_power`, `phase1_voltage`, `phase1_current`, `phase1_frequency`';
			} else {
				$columns = array('electrical_id', 'location_id', 'MAX(`datetime`)', 'AVG(phase1_apparent_power)', 'AVG(phase1_voltage)', 'AVG(phase1_current)', 'AVG(phase1_frequency)');
				$select_string = '`electrical_id`, `location_id`, MAX(`datetime`) AS `datetime`, AVG(`phase1_apparent_power`) AS phase1_apparent_power, AVG(`phase1_voltage`) AS phase1_voltage, AVG(`phase1_current`) AS phase1_voltage, AVG(`phase1_frequency`) AS phase1_frequency';
			} // if t = 1
		} else {
			$columns = explode(',', $columns_string);
			$select_string = '';
			$first_time = 1;
			if ($t > 1) {
				for($j=0; $j<sizeOf($columns);$j++) {
					if ($first_time) {
						$first_time = 0; 
						$comma = '';
					} else {
						$comma = ',';
					}
					if ($columns[$j] == 'datetime') {
						$select_string .= "$comma MAX(`datetime`) AS `datetime` ";
					} else {
						$select_string .= "$comma AVG(`$columns[$j]`) AS `$columns[$j]`";
					}		
				} // for
			} else {
				for($j=0; $j<sizeOf($columns);$j++) {
					if ($first_time) {
						$first_time = 0;
						$comma = '';
					} else {
						$comma = ',';
					}		
					$select_string .= "$comma `$columns[$j]`";
				} // for
			} //$t > 1
		} // if $columns_string
		

	
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
//		$query->select( $db->quoteName($columns) );
		$query->select( $select_string );

		$query->from( $db->quoteName("#__$table") );		
		$query->where( $db->quoteName('location_id')." = ".$db->quote($location_id) . 
				" AND `datetime` >= " . $db->quote($from_datetime) . " AND `datetime` <= " . $db->quote($to_datetime)  );
		if ($t > 1) { // more than 1 s data interval, need to group and average
			$query->group( "(TIME_TO_SEC(datetime) - (TIME_TO_SEC (datetime) % ($t) ) )" );		
		}
		$query->order('datetime ASC');
	
//  echo("query is " . $query->__toString() . '---');

		$db->setQuery($query,0,$num_records);
		$rows = $db->loadAssocList();		

	
		return json_encode($rows);
	} // getChartData

	public static function getDataAjax(){
		// $table is the table to read from. can have electrical, water, etc
		// $location_id is the location id. unique for each building
		// $zone_id=1 is the zone id. unique in a building
		// $from_datetime is the datetime to start the search from
		// $to_datetime is the datetime to end the search
		// $num_records is the number of records to retrieve
		// $interval is the time in seconds the records should be averaged
	    
		//jimport('joomla.log.log');
		//JLog::addLogger(array());
		
		//JLog::add(JText::_('Inside getChartDataAjax'), JLog::WARNING, 'jerror');
        
		
      
		 
		$table = JRequest::getVar('table', 'electrical');
		$location_id = JRequest::getVar('location_id', '1');
		$meter_address = JRequest::getVar('meter_address', '01');
		$from_datetime = JRequest::getVar('from_datetime', NULL);
		$to_datetime = JRequest::getVar('to_datetime', NULL);
		$num_records = JRequest::getVar('num_records', '30');
		$interval = JRequest::getVar('interval', '1');
		
		
		if ($table == 'electrical') {
			// read electrical status
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);
			$query->select( $db->quoteName(array('electrical_id', 'location_id', 'datetime', 'phase1_apparent_power', 'phase1_voltage', 'phase1_current', 'phase1_frequency') ) );
			$query->from( $db->quoteName('#__electrical') );
			$query->where( $db->quoteName('location_id')." = ".$db->quote(1) );
			$query->order('datetime DESC');
	
			$db->setQuery($query,0,$num_records);
			$rows = $db->loadAssocList();		
	
			return json_encode($rows);
		} // if
	} // getData




	public static function getTestDataAjax() {

	    //Get the app
	    //$app = JFactory::getApplication();
    
	    //Insert stuff to do here
		$data = "123";
		    
	    //echo the data
	    echo json_encode($data);

	    //close the $app
	    //$app->close();
	} // getTestData

}