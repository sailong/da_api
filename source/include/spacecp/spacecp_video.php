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



$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('bound', 'video', 'myvideo')) ? trim($_GET['op']) : 'bound';

//头部菜单的切换
if(in_array($operation, array('bound', 'video', 'myvideo'))) {
	$opactives = array($operation =>'class=a');
}

$do = $_GET['do'];
$uid = $_G['uid'];

//$bound = DB::fetch_first("select cardcode, cardphone, bound from ".DB::table('common_member_card')." as cmc left join ".DB::table('common_member')." as cm on cm.uid=cmc.carduid where cm.uid='$uid' and cm.bound=cmc.cardid");
$bound = DB::fetch_first("select cardcode, cardphone, bound from ".DB::table('common_member_card')." as cmc left join ".DB::table('common_member')." as cm on cm.bound=cmc.cardid where cm.uid='$uid'");


if($operation == 'bound') {
	if($do == 'bound') {
		$cardphone = $_POST['phone'];
		$cardcode = $_POST['code'];
		$card = DB::fetch_first('select cardid from '.DB::table('common_member_card')." where cardphone='$cardphone' and cardcode='$cardcode' and carduid='$uid'");
		if($card['cardid']) {
			$row = DB::query('update '.DB::table('common_member')." set bound='$card[cardid]' where uid='$uid'");
			if($row) {
				showmessage('绑定成功', 'home.php?mod=spacecp&ac=video&op=bound');
			} else {
				showmessage('绑定失败', 'home.php?mod=spacecp&ac=video&op=bound');
			}
		} else {
			showmessage('绑定失败，请查看填写信息是否有错误', 'home.php?mod=spacecp&ac=video&op=bound');
		}
	} elseif($do == 'clear') {
		$row = DB::query('update '.DB::table('common_member')." set bound=0 where uid='$uid'");
		if($row) {
			showmessage('解除成功', 'home.php?mod=spacecp&ac=video&op=bound');
		} else {
			showmessage('解除失败', 'home.php?mod=spacecp&ac=video&op=bound');
		}
	}
} elseif($operation == 'video') {
	if($do == 'del') {
		$id = $_GET['id'];
		$row = DB::query('delete from '.DB::table('common_video')." where uid='$_G[uid]' and id='$id'");
		if($row) {
			showmessage('操作成功', 'home.php?mod=spacecp&ac=video&op=videos');
		} else {
			showmessage('操作失败', 'home.php?mod=spacecp&ac=video&op=videos');
		}
	} else {
		$count = DB::result(DB::query('select count(*) from '.DB::table('common_video')." where uid='$uid' and typeid=0 group by videoid"));
		if($count) {
			$query = DB::query('select * from '.DB::table('common_video')." where uid='$uid' and typeid=0 group by videoid order by addtime desc limit $start, $pagesize");
			while($list = mysql_fetch_assoc($query)) {
				$info[] = $list;
			}
			foreach($info as $key=>$val) {
				$info[$key]['code'] = $i;
				$info[$key]['title'] = strlen($info[$key]['title']) > 24 ? mb_substr($info[$key]['title'], 0, 8, 'utf-8').'...' : $info[$key]['title'];
				$info[$key]['addtime'] = date('Y-m-d H:i:s', $info[$key]['addtime']);
			}
		}

		$theurl = 'home.php?mod=spacecp&ac=video&op=video';
		$multi = multi($count, $pagesize, $page, $theurl);
	}
} elseif($operation == 'myvideo') {
	if($do == 'up') {
		$dir = 'uploadfile/video/'.date('Ym');
		if(!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		chmod($dir, 0777);

		$name = $_FILES['file']['name'];
		$start = strpos($name, '.');
		$len = strlen($name) - $start;
		$suffix = substr($name, $start, $len);
		$type = array('.mp4', '.mp3', '.3gp', '.rmvb', '.rm', '.flv');

		$tmp = $_FILES['file']['tmp_name'];
		$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
		for($i = 1; $i <= 8; $i++) {
			$max = strlen($str);
			$start = rand(0, $max);
			$rand .= substr($str, $start, '1');
		}
		$file = $dir.'/'.$_G['uid'].'_'.date('YmdHis').$rand.$suffix;
		$error = $_FILES['file']['error'];

		if($error == '0') {
			if(in_array($suffix, $type)) {
				$info = move_uploaded_file($tmp, $file);
				if($info) {
					$array = array(
						'uid'=>$_G['uid'],
						'typeid'=>'1',
						'videoid'=>'0',
						'videoimg'=>'uploadfile/common/videoimg.gif',
						'videopath'=>$file,
						'title'=>$_POST['title'],
						'addtime'=>time()
					);
					$row = DB::insert('common_video', $array);
					if($row) {
						showmessage('操作成功', 'home.php?mod=spacecp&ac=video&op=videos');
					} else {
						unlink($file);
						showmessage('操作失败', 'home.php?mod=spacecp&ac=video&op=videos');
					}
				} else {
					showmessage('上传失败', 'home.php?mod=spacecp&ac=video&op=videos');
				}
			} else {
				showmessage('文件类型错误', 'home.php?mod=spacecp&ac=video&op=videos');
			}
		} else {
			showmessage('上传文件错误', 'home.php?mod=spacecp&ac=video&op=videos');
		}
	} elseif($do == 'del') {
		$id = $_GET['id'];
		$arr = DB::fetch_first('select videopath, videoimg from '.DB::table('common_video')." where id='$id'");
		$row = DB::query('delete from '.DB::table('common_video')." where uid='$_G[uid]' and id='$id'");
		if($row) {
			unlink($arr['videopath']);		//清除视频
			if($arr['videoimg'] != 'uploadfile/common/videoimg.gif') {
				unlink($arr['videoimg']);		//清除图片
			}
			showmessage('操作成功', 'home.php?mod=spacecp&ac=video&op=videos');
		} else {
			showmessage('操作失败', 'home.php?mod=spacecp&ac=video&op=videos');
		}
	} else {
		$count = DB::result(DB::query('select count(id) from '.DB::table('common_video')." where uid='$uid' and typeid=1 order by id desc"));
		if($count) {
			$query = DB::query('select * from '.DB::table('common_video')." where uid='$uid' and typeid=1 order by id desc limit $start, $pagesize");
			while($row = mysql_fetch_assoc($query)) {
				$row['title'] = strlen($row['title']) > 24 ? mb_substr($row['title'], 0, 8, 'utf-8').'...' : $row['title'];
				$row['addtime'] = date('Y-m-d', $row['addtime']);
				$info[] = $row;
			}
		}

		$theurl = 'home.php?mod=spacecp&ac=video&op=videos';
		$multi = multi($count, $pagesize, $page, $theurl);
	}
}

if($do == 'edit') {
	$id = $_GET['id'];
	$val = iconv('gbk', 'utf-8', $_GET['val']);
	$row = DB::query('update '.DB::table('common_video')." set title='$val' where id='$id'");
	if($row) {
		echo $title = strlen($val) > 24 ? mb_substr($val, '0', '8', 'utf-8').'...' : $val;
	} else {
		$video = DB::fetch_first('select title from '.DB::table('common_video')." where id='$id'");
		echo $title = substr($video['title']) > 24 ? mb_substr($video['title'], '0', '8', 'utf-8').'...' : $video['title'];
	}
} elseif($do == 'title') {
	$id = $_GET['id'];
	$video = DB::fetch_first('select title from '.DB::table('common_video')." where id='$id'");
	echo $video['title'];
} else {
	$usergroup = $_G['groupid'];
	$template = 'home/spacecp_'.$usergroup.'_video';
	include template($template);
}

?>