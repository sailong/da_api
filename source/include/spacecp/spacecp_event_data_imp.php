<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_event_data_imp.php 20084 2011-02-14 02:58:04Z angf $
 */


if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$defaultop = 'data_imp';
$operation = in_array($_GET['op'], array('data_imp')) ? trim($_GET['op']) : $defaultop;

$event_users_query= DB::query("select cm.uid,cmp.realname,cmp.rm_event from ".DB::table("common_member")." as cm LEFT JOIN ".DB::table("common_member_profile")." as cmp ON cmp.uid=cm.uid  where cm.groupid=25 ");
while($result = DB::fetch($event_users_query)){
    $event_users[] = $result;
}


/*当前的赛事体系里面 是否导入赛事的数据 获取UID*/
$event_id = DB::result_first("select rm_event from ".DB::table("common_member_profile")." where uid = ".$_G['uid']);

if($operation =='data_imp'){
    /*提交动作*/
    if(submitcheck('submit_data_imp')) {
        DB::update("common_member_profile",array('rm_event'=>$_G['gp_event_id']),array('uid'=>$_G['uid']));
        showmessage("推荐成功","home.php?mod=space&uid=".$_G['uid']);
    }
   $templates='home/spacecp_eventdataimp';
   include_once(template($templates));
}




?>