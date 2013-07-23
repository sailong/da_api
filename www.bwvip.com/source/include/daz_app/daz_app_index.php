<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: daz_app_index.php 19158 2012/3/5 08:21:50Z angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$perpage = 2;
$perpage = mob_perpage($perpage);
$page = empty($_GET['page'])? 0: intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;

$guess_num = 0;
$where ="";
/*取得活动列表*/
$query_guess_num = DB::fetch_first(" SELECT count(`gu_id`) as guess_num from ".DB::table("daz_guessing"));
if($query_guess_num) $guess_num = $query_guess_num['guess_num'];

$multipage = multi($guess_num, $perpage , $page, CURSCRIPT.".php?mode=guess&do=index".$urladd);

$guess_list_query = DB::query(" SELECT `gu_id`,`start_time`,`end_time`,`title`,`guess_picture`,`describe`,`start_time`,`end_time`,`guess_tag`,`guess_object` FROM ".DB::table('daz_guessing')." ".$wher." order by sort_order asc , gu_id desc limit ".$start.",".$perpage);
$now_time=time();
while($guess_list_result = DB::fetch($guess_list_query)){
    $attr_query = DB::query(" select `attr_id`,`attr_show_value` From ".DB::table('daz_attr')." where attr_id IN (".$guess_list_result['guess_tag'].")" );
	while($attr_result = DB::fetch($attr_query)){
	   $guess_tag_list[$attr_result['attr_id']]  = $attr_result['attr_show_value'];
	}
    $guess_list[$guess_list_result['gu_id']]['guess_status'] = $now_time < $guess_list_result['start_time'] ? '未开始' :($now_time > $guess_list_result['start_time'] && $now_time < $guess_list_result['end_time'] ? '进猜中...' :'已经结束');
    $guess_list[$guess_list_result['gu_id']]['title']            = $guess_list_result['title'];
    $guess_list[$guess_list_result['gu_id']]['guess_picture']    = $guess_list_result['guess_picture'];
    $guess_list[$guess_list_result['gu_id']]['describe']         = $guess_list_result['describe'];
    $guess_list[$guess_list_result['gu_id']]['guess_object']     = $guess_list_result['guess_object'];
    $guess_list[$guess_list_result['gu_id']]['start_time']       = date('Y-m-d',$guess_list_result['start_time']);
    $guess_list[$guess_list_result['gu_id']]['end_time']         = date('Y-m-d',$guess_list_result['end_time']);
    $guess_list[$guess_list_result['gu_id']]['guess_tag']        = $guess_tag_list;
	$guess_tag_list =array();

}

include_once(template('daz_app/guess_index'));

?>