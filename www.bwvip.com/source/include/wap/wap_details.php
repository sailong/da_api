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


$userAgent = $_SERVER['HTTP_USER_AGENT'];
if(strpos($userAgent,"iPhone") || strpos($userAgent,"iPad") || strpos($userAgent,"iPod") || strpos($userAgent,"iOS"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=0 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/da-zheng-gao-er-fu-golf/id642016024?ls=1&mt=8";
	$mlh_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1186 order by app_version_addtime desc limit 1 ");//"https://itunes.apple.com/us/app/shang-hai-mei-lan-hu-gao-er/id661625407?ls=1&mt=8";
	$ns_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='ios' and field_uid=1160 order by app_version_addtime desc limit 1 ");//"http://www.baidu.com";
	
}
else if(strpos($userAgent,"Android"))
{
	$dz_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=0 order by app_version_addtime desc limit 1 ");
	$mlh_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1186 order by app_version_addtime desc limit 1 ");
	$ns_down_url = DB::result_first("select app_version_file from tbl_app_version where app_version_type='android' and field_uid=1160 order by app_version_addtime desc limit 1 ");
	
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
$field_uid = empty($_G['gp_field_uid']) ? 0 : $_G['gp_field_uid'];


/*大正客户端下载start*/
$banner = "http://www.bwvip.com/images/wap/banner.png";
/*大正客户端下载end*/

switch ($field_uid)
{
    case 0:
      $top_pic = "<img src='http://www.bwvip.com/images/wap/dazheng-top.png' />";
      break;
    case 1186:
	  $top_pic = "<a href='{$mlh_down_url}'><img src='http://www.bwvip.com/images/wap/meilanhu-top.png' /></a>";
      break;
	case 1160:
	  $top_pic = "<a href='{$ns_down_url}'><img src='http://www.bwvip.com/images/wap/nanshan-top.png' /></a>";
      break;
    default:
      $top_pic = "<img src='http://www.bwvip.com/images/wap/dazheng-top.png' />";
      break;
}

if($_G['gp_uid']){
	$where = " and uid = '".getgpc('uid')."'";
}
if($_G['gp_id']){
	$where .= " and blogid>{$_G['gp_id']} ";
}
$num =3;
$uid = getgpc('uid');

/*最新博客*/

$new_blogs_query = DB::query(" select `blogid`,`subject` from ".DB::table('home_blog')." where 1=1 ".$where." limit ".$num);


while($new_blogs_result = DB::fetch($new_blogs_query)){
	$new_blogs_list[$new_blogs_result['blogid']]= $new_blogs_result['subject'];
}


$id = getgpc('id');

$news_blog = DB::fetch_first(" SELECT b.`subject`,b.`dateline`,bf.`tag`,bf.`message`,bf.`pic` FROM ".DB::table('home_blog')." as b LEFT join ".DB::table('home_blogfield')." as bf ON b.blogid=bf.blogid where b.blogid='".$id."'");


$aa=str_replace("src=\"/Public/editor/attached","src=\"http://www.bwvip.com/Public/editor/attached",$news_blog['message']);
$aa=str_replace("src=\"data/attachment/","src=\"http://www.bwvip.com/data/attachment/",$aa);
$news_blog['message']=$aa;


include_once template("wap/news_details");


?>