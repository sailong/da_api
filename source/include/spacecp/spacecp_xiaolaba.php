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
$op=in_array($_GET["op"],array('index','list','del'))?$_GET["op"]:'index';
$url="/home.php?mod=spacecp&ac=xiaolaba";
if($op=='index'){

	$limitpage=10;   //每页显示多少个
	$limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
	$start = ($page-1)*$limitpage;   //开始的条数
	ckstart($start, $limitpage);


	$sql="SELECT a.id,a.msgid,a.isread,b.`title`,b.`content`,b.`dataline`,a.`sendname` AS realname FROM `pre_buglet_gx` AS a,pre_buglet_msg AS b WHERE a.`msgid`=b.`buglet_id` AND touid=".$uid." ORDER BY b.`dataline` DESC limit ".$start.",".$limitpage;
	$re=DB::query($sql);
	$endrow=array();
	while($row=DB::fetch($re)){
		$endrow[]=$row;
	}
	//var_dump($endrow);

	if(!empty($endrow)){
		$theurl = $url; //地址

		//判断总条数
		$countnum = DB::result(DB::query("SELECT COUNT(0) num FROM `pre_buglet_gx` WHERE touid=".$uid));

		$allpage=ceil($countnum/$limitpage);

		if($page>$allpage){
			header("Location:".$url);
			exit;
		}

		$disppage = multi($countnum, $limitpage, $page, $theurl);

	}
}elseif ($op=='list'){
	$bid=getgpc("bid");   //信息的id
	if(empty($bid)){
		showmessage("参数错误",$url);
	}
	$bid=intval($bid);
	// 把状态重置成已读1
	$sta="SELECT isread FROM `pre_buglet_gx` WHERE touid=".$uid." AND msgid=".$bid;
	$rek=DB::fetch_first($sta);
	//var_dump($rek);
	if($rek["isread"]!='1'){
		DB::update("buglet_gx",array("isread"=>1),array("msgid"=>$bid,"touid"=>$uid));
	}

	$sql="SELECT a.msgid,a.sendname,b.`title`,b.`content`,b.`dataline` FROM `pre_buglet_gx` AS a,pre_buglet_msg AS b WHERE a.`msgid`=b.`buglet_id` AND a.touid=".$uid." AND a.`msgid`=".$bid;
	//echo $sql;
	$rearr=DB::fetch_first($sql);
	$rearr["date"]=date("Y-m-d H:i:s",$rearr["dataline"]);

}elseif ($op=='del'){
	//var_dump($_POST);
	if($_POST["sub"]=='删除'){
		$bid=$_POST["del"];   //数组
		if(!empty($bid)){
			foreach($bid as $b){
				$flag=DB::delete("buglet_gx",array("touid"=>$uid,"msgid"=>$b));
			}
			if($flag){
				showmessage("删除成功",$url);
			}else{
				showmessage("删除失败",$url);
			}
		}else{
			showmessage("请先选择要删除的信息",$url);
		}
		
		
	}else{
		header("Location:".$url);
	}
		
}

$navtitle="我的广播";
$templates = 'home/spacecp_xiaolaba_list';
include_once(template($templates));

?>