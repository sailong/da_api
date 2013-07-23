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
$uidarr=array('1899466','1899475');
//固定的id才能访问，高尔夫诚信俱乐部这个用户
if(!in_array($uid,$uidarr)){
	header("Location:/home.php?mod=spacecp");
	exit;
}

$op=in_array($_GET["op"],array('index','submit','del','add','list'))?$_GET["op"]:'index';
$url="/home.php?mod=spacecp&ac=weiyuan";
if($uid=='1899475'){
	$array=array("1"=>"诚信委员会","2"=>"观察员");
}elseif($uid=='1899466'){
	$array=array("3"=>"规则委员会");
}
$ak=array_keys($array);
$tishi=in_array($_GET["typelist"],$ak)?$array[$_GET["typelist"]]:'委员会';
//var_dump($tishi);
if($op=="index"){
	$sql="SELECT b.realname,c.recomid FROM pre_common_member AS a,pre_common_member_profile AS b,`pre_dazheng_weiyuan` AS c WHERE a.uid=b.uid AND a.uid=c.recomid ORDER BY c.dateline DESC";
	
}elseif($op=="submit"){
	if($_POST["sub"]=='查询'){
		$uname=trim($_POST["uname"]);
		$sid=intval(trim($_POST["sid"]));
		if(!empty($uname)){
			$where="WHERE b.realname='".$uname."'";
		}
		if(!empty($sid)){
			$where ="WHERE a.uid=".$sid;
		}
		if(!empty($uname) && !empty($sid)){
			$where ="WHERE a.uid=".$sid;
		}
		if(empty($uname) && empty($sid)){
			//没有输入条件
			showmessage("请输入查询条件",$url);
		}
		
		$sql="SELECT a.uid,a.username,b.realname FROM pre_common_member AS a LEFT JOIN pre_common_member_profile AS b ON a.uid=b.uid ".$where;
		$re=DB::query($sql);
		while($row=DB::fetch($re)){
			$endarr[]=$row;
		}
		//echo "<pre>";
		//print_r($endarr);
	}else{
		header("Location:".$url);
		exit;
	}

}elseif($op=="del"){
//删除
	$del=$_POST["del"];
	$count=count($del);
	if($count >= 1){
		foreach($del as $dv){
			$flag=DB::delete("dazheng_weiyuan",array("id"=>$dv));
		}
		if($flag){
			showmessage("删除成功",$url);
		}else{
			showmessage("删除失败",$url);
	   }
	}else{
		header("Location:".$url);
		exit;
	}
}elseif($op=="add"){
	if($_POST["sub"]=='添加'){
		$uname=$_POST["ch"];
		$typelist=$_POST["typelist"];
		$count=count($uname);
		if($count>=1){
			foreach($uname as $k){
			$nowtime=time();
				//先查询一下这个用户是否存在了
				$issql="SELECT id FROM `pre_dazheng_weiyuan` WHERE recomid=".$k." AND uid=".$uid." and flag=".$typelist;
				$re=DB::fetch_first($issql);
				if(!$re["id"]){
					$flag=DB::insert("dazheng_weiyuan",array("recomid"=>$k,"uid"=>$uid,"dateline"=>$nowtime,"flag"=>$typelist));
					if($flag){
					showmessage("添加成功",$url);
					}else{
						showmessage("添加失败",$url);
					}
				}else{
					showmessage("已经添加过了",$url);
				}	
			}
		}else{
			showmessage("先选择添加的用户",$url);
		}
		//print_r($uname);
	}else{
		header("Location:".$url);
		exit;
	}
}elseif($op=="list"){
		$typelist=$_GET["typelist"];
		if(empty($typelist)){
			showmessage("请先选择类别",$url);
		}
		
		$limitpage=15;   //每页显示多少个

    $limitpage = mob_perpage($limitpage);
    $page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
    $page=trim(intval($page));
    $start = ($page-1)*$limitpage;   //开始的条数
    ckstart($start, $limitpage);
		
		
		$sql="SELECT a.id,a.recomid as uid,b.`realname` FROM `pre_dazheng_weiyuan` AS a LEFT JOIN pre_common_member_profile AS b  ON a.recomid=b.`uid` WHERE a.flag=".$typelist." AND a.uid=".$uid." limit ".$start.",".$limitpage;
		$re=DB::query($sql);
		while($row=DB::fetch($re)){
			$typerow[]=$row;
		}
		
		
	 $theurl = 'home.php?mod=spacecp&ac=weiyuan&op=list&typelist='.$typelist; //地址

       //判断总条数
    $countnum = DB::result(DB::query("SELECT count(0) num FROM `pre_dazheng_weiyuan` AS a LEFT JOIN pre_common_member_profile AS b  ON a.recomid=b.`uid` WHERE a.flag=".$typelist." AND a.uid=".$uid));

    $disppage = multi($countnum, $limitpage, $page, $theurl);
		
}
$navtitle="委员会";

$templates = 'home/spacecp_weiyuan';
include_once(template($templates));

?>