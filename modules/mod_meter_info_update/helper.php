<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info_update
 *
 * @copyright   Copyright (C) 2016 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterInfoUpdateHelper
{
    function updateMeterInfolValues($datetime_change, $info_id, $location_id, $meter_address, $meter_model, $data_select) {
                
				// read meter_model values
		        $db = JFactory::getDBO();
                $query = $db->getQuery(true);
		        $query->select('*');
		        $query->from($db->quoteName('joomla3_meter_info'));
		        $query->where($db->quoteName('info_id')." != ".$info_id." and ".
				              $db->quoteName('location_id')." = ".$location_id." and ".
				              $db->quoteName('meter_address')." = ".$meter_address);

                //$query = 'SELECT * FROM joomla3_meter_info WHERE location_id ='.$location_id.' and location_id ='.$location_id;
				
                $db->setQuery($query);
                $result = $db->loadResult();
                if($result != ""){
					echo " <script>alert('数据库中已存在位置、表地址码相同的记录！ 记录序号：".$result."');history.back(); </script>";
				}else{
				
				
				  // Create and populate an object.
                  $profile = new stdClass();
				  $profile->info_id = $info_id;
                  $profile->location_id = $location_id;
				  $profile->meter_address = $meter_address;
                  $profile->meter_model = $meter_model;
				  $profile->data_select = $data_select;
				  $profile->datetime_change = $datetime_change;
               
                  // Update the object from the user profile table.
                  $result = JFactory::getDbo()->updateObject('joomla3_meter_info', $profile, 'info_id');
				  if($result==true){
					  echo "<script>alert('更新成功！');history.back(); </script>";
				  }else{
					  echo "<script>alert('更新失败，请重新修改！');history.back(); </script>";
				  }
				
				}

    }
}
