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
    //如果已经添加，直接默认到选择框
    $al=DB::query("SELECT a.seq,a.pid,a.fid,b.fieldname FROM `pre_home_self_feild` AS a LEFT JOIN pre_common_field AS b ON a.fid=b.id WHERE a.uid=".$uid);
    while($t=DB::fetch($al)){
        $tk[$t["seq"]]=$t;
    }
    //var_dump($tk);
    //城市
    $re=DB::query("SELECT `id`,`name` FROM pre_common_district WHERE LEVEL = 1");
    while($row=DB::fetch($re)){
        $cityarr[]=$row;
    }
    //var_dump($cityarr); 
}elseif($op=='aj'){
    $pid=$_POST["pid"];
    if(empty($pid)){
        echo '';
        exit;
    }
    $fi=DB::query("SELECT id,fieldname FROM `pre_common_field` WHERE province=".$pid);
    while($ak=DB::fetch($fi)){
        $str.='<option value="'.$ak["id"].'">'.$ak["fieldname"].'</option>';
    }
    echo $str;
    exit;
}elseif($op=='addsub'){
    //var_dump($_POST);
    if(!empty($_POST["one"]) && !empty($_POST["onea"])){
           $a=add_field($_POST["one"],$_POST["onea"],1);
    }
    if(!empty($_POST["two"]) && !empty($_POST["twoa"])){
        $b=add_field($_POST["two"],$_POST["twoa"],2);
    }
    if(!empty($_POST["three"]) && !empty($_POST["threea"])){
        $c=add_field($_POST["three"],$_POST["threea"],3);
    }
    if($a || $b || $c){
        showmessage("操作成功","home.php?mod=spacecp&ac=usefiled");
    }else{
        showmessage("没有更新","home.php?mod=spacecp&ac=usefiled");
    }
    exit;
}

/********
*参数一   省份
*参数二    球场
*参数三    第几个位置
*****/
function add_field($pid,$fid,$flag){
    Global $_G;
    $id=$_G["uid"];
    if(!empty($pid) && !empty($fid) && !empty($flag)){
        //判断存不存在
        //$f=DB::query();
        $fr=DB::fetch_first("select * from pre_home_self_feild where uid={$id} and seq=".$flag);
        //var_dump($fr);
        $nowtime=time();
        if(!empty($fr["pid"])){
            $in=DB::update("home_self_feild",array("pid"=>$pid,"fid"=>$fid,"dateline"=>$nowtime),array("uid"=>$id,"seq"=>$flag));
            //echo $fr["pid"]." have<br />";
        }else{
            $in=DB::insert("home_self_feild",array("seq"=>$flag,"pid"=>$pid,"fid"=>$fid,"uid"=>$id,"dateline"=>$nowtime));
            //echo "no<br />";
        }
        
        return $in;
    }
}

//$navtitle="添加常用球场";

$templates='home/spacecp_usefiled';
include_once(template($templates)); 
?>