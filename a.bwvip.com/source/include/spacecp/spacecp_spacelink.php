<?php
/**
 *
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('listlink', 'addlink', 'edit', 'del', 'save', 'up')) ? trim($_GET['op']) : 'listlink';

//头部菜单的切换
if(in_array($operation, array('listlink', 'addlink', 'edit'))) {
	$opactives = $operation == 'edit' ? array('listlink' =>'class=a') : array($operation =>'class=a');
}


if($operation == 'listlink') {
	$query = DB::query("select * from ".DB::table('common_spacelink')." where uid='".$_G['uid']."'");
	while($row = DB::fetch($query)) {
		$row['imgname'] = (strlen($row['imgname']) < 12) ? $row['imgname'] : mb_substr($row['imgname'], '0', '4', 'utf-8').'...';
		//$row['imglogo'] = (strlen($row['imglogo']) < 30) ? $row['imglogo'] : mb_substr($row['imglogo'], '0', '30', 'utf-8').'...';
		$row['imgurl'] = (strlen($row['imgurl']) < 23) ? $row['imgurl'] : mb_substr($row['imgurl'], '0', '23', 'utf-8').'...';
		$row['description'] = (strlen($row['description']) < 15) ? $row['description'] : mb_substr($row['description'], '0', '13', 'utf-8').'...';
		$linklist[] = $row;
	}
} elseif($operation == 'save') {
	$count = DB::result(DB::query('select count(*) from '.DB::table('common_spacelink')." where uid='".$_G['uid']."' order by id desc"));
	if($count <= 10) {
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
		for($i = 1; $i <= 8; $i++) {
			$max = strlen($str);
			$start = rand(0, $max);
			$rand .= substr($str, $start, '1');
		}
		$arr = $_POST;
		$arr['uid'] = $_G['uid'];
		unset($arr['profilesubmitbtn']);

		$tmp = $_FILES['imglogo']['tmp_name'];
		$dir = 'uploadfile/link/'.date('Ym');
		if(!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		chmod($dir, 0777);

		$arr['imglogo'] = $dir.'/'.$_G['uid'].'_'.date('YmdHis').$rand.'.jpg';
		$res = move_uploaded_file($tmp, $arr['imglogo']);
		if($res) {
			$row = DB::insert('common_spacelink', $arr);
		} else {
			showmessage('上传失败', 'home.php?mod=spacecp&ac=spacelink&op=addlink');
		}
		if($row) {
			showmessage('添加成功', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
		} else {
			unlink($file);
			showmessage('添加失败', 'home.php?mod=spacecp&ac=spacelink&op=addlink');
		}
	} else {
		showmessage('添加失败，您的链接数已超过十条', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
	}
} elseif($operation == 'del') {
	$id = $_GET['id'];
	$arr = DB::fetch_first('select imglogo from '.DB::table('common_spacelink')." where id='$id' and uid='".$_G['uid']."'");
	$row = DB::query('delete from '.DB::table('common_spacelink')." where uid='".$_G['uid']."' and id='$id'");
	if($row) {
		unlink($arr['imglogo']);
		showmessage('删除成功', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
	} else {
		showmessage('删除失败', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
	}
} elseif($operation == 'edit') {
	$id = $_GET['id'];
	$row = DB::fetch_first('select * from '.DB::table('common_spacelink')." where id='$id' and uid='".$_G['uid']."'");
} elseif($operation == 'up') {
	$arr = $_POST;
	unset($arr['profilesubmitbtn']);
	$row = DB::update('common_spacelink', $arr, array('id'=>$arr['id'], 'uid'=>$_G['uid']));
	if($row) {
		showmessage('修改成功', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
	} else {
		showmessage('修改失败', 'home.php?mod=spacecp&ac=spacelink&op=listlink');
	}
}
include template('home/spacecp_spacelink');
?>