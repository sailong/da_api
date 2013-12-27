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
$operation = in_array($_GET['op'], array('chazhao', 'shenhe','yaoqing','myqy')) ? trim($_GET['op']) : 'chazhao';
//头部菜单的切换
if(in_array($operation, array('chazhao', 'shenhe','yaoqing','myqy'))) {
	$opactives = array($operation =>'class=a');
}

    $compclass = array();
	$query = DB::query("SELECT id,groupid,sortname FROM ".DB::table('Team_sort')." order by id");
	while ($sort = DB::fetch($query)) {
		$compclass[] = $sort;
	}

//查找企业
if($_GET['sea']=="chaxun"){
$groupid=$_GET['groupid'];
	$chaxunqy = array();
	if(!$groupid){
	$query= DB::query("select a.username,a.uid,b.field1,b.bio,b.uid from ".DB::table('common_member')." a LEFT JOIN  ".DB::table('common_member_profile')." b ON a.uid=b.uid where a.groupid > 19 order by a.uid");
	}
	else {
	$query= DB::query("select a.username,a.uid,b.field1,b.bio,b.uid from ".DB::table('common_member')." a LEFT JOIN  ".DB::table('common_member_profile')." b ON a.uid=b.uid where a.groupid =".$groupid." order by a.uid");
	}
	while($cx = DB::fetch($query)) {
		$chaxunqy[] = $cx;
	}
	foreach($chaxunqy as $key=>$value) {
		$gcq = DB::fetch_first("SELECT * FROM ".DB::table('guanxi')." WHERE compid=".$chaxunqy[$key]['uid']." and userid=".$space['uid']);
   		if($gcq['iscomp']==0 or $gcq['iscomp']==1) {
			$chaxunqy[$key]['iscomp'] = $gcq['iscomp'];
			$chaxunqy[$key]['isuser'] = $gcq['isuser'];
		}
	}
}



//按名称查询
if($_GET['user']=="key"){
$userr=$_GET['user'];
$username=trim($_GET['username']);
$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE username='$username'");
	if ($user[uid]){
		$gxiscomp = DB::fetch_first("SELECT * FROM ".DB::table("guanxi")." WHERE userid=".$space[uid]." and compid=".$user[uid]);
	}
}



//个人对企业申请
if($_GET['qiyeid']){
	$qiyeid=trim($_GET['qiyeid']);
    if(empty($_GET['qiyeid'])){
        showmessage('参数错误', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
    }

	$user = DB::fetch_first("SELECT * FROM ".DB::table("guanxi")." WHERE userid=".$space['uid']." and compid=".$qiyeid);
	//var_dump($user);

    if (!$user[userid]){
	   DB::insert('guanxi', array('userid' => $space['uid'],'compid' => $qiyeid,'iscomp'=>0));
	   showmessage('申请成功等待审核');
	  // showmessage('申请成功', "home.php?mod=spacecp&ac=qiye&op=shenhe");
	}
    else
	{
	   showmessage('已经申请成功,请耐心等待');
	   //showmessage('已经申请过了!', "home.php?mod=spacecp&ac=qiye&op=shenhe");
	}
}

//个人对企业申请后的 企业申请状态
if ($operation=='shenhe') {
$shenqsh=array();
$query = DB::query("SELECT a.compid,a.iscomp,b.field1 FROM ".DB::table("guanxi")." a left join ".DB::table("common_member_profile")." b on a.compid=b.uid WHERE a.iscomp=0 and a.userid=".$space['uid']);
	while ($sqsh = DB::fetch($query)){
	$shenqsh[]=$sqsh;
	}
}


//审核企业邀请
if ($operation=='yaoqing') {
$shenqing=array();
$query = DB::query("SELECT a.compid,a.iscomp,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.compid=b.uid WHERE a.isuser=0 and a.userid=".$space['uid']);
	while ($sq = DB::fetch($query)){
	$shenqing[]=$sq;
	}
}

if ($_GET['isuser']) {
DB::update('guanxi', array('isuser' => $_GET['isuser']),array('compid'=>$_GET['compid'],'userid'=>$space['uid']));
showmessage('状态改变成功', 'home.php?mod=spacecp&ac=qiye&op=yaoqing');
}


//我的企业
if ($operation=='myqy') {
$myhy=array();
$query = DB::query("SELECT cmp.realname, a.userid,a.iscomp,a.compid,a.isuser,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.compid=b.uid LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=b.uid  WHERE (a.iscomp=1 or a.isuser=1) and a.userid=".$space['uid']);
	while ($hy = DB::fetch($query)){
	$myhy[]=$hy;
	}
}
//删除对应关系
if ($_GET['cz'] == 'del') {
DB::query("DELETE FROM ".DB::table('guanxi')." WHERE userid=".$space['uid']." AND compid=".$_GET['compid']);
showmessage('删除关系成功', 'home.php?mod=spacecp&ac=qiye');
}

if($_G['uid']>0){
$getstat = array();
$getstat=getusrarry($_G['uid']);
}

$templates='home/spacecp_qiye';

include_once(template($templates));


?>