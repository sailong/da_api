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

//var_dump($uid);
//参赛明星
$limitpage=40;   //每页显示多少个
$limitpage = mob_perpage($limitpage);
$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
$page=trim(intval($page));
$start = ($page-1)*$limitpage;   //开始的条数
ckstart($start, $limitpage);

$sqltwo="SELECT uid,realname FROM pre_home_dazbm WHERE  hot_district='".$array[$uid]."' AND pay_status=1 ORDER BY `addtime` DESC limit ".$start.",".$limitpage;
$csqy=DB::query($sqltwo);
while($rowb=DB::fetch($csqy)){
	$ylist[]=$rowb;
}
$theurl = 'home.php?mod=space&uid='.$uid."&do=mxlist"; //地址

//判断总条数
$countnum = DB::result(DB::query("SELECT COUNT(0) num FROM pre_home_dazbm WHERE hot_district='".$array[$uid]."' AND pay_status=1"));

//判断 如果用户随便输入一个大数,有没有超出最高限度
$allpage=ceil($countnum/$limitpage);
//echo $allpage;
//if(!empty($page) && $page>$allpage){
   // header("Location:/");
   // exit;
//}

$disppage = multi($countnum, $limitpage, $page, $theurl);


include template("home/space_mxlist");



?>