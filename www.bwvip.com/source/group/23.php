<?php
include 'source/function/function_group.php';
$query = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members  where uid=$uid");
		while($value=DB::fetch($query)) { 
		$username=$value['username'];
		$fans_count=$value['fans_count'];
		$follow_count=$value['follow_count'];
		$topic_count=$value['topic_count'];	
		
$fans='weibo/index.php?mod='.$username.'&code=fans';
$fansurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($fans);

$follow='weibo/index.php?mod='.$username.'&code=follow';
$followurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($follow);

$wbs='weibo/';
$wbsurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($wbs);
 

}
$wbgl=base64_encode('weibo/index.php');//微博管理
$wbhd=base64_encode('weibo/index.php?mod=event&code=myevent&uid='.$uid.'');//微博活动
$wbfqhd=base64_encode('weibo/index.php?mod=event&code=pevent');//微博发起活动
$wbtag=base64_encode('weibo/index.php?mod=user_tag');//微博标签
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
		$field2=$value['field2'];		
		$bio=$value['field2'];				
		$field3=$value['field3'];
		$credits=$value['credits'];
		}
//博客
$query = DB::query("SELECT blogid,uid, username, subject FROM ".DB::table('home_blog')."  where uid=$uid limit 5");
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
		
		 
$album=DB::query("SELECT albumid,albumname, username, picnum, pic FROM ".DB::table('home_album')." WHERE uid ={$uid} LIMIT 0,6 ");
while($value = DB::fetch($album)) {
	$albumre[] = $value;
}	 
//视频

$videoqu=DB::query("SELECT v.vid,v.title,v.uid,v.dateline,v.recomm,v.content,vp.* FROM ".DB::table('home_video')." v inner join ".DB::table('home_videopath')." vp ON vp.vpid = v.vpid WHERE v.uid='$uid' ORDER BY v.vid DESC limit 0,6");
while($value = DB::fetch($videoqu)) {
    $value["title"]=utf8Substr($value["title"],0,5);
	$videolft[] = $value;
}	 

//群组
 
	$query = DB::query("select qid, name,icon,founderuid,foundername,member_num,'' as wburl from jishigou_qun where founderuid={$uid}  limit 8");
	while($group = mysql_fetch_assoc($query)) {  
		$grouplist[] = $group;
	} 
	if($grouplist) {
	foreach($grouplist as $key=>$value) {	 	 
	$url=base64_encode('weibo/index.php?mod=qun&qid='.$grouplist[$key]['qid']);
	$grouplist[$key]['wburl'] = 'home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.$url;	 
	}
}
	
	
	
//参加的话题
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
		    //$tag='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode('weibo/index.php?mod=tag&code='.$tag);;
			$tag .= substr($str, $start, $len+1).',';
			$str = substr($str, $end+1);
		}
		
		$tag=str_replace("#","", $tag);
		$topic = array_unique(explode(',', $tag));
	} else {
		$topic = '';
	}	  
//var_dump($topic); 
 
//标签 
$taglst=DB::query("SELECT * FROM jishigou_user_tag_fields where  uid='$uid'    limit 0,6 ");
while($value = DB::fetch($taglst)) {
	$tagblglist[] = $value;
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
if(empty($listre)){
    $listpath="";
}
//诚信委员会
if($uid=='1899466'){
	$wsql="SELECT a.recomid,b.realname FROM `pre_dazheng_weiyuan` AS a,pre_common_member_profile AS b WHERE a.recomid=b.uid AND a.uid=".$uid." ORDER BY a.dateline DESC";
	$wre=DB::query($wsql);
	while($wrow=DB::fetch($wre)){
		$wy[]=$wrow;
	}
	
	
}

//取教练
$jxjx=DB::query("SELECT a.userid uid,b.username,c.realname,a.seq FROM pre_jgtj_user a LEFT JOIN pre_ucenter_members b ON a.userid=b.uid LEFT JOIN pre_common_member_profile c ON a.userid=c.uid WHERE a.groupuid=$uid and a.type=1 order by a.seq asc limit 3");
while($jxjxrow=DB::fetch($jxjx)){
    if(empty($jxjxrow["realname"])){
        $jxjxrow["realname"]=$jxjxrow["username"];
    }

    $jxjxrow["realname"]=utf8Substr($jxjxrow["realname"],0,4);
    $jlarr[]=$jxjxrow;
}


?>