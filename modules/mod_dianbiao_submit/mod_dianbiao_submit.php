<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_dianbiao
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
header('Content-type: text/html; charset=utf8');
defined('_JEXEC') or die;

// Include the functions only once
require_once __DIR__ . '/helper.php';

JHTML::stylesheet('styles.css','modules/mod_dianbiao_submit/css/');


date_default_timezone_set('Asia/Singapore');
$datetime = date('Y-m-d H:i:s');
$time = $datetime;
$server_datetime = date("Y-m-d H:i:s", strtotime("-60 seconds"));

$limit = 30; // number of records to retrieve


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
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<!--script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script-->
<script type="text/javascript">



function uploaddata(){
	//alert(" inside getpushdata");
	   
       var post = '<?php echo json_encode($_POST);?>';
	   var data_pos = '<?php echo $data_pos ;?> ';
	   var time_pos = '<?php echo $time_pos ;?> ';
	   
		jQuery.ajax({
			type : "get",
            //async : false,
			//cache:false,
			//crossdomain: true,
			//url: "index.php",
			url: "http://www.electromonitor.com/monitor/index.php",
			
			dataType:'jsonp',  //选择返回值类型  
			jsonp: "callbackparam",    //规定发送/接收参数，默认为callback
            jsonpCallback:"jsonpCallback",
            timeout:5000,
			data: {"option":"com_ajax", "module":"uploaddata", "method":"getUploadData","format":"jsonp", 
			       "allarr" : post,
		           "num_records" : "<?php echo $limit;?>",
		           "fields" : "<?php echo $fields;?>"
		    },
            success: function(){
				alert('success!');
             },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
				         //result = JSON.stringify(XMLHttpRequest);
						 callText = XMLHttpRequest.statusText;
						 //dataToLoadObj = JSON.parse(result); // dataToLoadObj is a JSON object, not an array
			             //dataToLoad = Object.keys(result).map(  function(2) { return dataToLoadObj[0] }  );
				       //alert('XMLHttpRequest : '+returnText);
						alert('XMLHttpRequest : '+callText);
                        //alert('status : '+XMLHttpRequest.status);
                        //alert('readyState : '+ XMLHttpRequest.readyState);
                        //alert('textStatus : '+textStatus);
						//alert('errorThrown : '+XMLHttpRequest.errorThrown);
						
						if(callText == "success"){
							location.href="index.php/updata-pos?data_pos="+data_pos+"&time_pos="+time_pos;
						}else{
							location.href="index.php/updata-error?&try_time=<?php echo $try_time;?>&error_msg="+callText;
						}
            },
            complete: function(XMLHttpRequest, textStatus) {
                        this; // 调用本次AJAX请求时传递的options参数
            }
        });
}			

/*parsererror原因分析： 
   1.data:"{}", data为空也一定要传"{}"；不然返回的是xml格式的 
   2.返回的数据中必须将单引号改为双引号 (默认: 自动判断 (xml 或 html)) 请求失败时调用时间。
       参数有以下三个：XMLHttpRequest 对象、错误信息、（可选）捕获的错误对象。
	   如果发生了错误，错误信息（第二个参数）除了得到null之外，还可能是"timeout", "error", "notmodified" 和 "parsererror"。
	   
	error事件返回的第一个参数XMLHttpRequest有一些有用的信息：XMLHttpRequest.readyState: 
	       状态码 0 － （未初始化）还没有调用send()方法 
		          1 － （载入）已调用send()方法，正在发送请求 
				  2 － （载入完成）send()方法执行完成，已经接收到全部响应内容
				  3 － （交互）正在解析响应内容 
				  4 － （完成）响应内容解析完成，可以在客户端调用了 XMLHttpRequest.status属性
*/


/*
function uploaddata(){
	//alert(" inside getpushdata");
	   
       var post = '<?php echo json_encode($_POST);?>';
	   var data_pos = '<?php echo $data_pos ;?> ';
	   var time_pos = '<?php echo $time_pos ;?> ';
	   
		jQuery.ajax({
			//url: "index.php",
			url: "http://www.electromonitor.com/monitor/index.php",
			
			//async: false,
			data: {"option":"com_ajax", "module":"uploaddata", "method":"getUploadData","format":"json", 
			       "allarr" : post,
		           "num_records" : "<?php echo $limit;?>",
		           "fields" : "<?php echo $fields;?>"
		    }
			 
			
		})
		.done(function (data, status) {

			//alert(" Updata to server succeed! \n Last record controller_electrical_id is : "+data_pos+"\n Last record datetime is : "+time_pos);
			location.href="index.php/updata-pos?data_pos="+data_pos+"&time_pos="+time_pos;
		})
		.fail(function (request, status, error) {
			alert(request.error);
			location.href="index.php/updata-error?&try_time=<?php echo $try_time;?>&error_msg="+request.responseText;
		});
		
	//alert(" end jquery");
}
*/


uploaddata()  //Run Ajax functon uploadata()

</script>

<?php 
      
    //ModDianBiaoSubmitHelper::setDataPos($data_pos);
    //ModDianBiaoSubmitHelper::setTimePos($time_pos);

//$lines = file("http://localhost/joomla/index.php/submit-data");

require(JModuleHelper::getLayoutPath('mod_dianbiao_submit', 'default'));
//sleep(5);

?>