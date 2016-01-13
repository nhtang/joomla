<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_info
 *
 * @copyright   Copyright (C) 2015 All rights reserved.
 */

defined('_JEXEC') or die;
?>

<html>
  <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
  <head>
  </head>
<body>


<div id="table" style="padding-top:5px;">
<table width="710px" align=center  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;">

<?php
    @$page = $_GET['page'];
    if($page==""){  
      $notepage=1; 
    }else{ 
      $notepage=$page; 
    } 
    $noterecs=0; 
    $pagesize=10;
	
    
    $sql = "select * from joomla3_meter_info  order by info_id desc";
	$rs = mysql_query($sql);
	$rsnum = mysql_num_rows($rs); 
	 
    $none_data = "<tr align=center><td><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=#ff000f>数据库中暂时还没有录入数据！</font></a><br><br></td></tr>";
	
	if($rsnum == ""){
		echo $none_data;}
    else{
		$pagecount=ceil($rsnum/$pagesize); 
        mysql_data_seek($rs,($notepage-1)*$pagesize); 	

        if ($notepage == 1){
	      $i=1;
        }else{
	      $i=$notepage*$pagesize-$pagesize+1;
        }
		
?>
 
 <tr align=center >
  <td width=50px><b>序号</td>
  <td width=50px><b>位置码</td>
  <td width=100px><b>表地址</td> 
  <td width=100px><b>电表型号</td>
  <td width=100px><b>采集参数项</td>  
  <td width=100px><b>电表状态</td> 
 </tr>

    <?php		
  
        while(($row=mysql_fetch_array($rs)) && ($noterecs <= ($pagesize - 1))){
		  $info_id = $row['info_id'];
          $location_id = $row['location_id'];
	      $meter_address = $row['meter_address'];
		  $meter_model = $row['meter_model'];
		  $data_select = $row['data_select'];
    ?> 
 <tr  onmouseover="this.style.backgroundColor='#e5ff00'" onmouseout="this.style.backgroundColor='#ffffff'" style="font-size:12px;color:#000035;">
 
   <td align="center" >
      <a href="index.php/meter-info-fix?info_id=<?php echo $info_id; ?>
	  " title="点击修改：<?php echo $info_id." &nbsp;&nbsp;&nbsp;&nbsp;位置码：".$location_id." &nbsp;&nbsp;&nbsp;&nbsp;电表地址：".$meter_address." &nbsp;&nbsp;&nbsp;&nbsp;电表型号：".$meter_model; ?>">
	  <?php echo $info_id;?>
	  </a>
	  </td>
   <td align="center" ><?php echo $location_id;?></td>
   <td align="center" ><?php echo $meter_address;?></td>
   <td align="center" ><?php echo $meter_model;?></td>
   <td align="center" ><?php echo $data_select;?></td>
   <td align="center" >
       <a href="index.php/meter-connect?info_id=<?php echo $info_id; ?>
	   &location_id=<?php echo $location_id; ?>
	   &meter_address=<?php echo $meter_address; ?>
	   &meter_model=<?php echo $meter_model; ?>
	   &data_select=<?php echo $data_select; ?>
	   " title="电表状态 <?php echo $info_id." &nbsp;&nbsp;&nbsp;&nbsp;位置码：".$location_id." &nbsp;&nbsp;&nbsp;&nbsp;电表地址：".$meter_address." &nbsp;&nbsp;&nbsp;&nbsp;电表型号：".$meter_model; ?>">
	   <?php echo $info_id;?>
	  </a>
	</td>

 </tr>


    <?php  
       
        $noterecs = $noterecs+1; 
        $i = $i+1;
        }//while 
    ?> 
</table>
</div>

<br>

<div id="table2" style="padding-top:5px;">
<table width="700px" align=center  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;">
  <tr align=left>
    <td width=200px style="padding-left:5px;">
     共 <?php echo $rsnum; ?> 项&nbsp;&nbsp;&nbsp;每页 <?php echo $pagesize; ?> 项&nbsp;&nbsp;&nbsp;&nbsp;页次：<?php echo $notepage; ?>/<?php echo $pagecount; ?>
    </td>
    <td width=500px align=right>  
        <a href="index.php/meter-info?page=1">首页</a>&nbsp;
		<a href="index.php/meter-info?page=
            <?php  if ($notepage>1) 
                    echo $notepage-1; 
                  else
                    echo $notepage; ?>" title=上一页>上一页</a>&nbsp;&nbsp;
            <?php echo $notepage; ?>
        <a href="index.php/meter-info?page=
            <?php if ($notepage==$pagecount) 
                   echo $notepage; 
                 else
                   echo $notepage+1 ;?>" title=下一页>&nbsp;下一页</a>&nbsp;
          
        <select class="input-small" onChange="window.location=this.options[this.selectedIndex].value">
            <?php 
                for($i=1;$i<=$pagecount;$i++) 
                { 
                   if ( $i == $notepage )
	               {
            ?>
            <option width=50px value="index.php/meter-info?page=<?php echo $i; ?>" selected>第<?php echo $i; ?>页</option>&nbsp;
	
            <?php  } else {  ?>

            <option width=50px value="index.php/meter-info?page=<?php echo $i; ?>">第<?php echo $i; ?>页</option>&nbsp;
	        <?php  
			       }
                }
			?>
        </select>

        <a href="index.php/meter-info?page=<?php echo $pagecount; ?>">尾页</a>
        
    </td>
  </tr>
</table>
</div>


    <?php
	}
    ?>
<!--循环体结束-->


<br>
<a href="index.php/meter-connect?info_id=<?php echo $info_id; ?>
	   &location_id=<?php echo $location_id; ?>
	   &meter_address=<?php echo $meter_address; ?>
	   &meter_model=<?php echo $meter_model; ?>
	   &data_select=<?php echo $data_select; ?>
	   " title="电表状态 <?php echo $info_id." &nbsp;&nbsp;&nbsp;&nbsp;位置码：".$location_id." &nbsp;&nbsp;&nbsp;&nbsp;电表地址：".$meter_address." &nbsp;&nbsp;&nbsp;&nbsp;电表型号：".$meter_model; ?>">
	   采集电表数据
</a> 
<form id=go  name="go"  method="post" action="index.php/meter-connect" onSubmit='return javacheck(this)'>
    <input id="location_id" name="location_id" type="hidden" size="10" value="<?php echo $info_id; ?>" /><br>
	<br>
	    <input type="submit" value=" 采集电表数据 "  id="get_data">
</form>

<br><br>
<form id=form  name="form"  method="post" action="index.php/meter-info-submit" onSubmit='return javacheck(this)'>
 <table width="900px" align=left  cellpadding="0" cellspacing="0" style="background-color:#F8F8FF;border-left:none;border-top:none;border-right:none;"> 
   <tr > 
    <td border="0" cellpadding="0" cellspacing="0" style="padding-left:5px;"> 
           电表位置码&nbsp;：
        <input id="location_id" name="location_id" type="text" size="10" value="" /><br>
           电表地址码&nbsp;：
        <input id="meter_address" name="meter_address" type="text" size="10" value="" /><br>
           电&nbsp;表&nbsp;型&nbsp;号&nbsp;：
        <input id="meter_model" name="meter_model" type="text" size="10" value="" /><br>
		   采集参数项&nbsp;：
        <input id="data_select" name="data_select" type="text" size="10" value="" /><br>
		  <font style="color:#5d5d5d;"> 
		    * 采集参数项的填写模式为：( u1 , u2 , u3 , i1 , i2 , i3 , s1 , s2 , s3 , f1 , f2 , f3 , ... )
		  </font>
    <div>
	<br>
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
	
      
        
        if (formct.location_id.value.replace(/^\s|\s$/g,'') == '') 
	{
		alert('请填写电表位置码！');
                 formct.location_id.focus();
		return false; 
	} 

        if (formct.meter_address.value.replace(/^\s|\s$/g,'') == '') 
	{
		alert('请填写电表地址码！');
                 formct.meter_address.focus();
		return false; 
	} 
	
	    if (formct.meter_model.value.replace(/^\s|\s$/g,'') == '') 
	{
		alert('请填写电表型号！');
                 formct.meter_model.focus();
		return false; 
	} 
       
}

</script>

