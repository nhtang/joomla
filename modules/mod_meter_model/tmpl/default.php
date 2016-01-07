<?php
/**
 * @package     electromonitor.com
 * @subpackage  mod_meter_model
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
	
    
    $sql = "select * from joomla3_metermodel  order by meter_model_id desc";
	$rs = mysql_query($sql);
	$rsnum = mysql_num_rows($rs); 
	 
    $none_data = "<tr align=center><td><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color=index.php/meter-modelff000f>数据库中暂时还没有录入数据！</font></a></td></tr><br><br>";
	
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
  <td width=50px><b>序号</font></td>
  <td width=120px><b>电表型号</font></td>
  <td width=100px><b>生产厂名</font></td> 
  <td width=100px><b>指令码</font></td> 
  <td width=50px><b>长度</font></td> 
  <td width=100px><b>地址码</font></td>
  <td width=100px><b>功能码</font></td>
  <td width=140px><b>起始寄存器地址</font></td>
  <td width=110px><b>寄存器个数</font></td>
  <td width=100px><b>校验码</font></td>
  <td width=100px><b>参数位置</font></td>
 </tr>

    <?php		
  
        while(($row=mysql_fetch_array($rs)) && ($noterecs <= ($pagesize - 1))){
		  $meter_model_id = $row['meter_model_id'];
          $meter_model = $row['meter_model'];
	      $meter_factory = $row['meter_factory'];
	      $command_code = $row['command_code'];
	      $var_len = $row['var_len'];
	      $address_code = $row['address_code'];
	      $function_code = $row['function_code'];
	      $storage_start_address = $row['storage_start_address'];
	      $storage_numbers = $row['storage_numbers'];
	      $check_code = $row['check_code'];
		  $data_index = $row['data_index'];
    ?> 
 <tr  onmouseover="this.style.backgroundColor='#e5ff00'" onmouseout="this.style.backgroundColor='#ffffff'" style="font-size:12px;color:#000035;">
 
   <td align="center" >
      <a href="index.php/meter-model-fix?meter_model_id=<?php echo $meter_model_id; ?>" title="记录序号：<?php echo $meter_model_id." &nbsp;&nbsp;&nbsp;&nbsp;电表型号：".$meter_model." &nbsp;&nbsp;&nbsp;&nbsp;指令码：".$command_code; ?>">
	  <?php echo $meter_model_id;?>
	  </a>
	  </td>
   <td align="center" ><?php echo $meter_model;?></td>
   <td align="center" ><?php echo $meter_factory;?></td>
   <td align="center" ><?php echo $command_code;?></td>
   <td align="center" ><?php echo $var_len;?></td>
   <td align="center" ><?php echo $address_code;?></td>
   <td align="center" ><?php echo $function_code;?></td>
   <td align="center" ><?php echo $storage_start_address;?></td>
   <td align="center" ><?php echo $storage_numbers;?></td>
   <td align="center" ><?php echo $check_code;?></td>
   <td align="center" ><?php echo $data_index;?></td>
  
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
        <a href="index.php/meter-model?page=1">首页</a>&nbsp;
		<a href="index.php/meter-model?page=
            <?php  if ($notepage>1) 
                    echo $notepage-1; 
                  else
                    echo $notepage; ?>" title=上一页>上一页</a>&nbsp;&nbsp;
            <?php echo $notepage; ?>
        <a href="index.php/meter-model?page=
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
            <option width=50px value="index.php/meter-model?page=<?php echo $i; ?>" selected>第<?php echo $i; ?>页</option>&nbsp;
	
            <?php  } else {  ?>

            <option width=50px value="index.php/meter-model?page=<?php echo $i; ?>">第<?php echo $i; ?>页</option>&nbsp;
	        <?php  
			       }
                }
			?>
        </select>

        <a href="index.php/meter-model?page=<?php echo $pagecount; ?>">尾页</a>
        
    </td>
  </tr>
</table>
</div>

    <?php
	}
    ?>
<!--循环体结束--> 

 

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
        <input id="check_code" name="check_code" type="text" size="10" value="" /><br>
		   参数位置：&nbsp;
        <input class="input-xxlarge" id="data_index" name="data_index" type="text"  value="" /><br>
          <font style="color:#5d5d5d;"> 
		    * 参数位置的填写模式为：( u1-10, u2-15, u3-20, i1-11, i2-17, i3-23, s1-xx, s2-xx, s3-xx, f1-xx, f2-xx, f3-xx )
		  </font>
		   <?php
            		   
		    echo '<br/>';
			$str = "     u1-00 10, u2-0x 20, u3-30, i1-11, i2-15, i3-19";
		    $strArr=explode(',',$str); //explode str
			$arr_num = sizeof($strArr); //cout array numbers or // $arr_num = count($strArr);
			for($i = 0; $i<$arr_num ; $i++){
              echo $i.':'.$strArr[$i].'<br/>';
            }
		  
			$var_u1 = $strArr[0]; 
	        $u1_address = explode("-",$var_u1);
	        echo $u1_address[1]; 
			
		   ?>
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

