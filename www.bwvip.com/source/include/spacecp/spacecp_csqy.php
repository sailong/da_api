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

$gid=$_G['groupid'];


//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','search','del','edit','searchsub','slist')) ? $_GET['op'] : 'list';
$space=getspace($_G['uid']);

if($op=='list'){
    $user_type  = isset($_G['gp_user_type']) ? $_G['gp_user_type'] : 0;
	//查看已经添加的球星
	$listqiuxing="select a.userid,a.seq,b.username,c.realname from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid left join pre_common_member_profile c on a.userid=c.uid where a.groupid=".$_G['uid']." and a.user_type='".$user_type."' order by a.seq asc";
	
	$listre=DB::query($listqiuxing);
	while ($row=DB::fetch($listre)){
		$earr[]=$row;
	}
}elseif ($op=="search"){
	$limitpage=10;   //每页显示多少个
	$page=empty($_GET["page"])?1:$_GET["page"];
    $limitpage = mob_perpage($limitpage);
    $start = ($page-1)*$limitpage;
    ckstart($start, $limitpage);
		$sql="SELECT a.uid,a.username,b.realname FROM ".DB::table("ucenter_members")." a left join ".DB::table("common_member_profile")." b on a.uid=b.uid LEFT JOIN ".DB::table("common_member")." c ON a.uid=c.uid where isstar=1 AND c.groupid=24 limit ".$start.",".$limitpage;   //显示所有用户 ，必须是球星的用户
		//echo $sql;
		$re=DB::query($sql);
		while($value = DB::fetch($re)) {
			$earr[]=$value;
		}
		//判断总条数
		$countnum=DB::result(DB::query("SELECT count(0) num FROM ".DB::table("ucenter_members")." where isstar=1"));

        $theurl = 'home.php?mod=spacecp&ac=csqy&op=search';

		$disppage=multi($countnum, $limitpage, $page, $theurl);    //显示分页串
		//echo $str;
	//}
}elseif($op=='del'){
	//var_dump($_POST);
	$delid=$_POST["delid"];
	if(empty($delid) || count($delid)<=0){
		showmessage("没有选择用户","/home.php?mod=spacecp&ac=csqy&op=list");
	}else{
		foreach ($delid as $did){
			if(empty($did)){
				continue;
			}
			//$sqlid="delete from ".DB::table("home_saishi_csqy")." where userid=".$did;
			//echo $sqlid."<br />";
			DB::delete("home_saishi_csqy",array("userid"=>$did,"groupid"=>$_G["uid"]));
		}
		showmessage("成功删除","/home.php?mod=spacecp&ac=csqy&op=list");

	}
}elseif ($op=='edit'){
	$editid=$_GET["id"];     //要编辑的明星id
	if(empty($editid)){
		showmessage("参数错误","/home.php?mod=spacecp&ac=csqy&op=list");
	}else{
		$editsql=DB::query("select a.userid,a.groupid,b.username,a.seq from pre_home_saishi_csqy a left join pre_ucenter_members b on a.userid=b.uid where a.groupid=".$_G['uid']." and userid=".$editid);
		$editarr=DB::fetch($editsql);
		//var_dump($editarr);
		$myseq=trim($_POST["myseq"]);  //接受的排序
		$groupid=trim($_POST["groupid"]);   //组id
		$userid=trim($_POST["userid"]);    //用户id
		if($_POST){
			//var_dump($_POST);
			$nowtime=time();
			if(!empty($myseq) && !empty($groupid) && !empty($userid)){
				//var_dump($_POST);
				DB::update("home_saishi_csqy",array("seq"=>$myseq,"inserttime"=>$nowtime),array("userid"=>$userid,"groupid"=>$groupid));
				showmessage("修改成功","/home.php?mod=spacecp&ac=csqy&op=list");
			}else{
				showmessage("修改失败","/home.php?mod=spacecp&ac=csqy&op=list");
			}
		}


	}
}elseif($op=='searchsub'){
    $user_type = getgpc("user_type");
    if($user_type=="") showmessage("请 选择人物物职业");

    //判断用户是否提交添加球星
	$addqx=$_POST["userid"];
    //var_dump($addqx);
	if(!empty($addqx)){
			foreach ($addqx as $value){
				$nowtime=time();
				$sql="insert into pre_home_saishi_csqy (groupid,userid,user_type) values({$uid},{$value},{$user_type})";

				//先判断一下数据库中有没有这个组的这条数据
				$searchsql="select userid from ".DB::table("home_saishi_csqy")." where userid=".$value." and groupid=".$_G['uid']." and user_type = '".$user_type."'";
				$searchre=DB::query($searchsql);
				while ($searchrow=DB::fetch($searchre)){
					$sre[]=$searchrow;
				}

				if(count($sre)>=1){
					unset($sre);
					continue;
				}else{
					DB::insert("home_saishi_csqy",array("groupid"=>"{$_G['uid']}","userid"=>"{$value}","inserttime"=>"{$nowtime}","user_type"=>$user_type));

				}
			}
				showmessage("添加球星成功","/home.php?mod=spacecp&ac=csqy&op=list&user_type=".$user_type);
	}else{
	   showmessage("你没有选择用户","/home.php?mod=spacecp&ac=csqy&op=search");
	}
}elseif($op=='slist'){
    $searchname=trim($_POST["searchname"]);
	$sql="SELECT a.uid,a.username,b.realname FROM pre_common_member a LEFT JOIN pre_common_member_profile b ON a.uid=b.uid WHERE a.username like '%{$searchname}%' OR b.realname like '%{$searchname}%'";     //a.groupid=24
        $re=DB::query($sql);
		while($value = DB::fetch($re)) {
			$earr[]=$value;
		}
}

$navtitle="参赛球星-大正高尔夫";

$templates='home/spacecp_csqy';
include_once(template($templates));


?>