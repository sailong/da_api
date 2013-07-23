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

$playlist = DB::fetch_first("select hv.title, hv.replynum, hvp.filepath, hvp.webpath, hvp.wappath from ".DB::table('home_videopath')." as hvp left join ".DB::table('home_video')." as hv on hv.vpid=hvp.vpid where hv.videoid='$vid' group by hv.videoid order by hv.dateline desc");

//$count = $playlist['replynum'] + 1;
//DB::query("update ".DB::table('home_video')." set replynum='$count' where videoid='$vid' group by videoid order by dateline desc");

include template('video/video_play');
?>