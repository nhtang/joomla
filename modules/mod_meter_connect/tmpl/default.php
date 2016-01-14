<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_connect
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;
?>
<html>
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<form id=go  name="go"  method="post" action="index.php/meter-info" onSubmit='return javacheck(this)'>
    <input id="location_id" name="location_id" type="hidden" size="10" value="<?php echo $location_id; ?>" /><br>
	<input id="meter_address" name="meter_address" type="hidden" size="10" value="<?php echo $meter_address; ?>" />
	<br>
	    <input type="submit" value=" 全部停止采集 "  id="get_data">
</form>
<div id="electrical">
    <h1>电表 [ <?php echo $meter_address; ?> ] </h1>

    <form action="index.php/meter-connect?meter_model=<?php echo $meter_model;?>&location_id=<?php echo $location_id;?>&meter_address=<?php echo $meter_address;?>" method="post" id="electrical_form">

          <table border="1">
            <tr>
              <td>行动</td>         
              <td colspan="6">
			  <!--input type="submit" name="submit" value="更新数据" -->
			  <form id=gostop  name="gostop"  method="post" action="index.php/" onSubmit='return javacheck(this)'>
    			  <input id="location_id" name="location_id" type="hidden" size="10" value="<?php echo $location_id; ?>" />
				  <input id="meter_address" name="meter_address" type="hidden" size="10" value="<?php echo $meter_address; ?>" />
	    		  <input type="submit" value=" 停止采集 "  id="stop_data">
			  </form>
			  </td> 
               <td colspan="4"></td>			   
            </tr>
            <tr>
              <td>时间</td>  
              <td colspan="6"><?php echo $time; ?></td>
			  <td colspan="4"></td>
            </tr>
            <tr>
              <td>电压1</td>  
              <td><?php echo $voltage1; ?></td>
              <td>V</td>
			  <td>电压2</td>  
              <td><?php echo $voltage2; ?></td>
              <td>V</td>
			  <td>电压3</td>  
              <td><?php echo $voltage3; ?></td>
              <td>V</td>
            </tr>
            <tr>
              <td>电流1</td>
              <td><?php echo $current1; ?></td>
              <td>A</td>
			  <td>电流2</td>
              <td><?php echo $current2; ?></td>
              <td>A</td>
			  <td>电流3</td>
              <td><?php echo $current3; ?></td>
              <td>A</td>
            </tr>
            <tr>
              <td>功率1</td>
              <td><?php echo $power1; ?></td>
              <td>kVA</td>
			  <td>功率2</td>
              <td><?php echo $power2; ?></td>
              <td>kVA</td>
			  <td>功率3</td>
              <td><?php echo $power3; ?></td>
              <td>kVA</td>
            </tr>
            <tr>
              <td>频率1</td>
              <td><?php echo $frequency1; ?></td>
              <td>Hz</td>
			  <td>频率2</td>
              <td><?php echo $frequency2; ?></td>
              <td>Hz</td>
			  <td>频率3</td>
              <td><?php echo $frequency3; ?></td>
              <td>Hz</td>
            </tr>
           </table>
    </form>
</div>
</html>