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



//修改密码
if($ac=="update_password")
{
	$uid = $_G['gp_uid'];  //用户名
    $oldpwd = $_G['gp_oldpwd'];
    $newpwd1 = $_G['gp_newpwd1'];
    $newpwd2 = $_G['gp_newpwd2'];
	if(empty($oldpwd) || empty($newpwd1) || empty($newpwd2) || empty($uid)) 
	{
		api_json_result(1,1,"参数不完整",'');
	}
	if($newpwd1 != $newpwd2)
	{
		api_json_result(1,1,"新密码两次输入不一致",'');
	}
	$uc_res = DB::fetch_first("select password,salt from pre_ucenter_members where uid='{$uid}'");
	$jsg_res = DB::fetch_first("select password from jishigou_members where uid='{$uid}'");
	$discuz_res = DB::fetch_first("select password from pre_common_member where uid='{$uid}'");
	if(empty($jsg_res) || empty($discuz_res) || empty($uc_res))
	{
		api_json_result(1,1,"用户不存在",'');
	}
	
	$oldpwd = md5(md5($oldpwd).$uc_res['salt']);
	if($_G['gp_test']==1) {
		echo 'uc密码：'.$uc_res['password'].'<br/>js密码：'.$jsg_res['password'].'<br/>ds密码：'.$discuz_res['password'].'<br/>旧密码：'.$oldpwd;die;
	}
	if(($oldpwd != $uc_res['password']))
	{
		api_json_result(1,1,"旧密码输入不正确",'');
	}
    
    $uc_res = DB::fetch_first("select password,salt from pre_ucenter_members where uid='{$uid}'");
    $newpwd1 = md5(md5($newpwd1).$uc_res['salt']);
    $jsg_res = DB::query("update jishigou_members set password='{$newpwd1}' where uid='{$uid}'");
    $discuz_res = DB::query("update pre_common_member set password='{$newpwd1}' where uid='{$uid}'");
    $uc_res = DB::query("update pre_ucenter_members set password='{$newpwd1}' where uid='{$uid}'");

    api_json_result(1,0,"修改成功",'');
}



//短信找回密码
if($ac=="get_password_by_message")
{

	$mobile=$_G['gp_mobile'];
	
	if($mobile)
	{
		$uid=DB::result_first("select uid from ".DB::table("common_member_profile")." where mobile='".$mobile."' ");
		$uid = $uid['uid'];
		if($uid)
		{
			$if_send=send_mobile_msg($mobile,"您在大正网的密码已被重置为：123456，请尽快登录并修改密码。");
			if($if_send=="0#1")
			{
				$uc_res = DB::fetch_first("select password,salt from pre_ucenter_members where uid='{$uid}'");
				$newpwd1 = md5(md5("123456").$uc_res['salt']);
				$jsg_res = DB::query("update jishigou_members set password='{$newpwd1}' where uid='{$uid}'");
				$discuz_res = DB::query("update pre_common_member set password='{$newpwd1}' where uid='{$uid}'");
				$uc_res = DB::query("update pre_ucenter_members set password='{$newpwd1}' where uid='{$uid}'");
				api_json_result(1,0,"新密码已经以短信的形式发到您的手机号：".$mobile.",请注意查收",'');
			}
			else
			{
				api_json_result(1,3,"短信发送失败，请重试",'');
			}
			
		}
		else
		{
			api_json_result(1,2,"手机号不正确，请重新输入",'');
		}
	}
	else
	{
		api_json_result(1,1,"手机号为必填",'');
	}
}



//推送设置
if($ac=="push_config")
{
	$uid=$_G['gp_uid'];
	$is_push=$_G['gp_is_push'];
	if($uid && $is_push)
	{
		$res=DB::query("update ".DB::table("common_member_profile")." set is_push='".$is_push."' where uid='".$uid."' ");
		api_json_result(1,0,"设置成功",'');
	}
	else
	{
		api_json_result(1,1,"出错了，参数不全",'');
	}
}



?>
