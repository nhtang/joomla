<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */
//
defined('_JEXEC') or die;
?>

<html>
  <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
  <head>
  </head>
<div id="electrical">


 

<br><br>
<form id=form  name="form"  method="post" action="index.php/meter-model-submit" onSubmit='return javacheck(this)'>
 <table width="900px" align=left  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;"> 
   <tr > 
    <td border="0" cellpadding="0" cellspacing="0" style="padding-left:5px;"> 
           电表型号：&nbsp;
        <input id="meter_model" name="meter_model" type="text" size="10" value="" /><br>
           生产厂名：&nbsp;
        <input id="meter_factory" name="meter_factory" type="text" size="10" value="" /><br>
           指&nbsp;&nbsp;令&nbsp;&nbsp;码：
        <input id="command_code" name="command_code" type="text" size="10" value="" /><br>
           长&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;度：
        <input id="var_len" name="var_len" type="text" size="10" value="" /><br>
           地&nbsp;&nbsp;址&nbsp;&nbsp;码：
        <input id="address_code" name="address_code" type="text" size="10" value="" /><br>
           功&nbsp;&nbsp;能&nbsp;&nbsp;码：
        <input id="function_code" name="function_code" type="text" size="10" value="" /><br>
           起始寄存器地址：
        <input id="storage_start_address" name="storage_start_address" type="text" size="10" value="" /><br>
           寄存器个数：
        <input id="storage_numbers" name="storage_numbers" type="text" size="10" value="" /><br>
           校&nbsp;&nbsp;验&nbsp;&nbsp;码：
        <input class="input-xxlarge" id="check_code" name="check_code" type="text" size="10" value="" /><br>
		   参数位置：&nbsp;
        <input class="input-xxlarge" id="data_index" name="data_index" type="text"  value="" /><br>
          <font style="color:#5d5d5d;"> 
		    * 校验码、参数位置的填写模式为：( u1-10 , u2-15 , u3-20 , i1-11 , i2-xx , i3-xx , s1-xx , s2-xx , s3-xx , f1-xx , f2-xx , f3-xx )
		  </font>
		<br>
    <div>
	<br>
	    <input type="submit" value=" 提  交 "  id="send-btn">
    </div>
    <br><br>
    </td>
   </tr>
 </table>
</form>


</div>
<script language=JavaScript1.2>
 
function javacheck(formct)
{
	
      
        
        if (formct.meter_model.value.replace(/^\s|\s$/g,'') == '') 
	{
		alert('请填写电表型号！');
                 formct.meter_model.focus();
		return false; 
	} 

    
       
}

</script>

