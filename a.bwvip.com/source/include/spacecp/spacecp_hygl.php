<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_profile.php 24010 2011-08-19 07:35:13Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


$ac = trim($_GET['ac']);
$compid=trim($_GET["compid"]);    //被申请人id
//这个id是用于申请的时候，不能直接拿当前登录的这个用户来当被申请人
if(empty($compid)){
    $compid=$space['uid'];
}
$userid=trim($_GET["userid"]);   //接收的用户id，申请人的id
$operation = in_array($_GET['op'], array('search', 'shenhe','shenqing','myhy','invite','doinvite')) ? trim($_GET['op']) : 'search';
//头部菜单的切换
if(in_array($operation, array('search', 'shenhe','shenqing','myhy','invite'))) {
	$opactives = array($operation =>'class=a');
}

//我的会员
if ($operation=='myhy') {
$myhy=array();
$query = DB::query("SELECT a.userid,a.iscomp,a.isuser,b.username,c.realname FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid left join ".DB::table('common_member_profile')." as c on b.uid=c.uid WHERE (a.iscomp=1 or a.isuser=1) and a.compid=".$space['uid']);
	while ($hy = DB::fetch($query)){
	   if(!empty($hy["realname"])){
	       $hy["username"]=$hy["realname"];
	   }
	$myhy[]=$hy;
	}
}


//申请会员
if($operation=='shenqing'){
    if($compid==$userid){
         showmessage('不能自己申请自己', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
         exit;
    }

   if(empty($userid)){
        showmessage('申请失败,缺少参数', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
        exit;
   }else{
        //查看用户申请过没有
       $isall=DB::fetch_first("select compid,userid from pre_guanxi where compid=".$compid." and userid=".$userid);
       
       //echo "select compid,userid from pre_guanxi where compid=".$compid." and userid=".$userid;
        //var_dump($isall);
       if(empty($isall)){
            DB::insert('guanxi', array('userid' => $userid,'compid' =>$compid,'iscomp' => 0));
            showmessage('已经申请成功,请耐心等待', dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
       }else{
            showmessage("不能重复申请", dreferer(), array(), array('showdialog' => 1, 'closetime' => true));
       }
       
	   
    }
}

//个人对企业申请后 企业审核状态 0-提交申请  1-审核通过
//审核中的 
if ($operation=='shenhe') {
$shenqing=array();
$query = DB::query("SELECT a.userid,a.iscomp,b.username,c.realname FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid left join ".DB::table("common_member_profile")." as c on b.uid=c.uid WHERE a.iscomp=0 and a.compid=".$space['uid']);
	while ($sq = DB::fetch($query)){
	   if(!empty($sq["realname"])){
	       $sq["username"]=$sq["realname"];
	   }
	   $shenqing[]=$sq;
	}
	
}
//通过审核
if ($_GET['iscomp'] && !empty($_GET['iscomp'])) {
    if(empty($_GET['userid'])){
        showmessage('缺少参数', 'home.php?mod=spacecp&ac=hygl');
    }else{
        DB::update('guanxi', array('iscomp' => $_GET['iscomp']),array('userid'=>$_GET['userid'],'compid'=>$space['uid']));
        showmessage('状态改变成功', 'home.php?mod=spacecp&ac=hygl');
    }
    
}

//拒绝或删除会员
if ($_GET['cz'] == 'del') {
        if(!empty($space['uid']) && !empty($_GET['userid'])){
            DB::query("DELETE FROM ".DB::table('guanxi')." WHERE compid=".$space['uid']." AND userid=".$_GET['userid']);
            showmessage('删除关系成功', 'home.php?mod=spacecp&ac=hygl');
        }else{
            showmessage('传参失败', 'home.php?mod=spacecp&ac=hygl');
        }
}


//按名称或id查询
if($_GET['user']=="key"){
    $userr=$_GET['user'];
    $userid=trim($_POST['userid']);
    $username=trim($_POST['username']);
    if ($userid){
    	$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and uid=$userid");   //groupid < 20
    }
    if ($username) {
    	//$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and username='$username'");   //groupid < 20
        $user = DB::fetch_first("SELECT a.uid,a.username,b.realname FROM ".DB::table("common_member")." a LEFT JOIN pre_common_member_profile b ON a.uid=b.uid WHERE a.groupid < 20 and a.username='{$username}' OR b.realname='{$username}'");
    }

	if ($user[uid]){
		$gxiscomp = DB::fetch_first("SELECT * FROM ".DB::table("guanxi")." WHERE userid=".$user[uid]." and compid=".$space[uid]);
	}
}

//邀请查询
if ($operation=='invite') {
$shenqsh=array();
$query = DB::query("SELECT a.userid,a.isuser,a.userid,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE a.isuser=0 and a.compid=".$space['uid']);
	while ($sqsh = DB::fetch($query)){
	$shenqsh[]=$sqsh;
	}
}

//邀请会员
if($operation=='doinvite'){
	$qiyeid=trim($_GET['userid']);
    if(!empty($qiyeid)){
        DB::insert('guanxi', array('userid' => $qiyeid,'compid' => $space['uid'],'isuser' => 0));
	   showmessage('邀请成功等待审核', 'home.php?mod=spacecp&ac=hygl');
    }else{
       showmessage('参数错误', 'home.php?mod=spacecp&ac=hygl');
	}
}


 
$templates='home/spacecp_hygl';
 

include_once(template($templates)); 





/*

//按名称查询
if($_GET['user']=="key"){
$userr=$_GET['user'];
$userid=trim($_POST['userid']);
$username=trim($_POST['username']);
if ($userid){
	$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and uid=$userid");
}
if ($username) {
	$user = DB::fetch_first("SELECT * FROM ".DB::table("common_member")." WHERE groupid < 20 and username='$username'");
}

	if ($user[uid]){
		$gxiscomp = DB::fetch_first("SELECT * FROM ".DB::table("guanxi")." WHERE userid=".$user[uid]." and compid=".$space[uid]);
	}
}

//企业对个人发出请求后的状态
if ($operation=='shenhe') {
$shenqsh=array();
$query = DB::query("SELECT a.userid,a.isuser,a.userid,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE a.isuser=0 and a.compid=".$space['uid']);
	while ($sqsh = DB::fetch($query)){
	$shenqsh[]=$sqsh;
	}
}


//个人对企业申请后 企业审核状态 0-提交申请  1-审核通过 
if ($operation=='shenqing') {
$shenqing=array();
$query = DB::query("SELECT a.userid,a.iscomp,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE a.iscomp=0 and a.compid=".$space['uid']);
	while ($sq = DB::fetch($query)){
	$shenqing[]=$sq;
	}
	
}
//通过审核
if ($_GET['iscomp']) {
DB::update('guanxi', array('iscomp' => $_GET['iscomp']),array('userid'=>$_GET['userid'],'compid'=>$space['uid']));
showmessage('状态改变成功', 'home.php?mod=spacecp&ac=huiyuan');
}

//删除对应关系
if ($_GET['cz'] == 'del') {
DB::query("DELETE FROM ".DB::table('guanxi')." WHERE compid=".$space['uid']." AND userid=".$_GET['userid']);
showmessage('删除关系成功', 'home.php?mod=spacecp&ac=huiyuan');
}


//我的会员
if ($operation=='myhy') {
$myhy=array();
$query = DB::query("SELECT a.userid,a.iscomp,a.isuser,b.username FROM ".DB::table("guanxi")." a left join ".DB::table("common_member")." b on a.userid=b.uid WHERE (a.iscomp=1 or a.isuser=1) and a.compid=".$space['uid']);
	while ($hy = DB::fetch($query)){
	$myhy[]=$hy;
	}
}


//企业邀请会员
if($_GET['qiyeid']){
	$qiyeid=trim($_GET['qiyeid']);
	DB::insert('guanxi', array('userid' => $qiyeid,'compid' => $space['uid'],'isuser' => 0));
	showmessage('申请成功等待审核', 'home.php?mod=spacecp&ac=huiyuan');

	
}
 

*/


?>