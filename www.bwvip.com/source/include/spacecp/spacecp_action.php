<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-08-19 07:35:13Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$act=$_GET['act'];
if($act=='weibo'){
//设置标题
 $navtitle=$space['username'].'的微博';
}
if($act==''){
	
 $navtitle=$space['username'].'手机预订';
}
	$perpage = 10;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
 

$count = DB::result(DB::query("SELECT * FROM zdy_getauto where compid=$space[uid]"), 0);

	$list = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
$query = DB::query("SELECT * FROM zdy_getauto where compid=$space[uid] LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$list[] = $value;
		}
	}
 	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=action");
 
if($act!='weibo'){
$templates='home/spacecp_action';
}
if($act=='weibo'){
$wburl=$_GET['wburl'];
$wburl=base64_decode($wburl);
$templates='home/spacecp_'.$getstat['groupid'].'_weibo';
}

include_once(template($templates)); 
?>