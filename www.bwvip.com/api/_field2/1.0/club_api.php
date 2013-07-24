<?php
/*
*
* club_api.php
* by zhanglong 2013-05-21
* field app 会员社区相关
*
*/

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
	$total=DB::result_first("select count(tid) from jishigou_topic where type<>'reply' ");
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{

		$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' order by dateline desc limit $page_start,$page_size ");
		while($row = DB::fetch($list) )
		{
			if($row['photo'])
			{
				$row['photo_big']=$site_url."/weibo/".$row['photo'];
				$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
			}
			else
			{
				$row['photo_big']=null;
				$row['photo_small']=null;
			}
			unset($row['photo']);

			$row['content']=cutstr_html($row['content'].$row['content2']);
			$row['dateline']=date("Y-m-d G:i",$row['dateline']);
			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
			
			if($row['voice'])
			{
				$row['voice']=$site_url."/weibo/".$row['voice']."";
			}

			$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
			if($root_topic)
			{
				if($root_topic['photo'])
				{
					$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
					$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
				}
				else
				{
					$root_topic['photo_big']=null;
					$root_topic['photo_small']=null;
				}
				unset($root_topic['photo']);
				$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
				$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
				$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
				if($root_topic['voice'])
				{
					$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
				}
				$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
			    $row['root_topic']=check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
			}
			else
			{
				$row['root_topic']="";
			}
			$list_data[]=array_default_value($row);
		}

	}//end page
    if(empty($list_data)) {
	    $list_data = null;
	}
	$data['title']		= "list_data";
	$data['data']		= $list_data;

	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}



//我的首页  我关注的人的动态
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

				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' and uid in (select buddyid from jishigou_buddys where uid='".$uid."' ) or uid='".$uid."' order by dateline desc limit $page_start,$page_size ");
				while($row = DB::fetch($list) )
				{
					if($row['photo'])
					{
						$row['photo_big']=$site_url."/weibo/".$row['photo'];
						$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($row['photo']);

					$row['content']=cutstr_html($row['content']);
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}
					
					
					

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					
					if(!empty($root_topic))
					{
						if($root_topic['photo'])
						{
							$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
							$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($root_topic['photo']);
						$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
						$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						//$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
						$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
			            $row['root_topic']=check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
					}
					else
					{
						$row['root_topic']=null;
					}
					$row = array_default_value($row);
					$row = check_field_to_relace($row,array('replys'=>'0','forwards'=>'0'));
					$list_data[]=$row;
				}

		}//end page
    	if(empty($detail_data)) {
    	    $detail_data = null;
    	}
	    if(empty($list_data)) {
    	    $list_data = null;
    	}
		$data['title']		= "detail_data";
		$data['data']=array(
						  'user_info'=>array_default_value($detail_data),
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
		$detail_data=DB::fetch_first("select uid,(select chadian from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as chadian,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as username,(select count(id) from jishigou_buddys where uid=".DB::table("common_member").".uid ) as guanzhu_num,(select count(id) from jishigou_buddys where buddyid=".DB::table("common_member").".uid ) as fensi_num,(select count(tid) from jishigou_topic where uid=".DB::table("common_member").".uid and type='first' ) as dongtai_num,(select count(id) from jishigou_buddys where uid='".$login_uid."' and  buddyid='".$get_uid."' ) as is_guanzhu,groupid,(select bio from ".DB::table("common_member_profile")." where uid=".DB::table('common_member').".uid) as content from ".DB::table("common_member")." where uid='".$get_uid."' ");
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";
		$detail_data['msg_num']=1;
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
				$query = DB::query("select baofen_id,uid,field_id,fenzhan_id,par,score,pars,total_score,lun,FROM_UNIXTIME(dateline, '%Y-%m-%d') as dateline,(select event_name from tbl_event where event_id=tbl_baofen.event_id) as event_name from tbl_baofen where event_id>0 and uid=$get_uid $strwhere order by addtime desc limit $page_start2,$page_size2");
				while($row = DB::fetch($query))
				{
					$row['event_name']=$row['event_name']." ";
					$row['iframe_url']=$site_url."/nd/score.php?ndid=".$row['baofen_id']."&size=small";
					$score_list[] = array_default_value($row); 
				}
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
			$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where  uid='".$get_uid."' order by dateline desc limit $page_start,$page_size ");
			while($row = DB::fetch($list) )
			{
				if($row['photo'])
				{
					$row['photo_big']=$site_url."/weibo/".$row['photo'];
					$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
				}
				else
				{
					$row['photo_big']=null;
					$row['photo_small']=null;
				}
				unset($row['photo']);

				$row['content']=cutstr_html($row['content']);
				$row['dateline']=date("Y-m-d G:i",$row['dateline']);
				
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				if($row['voice'])
				{
					$row['voice']=$site_url."/weibo/".$row['voice']."";
				}

				$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
				if($root_topic)
				{
					if($root_topic['photo'])
					{
						$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
						$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
					}
					else
					{
						$root_topic['photo_big']=null;
						$root_topic['photo_small']=null;
					}
					unset($root_topic['photo']);
					$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
					$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
					$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
					if($root_topic['voice'])
					{
						$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
					}
					$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
			        $row['root_topic']=check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
				}
				else
				{
					$row['root_topic']="";
				}
				$row = array_default_value($row);
				$list_data[]=$row;
			}

		}//end page
	    if(empty($list_data)) {
    	    $list_data = null;
    	}
	    if(empty($detail_data)) {
    	    $detail_data = null;
    	}
	    if(empty($score_list)) {
    	    $score_list = null;
    	}
		$data['title']		= "detail_data";
		$data['data']=array(
						  'user_info'=>array_default_value($detail_data),
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
		$data['data']		= array_default_value($detail_data);
	    if(empty($detail_data)) {
    	    $detail_data = null;
    	}
		api_json_result(1,0,$app_error['event']['10502'],$data);

	}

}



//关注我的   粉丝列表 某人   //他粉丝列表
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
				$list_data[]=array_default_value($row);
			}
		}
	    if(empty($detail_data)) {
    	    $detail_data = null;
    	}
	    if(empty($list_data)) {
    	    $list_data = null;
    	}
		$data['title']		= "list";
		$data['data']		=array(
			'user_info'=>array_default_value($detail_data),
			'list_info'=>$list_data,
		);
		//print_r($data);
		api_json_result(1,0,$app_error['event']['10502'],$data);
	}


}


//我关注的  关注列表 某人  //他关注列表
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
				$list_data[]=array_default_value($row);
			}
		}
	}
    
    if(empty($detail_data)) {
	    $detail_data = null;
	}
    if(empty($list_data)) {
	    $list_data = null;
	}
	$data['title']		= "list";
	$data['data']		=array(
		'user_info'=>array_default_value($detail_data),
		'list_info'=>$list_data,
	);
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

}


//球友圈 找朋友
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
			$city_list[]=array_default_value($row);
		}
	}

	//同一球场
	if($type=="qiuchang")
	{

		$field_id=DB::fetch_first("select field_id from tbl_baofen where uid='".$uid."' order by dateline desc limit 1  ");
		if($field_id)
		{
			$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where  uid in ( SELECT uid FROM ( select uid from tbl_baofen where field_id='".$field_id."' ) as t2 ) ".$not_in_sql." limit 6 ");
			while($row = DB::fetch($list))
			{
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				$list_data[]=array_default_value($row);
			}
		}

	}


	//同一赛事
	if($type=="saishi")
	{
		$event_id=1000333;
		$my_event=DB::result_first("select count(uid) from tbl_baofen where uid='".$uid."' and event_id='".$event_id."'  ");
		if($my_event>0)
		{
			$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid) as is_guanzhu from ".DB::table("common_member")." where uid in ( SELECT uid FROM ( select uid from tbl_baofen where event_id='".$event_id."' ) as t2 ) ".$not_in_sql." limit 6 ");

			//$list=DB::query("select uid,(select realname from ".DB::table("common_member_profile")." where uid=".DB::table("common_member").".uid) as realname,(select count(id) from jishigou_buddys where uid='".$uid."' and  buddyid=".DB::table("common_member").".uid ) as is_guanzhu from ".DB::table("common_member")." where  uid in (select uid from ".DB::table('common_score')." where sais_id in (select sais_id  from ".DB::table('common_score')." where uid='".$uid."' ) ) ".$not_in_sql." limit 6 ");
			while($row = DB::fetch($list))
			{
				$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
				$list_data[]=array_default_value($row);
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
			$list_data[]=array_default_value($row);
		}
	}

    if(empty($list_data)) {
	    $list_data = null;
	}
	$data['title']		= "list";
	$data['data']     =  array(
					  'list_data'=>$list_data,
					 );
	//print_r($data);
	api_json_result(1,0,$app_error['event']['10502'],$data);

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
			$search_list[]=array_default_value($row);
		}

	    if(empty($search_list)) {
	        $search_list = null;
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

				$list=DB::query("select tid,uid,roottid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where type<>'reply' and content like '%<M ".$username.">%' order by dateline desc limit $page_start,$page_size ");
				while($row = DB::fetch($list) )
				{
					if($row['photo'])
					{
						$row['photo_big']=$site_url."/weibo/".$row['photo'];
						$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
					}
					else
					{
						$row['photo_big']=null;
						$row['photo_small']=null;
					}
					unset($row['photo']);

					$row['content']=cutstr_html($row['content']);
					$row['dateline']=date("Y-m-d G:i",$row['dateline']);
					
					$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
					if($row['voice'])
					{
						$row['voice']=$site_url."/weibo/".$row['voice']."";
					}

					//根topic
					$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
					if($root_topic)
					{
						if($root_topic['photo'])
						{
							$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
							$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
						}
						else
						{
							$root_topic['photo_big']=null;
							$root_topic['photo_small']=null;
						}
						unset($root_topic['photo']);
						$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
						$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
						$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
						if($root_topic['voice'])
						{
							$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
						}
						$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
			            $row['root_topic']=check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
					}
					else
					{
						$row['root_topic']="";
					}
                    $row = array_default_value($row);
					$list_data[]=$row;
				}

			}//end page
	        if(empty($list_data)) {
	            $list_data = null;
	        }
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
	if($uid)
	{
		$res=DB::query("update jishigou_members set topic_new=0 where uid='".$uid."' ");
		if($_G['gp_is_hulue'])
		{
			api_json_result(1,0,"成功了！",$data);
		}
		else
		{
			//分页
			$total=DB::result_first("select count(tid) from jishigou_topic where  touid='".$uid."' and type='reply' ");
			$max_page=intval($total/$page_size);
			if($max_page<$total/$page_size)
			{
				$max_page=$max_page+1;
			}
			if($max_page>=$page)
			{

					$list=DB::query("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where touid='".$uid."' and type='reply' order by dateline desc limit $page_start,$page_size ");
					while($row = DB::fetch($list) )
					{
						if($row['photo'])
						{
							$row['photo_big']=$site_url."/weibo/".$row['photo'];
							$row['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$row['photo']);
						}
						else
						{
							$row['photo_big']=null;
							$row['photo_small']=null;
						}
						unset($row['photo']);

						$row['content']=cutstr_html($row['content']);
						$row['dateline']=date("Y-m-d G:i",$row['dateline']);
						
						$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
						if($row['voice'])
						{
							$row['voice']=$site_url."/weibo/".$row['voice']."";
						}


						//根topic
						$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$row['roottid']."' order by dateline asc ");
						if($root_topic)
						{
							if($root_topic['photo'])
							{
								$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
								$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
							}
							else
							{
								$root_topic['photo_big']=null;
								$root_topic['photo_small']=null;
							}
							unset($root_topic['photo']);
							$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
							$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
							$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
							if($root_topic['voice'])
							{
								$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
							}
							$row['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
			                $row['root_topic']=check_field_to_relace($row['root_topic'],array('replys'=>'0','forwards'=>'0'));
						}
						else
						{
							$row['root_topic']="";
						}


						$row=array_default_value($row);
    					$list_data[]=$row;
					}
				
			}// end page
            
		    if(empty($list_data)) {
	            $list_data = null;
	        }
			$data['title']		= "list_data";
			$data['data']=array(
							  'list_info'=>$list_data,
							 );
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		
	}

}




//微博具体页
if($ac=="topic_detail")
{
	$tid=$_G['gp_tid'];
	if($tid)
	{
		$detail_data=DB::fetch_first("select tid,uid,roottid,
(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$tid."' ");

		$detail_data=array_default_value($detail_data);
        $detail_data = check_field_to_relace($detail_data,array('replys'=>'0','forwards'=>'0'));
		if($detail_data['photo'])
		{
			$detail_data['photo_big']=$site_url."/weibo/".$detail_data['photo'];
			$detail_data['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$detail_data['photo']);
		}
		else
		{
			$detail_data['photo_big']=null;
			$detail_data['photo_small']=null;
		}
		unset($detail_data['photo']);

		$link_url=get_content_links($detail_data['content']);
		$detail_data['content_url']=$link_url['link'];
		$detail_data['content_fuwenben']="东莞峰景培苗慈善赛3月20日圆满落幕<a href=\"".$site_url."/blog-1051-25540.html\">".$site_url."/blog-1051-25540.html</a>";

		$detail_data['content']=cutstr_html($detail_data['content']);
		$detail_data['dateline']=date("Y-m-d G:i",$detail_data['dateline']);

		if($detail_data['voice'])
		{
			$detail_data['voice']=$site_url."/weibo/".$detail_data['voice']."";
		}

		
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";

		$reply_list=DB::query("select tid,uid,
(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,type,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo from jishigou_topic where totid='".$tid."'  order by dateline desc limit 10 ");
		while($row2 = DB::fetch($reply_list) )
		{
			$row2['content']=cutstr_html($row2['content']);
			$row2['dateline']=date("Y-m-d G:i",$row2['dateline']);
			$row2['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row2['uid']."&size=small";
			
			$row2=array_default_value($row2);
            $row2 = check_field_to_relace($row2,array('replys'=>'0','forwards'=>'0'));
			$reply_data[]=$row2;
		}
		$detail_data['touxiang']=$site_url."/uc_server/avatar.php?uid=".$detail_data['uid']."&size=middle";

		//根topic
		$root_topic=DB::fetch_first("select tid,uid,(select realname from ".DB::table("common_member_profile")." where uid=jishigou_topic.uid) as username,content,content2,replys,forwards,dateline,(select photo from jishigou_topic_image where tid=jishigou_topic.tid limit 1) as photo,voice,voice_timelong from jishigou_topic where tid='".$detail_data['roottid']."' order by dateline asc ");
		if($root_topic)
		{
			if($root_topic['photo'])
			{
				$root_topic['photo_big']=$site_url."/weibo/".$root_topic['photo'];
				$root_topic['photo_small']=$site_url."/weibo/".str_replace("_o","_s",$root_topic['photo']);
			}
			else
			{
				$root_topic['photo_big']=null;
				$root_topic['photo_small']=null;
			}
			unset($root_topic['photo']);
			$root_topic['content']=cutstr_html($root_topic['content'].$root_topic['content2']);
			$root_topic['dateline']=date("Y-m-d G:i",$root_topic['dateline']);
			$root_topic['touxiang']=$site_url."/uc_server/avatar.php?uid=".$root_topic['uid']."&size=small";
			if($root_topic['voice'])
			{
				$root_topic['voice']=$site_url."/weibo/".$root_topic['voice']."";
			}
			$detail_data['root_topic']=empty($root_topic) ? null : array_default_value($root_topic);
            $detail_data['root_topic'] = check_field_to_relace($detail_data['root_topic'],array('replys'=>'0','forwards'=>'0'));
		}
		else
		{
			$detail_data['root_topic']="";
		}
        
	    if(empty($detail_data)) {
            $detail_data = null;
        }
	    if(empty($reply_data)) {
            $reply_data = null;
        }
		if($detail_data['tid'])
		{
			$data['title']		= "detail";
			$data['data']		= array(
					'detail_info'=> array_default_value($detail_data),
					'reply_list'=>$reply_data
			);
			//print_r($data);
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
	}

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



//系统消息
if($ac=="system_msg")
{
	$uid=$_G['gp_uid'];
	//$msg =DB::fetch_first( " select `newpm`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new` from ultrax.jishigou_members where uid =".$this->var['uid']);
	$msg =DB::fetch_first( " select `newpm`,`push_new`,`qun_new`,`comment_new`,`fans_new`,`at_new`,`favoritemy_new`,`vote_new`,`topic_new` from ultrax.jishigou_members where uid =".$uid);

	$data['title']		= "detail_data";
	$data['data']		= array_default_value($msg);//{"newpm":"1","push_new":"0","qun_new":"0","comment_new":"0","fans_new":"0","at_new":"0","favoritemy_new":"0","vote_new":"0","topic_new":"0"}}
	$check_to_default = array(
	                    'newpm'=>'1',
						'push_new'=>'0',
	                    'qun_new'=>'0',
                    	'comment_new'=>'0',
                    	'fans_new'=>'0',
                    	'at_new'=>'0',
                    	'favoritemy_new'=>'0',
                    	'vote_new'=>'0',
                    	'topic_new'=>'0'
	                );
	$data['data']		= check_field_to_relace($msg,$check_to_default);
	
	api_json_result(1,0,$app_error['event']['10502'],$data);
}


//推送消息列表
if($ac=="push_msg_list")
{
	$uid=$_G['gp_uid'];
    
	//$list=DB::query("select message_id,message_number,message_type,uid,message_title,message_content,message_sendtime from tbl_push_message where uid='".$uid."' and uid=0 ");
	$list=DB::query("select message_id,message_number,message_type,uid,message_title,message_content,message_sendtime from tbl_push_message where uid='$uid'");
    
	while($row=DB::fetch($list))
	{
	    if(!json_parser($row['message_content']))
		{
	        continue;
	    }
	    $msg=json_decode($row['message_content'],true);
		
	    $row['message_info']=$msg;
	    
		$row['message_sendtime']=date("Y-m-d",$row['message_sendtime']);
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


//评论我的 回复我的评论
if($ac=="blog_comment_me")
{

	$uid=$_G['gp_uid'];//登录uid
	$res=DB::query("update jishigou_members set comment_new=0 where uid='".$uid."' ");
	if($_G['gp_is_hulue'])
	{
		api_json_result(1,0,"成功了！",$data);
	}
	else
	{

		$list=DB::query("select cid,uid,id,idtype,authorid,author,dateline,message from tbl_comment where uid='".$uid."' and parent_cid>0 order by dateline desc  limit 10 ");
		while($row=DB::fetch($list))
		{
			$blog=DB::fetch_first("select subject,username from ".DB::table("home_blog")." where blogid='".$row['id']."' ");
			$row['blog_title']=$blog['subject'];
			$row['blog_username']=$blog['username'];

			$arr=explode("</blockquote>",$row['message']);
			if(count($arr)>1)
			{
				$row['message_parent']=cutstr_html($arr[0]);
				$row['message']=cutstr_html($arr[1]);
			}
			else
			{
				$row['message_parent']="";
				$row['message']=cutstr_html($row['message']);
			}

			$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";

			$row['dateline']=date("Y-m-d G:i:s",$row['dateline']);
			
			$list_data[]=array_default_value($row);
		}

		if($list_data)
		{
			$data['title']="list";
			$data['data']=$list_data;
			api_json_result(1,0,$app_error['event']['10502'],$data);
		}
		else
		{
			api_json_result(1,0,"没有数据",$data);
		}

	}

}


//更新用户信息
if($ac=="update_member_info")
{
	$uid=$_G['gp_uid'];//当前登录uid
	$realname=trim(urldecode($_G['gp_realname']));
	$email=$_G['gp_email'];
	$chadian=$_G['gp_chadian'];	
	if($uid && $realname && $email)
	{
			if($_FILES["pic"]["error"]<=0 && $_FILES["pic"]["name"])
			{
				$full_save_path="../upload/avatar_cache/";
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

					
					$filename_s = '../uc_server/data/avatar/'.get_avatar($uid, "small", $type,"filename");
					$filename_m = '../uc_server/data/avatar/'.get_avatar($uid, "middle", $type,"filename");
					$filename_b = '../uc_server/data/avatar/'.get_avatar($uid, "big", $type,"filename");


					if($pic_source && $extname)
					{
						$aa=resizeImage($pic_source,48,48,$filename_s,".".$extname);
						$aa=resizeImage($pic_source,120,120,$filename_m,".".$extname);
						$aa=resizeImage($pic_source,200,220,$filename_b,".".$extname);

						if(file_exists($file_url))
						{
							//$result=unlink($file_url);
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
		$res3=DB::query("update ".DB::table("common_member_profile")." set realname='".$realname."',chadian='".$chadian."' where uid='".$uid."' ");
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
/**
 * 检查一维数组并替换数组中的某一项元素
 * @param array $arr      将要替换的数组
 * @param array $fields   要替换的字段
 * $fields = array(
 * 				'key'=>'val'   //key:将要替换的字段；val:最终替换后的结果
 * 			);
 */
function check_field_to_relace($arr=array(), $fields=array()) {
    if(empty($arr) || empty($fields)) {
        return null;
    }
    
    
    foreach($arr as $key=>$val) {
        if(is_array($arr[$key])) {
           //$arr[$key] = current(array_map(__FUNCTION__,array($arr[$key]),array($fields)));//处理多维度
           //$arr[$key] = check_field_to_relace($arr[$key],$fields);//处理多维度
           continue;
        }
        if(isset($fields[$key]) && isset($arr[$key]) && empty($arr[$key])) {
            $arr[$key]=$fields[$key];
        }
    }

    
    return $arr;
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