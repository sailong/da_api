<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_laba.php 22139 2011-04-22 06:24:53Z monkey $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
  
$uid = $_G['uid'];

$uid = !empty($_GET['uid']) ? $_GET['uid'] : $_G['uid'];

if($uid > 0) {
	$getstat = array();
	$getstat = getusrarry($uid);
	//$gropid=$getstat['groupid']; 
	$gropid = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
	if($gropid < 20) {
	  showmessage('对不起，您不是企业用户，没有权限！','/home.php?mod=spacecp');  
	} 
}
 $realname = DB::result_first("SELECT nickname FROM jishigou_members WHERE ucuid=".$uid); 

if($_GET['op'] == 'send') { 
 $msgtype=$_GET['msgtype'];
if($_GET['msgtype']==0){ showmessage('请选择发送类别！','/home.php?mod=spacecp&ac=laba'); }
if(strlen( $_GET['title'])==0){ showmessage('标题不能为空！','/home.php?mod=spacecp&ac=laba'); }
if(strlen( $_GET['sendmessage'])==0){ showmessage('内容不能为空！','/home.php?mod=spacecp&ac=laba'); }

 DB::insert('buglet_msg', array('uid' => $uid,'title' => $_GET['title'],'content' =>$_GET['sendmessage'],'dataline'=> time()));
 $msgid = DB::insert_id();

$toarr=array();
if($msgtype==1)	 
{
 //pre_guanxi 取出会员 for
$query = DB::query("SELECT  userid as touid FROM ".DB::table("guanxi")."  WHERE  iscomp=1 and  compid=".$uid);
	while ($row = DB::fetch($query)){
			
	 DB::insert('buglet_gx', array('msgid' => $msgid,'touid' => $row['touid'],'senduid' =>$uid,'sendname'=>$realname));
	}

}

if($msgtype==2)	 
{  
//粉丝jishigou_buddys for

$query = DB::query("select  m.uid as touid from jishigou_buddys as b left join jishigou_members as m on b.uid=m.uid where b.buddyid='$uid' AND m.`uid` != '' AND m.uid !=1 ");
	while ($row = DB::fetch($query)){
		
		 DB::insert('buglet_gx', array('msgid' => $msgid,'touid' => $row['touid'],'senduid' =>$uid,'sendname'=>$realname));
	}
}


 //DB::insert('buglet_gx', $toarr);
 showmessage('发送成功！','/home.php?mod=spacecp&ac=laba'); 

}  
   
 
$templates = 'home/spacecp_laba';
include_once template($templates); 
?>