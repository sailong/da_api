<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_memberphoto.php 22572 2011-05-12 09:35:18Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
 $op=$_GET['act'];
 $id=$_GET['id'];
 $uid=$_GET['uid']; 
 $perpage = 10;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
 
 
 $count = DB::result_first("SELECT count(*) FROM ".DB::table('guanxi')." as a INNER JOIN ".DB::table('common_member_profile')." as b ON a.userid=b.uid  INNER JOIN ".DB::table('common_member')." as c  ON c.uid=b.uid   where a.compid=$uid and a.iscomp=1");
//echo $count;
	$memberlist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
$query = DB::query("SELECT a.compid,a.userid,b.realname,c.username FROM ".DB::table('guanxi')." as a INNER JOIN ".DB::table('common_member_profile')." as b ON a.userid=b.uid  INNER JOIN ".DB::table('common_member')." as c  ON c.uid=b.uid   where a.compid=$uid and a.iscomp=1 LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$memberlist[] = $value;
		}
	}
 	$multi = multi($count, $perpage, $page, "home.php?mod=space&do=member&act=list&uid=".$uid);
 
  
    $ty=getusrarry($uid);   //取出当前用户的组id
	  $templates='home/'.$ty['groupid'].'_member';
	//$templates='home/'.$gropid.'_member';
include_once(template($templates));    
?>