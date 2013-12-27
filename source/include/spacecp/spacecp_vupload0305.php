<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_upload.php 22318 2011-04-29 09:34:15Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
//设置标题
$uid = !empty($_GET['uid']) ? $_GET['uid'] : $_G['uid'];
 $navtitle=$space['username'].'的视频管理';
$albumid = empty($_GET['albumid'])?0:intval($_GET['albumid']);
$eventid = empty($_GET['eventid'])?0:intval($_GET['eventid']);
$op=$_GET['aa'];
 
if($op=='fltiao'){
	include_once template("cp_vupload_fenlei");
}

if($op=='fenlei'){
	//$uid = $space['uid'];
	$vtypename = $_GET['newvtypename'];
	
	$count = DB::result_first("select * from ".DB::table('home_videotype')." where uid = $uid and vtypename = '$vtypename'");
  
	if($count>0){
		echo "<script language=javascript>alert('该分类已存在！');history.back(-1);</script>";
		exit;
	}else{
	$sql="insert into ".DB::table('home_videotype')."(uid,vtypename) values ($uid,'$vtypename');";
	$rs  =  DB::query($sql);
	$video = array();
	$video['vtypename'] = $vtypename;
	$video['videoid'] = DB::insert_id();
	}
}
if($op=='tijiao'){
	if($_POST['videoid']=='0'){
		//$uid = $space['uid'];
		$vtypename = $_POST['newvtypename'];
		
	$count = DB::result_first("select * from ".DB::table('home_videotype')." where uid = $uid and vtypename = '$vtypename'"); 
		if($count>0){
			echo "<script language=javascript>alert('该分类已存在！');history.back(-1);</script>";
			exit;
		}else{
			$rs  =  DB::query("insert into ".DB::table('home_videotype')."(uid,vtypename) values ($uid,'$vtypename');");
			$videoid = DB::insert_id();
		}
	}else{
		$videoid = $_POST["videoid"];
	}

	// 获取提交参数
    $title = $_POST["title"];
	$content = $_POST["content"];
//	$videoid = $_POST["videoid"];
	$dateline = time();
	$uid = $space['uid'];
	$username = $space['username'];
	$vpid = $_POST['first_news_vid'];
	$vpid = substr($vpid, 1);
	if (empty($vpid)){
		$vpid = 0;
	}
	if($title==''){
		echo "<script language=javascript>alert('标题不能为空！');history.back(-1);</script>";
		exit;
	}if($videoid=='0'){
		echo "<script language=javascript>alert('请选择分类！');history.back(-1);</script>";
		exit;
	}
	if($content==''){
		echo "<script language=javascript>alert('内容不能为空！');history.back(-1);</script>";
		exit;
	}
  
	if(DB::query("insert into ".DB::table('home_video')."(videoid,vpid,uid,username,dateline,title,content) values ($videoid,$vpid,$uid,'$username',$dateline,'$title','$content');")){
		//积分 
	//DB::query("update ".DB::table('space')." set credit = credit + 10 where uid = $uid"); 
	 showmessage('do_success', 'videocopy.php?do=video');
	}

}
if($op=='del'){
	include_once(S_ROOT.'./source/function_delete.php');
	$id = array($_GET['vid']);
	if(deletevideos($id)){
		showmessage('do_success', 'home.php?mod=spacecp&ac=vupload&aa=list');
	}
}
if($op=='list'){ 
	$perpage = 3;
	$page = empty($_GET['page'])?0:intval($_GET['page']);
	if($page<1) $page = 1;
	$start = ($page-1)*$perpage;
	ckstart($start, $perpage);
 
$count = DB::result_first("select count(*) FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vid DESC");
//$count = DB::result(DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v 
//										inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vid DESC"), 0);

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
 	$multi = multi($count, $perpage, $page, "home.php?mod=spacecp&ac=vupload&aa=list");
	
	 
}

 
//视频分类
	$uid=$space['uid'];
$listvideotype = array();
$query1 = DB::query("SELECT * FROM ".DB::table('home_videotype')." WHERE uid='$uid'");
	while ($value = DB::fetch($query1)){
		$listvideotype[] = $value;
}

 
$starttime = $_SGLOBAL['timestamp'];


$usergroup = $_G['groupid'];
$templates = 'home/spacecp_'.$usergroup.'_vupload';
include_once template($templates);


//删除视频
function deletevideos($videoids) {
	global $_SGLOBAL, $_SC;
	$delvideos = $videotypenums = $newids =  $auids = $spaces = array();
	$delnum = 0;
	$videos = array();
	$query = DB::query("SELECT * FROM ".DB::table('home_video')." v LEFT JOIN ".DB::table('home_videopath')." vp ON v.vpid = vp.vpid WHERE v.vid IN (".simplode($videoids).")");
	while ($value = $_SGLOBAL['db']->fetch_array($query)) {		
		if($value['uid'] == $_SGLOBAL['supe_uid'] || $_SGLOBAL['supe_uid'] == 1) {
			//删除文件
			$videos[] = $value;
			$newids['vid'][] = $value['vid'];
			$newids['vpid'][] = $value['vpid'];
			$delvideos[] = $value;
			if($value['videoid']) {
				$auids[$value['videoid']] = $value['uid'];
				$videotypenums[$value['videoid']]++;
			}
			if($value['uid'] != $_SGLOBAL['supe_uid']) {
				$delnum++;
				$spaces[$value['uid']]++;
			}
			
		}
	}
	if(empty($delvideos) || $delnum < 1) return array();
	if($newids) {
		DB::query("DELETE FROM ".DB::table('home_video')." WHERE vid IN (".simplode($newids['vid']).")");
		DB::query("DELETE FROM ".DB::table('home_videopath')." WHERE vpid IN (".simplode($newids['vpid']).")");		
		DB::query("DELETE FROM ".DB::table('home_comment')." WHERE id IN (".simplode($newids['vid']).") AND idtype='vid'");
	//	DB::query("DELETE FROM ".DB::table('home_feed')." WHERE id IN (".simplode($newids).") AND idtype='vid'");

		//删除举报
		//DB::query("DELETE FROM ".DB::table('home_report')." WHERE id IN (".simplode($newids['vid']).") AND idtype='vid'");
			
		//删除脚印
		//DB::query("DELETE FROM ".DB::table('home_clickuser')." WHERE id IN (".simplode($newids['vid']).") AND idtype='vid'");
	}

	//删除视频文件
    deletevideofiles($videos);
	return $delvideos;
}

//删除视频文件
function deletevideofiles($videos) {
	global $_SGLOBAL, $_SC;
	foreach ($videos as $video) {
			$filepath = $video['filepath'];
			$fileimages = $video['images'];
			$pathbak = $video['pathbak'];
			$webpath = $video['webpath'];
			$wappath = $video['wappath'];
			if($filepath){
			if(!@unlink($filepath)) {
				runlog('VIDEO', "Delete pic file '$filepath' error.", 0);
			}}
			if($fileimages){
			if(!@unlink($fileimages)) {
				runlog('VIDEO', "Delete pic file '$fileimages' error.", 0);
			}}
			if($pathbak){
			if(!@unlink($pathbak)) {
				runlog('VIDEO', "Delete pic file '$fileimages' error.", 0);
			}}
			if($webpath){
			if(!@unlink($webpath)) {
				runlog('VIDEO', "Delete pic file '$webpath' error.", 0);
			}}
			if($wappath){
			if(!@unlink($wappath)) {
				runlog('VIDEO', "Delete pic file '$wappath' error.", 0);
			}}
	}
}

function simplode($ids) {  
    return "'".implode("','", $ids)."'";  
 } 

?>