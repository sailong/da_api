<?php
/*
*
* bwvip.com
* 自媒体 第一线
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
$current_path = dirname(__FILE__); 





//申请第一线
if($ac=="zimeiti_apply")
{
	
	$tip_info['tip_text']="我同意协议";
	$tip_info['xieyi']="这里是协议内容";
	$tip_info['xieyi_url']="http://www.bwvip.com/xieyi/1.html";

	$list_data=include($current_path.'/data/tbl_zimeiti_apply_array_data.php');

	$data['title']="data";
	$data['data']=array(
		'tip_info'=>$tip_info,
		'list'=>$list_data
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}



//申请第一线 保存
if($ac=="zimeiti_apply_action")
{

	$uid=$_G['gp_uid'];
	$field_uid=$_G['gp_field_uid'];
	if(!$uid)
	{
		//自动注册
		$mobile=$_G['gp_zimeiti_apply_mobile'];
		$username=$_G['gp_zimeiti_apply_realname'];
		$password=get_number(6,"0123456789");
		//$password="123456";
		$email='diyixian_apply_'.time()."@bwvip.com";
		
		$uid = uc_user_register($username, $password, $email);
		if($uid == -3)
		{
			$username=$username."".get_number(1,"0123456789");
			$uid = uc_user_register($username, $password, $email);
		}
		
		
		if($uid>0)
		{
		
			$post_string = "&username=".$username."&password=".$password."";
			$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
			
			DB::query("UPDATE ultrax.jishigou_members SET nickname='$username',validate=1 WHERE ucuid='$uid'"); 
			DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$username',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$uid'"); 
			DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$uid'");
			
			$_G['gp_uid']=$uid;
		}
	
	}
	
	
	$apply_info=DB::fetch_first("select zimeiti_apply_id,zimeiti_apply_status from tbl_zimeiti_apply where uid='".$uid."' order by zimeiti_apply_id desc limit 1 ");
	if(!$apply_info['zimeiti_apply_id'])
	{
		$sql_data=include($current_path.'/data/tbl_zimeiti_apply_sql_data.php');
		DB::query($sql_data);
		
		
		//发送短信给推荐人
		$tjr_mobile=is_mobile($_G['gp_zimeiti_recommend_mobile']);
		if($tjr_mobile)
		{
			$new_apply=DB::fetch_first("select zimeiti_apply_id from tbl_zimeiti_apply where uid='".$uid."' and zimeiti_recommend_mobile='".$tjr_mobile."' ");
		
			$msg_content="您的好友 ".$_G['zimeiti_apply_realname']." ,手机号：".$mobile."  ，现申请“大正高尔夫”第一线权限，你是他的推荐人，是否同意他的申请？http://wap.bwvip.com/zimeiti_apply_confirm.php?zimeiti_apply_id='".$new_apply['zimeiti_apply_id']."'&uid=".$uid."&mobile=".$tjr_mobile;
		
			$sql_content=$msg_content;
			$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
			
			//检查是否已发送过验证码，如果有，则读取旧的验证码
			$task_info = DB::fetch_first( "select * from tbl_msg_task where mobile='".$tjr_mobile."' and msg_task_status=0 and msg_task_source='zimeiti_apply' order by msg_task_id desc ");
			if(!$task_info['msg_task_id'])
			{
				//如果没有任务，则添加
				$task_in="insert into tbl_msg_task (field_uid,mobile,msg_task_source,msg_task_status,msg_task_addtime,msg_task_date) values ('".$field_uid."','".$tjr_mobile."','zimeiti_apply','0','".$time."','".date("Y-m-d H:i:s",$time)."') ";
				DB::query($task_in);
				$msg_task_id = DB::result_first( "select msg_task_id from tbl_msg_task where mobile='".$tjr_mobile."' and msg_task_status=0 and msg_task_source='zimeiti_apply' order by msg_task_id desc limit 1 ");
			}
			else
			{
				$msg_task_id=$task_info['msg_task_id'];
			}
			
			send_mobile_msg($tjr_mobile,$msg_content,'','zimeiti_apply',$sql_content,$msg_task_id);
		
		}
		
		
		
		
		
		api_json_result(1,0,'您的申请已提交，我们会尽快处理。',$data);
	}
	else
	{
		if($apply_info['zimeiti_apply_status']==1)
		{
			api_json_result(1,1,'您已经是第一线用户，请不要重新申请',$data);
		}
		else
		{
			api_json_result(1,1,'您的申请已提交，请耐心等待，详情请致电：400-810-9966',$data);
		}
	}
	
	
	
}






//第一线邀请
if($ac=="zimeiti_yaoqing")
{
	
	$tip_info['tip_text']="";
	$tip_info['xieyi']="这里是协议内容";
	$tip_info['xieyi_url']="http://www.bwvip.com/xieyi/1.html";

	$list_data=include($current_path.'/data/tbl_zimeiti_yaoqing_array_data.php');

	$data['title']="data";
	$data['data']=array(
		'tip_info'=>$tip_info,
		'list'=>$list_data
	);
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}



//第一线邀请 保存
if($ac=="zimeiti_yaoqing_action")
{
	
	$field_uid=$_G['gp_field_uid'];
	$uid=$_G['gp_uid'];
	
	$mobile=is_mobile($_G['gp_mobile']);
	
	if($mobile)
	{
		
		$user_info=DB::fetch_first("select uid from pre_common_member_profile where mobile='".$mobile."' order by uid desc ");
		if(!$user_info['uid'])
		{
			//注册新用户
			$username=get_number(8,"0123456789");
			$password=get_number(6,"0123456789");
			$email='diyixian_yaoqing_'.time()."@bwvip.com";
			
			$to_uid = uc_user_register($username, $password, $email);
			if($to_uid == -3)
			{
				$username=$username."".get_number(1,"0123456789");
				$to_uid = uc_user_register($username, $password, $email);
			}
			
			
			if($to_uid>0)
			{
				//激活
				$post_string = "&username=".$username."&password=".$password."";
				$info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
				
				DB::query("UPDATE ultrax.jishigou_members SET nickname='$username',validate=1 WHERE ucuid='$to_uid'"); 
				DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$username',mobile='$mobile',cron_fensi_state=0,regdate='".time()."'  WHERE uid='$to_uid'"); 
				DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10' WHERE uid='$to_uid'");
			}
			
			$msg_content='您的朋友邀请您开通大正客户端第一线功能，您的登录名是'.$mobile.'，密码是'.$password.'。请尽快登录客户端，并申请“第一线”。【大正】';
			
		}
		else
		{
			$to_uid=$user_info['uid'];
			$msg_content='您的朋友邀请您开通大正客户端第一线功能，您的登录名是'.$mobile.'。请尽快登录客户端，并申请“第一线”。【大正】';
		}
		
		
		//邀请记录入库
		$sql_data=include($current_path.'/data/tbl_zimeiti_yaoqing_sql_data.php');
		DB::query($sql_data);
		
		
		//发送短信
		$sql_content=$msg_content;
		$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
		
		//检查是否已发送过验证码，如果有，则读取旧的验证码
		$task_info = DB::fetch_first( "select * from tbl_msg_task where mobile='".$mobile."' and msg_task_status=0 and msg_task_source='zimeiti_yaoqing' order by msg_task_id desc ");
		if(!$task_info['msg_task_id'])
		{
			//如果没有任务，则添加
			$task_in="insert into tbl_msg_task (field_uid,mobile,msg_task_source,msg_task_status,msg_task_addtime,msg_task_date) values ('".$field_uid."','".$mobile."','zimeiti_yaoqing','0','".$time."','".date("Y-m-d H:i:s",$time)."') ";
			
			DB::query($task_in);
			$msg_task_id = DB::result_first( "select msg_task_id from tbl_msg_task where mobile='".$mobile."' and msg_task_status=0 and msg_task_source='zimeiti_yaoqing' order by msg_task_id desc limit 1 ");
		}
		else
		{
			$msg_task_id=$task_info['msg_task_id'];
		}
		
		send_mobile_msg($mobile,$msg_content,'','zimeiti_yaoqing',$sql_content,$msg_task_id);
		

		api_json_result(1,0,'您的邀请已发送',$data);
		
		
	}
	else
	{
		api_json_result(1,1,'手机号格式错误',$data);
	}
	
	
	
	
	
	
}

?>