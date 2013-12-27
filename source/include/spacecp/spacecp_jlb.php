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

$op=in_array($_GET["op"],array('list','addsub','aj'))?$_GET["op"]:'list';
if($op=='list'){
    //查询
    $list=DB::fetch_first("SELECT uid,jlbname,qdname FROM ".DB::table("home_self_jlb")." WHERE uid=".$_G["uid"]);
    //var_dump($is);
    
}else if($op=='addsub'){
    //var_dump($_POST);
    if($_POST["sub"]=='提交'){
        $jlb=trim($_POST["myjlb"]);
        $qd=trim($_POST["myqd"]);
        $time=time();
        //查是否存在
        $is=DB::fetch_first("SELECT uid FROM ".DB::table("home_self_jlb")." WHERE uid=".$_G["uid"]);
        if(!empty($is["uid"])){
            $flag=DB::update("home_self_jlb",array("jlbname"=>$jlb,"qdname"=>$qd,"addtime"=>$time),array("uid"=>$_G["uid"]));
            $msg="更新成功";
        }else{
            $flag=DB::insert("home_self_jlb",array("uid"=>$_G["uid"],"jlbname"=>$jlb,"qdname"=>$qd,"addtime"=>$time));
            $msg="添加成功";
        }
        showmessage($msg,"home.php?mod=spacecp&ac=jlb");
    }else{
        header("Location:/home.php?mod=spacecp&ac=jlb");
    }
    
}

//$navtitle="添加常用球场";


$templates='home/spacecp_jlb';
include_once(template($templates)); 
?>