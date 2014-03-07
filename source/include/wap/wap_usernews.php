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
$page = $_G['gp_page'];
$page = max(1,$page);
//$tip = $_G['gp_tip'];



$num =10;
$offset = ($page-1)*$num;
$uid = $_G['gp_uid'];

/*最新博客*/
//$new_blogs_query = DB::query(" select `blogid`,`subject` from ".DB::table('home_blog')." where 1=1 ".$where." limit 1");
//if(!$new_blogs_query){
	$where = " and b.arc_type='U'";
	if($uid){
		$where .= " and b.uid = '".$uid."'";
	}
	
	$where .= " order by b.`blogid` desc limit {$offset},11";
	
	$new_blogs_query = DB::query(" SELECT b.`blogid`,b.`subject`,b.`dateline`,b.`viewnum`,b.`replynum`,bf.`tag`,bf.`message`,bf.`pic`,bf.`ad_pic`,arc.`arc_pic` FROM (".DB::table('home_blog')." as b LEFT join ".DB::table('home_blogfield')." as bf ON b.blogid=bf.blogid) left join tbl_arc as arc on b.blogid=arc.arc_id  where 1=1 ".$where);
//}
$blog_id = 0;
while($new_blogs_result = DB::fetch($new_blogs_query)){
	$new_blogs_result['limit_content'] = strip_tags($new_blogs_result['message']);
	$new_blogs_result['limit_content'] =msubstr($new_blogs_result['limit_content'],0,42);
	$new_blogs_result['date'] = date('Y-m-d',$new_blogs_result['dateline']);
	$new_blogs_result['arc_pic'] = "http://www.bwvip.com/".$new_blogs_result['arc_pic'];
	$new_blogs_list[$new_blogs_result['blogid']]= $new_blogs_result;
	$blog_ids[] = $new_blogs_result['blogid'];
}
$blog_id = $blog_ids[9];
if(count($new_blogs_list)>$num){
array_pop($new_blogs_list);
$next_page = $page+1;
//$echo_data['next_page'] = $next_page;
}else{
$next_page = $page;
//$echo_data['next_page'] = '';
}
$pre_page = max(1,$page-1);
/* if($tip=='ajax'){
$echo_data['data_list'] = $new_blogs_list;
echo json_encode($echo_data);exit;
return false;
} */
/*  echo '<pre>';
var_dump($new_blogs_list);  */
//最新blog
/* $last_blogs_result = DB::fetch_first("SELECT `blogid`,`subject`,`uid` FROM ".DB::table('home_blog')." where 1=1 and arc_type='U' order by blogid desc limit 1");
$last_blogs_result['subject'] = strip_tags($last_blogs_result['subject']);
$last_blogs_result['subject'] =msubstr($last_blogs_result['subject'],0,16); */

//作者头像和姓名

$member_sql = "select uid,realname,level,intro from pre_common_member_profile where uid='{$uid}'";
$member_info = DB::fetch_first($member_sql);
$member_info['touxiang'] = "http://www.bwvip.com/uc_server/avatar.php?uid=".$uid."&size=big";//http://www.bwvip.com/uc_server/avatar.php?uid=1889013&size=middle
$num_data = DB::fetch_first(" select `newpm`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new`,`fans_count` from ultrax.jishigou_members where uid =".$uid);
/* echo '<pre>';
var_dump($num_data); */
$blog_num = DB::fetch_first("SELECT count(blogid) blognum FROM ".DB::table('home_blog')." where 1=1 and arc_type='U' and uid=".$uid);

	function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
		if(function_exists("mb_substr")){
			if($suffix) {
				return mb_substr($str, $start, $length, $charset).'......';
			}
			return mb_substr($str, $start, $length, $charset);
		}elseif(function_exists('iconv_substr')) {
			if($suffix) {
				return iconv_substr($str,$start,$length,$charset).'......';
			}
			return iconv_substr($str,$start,$length,$charset);
		}
		
		$re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk']	  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
		$re['big5']	  = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join("",array_slice($match[0], $start, $length));
		if($suffix) return $slice."…";
		return $slice;
	}


include_once template("wap/user_news");


?>