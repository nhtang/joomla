<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_update
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelUpdateHelper
{
    function updateMetermodelValues($datetime_change, $meter_model_id, $meter_model, $meter_factory, $command_code, $command_code2, $var_len, $function_code, $storage_start_address, $storage_numbers, $data_index) {
                // Create and populate an object.
                $profile = new stdClass();
				$profile->meter_model_id = $meter_model_id;
                $profile->meter_model = $meter_model;
				$profile->meter_factory = $meter_factory;
                $profile->command_code = $command_code;
				$profile->command_code2 = $command_code2;
                $profile->var_len = $var_len;
                //$profile->address_code = $address_code;
				$profile->function_code = $function_code;
				$profile->storage_start_address = $storage_start_address;
				$profile->storage_numbers = $storage_numbers;
				//$profile->check_code = $check_code;
				$profile->data_index = $data_index;
				$profile->datetime_change = $datetime_change;

                // Update the object from the user profile table.
                $result = JFactory::getDbo()->updateObject('joomla3_metermodel', $profile, 'meter_model_id');
				if($result==true){
					echo "<script>alert('更新成功！');history.back(); </script>";
				}else{
					echo "<script>alert('更新失败，请重新修改！');history.back(); </script>";
				}

    }
}
