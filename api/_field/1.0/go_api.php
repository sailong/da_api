<?php
/*
*
* field_api.php
* by zhanglong 2013-05-21
* field app 跳转接口页
*
*/

if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


$ac=$_G['gp_ac'];
if($ac=="index_dazheng")
{
	$pic_width=$_G['gp_pic_width'];
	echo "<script>location='http://www.bwvip.com/api/page/dazheng_chuanmei/index.php?pic_width=".$pic_width."';</script>";
	
}


?>