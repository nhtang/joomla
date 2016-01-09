<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_connect
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;
?>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<div id="electrical">
    <h1>链接电表</h1>

    <form action="index.php/meter-connect?meter_model=<?php echo $meter_model;?>&location_id=<?php echo $location_id;?>&meter_address=<?php echo $meter_address;?>" method="post" id="electrical_form">

          <table border="1">
            <tr>
              <td>行动</td>         
              <td colspan="2"><input type="submit" name="submit" value="更新数据"></td>       
            </tr>
            <tr>
              <td>时间</td>  
              <td colspan="2"><?php echo $time; ?></td>
            </tr>
            <tr>
              <td>电压1</td>  
              <td><?php echo $voltage1; ?></td>
              <td>V</td>
            </tr>
            <tr>
              <td>电流1</td>
              <td><?php echo $current1; ?></td>
              <td>A</td>
            </tr>
            <tr>
              <td>功率1</td>
              <td><?php echo $power1; ?></td>
              <td>kVA</td>
            </tr>
            <tr>
              <td>频率1</td>
              <td><?php echo $frequency1; ?></td>
              <td>Hz</td>
            </tr>
           </table>
    </form>
</div>
