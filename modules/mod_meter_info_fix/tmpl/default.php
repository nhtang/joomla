<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info_fix
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;
?>

<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>


<div id="meter_info">
    <br><br>
<form id=form  name="form"  method="post" action="index.php/meter-info-update" onSubmit='return javacheck(this)'>
 <table width="900px" align=left  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;"> 
   <tr > 
    <td border="0" cellpadding="0" cellspacing="0" style="padding-left:5px;">
           <font style="color:#00a0e8">序&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号：&nbsp;&nbsp;<?php echo $info_id; ?></font>
		   <input id="info_id" name="info_id" type="hidden" size="10" value="<?php echo $info_id; ?>" />
		   <br><br>
            	
           电表位置码&nbsp;：
        <input id="location_id" name="location_id" type="text" size="10" value="<?php echo $location_id; ?>" /><br>
           电表地址码&nbsp;：
        <input id="meter_address" name="meter_address" type="text" size="10" value="<?php echo $meter_address; ?>" /><br>
           电&nbsp;表&nbsp;型&nbsp;号&nbsp;：
        <input id="meter_model" name="meter_model" type="text" size="10" value="<?php echo $meter_model; ?>" /><br>

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
