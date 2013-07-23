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
$operation = in_array($_GET['op'], array('jiaohuan', 'cunchu')) ? trim($_GET['op']) : 'jiaohuan';
//头部菜单的切换
if(in_array($operation, array('jiaohuan', 'cunchu'))) {
	$opactives = array($operation =>'class=a');
}

if ($operation=='jiaohuan') {
$query =DB::query("SELECT a.uid,a.realname,a.company,a.position,a.mobile,a.qq,a.address,b.email FROM ".DB::table('common_member_profile')." a join ".DB::table('common_member')." b ON a.uid = b.uid join ".DB::table('common_mingpian')." c on c.cid=a.uid WHERE c.uid='$uid' and c.isok=0");
while ($value = DB::fetch($query)) {
			$mingpian[] = $value;
		}
}



if ($operation=='cunchu') {
$query =DB::query("SELECT a.uid,a.realname,a.company,a.position,a.mobile,a.qq,a.address,b.email FROM ".DB::table('common_member_profile')." a join ".DB::table('common_member')." b ON a.uid = b.uid join ".DB::table('common_mingpian')." c on c.cid=a.uid WHERE c.uid='$uid' and c.isok=1");
while ($valueall = DB::fetch($query)) {
			$mingpianall[] = $valueall;
		}
}


//删除对应关系
if ($_GET['cz'] == 'del') {
DB::query("DELETE FROM ".DB::table('common_mingpian')." WHERE uid=".$space['uid']." AND cid=".$_GET['cid']);

showmessage('删除关系成功', 'home.php?mod=spacecp&ac=mingpianall');
}

//仅存储
if ($_GET['cz'] == 'cunchu') {
DB::update('common_mingpian', array('isok' => 1),array('uid'=>$uid,'cid'=>$_GET['cid']));
showmessage('存储名片成功', 'home.php?mod=spacecp&ac=mingpianall');
}

//存储与交换
if ($_GET['cz'] == 'cunhuan') {
DB::update('common_mingpian', array('isok' => 1),array('uid'=>$uid,'cid'=>$_GET['cid']));
$count = DB::result_first("select count(*) FROM ".DB::table('common_mingpian')." WHERE uid='".$_GET['cid']."' and cid='$uid'");
if($count) {
showmessage("名片递过了或者已经在对方名片库里","home.php?mod=spacecp&ac=mingpianall");	
}else{
DB::insert('common_mingpian', array('uid' => $_GET['cid'],'cid' => $uid,'isok' => 0));
showmessage('存储交换名片成功', 'home.php?mod=spacecp&ac=mingpianall');
}
}

if($_G['uid']>0){
$getstat = array();
$getstat=getusrarry($_G['uid']);
} 

$templates='home/spacecp_mingpianall';

include_once(template($templates)); 


?>