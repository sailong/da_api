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
$gid=$_G['groupid'];   //组id  现在的是25
if($gid!='25'){
    header("Location:/");
    exit();
}

//var_dump($gid);
//var_dump($_G['uid']);
//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('list','search','del','add','edit','area')) ? $_GET['op'] : 'list';    
//var_dump($op);
if($op=='list'){
	$sqllist=DB::query("select a.cdid,a.seq,b.fieldimg,c.field1 from pre_home_saishi_jbqc a,pre_common_field b,pre_common_member_profile c where a.cdid=b.uid and a.cdid=c.uid and a.groupid=".$_G["uid"]." order by a.seq asc");
	while($row=DB::fetch($sqllist)){
		//数据库中有的结尾少了一个g所以要判断一下
		if(substr($row["fieldimg"],-3)=='.jp'){
			$row["fieldimg"]=str_replace(".jp",".jpg",$row["fieldimg"]);
		}
		$endrow[]=$row;
	}
	//var_dump($endrow);
	
}elseif($op=='search'){
	$limitpage=10;   //每页显示多少个
    $limitpage = mob_perpage($limitpage);
	$page=empty($_GET["page"])?1:$_GET["page"];
	$page=trim(intval($page));
    $start = ($page-1)*$limitpage;
    ckstart($start, $limitpage);
    
	//echo "select a.fieldimg,b.uid,b.field1 from pre_common_field a left join pre_common_member_profile b on a.uid=b.uid limit ".$start.",".$limitpage;
	$re=DB::query("select a.fieldimg,b.uid,b.field1 from pre_common_field a left join pre_common_member_profile b on a.uid=b.uid limit ".$start.",".$limitpage);   //显示所有用户 ，
	while($row=DB::fetch($re)){
		//数据库中有的结尾少了一个g所以要判断一下
		if(substr($row["fieldimg"],-3)=='.jp'){
			$row["fieldimg"]=str_replace(".jp",".jpg",$row["fieldimg"]);
		}
		$searchrow[]=$row;
	}
	//var_dump($searchrow);
    
	
    $theurl = 'home.php?mod=spacecp&ac=jbqc&op=search';

	//判断总条数
    $countnum = DB::result(DB::query("select count(0) num from pre_common_field"));
    //echo $countnum;
    
	//$disppage;   //显示分页串
	//echo $str;
    $disppage = multi($countnum, $limitpage, $page, $theurl);
    
    
	
}elseif($op=='del'){
	$delid=$_POST["delid"];
	 //var_dump($_POST);
	 if(empty($delid)){
	 	showmessage("你没有选择要删除的场地","http://121.101.216.67/home.php?mod=spacecp&ac=jbqc&op=search");
	 }else{
	 	DB::delete("home_saishi_jbqc",array("cdid"=>$delid));    //每一个用户只能传一个球场
	 	showmessage("删除场地成功","home.php?mod=spacecp&ac=jbqc&op=list");
	 }
	
	
	
}elseif($op=='add'){
	//echo "add";
    
    $addid=trim($_POST["fuid"]);    //接受的id
    //echo $addid."*****";
    $seqid=trim($_POST["myseq"]);    //排序的id
    //echo $seqid;
    
    if(empty($addid)){
    	showmessage("请先选择场地","home.php?mod=spacecp&ac=jbqc&op=search");
    }else{
    	if (empty($seqid)){
    		$seqnow=100;
    	}else{
    		$seqnow=$seqid;
    	}
    	$nowtime=time();
    	//先查一下数据库中有没有这条数据
    	$ishav=DB::query("select cdid from pre_home_saishi_jbqc where cdid=".$addid." and groupid=".$_G["uid"]);
    	while($haverow=DB::fetch($ishav)){
    		$ak[]=$haverow;
    	}
    	
    	if(count($ak)>=1){
	    	showmessage("该场地已经被添加，请勿重复添加","home.php?mod=spacecp&ac=jbqc&op=list");
    	}else{
    		DB::insert("home_saishi_jbqc",array("groupid"=>"{$_G['uid']}","cdid"=>"{$addid}","inserttime"=>"{$nowtime}","seq"=>$seqnow));
	    	showmessage("添加场地成功","home.php?mod=spacecp&ac=jbqc&op=list");
    	}
    	
    }

}elseif ($op=='edit'){
	$editid=$_GET["id"];     //要编辑的场地id
	if(empty($editid)){
		showmessage("参数错误","http://121.101.216.67/home.php?mod=spacecp&ac=jbqc&op=list");
	}else{
		$editsql=DB::query("select a.cdid,a.seq,b.fieldimg,c.field1,a.groupid from pre_home_saishi_jbqc a,pre_common_field b,pre_common_member_profile c where a.cdid=b.uid and a.cdid=c.uid and a.groupid=".$_G['uid']." and a.cdid=".$editid);
		$editarr=DB::fetch($editsql);
		if(substr($editarr["fieldimg"],-3)=='.jp'){
			$editarr["fieldimg"]=str_replace(".jp",".jpg",$editarr["fieldimg"]);
		}
		//var_dump($editarr);
		$idc=trim($_POST["changeid"]);  //接受的排序 
		$acgid=trim($_POST["groupid"]);   //组id
		$cdcid=trim($_POST["cdid"]);    //用户id
		if($_POST){
			if(!empty($idc) && !empty($acgid) && !empty($cdcid)){
				//var_dump($_POST);
				DB::update("home_saishi_jbqc",array("seq"=>$idc),array("cdid"=>$cdcid,"groupid"=>$acgid));
				showmessage("修改成功","home.php?mod=spacecp&ac=jbqc&op=list");
			}else{
				showmessage("修改失败","home.php?mod=spacecp&ac=jbqc&op=list");
			}
		}
		
		
	}
	
}elseif($op=='area'){
    //ajax请求
    $id = trim($_GET['val']);   //接收的城市id
	$query = DB::query('select id, uid, fieldname from '.DB::table('common_field')." where province='".$id."' order by id desc");
	$option = "<option value='0'>请选择</option>";
	while($row = mysql_fetch_assoc($query)) {
		$option .= "<option value='".$row['uid']."'>".$row['fieldname']."</option>";
	}
	echo $option; 
    exit;
}
$navtitle="举办场地设置";
//$templates='home/spacecp_'.$_G['groupid'].'_jbqc';
$templates='home/spacecp_jbqc';
include_once(template($templates)); 


//include_once(template('home/spacecp_jbqc'));
?>