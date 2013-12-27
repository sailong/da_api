<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: space_blog.php 21922 2011-04-18 02:41:54Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
exit('Access Denied');
}

$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];


space_merge($space, 'count');
$op=$_GET["op"];
//$templates='home/'.$usergroup.'_saishiinfo';
if($op=='qclist'){
	$templates='home/'.$usergroup.'_qclist';
}elseif($op=='mingxing'){
    $user_type = isset($_G['gp_user_type']) ?  $_G['gp_user_type'] : 0;
	//参赛明星
	$limitpage=40;   //每页显示多少个
	$limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
	$start = ($page-1)*$limitpage;   //开始的条数
	ckstart($start, $limitpage);

	if($uid=='1000333'){
		$listqiuxing="SELECT uid AS userid,realname FROM pre_home_dazbm WHERE game_s_type={$uid} AND pay_status=1 ORDER BY `addtime` DESC limit ".$start.",".$limitpage;
		$countsql="SELECT count(0) num FROM pre_home_dazbm WHERE game_s_type={$uid} AND pay_status=1";
	}else{
		$listqiuxing="select a.userid,b.username,c.realname from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid left join pre_common_member_profile c on a.userid=c.uid where a.groupid=".$uid." and user_type ='".$user_type."' order by a.seq asc limit ".$start.",".$limitpage;
		$countsql="select count(0) num from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid left join pre_common_member_profile c on a.userid=c.uid where a.groupid=".$uid;
	}
	//echo $listqiuxing;
	$listall=DB::query($listqiuxing);
	while ($rowre=DB::fetch($listall)){
		if(!empty($rowre["realname"])){
			$rowre["cname"]=$rowre["realname"];
			$rowre["subname"]=utf8Substr($rowre["cname"],0,6);
		}
		$newlist[]=$rowre;
	}

	$theurl = 'home.php?mod=space&uid='.$uid."&do=saishiinfo&op=mingxing"; //地址

	//判断总条数
	$countnum = DB::result(DB::query($countsql));

	//判断 如果用户随便输入一个大数,有没有超出最高限度
	$allpage=ceil($countnum/$limitpage);

	$disppage = multi($countnum, $limitpage, $page, $theurl);

	$templates='home/'.$usergroup.'_saishiinfo_two';
}else{
	$templates='home/'.$usergroup.'_saishiinfo';
}
include_once(template($templates));


?>