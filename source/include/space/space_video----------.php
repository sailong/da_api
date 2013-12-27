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
 $op = $_GET['act'];
 $id = $_GET['id'];
 $uid = $_GET['uid'];
 if($op == 'list') { 
	$perpage = 10;
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	if($page < 1) {
		$page = 1;
	}
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
 
	$count = DB::result_first("select count(*) FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vpid DESC");
	$videolist = array();
	if($count) {
		$query = DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.*  FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE v.uid='$uid' ORDER BY v.vpid DESC LIMIT $start,$perpage");
		while ($value = DB::fetch($query)) {
			$videolist[] = $value;
		}
	}
 	$multi = multi($count, $perpage, $page, "home.php?mod=space&do=video&act=list&uid=$uid");
 } else {
	 

	 
	$query = DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,v.gvideo,vp.* FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE  v.vpid='$id'"); 
	while ($value = DB::fetch($query)) { 	
		$video[] = $value;
	} 
	$gvideo = DB::result_first("SELECT  gvideo from pre_home_video  where  vpid='$id'");
	//徐玉枭三个一组
	if($gvideo){
	$i=1;
	$count1 = DB::result_first("SELECT count(*) FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE  v.gvideo='$gvideo'"); 
	$query=DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid  WHERE  v.gvideo='$gvideo'");
   while($value = DB::fetch($query)) {
	   if($i<$count1){
    $dxlbvideo=$dxlbvideo.'http://t2012.bwvip.com/'.$value['webpath'].'|'; }else{
	$dxlbvideo=$dxlbvideo.'http://t2012.bwvip.com/'.$value['webpath']; }
	$i=$i+1;
    }
 }
 }
 
$templates='home/'.$gropid.'_video';
include_once(template($templates));    
?>