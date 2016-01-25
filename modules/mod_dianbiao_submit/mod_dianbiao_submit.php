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


date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;
$server_datetime = date("Y-m-d H:i:s", strtotime("-60 seconds"));

$limit = 100; // number of records to retrieve


$data_pos = ModDianBiaoSubmitHelper::getDataPos();
if($data_pos == ""){$data_pos = 0;}

$time_pos = ModDianBiaoSubmitHelper::getTimePos();
$time_pos = date("Y-m-d H:i:s", strtotime($time_pos));

//$controller_electrical_id = $data_pos;
//$datatime = $time_pos;

$try_time = ModDianBiaoSubmitHelper::getTryTime();
echo "<br>try_time : $try_time";
/*switch($try_time){
	case "3":
	  $try_time = 2 ; //next try again after 30's 
	  break;
    case "2":
	  $try_time = 1 ;//The thrid try again after 10'm
	  break;
    case "1":
	  $try_time = 3 ;//The last try again after 2'h
	  break;
    	  
}*/
//echo "<br>var try_time : $try_time";

unset($electrical_data);
$electrical_data = ModDianBiaoSubmitHelper::getElectricalData($server_datetime, $data_pos, $time_pos, $limit);
$size = count($electrical_data);
// echo "size of data is $size";


// send data to electrom server
//$url = 'http://www.electromonitor.com/monitor/index.php/submit-values';
 //$url = 'http://localhost/joomla/index.php/submit-values';

// set up _POST array of key value pairs
$data_rows = $electrical_data;

$n = 0;
unset ($_POST);
//$_POST["num_records"] = $limit;
$num_records = $limit;
//$fields = 16;
foreach ($data_rows AS $data) {

  $_POST["controller_electrical_id-$n"]  = $data['electrical_id'];
  $_POST["location_id-$n"]  = $data['location_id'];
  $_POST["meter_address-$n"]  = $data['meter_address'];
  $_POST["datetime-$n"]  = $data['datetime'];
  $_POST["phase1_voltage-$n"]  = $data['phase1_voltage'];
  $_POST["phase1_current-$n"]  = $data['phase1_current'];
  $_POST["phase1_apparent_power-$n"]  = $data['phase1_apparent_power'];
  $_POST["phase1_frequency-$n"]  = $data['phase1_frequency'];
  $_POST["phase2_voltage-$n"]  = $data['phase2_voltage'];
  $_POST["phase2_current-$n"]  = $data['phase2_current'];
  $_POST["phase2_apparent_power-$n"]  = $data['phase2_apparent_power'];
  $_POST["phase2_frequency-$n"]  = $data['phase2_frequency'];
  $_POST["phase3_voltage-$n"]  = $data['phase3_voltage'];
  $_POST["phase3_current-$n"]  = $data['phase3_current'];
  $_POST["phase3_apparent_power-$n"]  = $data['phase3_apparent_power'];
  $_POST["phase3_frequency-$n"]  = $data['phase3_frequency'];
  
  $n++;
   //echo "n= $n ...";

} //foreach
  $fields = sizeof($_POST)/$n;
  //echo "<br>fields : $fields ";
  
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


?>
<div id="setTimejump" algin=center></div>
<script src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.8.0.js"></script>
<script type="text/javascript">

function uploaddata(){
	//alert(" inside getpushdata");
	   
       var post = '<?php echo json_encode($_POST);?>';
	   var data_pos = '<?php echo $data_pos ;?> ';
	   var time_pos = '<?php echo $time_pos ;?> ';
	   
		jQuery.ajax({
			url: "index.php",
			//url: 'http://www.electromonitor.com/monitor/index.php',
			data: {"option":"com_ajax", "module":"uploaddata", "method":"getUploadData","format":"json" 
			 , "allarr" : post,
		   "num_records" : "<?php echo $limit;?>",
		   "fields" : "<?php echo $fields;?>"
		   }
		})
		.done(function () {
			//alert(" Updata to server succeed! \n Last record controller_electrical_id is : "+data_pos+"\n Last record datetime is : "+time_pos);
			location.href="index.php/move-updata-pos?data_pos="+data_pos+"&time_pos="+time_pos;
		})
		.fail(function (request, status, error) {
			alert(request.responseText);
			//setTimejump()
			location.href="index.php/updata-error?&try_time=<?php echo $try_time;?>&error_msg="+request.responseText;
		});
	//alert(" end jquery");
}

uploaddata()  //Run Ajax functon uploadata()



var time = 30;
var try_times = "<?php echo $try_time; ?>";
function setTimejump(){ 
  time--;
  try_times--;
  
  // obj.innerHTML = "上传数据失败! " + (time--) + "秒后自动重新上传，如果没有自动跳转<a href=\"" + url + "\">请点这里<\/a>";
 if(time < 0){ location.href="index.php/submit-data";}else{ setTimeout(setTimejump, 1000) }
}


</script>

<?php 
      
    //ModDianBiaoSubmitHelper::setDataPos($data_pos);
    //ModDianBiaoSubmitHelper::setTimePos($time_pos);



//$lines = file("http://localhost/joomla/index.php/submit-data");

require(JModuleHelper::getLayoutPath('mod_dianbiao_submit', 'default'));
//sleep(5);

?>