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
$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('chazhao', 'shenhe','shenqing','myhy')) ? trim($_GET['op']) : 'chazhao';
//头部菜单的切换
if(in_array($operation, array('chazhao', 'shenhe','shenqing','myhy'))) {
	$opactives = array($operation =>'class=a');
}



//按名称查询
if($_GET['user']=="key"){
$userr=$_GET['user'];
$userid=trim($_POST['userid']);
$username=trim($_POST['username']);
if ($userid){
	$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and uid=$userid");
}
if ($username) {
	$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and username='$username'");
}

	if ($user[uid]){
		$gxiscomp = DB::fetch_first("SELECT * FROM ".DB::table("guanxi")." WHERE userid=".$user[uid]." and compid=".$space[uid]);
	}
}

//企业对个人发出请求后的状态
if ($operation=='shenhe') {
$shenqsh=array();
$query = DB::query("SELECT a.userid,a.isuser,a.userid,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE a.isuser=0 and a.compid=".$space['uid']);
	while ($sqsh = DB::fetch($query)){
	$shenqsh[]=$sqsh;
	}
}


//个人对企业申请后 企业审核状态 0-提交申请  1-审核通过 
if ($operation=='shenqing') {
$shenqing=array();
$query = DB::query("SELECT a.userid,a.iscomp,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE a.iscomp=0 and a.compid=".$space['uid']);
	while ($sq = DB::fetch($query)){
	$shenqing[]=$sq;
	}
	
}
//通过审核
if ($_GET['iscomp']) {
DB::update('guanxi', array('iscomp' => $_GET['iscomp']),array('userid'=>$_GET['userid'],'compid'=>$space['uid']));
showmessage('状态改变成功', 'home.php?mod=spacecp&ac=huiyuan');
}

//删除对应关系
if ($_GET['cz'] == 'del') {
DB::query("DELETE FROM ".DB::table('guanxi')." WHERE compid=".$space['uid']." AND userid=".$_GET['userid']);
showmessage('删除关系成功', 'home.php?mod=spacecp&ac=huiyuan');
}


//我的会员
if ($operation=='myhy') {
$myhy=array();
$query = DB::query("SELECT a.userid,a.iscomp,a.isuser,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE (a.iscomp=1 or a.isuser=1) and a.compid=".$space['uid']);
	while ($hy = DB::fetch($query)){
	$myhy[]=$hy;
	}
}


//企业邀请会员
if($_GET['qiyeid']){
	$qiyeid=trim($_GET['qiyeid']);
	DB::insert('guanxi', array('userid' => $qiyeid,'compid' => $space['uid'],'isuser' => 0));
	showmessage('申请成功等待审核', 'home.php?mod=spacecp&ac=huiyuan');

	
}
 
if($_G['uid']>0){
$getstat = array();
$getstat=getusrarry($_G['uid']);
} 

$templates='home/spacecp_huiyuan';

include_once(template($templates)); 



?>