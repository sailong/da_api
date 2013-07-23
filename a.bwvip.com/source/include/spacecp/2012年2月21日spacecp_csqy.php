<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_index.php 22814 2011-05-24 05:42:54Z zhangguosheng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=empty($_G['groupid'])?25:$_G['groupid'];   //组id  现在的是25

//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','search','del')) ? $_GET['op'] : 'list';    
$space=getspace($_G['uid']);
$ip="http://121.101.216.67/";

//require_once libfile('function/space');
//require_once libfile('function/portalcp');
//var_dump($_POST);
//var_dump($op);
if($op=='list'){
	//查看已经添加的球星
	//$listqiuxing="select userid from ".DB::table("home_qiuxing")." where groupid=3";
	$listqiuxing="select a.userid,b.username from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid where a.groupid=".$gid;
	
	$listre=DB::query($listqiuxing);
	while ($row=DB::fetch($listre)){
		$earr[]=$row;
	}
	
	//print_r($earr);
	
}elseif ($op=="search"){
	$limitpage=10;   //每页显示多少个
	$page=empty($_GET["p"])?1:$_GET["p"];
	$page=trim(intval($page));
	$start=($page-1)*$limitpage;
	//var_dump($_POST);
	$searchname=$_POST["searchname"];
	//var_dump($_POST["searchname"]);
	if(!empty($searchname)){
		$sql="SELECT uid,username FROM ".DB::table("ucenter_members")." where username='{$searchname}' and isstar=1";   //显示单个用户,必须是球星的用户
	}else{
		$sql="SELECT uid,username FROM ".DB::table("ucenter_members")." where isstar=1 limit ".$start.",".$limitpage;   //显示所有用户 ，必须是球星的用户
		//echo $sql;
	}
	$re=DB::query($sql);
	while($value = DB::fetch($re)) {
		$earr[]=$value;
	}
	//判断总条数
	$countre=DB::query("SELECT count(0) num FROM ".DB::table("ucenter_members")." where isstar=1");
	$countarr=DB::fetch($countre);
	//var_dump($countarr);
	$countnum=$countarr["num"];   //总条数
	//分页
	$pager=ceil($countnum/$limitpage);   //总共分几页
	//echo $pager;
	$str='';
	$k=1;
	//上一页
	if($page==1){
		$uppage='';
	}else{
		$uppage='&nbsp;&nbsp;<a href="home.php?mod=spacecp&ac=csqy&op=search&p='.($page-1).'">上一页</a>&nbsp;&nbsp;';
	}
	//下一页
	if($page>=$pager){
    	$nextpage='';
	}else{
		$nextpage='&nbsp;&nbsp;<a href="home.php?mod=spacecp&ac=csqy&op=search&p='.($page+1).'">下一页</a>&nbsp;&nbsp;';
	}
	//首页
    $indexpage='<a href="home.php?mod=spacecp&ac=csqy&op=search">首页</a>';
	
	//尾页
	$lastpage='<a href="home.php?mod=spacecp&ac=csqy&op=search&p='.$pager.'">尾页</a>';
	$disppage=$indexpage.$uppage.$nextpage.$lastpage;    //显示分页串
	//echo $str;
	
	
	
	//判断用户是否提交添加球星
	$addqx=$_POST["userid"];
	if(!empty($addqx)){
		
	//var_dump($addqx);
		//判断有没有选中用户
		if(count($addqx)<=0){
			showmessage("你没有选择用户",$ip."home.php?mod=spacecp&ac=csqy&op=search");
		}else{
			//echo "ddd";
			//添加到数据库
			//这里要判断有没有存在数据库中
			foreach ($addqx as $value){
				$nowtime=time();
				$sql="insert into pre_home_saishi_csqy (groupid,userid) values({$gid},{$value})";
				//echo $sql."<br />";
				//先判断一下数据库中有没有这条数据
				$searchsql="select userid from ".DB::table("home_saishi_csqy")." where userid=".$value;
				$searchre=DB::query($searchsql);
				//var_dump($searchre);
				while ($searchrow=DB::fetch($searchre)){
					$sre[]=$searchrow;
				}
				//var_dump($sre);
				if(count($sre)>=1){
					//print_r($sre);
					//如果有这个用户，则进行下一个循环
					//echo "id{$sre[0]["userid"]}存在了<br />";
					unset($sre);
					continue;
				}else{
					DB::insert("home_saishi_csqy",array("groupid"=>"{$gid}","userid"=>"{$value}","inserttime"=>"{$nowtime}"));
					//echo "插入<br />";
				}
			}
				showmessage("添加球星成功",$ip."home.php?mod=spacecp&ac=csqy&op=list");
		} 	
	}
}elseif($op=='del'){
	//var_dump($_POST);
	$delid=$_POST["delid"];
	if(empty($delid) || count($delid)<=0){
		showmessage("没有选择用户",$ip."home.php?mod=spacecp&ac=csqy&op=list");
	}else{
		foreach ($delid as $did){
			if(empty($did)){
				continue;
			}
			//$sqlid="delete from ".DB::table("home_saishi_csqy")." where userid=".$did;
			//echo $sqlid."<br />";
			DB::delete("home_saishi_csqy",array("userid"=>$did));
		}
		showmessage("成功删除",$ip."home.php?mod=spacecp&ac=csqy&op=list");
		
	}
}



include_once(template('home/spacecp_csqy'));
?>