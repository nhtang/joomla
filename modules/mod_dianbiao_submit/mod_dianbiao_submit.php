<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_dianbiao
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

JHTML::stylesheet('styles.css','modules/mod_dianbiao_submit/css/');

//$electrical_status = JRequest::getVar('electrical_status', '-1');
//$quantity = JRequest::getVar('quantity', '0');

//if ($electrical_status != -1) {
  // update database
//  ModDianBiaoSwitchHelper::setElectricalStatus($electrical_status);

//}

// ask server for datetime (use current time - 10s for trial)
// read database
// list data
// submit data


//for ($k=0;$k<5;$k++){

date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;
$server_datetime = date("Y-m-d H:i:s", strtotime("-60 seconds"));

$limit = 22; // number of records to retrieve


$data_pos = ModDianBiaoSubmitHelper::getDataPos();
if($data_pos == ""){$data_pos = 0;}

$time_pos = ModDianBiaoSubmitHelper::getTimePos();
$time_pos = date("Y-m-d H:i:s", strtotime($time_pos));

//$controller_electrical_id = $data_pos;
//$datatime = $time_pos;



unset($electrical_data);
$electrical_data = ModDianBiaoSubmitHelper::getElectricalData($server_datetime, $data_pos, $time_pos, $limit);
$size = count($electrical_data);
// echo "size of data is $size";


// send data to electrom server
//$url = 'http://www.electromonitor.com/monitor/index.php/submit-values';
 $url = 'http://localhost/joomla/index.php/submit-values';

// set up _POST array of key value pairs
$data_rows = $electrical_data;

$n = 0;
unset ($_POST);
//$_POST["num_records"] = $limit;
$num_records = $limit;
$fields = 8;
foreach ($data_rows AS $data) {

  $_POST["controller_electrical_id-$n"]  = $data['electrical_id'];
  $_POST["location_id-$n"]  = $data['location_id'];
  $_POST["meter_address-$n"]  = $data['meter_address'];
  $_POST["datetime-$n"]  = $data['datetime'];
  $_POST["phase1_voltage-$n"]  = $data['phase1_voltage'];
  $_POST["phase1_current-$n"]  = $data['phase1_current'];
  $_POST["phase1_apparent_power-$n"]  = $data['phase1_apparent_power'];
  $_POST["phase1_frequency-$n"]  = $data['phase1_frequency'];
  
  $n++;
   //echo "n= $n ...";

} //foreach
  
  /*
  $json_post = json_encode($_POST);
  $arr_len = strlen($json_post);
  $allarr = substr($json_post , 1, $arr_len-2);
  $data_index = str_replace('"' , '', $allarr);
  echo  $data_index ;
  */
//echo "fields_string: $fields_string" ;

    $data_pos = $data['electrical_id'];
    $time_pos = $data['datetime'];
    //ModDianBiaoSubmitHelper::setDataPos($data_pos);
    //ModDianBiaoSubmitHelper::setTimePos($time_pos);


?>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<script type="text/javascript">

   $(function(){
	   var post = '<?php echo json_encode($_POST);?>';
	   var data_pos = '<?php echo $data_pos;?>';
	   var time_pos = '<?php echo $time_pos;?>';
	   
     $.ajax({
         url: 'http://127.0.0.1/joomla/index.php/submit-values/show',
		 datatype: "json",
		 //type: "POST",
		 traditional:true,
         data:{ 
		   allarr : post,
		   num_records : "<?php echo $limit;?>",
		   fields : "<?php echo $fields;?>"
		 },
          		 
		 beforeSend:function(){},
         error: function(){  
             alert('Error loading XML document');  
         },  
         success: function(){  
             alert(" Updata to server succeed! \n Last record controller_electrical_id is : "+data_pos+"\n Last record datetime is : "+time_pos);
			 //document.write(" Updata to server succeed! \n Last record controller_electrical_id is : "+data_pos+"\n Last record datetime is : "+time_pos):
			 
         }
     });
     
 });
 
		

</script>

<?php 
      
    ModDianBiaoSubmitHelper::setDataPos($data_pos);
    ModDianBiaoSubmitHelper::setTimePos($time_pos);

//} // for k

//$lines = file("http://localhost/joomla/index.php/submit-data");

require(JModuleHelper::getLayoutPath('mod_dianbiao_submit', 'default'));






?>