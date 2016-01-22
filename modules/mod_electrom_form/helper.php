<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_electrical
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModElectromFormHelper
{
	public static function isDataNew($controller_electrical_id, $location_id, $meter_address) {
		// check if data is new based on controller_electrical_id and location_id
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('electrical_id');
		$query->from($db->quoteName('#__electrical2'));
		$query->where("controller_electrical_id = " . $db->quote($controller_electrical_id) . " AND "
		      . "location_id =  " . $db->quote($location_id). " AND "
		      . "$meter_address =  " . $db->quote($$meter_address) );

		$db->setQuery($query);
		$db->execute();
		$num_rows = $db->getNumRows();
		// $row = $db->loadAssoc();

		if ($num_rows == 0) {
			return 1; // new
		} else {
			return 0; // not new
		}
	}
}
