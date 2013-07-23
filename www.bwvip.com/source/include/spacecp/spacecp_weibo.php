<?php
$option = $_GET['op'];
$do = $_GET['do'];




//微博
if($option == 'mywb') {

	$username = DB::result_first( " select `username` from ".DB::table('common_member')." where uid=$uid" );
	$url = "weibo/index.php?mod=$username&fuid=$uid";
}
//设置标签
if($option == 'tag') {
	$tag = $_GET['tag'];
	if($tag) {
		$url = 'weibo/index.php?mod=search&code=usertag&fuid='.$uid.'&usertag='.$tag;
	} else {
		$url = 'weibo/index.php?mod=user_tag&fuid='.$uid.'';
	}
}
//群
if($option == 'qun') {
	$qid = $_GET['qid'];
	if($qid) {
		$url = 'weibo/index.php?mod=qun&fuid='.$uid.'&qid='.$qid;
	} else {
		//$url = 'weibo/index.php?mod=qun';
        $url = "weibo/index.php?mod=topic&code=qun";
	}
}
//活动
if($option == 'event') {
	$eid = $_GET['eid'];
	if($eid) {
		$url = 'weibo/index.php?mod=event&code=detail&fuid='.$uid.'&id='.$eid;
	} else {
		$url = 'weibo/index.php?mod=event&code=myevent&fuid='.$uid.'&type=part&uid='.$uid;
	}
}
//话题
if($option == 'topic') {
	$tcode = $_GET['tcode'];
	if($tcode) {
		$url = 'weibo/index.php?mod=tag&fuid='.$uid.'&code='.$tcode;
	} else {
		$url = 'weibo/index.php?mod='.$userinfo['username'].'&fuid='.$uid.'';
	}
}
//@我
if($option == 'refer') {
	$url = 'weibo/index.php?mod=topic&code=myat&fuid='.$uid.'';
}
//评论我的
if($option == 'mycomment') {
	$url = 'weibo/index.php?mod=topic&code=mycomment&fuid='.$uid.'';
}
//我评论的
if($option == 'tocomment') {
	$url = 'weibo/index.php?mod=topic&code=tocomment&fuid='.$uid.'';
}
//收藏我的
if($option == 'favoritemy') {
	$url = 'weibo/index.php?mod=topic&code=favoritemy&fuid='.$uid.'';
}
//我收藏的
if($option == 'myfavorite') {
	$url = 'weibo/index.php?mod=topic&code=myfavorite&fuid='.$uid.'';
}
//私信
if($option == 'list') {
	$url = 'weibo/index.php?mod=pm&code=list&fuid='.$uid.'';
}


//关注
if($option == 'follow') {
	if($userinfo['follow_count']) {
		$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=follow&fuid='.$uid.'';
	} else {
		$url = 'weibo/index.php?mod=topic&code=top&fuid='.$uid.'';
	}
}
//粉丝
if($option == 'fans') {
	$url = 'weibo/index.php?mod='.$userinfo['username'].'&code=fans';
}
//投票
if($option == 'vote') {
	if($do == 'view') {
		$url = 'weibo/index.php?mod=vote&view=me&fuid='.$uid.'';
	} elseif($do == 'new') {
		$url = 'weibo/index.php?mod=vote&view=me&filter=new_update&fuid='.$uid.'&uid='.$_G['uid'];
	}else{
        $url = 'weibo/index.php?mod=vote&code=create&fuid='.$uid.'';
    }
}
//收藏
if($option == 'favorite') {
	$url = 'weibo/index.php?mod=topic&code=myfavorite&fuid='.$uid.'';
}
//评论
if($option == 'discuss') {
	$url = 'weibo/index.php?mod=topic&code=tocomment&fuid='.$uid.'';
}
//邀请
if($option == 'invite') {
	$url = 'weibo/index.php?mod=profile&code=invite&fuid='.$uid.'';
}
//私信
if($option == 'letter') {
	$url = 'weibo/index.php?mod=pm&code=list&fuid='.$uid.'';
}
if($option == 'master') {
	$url = 'weibo/index.php?mod=topic&code=top&fuid='.$uid.'';
}
//热门话题
if($option == 'httopic') {
	$url = 'weibo/index.php?mod=tag&fuid='.$uid.'';
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



//

$templates='home/spacecp_weibo';

include_once(template($templates));
?>
