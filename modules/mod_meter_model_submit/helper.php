<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_submit
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelSubmitHelper
{

        function insertMetermodelValues($datetime_create, $meter_model, $meter_factory, $command_code, $command_code2, $var_len, $function_code, $storage_start_address, $storage_numbers, $data_index) {
               
                // read meter_model values
		        $db = JFactory::getDBO();
                //$query = $db->getQuery(true);
		        //$query->select('*');
		        //$query->from($db->quoteName('joomla3_metermodel'));
		        //$query->where($db->quoteName('meter_model')." = ".$meter_model);

                $query = "SELECT * FROM joomla3_metermodel WHERE meter_model = '$meter_model' ";
                $db->setQuery($query);
                $result = $db->loadResult();
                if($result != ""){
					echo " <script>alert('数据库中已存在相同的电表型号！ 记录序号：".$result."');history.back(); </script>";
				}else{

			   // Create and populate an object.
                $profile = new stdClass();
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
                $profile->datetime_create = $datetime_create;
				
                // Insert the object into the user profile table.
                $result2 = JFactory::getDbo()->insertObject('joomla3_metermodel', $profile);
				  if($result2 == true){
					  echo "<script>alert('录入成功！');history.back(); </script>";
				  }else{
					  echo "<script>alert('录入失败，请重新录入！');history.back(); </script>";
				  }
				
				}

        }
}
