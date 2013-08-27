<?php

/**
 *      [dazheng!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php 21922 2012/3/13 02:41:54Z angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$width=$_G['gp_pic_width'];
if($width)
{
	$width="width='{$width}'";
}else{
	$width="width='720'";
}

$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8";
	$mlh_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1186 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/shang-hai-mei-lan-hu-gao-er/id661625407?ls=1&mt=8";
	$ns_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1160 order by app_version_addtime desc limit 1 ");//"http://www.baidu.com";
	
}
else if(strpos($userAgent,"Android"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$mlh_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$ns_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1160 order by app_version_addtime desc limit 1 ");
}


include_once template("wap/field_space");


?>