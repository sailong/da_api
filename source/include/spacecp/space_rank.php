<?php
$pagesize = 35;
$pagesize = mob_perpage($pagesize);
$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
if($page < 1) {
	$page = 0;
}
$start = $page*$pagesize;
ckstart($start, $pagesize);

$theurl = 'home.php?mod=space&do=rank&op=rank&uid='.$_GET['uid'];

$count = DB::result(DB::query("select count(*) from ".DB::table('common_score')." where fuid='".$_GET['uid']."' and status='2' group by uid"));

if($count) {
	$query = DB::query("select cs.id, cs.uid, cs.tee, cs.dateline, cmp.realname from ".DB::table('common_score')." as cs left join ".DB::table('common_member_profile')." as cmp on cmp.uid=cs.uid where cs.fuid='".$_GET['uid']."' and cs.status='2' group by cs.uid order by cs.total_score asc limit $start, $pagesize");
	while($row = DB::fetch($query)) {
		$row['dateline'] = date('Y-m-d H:i:s', $row['dateline']);
		$rank[] = $row;
	}
	foreach($rank as $k=>$v) {
		$query = DB::query("select total_score from ".DB::table('common_score')." where uid='".$v['uid']."' and status='2' order by total_score asc limit 3");
		$total = 0;
		$i = 0;
		while($row = DB::fetch($query)) {
			$i++;
			$total += $row['total_score'];
		}
		if($i < 3) {
			$total_first = DB::fetch_first("select total_score from ".DB::table('common_score')." where uid='".$v['uid']."' and status='2' order by total_score asc");
			$total_score = $total_first['total_score'];
		} else {
			$total_score = round($total/3, 2);
		}
		$rank[$k]['sort'] = $k+1;
		$rank[$k]['rank'] = $k+$start+1;
		$rank[$k]['total_score'] = $total_score;
	}
	$scorerank = scorerank($rank);
}

function scorerank($arr) {
	$count = count($arr);
	$t = 0;
	for($i = 0; $i < $count; $i++) {
		for($j = $i; $j < $count; $j++) {
			if($arr[$i]['total_score'] > $arr[$j]['total_score']) {
				$t = $arr[$i];
				$arr[$i] = $arr[$j];
				$arr[$j] = $t;
			}
		}
	}
	return $arr;
}

$multi = multi($count, $pagesize, $page, $theurl);

include template('home/space_rank');
?>