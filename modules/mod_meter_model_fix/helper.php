<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_dianbiao
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

class ModMeterModelSubmitHelper
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
			

		/*
		public function getMetermodel() {
                // read electrical status
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $query->select('metermodel, command_code, var_len, datetime_create');
                $query->from($db->quoteName('joomla3_metermodel'));
                $query->where($db->quoteName('meter_model_id')." = ".$db->quote(1));

                $db->setQuery($query);
                $row = $db->loadAssoc();

                $metermodel = $row['metermodel'];
                return $metermodel;
        }
		

        public function insertMetermodelValues($datetime_create, $meter_model, $meter_factory, $command_code, $var_len, $address_code, $function_code, $storage_start_address, $storage_numbers, $check_code，$data_index) {
                // Create and populate an object.
                $profile = new stdClass();
                $profile->datetime_create = $datetime_create;
                $profile->meter_model = $meter_model;
				$profile->meter_factory = $meter_factory;
                $profile->command_code = $command_code;
                $profile->var_len = $var_len;
                $profile->address_code = $address_code;
				$profile->function_code = $function_code;
				$profile->storage_start_address = $storage_start_address;
				$profile->storage_numbers = $storage_numbers;
				$profile->check_code = $check_code;
				$profile->data_index = $data_index;

                // Insert the object into the user profile table.
                $result = JFactory::getDbo()->insertObject('joomla3_metermodel', $profile);
				if($result==true){
					echo "<script>alert('录入成功！');history.back(); </script>";
				}else{
					echo "<script>alert('录入失败，请重新录入！');history.back(); </script>";
				}

        }
		*/
}
