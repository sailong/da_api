<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
cpheader();
$operation = $operation ? $operation : 'apply';

$anchor = in_array($_G['gp_anchor'], array('base', 'edit', 'verify', 'verify1', 'verify2', 'verify3', 'verify4', 'verify5', 'verify6', 'verify7', 'authstr', 'refusal', 'pass')) ? $_G['gp_anchor'] : 'base';
$current = array($anchor => 1);
$navmenu = array();
$id = $_GET['id'];
$do = $_GET['do'];


if($operation == 'apply') {
	loadcache('profilesetting');
	$vid = intval($_G['gp_do']);
	$anchor = in_array($_G['gp_anchor'], array('authstr', 'refusal', 'pass', 'add')) ? $_G['gp_anchor'] : 'authstr';
	$current = array($anchor => 1);
	if($anchor != 'pass') {
		$_GET['verifytype'] = $vid;
	} else {
		$_GET['verify'.$vid] = 1;
		$_GET['orderby'] = 'uid';
	}
	require_once libfile('function/profile');
	if(!submitcheck('verifysubmit', true)) {
		$menutitle = $vid ? $_G['setting']['verify'][$vid]['title'] : $lang['members_verify_profile'];
		$navmenu[0] = array('members_verify_nav_authstr', 'apply&operation=apply&anchor=authstr', $current['authstr']);
		$navmenu[1] = array('members_verify_nav_refusal', 'apply&operation=apply&anchor=refusal', $current['refusal']);
		$navmenu[2] = array('members_verify_nav_pass', 'apply&operation=apply&anchor=pass', $current['pass']);
		//$navmenu[3] = array('members_verify_nav_add', 'apply&operation=add&vid='.$vid, $current['add']);
		$vid ? shownav('user', 'nav_members_verify', $menutitle) : shownav('user', $menutitle);
		showsubmenu($lang['members_verify_verify'].($vid ? '-'.$menutitle : ''), $navmenu);
	}
	if(empty($do)) {
		if($anchor == 'authstr') {
			echo getapply('0', 'authstr');
		}
		if($anchor == 'refusal') {
			echo getapply('2', 'refusal');
		}
		if($anchor == 'pass') {
			echo getapply('1', 'pass');
		}
	}
	if($do == 'pass') {
		DB::query("update ".DB::table('home_apply')." set isverify=1 where id=".$id);
		cpmsg('members_verify_succeed', 'action=apply&operation=apply&anchor=pass');
	} elseif($do == 'refusal') {
		DB::query("update ".DB::table('home_apply')." set isverify=2 where id=".$id);
		cpmsg('members_verify_succeed', 'action=apply&operation=apply&anchor=refusal');
	}
}
function getapply($tid, $anchor) {
	$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
	$pagesize = 10;
	if($page < 1) $page = 1;
	$start = ($page-1)*$pagesize;
	$multipage = '';

	$fieldstr = "<table width='96%'><tr style='height:30px'><td>id</td><td>用户名</td><td>申请类型</td><td>申请时间</td><td>从业年限</td><td>审核状态</td><td>操作</td></tr>";
	$count = DB::result(DB::query("select count(*) from ".DB::table('home_apply')." as ha left join ".DB::table('common_district')." as cd on cd.id=ha.provinceid left join ".DB::table('common_field')." as cf on cf.uid=ha.fuid where isverify=".$tid));
	if($count) {
		$query = DB::query("select ha.*, cd.name, cf.fieldname from ".DB::table('home_apply')." as ha left join ".DB::table('common_district')." as cd on cd.id=ha.provinceid left join ".DB::table('common_field')." as cf on cf.uid=ha.fuid where ha.isverify=".$tid." limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			if($tid == '0') {
				$str = "<a href='admin.php?action=apply&operation=apply&do=pass&id=".$row['id']."'>通过</a> | <a href='admin.php?action=apply&operation=apply&do=refusal&id=".$row['id']."'>拒绝</a>";
			} elseif($tid == '1') {
				$str = "<a href='admin.php?action=apply&operation=apply&do=refusal&id=".$row['id']."'>取消</a>";
			} elseif($tid == '2') {
				$str = "<a href='admin.php?action=apply&operation=apply&do=pass&id=".$row['id']."'>通过</a>";
			}
			$row['type'] = ($row['applytype'] == '0') ? '申请球童('.$row['name'].' >> '.$row['fieldname'].')' : ($row['applytype'] == '1' ? '申请教练' : '申请专家');
			$row['lasttime'] = date('Y-m-d', $row['lasttime']);
			$row['status'] = $row['isverify'] == '0' ? '待审核' : ($row['isverify'] == '1' ? '已通过' : '未通过');
			$fieldstr .= "<tr style='height:30px' id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")'><td>".$row['uid']."</td><td><a href=\""."/home.php?mod=space&uid=".$row["uid"]."\" target='_blank'>".$row['username']."</a></td><td>".$row['type']."</td><td>".$row['lasttime']."</td><td>".$row['year']."</td><td>".$row['status']."</td><td>".$str."</td></tr>";
		}
	}
	$fieldstr .= "</table>";
	$multipage = multi($count, $pagesize, $page, '?action=apply&operation=apply&anchor='.$anchor);
	$apply = $fieldstr.$multipage;
	return $apply;
}
?>
<script type='text/javascript'>
	function showcolor(id) {
		document.getElementById(id).style.background = 'FAFAFA';
	}
	function clearcolor(id) {
		document.getElementById(id).style.background = '';
	}
</script>