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


$nva_static_url="http://wap.bwvip.com/index.php?mod=news&ac=group_list&groupid=";


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

$id = getgpc('id');

$news_blog = DB::fetch_first(" SELECT b.`subject`,b.`dateline`,bf.`tag`,bf.`message`,bf.`pic` FROM ".DB::table('home_blog')." as b LEFT join ".DB::table('home_blogfield')." as bf ON b.blogid=bf.blogid where b.blogid='".$id."'");



include_once template("wap/news_details");


?>