<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}



$ac=$_G['gp_ac'];
//page 1
$page=$_G['gp_page'];
if(!$page)
{
	$page=1;
}
$page_size=$_G['gp_page_size'];
if(!$page_size)
{
	$page_size=10;
}

if($page==1)
{
	$page_start=0;
}
else
{
	$page_start=($page-1)*($page_size);
}

//page 2
$page2=$_G['gp_page2'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size2'];
if(!$page_size2)
{
	$page_size2=10;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}



//社区动态 -- 未登录
if($ac=="club_index_nologin")
{
	$total=DB::result_first("select count(tid) from jishigou_topic where 'type'<>'reply' ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where'type'<>'reply' order by dateline desc limit $page_start,$page_size ");
		
		while($row = DB::fetch($list) )
		{
			$imageids_arr = explode(',',$row['imageid']);
					
			$pic_ids = implode("','",$imageids_arr);
			unset($imageids_arr);
			$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
			unset($pic_ids);
			
			$pic_i=0;
			while($pic_row = DB::fetch($topic_img_rs) ){
				$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
				$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
				$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
				$pic_i++;
			}
			unset($topic_img_rs,$pic_i,$pic_row);
			if(!empty($pic_list)) {
				$row['pic_list'] = $pic_list;
			}else{
				$row['pic_list'] = null;
			}
			$photo_pic = reset($pic_list);
			if($photo_pic)
			{
				$row['photo_big']=$photo_pic['photo_big'];
				$row['photo_mibble']=$photo_pic['photo_mibble'];
				$row['photo_small']=$photo_pic['photo_small'];
			}
			else
			{
				$row['photo_big']=null;
				$row['photo_small']=null;
			}
			unset($pic_list,$photo_pic);

			//$row['content']=cutstr_html($row['content']);
			$content_tmp = cutstr_html($row['content']);
			$row['content']=cutstr_html($row['full_content']);
			if(empty($row['content']))
			{
				$row['content']=$content_tmp;
			}
			$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			
			if($row['voice'])
			{
				$row['voice']=$site_url."/weibo/".$row['voice']."";
			}

			$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
			if($root_topic)
			{
				$imageids_arr = explode(',',$root_topic['imageid']);
				$pic_ids = implode("','",$imageids_arr);
				$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
				unset($imageids_arr,$pic_ids);
				
				$pic_i=0;
				while($pic_row = DB::fetch($root_topic_img_rs) ){
					$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
					$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
					$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
					$pic_i++;
				}
				unset($root_topic_img_rs,$pic_i,$pic_row);
				if(!empty($pic_list)) {
					$root_topic['pic_list'] = $pic_list;
				}else{
					$root_topic['pic_list'] = null;
				}
				
				$photo_pic = reset($pic_list);
				if($photo_pic)
				{
					$root_topic['photo_big']=$photo_pic['photo_big'];
					$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
					$root_topic['photo_small']=$photo_pic['photo_small'];
				}
				else
				{
					$root_topic['photo_big']=null;
					$root_topic['photo_small']=null;
				}
				unset($pic_list,$photo_pic);
				//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
				
				$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
				$root_topic['content']=cutstr_html($root_topic['full_content']);
				if(empty($root_topic['content']))
				{
					$root_topic['content']=$content_tmp;
				}
				
				$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
				$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
				if($root_topic['voice'])
				{
					$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
				}
				$row['root_topic']=$root_topic;
			}
			else
			{
				$row['root_topic']="";
			}

			$list_data[]=$row;
		}

	}//end page

	$data['title']		= "list_data";
	$data['data']		= $list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}



//关注动态 -- 已登录
if($ac=="club_index_login")
{
	$uid=$_G['gp_uid'];

	$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and uid in ( select buddyid from ( select buddyid from jishigou_buddys where uid='".$uid."')  as t2) ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic  where type<>'reply' and uid in (select buddyid from jishigou_buddys where uid='".$uid."' )  order by dateline desc limit $page_start,$page_size ");
		while($row = DB::fetch($list) )
		{
			$imageids_arr = explode(',',$row['imageid']);
					
			$pic_ids = implode("','",$imageids_arr);
			unset($imageids_arr);
			$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
			unset($pic_ids);
			
			$pic_i=0;
			while($pic_row = DB::fetch($topic_img_rs) ){
				$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
				$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
				$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
				$pic_i++;
			}
			unset($topic_img_rs,$pic_i,$pic_row);
			if(!empty($pic_list)) {
				$row['pic_list'] = $pic_list;
			}else{
				$row['pic_list'] = null;
			}
			
			$photo_pic = reset($pic_list);
			if($photo_pic)
			{
				$row['photo_big']=$photo_pic['photo_big'];
				$row['photo_mibble']=$photo_pic['photo_mibble'];
				$row['photo_small']=$photo_pic['photo_small'];
			}
			else
			{
				$row['photo_big']=null;
				$row['photo_small']=null;
			}
			unset($pic_list,$photo_pic);

			//$row['content']=cutstr_html($row['content']);
			
			$content_tmp = cutstr_html($row['content']);
			$row['content']=cutstr_html($row['full_content']);
			if(empty($row['content']))
			{
				$row['content']=$content_tmp;
			}
			
			$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			if($row['voice'])
			{
				$row['voice']=$site_url."/weibo/".$row['voice']."";
			}

			//根topic
			$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
			if($root_topic)
			{
				$imageids_arr = explode(',',$root_topic['imageid']);
				$pic_ids = implode("','",$imageids_arr);
				$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
				unset($imageids_arr,$pic_ids);
				
				$pic_i=0;
				while($pic_row = DB::fetch($root_topic_img_rs) ){
					$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
					$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
					$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
					$pic_i++;
				}
				unset($root_topic_img_rs,$pic_i,$pic_row);
				if(!empty($pic_list)) {
					$root_topic['pic_list'] = $pic_list;
				}else{
					$root_topic['pic_list'] = null;
				}
				
				$photo_pic = reset($pic_list);
				if($photo_pic)
				{
					$root_topic['photo_big']=$photo_pic['photo_big'];
					$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
					$root_topic['photo_small']=$photo_pic['photo_small'];
				}
				else
				{
					$root_topic['photo_big']=null;
					$root_topic['photo_small']=null;
				}
				unset($pic_list,$photo_pic);
				//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
				$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
				$root_topic['content']=cutstr_html($root_topic['full_content']);
				if(empty($root_topic['content']))
				{
					$root_topic['content']=$content_tmp;
				}
				
				$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
				$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
				if($root_topic['voice'])
				{
					$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
				}
				$row['root_topic']=$root_topic;
			}
			else
			{
				$row['root_topic']="";
			}

			$list_data[]=$row;
		}

	}//end page


	$data['title']		= "list_data";
	$data['data']		= $list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}



//微博具体页
if($ac=="topic_detail")
{
	$tid=$_G['gp_tid'];
	if($tid)
	{
		$detail_data=DB::fetch_first("select tid,uid,roottid,
(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,imageid,replys,forwards,dateline,voice,voice_timelong from jishigou_topic where tid='".$tid."' ");

		$imageids_arr = explode(',',$detail_data['imageid']);
					
		$pic_ids = implode("','",$imageids_arr);
		unset($imageids_arr);
		$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
		unset($pic_ids);
		
		$pic_i=0;
		while($pic_row = DB::fetch($topic_img_rs) ){
			$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
			$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
			$pic_i++;
		}
		unset($topic_img_rs,$pic_i,$pic_row);
		if(!empty($pic_list)) {
			$detail_data['pic_list'] = $pic_list;
		}else{
			$detail_data['pic_list'] = null;
		}
		
		$photo_pic = reset($pic_list);
		if($photo_pic)
		{
			$detail_data['photo_big']=$photo_pic['photo_big'];
			$detail_data['photo_small']=$photo_pic['photo_small'];
		}
		else
		{
			$detail_data['photo_big']=null;
			$detail_data['photo_small']=null;
		}
		unset($pic_list,$photo_pic);

		$link_url=get_content_links($detail_data['content']);
		$detail_data['content_url']=$link_url['link'];
		$detail_data['content_fuwenben']="东莞峰景培苗慈善赛3月20日圆满落幕<a href=\"".$site_url."/blog-1051-25540.html\">".$site_url."/blog-1051-25540.html</a>";

		if($detail_data['full_content'])
		{
			$detail_data['content']=cutstr_html($detail_data['full_content']);
		}
		else
		{
			$detail_data['content']=cutstr_html($detail_data['content'].$detail_data['content2']);
		}
		
		$detail_data['dateline']=date("Y-m-d G:i",$detail_data['dateline']);

		if($detail_data['voice'])
		{
			$detail_data['voice']=$site_url."/weibo/".$detail_data['voice']."";
		}

		
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";

		$reply_list=DB::query("select tid,uid,
(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,type,imageid from jishigou_topic where totid='".$tid."'  order by dateline desc limit 10 ");
		while($row2 = DB::fetch($reply_list) )
		{
			$imageids_arr = explode(',',$row['imageid']);
					
			$pic_ids = implode("','",$imageids_arr);
			unset($imageids_arr);
			$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
			unset($pic_ids);
			
			$pic_i=0;
			while($pic_row = DB::fetch($topic_img_rs) ){
				$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
				$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
				$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
				$pic_i++;
			}
			unset($topic_img_rs,$pic_i,$pic_row);
			if(!empty($pic_list)) {
				$row2['pic_list'] = $pic_list;
			}else{
				$row2['pic_list'] = null;
			}
			$photo_pic = reset($pic_list);
			if($photo_pic)
			{
				$row2['photo_big']=$photo_pic['photo_big'];
				$row2['photo_mibble']=$photo_pic['photo_mibble'];
				$row2['photo_small']=$photo_pic['photo_small'];
			}
			else
			{
				$row2['photo_big']=null;
				$row2['photo_small']=null;
			}
			unset($pic_list,$photo_pic);
			//$row2['content']=cutstr_html($row2['content']);
			$content_tmp = cutstr_html($row2['content']);
			$row2['content']=cutstr_html($row2['full_content']);
			if(empty($row2['content']))
			{
				$row2['content']=$content_tmp;
			}
			
			$row2['dateline']=date("Y-m-d G:i",$row2['dateline']);
			$row2['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row2['uid']."&size=small";
			$reply_data[]=$row2;
		}
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";

		//根topic
		$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$detail_data['roottid']."' order by dateline asc ");
		if($root_topic)
		{
			$imageids_arr = explode(',',$root_topic['imageid']);
			$pic_ids = implode("','",$imageids_arr);
			$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
			unset($imageids_arr,$pic_ids);
			
			$pic_i=0;
			while($pic_row = DB::fetch($root_topic_img_rs) ){
				$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
				$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
				$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
				$pic_i++;
			}
			unset($root_topic_img_rs,$pic_i,$pic_row);
			if(!empty($pic_list)) {
				$root_topic['pic_list'] = $pic_list;
			}else{
				$root_topic['pic_list'] = null;
			}
			
			$photo_pic = reset($pic_list);
			if($photo_pic)
			{
				$root_topic['photo_big']=$photo_pic['photo_big'];
				$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
				$root_topic['photo_small']=$photo_pic['photo_small'];
			}
			else
			{
				$root_topic['photo_big']=null;
				$root_topic['photo_small']=null;
			}
			unset($pic_list,$photo_pic);
			//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
			
			$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
			$root_topic['content']=cutstr_html($root_topic['full_content']);
			if(empty($root_topic['content']))
			{
				$root_topic['content']=$content_tmp;
			}
			$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
			$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
			if($root_topic['voice'])
			{
				$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
			}
			$detail_data['root_topic']=$root_topic;
		}
		else
		{
			$detail_data['root_topic']="";
		}

		if($detail_data['tid'])
		{
			$data['title']		= "detail";
			$data['data']		= array(
					'detail_info'=> $detail_data,
					'reply_list'=>$reply_data
			);
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
	}

}




//粉丝列表 某人   //他粉丝列表
if($ac=="fensi_list")
{
	$uid=$_G['gp_uid'];
	
	$res=DB::query("update jishigou_members set fans_new=0 where uid='".$uid."' ");
	if($_G['gp_is_hulue'])
	{
		api_json_result(1,0,"成功了！",$data);
	}
	else
	{
		$detail_data=DB::fetch_first("select uid,
	(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select count(id) from jishigou_buddys where uid=".DB::table("common_member").".uid ) as guanzhu_num,(select count(id) from jishigou_buddys where buddyid=".DB::table("common_member").".uid ) as fengsi_num,(select count(tid) from jishigou_topic where uid=".DB::table("common_member").".uid and type='first' ) as dongtai_num from ".DB::table("common_member")." where uid='".$uid."' ");
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";

		$total=DB::result_first("select count(uid) from jishigou_buddys where buddyid='".$uid."' ");
		$max_page=intval($total/$page_size);
		if($max_page<$total/$page_size)
		{
			$max_page=$max_page+1;
		}
		if($max_page>=$page)
		{
			$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_buddys.uid) as username,(select mobile from ".DB::table("common_member_profile")." where uid=jishigou_buddys.uid) as mobile,(select chadian from ".DB::table("common_member_profile")." where uid=jishigou_buddys.uid) as chadian,(select gender from ".DB::table("common_member_profile")." where uid=jishigou_buddys.uid) as sex,(select email from ".DB::table("common_member")." where uid=jishigou_buddys.uid) as email from jishigou_buddys where buddyid='".$uid."' order by dateline desc limit $page_start,$page_size ");
			while($row = DB::fetch($list))
			{
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=middle";
				$list_data[]=$row;
			}
		}
		
		$data['title']		= "list";
		$data['data']		=array(
			'user_info'=>$detail_data,
			'list_info'=>$list_data,
		);
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}


}


//关注列表 某人  //他关注列表
if($ac=="guanzhu_list")
{
	$uid=$_G['gp_uid'];
	

	$detail_data=DB::fetch_first("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select count(id) from jishigou_buddys where uid=".DB::table("common_member").".uid ) as guanzhu_num,(select count(id) from jishigou_buddys where buddyid=".DB::table("common_member").".uid ) as fengsi_num,(select count(tid) from jishigou_topic where uid=".DB::table("common_member").".uid and type='first' ) as dongtai_num from ".DB::table("common_member")." where uid='".$uid."' ");
	$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";

	$total=DB::result_first("select count(uid) from jishigou_buddys where uid='".$uid."' ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}

	if($max_page>=$page)
	{
		$list=DB::query("select buddyid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_buddys.buddyid) as username from jishigou_buddys where uid='".$uid."' group by buddyid order by dateline desc limit $page_start,$page_size ");
		while($row = DB::fetch($list))
		{
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['buddyid']."&size=middle";
			if($row['buddyid']>0)
			{
				$list_data[]=$row;
			}
		}
	}

	$data['title']		= "list";
	$data['data']		=array(
		'user_info'=>$detail_data,
		'list_info'=>$list_data,
	);
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//我的首页 动态展示  当前登录人
if($ac=="my_detail")
{
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$detail_data=DB::fetch_first("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select count(id) from jishigou_buddys where uid=".DB::table("common_member").".uid ) as guanzhu_num,(select count(id) from jishigou_buddys where buddyid=".DB::table("common_member").".uid ) as fensi_num,(select count(tid) from jishigou_topic where uid=".DB::table("common_member").".uid and type='first' ) as dongtai_num,groupid,(select chadian from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as chadian from ".DB::table("common_member")." where uid='".$uid."' ");

		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";

		$msg =DB::fetch_first( " select `newpm`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new` from ultrax.jishigou_members where uid =".$uid);
		$detail_data['msg_num']=$msg['comment_new']+$msg['fans_new']+$msg['at_new']+$msg['topic_new'];

		//分页
		$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and uid in ( select buddyid from (  select buddyid from jishigou_buddys where uid='".$uid."')  as t2) or uid='".$uid."' ");
		
		$max_page=intval($total/$page_size);
		if($max_page<$total/$page_size)
		{
			$max_page=$max_page+1;
		}
		if($max_page>=$page)
		{
				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,imageid,replys,forwards,dateline,voice,voice_timelong from jishigou_topic where type<>'reply' and uid in (select buddyid from jishigou_buddys where uid='".$uid."' ) or uid='".$uid."' order by dateline desc limit $page_start,$page_size ");
				while($row = DB::fetch($list) )
				{
					$imageids_arr = explode(',',$row['imageid']);
					
					$pic_ids = implode("','",$imageids_arr);
					unset($imageids_arr);
					$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
					unset($pic_ids);
					//echo "select id,photo from jishigou_topic_image where id in({$imageids})";
					$pic_i=0;
					while($pic_row = DB::fetch($topic_img_rs) ){
						$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
						$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
						$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
						$pic_i++;
					}
					unset($topic_img_rs,$pic_i,$pic_row);
					if(!empty($pic_list)) {
						$row['pic_list'] = $pic_list;
					}else{
						$row['pic_list'] = null;
					}
					
					$photo_pic = reset($pic_list);
					
					if($photo_pic)
					{
						$row['photo_big']=$photo_pic['photo_big'];
						$row['photo_mibble']=$photo_pic['photo_mibble'];
						$row['photo_small']=$photo_pic['photo_small'];
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($pic_list,$photo_pic);

					//$row['content']=cutstr_html($row['content']);
					$content_tmp = cutstr_html($row['content']);
					$row['content']=cutstr_html($row['full_content']);
					if(empty($row['content']))
					{
						$row['content']=$content_tmp;
}
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,imageid,replys,forwards,dateline,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					
					if($root_topic)
					{
						$imageids_arr = explode(',',$root_topic['imageid']);
						$pic_ids = implode("','",$imageids_arr);
						$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
						unset($imageids_arr,$pic_ids);
						//echo "<br/>select photo from jishigou_topic_image where id in ('{$imageids}')";
						$pic_i=0;
						while($pic_row = DB::fetch($root_topic_img_rs) ){
							$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
							$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
							$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
							$pic_i++;
						}
						unset($root_topic_img_rs,$pic_i,$pic_row);
						if(!empty($pic_list)) {
							$root_topic['pic_list'] = $pic_list;
						}else{
							$root_topic['pic_list'] = null;
						}
						
						$photo_pic = reset($pic_list);
						if($photo_pic)
						{
							$root_topic['photo_big']=$photo_pic['photo_big'];
							$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
							$root_topic['photo_small']=$photo_pic['photo_small'];
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($pic_list,$photo_pic);
						//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
						$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
						$root_topic['content']=cutstr_html($root_topic['full_content']);
						if(empty($root_topic['content']))
						{
							$root_topic['content']=$content_tmp;
						}
						$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						$row['root_topic']=$root_topic;
					}
					else
					{
						$row['root_topic']="";
					}

					$list_data[]=$row;
				}

		}//end page

		$data['title']		= "detail_data";
		$data['data']=array(
						  'user_info'=>$detail_data,
						  'list_info'=>$list_data,
						 );
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);

	}

}



//他人首页（球星页面） 只显示他发的
if($ac=="member_detail")
{

	$get_uid=$_G['gp_uid'];
	$login_uid=$_G['gp_login_uid'];
	if($get_uid)
	{
		$detail_data=DB::fetch_first("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select enrealname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as enrealname,(select count(id) from jishigou_buddys where uid=".DB::table("common_member").".uid ) as guanzhu_num,(select count(id) from jishigou_buddys where buddyid=".DB::table("common_member").".uid ) as fensi_num,(select count(tid) from jishigou_topic where uid=".DB::table("common_member").".uid and type='first' ) as dongtai_num,(select count(id) from jishigou_buddys where uid='".$login_uid."' and  buddyid='".$get_uid."' ) as is_guanzhu,groupid,(select bio from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as content from ".DB::table("common_member")." where uid='".$get_uid."' ");
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";
		$detail_data['msg_num']=1;
		
		if($detail_data['username']!=$detail_data['enrealname'])
		{
			$detail_data['username']=$detail_data['username']."\n".$detail_data['enrealname'];
		}
		else
		{
			$detail_data['username']=$detail_data['username'];
		}
		
		
		if(!$detail_data['content'])
		{
			if($detail_data['groupid']==24)
			{
				$detail_data['content']="暂无该球星简介";
			}
			else
			{
				$detail_data['content']="这家伙很懒。";
			}
		}


		//print_r($detail_data);

		//成绩卡列表
		//if($detail_data['groupid']==24)
		//{
			$total2=DB::result_first("select baofen_id from tbl_baofen where uid=$get_uid ");
			$max_page2=intval($total2/$page_size2);
			if($max_page2<$total2/$page_size2)
			{
				$max_page2=$max_page2+1;
			}

			if($max_page2>=$page2)
			{
			
				$query = DB::query("select baofen_id as id,event_id,uid,fuid,fz_id,par,score,pars,total_score,lun,dateline,event_name,start_time from (select baofen_id,field_id,baofen_id as id,uid,field_id as fuid,event_id,fenzhan_id as fz_id,sid,par,score,pars,total_score,lun,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,addtime,(select event_name from tbl_event where event_id=tbl_baofen.event_id) as event_name,start_time from tbl_baofen where start_time>'".strtotime("2013-04-01")."' and uid=$get_uid $strwhere ) as t2 group by event_id order by total_score asc,start_time desc limit $page_start2,$page_size2");

				while($row = DB::fetch($query))
				{
					$row['ndid']=$row['id'];
					$row['event_name']=$row['event_name']." ";
					$row['iframe_url']=$site_url."/nd/score.php?ndid=".$row['ndid']."&size=small";
					$score_list[] = array_default_value($row); 
				}
				/*
				$query = DB::query("select id,uid,fuid,fz_id,par,score,pars,total_score,lun,onlymark,dateline,event_name,addtime from (select id,uid,fuid,fz_id,par,score,sais_id,pars,total_score,lun,onlymark,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,addtime,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_score').".sais_id) as event_name from ".DB::table('common_score')."  where addtime>'".strtotime("2013-04-01")."' and uid=$get_uid $strwhere order by total_score asc) as t2 group by sais_id order by total_score asc,addtime desc limit $page_start2,$page_size2");

				//echo "select id,uid,fuid,par,score,pars,total_score,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_score').".uid) as event_name from ".DB::table('common_score')."  where uid=$get_uid $strwhere order by addtime desc limit $page_start2,$page_size2";
				while($row = DB::fetch($query))
				{
					
					$row['ndid']=DB::result_first("select nd_id from ".DB::table("golf_nd_baofen")." where uid='".$row['uid']."' and fenz_id='".$row['fz_id']."'  ");
					if($row['ndid']==false)
					{
						$row['ndid']=DB::result_first("select nd_id from ".DB::table("golf_nd_baofen")." where uid='".$row['uid']."' and onlymark='".$row['onlymark']."'  ");	
					}
					
					$row['event_name']=$row['event_name']." ";
					$row['iframe_url']=$site_url."/nd/score.php?ndid=".$row['ndid']."&size=small";
					$score_list[] = $row; 
				}
				*/
			}

		//}

		
		//微博列表 分页
		$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and uid='".$get_uid."' ");
		$max_page=intval($total/$page_size);
		if($max_page<$total/$page_size)
		{
			$max_page=$max_page+1;
		}
		if($max_page>=$page)
		{
			$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where  uid='".$get_uid."' order by dateline desc limit $page_start,$page_size ");
			while($row = DB::fetch($list) )
			{
				$imageids_arr = explode(',',$row['imageid']);
						
				$pic_ids = implode("','",$imageids_arr);
				unset($imageids_arr);
				$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
				unset($pic_ids);
				
				$pic_i=0;
				while($pic_row = DB::fetch($topic_img_rs) ){
					$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
					$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
					$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
					$pic_i++;
				}
				unset($topic_img_rs,$pic_i,$pic_row);
				if(!empty($pic_list)) {
					$row['pic_list'] = $pic_list;
				}else{
					$row['pic_list'] = null;
				}
				$photo_pic = reset($pic_list);
				if($photo_pic)
				{
					$row['photo_big']=$photo_pic['photo_big'];
					$row['photo_mibble']=$photo_pic['photo_mibble'];
					$row['photo_small']=$photo_pic['photo_small'];
				}
				else
				{
					$row['photo_big']=null;
					$row['photo_small']=null;
				}
				unset($pic_list,$photo_pic);
				//$row['content']=cutstr_html($row['content']);
				$content_tmp = cutstr_html($row['content']);
				$row['content']=cutstr_html($row['full_content']);
				if(empty($row['content']))
				{
					$row['content']=$content_tmp;
				}
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
				
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				if($row['voice'])
				{
					$row['voice']=$site_url."/weibo/".$row['voice']."";
				}

				$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
				if($root_topic)
				{
					$imageids_arr = explode(',',$root_topic['imageid']);
					$pic_ids = implode("','",$imageids_arr);
					$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
					unset($imageids_arr,$pic_ids);
					
					$pic_i=0;
					while($pic_row = DB::fetch($root_topic_img_rs) ){
						$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
						$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
						$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
						$pic_i++;
					}
					unset($root_topic_img_rs,$pic_i,$pic_row);
					if(!empty($pic_list)) {
						$root_topic['pic_list'] = $pic_list;
					}else{
						$root_topic['pic_list'] = null;
					}
					$photo_pic = reset($pic_list);
					if($photo_pic)
					{
						$root_topic['photo_big']=$photo_pic['photo_big'];			
						$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
						$root_topic['photo_small']=$photo_pic['photo_small'];
					}
					else
					{
						$root_topic['photo_big']=null;
						$root_topic['photo_small']=null;
					}
					unset($pic_list,$photo_pic);
					//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
					
					$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
					$root_topic['content']=cutstr_html($root_topic['full_content']);
					if(empty($root_topic['content']))
					{
						$root_topic['content']=$content_tmp;
					}
					$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
					$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
					if($root_topic['voice'])
					{
						$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
					}
					$row['root_topic']=$root_topic;
				}
				else
				{
					$row['root_topic']="";
				}

				$list_data[]=$row;
			}

		}//end page

		$data['title']		= "detail_data";
		$data['data']=array(
						  'user_info'=>$detail_data,
						  'list_info'=>$list_data,
						  'score_list'=>$score_list,
						 );
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);

	}

}



//球星详细
if($ac=="star_detail")
{

	$get_uid=$_G['gp_uid'];
	$login_uid=$_G['gp_login_uid'];
	if($get_uid)
	{
		$detail_data=DB::fetch_first("select uid,username,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as realname,(select bio from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as content,(select count(id) from jishigou_buddys where uid='".$login_uid."' and  buddyid='".$get_uid."' ) as is_guanzhu,groupid from ".DB::table("common_member")." where uid='".$get_uid."' ");
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";
		if(!$detail_data['content'])
		{
			if($detail_data['groupid']==24)
			{
				$detail_data['content']="暂无该球星简介";
			}
			else
			{
				$detail_data['content']="这个家伙很懒。";
			}
			
		}

		$data['title']		= "detail_data";
		$data['data']		= $detail_data;

		api_json_result(1,0,$app_error['event']['10502'],$data);

	}

}


//行业资讯 arctype_id=2
/*
if($ac=="golf_news")
{
	//微博列表 分页
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arctype_id=2 ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime,'%Y%m%d') as today from tbl_arc where arc_model='arc' and arc_state=1 and arctype_id=2 order by today desc,arc_sort desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="评论：".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']=$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			$list_data[]=$row;
			$i++;
		}

			
	}	

	$data['title']="list_data";
	$data['data']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);


}
*/


//大正行业资讯 + 球场新闻
if($ac=="golf_news")
{
	$field_uid=$_G['gp_field_uid'];
	$page_size=9;
	
	if($page==1)
	{
		$page_start=0;
	}
	else
	{
		$page_start=($page-1)*($page_size);
	}


	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewstatus=1  and (arctype_id=2 or arc_type='Q') and arc_viewtype='normal' $language_sql ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select field_uid,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime,'%Y%m%d') as today,arc_top from tbl_arc where  arc_model='arc' and arc_state=1 and arc_viewtype='normal' and arc_state=1 and arc_viewstatus=1 and (arctype_id=2 or arc_type='Q')  $language_sql order by arc_top desc,today desc,arc_sort desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			
			if($row['field_uid']==1186)
			{
				$row['replynum']=$row['replynum']." - 来自美兰湖球场";
			}
			else if($row['field_uid']==1160)
			{
				$row['replynum']=$row['replynum']." - 来自南山球会";
			}
			else
			{
				
			}
			
			$row = array_default_value($row);
			//$row = check_field_to_relace($row, array('replynum'=>'0'));
			$list_data[]=$row;
			
			$i++;
		}
			
	}

	//处理
	$normal_1=array_slice($list_data,0,3,true);
	$normal_2=array_slice($list_data,3,3,true);
	$normal_3=array_slice($list_data,6,3,true);


	$page_size=3;
	
	if($page==1)
	{
		$page_start=0;
	}
	else
	{
		$page_start=($page-1)*($page_size);
	}

	
	
	$total=DB::result_first("select count(arc_id) from tbl_arc where arc_model='arc' and arc_state=1 and arc_viewstatus=1 and arc_viewtype='pic' and arc_state=1 and (arctype_id=2 or arc_type='Q')  ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		$list=DB::query("select field_uid,arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime,'%Y%m%d') as today,arc_top from tbl_arc where  arc_model='arc' and arc_state=1 and arc_viewstatus=1  and arc_viewtype='pic' and (arctype_id=2 or arc_type='Q') $language_sql order by arc_top desc,today desc,arc_sort desc limit $page_start,$page_size");
		$i=0;
		while($row = DB::fetch($list))
		{
			$row['uid']=0;
			$row['replynum']="".$row['replynum'];
			if($row['pic'])
			{
				$row['pic']="".$site_url."/".$row['pic'];
			}
			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			$row['content']=msubstr(cutstr_html($row['content']),0,30);
			if($row['field_uid']==1186)
			{
				$row['replynum']=$row['replynum']." - 来自美兰湖球场";
			}
			else if($row['field_uid']==1160)
			{
				$row['replynum']=$row['replynum']." - 来自南山球会";
			}
			else
			{
				
			}
			
			$row = array_default_value($row);
			//$row = check_field_to_relace($row, array('replynum'=>'0'));
			$pic_list[]=$row;
			$i++;
		}
	}

	$pic_1=array_slice($pic_list,0,1,true);
	$pic_2=array_slice($pic_list,1,1,true);
	$pic_3=array_slice($pic_list,2,1,true);

	$list_data=array_merge($pic_1,$normal_1,$pic_2,$normal_2,$pic_3,$normal_3);

    if(empty($list_data)) 
	{
        $list_data = null;
    }

	$data['title']="list_data";
	$data['data']=$list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//品牌商城 arctype_id=1
if($ac=="goods_list")
{
	$list=DB::query("select arc_id as blogid,arc_name as subject,arc_replynum as replynum,arc_viewtype as view_type,arc_pic as pic ,arc_addtime as dateline,arc_content as content,FROM_UNIXTIME(arc_addtime, '%Y%m%d') as today from tbl_arc where  arc_model='arc'  and arc_state=1 and arctype_id=1 order by today desc,arc_sort desc  limit 10");
	$i=0;
	while($row = DB::fetch($list))
	{
		$row['uid']=0;
		$row['replynum']="评论：".$row['replynum'];
		if($row['pic'])
		{
			$row['pic']=$site_url."/".$row['pic'];
		}
		$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
		$row['content']=msubstr(cutstr_html($row['content']),0,30);
		$list_data[]=$row;
		$i++;
	}
	if($list_data)
	{
		$data['title']="list_data";
		$data['data']=$list_data;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,0,"没有新闻了",$data);
	}
}

/*
".$site_url."/bw_api.php?mod=club&ac=goods_list_cache&tid=51&arctype_id=3
".$site_url."/bw_api.php?mod=club&ac=goods_list_cache&tid=48&arctype_id=1
".$site_url."/bw_api.php?mod=club&ac=goods_list_cache&tid=50&arctype_id=2
*/
if($ac=="goods_list_cache")
{
	$type_id=$_G['gp_tid'];
	$arctype_id=$_G['gp_arctype_id'];
	$list=DB::query("select blogid,uid,subject,replynum,view_type,(select pic from ".DB::table("home_blogfield")." where blogid=".DB::table("home_blog").".blogid ) as pic,dateline,(select message from ".DB::table("home_blogfield")." where blogid=".DB::table("home_blog").".blogid ) as content from ".DB::table("home_blog")." where blogid in (select cid from ".DB::table('home_recommend')." where rectype='".$type_id."' ) order by dateline asc limit 50");
	$i=0;
	$n=0;
	while($row = DB::fetch($list))
	{
		$row['content']=stripslashes($row['content']);
		$row['content']=str_replace("'","\'",$row['content']);
		$if_have=DB::result_first("select arc_id from tbl_arc where arc_name like '".$row['subject']."' limit 1 ");
		if(!$if_have['arc_id'])
		{
			$res=DB::query("insert into tbl_arc (arc_id,uid,arctype_id,arc_name,arc_replynum,arc_pic,arc_addtime,arc_content,arc_viewtype,arc_type,arc_state) values ('".$row['blogid']."','".$row['uid']."','".$arctype_id."','".$row['subject']."','0','".$row['pic']."','".$row['dateline']."','".$row['content']."','".$row['view_type']."','B','0')");
			$i=$i+1;
			$i_str .="<dd>".$row['subject']."</dd>";
		}
		else
		{
			$n_str .="<dd>".$row['subject']."</dd>";
			$n=$n+1;
		}
	}

	echo " <dt>更新 ".$i." 条</dt>";
	echo $i_str;

	echo "<hr>";
	echo " 忽略 ".$n." 条";
	echo $n_str;
}







//添加关注
if($ac=="guanzhu_add")
{
	$uid=$_G['gp_uid'];
	$buddyid=$_G['gp_buddyid'];
	$aaa=DB::fetch_first("select * from jishigou_buddys where uid='".$uid."' and buddyid='".$buddyid."' ");
	if(empty($aaa))
	{
		$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$uid."','".$buddyid."','1','','".time()."','','".time()."') ");
		
		$up=DB::query("update jishigou_members set follow_count=follow_count+1 where uid='".$uid."' ");
		$up2=DB::query("update jishigou_members set fans_count=fans_count+1 where uid='".$buddyid."' ");

		api_json_result(1,0,"关注成功",$res);
	}
	else
	{
		api_json_result(1,1,"关注失败",$res);
	}

}



//找朋友
if($ac=="friend")
{
	$uid=$_G['gp_uid'];
	$type=$_G['gp_type'];
	$notinuid=str_replace("^",",",$_G['gp_notinuid']);
	if($notinuid)
	{
		$not_in_sql=" and uid not in (".$notinuid.") ";
	}
	else
	{
		$not_in_sql="";
	}

	$user_info=DB::fetch_first("select resideprovince,residecity from pre_common_member_profile where uid='".$uid."'  ");
	if($user_info['resideprovince'])
	{
		$my_city=$user_info['resideprovince'];
	}

	//同一城市
	if($my_city && $type=="city")
	{

		$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where  uid in (select uid from ".DB::table('common_member_profile')." where resideprovince like '".$my_city."')  ".$not_in_sql." limit 6 ");
		while($row = DB::fetch($list))
		{
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			$city_list[]=$row;
		}
	}

	//同一球场
	if($type=="qiuchang")
	{

		$fuid=DB::fetch_first("select fuid from ".DB::table("common_score")." where uid='".$uid."' order by dateline desc limit 1  ");
		if($fuid)
		{
			$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where  uid in ( SELECT uid FROM ( select uid from ".DB::table('common_score')." where fuid='".$fuid."' ) as t2 ) ".$not_in_sql." limit 6 ");
			while($row = DB::fetch($list))
			{
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				$list_data[]=$row;
			}
		}

	}


	//同一赛事
	if($type=="saishi")
	{
		//$sais_id=DB::fetch_first("select sais_id from ".DB::table("common_score")." where uid='".$uid."' order by dateline desc limit 1  ");
		$sais_id=1000333;
		$my_event=DB::result_first("select count(uid) from ".DB::table("common_score")." where uid='".$uid."' and  sais_id='".$sais_id."'  ");
		if($my_event>0)
		{
			$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where uid in ( SELECT uid FROM ( select uid from ".DB::table('common_score')." where sais_id='".$sais_id."' ) as t2 ) ".$not_in_sql." limit 6 ");

			//$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid ) as is_guanzhu from ".DB::table("common_member")." where  uid in (select uid from ".DB::table('common_score')." where sais_id in (select sais_id  from ".DB::table('common_score')." where uid='".$uid."' ) ) ".$not_in_sql." limit 6 ");
			while($row = DB::fetch($list))
			{
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				$list_data[]=$row;
			}
		}
		
	}


	//随机列表
	if($type=="suiji")
	{
		$count=DB::fetch_first("select MIN(uid) as min_uid,MAX(uid)  as max_uid from ".DB::table("common_member")."  ");
		$rand_uid=rand($count['min_uid'],$count['max_uid']);
		
		$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where uid>'".$rand_uid."' ".$not_in_sql." limit 6 ");

		while($row = DB::fetch($list))
		{
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			$list_data[]=$row;
		}
	}

	$data['title']		= "list";
	$data['data']     =  array(
					  'list_data'=>$list_data,
					 );
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}



//找朋友 搜索
if($ac=="friend_search")
{

	$k=$_G['gp_k'];
	$uid=$_G['gp_uid'];
	if($k)
	{
		$list=DB::query("select uid,realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member_profile").".uid ) as is_guanzhu from ".DB::table("common_member_profile")." where realname like '%".$k."%' or mobile='".$k."'  limit 20 ");
		while($row = DB::fetch($list))
		{
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			$search_list[]=$row;
		}

		$data['title']		= "list";
		$data['data']=array(
						  'list_data'=>$search_list,
						 );
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"关键词不能为空",$data);
	}

	

}



//邀请链接
if($ac=="invite_link")
{
	$data['title']		= "data";
	$data['data']=array(
					  'link'=>"<读取通讯录姓名>你好，你的好友<邀请者真实姓名>邀请你注册大正网和他一起高球互动。<用户留言内容>。请点击以下链接下载安装大正网手机客户端 ".$site_url."/invite.php",
					 );
	api_json_result(1,0,$app_error['event']['10502'],$data);
}



//测试IP
if($ac=="test")
{

	$num=(string)1;

	$data['title']		= "data";
	$data['data']     =  $num;
	api_json_result(1,0,$app_error['event']['10502'],$data);

	/*

	$var='var remote_ip_info = {"ret":1,"start":"221.220.101.0","end":"221.220.106.255","country":"\u4e2d\u56fd","province":"\u5317\u4eac","city":"\u5317\u4eac","district":"\u6000\u67d4","isp":"\u8054\u901a","type":"","desc":""};';
	$arr=explode(",",$var);

	echo json_decode("\u6000\u67d4");
	print_r($arr);
	echo "<hr>";


	print_r($_SERVER);
	
	echo "<br>";

	function getIP()
	{
		global $ip;
		if (getenv("HTTP_CLIENT_IP"))
		$ip = getenv("HTTP_CLIENT_IP");
		else if(getenv("HTTP_X_FORWARDED_FOR"))
		$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if(getenv("REMOTE_ADDR"))
		$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}
	 
	// 使用方法：
	echo getIP();
	echo "<br />";

	function get_real_ip()
	{
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
		$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
		if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
		for ($i = 0; $i < count($ips); $i++) {
		if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
		$ip = $ips[$i];
		break;
		}
		}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}

	echo get_real_ip();
	*/
}





//@我列表
if($ac=="at_me")
{

	$uid=$_G['gp_uid'];
	$username=$_G['gp_username'];
	if($uid)
	{
		$res=DB::query("update jishigou_members set at_new=0 where uid='".$uid."' ");
		if($_G['gp_is_hulue'])
		{
			api_json_result(1,0,"成功了！",$data);
		}
		else
		{

			//分页
			$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and content like '%<M ".$username.">%' ");
			$max_page=intval($total/$page_size);
			if($max_page<$total/$page_size)
			{
				$max_page=$max_page+1;
			}
			if($max_page>=$page)
			{

				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%<M ".$username.">%' order by dateline desc limit $page_start,$page_size ");
				while($row = DB::fetch($list) )
				{
					$imageids_arr = explode(',',$row['imageid']);
						
					$pic_ids = implode("','",$imageids_arr);
					unset($imageids_arr);
					$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
					unset($pic_ids);
					
					$pic_i=0;
					while($pic_row = DB::fetch($topic_img_rs) ){
						$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
						$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
						$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
						$pic_i++;
					}
					unset($topic_img_rs,$pic_i,$pic_row);
					if(!empty($pic_list)) {
						$row['pic_list'] = $pic_list;
					}else{
						$row['pic_list'] = null;
					}
					$photo_pic = reset($pic_list);
					if($photo_pic)
					{
						$row['photo_big']=$photo_pic['photo_big'];
						$row['photo_mibble']=$photo_pic['photo_mibble'];
						$row['photo_small']=$photo_pic['photo_small'];
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($pic_list,$photo_pic);

					//$row['content']=cutstr_html($row['content']);
					
					$content_tmp = cutstr_html($row['content']);
					$row['content']=cutstr_html($row['full_content']);
					if(empty($row['content']))
					{
						$row['content']=$content_tmp;
					}
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					if($root_topic)
					{
						$imageids_arr = explode(',',$root_topic['imageid']);
						$pic_ids = implode("','",$imageids_arr);
						$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
						unset($imageids_arr,$pic_ids);
						
						$pic_i=0;
						while($pic_row = DB::fetch($root_topic_img_rs) ){
							$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
							$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
							$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
							$pic_i++;
						}
						unset($root_topic_img_rs,$pic_i,$pic_row);
						if(!empty($pic_list)) {
							$root_topic['pic_list'] = $pic_list;
						}else{
							$root_topic['pic_list'] = null;
						}
						$photo_pic = reset($pic_list);
						if($photo_pic)
						{
							$root_topic['photo_big']=$photo_pic['photo_big'];
							$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
							$root_topic['photo_small']=$photo_pic['photo_small'];
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($pic_list,$photo_pic);
						//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
						
						$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
						$root_topic['content']=cutstr_html($root_topic['full_content']);
						if(empty($root_topic['content']))
						{
							$root_topic['content']=$content_tmp;
						}
						$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						$row['root_topic']=$root_topic;
					}
					else
					{
						$row['root_topic']="";
					}

					$list_data[]=$row;
				}

			}//end page

			$data['title']  = "list_data";
			$data['data'] = $list_data;
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}

	}

}




//评论我的列表
if($ac=="comment_me")
{
	

	$uid=$_G['gp_uid'];
	$username=$_G['gp_username'];
	if($uid && $username)
	{
		$res=DB::query("update jishigou_members set topic_new=0 where uid='".$uid."' ");
		if($_G['gp_is_hulue'])
		{
			api_json_result(1,0,"成功了！",$data);
		}
		else
		{
			//分页
			$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' and content like '%<M ".$username.">%' ");
			$max_page=intval($total/$page_size);
			if($max_page<$total/$page_size)
			{
				$max_page=$max_page+1;
			}
			if($max_page>=$page)
			{

					$list=DB::query("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%<M ".$username.">%' order by dateline desc limit $page_start,$page_size ");
					while($row = DB::fetch($list) )
					{
						$imageids_arr = explode(',',$row['imageid']);
					
						$pic_ids = implode("','",$imageids_arr);
						unset($imageids_arr);
						$topic_img_rs = DB::query("select id,photo from jishigou_topic_image where id in('{$pic_ids}')");
						unset($pic_ids);
						
						$pic_i=0;
						while($pic_row = DB::fetch($topic_img_rs) ){
							$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
							$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
							$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
							$pic_i++;
						}
						unset($topic_img_rs,$pic_i,$pic_row);
						if(!empty($pic_list)) {
							$row['pic_list'] = $pic_list;
						}else{
							$row['pic_list'] = null;
						}
						$photo_pic = reset($pic_list);
						if($photo_pic)
						{
							$row['photo_big']=$photo_pic['photo_big'];
							$row['photo_mibble']=$photo_pic['photo_mibble'];
							$row['photo_small']=$photo_pic['photo_small'];
						}
						else
						{
							$row['photo_big']=null;
							$row['photo_small']=null;
						}
						unset($pic_list,$photo_pic);
						//$row['content']=cutstr_html($row['content']);
						
						$content_tmp = cutstr_html($row['content']);
						$row['content']=cutstr_html($row['full_content']);
						if(empty($row['content']))
						{
							$row['content']=$content_tmp;
						}
						$row['dateline']=date("Y-m-d G:i",$row['dateline']);
						
						$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
						if($row['voice'])
						{
							$row['voice']=$site_url."/weibo/".$row['voice']."";
						}


						//根topic
						$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,(select `longtext` from jishigou_topic_longtext where tid=jishigou_topic.tid) as full_content,replys,forwards,dateline,imageid,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
						if($root_topic)
						{
							$imageids_arr = explode(',',$root_topic['imageid']);
							$pic_ids = implode("','",$imageids_arr);
							$root_topic_img_rs =  DB::query("select photo from jishigou_topic_image where id in ('{$pic_ids}')");
							unset($imageids_arr,$pic_ids);
							
							$pic_i=0;
							while($pic_row = DB::fetch($root_topic_img_rs) ){
								$pic_list[$pic_i]['photo_big'] = $site_url."/weibo/".$pic_row['photo'];
								$pic_list[$pic_i]['photo_mibble'] = $site_url."/weibo/".str_replace("_o","_p",$pic_row['photo']);
								$pic_list[$pic_i]['photo_small'] = $site_url."/weibo/".str_replace("_o","_s",$pic_row['photo']);
								$pic_i++;
							}
							unset($root_topic_img_rs,$pic_i,$pic_row);
							if(!empty($pic_list)) {
								$root_topic['pic_list'] = $pic_list;
							}else{
								$root_topic['pic_list'] = null;
							}
							
							$photo_pic = reset($pic_list);
							if($photo_pic)
							{
								$root_topic['photo_big']=$photo_pic['photo_big'];
								$root_topic['photo_mibble']=$photo_pic['photo_mibble'];
								$root_topic['photo_small']=$photo_pic['photo_small'];
							}
							else
							{
								$root_topic['photo_big']=null;
								$root_topic['photo_small']=null;
							}
							unset($pic_list,$photo_pic);
							//$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
							
							$content_tmp = cutstr_html($root_topic['content'].$root_topic['content2']);
							$root_topic['content']=cutstr_html($root_topic['full_content']);
							if(empty($root_topic['content']))
							{
								$root_topic['content']=$content_tmp;
							}

							$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
							$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
							if($root_topic['voice'])
							{
								$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
							}
							$row['root_topic']=$root_topic;
						}
						else
						{
							$row['root_topic']="";
						}


						$list_data[]=$row;
					}
				
			}// end page

			$data['title']		= "list_data";
			$data['data']=array(
							  'list_info'=>$list_data,
							 );
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		
	}

}



//系统消息
if($ac=="system_msg")
{
	$uid=$_G['gp_uid'];
	//$msg =DB::fetch_first( " select `newpm`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new` from ultrax.jishigou_members where uid =".$this->var['uid']);
	$msg =DB::fetch_first( " select `newpm`,`push_new`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new` from ultrax.jishigou_members where uid =".$uid);

	$data['title']		= "detail_data";
	$data['data']		=	$msg;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);

}

//推送消息列表
if($ac=="push_msg_list")
{
	$uid=$_G['gp_uid'];
	$field_uid=$_G['gp_field_uid'];
	$type=$_G['gp_type'];
	if($field_uid!="")
	{
		$sql .=" and field_uid='".$field_uid."' ";
	}
	
	if($type!="")
	{
		$sql .=" and message_type='".$type."' ";
	}
    
	$list=DB::query("select message_id,message_number,message_type,uid,message_title,message_content,message_pic,message_addtime from tbl_push_message where (uid='{$uid}' or uid=0) ".$sql." ");
	
	//echo "select message_id,message_number,message_type,uid,message_title,message_content,message_pic,message_addtime from tbl_push_message where uid='{$uid}' ".$sql." ";
    
	while($row=DB::fetch($list))
	{
		/*
	    if(!json_parser($row['message_content']))
		{
	        continue;
	    }
		*/
	    $msg=json_decode($row['message_content'],true);
		//print_r($msg);
		$msg['n_title'] = urldecode($msg['n_title']);
		$msg['n_content'] = urldecode($msg['n_content']);
		if($msg['n_extras']['title'])
		{
			$msg['n_extras']['title'] = urldecode($msg['n_extras']['title']);
		}
		$row['pic_width'] = '';
		$row['pic_height'] = '';
	    $row['message_info']=$msg;
		if(!empty($row['message_pic']))
		{
			if(stripos($row['message_pic'],"http://") === false) {
				$row['message_pic']=$site_url.'/'.$row['message_pic'];
			}
			
			$message_pic_info = (array)getimagesize($row['message_pic']);
			$row['pic_width'] = $message_pic_info[0];
			$row['pic_height'] = $message_pic_info[1];
		}
		
		$row['message_sendtime']=date("Y-m-d",$row['message_addtime']);
		//unset($row['message_content']);
		$list_data[]=array_default_value($row,message_content);
		
	}
	/*
    if(empty($list_data))
	{
        $list_data = null;
    }
	*/
	$data['title']		= "list_data";
	$data['data']		= $list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}
//推送消息详情
if($ac=="msg_detail")
{
	$message_id=$_G['gp_message_id'];
	
	$message_info=DB::fetch_first("select message_id,message_number,message_type,uid,message_title,message_content,message_pic,message_addtime from tbl_push_message where message_id='$message_id'");
    

	if(!json_parser($message_info['message_content']))
	{
		continue;
	}
	$msg=json_decode($message_info['message_content'],true);
	
	$msg['n_title'] = urldecode($msg['n_title']);
	$msg['n_content'] = urldecode($msg['n_content']);
	if($msg['n_extras']['title']) {
		$msg['n_extras']['title'] = urldecode($msg['n_extras']['title']);
	}
	
	$message_info['pic_width'] = '';
	$message_info['pic_height'] = '';
	$message_info['message_info']=$msg;
	if(!empty($message_info['message_pic'])) {
		if(stripos($message_info['message_pic'],"http://") === false) {
			$message_info['message_pic']=$site_url.'/'.$message_info['message_pic'];
		}
		$message_pic_info = (array)getimagesize($message_info['message_pic']);
		$message_info['pic_width'] = $message_pic_info[0];
		$message_info['pic_height'] = $message_pic_info[1];
	}
	
	$message_info['message_sendtime']=date("Y-m-d",$message_info['message_addtime']);
	unset($message_info['message_content']);
	$message_info =array_default_value($message_info,message_content);
	
	$data['title']		= "msg_detail";
	$data['data']		= $message_info;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}

/*系统消息start*/
//推送消息列表
if($ac=="sys_msg_list")
{
	$uid=$_G['gp_uid'];
	//$list=DB::query("select message_id,message_number,message_type,uid,message_title,message_content,message_addtime from tbl_push_message where uid='".$uid."' and uid=0 ");
	$list=DB::query("select message_id,uid,message_title,message_content,message_pic,message_addtime from tbl_sys_message where uid in('$uid','0')");
    
	while($row=DB::fetch($list))
	{
	    if(!json_parser($row['message_content']))
		{
	        continue;
	    }
	    $msg=json_decode($row['message_content'],true);
		$msg['n_title'] = urldecode($msg['n_title']);
		$msg['n_content'] = urldecode($msg['n_content']);
		if($msg['n_extras']['title']) {
			$msg['n_extras']['title'] = urldecode($msg['n_extras']['title']);
		}
		$row['pic_width'] = '';
		$row['pic_height'] = '';
	    $row['message_info']=$msg;
		if(!empty($row['message_pic'])) {
			if(stripos($row['message_pic'],"http://") === false) {
				$row['message_pic']=$site_url.'/'.$row['message_pic'];
			}
			
			$message_pic_info = (array)getimagesize($row['message_pic']);
			$row['pic_width'] = $message_pic_info[0];
			$row['pic_height'] = $message_pic_info[1];
		}
		
		$row['message_sendtime']=date("Y-m-d",$row['message_addtime']);
		unset($row['message_content']);
		$list_data[]=array_default_value($row,message_content);
		
	}
	/*
    if(empty($list_data))
	{
        $list_data = null;
    }
	*/
	$data['title']		= "list_data";
	$data['data']		= $list_data;
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}
//推送消息详情
if($ac=="sys_msg_detail")
{
	$message_id=$_G['gp_message_id'];
	
	$message_info=DB::fetch_first("select message_id,uid,message_title,message_content,message_pic,message_addtime from tbl_sys_message where message_id='$message_id'");
    if(empty($message_info)){
		api_json_result(1,1,$app_error['event']['10502'],$data);exit;
	}

	if(!json_parser($message_info['message_content']))
	{
		continue;
	}
	$msg=json_decode($message_info['message_content'],true);
	
	$msg['n_title'] = urldecode($msg['n_title']);
	$msg['n_content'] = urldecode($msg['n_content']);
	if($msg['n_extras']['title']) {
		$msg['n_extras']['title'] = urldecode($msg['n_extras']['title']);
	}
	
	$message_info['pic_width'] = '';
	$message_info['pic_height'] = '';
	$message_info['message_info']=$msg;
	if(!empty($message_info['message_pic'])) {
		if(stripos($message_info['message_pic'],"http://") === false) {
			$message_info['message_pic']=$site_url.'/'.$message_info['message_pic'];
		}
		$message_pic_info = (array)getimagesize($message_info['message_pic']);
		$message_info['pic_width'] = $message_pic_info[0];
		$message_info['pic_height'] = $message_pic_info[1];
	}
	
	$message_info['message_sendtime']=date("Y-m-d",$message_info['message_addtime']);
	unset($message_info['message_content']);
	$message_info =array_default_value($message_info,message_content);
	
	$data['title']		= "msg_detail";
	$data['data']		= $message_info;
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}

/*系统消息end*/


//我的首页 动态展示  当前登录人
if($ac=="my_index")
{
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$detail_data=DB::fetch_first("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select chadian from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as chadian from ".DB::table("common_member")." where uid='".$uid."' ");

		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";
		$detail_data['msg_num']=1;

		$data['title']		= "detail_data";
		$data['data']=array(
						  'user_info'=>$detail_data,
						 );
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);

	}

}




//系统更新
if($ac=="app_version")
{
	$app_version_type=$_G['gp_type'];
	$field_uid=$_G['gp_client_id'];
	if($field_uid)
	{
		$sql=" and field_uid='".$field_uid."' ";
	}
	else
	{
		$sql=" and field_uid='0' ";
	}
	$version =DB::fetch_first( "select app_version_type,app_version_number,app_version_name,app_version_content,app_version_file,app_version_is_important,app_version_addtime from tbl_app_version where app_version_type ='".$app_version_type."' ".$sql." order by app_version_addtime desc limit 1  ");
	$version['app_version_addtime']=date("Y-m-d G:i:s",$version['app_version_addtime']);

	$version['token']=get_token();

	$data['title']		= "data";
	$data['data']		=	$version;
	
	api_json_result(1,0,"返回成功",$data);

}



//删除微博
if($ac=="delete_topic")
{
	$tid=$_G['gp_tid'];
	$uid=$_G['gp_uid'];//当前登录uid
	if($tid)
	{
		$res=DB::query("delete from jishigou_topic where tid='".$tid."' and uid='".$uid."' ");
		if($res)
		{
			api_json_result(1,0,"删除成功",$data);
		}
		else
		{
			api_json_result(1,1,"没有权限",$data);
		}
	}
	else
	{
		api_json_result(1,1,"参数不全",$data);
	}
	
}



//更新用户信息
if($ac=="update_member_info")
{
	/*
	print_r($_POST);
	echo "<br />";
	print_r($_GET);
	echo "<br />";
	print_r($_FILES);
	echo "<br />";
	*/

	$uid=$_G['gp_uid'];//当前登录uid
	$realname=trim(urldecode($_G['gp_realname']));
	$email=$_G['gp_email'];
	$chadian=$_G['gp_chadian'];	
	$is_push=$_G['gp_is_push'];	
	if($uid && $realname && $email)
	{
			if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
			{
				$full_save_path="./upload/avatar_cache/";
				if(!file_exists($full_save_path))
				{
					mkdir($full_save_path);
				}
				$up_res=move_uploaded_file($_FILES["pic"]["tmp_name"], $full_save_path.time().$_FILES["pic"]["name"]);//将上传的文件存储到服务器
				if($up_res)
				{
					//print_r($up_res);
					$file_url=$full_save_path.time().$_FILES["pic"]["name"];

					//echo $file_url;
					//echo "<hr>";
					$extname=end(explode(".",$file_url));
					if($extname=="jpg")
					{
						$pic_source=imagecreatefromjpeg($file_url);
					}
					/*
					else if($extname=="gif")
					{
						$pic_source=imagecreatefromgif($file_url);
					}
					else if($extname=="png")
					{
						$pic_source=imagecreatefrompng($file_url);
					}
					*/
					else
					{
						echo "文件类型不支持";
					}

					
					$filename_s = './uc_server/data/avatar/'.get_avatar($uid, "small", $type,"filename");
					$filename_m = './uc_server/data/avatar/'.get_avatar($uid, "middle", $type,"filename");
					$filename_b = './uc_server/data/avatar/'.get_avatar($uid, "big", $type,"filename");


					if($pic_source && $extname)
					{
						$aa=resizeImage($pic_source,48,48,$filename_s,".".$extname);
						$aa=resizeImage($pic_source,120,120,$filename_m,".".$extname);
						$aa=resizeImage($pic_source,200,220,$filename_b,".".$extname);

						if(file_exists($file_url))
						{
							$result=unlink($file_url);
						}

					}
					else
					{
						api_json_result(1,1,"图片格式不支持",$data);
					}
				
				}
				else
				{
					api_json_result(1,1,"文件上传失败",$data);
				}

			}
			

		$res1=DB::query("update ".DB::table("ucenter_members")." set email='".$email."' where uid='".$uid."' ");
		$res2=DB::query("update ".DB::table("common_member")." set email='".$email."' where uid='".$uid."' ");
		$res3=DB::query("update ".DB::table("common_member_profile")." set realname='".$realname."',chadian='".$chadian."',is_push='".$is_push."' where uid='".$uid."' ");
		$res4=DB::query("update jishigou_members set nickname='".$realname."',email='".$email."' where uid='".$uid."' ");

		//print_r($data);

		api_json_result(1,0,"保存成功",$data);
		
	}
	else
	{
		//print_r($data);
		api_json_result(1,1,"所有选项均于必填",$data);
	}
	
}



if($ac=="count")
{
	$time=time()-86400*14;
	//echo $time;
	$aaa=DB::fetch_first("select count(uid) as reg_num from pre_common_member where regdate>".$time." ");


	print_r($aaa);
}



//广告接口
if($ac=="ad_index")
{
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='ad_index' and ad_app='bwvip_app' order by rand() limit 1  ");
	if($ad['ad_file'])
	{
		$ad['ad_file']=$site_url."/".$ad['ad_file'];
	}
	if($ad['ad_file_iphone4'])
	{
		$ad['ad_file_iphone4']=$site_url."/".$ad['ad_file_iphone4'];
	}
	if($ad['ad_file_iphone5'])
	{
		$ad['ad_file_iphone5']=$site_url."/".$ad['ad_file_iphone5'];
	}

	if(!empty($ad))
	{
		$data['title']		= "data";
		$data['data']     =  $ad;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}


//广告接口 欢迎页
if($ac=="ad_welcome")
{
	$ad=DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where ad_page='ad_welcome' and ad_app='bwvip_app' order by ad_addtime desc limit 1  ");

	$arr=explode("|",$ad['ad_url']);
	if(count($arr)>1)
	{
		$ad['ad_action']=$arr[0];
		$ad['ad_action_id']=$arr[1];
		$ad['ad_action_text']=$arr[2];
		$ad['event_url']=$arr[3];
		if(!$ad['event_url'])
		{
			$ad['event_url']="";
		}
	}
	else
	{
		$ad['ad_action']="";
		$ad['ad_action_id']="";
		$ad['ad_action_text']="";
		$ad['event_url']="";
	}

	if($ad['ad_file'])
	{
		$ad['ad_file']=$site_url."/".$ad['ad_file'];
	}
	if($ad['ad_file_iphone4'])
	{
		$ad['ad_file_iphone4']=$site_url."/".$ad['ad_file_iphone4'];
	}
	if($ad['ad_file_iphone5'])
	{
		$ad['ad_file_iphone5']=$site_url."/".$ad['ad_file_iphone5'];
	}
	if(!empty($ad))
	{
		$data['title']		= "data";
		$data['data']     =  $ad;
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}
	else
	{
		api_json_result(1,1,"没有数据",$data);
	}

}
function json_parser($str){
    $arr = json_decode($str,true);
    if(gettype($arr) != "array"){
        return false;
    }else {
        return true;
    }
}

?>