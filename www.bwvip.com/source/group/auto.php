<?php
$query = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members  where uid=$uid");
		while($value=DB::fetch($query)) { 
		$username=$value['username'];
		$fans_count=$value['fans_count'];
		$follow_count=$value['follow_count'];
		$topic_count=$value['topic_count'];	
		}
		
 	
$query = DB::query("SELECT b.uid, b.realname,a.username,b.bio,b.field3,a.credits FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 while($value = DB::fetch($query)) {		
		$realname=$value['realname'];		
		$username=$value['username'];	
		if(strlen($realname)==0)	
		{ 
		  $realname=$username;
			}	
		$bio=$value['bio'];				
		$field3=$value['field3'];
		$credits=$value['credits'];
		}
 
$query = DB::query("SELECT blogid,uid, username, subject FROM ".DB::table('home_blog')."  where uid=$uid");
	 while($value = DB::fetch($query)) {
			$blog[] = $value;
		}	

$yglist = array();		
$sql="SELECT a.compid,a.userid,b.realname,c.username FROM ".DB::table('guanxi')." as a INNER JOIN ".DB::table('common_member_profile')." as b ON a.userid=b.uid  INNER JOIN ".DB::table('common_member')." as c  ON c.uid=b.uid   where a.compid=$uid and a.iscomp=1 limit 0,3";
$query = DB::query($sql); 
	 while($value = DB::fetch($query)) {
			$yglist[] = $value;
		}	
		
		
	 

 
?>