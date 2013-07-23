<?php
$query = DB::query("SELECT uid, fans_count, follow_count,topic_count,username FROM jishigou_members  where uid=$uid");
		while($value=DB::fetch($query)) { 
		$username=$value['username'];
		$fans_count=$value['fans_count'];
		$follow_count=$value['follow_count'];
		$topic_count=$value['topic_count'];	
		}
		
 	
$query = DB::query("SELECT b.uid, b.realname,b.bio,a.credits FROM ".DB::table('common_member_profile')." as b INNER JOIN ".DB::table('common_member')." as a ON a.uid=b.uid where a.uid=$uid");
	 while($value = DB::fetch($query)) {		
		$realname=$value['realname'];				
		$bio=$value['bio'];
		$credits=$value['credits'];
		}
 
$query = DB::query("SELECT blogid,uid, username, subject FROM ".DB::table('home_blog')."  where uid=$uid");
	 while($value = DB::fetch($query)) {
			$blog[] = $value;
		}	


?>