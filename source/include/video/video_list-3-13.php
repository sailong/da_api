<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$pagesize = 20;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page'])? 1 : intval($_GET['page']);
if($page < 1) {
	$page = 1;
}
$start = ($page-1)*$pagesize;
ckstart($start, $pagesize);
$theurl = 'video.php?mod=list&ac=list&op='.$operation;

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('new', 'hot')) ? trim($_GET['op']) : 'hot';

if($operation == 'new') {
	$order = 'order by hv.dateline desc';
} elseif($operation == 'hot') {
	$order = 'order by hv.replynum desc';
}

$count = DB::result(DB::query("select count(*) from ".DB::table('home_video')." group by videoid"));

if($count) {
	$query = DB::query("select hv.vid, hv.videoid, hv.uid, hv.vpid, hv.title, hv.replynum, hv.dateline, hvp.images from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid group by hv.videoid $order limit $start, $pagesize");
	while($row = DB::fetch($query)) {
		$row['dateline'] = date('Y-m-d', $row['dateline']);
		$videolist[] = $row;
	}
}
$multi = multi($count, $pagesize, $page, $theurl);

include template('video/video_list');
?>