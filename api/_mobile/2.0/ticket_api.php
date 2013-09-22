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
	$source=$_G['gp_source'];
	$arr=explode("^",$source);
	if(count($arr)>1)
	{
		$big_where .=" and ( 1 ";
		for($i=0; $i<count($arr); $i++)
		{
			if($arr[$i])
			{
				$big_where .=" or source='".$arr[$i]."' ";
			}
		}
		$big_where .=" )";
	}
	else
	{
		if($source)
		{
			$big_where .=" and source='".$source."' ";
		}
		
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
		$big_where .=" and uid='".$uid."' ";
	}
	
	$from_uid=$_G['gp_from_uid'];
	if($from_uid)
	{
		$big_where .=" and from_uid='".$from_uid."' ";
	}
	
	//echo "select user_ticket_id,ticket_id,source,ticket_type,event_id,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_addtime,source from tbl_user_ticket where 1  ".$big_where."  ";
	

	$total=DB::result_first("select user_ticket_id,ticket_id,source,ticket_type,event_id,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_addtime,source from tbl_user_ticket where 1 and user_ticket_status=1  ".$big_where."  ");
	
	$max_page=intval($total/$page_size);
	if($max_page<$total/$page_size)
	{
		$max_page=$max_page+1;
	}
	if($max_page>=$page)
	{
		
		$list=DB::query("select user_ticket_id,source,ticket_id,ticket_type,event_id,user_ticket_code,user_ticket_codepic,user_ticket_nums,user_ticket_addtime,user_ticket_more from tbl_user_ticket where 1 and user_ticket_status=1 ".$big_where." order by user_ticket_addtime desc limit $page_start,$page_size ");
		while($row = DB::fetch($list))
		{
			$row['ticket_info']=DB::fetch_first("select ticket_name,ticket_starttime,ticket_endtime,ticket_is_zengsong from tbl_ticket where ticket_id='".$row['ticket_id']."' ");
			$row['ticket_name']=$row['ticket_info']['ticket_name'];
			$row['ticket_is_zengsong']=$row['ticket_info']['ticket_is_zengsong'];

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





//我的门票列表
if($ac=="user_ticket_detail")
{
	
	$user_ticket_id=$_G['gp_user_ticket_id'];
	if($user_ticket_id)
	{
		$detail_info=DB::fetch_first("select *,(select ticket_name from tbl_ticket where ticket_id=tbl_user_ticket.ticket_id) as ticket_name,(select ticket_is_zengsong from tbl_ticket where ticket_id=tbl_user_ticket.ticket_id) as ticket_is_zengsong from tbl_user_ticket where user_ticket_id='".$user_ticket_id."'  ");
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
	
	
	$n=0;
	$e=0;
	for($i=0; $i<count($m_arr); $i++)
	{
		$sub_m=explode("|",$m_arr[$i]);
		$zengsong_num=intval($sub_m[1]);
		$zengsong_mobile=$sub_m[0];
		
	
		$user_ticket_info=DB::fetch_first("select user_ticket_id,user_ticket_nums,uid,ticket_id from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ");
		if($user_ticket_info['user_ticket_nums']>=$zengsong_num)
		{
			if($zengsong_mobile && $zengsong_num && $user_ticket_id)
			{
				$from_uid=$user_ticket_info['uid'];
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
				if(!empty($if_mobile[0]))
				{
					$mobile=$zengsong_mobile;
					$user_info=DB::fetch_first("select uid,mobile from pre_common_member_profile where mobile='{$mobile}' order by uid desc");
					$uid=$user_info['uid'];
				}
				else
				{
					$mobile="";
					$uid=0;
				}
				$realname="";

				
				
				if(!$uid)
				{
					//注册账号
					$uid = uc_user_register($username, $password, $email);
					if($uid <= 0)
					{
						$username=get_number(8,"0123456789");
						$uid = uc_user_register($username, $password, $email);
					}
				}
			
		
				if($uid>0)
				{
				
					$post_string = "&username=".$username."&password=".$password."";
					$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
					
					DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
					DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'"); 

					DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$uid'");
					
					
					//赠送
					$sql="INSERT INTO `tbl_user_ticket` (`parent_id`,`from_uid`,`uid`, `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, `user_ticket_nums`, `user_ticket_realname`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `user_ticket_mobile`, `user_ticket_imei`, `user_ticket_company`, `user_ticket_company_post`, `user_ticket_status`, `user_ticket_addtime`,`source`,`user_ticket_more`) 
					select user_ticket_id,'".$from_uid."','".$uid."', ticket_id, event_id, ticket_type, ticket_times, ticket_starttime, ticket_endtime, ticket_price, out_idtype, out_id,  user_ticket_code, user_ticket_codepic, '".$zengsong_num."', user_ticket_realname, user_ticket_sex, user_ticket_age, user_ticket_address, user_ticket_cardtype, user_ticket_card, '".$zengsong_mobile."', user_ticket_imei, user_ticket_company, user_ticket_company_post, '1', ".time().", 'S', '".$zengsong_mobile."' from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ";
					DB::query($sql);

					//echo $sql;
					//echo "<hr>";
					
					DB::query("update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ");
					//echo "update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ";
					//echo "<hr>";
					
					
					
					//send mobile message
					$ticket_info=DB::fetch_first("select ticket_name,(select event_name from tbl_event where event_id=tbl_ticket.event_id) as event_name from tbl_ticket where ticket_id='".$user_ticket_info['ticket_id']."' ");
					$user_info=DB::fetch_first("select realname,mobile from pre_common_member_profile where uid='".$from_uid."' ");
					
					$msg_content="您好，您的朋友".$user_info['realname']."（".$user_info['mobile']."）赠送您的一张《".$ticket_info['event_name']."》".$ticket_info['ticket_name']."。同时，您已成为大正网用户，且获得了一次抽奖机会。请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$mobile."，密码为:".$password."，大正客户端下载地址：http://www.bwvip.com/app ";
					if($mobile)
					{
						$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
						send_msg($mobile,$msg_content);
					}
					
					
					$n=n+1;
				}
				
				
			}
		
		}
		else
		{
			$e=$e+1;
		}
	}
	
	if($n>0)
	{
		api_json_result(1,0,'成功'.$n."人",$data);
	}
	else
	{
		api_json_result(1,1,'赠送失败',$data);
	}
	
	
	
}






//邮寄
if($ac=="youji")
{
	$user_ticket_id=intval($_G['gp_user_ticket_id']);
	$m_arr=explode("^",$_G['gp_ids']);
	
	$n=0;
	$e=0;
	for($i=0; $i<count($m_arr); $i++)
	{
	
		
		$sub_m=explode("|",$m_arr[$i]);
		//print_r($sub_m);
		
		$zengsong_realname=$sub_m[0];
		$zengsong_address=$sub_m[1];
		$zengsong_post=$sub_m[2];
		$zengsong_mobile=intval($sub_m[3]);
		$zengsong_num=intval($sub_m[4]);
		
		$user_ticket_info=DB::fetch_first("select user_ticket_id,user_ticket_nums,uid,ticket_id from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ");
		if($user_ticket_info['user_ticket_nums']>=$zengsong_num)
		{
		
			if($zengsong_mobile && $zengsong_num && $user_ticket_id)
			{
			
				$from_uid=$user_ticket_info['uid'];
				
				$username=$zengsong_realname;
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
				if(!empty($if_mobile[0]))
				{
					$mobile=$zengsong_mobile;
					$user_info=DB::fetch_first("select uid,mobile from pre_common_member_profile where mobile='{$mobile}' order by uid desc");
					$uid=$user_info['uid'];
				}
				else
				{
					$mobile="";
					$uid=0;
				}
				
				$realname=$zengsong_realname;

				if(!$uid)
				{
					//注册账号
					$uid = uc_user_register($username, $password, $email);
					if($uid <= 0)
					{
						$username=$zengsong_realname."".get_number(1,"0123456789");
						$uid = uc_user_register($username, $password, $email);
					}
				}
			
			
				//$uid=3802672;
				if($uid>0)
				{
					
		
					$post_string = "&username=".$username."&password=".$password."";
					$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
					
					DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
					DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'"); 

					DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$uid'");
			
					
					//赠送
					$sql="INSERT INTO `tbl_user_ticket` (`parent_id`,`from_uid`,`uid`, `ticket_id`, `event_id`, `ticket_type`, `ticket_times`, `ticket_starttime`, `ticket_endtime`, `ticket_price`, `out_idtype`, `out_id`,  `user_ticket_code`, `user_ticket_codepic`, `user_ticket_nums`, `user_ticket_realname`, `user_ticket_sex`, `user_ticket_age`, `user_ticket_address`, `user_ticket_cardtype`, `user_ticket_card`, `user_ticket_mobile`, `user_ticket_imei`, `user_ticket_company`, `user_ticket_company_post`, `user_ticket_status`, `user_ticket_addtime`,`source`,`user_ticket_more`) 
					select user_ticket_id,'".$from_uid."','".$uid."', ticket_id, event_id, ticket_type, ticket_times, ticket_starttime, ticket_endtime, ticket_price, out_idtype, out_id,  user_ticket_code, user_ticket_codepic, '".$zengsong_num."', '".$zengsong_realname."', user_ticket_sex, user_ticket_age, '".$zengsong_address."', user_ticket_cardtype, user_ticket_card, '".$zengsong_mobile."', user_ticket_imei, '', '".$zengsong_post."', '1', ".time().", 'Y', '".$zengsong_mobile."' from tbl_user_ticket where user_ticket_id='".$user_ticket_id."' ";
					DB::query($sql);

					//echo $sql;
					//echo "<hr>";
					
					DB::query("update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ");
					//echo "update tbl_user_ticket set user_ticket_nums=user_ticket_nums-".$zengsong_num." where user_ticket_id='".$user_ticket_id."' ";
					//echo "<hr>";
					
					//send mobile message
					$ticket_info=DB::fetch_first("select ticket_name,(select event_name from tbl_event where event_id=tbl_ticket.event_id) as event_name from tbl_ticket where ticket_id='".$user_ticket_info['ticket_id']."' ");
					$user_info=DB::fetch_first("select realname,mobile from pre_common_member_profile where uid='".$from_uid."' ");
					
					$msg_content="您好，您的朋友".$user_info['realname']."（".$user_info['mobile']."）赠送您的一张《".$ticket_info['event_name']."》".$ticket_info['ticket_name']."。同时，您已成为大正网用户，且获得了一次抽奖机会。请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$mobile."，密码为:".$password."，大正客户端下载地址：http://www.bwvip.com/app ";
					if($mobile)
					{
						$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
						send_msg($mobile,$msg_content);
					}
					
					$n=$n+1;
					
				}
				
				
			}
			
		}
		else
		{
			$e=$e+1;
		}
	}
	
	
	
	if($n>0)
	{
		api_json_result(1,0,'邮寄成功'.$n."人",$data);
	}
	else
	{
		api_json_result(1,1,'邮寄失败',$data);
	}
	
}





//用户预定门票信息
if($ac == 'ticket_apply')
{
	//post: user_ticket_mobile *user_ticket_imei* ticket_id ticket_type user_ticket_realname user_ticket_sex user_ticket_age  user_ticket_address *user_ticket_company user_ticket_company_post*
	//api生成: user_ticket_code user_ticket_codepic user_ticket_status
	if(empty($_G['gp_phone']))
	{
		api_json_result(1,1,"缺少参数phone",null);
	}
	if(empty($_G['gp_ticket_id']))
	{
		api_json_result(1,1,"缺少参数ticket_id",null);
	}
	
	$user_ticket_mobile = $_G['gp_phone'];//手机号
	$uid = $_G['gp_uid'];
	$user_ticket_imei = $_G['gp_phone_imei'];//手机窜号
	$ticket_id = $_G['gp_ticket_id'];//门票ID
	$ticket_nums = empty($_G['gp_ticket_nums']) ? 1 : $_G['gp_ticket_nums'];//门票数量
	$user_ticket_realname = urldecode($_G['gp_realname']);//订票人真实姓名
	$user_ticket_sex = urldecode($_G['gp_sex']);//性别
	$user_ticket_age = $_G['gp_age'];//年龄
	$user_ticket_address = urldecode($_G['gp_address']);//所在区域
	$user_ticket_company = urldecode($_G['gp_company']);//所在公司
	$user_ticket_company_post = urldecode($_G['gp_company_post']);//公司职位
	$user_ticket_code = get_randmod_str();//$_G['company_post'];//随机唯一窜
	$user_ticket_addtime = time();//$_G['company_post'];//随机唯一窜
	
	//没有uid则生成
	if(empty($uid)){
		$sql = "select uid,mobile from pre_common_member_profile where mobile='{$user_ticket_mobile}'";
		$rs=DB::fetch_first($sql);
		if(!empty($rs)){
			$uid=$rs['uid'];
		}else{
			$uid = user_add_return($user_ticket_mobile);
		}
	}
	$ticket_info=DB::fetch_first("select event_id,ticket_times,ticket_type,ticket_price,ticket_starttime,ticket_endtime from tbl_ticket where ticket_id='".$ticket_id."' limit 1 ");
	$event_id=$ticket_info['event_id'];
	$ticket_times=$ticket_info['ticket_times'];
	$ticket_starttime=$ticket_info['ticket_starttime'];
	$ticket_endtime=$ticket_info['ticket_endtime']; 
	$ticket_price = $ticket_info['ticket_price'];
	$ticket_type = $ticket_info['ticket_type'];
	//检查用户是否已提交申请
    $sql = "select user_ticket_id,ticket_id,user_ticket_codepic,ticket_price,user_ticket_status from tbl_user_ticket where ticket_id='{$ticket_id}' and ticket_type='".$ticket_type."' and (user_ticket_mobile='{$user_ticket_mobile}' or user_ticket_imei='{$user_ticket_imei}')";
    $list = DB::fetch_first($sql);
	
	$ticket_detail = DB::fetch_first("select ticket_name,ticket_price,ticket_ren_num,ticket_num,ticket_pic,ticket_starttime,ticket_endtime,ticket_times,ticket_content from tbl_ticket where ticket_id='{$ticket_id}' limit 1");
	$return_detail['ticket_name'] = $ticket_detail['ticket_name'];
	//$return_detail['ticket_starttime'] = date('Y年m月d日',$ticket_detail['ticket_starttime']);
	//$return_detail['ticket_endtime'] = date('Y年m月d日',$ticket_detail['ticket_endtime']);
	
	$data['title'] = 'erweima';
	
	$phone = mt_rand(1000000000,9999999999);
	$return_detail['user_ticket_code'] = $phone;
	//已经索取过
	if($list)
	{
		$erweima_path = erweima($phone);
		if(empty($erweima_path)) {
			api_json_result(1,1,"二维码生成失败",null);
		}
		$sql = "update tbl_user_ticket set user_ticket_code='{$phone}',user_ticket_codepic='{$erweima_path}' where user_ticket_id='".$list['user_ticket_id']."'";
		$res = DB::query($sql);
		if(empty($res))
		{
			api_json_result(1,1,"索取二维码失败",null);
		}
		
		if($ticket_price<1)
		{
			if(empty($list['user_ticket_codepic'])) 
			{
				$return_detail['ticket_pic'] = $site_url.$erweima_path;
			}
			else 
			{
				$return_detail['ticket_pic'] = $site_url.$list['user_ticket_codepic'];
				
			}
			
			//发系统消息
			$user_ticket_info = array(
				'event_id' => $event_id,
				'uid'      => $uid,
				'user_ticket_codepic' =>$return_detail['ticket_pic']
			);
			sys_message_add_return($user_ticket_info);
			$data['data'] =$return_detail;
			api_json_result(1,0,"索取门票成功",$data);
		}
		else
		{
			$data['title'] = 'erweima';
			$data['data'] = null;
			if($list['user_ticket_status'] == 1){
				$return_detail['ticket_pic'] = $site_url.$erweima_path;
				$data['data'] =$return_detail;
				api_json_result(1,0,"门票索取成功",$data);
			}
			
			api_json_result(1,0,"门票索取成功，等待审核",$data);
		}
	}
	else
	{
    
		
		//生成二维码
		$erweima_path = erweima($phone);
		$user_ticket_codepic = $erweima_path;
		
		$row=explode("/",$user_ticket_codepic);
		$user_ticket_code=str_replace(".png","",$row[4]);
		
		
		if($ticket_price > 0)
		{
			$user_ticket_status = 0;
		}else
		{
			$user_ticket_status = 1;
			
			$return_detail['ticket_pic'] = $site_url.$erweima_path;
			$return_detail['user_ticket_code'] = $phone;
			$data['data'] =$return_detail;
		
			//发系统消息
			$user_ticket_info = array(
				'event_id' => $event_id,
				'uid'      => $uid,
				'user_ticket_codepic' => $user_ticket_codepic
			);
			sys_message_add_return($user_ticket_info);
				
		}
		
		$sql = "insert into tbl_user_ticket(uid,ticket_id,event_id,ticket_type,user_ticket_code,user_ticket_codepic,user_ticket_realname,user_ticket_sex,user_ticket_age,user_ticket_address,user_ticket_mobile,user_ticket_imei,user_ticket_company,user_ticket_company_post,user_ticket_status,user_ticket_addtime,ticket_times,ticket_starttime,ticket_endtime,ticket_price) values('{$uid}','{$ticket_id}','{$event_id}','{$ticket_type}','{$user_ticket_code}','{$user_ticket_codepic}','{$user_ticket_realname}','{$user_ticket_sex}','{$user_ticket_age}','{$user_ticket_address}','{$user_ticket_mobile}','{$user_ticket_imei}','{$user_ticket_company}','{$user_ticket_company_post}','{$user_ticket_status}','{$user_ticket_addtime}','{$ticket_times}','{$ticket_starttime}','{$ticket_endtime}','{$ticket_price}')";
		$res = DB::query($sql);
		
		api_json_result(1,0,"门票索取成功",$data);
	}
	
	
}


//生成二维码成功返回路径，失败返回 false
function erweima($phone)
{
	
    //如果没有就生成二维码
	$path_erweima_core = dirname(dirname(dirname(dirname(__FILE__))));
	
	include $path_erweima_core."/tool/phpqrcode/qrlib.php";
	$prefix = $path_erweima_core;
	$save_path="/upload/erweima/";
	$now_date = date("Ymd",time());
	$full_save_path=$path_erweima_core.$save_path.$now_date."/";

	if(!file_exists($prefix.$save_path))
	{
		mkdir($prefix.$save_path);
	}
	if(!file_exists($full_save_path))
	{
		$a = mkdir($full_save_path);
	}
	
	$pic_filename=$full_save_path.$phone.".png";
	$sql_save_path = $save_path.$now_date.'/'.$phone.".png";
	$errorCorrectionLevel = "L";
	$matrixPointSize=9;
	$margin=1;
	
	QRcode::png($phone, $pic_filename, $errorCorrectionLevel, $matrixPointSize, $margin); 
	
	if(file_exists($pic_filename))
	{
		return $sql_save_path;
	}
	else
	{
		return false;
	}
}
//获取随机字符串
function get_randmod_str(){
	$str = 'abcdABCefgD69EFhigkGHI7nm8JKpqMNrs3PQRtuS5vw4TxyU1VWzXYZ20';
    $len = strlen($str); //得到字串的长度;

    //获得随即生成的积分卡号
    $s = rand(0, 1);
    $serial = '';

    for($s=1;$s<=10;$s++)
    {
       $key     = rand(0, $len-1);//获取随机数
       $serial .= $str[$key];
    }

   //strtoupper是把字符串全部变为大写
   $serial = strtoupper(substr(md5($serial.time()),10,10));
   if($s)
   {
      $serial = strtoupper(substr(md5($serial),mt_rand(0,22),10));
   }
   
   return $serial;

}


/*
*  添加用户注册
*/
function user_add_return($phone)
{
	
	$username=time(). mt_rand(1000,9999);//post("user_ticket_realname");	
	$password='123456';
	$salt = substr(uniqid(rand()), -6);
	$password = md5(md5($password).$salt);
	$salt=$salt;
	$password=$password;
	$email=$username.'@bw.com'; 
	$mobile=$phone; 
	$regip=time();
	$regdate=time();
	$gender = '';
	//生成ucenter会员
	$sql = "insert into pre_ucenter_members(username,salt,password,email,regip,regdate) values('{$username}','{$salt}','{$password}','{$email}','{$regip}','{$regdate}')";
	$rs = DB::query($sql);
	$ucuid=DB::insert_id();
	$groupid=10;  
	//生成社区会员
	$sql = "insert into pre_common_member(uid,username,password,email,regdate,groupid) values('{$ucuid}','{$username}','{$password}','{$email}','{$regdate}','{$groupid}')";
	$rs = DB::query($sql);
	
	$sql = "insert into pre_common_member_profile(uid,realname,gender,mobile,regdate) values('{$ucuid}','{$username}','{$gender}','{$mobile}','{$regdate}')";
	//生成真实姓名
	$rs = DB::query($sql);
	
	$role_id = 3;
	$sql = "insert into jishigou_members(uid,username,nickname,password,email,phone,regip,regdate,gender,role_id) values('{$ucuid}','{$username}','{$username}','{$password}','{$email}','{$mobile}','{$regip}','{$regdate}','{$gender}','{$role_id}')";
	///生成微博记录
	$rs = DB::query($sql);
	if($rs!=false)
	{
		return $ucuid;
	}
	else
	{
		return false;
	}
}
//添加系统消息
function sys_message_add_return($user_ticket_info)
{
	$sys_event_id = $user_ticket_info['event_id'];
	
	$sql = "select field_uid,event_name from tbl_event where event_id='{$sys_event_id}'";

	$sys_event_info = DB::fetch_first($sql);
	$sys_field_uid=$sys_event_info['field_uid'];
	if(empty($sys_field_uid)){
		$sys_field_uid = 0;
	}
	$field_uid=$sys_field_uid;
	if($user_ticket_info["uid"])
	{
		$sys_uid=$user_ticket_info["uid"];
	}
	else
	{
		$sys_uid=0;
	}
	$uid=$sys_uid;
	$message_title=$sys_event_info['event_name']."门票申请成功";

	$n_title=$message_title;
	$n_content=$message_title;
	
	$message_extinfo=array('action'=>"system_msg");	
	
	$msg_content = json_encode(array('n_title'=>urlencode($n_title), 'n_content'=>urlencode($n_content),'n_extras'=>$message_extinfo));

	$smessage_content=$msg_content;
	$receiver_type=3;//3:指定用户
	$message_pic=$user_ticket_info['user_ticket_codepic'];
	

	
	$message_totalnum=0;
	$message_sendnum=0;
	$message_errorcode="";
	$message_errormsg="";
	$message_addtime=time();
	
	$sql = "insert into tbl_sys_message(field_uid,uid,message_title,message_content,receiver_type,message_pic,message_totalnum,message_sendnum,message_errorcode,message_errormsg,message_addtime) values('{$field_uid}','{$uid}','{$message_title}','{$message_content}','{$receiver_type}','{$message_pic}','{$message_totalnum}','{$message_sendnum}','{$message_errorcode}','{$message_errormsg}','{$message_addtime}')";
	$rs = DB::query($sql);

	if($rs!=false)
	{
		return true;
	}
	else
	{				
		return false;
	}

}





if($ac="test")
{
	$mobile=18710066977;
	$msg_content="您好，您的朋友".$user_info['realname']."（".$user_info['mobile']."）赠送您的一张《".$ticket_info['event_name']."》".$ticket_info['ticket_name']."。同时，您已成为大正网用户，且获得了一次抽奖机会。请您下载并登录大正网客户端 个人中心，我的门票中查看具体信息。您的大正登录名为:".$mobile."，密码为:".$password."，大正客户端下载地址：http://www.bwvip.com/app ";
	$msg_content=iconv('UTF-8', 'GB2312', $msg_content);;
						if($mobile)
						{
							send_msg($mobile,$msg_content);
						}

}


?>