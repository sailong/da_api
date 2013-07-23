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

//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('edit','change')) ? $_GET['op'] : 'edit';    
$space=getspace($_G['uid']);
$ip="http://211.94.187.157/";


if($op=='list'){
    /*
    $query=DB::query("select id,name,start,end,address,inserttime from ".DB::table("groupinfo_25")." where groupid=".$_G['uid']);
    while($ty=DB::fetch($query)){
        $endrow[]=$ty;
    }
    //var_dump($endrow);
    */
}elseif($op=='edit'){
    //$id=$_GET["id"];
    //如果传入非数字
   /* if(!is_numeric($id)){
        showmessage("参数错误",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
        exit;
    }*/
   // if(!empty($id)){
        $sqlre=DB::query("select id,name,start,end,groupid,address from ".DB::table("groupinfo_25")." where groupid=".$_G['uid']);
        $editre=DB::fetch($sqlre);
        //var_dump($editre);
        /*if(empty($editre)){
            showmessage("没找到相关赛事",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
        }*/
    //}  
}elseif($op=='add'){
    //var_dump($_POST);
   /* if($_POST){
        $name=$_POST["name"];
        $nowtime=time();
        $address=$_POST["address"];
        $start=$_POST["start"];
        $end=$_POST["end"];
        $uid=$_G['uid'];
        DB::insert("groupinfo_25",array("name"=>$name,"address"=>$address,"start"=>$start,"groupid"=>$_G['uid'],"end"=>$end,"inserttime"=>$nowtime));
        showmessage("添加信息成功",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
    }
    */
    
}elseif($op=='del'){
    /*
    $id=$_GET["id"];
    if(empty($id)){
        showmessage("请先选择要删除的信息",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
    }else{
        DB::delete("groupinfo_25",array("id"=>$id));
        showmessage("删除成功",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
    }
    */
}elseif($op=='change'){
     if($_POST){
        //var_dump($_POST);
        /*$id=$_POST["saishiid"];
        if(!is_numeric($id)){
            showmessage("参数错误",$ip."home.php?mod=spacecp&ac=ssinfo&op=list");
            exit;
        }*/
        $name=$_POST["tname"];
        $address=$_POST["address"];
        $start=$_POST["start"];
        $end=$_POST["end"];
        $nowtime=time();
        //先判断有没有存在这个信息
        $issql=DB::query("select id,name,start,end,groupid,address from pre_groupinfo_25 where groupid=".$_G["uid"]);
        $isre=DB::fetch($issql);
        if(!empty($isre)){
            DB::update("groupinfo_25",array("name"=>$name,"address"=>$address,"start"=>$start,"end"=>$end,"inserttime"=>$nowtime),array("groupid"=>$_G["uid"]));
            showmessage("更新成功","/home.php?mod=spacecp&ac=ssinfo"); 
        }else{
            DB::insert("groupinfo_25",array("name"=>$name,"address"=>$address,"start"=>$start,"end"=>$end,"inserttime"=>$nowtime,"groupid"=>$_G["uid"]));
             showmessage("添加成功","/home.php?mod=spacecp&ac=ssinfo"); 
        }
     }else{
        //如果直接访问这个页面就直接跳走
        header("Location:/home.php?mod=spacecp&ac=ssinfo");
        exit;
     }
}



$navtitle="赛事信息设置";

$templates='home/spacecp_'.$_G['groupid'].'_ssinfo';
include_once(template($templates)); 


//include_once(template('home/spacecp_csqy'));
?>