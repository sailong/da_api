<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('new', 'hot')) ? trim($_GET['op']) : 'hot';

$pagesize = 42;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page'])? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);
$theurl = 'video.php?mod=list&ac=list&op='.$operation;


if($operation == 'new') {
	$order = 'order by hv.dateline desc';
} elseif($operation == 'hot') {
	$order = 'order by hv.viewnum desc';
}

$count = DB::result(DB::query("select count(*) from ".DB::table('home_video')));

if($count) {
	$query = DB::query("select hv.vid, hv.videoid, hv.uid, hv.vpid, hv.title, hv.replynum, hv.dateline, hvp.images from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid $order limit $start, $pagesize");
	while($row = DB::fetch($query)) {
		$row['dateline'] = date('Y-m-d', $row['dateline']);
		$row['title'] = (strlen($row['title']) > 12) ? mb_substr($row['title'], '0', '8', 'utf-8') : $row['title'];
		$videolist[] = $row;
	}
}
$multi = multi($count, $pagesize, $page, $theurl);
$navtitle="大正高尔夫视频";
include template('video/video_list');
?>