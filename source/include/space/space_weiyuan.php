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
$type=in_array($_GET["type"],array('1','2','3'))?$_GET["type"]:'1';
if($type=='1'){
	$string="诚信委员会";
}elseif ($type=='2'){
	$string='观察员';
}elseif ($type=='3'){
	$string='规则委员会';
}

$uidarr=array('1899475','1899466');

if(in_array($uid,$uidarr)){
	$limitpage=60;   //每页显示多少个
	$limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
	$start = ($page-1)*$limitpage;   //开始的条数
	ckstart($start, $limitpage);
	$sql="SELECT a.recomid,b.realname FROM pre_dazheng_weiyuan AS a LEFT JOIN pre_common_member_profile AS b ON a.`recomid`=b.`uid` WHERE a.flag={$type} and a.uid={$uid} ORDER BY a.dateline DESC limit ".$start.",".$limitpage;
	$re=DB::query($sql);
	while($row=DB::fetch($re)){
		$akarr[]=$row;
	}
	
	$theurl = 'home.php?mod=space&uid='.$uid."&do=weiyuan&type=".$type; //地址

	//判断总条数
	$countnum = DB::result(DB::query("SELECT count(0) as num FROM pre_dazheng_weiyuan AS a LEFT JOIN pre_common_member_profile AS b ON a.`recomid`=b.`uid` WHERE a.flag={$type} and a.uid={$uid}"));

	//判断 如果用户随便输入一个大数,有没有超出最高限度
	//$allpage=ceil($countnum/$limitpage);
	//echo $allpage;
	//if(!empty($page) && $page>$allpage){
	  //  header("Location:/");
	   // exit;
	//}
	
	$disppage = multi($countnum, $limitpage, $page, $theurl);
	$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
	$usergroup = ($gropid < 20) ? 10 : $usergroup;
	include template("home/".$usergroup."_weiyuan");
}else{
	header("Location:/");
}





?>