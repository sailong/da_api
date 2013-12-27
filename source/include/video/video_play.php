<?php
/**
 *
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$op = empty($_GET['op']) ? '' : $_GET['op'];

$uid = $_G['uid'];
$vid = $_GET['vid'];

$playlist = DB::fetch_first("select hv.title,hv.content, hv.replynum, hv.viewnum, hvp.filepath, hvp.webpath, hvp.wappath from ".DB::table('home_videopath')." as hvp left join ".DB::table('home_video')." as hv on hv.vpid=hvp.vpid where hv.vid='$vid' order by hv.dateline desc");

$count = $playlist['viewnum'] + 1;
DB::query("update ".DB::table('home_video')." set viewnum='$count' where videoid='$vid' order by dateline desc");
$navtitle=iconv('gb2312','utf-8','大正高尔夫视频').'--'.$playlist[title];
$metakeywords=$playlist[title];
$metadescription=$playlist[title].$playlist[content];
include template('video/video_play');
?>