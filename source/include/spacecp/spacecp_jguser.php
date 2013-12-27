<?php

/**
 *      xgw
 *      2012年3月9日
 *      推荐表
 *     专家  教练
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];   //组id  现在的是25
if($gid!='21'){
    header("Location:/");
    exit;
}



$type=trim($_GET["type"]);   //页面的类型   1教练，2专家
if(empty($type)){
    //$type='1';
    header("home.php?mod=spacecp&ac=jguser&type=1");
    exit;
}

//var_dump($type);
//这里面也需要判断用户的组id
$op = in_array($_GET['op'], array('type','list','del','add','addsub','edit')) ? $_GET['op'] : 'list';    
$space=getspace($_G['uid']);


//require_once libfile('function/space');
//require_once libfile('function/portalcp');


if($type=='1'){
   $what='1';    //教练
   $alttitle="教练管理";
}
if($type=='2'){
    $what='2';    //专家
    $alttitle="专家管理";
}
    if($op=='add'){
        $list=DB::query("SELECT uid,username FROM pre_home_apply WHERE applytype=".$what);
        while($listre=DB::fetch($list)){
            $jiaolian[]=$listre;
        }
        //var_dump($jiaolian);
    }
    if($op=='addsub'){
        //var_dump($_POST);
        $arr=$_POST["add"];
        if(count($arr)>=1){
            $nowtime=time();
            
            foreach($arr as $vk){
               //先判断数据库中有没有这条记录
                $ishave=DB::query("SELECT userid FROM pre_jgtj_user WHERE userid=".$vk." AND type=".$type." and groupuid=".$_G["uid"]);
                $ak=DB::fetch($ishave);
                //var_dump($ak);
                if(!empty($ak)){
                    continue;
                }
                DB::insert("jgtj_user",array("userid"=>$vk,"groupuid"=>$_G["uid"],"inserttime"=>$nowtime,"type"=>$type));
            }
            showmessage('添加成功', 'home.php?mod=spacecp&ac=jguser&type='.$type);
        }else{
            showmessage('没有选择用户', 'home.php?mod=spacecp&ac=jguser&type='.$type);
        }
    }
    
    if($op=='list'){
        $re=DB::query("SELECT a.userid,b.username,c.realname,a.seq FROM pre_jgtj_user a LEFT JOIN pre_ucenter_members b ON a.userid=b.uid LEFT JOIN pre_common_member_profile c ON a.userid=c.uid WHERE a.type=".$type." and a.groupuid=".$_G["uid"]." order by a.seq asc");
        while($row=DB::fetch($re)){
            $listrow[]=$row;
        }
        //var_dump($listrow);
    }
    if($op=='del'){
        $delid=$_POST["del"];
        //var_dump($delid);
        if(count($delid)>=1){
            foreach($delid as $k){
                    DB::delete("jgtj_user",array("userid"=>$k,"groupuid"=>$_G[uid],"type"=>$type));
            }
            showmessage('删除完毕', 'home.php?mod=spacecp&ac=jguser&type='.$type);
        }else{  
            showmessage('没有选择用户', 'home.php?mod=spacecp&ac=jguser&type='.$type);
        }
    }
  



$templates='home/spacecp_jguser';

include_once(template($templates)); 

?>