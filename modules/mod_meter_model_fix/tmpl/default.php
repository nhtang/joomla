<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model_fix
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;
?>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<div id="electrical">
    <br><br>
<form id=form  name="form"  method="post" action="index.php/meter-model-update" onSubmit='return javacheck(this)'>
 <table width="900px" align=left  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;"> 
   <tr > 
    <td border="0" cellpadding="0" cellspacing="0" style="padding-left:5px;">
           <font style="color:#00a0e8">序&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：&nbsp;&nbsp;<?php echo $meter_model_id; ?></font>
		   <input id="meter_model_id" name="meter_model_id" type="hidden" size="10" value="<?php echo $meter_model_id; ?>" />
		   <br><br>
            	
           电表型号：&nbsp;
        <input id="meter_model" name="meter_model" type="text" size="10" value="<?php echo $meter_model; ?>" /><br>
           生产厂名：&nbsp;
        <input id="meter_factory" name="meter_factory" type="text" size="10" value="<?php echo $meter_factory; ?>" /><br>
           指&nbsp;&nbsp;令&nbsp;&nbsp;码：
        <input id="command_code" name="command_code" type="text" size="10" value="<?php echo $command_code; ?>" /><br>
           长&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;度：
        <input id="var_len" name="var_len" type="text" size="10" value="<?php echo $var_len; ?>" /><br>
           地&nbsp;&nbsp;址&nbsp;&nbsp;码：
        <input id="address_code" name="address_code" type="text" size="10" value="<?php echo $address_code; ?>" /><br>
           功&nbsp;&nbsp;能&nbsp;&nbsp;码：
        <input id="function_code" name="function_code" type="text" size="10" value="<?php echo $function_code; ?>" /><br>
           起始寄存器地址：
        <input id="storage_start_address" name="storage_start_address" type="text" size="10" value="<?php echo $storage_start_address; ?>" /><br>
           寄存器个数：
        <input id="storage_numbers" name="storage_numbers" type="text" size="10" value="<?php echo $storage_numbers; ?>" /><br>
           校&nbsp;&nbsp;验&nbsp;&nbsp;码：
        <input id="check_code" name="check_code" type="text" size="10" value="<?php echo $check_code; ?>" /><br>

    <div>
	    <input type="submit" value=" 提  交 "  id="send-btn">
    </div>
    <br><br>
    </td>
   </tr>
 </table>
</form>



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
          
   
</div>
