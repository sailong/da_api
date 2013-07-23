<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
cpheader();
$operation = in_array($_GET['operation'], array('member', 'video', 'blog', 'other', 'custom','viewlist','del')) ? $_GET['operation'] : 'member';
$id = $_GET['id'];
$do = $_GET['do'];
$c = $_GET['c'];
$type=getgpc("type");   //参数的类型  blog,video,member
$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
$pagesize = ($operation != 'blog') ? '10' : '20';
if($page < 1) $page = 1;
$start = ($page-1)*$pagesize;
$multipage = '';

$groupid = $_POST['groupid'] ? $_POST['groupid'] : $_GET['groupid'];
$uid = $_POST['uid'] ? $_POST['uid'] : $_GET['uid'];
$username = $_POST['username'] ? $_POST['username'] : $_GET['username'];
$title = $_POST['title'] ? $_POST['title'] : $_GET['title'];

shownav('portal', '社区推荐');
loadcache('profilesetting');
require_once libfile('function/profile');
if(!submitcheck('verifysubmit', true)) {
	$navmenu[0] = array('会员推荐', 'recommend&operation=member', $operation == 'member');
	$navmenu[1] = array('视频推荐', 'recommend&operation=video', $operation == 'video');
	$navmenu[2] = array('博客推荐', 'recommend&operation=blog', $operation == 'blog');
	$navmenu[3] = array('首页推荐', 'recommend&operation=other', $operation == 'other');
	$navmenu[4] = array('自定义推荐', 'recommend&operation=custom', $operation == 'custom');
	showsubmenu('社区推荐', $navmenu);
}
$query = DB::query("select groupid, grouptitle from ".DB::table('common_usergroup')." where groupid>=20");
$membertype = "&nbsp;&nbsp;分类：<select name='groupid'><option value='10'>个人</option>";
while($row = DB::fetch($query)) {
	if($groupid == $row['groupid']) {
		$membertype .= "<option value='".$row['groupid']."' selected>".$row['grouptitle']."</option>";
	} else {
		$membertype .= "<option value='".$row['groupid']."'>".$row['grouptitle']."</option>";
	}
}
$membertype .= "</select>";
//add by xgw on 2012年5月15日
$listtype=getgpc("type");   //判断是那个页面  blog , video ,member
if($operation=='blog' || $listtype=='blog'){

	$tyid=" where group_num=3";
}elseif ($operation=='member' || $listtype=='member'){
	$tyid=" where group_num=1";
}elseif ($operation=='video' || $listtype=='video'){
	$tyid=" where group_num=2";
}
//判断下拉框中的页面类型 blog,member,video
$myop=array('blog','member','video');
if(in_array($operation,$myop)){
	$chakan=$operation;
}else{
	$chakan=$type;
}

//add by xgw on 2012年5月15日end

$query = DB::query("select group_id, group_name from ".DB::table('recommend_group')." ".$tyid);
while($row = DB::fetch($query)) {
	$rectypearr[$row["group_id"]] = $row;    //chang by xgw on 2012年5月15日  原来的$rectypearr[$row["group_id"]] = $row;
}
//var_dump($rectypearr);

if($operation != 'other' && $operation != 'custom') {
?>
<form method='post' name='star' action="<?php echo ADMINSCRIPT;?>?action=recommend&operation=<?php echo $operation; ?>&do=search">
	UID：<input type='text' name='uid' maxlength='11' value='<?php echo $uid; ?>' />&nbsp;&nbsp;用户名：<input type='text' name='username' maxlength='20' value='<?php echo $username; ?>' /><?php if($operation == 'member') { echo $membertype;} ?>
	<?php if($operation == 'video' || $operation == 'blog') { ?>标题：<input type='text' name='title' maxlength='50' value='<?php echo $title; ?>' /><?php } ?>&nbsp;&nbsp;&nbsp;&nbsp;<input type='submit' style='width:60px' name='submit' value='搜索' />
</form>
&nbsp;查看：<select onchange="javascript:location.href='<?php echo ADMINSCRIPT;?>?action=recommend&operation=viewlist&type=<?php echo $chakan ?>&tid='+this.value" name="view">
<option value="">请选择</option>
	<?php
foreach($rectypearr as $k=>$v) {
	$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
}
echo $rectype;
?>
</select>
<br />
<br />
<?php
}
if(($do == 'search' || empty($do)) && $operation != 'other' && $operation != 'custom') {
	if($operation == 'member') {
		$list = "<table width='100%'><tr><td>排序</td><td>用户id</td><td>头像</td><td>用户名</td><td>分类</td><td>操作</td></tr>";
		if($do == 'search') {
			$where = '';
			if($uid) {
				if($username) {
					$where = "uid like '%$uid%' or username like '%$username%'";
				} else {
					$where = "uid like '%$uid%'";
				}
			} else {
				if($username) {
					$where = "username like '%$username%'";
				} else {
					$where = "uid>0";
				}
			}
			if($groupid >= 20) {
				$group = "groupid='$groupid'";
			} else {
				$group = "groupid='10'";
			}
			$count = DB::result(DB::query("select count(*) from ".DB::table('common_member')." where ($where) and $group"));
			$query = DB::query("select * from ".DB::table('common_member')." where ($where) and $group limit $start, $pagesize");
			while($row = DB::fetch($query)) {
				$data = DB::fetch_first("select id, uid, sort, groupid, rectype from ".DB::table('home_recommend')." where uid='".$row['uid']."' and groupid='".$groupid."'");
				$sort = $data['sort'] ? $data['sort'] : '';
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					/*if($v['group_id'] == $data['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {*/
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					//}
				}
				$rectype .= "</select>";
				$str = "<input type='submit' name='submit' value='推荐' style='border:none'>";
				$list .= "<form action='".ADMINSCRIPT."?action=recommend&operation=star&do=recomm&uid=".$row['uid']."&gid=".$row['groupid']."' method='post' name='myformval'><tr id=".$row['uid']." onmouseover='showcolor(".$row['uid'].")' onmouseout='clearcolor(".$row['uid'].")'><td><input type='text' name='sort' value='".$sort."' style='width:50px' /></td><td>".$row['uid']."</td><td><img src='uc_server/avatar.php?uid=".$row['uid']."&size=middle' width='60' height='60' /></td><td>".$row['username']."</td><td>".$rectype."</td><td>".$str."</td></tr></form>";
			}
			$thurl = ADMINSCRIPT.'?action=recommend&operation=star&do=search&uid='.$uid.'&username='.$username.'&groupid='.$groupid;
			$multipage = multi($count, $pagesize, $page, $thurl);
		}
		//chang by xgw on 2012年5月16日
		/* else {
			$count = DB::result(DB::query("select count(*) from ".DB::table('home_recommend')." as hr left join ".DB::table('common_usergroup')." as cu on cu.groupid=hr.groupid where hr.groupid<'50'"));
			$query = DB::query("select hr.*, cu.grouptitle from ".DB::table('home_recommend')." as hr left join ".DB::table('common_usergroup')." as cu on cu.groupid=hr.groupid where hr.groupid<'50' limit $start, $pagesize");
			while($row = DB::fetch($query)) {
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					if($v['group_id'] == $row['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					}
				}
				$rectype .= "</select>";
				$membergroup = ($row['groupid'] >= 20) ? $row['grouptitle'] : '个人';
				$list .= "<form action='admin.php?action=recommend&operation=star&do=recomm&uid=".$row['uid']."&c=1&gid=".$row['groupid']."' method='post' name='form'><tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")'><td><input type='text' name='sort' value='".$row['sort']."' style='width:50px' /></td><td>".$row['uid']."</td><td><img src='uc_server/avatar.php?uid=".$row['uid']."&size=middle' width='60' height='60' /></td><td>".$row['username']."（".$membergroup."）</td><td>".$rectype."</td><td><a href='admin.php?action=recommend&operation=star&do=del&id=".$row['id']."&gid=".$row['groupid']."'>取消推荐</a> | <input type='submit' name='submit' value='重新推荐' style='border:none'></td></tr></form>";
			}
			$multipage = multi($count, $pagesize, $page, 'admin.php?action=recommend&operation=member&groupid='.$groupid);
		}*/
		echo $list."</table>".$multipage;
	} elseif($operation == 'video') {
		$list = "<table width='100%'><tr><td>排序</td><td>用户id</td><td>缩略图</td><td>用户名</td><td>标题</td><td>分类</td><td>操作</td></tr>";
		if($do == 'search') {
			$where = '';
			if($uid) {
				if($username) {
					if($title) {
						$where = "hv.uid like '%$uid%' or hv.username like '%$username%' or hv.title like '%$title%'";
					} else {
						$where = "hv.uid like '%$uid%' or hv.username like '%$username%'";
					}
				} else {
					if($title) {
						$where = "hv.uid like '%$uid%' or hv.title like '%$title%'";
					} else {
						$where = "hv.uid like '%$uid%'";
					}
				}
			} else {
				if($username) {
					if($title) {
						$where = "hv.username like '%$username%' or hv.title like '%$title%'";
					} else {
						$where = "hv.username like '%$username%'";
					}
				} else {
					if($title) {
						$where = "hv.title like '%$title%'";
					} else {
						$where = "hv.uid>0";
					}
				}
			}
			$count = DB::result(DB::query("select count(*) from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where $where"));
			$query = DB::query("select hv.*, hvp.images from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where $where limit $start, $pagesize");
			while($row = DB::fetch($query)) {
				$data = DB::fetch_first("select id, uid, sort, groupid, rectype from ".DB::table('home_recommend')." where uid='".$row['uid']."' and cid='".$row['vid']."' and groupid='50'");
				$sort = $data['sort'] ? $data['sort'] : '';
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					/*if($v['group_id'] == $data['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {*/
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					//}
				}
				$rectype .= "</select>";
				$str = "<input type='submit' name='submit' value='推荐' style='border:none'>";
				$list .= "<form action='".ADMINSCRIPT."?action=recommend&operation=video&do=recomm&uid=".$row['uid']."&cid=".$row['vid']."&gid=50' method='post' name='myformval'><tr id=".$row['vid']." onmouseover='showcolor(".$row['vid'].")' onmouseout='clearcolor(".$row['vid'].")'><td><input type='text' name='sort' value='".$sort."' style='width:50px' /></td><td>".$row['uid']."</td><td><img src='".$row['images']."' width='60' height='60' /></td><td>".$row['username']."</td><td>".$row['title']."</td><td>".$rectype."</td><td>".$str."</td></tr></form>";
			}
			$thurl = ADMINSCRIPT.'?action=recommend&operation=video&do=search&uid='.$uid.'&username='.$username.'&title='.$title;
			$multipage = multi($count, $pagesize, $page, $thurl);
		}
		/* else {

			$count = DB::result(DB::query("select count(*) from ".DB::table('home_recommend')." as hr left join ".DB::table('home_video')." as hv on hv.vid=hr.cid left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hr.groupid='50'"));
			$query = DB::query("select hr.*, hv.title, hvp.images from ".DB::table('home_recommend')." as hr left join ".DB::table('home_video')." as hv on hv.vid=hr.cid left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hr.groupid='50'");
			while($row = DB::fetch($query)) {
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					if($v['group_id'] == $row['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					}
				}
				$rectype .= "</select>";
				$list .= "<form action='admin.php?action=recommend&operation=video&do=recomm&uid=".$row['uid']."&cid=".$row['cid']."&c=1&gid=50' method='post' name='form'><tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")'><td><input type='text' name='sort' value='".$row['sort']."' style='width:50px' /></td><td>".$row['uid']."</td><td><img src='".$row['images']."' width='60' height='60' /></td><td>".$row['username']."</td><td>".$row['title']."</td><td>".$rectype."</td><td><a href='admin.php?action=recommend&operation=video&do=del&id=".$row['id']."&gid=50'>取消推荐</a> | <input type='submit' name='submit' value='重新推荐' style='border:none'></td></tr></form>";
			}
			$multipage = multi($count, $pagesize, $page, 'admin.php?action=recommend&operation=video');
		}*/
		echo $list."</table>".$multipage;
	} else {
		$list = "<table width='100%'><tr><td>排序</td><td>日志Id</td><td>标题</td><td>作者</td><td>时间</td><td>分类</td><td>操作</td></tr>";
		if($do == 'search') {
			$where = '';
			if($uid) {
				if($username) {
					if($title) {
						$where = "uid like '%$uid%' or username like '%$username%' or subject like '%$title%'";
					} else {
						$where = "uid like '%$uid%' or username like '%$username%'";
					}
				} else {
					if($title) {
						$where = "uid like '%$uid%' or subject like '%$title%'";
					} else {
						$where = "uid like '%$uid%'";
					}
				}
			} else {
				if($username) {
					if($title) {
						$where = "username like '%$username%' or subject like '%$title%'";
					} else {
						$where = "username like '%$username%'";
					}
				} else {
					if($title) {
						$where = "subject like '%$title%'";
					} else {
						$where = "uid>0";
					}
				}
			}
			$count = DB::result(DB::query("select count(*) from ".DB::table('home_blog')." where $where order by blogid desc"));
			$query = DB::query("select blogid, subject, dateline, username, uid from ".DB::table('home_blog')." where $where order by blogid desc limit $start, $pagesize");
			while($row = DB::fetch($query)) {
				$data = DB::fetch_first("select id, uid, sort, groupid, rectype from ".DB::table('home_recommend')." where uid='".$row['uid']."' and cid='".$row['blogid']."' and groupid='51'");
				$sort = $data['sort'] ? $data['sort'] : '';
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					/*if($v['group_id'] == $data['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {*/
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					//}
				}
				$rectype .= "</select>";
				$str = "<input type='submit' name='submit' value='推荐' style='border:none'>";
				$list .= "<form action='".ADMINSCRIPT."?action=recommend&operation=blog&do=recomm&uid=".$row['uid']."&cid=".$row['blogid']."&gid=51' method='post' name='myformval'><tr id=".$row['blogid']." onmouseover='showcolor(".$row['blogid'].")' onmouseout='clearcolor(".$row['blogid'].")'><td><input type='text' name='sort' value='".$sort."' style='width:50px' /></td><td>".$row['blogid']."</td><td><a href='/home.php?mod=space&uid=".$row['uid']."&do=blog&id=".$row['blogid']."' target='_blank'>".$row['subject']."</td><td>".$row['username']."</td><td>".date('Y-m-d H:i:s', $row['dateline'])."</td><td>".$rectype."</td><td>".$str."</td></tr></form>";
			}
			$thurl = ADMINSCRIPT.'?action=recommend&operation=blog&do=search&uid='.$uid.'&username='.$username.'&title='.$title;
			$multipage = multi($count, $pagesize, $page, $thurl);
		} else {
			/*
			$count = DB::result(DB::query("select count(*) from ".DB::table('home_recommend')." as hr left join ".DB::table('home_blog')." as hb on hb.blogid=hr.cid left join ".DB::table('common_usergroup')." as cu on cu.groupid=hr.groupid where hr.groupid='51'"));
			$query = DB::query("select hr.*, hb.blogid, hb.subject, hb.dateline, hb.username from ".DB::table('home_recommend')." as hr left join ".DB::table('home_blog')." as hb on hb.blogid=hr.cid left join ".DB::table('common_usergroup')." as cu on cu.groupid=hr.groupid where hr.groupid='51'");
			while($row = DB::fetch($query)) {
				$rectype = "<select name='rectype'>";
				foreach($rectypearr as $k=>$v) {
					if($v['group_id'] == $row['rectype']) {
						$rectype .= "<option value='".$v['group_id']."' selected>".$v['group_name']."</option>";
					} else {
						$rectype .= "<option value='".$v['group_id']."'>".$v['group_name']."</option>";
					}
				}
				$rectype .= "</select>";
				$list .= "<form action='admin.php?action=recommend&operation=blog&do=recomm&uid=".$row['uid']."&c=1&gid=51&cid=".$row['blogid']."' method='post' name='form'><tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")'><td><input type='text' name='sort' value='".$row['sort']."' style='width:50px' /></td><td>".$row['blogid']."</td><td><a href='home.php?mod=space&uid=".$row["uid"]."&do=blog&id=".$row['blogid']."' target='_blank'>".$row['subject']."</a></td><td>".$row['username']."</td><td>".date('Y-m-d H:i:s', $row['dateline'])."</td><td>".$rectype."</td><td><a href='admin.php?action=recommend&operation=blog&do=del&id=".$row['id']."&gid=51'>取消推荐</a> | <input type='submit' name='submit' value='重新推荐' style='border:none'></td></tr></form>";
			}
			$multipage = multi($count, $pagesize, $page, 'admin.php?action=recommend&operation=blog');
			*/
		}
		echo $list."</table>".$multipage;
	}
} elseif($operation == 'other') {
	if($_GET['do'] == 'add') {
		$query = DB::query("select * from ".DB::table('recommend_group'));
		$option = "<option value='0'>选择</option>";
		while($row = DB::fetch($query)) {
			$option .= "<option value='".$row['group_id']."'>".$row['group_name']."</option>";
		}
		echo "<form action='".ADMINSCRIPT."?action=recommend&operation=other&do=save' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><tr style='height:30px'><td style='width:10%'>排序</td><td><input type='text' name='sort' style='width:100px' maxlength='20' /></td></tr><tr style='height:30px'><td style='width:10%'>标题</td><td><textarea name='title' rows='5' cols='100'></textarea></td></tr><tr><td>图片</td><td><input type='file' name='img' style='width:300px; border:1px solid #abcdef; maxlength='200' /></td></tr><tr><td>链接</td><td><input type='text' name='url' style='width:300px' maxlength='100' /></td></tr><tr><td>分类</td><td><select name='type' style='widht:160px'>".$option."</select></td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	} elseif($_GET['do'] == 'save') {
		$arr = $_POST;
		$arr['dateline'] = time();
		unset($arr['submit']);
		$file = $_FILES;
		$time = date('Ym');
		$dir = 'uploadfile/focus/'.$time;
		if(!is_dir($dir)) {
			mkdir($dir, 0777);
		}
		chmod($dir, 0777);
		$str = '0123456789abcdefghijklmnopqrstuvwxyz';
		for($i = 1; $i <= 20; $i++) {
			$max = strlen($str);
			$rand = rand(0, $max);
			$num .= substr($str, $rand, '1');
		}
		$tmp_name = $file['img']['tmp_name'];
		$dir = $dir.'/'.$num.'.jpg';
		$move = move_uploaded_file($tmp_name, $dir);
		if(empty($_GET['c'])) {
			$arr['img'] = $move ? $dir : '';
			$row = DB::insert('recommend', $arr);
			if($row) {
				$rid = DB::insert_id();
				$array = array('rm_group_id'=>$arr['type'], 'relevance_id'=>$rid, 'type'=>'other', 'addtime'=>$arr['dateline']);
				DB::insert('recommend_ids', $array);
				cpmsg('添加成功', 'action=recommend&operation=other&do=add');
			} else {
				unlink($dir);
				cpmsg('添加失败', 'action=recommend&operation=other&do=add');
			}
		} else {
			unset($arr['advertid']);
			$arr['img'] = $move ? $dir : ($_POST['imgpath'] ? $_POST['imgpath'] : '');
			unset($arr['imgpath']);
			$row = DB::update('recommend', $arr, array('id'=>$_POST['advertid']));
			DB::query("update ".DB::table('recommend_ids')." set rm_group_id='".$arr['type']."' where relevance_id='".$_POST['advertid']."' and type='other'");
			if($row) {
				cpmsg('修改成功', 'action=recommend&operation=other&do=add');
			} else {
				cpmsg('修改失败', 'action=recommend&operation=other&do=add');
			}
		}
	} elseif($_GET['do'] == 'del') {
		$id = $_GET['id'];
		$arr = DB::fetch_first("select * from ".DB::table('recommend')." where id=".$id);
		$row = DB::query("delete from ".DB::table('recommend')." where id=".$id);
		DB::query("delete from ".DB::table('recommend_ids')." where relevance_id=".$id);
		if($row) {
			unlink($arr['img']);
			cpmsg('删除成功', 'action=recommend&operation=other');
		} else {
			cpmsg('删除失败', 'action=recommend&operation=other');
		}
	} elseif($_GET['do'] == 'edit') {
		$id = $_GET['id'];
		$advert = DB::fetch_first("select * from ".DB::table('recommend')." where id=$id order by id desc");
		$query = DB::query("select * from ".DB::table('recommend_group')." order by group_id desc");
		$option = "<option value='0'>选择</option>";
		while($row = DB::fetch($query)) {
			if($row['group_id'] == $advert['type']) {
				$option .= "<option value='".$row['group_id']."' selected>".$row['group_name']."</option>";
			} else {
				$option .= "<option value='".$row['group_id']."'>".$row['group_name']."</option>";
			}
		}
		echo "<form action='".ADMINSCRIPT."?action=recommend&operation=other&do=save&c=1' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><input type='hidden' name='advertid' value='".$advert['id']."' /><tr style='height:30px'><td style='width:10%'>排序</td><td><input type='text' name='sort' value='".$advert['sort']."' style='width:300px' maxlength='20' /></td></tr><tr style='height:30px'><td style='width:10%'>标题</td><td><textarea name='title' rows='5' cols='100'>".$advert['title']."</textarea></td></tr><tr><td>链接</td><td><input type='text' name='url' value='".$advert['url']."' style='width:300px' maxlength='100' /></td></tr><tr><td>分类</td><td><select name='type' style='widht:160px'>".$option."</select></td></tr><tr><td>图片</td><td><input type='file' name='img' style='width:300px; border:1px solid gray' maxlength='100' /><br /><img src='".$advert['img']."' width='300' height='200' /><input type='hidden' name='imgpath' value='".$advert['img']."' /></td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='修改' style='border:1px solid gray' /></td></tr></table></form>";
	} else {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		$pagesize = 10;
		if($page < 1) $page = 1;
		$start = ($page-1)*$pagesize;
		$multipage = '';

		$str = "<table width='96%'><tr style='height:30px'><td>排序</td><td>标题</td><td>地址</td><td>分类</td><td>缩略图</td><td>操作</td></tr>";
		$count = DB::result(DB::query("select count(*) from ".DB::table('recommend')." as ha left join ".DB::table('recommend_group')." as hat on hat.group_id=ha.type"));
		if($count) {
			$query = DB::query("select ha.*, hat.group_name from ".DB::table('recommend')." as ha left join ".DB::table('recommend_group')." as hat on hat.group_id=ha.type order by ha.sort asc limit $start, $pagesize");
			while($row = DB::fetch($query)) {
				$str .= "<tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")' style='height:30px'><td>".$row['sort']."</td><td>".$row['title']."</td><td>".$row['url']."</td><td>".$row['group_name']."</td><td><img src=".$row['img']." width='150' height='80' /></td><td><a href='".ADMINSCRIPT."?action=recommend&operation=other&do=edit&id=".$row['id']."'>修改</a> | <a href='".ADMINSCRIPT."?action=recommend&operation=other&do=del&id=".$row['id']."'>删除</a></td></tr>";
			}
		}
		$multipage = multi($count, $pagesize, $page, ADMINSCRIPT.'?action=recommend&operation=other');
		echo $str."</table><a href='".ADMINSCRIPT."?action=recommend&operation=other&do=add'>添加推荐</a><br />".$multipage;
	}
} elseif($operation == 'custom') {
	$title = array('标题一', '标题二', '标题三', '标题四', '标题五', '标题六');
	$keyword = array('6'=>'关键词一', '7'=>'关键词二', '8'=>'关键词三', '9'=>'关键词四');
	if($_GET['do'] == 'addtitle') {
		$option = "<select name='type'>";
		foreach($title as $k=>$v) {
			$option .= "<option value='".$k."'>".$v."</option>";
		}
		$option .= "</select>";
		echo "<form action='".ADMINSCRIPT."?action=recommend&operation=custom&do=save&c=title' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><tr style='height:30px'><td style='width:10%'>标题</td><td><input type='text' name='title' /></td></tr><tr><td>链接</td><td><input type='text' name='url' style='width:300px' maxlength='100' /></td></tr><tr><td>分类</td><td>".$option."</td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	} elseif($_GET['do'] == 'addkeyword') {
		$option = "<select name='type'>";
		foreach($keyword as $k=>$v) {
			$option .= "<option value='".$k."'>".$v."</option>";
		}
		$option .= "</select>";
		echo "<form action='".ADMINSCRIPT."?action=recommend&operation=custom&do=save&c=keyword' method='post' enctype='multipart/form-data'>
		<table width='96%; style='border:1px solid gray'>
		<tr style='height:30px'><td style='width:10%'>标题</td><td><input type='text' name='title' /></td></tr>
		<tr><td>关键词一</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>关键词二</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>关键词三</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>关键词四</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>关键词五</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>关键词六</td><td><input type='text' name='keyword[]' style='width:200px' maxlength='100' />&nbsp;&nbsp;&nbsp;&nbsp;链接<input type='text' name='keyurl[]' style='width:200px' maxlength='100' /></td></tr>
		<tr><td>分类</td><td>".$option."</td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
	} elseif($_GET['do'] == 'save') {
		$arr = $_POST;
		$arr['typename'] = ($_GET['c'] == 'keyword') ? $keyword[$arr['type']] : $title[$arr['type']];
		$arr['dateline'] = time();
		unset($arr['submit']);
		if($_GET['c'] == 'keyword') {
			if(empty($_GET['cp'])) {
				$array['keyword'] = $arr['keyword'];
				$array['keyurl'] = $arr['keyurl'];
				unset($arr['keyurl']);
				unset($arr['keyword']);
				for($i = 0; $i < 6; $i++) {
					$arr['keyword'] = $array['keyword'][$i];
					$arr['keyurl'] = $array['keyurl'][$i];
					$arr['flag'] = '1';
					if(!empty($arr['keyurl']) && !empty($arr['keyurl'])){
                        $row = DB::insert('home_custom', $arr);
                    }
				}
				$str = '添加';
			} else {
				$id = $arr['id'];
				$row = DB::update('home_custom', $arr, array('id'=>$arr['id'], 'flag'=>'1'));
				$str = '修改';
			}
		} else {
			$arr['flag'] = '0';
			if(empty($_GET['cp'])) {
				$row = DB::insert('home_custom', $arr);
				$str = '添加';
			} else {
				$id = $arr['id'];
				$row = DB::update('home_custom', $arr, array('id'=>$arr['id'], 'flag'=>'0'));
				$str = '修改';
			}
		}
		if($row) {
			cpmsg($str.'成功', 'action=recommend&operation=custom');
		} else {
			cpmsg($str.'失败', 'action=recommend&operation=custom');
		}
	} elseif($_GET['do'] == 'edit') {
		$id = $_GET['id'];
		$flag = $_GET['c'];
		$custom = DB::fetch_first("select * from ".DB::table('home_custom')." where id=$id and flag=$flag order by id desc");
		$option = "<option value='0'>选择</option>";
		$array = ($flag == 1) ? $keyword : $title;
		$option = "<select name='type'>";
		foreach($array as $k=>$v) {
			if($custom['type'] == $k) {
				$option .= "<option value='".$k."' selected>".$v."</option>";
			} else {
				$option .= "<option value='".$k."'>".$v."</option>";
			}
		}
		$option .= "</select>";
		if($flag == 1) {
			echo "<form action='".ADMINSCRIPT."?action=recommend&operation=custom&do=save&c=keyword&cp=1' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><input type='hidden' name='id' value='".$id."' /><tr style='height:30px'><td style='width:10%'>标题</td><td><input type='text' name='title' value='".$custom['title']."' /></td></tr><tr><td>关键词</td><td><input type='text' name='keyword' style='width:300px' maxlength='100' value='".$custom['keyword']."' /></td></tr><tr><td>链接</td><td><input type='text' name='keyurl' style='width:300px' maxlength='100' value='".$custom['keyurl']."' /></td></tr><tr><td>分类</td><td>".$option."</td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
		} else {
			echo "<form action='".ADMINSCRIPT."?action=recommend&operation=custom&do=save&c=title&cp=1' method='post' enctype='multipart/form-data'><table width='96%; style='border:1px solid gray'><input type='hidden' name='id' value='".$id."' /><tr style='height:30px'><td style='width:10%'>标题</td><td><input type='text' name='title' value='".$custom['title']."' /></td></tr><tr><td>链接</td><td><input type='text' name='url' style='width:300px' maxlength='100' value='".$custom['url']."' /></td></tr><tr><td>分类</td><td>".$option."</td></tr><tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /></td></tr></table></form>";
		}
	} elseif($_GET['do'] == 'del') {
		$row = DB::query("delete from ".DB::table('home_custom')." where id='".$_GET['id']."' and flag='".$_GET['c']."'");
		if($row) {
			cpmsg('删除成功', 'action=recommend&operation=custom');
		} else {
			cpmsg('删除失败', 'action=recommend&operation=custom');
		}
	} else {
		$page = empty($_GET['page']) ? 0 : intval($_GET['page']);
		$pagesize = 15;
		if($page < 1) $page = 1;
		$start = ($page-1)*$pagesize;
		$multipage = '';

		$str = "<table width='96%'><td>标题</td><td>标题链接</td><td>关键词</td><td>关键词链接</td><td>分类</td><td>操作</td></tr>";
		$count = DB::result(DB::query("select count(*) from ".DB::table('home_custom')));
		$query = DB::query("select * from ".DB::table('home_custom')." limit $start, $pagesize");
		while($row = DB::fetch($query)) {
			$row['url'] = !empty($row['url']) ? $row['url'] : '------';
			$row['keyword'] = !empty($row['keyword']) ? $row['keyword'] : '------';
			$row['keyurl'] = !empty($row['keyurl']) ? $row['keyurl'] : '------';
			$str .= "<tr id=".$row['id']." onmouseover='showcolor(".$row['id'].")' onmouseout='clearcolor(".$row['id'].")' style='height:30px'><td>".$row['title']."</td><td>".$row['url']."</td><td>".$row['keyword']."</td><td>".$row['keyurl']."</td><td>".$row['typename']."</td><td><a href='".ADMINSCRIPT."?action=recommend&operation=custom&do=edit&id=".$row['id']."&c=".$row['flag']."'>修改</a> | <a href='".ADMINSCRIPT."?action=recommend&operation=custom&do=del&id=".$row['id']."&c=".$row['flag']."'>删除</a>";
		}
		$multipage = multi($count, $pagesize, $page, ADMINSCRIPT.'?action=recommend&operation=custom');
		echo $str."</table>".$multipage."<br /><a href='".ADMINSCRIPT."?action=recommend&operation=custom&do=addtitle'>添加标题</a>&nbsp;&nbsp&nbsp;&nbsp<a href='".ADMINSCRIPT."?action=recommend&operation=custom&do=addkeyword'>添加关键词</a>";
	}
}
if($do == 'recomm') {
	$uid = $_GET['uid'];
	$arr = DB::fetch_first("select username from ".DB::table('common_member')." where uid='$uid'");
	$arr['uid'] = $uid;
	$arr['username'] = addslashes($arr['username']);
	$arr['sort'] = $_POST['sort'];
	$arr['cid'] = $_GET['cid'];
	$array = $arr['cid'] ? array('uid'=>$uid, 'cid'=>$arr['cid']) : array('uid'=>$uid);
	$arr['groupid'] = ($_GET['gid'] >= 20) ? $_GET['gid'] : '10';
	$arr['rectype'] = $_POST['rectype'];
	$arr['dateline'] = time();
	if(empty($c)) {
		$row = DB::insert('home_recommend', $arr);
		$str = '推荐';
	} else {
		$row = DB::update('home_recommend', $arr, $array);
		$str = '重新推荐';
	}
	if($row) {
		cpmsg($str.'成功', 'action=recommend&operation='.$operation);
	} else {
		cpmsg($str.'失败', 'action=recommend&operation='.$operation);
	}
} elseif($do == 'del' && $operation != 'other' && $operation != 'custom') {
	$cid = $_GET['cid'];
	$uid = $_GET['uid'];
	$groupid = ($_GET['gid'] >= 20) ? $_GET['gid'] : '10';
	$where = $_GET['id'] ? "id='$id'" : "cid='$cid' and uid='$uid'";
	$row = DB::query("delete from ".DB::table('home_recommend')." where $where and groupid='".$groupid."'");
	if($row) {
		cpmsg('取消推荐成功', 'action=recommend&operation='.$operation);
	} else {
		cpmsg('取消推荐失败', 'action=recommend&operation='.$operation);
	}
}
?>
<?php
//add by xgw
$url="action=recommend";
if($operation == 'viewlist') {
	//var_dump($_GET);
	$type=getgpc("type");   //判断是那个页面  blog , video ,member
	$tid=getgpc("tid");

	$limitpage=10;   //每页显示多少个
	$page=empty($_GET["page"])?1:$_GET["page"];  //page是必须的一样的
	$page=trim(intval($page));
    $start = ($page-1)*$limitpage;   //开始的条数


	if($type=='blog'){
		if(!submitcheck('searchsubmit')){
			$sql="SELECT hr.id,hr.uid,hr.sort,hb.blogid, hb.subject, hb.username FROM pre_home_recommend AS hr LEFT JOIN pre_home_blog AS hb ON hb.blogid=hr.cid LEFT JOIN pre_common_usergroup AS cu ON cu.groupid=hr.groupid WHERE hr.rectype=".$tid." AND hr.groupid='51' ORDER BY hr.sort ASC,hr.dateline DESC limit ".$start.",".$limitpage;
			$re=DB::query($sql);
			while ($rw=DB::fetch($re)){
				$erow[]=$rw;
			}
			//echo "<pre>";
			//print_r($erow);
			$theurl = ADMINSCRIPT.'?action=recommend&operation=viewlist&type='.$type."&tid=".$tid; //地址

			//判断总条数
		    $countnum = DB::result(DB::query("SELECT count(0) num FROM pre_home_recommend AS hr LEFT JOIN pre_home_blog AS hb ON hb.blogid=hr.cid LEFT JOIN pre_common_usergroup AS cu ON cu.groupid=hr.groupid WHERE hr.rectype=".$tid." AND hr.groupid='51'"));
		    $disppage = multi($countnum, $limitpage, $page, $theurl);


			if(!empty($erow)){
				showformheader("recommend&operation=viewlist&type=blog");
				showtableheader($rectypearr[$tid]["group_name"]);   //显示表格的第一个tr 的 th
				showsubtitle(array('删除', '排序','博客id' , '标题', '作者'));    //显示表格第二个的标题
				foreach ($erow as $rv ){
					showtablerow('',array('class="td25"','class="td28"', 'class="td26"'),array(
					'<input type="checkbox" value="'.$rv["id"].'" name="delete[]" />',
					'<input type="text" class="txt" name="displayorder['.$rv['id'].']" value="'.$rv['sort'].'" size="3" />',
					$rv["blogid"],
					$rv["subject"],
					$rv["username"],
					));
				}

				showsubmit('searchsubmit', 'submit', 'del');   //带删除的提交按钮
				//showsubmit("searchsubmit");
				showtablefooter();  //显示结束的</table>
				showformfooter();
				echo $disppage;
			}
		}else{
			//删除
			if($_G['gp_delete']) {
				DB::delete('home_recommend', "id IN (".dimplode($_G['gp_delete']).")");
			}
			//更新
			if(is_array($_G['gp_displayorder'])) {
				foreach($_G['gp_displayorder'] as $id => $val) {
					DB::update('home_recommend', array(
						'sort' => $_G['gp_displayorder'][$id],
					), array(
						'id' => $id,
					));
				}
			}
			cpmsg('更新成功', $url, 'succeed');
		}

	}elseif ($type=='member'){
		if(!submitcheck('searchsubmit')){
			$sql="SELECT a.id,a.uid,a.`sort`,a.username FROM `pre_home_recommend` AS a  WHERE a.rectype=".$tid." ORDER BY a.sort ASC,a.dateline DESC limit ".$start.",".$limitpage;
			$re=DB::query($sql);
			while ($rw=DB::fetch($re)){
				$erow[]=$rw;
			}
			//echo "<pre>";
			//print_r($erow);
			$theurl = ADMINSCRIPT.'?action=recommend&operation=viewlist&type='.$type."&tid=".$tid; //地址

			//判断总条数
		    $countnum = DB::result(DB::query("SELECT COUNT(0) AS num FROM `pre_home_recommend` AS a  WHERE a.rectype=".$tid));
		    $disppage = multi($countnum, $limitpage, $page, $theurl);


			if(!empty($erow)){
				showformheader("recommend&operation=viewlist&type=member");
				showtableheader($rectypearr[$tid]["group_name"]);   //显示表格的第一个tr 的 th
				showsubtitle(array('删除', '排序','用户id' ,'用户名', '头像'));    //显示表格第二个的标题
				foreach ($erow as $rv ){
					showtablerow('',array('class="td25"','class="td28"'),array(
					'<input type="checkbox" value="'.$rv["id"].'" name="delete[]" />',
					'<input type="text" class="txt" name="displayorder['.$rv['id'].']" value="'.$rv['sort'].'" size="3" />',
					$rv["uid"],
					$rv["username"],
					'<img src="uc_server/avatar.php?uid='.$rv["uid"].'&size=small" />',
					));
				}

				showsubmit('searchsubmit', 'submit', 'del');   //带删除的提交按钮
				//showsubmit("searchsubmit");
				showtablefooter();  //显示结束的</table>
				showformfooter();
				echo $disppage;
			}
		}else{
			//删除
			if($_G['gp_delete']) {
				DB::delete('home_recommend', "id IN (".dimplode($_G['gp_delete']).")");
			}
			//更新
			if(is_array($_G['gp_displayorder'])) {
				foreach($_G['gp_displayorder'] as $id => $val) {
					DB::update('home_recommend', array(
						'sort' => $_G['gp_displayorder'][$id],
					), array(
						'id' => $id,
					));
				}
			}
			cpmsg('更新成功', $url, 'succeed');

		}
	}elseif ($type=='video'){
		if(!submitcheck('searchsubmit')){
			$sql="SELECT hr.*, hv.title, hvp.images FROM pre_home_recommend AS hr LEFT JOIN pre_home_video AS hv ON hv.vid=hr.cid LEFT JOIN pre_home_videopath AS hvp
 ON hvp.vpid=hv.vpid WHERE hr.rectype={$tid} ORDER BY hr.sort ASC,hr.dateline DESC limit ".$start.",".$limitpage;
			$re=DB::query($sql);
			while ($rw=DB::fetch($re)){
				$erow[]=$rw;
			}
			//echo "<pre>";
			//print_r($erow);
			$theurl = ADMINSCRIPT.'?action=recommend&operation=viewlist&type='.$type."&tid=".$tid; //地址

			//判断总条数
		    $countnum = DB::result(DB::query("SELECT COUNT(0) AS num FROM pre_home_recommend AS hr LEFT JOIN pre_home_video AS hv ON hv.vid=hr.cid LEFT JOIN pre_home_videopath AS hvp ON hvp.vpid=hv.vpid WHERE hr.rectype=".$tid));
		    $disppage = multi($countnum, $limitpage, $page, $theurl);


			if(!empty($erow)){
				showformheader("recommend&operation=viewlist&type=member");
				showtableheader($rectypearr[$tid]["group_name"]);   //显示表格的第一个tr 的 th
				showsubtitle(array('删除', '排序','用户id' ,'用户名','标题','缩略图'));    //显示表格第二个的标题
				foreach ($erow as $rv ){
					showtablerow('',array('class="td25"','class="td28"'),array(
					'<input type="checkbox" value="'.$rv["id"].'" name="delete[]" />',
					'<input type="text" class="txt" name="displayorder['.$rv['id'].']" value="'.$rv['sort'].'" size="3" />',
					$rv["uid"],
					$rv["username"],
					$rv["title"],
					'<img src="'.$rv["images"].'" width="60" height="60">',
					));
				}

				showsubmit('searchsubmit', 'submit', 'del');   //带删除的提交按钮
				//showsubmit("searchsubmit");
				showtablefooter();  //显示结束的</table>
				showformfooter();
				echo $disppage;
			}
		}else{
			//删除
			if($_G['gp_delete']) {
				DB::delete('home_recommend', "id IN (".dimplode($_G['gp_delete']).")");
			}
			//更新
			if(is_array($_G['gp_displayorder'])) {
				foreach($_G['gp_displayorder'] as $id => $val) {
					DB::update('home_recommend', array(
						'sort' => $_G['gp_displayorder'][$id],
					), array(
						'id' => $id,
					));
				}
			}
			cpmsg('更新成功', $url , 'succeed');   //颜色是绿色的，如果不带最后一个参数，颜色是黑色

		}

	}

}
/*
if($operation=='del'){
	if(!submitcheck('searchsubmit')) {
		header("Location:/admin.php?ac=recommend");
	}else{
		$ch=getgpc("ch");

		if(empty($ch)){
			cpmsg("请先选择",$url);
		}else{
			foreach ($ch as $did){
				$flag=DB::delete("home_recommend",array("id"=>$did));
			}
			if($flag){
				cpmsg("删除成功",$url);
			}else{
				cpmsg("删除失败",$url);
			}
		}
	}

}*/


//end by xgw
?>
<script type='text/javascript'>
	function showcolor(id) {
		document.getElementById(id).style.background = 'FAFAFA';
	}
	function clearcolor(id) {
		document.getElementById(id).style.background = '';
	}
</script>