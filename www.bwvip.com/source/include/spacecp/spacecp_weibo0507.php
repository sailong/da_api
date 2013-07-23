<?php
$option = $_GET['op'];
$do = $_GET['do'];

//微博
if($option == 'mywb') {
	$url = 'weibo/index.php';
}
//设置标签
if($option == 'tag') {
	$tag = $_GET['tag'];
	if($tag) {
		$url = 'weibo/index.php?mod=search&code=usertag&usertag='.$tag;
	} else {
		$url = 'weibo/index.php?mod=user_tag';
	}
}
//群
if($option == 'qun') {
	$qid = $_GET['qid'];
	if($qid) {
		$url = 'weibo/index.php?mod=qun&qid='.$qid;
	} else {
		//$url = 'weibo/index.php?mod=qun';
		$url = 'weibo/index.php?mod=qun&code=profile';
	}
}
//活动
if($option == 'event') {
	$eid = $_GET['eid'];
	if($eid) {
		$url = 'weibo/index.php?mod=event&code=detail&id='.$eid;
	} else {
		$url = 'weibo/index.php?mod=event&code=myevent&type=part&uid='.$uid;
	}
}
//话题
if($option == 'topic') {
	$tcode = $_GET['tcode'];
	if($tcode) {
		$url = 'weibo/index.php?mod=tag&code='.$tcode;
	} else {
		$url = 'weibo/index.php?mod='.$userinfo['username'];
	}
}
//@我
if($option == 'refer') {
	$url = 'weibo/index.php?mod=topic&code=myat';
}
//关注
if($option == 'follow') {
	if($userinfo['follow_count']) {
		$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=follow';
	} else {
		$url = 'weibo/index.php?mod=topic&code=top';
	}
}
//粉丝
if($option == 'fans') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=fans';
}
//投票
if($option == 'vote') {
	if($do == 'view') {
		$url = 'weibo/index.php?mod=vote&view=me';
	} else {
		$url = 'weibo/index.php?mod=vote&code=create';
	}
}
//收藏
if($option == 'favorite') {
	$url = 'weibo/index.php?mod=topic&code=myfavorite';
}
//评论
if($option == 'discuss') {
	$url = 'weibo/index.php?mod=topic&code=tocomment';
}
//邀请
if($option == 'invite') {
	$url = 'weibo/index.php?mod=profile&code=invite';
}
//私信
if($option == 'letter') {
	$url = 'weibo/index.php?mod=pm&code=list';
}
if($option == 'master') {
	$url = 'weibo/index.php?mod=topic&code=top';
}
//热门话题
if($option == 'httopic') {
	$url = 'weibo/index.php?mod=tag';
}

if($option == 'bound') {
	if($do == 'bound') {
		$cardphone = $_POST['phone'];
		$cardcode = $_POST['code'];
		$card = DB::fetch_first('select cardid from '.DB::table('common_member_card')." where cardphone='$cardphone' and cardcode='$cardcode'");
		if($card['cardid']) {
			$row = DB::query('update '.DB::table('common_member')." set bound='$card[cardid]' where uid='$uid'");
			if($row) {
				showmessage('操作成功', 'home.php?mod=spacecp&ac=weibo&op=bound');
			} else {
				showmessage('操作失败', 'home.php?mod=spacecp&ac=weibo&op=bound');
			}
		}
	} elseif($do == 'clear') {
		$row = DB::query('update '.DB::table('common_member')." set bound=0 where uid='$uid'");
		if($row) {
			showmessage('操作成功', 'home.php?mod=spacecp&ac=weibo&op=bound');
		} else {
			showmessage('操作失败', 'home.php?mod=spacecp&ac=weibo&op=bound');
		}
	} else {
		$arr = DB::fetch_first("select cmc.cardcode, cmc.cardphone, cm.bound from ".DB::table('common_member_card')." as cmc left join ".DB::table('common_member')." as cm on cm.bound=cmc.cardid where cm.uid='$uid'");
	}
}

$templates='home/spacecp_weibo';

include_once(template($templates));    
?>