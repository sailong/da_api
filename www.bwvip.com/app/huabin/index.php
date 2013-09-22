<?php
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();

$android_url=DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=3803491 order by app_version_addtime desc limit 1 ");
$ios_url=DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=3803491 order by app_version_addtime desc limit 1 ");

/*
$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	//iPhone
	//echo "<script>location='https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8';</script>";
	//echo "http://www.bwvip.com/nd/fenzhan.php";
	//echo "iphone客户端暂不能下载，请使用彩信二维码签到";
}

else if(strpos($userAgent,"Android"))
{
	//Android
	echo "<h1>Android访问</h1>";
}

else
{
	$android_url=DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' order by app_version_addtime desc limit 1 ");
	//echo $version;
	//echo "<script>location='".$version."';</script>";
}

*/

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>北京华彬国际高尔夫俱乐部</title>
<style type="text/css">
body{margin:0;padding:0;}
img{border:0;}
.top{width:550px;height:287px; margin:0 auto; background:url(images/pic1.gif) no-repeat;padding-top:362px;padding-left:170px;}	
.top .p1{width:550px;height:103px;}
.top .p1 img{width:379px;height:103px; cursor:pointer;}
.top .p2{width:550px;height:103px;margin-top:44px;}
.top .p2 img{width:379px;height:103px; cursor:pointer;}
</style>
</head>

<body>
<table width="100%" height="650" border="0" cellspacing="0" cellpadding="0" style="background:url(images/pic1.png) center top no-repeat;">
  <tr>
    <td align="center"  style="height:200px;"></td>
  </tr>
  <!--<tr>
    <td align="center" style="height:120px;"><a href="<? echo $ios_url;?>" target="_blank"><img src="images/iphone.gif" alt="" width="379" height="103" /></a></td>
  </tr>-->

  <tr>
    <td align="center"><a href="<? echo $android_url;?>" target="_blank"><img src="images/android.gif" width="379" height="103" /></a></td>
  </tr>
</table>

</body>
</html>
