<?php     
$con=mysql_connect("localhost","root","huge");
if (!$con)
 {  
  die('数据库连接失败：' . mysql_error());
 }
@mysql_select_db("j3", $con);
mysql_query("set names utf8")
?>
