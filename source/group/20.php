<?php
if($uid=='1000183'){
    header("Location:/");
    exit;
}
include 'source/function/function_group.php';
	$query = DB::fetch_first("SELECT uid, fans_count,follow_count,topic_count,username FROM jishigou_members where uid=$uid");


			//var_dump($query);
			$username=$query['username'];
			$fans_count=empty($query['fans_count'])?'0':$query['fans_count'];
			$follow_count=empty($query['follow_count'])?'0':$query['follow_count'];
			$topic_count=empty($query['topic_count'])?'0':$query['topic_count'];	
			
			$fans='weibo/index.php?mod='.$username.'&code=fans';
			$fansurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($fans);
			
			$follow='weibo/index.php?mod='.$username.'&code=follow';
			$followurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($follow);
			
			$wbs='weibo/';
			$wbsurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbs);
			
			$qunmor='weibo/index.php?mod=qun';
			$qunmorurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($qunmor);

$wbgl=base64_encode('weibo/index.php');//微博管理
$wbhd='weibo/index.php?mod=event&code=myevent&uid='.$uid.'';//微博活动
$wbhdurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbhd);
$wbfqhd='weibo/index.php?mod=event&code=pevent';//微博发起活动
$wbfqhdurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbfqhd);
$wbtag='weibo/index.php?mod=user_tag';//微博标签
$wbtagurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbtag);

unset($query);

//友情链接	
	$query = DB::query("select * from ".DB::table('common_spacelink')." where uid='".$uid."' order by id desc limit 3");
	while($list = mysql_fetch_assoc($query)) {
		$spacelink[] = $list;
	}		
 	
$query = DB::query("SELECT b.uid, b.realname,a.username,b.field1,b.field2,b.field3,a.credits FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 while($value = DB::fetch($query)) {		
		$realname=$value['realname'];		
		$username=$value['username'];	
		$qiyename=$field1=$value['field1'];
		
		if(strlen($field1)==0)	
		{ 
		  $field1=$realname;
		  if(strlen($realname)==0)	
		  { $field1=$username;}	
		}				
		$field2=utf8Substr($value['field2'],0,150);
		$bio=utf8Substr($value['field2'],0,150);	  //简介			
		$field3=$value['field3'];
		$credits=$value['credits'];
		}
 
$query = DB::query("SELECT blogid,uid, username, subject FROM ".DB::table('home_blog')."  where uid={$uid} ORDER BY dateline DESC limit 5");
	 while($value = DB::fetch($query)) {
	   $value["subject"]=utf8Substr($value["subject"],0,14);
			$ubloglst[] = $value;
		}	
//会员
$yglist = array();		
$sql="SELECT a.compid,a.userid,b.realname,c.username FROM ".DB::table('guanxi')." as a INNER JOIN ".DB::table('common_member_profile')." as b ON a.userid=b.uid  INNER JOIN ".DB::table('common_member')." as c  ON c.uid=b.uid   where a.compid=$uid and (a.iscomp=1 OR a.isuser=1) limit 0,10";
$query = DB::query($sql); 
	 while($value = DB::fetch($query)) {
			$yglist[] = $value;
		}	
		
		 
$album=DB::query("SELECT albumid,albumname,albumname as dname, username, picnum, pic FROM ".DB::table('home_album')." WHERE uid ={$uid} ORDER BY dateline DESC LIMIT 0,6 ");
while($value = DB::fetch($album)) {
	 $value["dname"]=utf8Substr($value["dname"],0,6);
	$albumre[] = $value;
}	 
//视频

$videoqu=DB::query("SELECT v.vid,v.title,v.title as sname,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v 
										inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vid DESC limit 0,6");
while($value = DB::fetch($videoqu)) {
    $value["sname"]=utf8Substr($value["sname"],0,5); 
	$videolft[] = $value;
}	  
//群组
 
	//$query = DB::query("select qid, name,icon,founderuid,foundername,member_num,'' as wburl from jishigou_qun where founderuid={$uid}  limit 8");
    $query = DB::query("SELECT a.qid,a.name,a.icon,a.founderuid,a.foundername,a.member_num,b.`realname` FROM jishigou_qun AS a LEFT JOIN ".DB::table("common_member_profile")." AS b ON a.`founderuid`=b.`uid` WHERE a.founderuid={$uid} LIMIT 8");
    
	while($group = mysql_fetch_assoc($query)) {
	   if(!empty($group["realname"])){
	       $group["foundername"]=$group["realname"];
	   }
		$grouplist[] = $group;
	} 
	if($grouplist) {
	foreach($grouplist as $key=>$value) {	 	 
	$url=base64_encode('weibo/index.php?mod=qun&qid='.$grouplist[$key]['qid']);
	$grouplist[$key]['wburl'] = 'home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.$url;	 
	}
}
	
	
	
//参加的话题
/*
	$query = DB::query("select content from jishigou_topic where uid='$uid' order by dateline desc limit 3");
	$str = '';
	while($row = mysql_fetch_assoc($query)) {
		$str .= $row['content'];
	}
	if($str) {
		$num = substr_count($str, '#')/2;
		for($i = 0; $i < $num; $i++) {
			$start = strpos($str, '#');
			$end = strpos($str, '#', $start+1);
			$len = $end - $start;			 
			$tag .= substr($str, $start, $len+1).',';
			$str = substr($str, $end+1);
		}
		
		$tag=str_replace("#","", $tag);
		$topic = array_unique(explode(',', $tag));
	} else {
		$topic = '';
	} 
	$i=0;
	if($topic) {
	foreach ($topic as $a)
	  {
		  $ur=base64_encode('weibo/index.php?mod=tag&code='.$a);
		  $url="<a href='home.php?mod=space&uid=$uid&do=action&act=weibo&wburl=$ur' >".$a."</a>"; 			
		  $topic[$i]=$url; 
		  
	$i=$i+1;
      } 

	}*/
    $query = DB::query("select t.id, t.name from jishigou_tag as t left join jishigou_my_topic_tag as mt on mt.tag_id=t.id where mt.user_id='$uid' group by mt.tag_id order by t.dateline desc limit 3");
	while($row = mysql_fetch_assoc($query)) {
		$topic[] = $row;
	}
//var_dump($topic); 
 
//标签 
$taglst=DB::query("SELECT * FROM jishigou_user_tag_fields where  uid='$uid' limit 0,6 ");
while($value = DB::fetch($taglst)) {
	$tagblglist[] = $value;
} 
if($tagblglist) {
	foreach($tagblglist as $key=>$value) {	 	 
	$url=base64_encode('weibo/index.php?mod=search&code=usertag&usertag='.$tagblglist[$key]['tag_name']);
	$tagblglist[$key]['tag_url'] = 'home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.$url;	 
	}
}
//*********截取中文字符
function utf8Substr($str, $from, $len)
{
   return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$from.'}'.
   '((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,'.$len.'}).*#s',
   '$1',$str);
}

//读取logo
$list=DB::query("select logo from ".DB::table("qiye_logo")." where uid=".$uid);
$listre=DB::fetch($list);
$listpath="static/space/qiye_logo/".$listre["logo"];
//echo $listpath;
//var_dump($listre);
if(empty($listre["logo"])){
    $listpath="";
}
//左侧广告
$left=DB::query("SELECT id,adverturl,imglogo FROM ".DB::table("home_advert")." WHERE id=21 OR id=22");
while($rowl=DB::fetch($left)){
    $leftarr[$rowl["id"]]=$rowl;
}

if($uid=='1899475'){
	function weiyuanhui($tid,$uid){
		$sql="SELECT a.recomid,b.realname FROM pre_dazheng_weiyuan AS a LEFT JOIN pre_common_member_profile AS b ON a.`recomid`=b.`uid` WHERE a.flag={$tid} AND a.uid={$uid} ORDER BY a.dateline DESC limit 6";
		$re=DB::query($sql);
		while($row=DB::fetch($re)){
			$endrow[]=$row;
		}
		return $endrow;
	}
	
	//诚信委员会
	$cxarr=weiyuanhui('1',$uid);
	//var_dump($cxarr);
	//观察团
	$gctarr=weiyuanhui('2',$uid);
	//var_dump($gctarr);

	//判断是不是会员
	$ishuiyuan=DB::fetch_first("SELECT iscomp FROM pre_guanxi WHERE userid={$_G["uid"]} AND compid=".$uid);
	//var_dump($ishuiyuan);
	if($ishuiyuan["iscomp"]=='1'){
		$hystr="yes";
	}elseif($ishuiyuan["iscomp"]=='0'){
		$hystr="shenhe";
	}else{
		$hystr="no";
	}

}



?>