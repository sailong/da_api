<?php
/*
*
* bwvip.com
* 门票相关
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
$page2=$_G['gp_page'];
if(!$page2)
{
	$page2=1;
}
$page_size2=$_G['gp_page_size'];
if(!$page_size2)
{
	$page_size2=9;
}
if($page2==1)
{
	$page_start2=0;
}
else
{
	$page_start2=($page2-1)*($page_size2);
}

$root_path = dirname(dirname(dirname(dirname(__FILE__))));



//赛事 列表
function event_list_for_ticket()
{
	$field_uid = $_G['gp_field_uid'];
	if($field_uid)
	{
		$big_where=" and (event_viewtype='B' or (event_viewtype='A'  and field_uid='".$field_uid."') or (event_viewtype='Q' and field_uid='".$field_uid."'))  and event_is_ticket='Y' ";
	}
	else
	{
		$big_where=" and (event_viewtype='B' or event_viewtype='A' or event_viewtype='S') and event_is_ticket='Y' ";
	}
	
	$sql = "select event_id,event_name,field_uid,event_logo,event_starttime,event_endtime,event_ticket_status,event_ticket_wapurl from tbl_event where 1 ".$big_where." order by event_sort desc ";
	
	$list=DB::query($sql);
	$event_list = array();
	while($row = DB::fetch($list))
	{
		$row['event_logo'] = $site_url.'/'.$row['event_logo'];
		$y_s=date('m',$row['event_starttime']);
		$d_s=date('d',$row['event_starttime']);
		$y_e=date('m',$row['event_endtime']);
		$d_e=date('d',$row['event_endtime']);
		if($y_s==$y_e)
		{
			$row['event_starttime']=$y_s."月".$d_s."日-".$d_e."日";
		}
		else
		{
			$row['event_starttime']=$y_s."月".$d_s."日-".$y_e."月".$d_e."日";
		}
		/*
		$row['event_starttime'] = date('Y年m月d日',$row['event_starttime']);
		$row['event_starttime'] = $row['event_starttime']." - ".date('Y年m月d日',$row['event_endtime']);
		*/
		$row['wab_url'] = $row['event_ticket_wapurl'];
		
		
		$row2 = DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where field_uid='".$row['field_uid']."' and ad_page='ticket' order by ad_sort desc limit 1");
		
		$arr=explode("|",$row2['ad_url']);
		if(count($arr)>1)
		{
			$row2['ad_action']=$arr[0];
			$row2['ad_action_id']=$arr[1];
			$row2['ad_action_text']=$arr[2];
			$row2['event_url']=$arr[3];
		}
	
		if($row2['ad_file'])
		{
			$row2['ad_file']="".$site_url."/".$row2['ad_file'];
		}
		if($row2['ad_file_iphone4'])
		{
			$row2['ad_file_iphone4']="".$site_url."/".$row2['ad_file_iphone4'];
		}
		if($row2['ad_file_iphone5'])
		{
			$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
		}
		
		if(!empty($row2))
		{
			$row['ad_list']=$row2;
		}
		else
		{
			$row['ad_list']=null;
		}
		$event_list[] = $row;
	}
	if(empty($event_list))
	{
		$event_list = null;
	}
	$data['title'] = 'event_list';
	$data['data'] = $event_list;
	
	api_json_result(1,0,"成功",$data);
	
}




//我的门票列表
if($ac=="user_ticket_list")
{

	$big_where="";
	$is_zengsong=$_G['gp_is_zengsong'];
	if($is_zengsong)
	{
		$big_where .=" and is_zengsong='".$is_zengsong."' ";
	}
	
	$ticket_id=$_G['gp_ticket_id'];
	if($ticket_id)
	{
		$big_where .=" and ticket_id='".$ticket_id."' ";
	}
	
	$event_id=$_G['gp_event_id'];
	if($event_id)
	{
		$big_where .=" and event_id='".$event_id."' ";
	}
	
	
	$uid=$_G['gp_uid'];
	if($uid)
	{
		$total=DB::result_first("select user_ticket_id,ticket_id,ticket_type,event_id,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_addtime,is_zengsong from tbl_user_ticket where uid='".$uid."'  ".$big_where."  ");
		
		$max_page=intval($total/$page_size);
		if($max_page<$total/$page_size)
		{
			$max_page=$max_page+1;
		}
		if($max_page>=$page)
		{
			
			$list=DB::query("select user_ticket_id,ticket_id,ticket_type,event_id,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_addtime from tbl_user_ticket where uid='".$uid."' order by user_ticket_addtime desc limit $page_start,$page_size ");
			while($row = DB::fetch($list))
			{
				$row['ticket_info']=DB::fetch_first("select ticket_name,ticket_starttime,ticket_endtime from tbl_ticket where ticket_id='".$row['ticket_id']."' ");
				$row['ticket_name']=$row['ticket_info']['ticket_name'];

				$row['ticket_starttime']=date("Y年m月d日",$row['ticket_info']['ticket_starttime']);
				$row['ticket_endtime']=date("Y年m月d日",$row['ticket_info']['ticket_endtime']);
				
				
				$row['ticket_name']=$row['ticket_info']['ticket_name'];
				if($row['user_ticket_codepic'])
				{
					$row['user_ticket_codepic']=$site_url."/".$row['user_ticket_codepic'];
				}
				
				
				$row['event_info']=DB::fetch_first("select event_logo,event_name,event_starttime,event_endtime from tbl_event where event_id='".$row['event_id']."' ");
				$row['event_logo']=$row['event_info']['event_logo'];
				if($row['event_logo'])
				{
					$row['event_logo']=$site_url."/".$row['event_logo'];
				}
				$row['event_name']=$row['event_info']['event_name'];
				$row['event_starttime']=date("Y年m月d日",$row['event_info']['event_starttime']);
				$row['event_endtime']=date("Y年m月d日",$row['event_info']['event_endtime']);
				unset($row['event_info']);
				
				
				
				$row['user_ticket_addtime']=date("Y年m月d日",$row['user_ticket_addtime']);
				
				unset($row['ticket_info']);
				
				$list_data[]=array_default_value($row,array('ticket_info'));
			}

		}
		
		$data['title'] = "list_data";
		$data['data']=$list_data;
		
		api_json_result(1,0,null,$data);
	}
	else
	{
		api_json_result(1,1,'uid不能为空',$data);
	}

}





//我的门票列表
if($ac=="user_ticket_detail")
{
	
	$user_ticket_id=$_G['gp_user_ticket_id'];
	if($user_ticket_id)
	{
		$detail_info=DB::fetch_first("select *,(select ticket_name from tbl_ticket where ticket_id=tbl_user_ticket.ticket_id) as ticket_name from tbl_user_ticket where user_ticket_id='".$user_ticket_id."'  ");
		if($detail_info['user_ticket_id'])
		{
			if($detail_info['user_ticket_codepic'])
			{
				$detail_info['user_ticket_codepic']=$site_url."/".$detail_info['user_ticket_codepic'];
			}
			
			$detail_info['ticket_starttime']=date("Y年m月d日",$detail_info['ticket_starttime']);
			$detail_info['ticket_endtime']=date("Y年m月d日",$detail_info['ticket_endtime']);
			$detail_info['user_ticket_addtime']=date("Y年m月d日",$detail_info['user_ticket_addtime']);
			
			
			
			
			
			$row2 = DB::fetch_first("select ad_url,ad_file,ad_file_iphone4,ad_file_iphone5,ad_width,ad_height from tbl_ad where event_id='".$detail_info['event_id']."' and ad_page='ticket' order by ad_sort desc limit 1");
		
			$arr=explode("|",$row2['ad_url']);
			if(count($arr)>1)
			{
				$row2['ad_action']=$arr[0];
				$row2['ad_action_id']=$arr[1];
				$row2['ad_action_text']=$arr[2];
				$row2['event_url']=$arr[3];
			}
		
			if($row2['ad_file'])
			{
				$row2['ad_file']="".$site_url."/".$row2['ad_file'];
			}
			if($row2['ad_file_iphone4'])
			{
				$row2['ad_file_iphone4']="".$site_url."/".$row2['ad_file_iphone4'];
			}
			if($row2['ad_file_iphone5'])
			{
				$row2['ad_file_iphone5']="".$site_url."/".$row2['ad_file_iphone5'];
			}
			
			if(!empty($row2))
			{
				$detail_info['ad_list']=array_default_value($row2);
			}
			else
			{
				$detail_info['ad_list']=null;
			}
		
		
			$detail_info=array_default_value($detail_info,array('ad_list'));
		
		
			$data['title'] = "data";
			$data['data']=$detail_info;
			
			api_json_result(1,0,null,$data);
		}
		else
		{
			api_json_result(1,1,'订单不存在',$data);
		}
		
		
	}
	else
	{
		api_json_result(1,1,'参数不能为空',$data);
	}

}




//赠送
if($ac=="zengsong")
{
	$user_ticket_id=intval($_G['gp_user_ticket_id']);
	$m_arr=explode("^",$_G['gp_ids']);
	
	for($i=0; $i<count($m_arr); $i++)
	{
		$sub_m=explode("|",$m_arr[$i]);
		$zengsong_num=intval($sub_m[1]);
		$zengsong_mobile=intval($sub_m[0]);
		
		if($zengsong_mobile && $zengsong_num && $user_ticket_id)
		{
			
			$username=get_number(8,"0123456789");
			$password=get_number(6,"0123456789");
			if(ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+",$zengsong_mobile))
			{
				$email=$zengsong_mobile;
			}
			else
			{
				$email=time()."@zengsong.com";
			}
			
			preg_match_all("/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$zengsong_mobile, $if_mobile);
			if($if_mobile)
			{
				$mobile=$zengsong_mobile;
			}
			else
			{
				$mobile="";
			}
			$realname="";

		
			//注册账号
			$uid = uc_user_register($username, $password, $email);
			if($uid <= 0)
			{
				$username=get_number(8,"0123456789");
				$uid = uc_user_register($username, $password, $email);
			}
		
			
			$uid=3802672;
			
			if($uid>0)
			{
			
				$post_string = "&username=".$username."&password=".$password."";
				$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
				
				DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
				DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'"); 

				DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$uid'");
				
				
				//赠送
				$sql="INSERT INTO `tbl_user_ticket` (`parent_id`,`uid`, `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, `user_ticket_nums`, `user_ticket_realname`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `user_ticket_mobile`, `user_ticket_imei`, `user_ticket_company`, `user_ticket_company_post`, `user_ticket_status`, `user_ticket_addtime`,`is_zengsong`) 
				select `user_ticket_id`,".$uid.", `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, ".$zengsong_num.", `user_ticket_realname`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `".$zengsong_mobile."`, `user_ticket_imei`, `user_ticket_company`, `user_ticket_company_post`, `user_ticket_status`, ".time().", 'Y' from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ";
				DB::query($sql);

				//echo $sql;
				//echo "<hr>";
				
				DB::query("update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ");
				//echo "update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ";
				//echo "<hr>";
				
			}
			
			
		}
	}
	
	api_json_result(1,0,'赠送成功',$data);
	
}






//邮寄
if($ac=="youji")
{
	$user_ticket_id=intval($_G['gp_user_ticket_id']);
	$m_arr=explode("^",$_G['gp_ids']);
	
	for($i=0; $i<count($m_arr); $i++)
	{
		$sub_m=explode("|",$m_arr[$i]);
		
		
		$zengsong_realname=intval($sub_m[0]);
		$zengsong_address=intval($sub_m[1]);
		$zengsong_post=intval($sub_m[2]);
		$zengsong_mobile=intval($sub_m[3]);
		$zengsong_num=intval($sub_m[4]);
		
		if($zengsong_mobile && $zengsong_num && $user_ticket_id)
		{
			
			$username=get_number(8,"0123456789");
			$password=get_number(6,"0123456789");
			if(ereg("^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+",$zengsong_mobile))
			{
				$email=$zengsong_mobile;
			}
			else
			{
				$email=time()."@zengsong.com";
			}
			
			preg_match_all("/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$zengsong_mobile, $if_mobile);
			if($if_mobile)
			{
				$mobile=$zengsong_mobile;
			}
			else
			{
				$mobile="";
			}
			
			$realname=$zengsong_realname;

		
			//注册账号
			$uid = uc_user_register($username, $password, $email);
			if($uid <= 0)
			{
				$username=get_number(8,"0123456789");
				$uid = uc_user_register($username, $password, $email);
			}
		
			
			$uid=3802672;
			
			if($uid>0)
			{
			
				$post_string = "&username=".$username."&password=".$password."";
				$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
				
				DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
				DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'"); 

				DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$uid'");
				
				
				//赠送
				$sql="INSERT INTO `tbl_user_ticket` (`parent_id`,`uid`, `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, `user_ticket_nums`, `user_ticket_realname`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `user_ticket_mobile`, `user_ticket_imei`, `user_ticket_company`, `user_ticket_company_post`, `user_ticket_status`, `user_ticket_addtime`,`is_zengsong`) 
				select `user_ticket_id`,`".$uid."`, `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, `".$zengsong_num."`, `".$zengsong_realname."`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `".$zengsong_mobile."`, `user_ticket_imei`, `".$zengsong_address."`, `".$zengsong_post."`, `user_ticket_status`, `".time()."`, 'Y' from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ";
				//DB::query($sql);

				echo $sql;
				echo "<hr>";
				
				//DB::query("update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ");
				//echo "update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ";
				//echo "<hr>";
				
			}
			
			
		}
	}
	
	api_json_result(1,0,'邮寄成功',$data);
	
}










?>