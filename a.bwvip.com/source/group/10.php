<?php
include 'source/function/function_group.php';

if($space['groupid'] < 20) {
	//资料（用户名，积分，真实姓名，现居地，公司，官网，简介，访问量，粉丝数，关注数，话题数，加v）
	$userinfo = DB::fetch_first("select m.username, m.credits, mp.realname, mp.resideprovince, mp.residecity, mp.company, mp.site, mp.bio, mc.views, jm.fans_count, jm.follow_count, jm.topic_count, jm.validate from ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as mp on mp.uid=m.uid left join ".DB::table('common_member_count')." as mc on mc.uid=m.uid left join jishigou_members as jm on jm.uid=m.uid where m.uid='$zyuid' order by m.uid desc");

    //所属俱乐部和球队
    $jlb=DB::fetch_first("SELECT jlbname,qdname FROM pre_common_member_profile WHERE uid=".$uid);
    $jlb["jlbname"]=cutstr($jlb["jlbname"],16,'');
    $jlb["qdname"]=cutstr($jlb["qdname"],16,'');

	//人气排名
	$query = DB::query("select uid, views from ".DB::table('common_member_count')." order by views desc");
	while($row = mysql_fetch_assoc($query)) {
		$hits[] = $row;
	}
	foreach($hits as $k=>$v) {
		if($v['uid'] == $uid) {
			$visitrank = $k+1;
			break;
		}
	}

	//博客
	$query = DB::query("select blogid, uid, username, subject from ".DB::table('home_blog')."  where uid=$uid order by dateline desc limit 5");
	while($value = DB::fetch($query)) {
	   $value["subject"]=utf8Substr($value["subject"],0,14);
		$blog[] = $value;
	}

	//访客
	$query = DB::query("select hv.uid, hv.vuid, hv.vusername, cmp.realname, jm.validate from ".DB::table('home_visitor')." hv left join ".DB::table('common_member_profile')." cmp on cmp.uid=hv.vuid left join jishigou_members jm on jm.uid=hv.vuid where hv.uid='$uid' order by hv.dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		//$row['vusername'] = strlen($row['vusername']) <= 9 ? $row['vusername'] : mb_substr($row['vusername'], '0', '5', 'utf-8');
		$row['vusername'] = $row['realname'] ? ((strlen($row['realname']) <= 9) ? $row['realname'] : mb_substr($row['realname'], '0', '5', 'utf-8')) : ((strlen($row['vusername']) <= 9)? $row['vusername'] : mb_substr($row['vusername'], '0', '5', 'utf-8'));
		$visit[] = $row;
	}

	//勋章
	$medals = DB::fetch_first("select medals from ".DB::table('common_member_field_forum')." where uid='$uid' limit 1");
	$arr = explode('	', $medals['medals']);
	foreach($arr as $val) {
		$query = DB::query("select image, name from ".DB::table('forum_medal')." where medalid='$val' limit 1");
		while($row = mysql_fetch_assoc($query)) {
			$row['name'] = strlen($row['name']) <= 9 ? $row['username'] : mb_substr($row['name'], '0', '5', 'utf-8');
			$medal[] = $row;
		}
	}

	//标签
	$query = DB::query("select tag_name from jishigou_user_tag_fields where uid='$uid' order by id desc limit 8");
	while($row = mysql_fetch_assoc($query)) {
		$tags[] = $row;
	}

	//群组
	//$query = DB::query("select f.fid, f.name, ff.icon, ff.foundername from ".DB::table('forum_groupuser')." g left join ".DB::table('forum_forum')." f on f.fid=g.fid left join ".DB::table("forum_forumfield")." ff on ff.fid=f.fid where g.uid='$uid' order by g.joindateline desc limit 8");
	$query = DB::query("select jq.qid, jq.name, jq.icon,c.realname, jq.founderuid, jq.foundername, jq.member_num from jishigou_qun_user as jqu left join jishigou_qun as jq on jq.qid=jqu.qid LEFT JOIN pre_common_member_profile c ON jqu.uid=c.uid where jqu.uid='$uid' order by jqu.join_time desc limit 6");
	while($group = mysql_fetch_assoc($query)) {
	   if(!empty($group["realname"])){
	       $group["foundername"]=$group["realname"];
	   }
		$grouplist[] = $group;
	}

	//参加的活动
	$query = DB::query("select ea.id, ea.title, ea.image from jishigou_event as ea left join jishigou_event_member  as em on ea.id=em.id where ea.postman='$uid' or em.fid='$uid' order by em.id desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$row['title'] = strlen($row['title']) <= 9 ? $row['title'] : mb_substr($row['title'], '0', '5', 'utf-8');
		$event[] = $row;
	}

	//参加的话题
	$query = DB::query("select t.id, t.name from jishigou_tag as t left join jishigou_my_topic_tag as mt on mt.tag_id=t.id where mt.user_id='$uid' group by mt.tag_id order by t.dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$topic[] = $row;
	}
function getstar($type) {
	$recomond = DB::query("select hr.uid, hr.username, cmp.realname, cmp.field3 ,cmp.bio,m.nickname from ".DB::table('home_recommend')." hr left join ".DB::table('common_member_profile')." as cmp on cmp.uid=hr.uid left join jishigou_members as m on hr.uid=m.uid where hr.rectype='".$type."' order by hr.sort asc limit 6");
	while($row = DB::fetch($recomond)) {

		/*置顶博客*/
$zdstickblogs = DB::result_first("select stickblogs from ".DB::table('common_member_field_home')."  where uid='".$row['uid']."' ");
$zdblg=explode(',', $zdstickblogs); //取第一个blogid
	if($zdstickblogs) {
		$zdbloginfo = DB::fetch_first("select pic,message,blogid from ".DB::table('home_blogfield')."  where uid='".$row['uid']."'  and blogid =$zdblg[0]  limit 1");}
		 else
		 {
	$zdbloginfo = DB::fetch_first("select pic,message,blogid from ".DB::table('home_blogfield')."  where uid='".$row['uid']."'  order by blogid desc limit 1");}
	if($zdbloginfo['blogid']){
	$zdsblogsubject = DB::result_first("select subject from ".DB::table('home_blog')."  where blogid='".$zdbloginfo['blogid']."' ");
	}
$zdblogs=$zdbloginfo['message'];
$blogid=$zdbloginfo['blogid'];
$zdblogs = preg_replace( "@<script(.*?)</script>@is", "", $zdblogs );
$zdblogs = preg_replace( "@<iframe(.*?)</iframe>@is", "", $zdblogs );
$zdblogs = preg_replace( "@<style(.*?)</style>@is", "", $zdblogs );
$zdblogs = preg_replace( "@<(.*?)>@is", "", $zdblogs );

        $row['zdblog'] =$zdblogs;
        $row['blogid'] =$blogid;

		$stararr[] = $row;
	}
	return $stararr;
}
$club = getstar('25');


	//关注
	$query = DB::query("select DISTINCT b.remark, m.uid, m.username,m.nickname from jishigou_buddys as b left join jishigou_members as m on b.buddyid=m.uid where b.uid='$uid' AND m.uid != '' order by b.dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$row['nickname'] = strlen($row['nickname']) <= 6 ? $row['nickname'] : mb_substr($row['nickname'], '0', '5', 'utf-8');
		$buddys[] = $row;
	}

	//粉丝
	$query = DB::query("select b.remark, m.uid, m.username,m.nickname from jishigou_buddys as b left join jishigou_members as m on b.uid=m.uid where b.buddyid='$uid' AND m.`uid` != '' AND m.uid !=1 order by b.dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$row['nickname'] = strlen($row['nickname']) <= 6 ? $row['nickname'] : mb_substr($row['nickname'], '0', '5', 'utf-8');
		$fans[] = $row;
	}

	//相册
	$query = DB::query("select albumid, albumname, pic, picnum from ".DB::table('home_album')." where uid='$uid' order by updatetime desc limit 9");
	while($row = mysql_fetch_assoc($query)) {
	   $row["albumname"]=utf8Substr($row["albumname"],0,5);
		$albumlist[] = $row;
	}

	//视频
	$query = DB::query("select hv.vid, hv.videoid, hv.videotypeid, hv.uid, hv.username, hv.title, hvp.* from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hv.uid='$uid' group by hv.videoid order by hv.dateline desc limit 6");
	while($row = mysql_fetch_assoc($query)) {
		$row['dateline'] = date('Y-m-d', ($row['dateline'] ? $row['dateline'] : '0'));
		$row['title'] = strlen($row['title']) <= 9 ? $row['title'] : mb_substr($row['title'], '0', '5', 'utf-8');
		$spacevideo[] = $row;
	}

	//成绩卡
	$scorerow = DB::fetch_first("select * from ".DB::table('common_score')." where uid='$uid' order by dateline desc");

    /*统计数据*/
    $gz=DB::query("SELECT COUNT(0) num FROM jishigou_buddys WHERE uid=".$uid);  //关注数
    $gznum=DB::fetch($gz);
    //var_dump($gznum);
    if(empty($gznum["num"])){
        $gznum["num"]="0";
    }
    $fs=DB::query("SELECT COUNT(0) num FROM jishigou_buddys WHERE buddyid=".$uid);   //粉丝数
    $fsnum=DB::fetch($fs);
    if(empty($fsnum["num"])){
        $fsnum["num"]="0";
    }
    $frend=DB::query("SELECT COUNT(0) num FROM pre_home_friend WHERE uid=".$uid);   //好友数
    $frendnum=DB::fetch($frend);
    //var_dump($frendnum);
    if(empty($frendnum["num"])){
        $frendnum["num"]="0";
    }
    $qz=DB::query("SELECT COUNT(0) num FROM jishigou_qun_user AS jqu LEFT JOIN jishigou_qun AS jq ON jq.qid=jqu.qid WHERE jqu.uid=".$uid);   //群组数
    $qznum=DB::fetch($qz);
    if(empty($qznum["num"])){
        $qznum["num"]="0";
    }
    $xc=DB::query("SELECT COUNT(0) num FROM pre_home_album WHERE uid=".$uid);   //相册数
    $xcnum=DB::fetch($xc);
    //var_dump($xcnum);
    if(empty($xcnum["num"])){
        $xcnum["num"]="0";
    }
    $vid=DB::query("SELECT COUNT(0) num FROM pre_home_video WHERE uid=".$uid);   //总视频数
    $vidnum=DB::fetch($vid);
    if(empty($vidnum["num"])){
       $vidnum["num"]="0";
    }
    $cjk=DB::query("SELECT COUNT(0) num FROM pre_common_score WHERE uid=".$uid);    //成绩卡数
    $cjknum=DB::fetch($cjk);
    //var_dump($cjknum);
    if(empty($cjknum["num"])){
        $cjknum["num"] = "0";
    }
}

//是否关注
$row = DB::fetch_first("select id from jishigou_buddys where uid='".$_G['uid']."' and buddyid='$uid' limit 1");
if($row) {
	$guanzhu['buddy'] = '1';
} else {
	$guanzhu['buddy'] = '0';
}

//*********截取中文字符
function utf8Substr($str, $from, $len)
{
   return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
   '$1',$str);
}

//左侧广告
$query = DB::query("select id, advertname, adverturl, imglogo from pre_home_advert where adverttype='6' limit 2");
while($row = DB::fetch($query)){
    $personadvert[] = $row;
}
//长打球场
$usf=DB::query("SELECT b.fieldname,b.uid FROM `pre_home_self_feild` AS a LEFT JOIN pre_common_field AS b ON a.fid=b.id WHERE a.uid={$uid} ORDER BY seq ASC");
while($urow=DB::fetch($usf)){
    $urow["fieldname"]=cutstr($urow["fieldname"],34,'');
    $uarr[]=$urow;
}


/*查看自己是否是教练*/
$is_jl = DB::result_first("select * from ".DB::table("home_apply")." WHERE uid = ".$_G['uid']." and applytype=1");

if($_G['gp_logaction']=='logout'){
    clearcookies();
    showmessage("正在跳转..",'member.php?mod=register&fuid='.$_G['gp_fuid']);
}



function clearcookies() {
    global $_G;
    foreach($_G['cookie'] as $k => $v) {
        if($k != 'widthauto') {
            dsetcookie($k);
        }
    }
    $_G['uid'] = $_G['adminid'] = 0;
    $_G['username'] = $_G['member']['password'] = '';
}

?>