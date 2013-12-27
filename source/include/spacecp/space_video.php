<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_videophoto.php 22572 2011-05-12 09:35:18Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
 $op=$_GET['act'];
 $id=$_GET['id'];
 //$uid=$_GET['uid'];
 if($op=='list'){ 
 $perpage = 10;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
 

$count = DB::result(DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v 
										inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vid DESC"), 0);
 
	$videolist = array();
	if($count) {
		if($page > 1 && $start >=$count) {
			$page--;
			$start = ($page-1)*$perpage;
		}
$query = DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.*  FROM ".DB::table('home_video')." v 
										inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE v.uid='$uid' ORDER BY v.vid DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$videolist[] = $value;
		}
	}
 	$multi = multi($count, $perpage, $page, "home.php?mod=space&do=video&act=list");

 }
 else
 {$query = DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v 
										inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE v.uid='$uid' and v.vid='$id'");
		while ($value = DB::fetch($query)) {
			$video[] = $value;
		}
 }
 
	$templates='home/'.$getstat['groupid'].'_video';
include_once(template($templates));    
?>