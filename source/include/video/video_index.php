<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('new', 'hot')) ? trim($_GET['op']) : 'new';

$hotvideo = getvideo('1');
$newvideo = getvideo('2');

function getvideo($i) {
	if($i == '1') {
		$order = 'order by viewnum desc';
	} elseif($i == '2') {
		$order = 'order by dateline desc';
	}

	$query = DB::query("select hv.vid, hv.videoid, hv.uid, hv.vpid, hv.title, hv.dateline, hvp.images from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid $order limit 21");
	while($row = mysql_fetch_assoc($query)) {
		$row['dateline'] = date('Y-m-d', $row['dateline']);
		$row['title'] = (strlen($row['title']) > 12) ? mb_substr($row['title'], '0', '8', 'utf-8') : $row['title'];
		$data[] = $row;
	}
	return $data;
}
$navtitle="大正高尔夫视频";
include template('video/video_index');
?>