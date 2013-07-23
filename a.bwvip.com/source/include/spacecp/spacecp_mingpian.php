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

//$query = DB::query("SELECT realname,mobile,address,company,qq FROM ".DB::table('common_member_profile')." WHERE uid = '$uid'");
$query =DB::query("SELECT a.realname,a.company,a.position,a.mobile,a.qq,a.address,b.email FROM ".DB::table('common_member_profile')." a inner join ".DB::table('common_member')." b ON a.uid = b.uid WHERE a.uid='$uid'");
while ($value = DB::fetch($query)) {
			$mingpian[] = $value;
		}

if ($_GET['caozuo']=='update') {
	//DB::update('common_member_profile', array('company' => $_POST['company'],'mobile' => $_POST['mobile'],'position' => $_POST['position'],'qq' => $_POST['qq'],'address' => $_POST['address']) where uid='$uid');
	$company=$_POST['company'];
	$mobile=$_POST['mobile'];
	$position=$_POST['position'];
	$qq=$_POST['qq'];
	$address=$_POST['address'];
	DB::query("UPDATE ".DB::table('common_member_profile')." SET company='$company', mobile='$mobile', position='$position', qq='$qq', address='$address' WHERE uid='$uid'");
	//showmessage('编辑名片成功', dreferer("home.php?mod=spacecp&ac=mingpian"));
echo "<Script language='JavaScript'> alert('编辑名片成功');</Script>";
echo "<meta http-equiv=refresh content=0;URL='home.php?mod=spacecp&ac=mingpian'>";
exit;
}


if($_G['uid']>0){
$getstat = array();
$getstat=getusrarry($_G['uid']);
} 

$templates='home/spacecp_mingpian';

include_once(template($templates)); 


?>