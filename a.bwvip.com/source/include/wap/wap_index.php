<?php

/**
 *      [dazheng!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: index.php 21922 2012/3/13 02:41:54Z angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$nva_static_url="./index.php?mod=news&ac=group_list&groupid=";


/*定义 需要查询用户组下的ID*/
$group_ids = array(
                   '20'=>'资讯',
				   '21'=>'球场',
				   //'22'=>'球具',
				   //'23'=>'教学',
				   //'24'=>'球星',
				   '25'=>'赛事',
				   //'26'=>'旅游',
				   '20'=>'品牌俱乐部',
				   '1889180'=>'手机报',
				   '1889013'=>'资讯'
				   );
$num =10; $where = '';

/*获取相对 频道下的 或者是某个UId下的 blogs */
$get_groupid = getgpc('groupid');
$get_groupid = empty($get_groupid) ? getgpc('uid') : $get_groupid;
if($_G['gp_ac']=='group_list')
{

	if($_G['gp_uid']){
		$where = " where uid = '".getgpc('uid')."'";
	}elseif($_G['gp_groupid']){
		$where = " where groupid = '".$get_groupid."'";
	}
	$num =100;
}


/*最新博客*/

$new_blogs_query = DB::query(" select `blogid`,`subject` from ".DB::table('home_blog').$where."  order by dateline desc limit ".$num);


while($new_blogs_result = DB::fetch($new_blogs_query)){
	$new_blogs[$new_blogs_result['blogid']]= $new_blogs_result['subject'];
}

if($_G['gp_ac']=='group_list') { include_once template("diy:wap/group_news_list");exit;}


/*查询blog用户组下的 最新blog 用于手机推送*/
foreach($group_ids as $key=>$value){
	$blogs_query = DB::query(" SELECT `blogid`,`uid`,`subject` FROM ".DB::table('home_blog')." WHERE groupid = '".$key."' limit ".$num);
	while($blogs_result=DB::fetch($blogs_query)){
		$blogs[$key][$blogs_result['blogid']]['blogid']  =$blogs_result['blogid'];
		$blogs[$key][$blogs_result['blogid']]['uid']     =$blogs_result['uid'];
		$blogs[$key][$blogs_result['blogid']]['subject'] =$blogs_result['subject'];
	}
}












/*首页 推荐内容 */
$rec_ids = array('36'=>'资讯','34'=>'球场','30'=>'赛事');
foreach($rec_ids as $key=>$value){
	$index_blogs[$value]= blogy($key);
}


//博客
//参数一  是那个类别  参数二显示条数
function blogy($type,$limit=10){
    if(empty($type)){
        $type=27;
    }
    if(empty($limit)){
        $limit=8;
    }

    $query = DB::query("select hb.uid, hb.blogid, hb.subject from ".DB::table('home_recommend')." as hr left join ".DB::table('home_blog')." as hb on hb.blogid=hr.cid where hr.groupid='51' and hr.rectype={$type} order by hr.sort asc,hr.dateline DESC limit ".$limit);
    while($row = DB::fetch($query)) {
    	$row['subject'] = (strlen($row['subject']) < 50) ? $row['subject'] : mb_substr($row['subject'], '0', '20', 'utf-8');
    	$blog[] = $row;
    }
    return $blog;
}

include_once template("diy:wap/news_index_list");



?>