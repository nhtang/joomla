<?php     
$con=mysql_connect("localhost","root","huge");
if (!$con)
 {  
  die('���ݿ�����ʧ�ܣ�' . mysql_error());
 }
@mysql_select_db("huge_mysql", $con);
mysql_query("set names utf8")
?>
