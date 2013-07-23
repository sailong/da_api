<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_resultcard.php 19160 2012/4/4 angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(($_G['adminid'] == 1 && $_G['setting']['allowquickviewprofile'] && $_G['gp_view'] != 'admin' && $_G['gp_diy'] != 'yes') || defined('IN_MOBILE')) {
	dheader("Location:home.php?mod=space&uid=$space[uid]&do=profile");
}

$perpage = 10;
$perpage = mob_perpage($perpage);
$page = empty($_GET['page'])? 0: intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;


$guess_num = 0;
$where ="";



/*成绩卡的状态*/
$card_status = array('0'=>'未填写','1'=>'待审核','2'=>'已审核');

/*查询球童所在的球场 和 球场下有成绩卡的球星 2012/4/4 angf */
$qiut_form_qiuc = DB::query("SELECT sc.id,qc.realname as qc_name ,ap.fuid,cmp.realname,cmp.uid,sc.dateline,sc.id,sc.tee,sc.status FROM ".DB::table('common_score')." sc  LEFT JOIN ".DB::table('home_apply')." ap  ON ap.fuid=sc.fuid LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=sc.uid LEFT JOIN ".DB::table('common_member_profile')." as qc ON qc.uid=sc.fuid where ap.uid='".$_G['uid']."' and sc.ismine=0 order by sc.id desc limit ".$start.",".$perpage." ");


while($result = DB::fetch($qiut_form_qiuc)){
    $card_info[$result['id']]=$result;
	$card_info[$result['id']]['image'] =avatar($result['uid'], 'middle', true, false,false);
	$card_info[$result['id']]['status'] = $card_status[$result['status']];
	$card_info[$result['id']]['dateline'] =date('Y-m-d H:i:s',$result['dateline']);
	$qc_name = $result['qc_name'];
	$fuid = $result['fuid'];
}

$qc_sql =DB::fetch_first("select count(*) as num from ".DB::table('common_score')." where fuid='".$fuid."'");
$multipage = multi($qc_sql['num'], $perpage , $page, CURSCRIPT.".php?mod=spacecp&uid=1899890&ac=resultcard&id=#resultcard".$urladd);



include_once(template('home/qc_result_card'));
?>