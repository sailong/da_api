<?php
/*******
*auto:xgw
* date:2012年2月16日
* info:取赛事的数据
* *********/
//取用户的信息
$info_user = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members  where uid=$uid");
$username=empty($info_user['username'])?0:$info_user['username'];        //用户名
$fans_count=empty($info_user['fans_count'])?0:$info_user['fans_count'];        //粉丝数
$follow_count=empty($info_user['follow_count'])?0:$info_user['follow_count'];    //关注数
$topic_count=empty($info_user['topic_count'])?0:$info_user['topic_count'];	    //微博
//var_dump($fans_count);

//取真实姓名和积分
$query = DB::query("SELECT b.uid, b.realname,b.bio,b.field3,a.credits FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 while($value = DB::fetch($query)) {		
		$realname=$value['realname'];		//真实姓名		
		$bio=$value['bio'];       //简介
		$field3=$value['field3'];     //企业logo  如果没有，是不是给一个默认的     
		$credits=$value['credits'];     //积分
		//var_dump($value);
		}
		
//取博客
$blogsql = DB::query("SELECT blogid,uid, username, subject FROM ".DB::table('home_blog')."  where uid=$uid");
while($rowblog = DB::fetch($blogsql)) {
	$blog[] = $rowblog;
}	
//var_dump($blog);
//取相册
$album=DB::query("SELECT albumname, username, picnum, pic FROM ".DB::table('home_album')." WHERE uid ={$uid} LIMIT 0,3 ");
while($rowalbum = DB::fetch($album)) {
	$albumre[] = $rowalbum;
}
//var_dump($albumre);
?>