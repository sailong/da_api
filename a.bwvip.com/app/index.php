<?php
define('APPTYPEID', 0);
define('CURSCRIPT', 'member');
require '../source/class/class_core.php';
$discuz = & discuz_core::instance();

$discuz->init();


$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	//iPhone
	echo "<script>location='https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8';</script>";
	//echo "http://www.bwvip.com/nd/fenzhan.php";
	//echo "iphone客户端暂不能下载，请使用彩信二维码签到";
}
/*
else if(strpos($userAgent,"Android"))
{
	//Android
	echo "<h1>Android访问</h1>";
}
*/
else
{
	$version=DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' order by app_version_addtime desc limit 1 ");
	//echo $version;
	echo "<script>location='".$version."';</script>";
}

?>