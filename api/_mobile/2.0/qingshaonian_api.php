<?php
/*
*
* bwvip.com
* 赛事报名
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
//当前文件所在路径
$current_path = dirname(__FILE__); 
//根目录
$root_path = dirname(dirname(dirname($current_path)));

//参考  qingshaonian_api.php

//报名页面
if($ac=="qingshaonian_add")
{
	$list_data=include($current_path.'/data/tbl_qingshaonian_reg_data.php');
	//var_dump($list_data);
	$data['title']='data';
	$data['data']['list']=$list_data;
	api_json_result(1,0,$app_error['event']['10502'],$data);
	
}


if($ac=="qingshaonian_add_action")
{
	$realname = urldecode($_G['gp_realname']);
	$sex = urldecode($_G['gp_sex']);
	$mobile = urldecode($_G['gp_mobile']);
	$card = urldecode($_G['gp_card']);
	$jianhuren_name = urldecode($_G['gp_jianhuren_name']);
	$jianhuren_mobile = urldecode($_G['gp_jianhuren_mobile']);
	$captcha = urldecode($_G['gp_captcha']);
	/* echo '<pre>';
	var_dump($realname);
	var_dump($sex);
	var_dump($mobile);
	var_dump($jianhuren_name);
	var_dump($jianhuren_mobile);
	var_dump($captcha);
	var_dump($card);
	var_dump($_FILES);die; */
	if(!preg_match_type($mobile,'phone')){
		api_json_result(1,1,'电话输入有误','');
	}
	
	if(!preg_match_type($jianhuren_mobile,'phone')){
		api_json_result(1,1,'监护人电话有误','');
	}
	
	if(!verify_captcha($captcha,$jianhuren_mobile)){
		api_json_result(1,1,'验证码错误','');
	}
	
	if($_FILES["touxiang"]["error"]<=0 && $_FILES["touxiang"]["name"])
	{
		$extname=end(explode(".",$_FILES["touxiang"]["name"]));
		$is_img = check_img($extname);
		if($is_img){
			$qingshaonian_touxiang =  "/upload/qingshaonian/" .mt_rand(100000,999999).time().$extname;
			move_uploaded_file($_FILES["file"]["tmp_name"],$root_path.$qingshaonian_touxiang);
		}else{
			api_json_result(1,1,'上传头像失败','');
		}
	}
	$time = time();
	$uid1 = get_uid_by_phone($mobile);
	if(!$uid1){
		$sql = "select * from tbl_msg_task where mobile='".$mobile."' and msg_task_status=0 and msg_task_source='reg' order by msg_task_id desc ";
		$find_task=DB::fetch_first($sql);
		//如果没有任务，则添加
		if(!$find_task){
			$time = time();
			$sql = "insert into tbl_msg_task(field_uid,mobile,msg_task_source,msg_task_status,msg_task_addtime,msg_task_date) 
			values('0','{$mobile}','reg','0','{$time}','".date('Y-m-d H:i:s',$time)."')";
			DB::query($sql);
			$msg_task_id=DB::insert_id();
		}else{
			$msg_task_id = $find_task['msg_task_id'];
		}
		$user1 = user_reg(array('realname'=>$realname,'mobile'=>$mobile,'	'=>''));
		$uid1 = $user1['uid'];
		$sql_content="恭喜您，注册成功。您的大正登录名为:".$mobile."，密码为:".$user1['password']."，大正客户端下载地址：http://www.bwvip.com/app ";
		$msg_content=iconv('UTF-8', 'GB2312', $sql_content);;
		send_mobile_msg($mobile,$msg_content,'','reg',$sql_content,$msg_task_id);
	}
	
	$uid2 = get_uid_by_phone($jianhuren_mobile);
	if(!$uid2){
		$smcode=rand(1000,9999);
		
		$sql = "select * from tbl_msg_task where mobile='".$jianhuren_mobile."' and msg_task_status=0 and msg_task_source='reg' order by msg_task_id desc ";
		$find_task=DB::fetch_first($sql);
		//如果没有任务，则添加
		if(!$find_task){
			$sql = "insert into tbl_msg_task(field_uid,mobile,msg_task_source,msg_task_status,msg_task_addtime,msg_task_date) 
			values('0','{$jianhuren_mobile}','reg','0','{$time}','".date('Y-m-d H:i:s',$time)."')";
			DB::query($sql);
			$msg_task_id=DB::insert_id();
		}else{
			$msg_task_id = $find_task['msg_task_id'];
		}
		$user2 = user_reg(array('username'=>$jianhuren_name,'mobile'=>$jianhuren_mobile,'email'=>''));
		$uid2 = $user2['uid'];
		$sql_content="恭喜您，注册成功。您的大正登录名为:".$jianhuren_mobile."，密码为:".$user2['password']."，大正客户端下载地址：http://www.bwvip.com/app ";
		$msg_content=iconv('UTF-8', 'GB2312', $sql_content);;
		send_mobile_msg($jianhuren_mobile,$msg_content,'smcode','reg',$sql_content,$msg_task_id);
	}
	
	DB::query("UPDATE ".DB::table('common_member_profile')."  SET parent_uid='{$uid2}',qingshaonian_touxiang='{$qingshaonian_touxiang}' WHERE uid='{$uid1}'"); 
	
	api_json_result(1,0,'注册成功，请登录','');
}


//获取验证码
if($ac=='get_captcha'){
	$mobile = $_G['gp_jianhuren_mobile'];
	if(empty($mobile) || !preg_match_type($mobile,'phone')){
		api_json_result(1,1,'监护人电话有误','');
	}
	$smcode=rand(1000,9999);
	$msg_content=$smcode.'（手机注册确认码，请完成验证），如非本人操作，请忽略本短信。【大正】';
	$sql_content=$msg_content;
	$msg_content=iconv('UTF-8', 'GB2312', $msg_content);
	send_mobile_msg($mobile,$msg_content,$smcode,'reg_from_app',$sql_content);
	
	$data['title']='data';
	$data['data'] = array('code'=>$smcode);
	api_json_result(1,0,'验证码已发送',$data);
}

//验证码验证
function verify_captcha($captcha,$phone){
	if(empty($captcha) || empty($phone)){
		return false;
	}
	$sql = "select code,msg_task_id from tbl_msg_log where mobile='".$phone."' and msg_log_source='reg_from_app' order by msg_log_id desc limit 1";
	$find_msg_log=DB::fetch_first($sql);
	if($find_msg_log['code']==$captcha)
	{
		DB::query("UPDATE tbl_msg_task  SET msg_task_status='1' WHERE msg_task_id='".$find_msg_log['msg_task_id']."'");
		return true;
	}
	else
	{
		return false;
	}
}

//通过手机号获取UID
function get_uid_by_phone($phone){
	if(!empty($phone))
	{
		$sql = "select uid,mobile from pre_common_member_profile where mobile='{$phone}' order by uid desc";
		$rs=DB::fetch_first($sql);
		if(!empty($rs)){
			return $rs['uid'];
		}
	}
	
	return false;
}


//检查图片格式
function check_img($extname){
	if(empty($extname)){
		return false;
	}
	$extname = strtolower($extname);
	switch ($extname)
	{
		case 'jpg':
		  return true;
		  break;  
		case 'gif':
		  return true;
		  break;
	    case 'png':
		  return true;
		  break;
		default:
		  return false;
		  break;
	}
}


//验证信息
function preg_match_type($str,$type="int")
{
	switch ($type)
	{
	case 'int':
	  $pattern = "/^[0-9]*$/";
	  break;  
	case 'phone':
	  $pattern = "/^13[0-9]{1}[0-9]{8}|14[0-9]\d{8}|15[0-9]\d{8}|18[0-9]\d{8}$/";
	  break;
	case 'email':
	  $pattern = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
	  break;
	case 'postcode':
	  $pattern = "/^[0-9]\d{5}$/";
	  break;
	}
	
	return preg_match($pattern,$str);
}

//注册用户
/*
$data_arr['email']      邮箱
$data_arr['realname']   用户名
$data_arr['mobile']     手机号
*/
function user_reg($data_arr){
	/**
	 * UCenter 应用程序开发 Example
	 *
	 * 应用程序无数据库，用户注册的 Example 代码
	 * 使用到的接口函数：
	 * uc_user_register()	必须，注册用户数据
	 * uc_authcode()	可选，借用用户中心的函数加解密 Cookie
	 */
	
	if(empty($data_arr)){
		return false;
	}

	//在UCenter注册用户信息
	$t=time();
	
	$password=mt_rand(100000,999999);
	$email=$data_arr['email']? $data_arr['email'] : $t.'@bw.com';
	$data_arr['username'] = mt_rand(100000,999999).substr(time(),-4);
	if($data_arr['username'] && $password &&  $email)
	{
		$uid = uc_user_register($data_arr['username'], $password, $email);

	}else{
		api_json_result(1,10018,$api_error['register']['10018'],null);
	}

	/*
	echo "post:<HR>";
	print_r($_POST);

	echo "get:<HR>";
	print_r($_GET);

	*/


	/*接口返回的参数*/
		$response         = 0;
		$error_state      = 0;
		$data['title']    = "userinfo";
		$data['data'] = array(
							'uid'=>$uid,
							 );

	if($uid <= 0) {
		if($uid == -1) {
			 api_json_result(1,10011,$api_error['register']['10011'],$data);
		} elseif($uid == -2) {
			 api_json_result(1,10012,$api_error['register']['10012'],$data);
		} elseif($uid == -3) {
			 api_json_result(1,10013,$api_error['register']['10013'],$data);
		} elseif($uid == -4) {
			 api_json_result(1,10014,$api_error['register']['10014'],$data);
		} elseif($uid == -5) {
			 api_json_result(1,10015,$api_error['register']['10015'],$data);
		} elseif($uid == -6) {
			 api_json_result(1,10016,$api_error['register']['10016'],$data);
		} else {
			 api_json_result(1,10017,$api_error['register']['10017'],$data);
		}
	} else {

		//注册成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数

			 $post_string = "&username=".$data_arr['username']."&password=".$password."";
			 $info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);

			$mobile = $data_arr['mobile'];
			$realname =$data_arr['realname'];
			
			$sheng=get_city_bymobile($mobile);
			
			if($sheng=="北京" || $sheng=="上海" || $sheng=="天津" || $sheng=="重庆")
			{
				$sheng=$sheng."市";
			}
			else
			{
				$sheng=$sheng."省";
			}
	 
			 DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
			 DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',resideprovince='$sheng',cron_fensi_state=0,regdate='".time()."',user_role='Q',user_status='0' WHERE uid='$uid'"); 

			 DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10'  WHERE uid='$uid'"); 
					 
			setcookie('Example_auth', uc_authcode($uid."\t".$data_arr['username'], 'ENCODE'));
			
			return array('uid'=>$uid,'password'=>$password);
			//api_json_result(1,0,$api_error['login']['10010'],$data);
	}

}

function get_city_bymobile($mobilephone)
{ 
	$url = "http://tcc.taobao.com/cc/json/mobile_tel_segment.htm?tel=".$mobilephone."&t=".time(); 
	$content = file_get_contents($url); 
	//echo $content;
	//echo "<hr>";
	$p = substr($content, 56, 4); 
	//$mo = substr($content, 81, 4); 
	//return $str = conv2utf8($p).conv2utf8($mo); 
	return $str = $p; 
}


?>