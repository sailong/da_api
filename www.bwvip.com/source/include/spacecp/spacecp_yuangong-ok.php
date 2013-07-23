<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];
if($gid<'20'){
    header("Location:/");
    exit;
}
$url="/home.php?mod=spacecp&ac=yuangong";
$op=in_array($_GET["op"],array("index","search","del","subadd"))?$_GET["op"]:'index';

//var_dump($op);
if($op=='index'){
    //默认列出全部的员工
    $lia=DB::query("SELECT a.yid,b.`realname` FROM pre_jigou_yuangong a LEFT JOIN pre_common_member_profile b ON a.`yid`=b.`uid` WHERE a.`jid`=".$_G["uid"]);
    while($row=DB::fetch($lia)){
        $listarr[]=$row;
    }
    //var_dump($listarr);
}elseif($op=='search'){
    //var_dump($_POST);    
    $id=trim($_POST["id"]);
    $username=($_POST["username"]);
    if(empty($id) && empty($username)){
        showmessage('缺少参数',$url);
    }
    if(!empty($id)){
        $re=DB::query("SELECT a.uid,a.realname,b.`groupid` FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE a.uid={$id} AND b.`groupid`<20");
        $rear=DB::fetch($re);
        //var_dump($rear);
    }elseif(!empty($username)){
        $re=DB::query("SELECT a.uid,a.realname,b.`groupid` FROM pre_common_member_profile a LEFT JOIN pre_common_member b ON a.uid=b.`uid` WHERE a.realname='{$username}' AND b.`groupid`<20");
        $rear=DB::fetch($re);
    }   
}elseif($op=='subadd'){
    //添加员工
    $uid=trim($_POST["uid"]);
    //echo $uid;
    if(empty($uid)){
        showmessage('缺少参数',$url);
    }
    $time=time();
    $jid=$_G["uid"];
    //判断有没有添加过
    $ya=DB::query("SELECT yid FROM pre_jigou_yuangong WHERE yid=".$uid." AND jid=".$_G["uid"]);
    $yare=DB::fetch($ya);
    if(!empty($yare["yid"])){
        showmessage('已经添加过了',$url);
    }
    $flag=DB::insert("jigou_yuangong",array("yid"=>$uid,"jid"=>$jid,"dateline"=>$time));
    if($flag){
        showmessage('添加员工成功',$url);
    }else{
        showmessage('添加员工失败',$url);
    }
}

if($op=='del'){
    echo "oo";
    exit;
}else{
    $template = 'home/spacecp_yuangong';
    include template($template);
}

   

?>