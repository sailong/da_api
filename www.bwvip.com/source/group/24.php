<?php
include 'source/function/function_group.php';

//资料
$userinfo = DB::fetch_first("select m.username, m.credits, mp.realname, mp.resideprovince, mp.residecity, mp.company, mp.site, mp.bio, mc.views, jm.fans_count, jm.follow_count, jm.topic_count from ".DB::table('common_member')." as m left join ".DB::table('common_member_profile')." as mp on mp.uid=m.uid left join ".DB::table('common_member_count')." as mc on mc.uid=m.uid left join jishigou_members as jm on jm.uid=m.uid where m.uid='$uid' order by m.uid desc");

//关注
$query = DB::query("select b.remark, m.uid, m.username from jishigou_buddys as b left join jishigou_members as m on b.buddyid=m.uid where b.uid='$uid' order by b.dateline desc limit 6");
while($row = mysql_fetch_assoc($query)) {
	$buddys[] = $row;
}

//粉丝
$query = DB::query("select b.remark, m.uid, m.username,c.realname from jishigou_buddys as b left join jishigou_members as m on b.uid=m.uid LEFT JOIN pre_common_member_profile c ON m.uid=c.uid where b.buddyid='$uid' order by b.dateline desc limit 9");
while($row = mysql_fetch_assoc($query)) {
    if(!empty($row["realname"])){
        $row["username"]=$row["realname"];
    }
	$fans[] = $row;
}

//相册
$query = DB::query("select albumid, albumname, pic, picnum from ".DB::table('home_album')." where uid='$uid' order by updatetime desc limit 8");
while($row = mysql_fetch_assoc($query)) {
	$albumlist[] = $row;
}

//标签
$query = DB::query("select tag_name from jishigou_user_tag_fields where uid='$uid' order by id desc limit 8");
while($row = mysql_fetch_assoc($query)) {
	$tags[] = $row;
}

//参加的活动
$query = DB::query("select ea.id, ea.title, ea.image from jishigou_event as ea left join jishigou_event_member  as em on ea.id=em.id where ea.postman='$uid' or em.fid='$uid' order by em.id desc limit 6");
while($row = mysql_fetch_assoc($query)) {
	$event[] = $row;
}

//参加的话题
$query = DB::query("select t.id, t.name from jishigou_tag as t left join jishigou_my_topic_tag as mt on mt.tag_id=t.id where mt.user_id='$uid' group by mt.tag_id order by t.dateline desc limit 6");
while($row = mysql_fetch_assoc($query)) {
	$topic[] = $row;
}

//成绩卡
$query = DB::query("select cs.id,cs.sais_id, cs.uid, cs.dateline, cs.member, cs.total_score, cs.fuid, cf.fieldname from ".DB::table('common_score')." as cs left join ".DB::table('common_field')." as cf on cf.uid=cs.fuid where cs.uid='$uid' order by cs.dateline DESC limit 6");
//$i = 1;
while($row = mysql_fetch_assoc($query)) {
	$row['dateline'] = date('Y-m-d', ($row['dateline'] ? $row['dateline'] : '0'));
	$row['realname'] = (strlen($row['fieldname']) < 5) ? $row['fieldname'] : mb_substr($row['fieldname'], 0, 7, 'utf-8');
	//$row['rank'] = $i;
	//$i++;
	$spacescore[$row["fuid"]][] = $row;
}

//博客
$query = DB::query("select blogid, uid, username, subject from ".DB::table('home_blog')."  where uid=$uid order by dateline desc limit 10");
while($value = DB::fetch($query)) {
	$bestblog[] = $value;
}

//视频
$query = DB::query("select hv.vid, hv.videoid, hv.videotypeid, hv.uid, hv.username, hv.title, hvp.images from ".DB::table('home_video')." as hv left join ".DB::table('home_videopath')." as hvp on hvp.vpid=hv.vpid where hv.uid='$uid' group by hv.videoid order by hv.dateline desc limit 6");
while($row = mysql_fetch_assoc($query)) {
	$row['dateline'] = date('Y-m-d', ($row['dateline'] ? $row['dateline'] : '0'));
	$row['title'] = strlen($row['title']) <= 9 ? $row['title'] : mb_substr($row['title'], '0', '5', 'utf-8');
	$spacevideo[] = $row;
}

//赞助商
$query = DB::query("select name, href, logo from pre_zanzhushang where uid=$uid order by seq asc limit 4");
while($row = DB::fetch($query)){
    $sponsor[] = $row;
}

//参加的赛事
//不支持用in,NND
//$saishi = DB::query("SELECT b.uid,b.realname,b.field3 FROM pre_common_member_profile b WHERE uid IN(SELECT groupid FROM pre_home_saishi_csqy WHERE userid=1686)");
//先取出来参加过的赛事，然后循环赛事用户取数据
$saishi=DB::query("SELECT groupid FROM pre_home_saishi_csqy WHERE userid=".$uid." order by  id desc");
while($rows = DB::fetch($saishi)){
    $saishirow[] = $rows;
}
//var_dump($saishirow);
if(count($saishirow)>=1){
    $allarr=array();
    foreach($saishirow as $va){
        $rk=DB::query("SELECT b.uid,b.realname,b.field3 FROM pre_common_member_profile b WHERE uid=".$va["groupid"]);
        $rkarr=DB::fetch($rk);
        $allarr[]=$rkarr;
    }
}
//var_dump($allarr);


//是否关注
$row = DB::fetch_first("select id from jishigou_buddys where uid='".$_G['uid']."' and buddyid='$uid' limit 1");
if($row) {
	$guanzhu['buddy'] = '1';
} else {
	$guanzhu['buddy'] = '0';
}

//左侧广告
$left=DB::query("SELECT id,adverturl,imglogo FROM ".DB::table("home_advert")." WHERE id=25");
while($rowl=DB::fetch($left)){
    $leftarr[$rowl["id"]]=$rowl;
}

//球星参加的赛事下面的 成绩卡  【上面成绩卡数据的重组】
 foreach($spacescore as $value){
      foreach($value as $k => $v){
          $sais_score_list[$v['sais_id']][] = $v;
      }
  }

?>