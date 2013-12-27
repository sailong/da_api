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

/*定义 需要查询用户组下的ID*/
$group_ids = array(
                   '20'=>'资讯',
				   '21'=>'球场',
				   //'22'=>'球具',
				   //'23'=>'教学',
				   //'24'=>'球星',
				   '25'=>'赛事',
				   '26'=>'旅游',
				   '20'=>'品牌俱乐部',
				   '1889180'=>'手机报',
				   '1889013'=>'资讯'
				   );

$nva_static_url="http://wap.bwvip.com/index.php?mod=news&ac=group_list&groupid=";

$num =10; $where = '';

/*获取相对 频道下的 或者是某个UId下的 blogs */
$get_groupid = getgpc('groupid');
$get_groupid = empty($get_groupid) ? getgpc('uid') : $get_groupid;

if($_G['gp_uid']){
	$where = " where uid = '".getgpc('uid')."'";
}
if($_G['gp_id']){
	$where .= " and blogid>{$_G['gp_id']} ";
}
$num =3;
$uid = getgpc('uid');

/*最新博客*/

$new_blogs_query = DB::query(" select `blogid`,`subject` from ".DB::table('home_blog').$where." limit ".$num);


while($new_blogs_result = DB::fetch($new_blogs_query)){
	$new_blogs_list[$new_blogs_result['blogid']]= $new_blogs_result['subject'];
}


$id = getgpc('id');

$news_blog = DB::fetch_first(" SELECT b.`subject`,b.`dateline`,bf.`tag`,bf.`message`,bf.`pic` FROM ".DB::table('home_blog')." as b LEFT join ".DB::table('home_blogfield')." as bf ON b.blogid=bf.blogid where b.blogid='".$id."'");

//print_r($news_blog);

$aa=str_replace("src=\"/Public/editor/attached","src=\"http://www.bwvip.com/Public/editor/attached",$news_blog['message']);
$news_blog['message']=$aa;


if($_GET['test'] == 1) {
    $test = 1;
    echo " select `blogid`,`subject` from ".DB::table('home_blog').$where." limit ".$num;
    var_dump($new_blogs_list);
}

include_once template("wap/news_details");


?>