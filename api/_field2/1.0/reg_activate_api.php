<?php
if(!defined("IN_DISCUZ"))
{
	exit('Access Denied');
}


////如果未注册,则增加新用户
//$t=time();
//$email=$_G['gp_email']?$_G['gp_email']:$t.'@bw.com';
//$mobile=$_G['gp_mobile'];
//$username=$mobile;
//$password=$mobile;
//$realname=$_G['gp_realname'];
//
//if($username && $password &&  $email)
//{
//	$uid = uc_user_register($username,$password, $email);
//}
//else
//{
//	api_json_result(1,10018,$api_error['register']['10018'],null);
//}
//
//if($uid>0)
//{
//
//	//处理用户信息
//	//userlogin($username, $password);
//	
//	$post_string = "&username=".$username."&password=".$password."";
//	$info = request_by_curl_new("{$site_url}/member.php?mod=logging&action=login&loginsubmit=yes",$post_string);
//	
//	DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
//	DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',cron_fensi_state=0  WHERE uid='$uid'"); 
//	DB::query("UPDATE ".DB::table('common_member')."  SET groupid='10'  WHERE uid='$uid'"); 
//
//	$data['title']="add_data";
//	$data['data']=array(
//		'message'=>	'添加成功',
//		'uid'=>	$uid,
//	);
//
//}
//else
//{
//	$data['title']="add_data";
//	$data['data']=array(
//		'message'=>	'添加失败',
//		'uid'=>	$uid,
//	);
//}
//
//
//
//api_json_result(1,0,null,$data);
















/**
 * UCenter 应用程序开发 Example
 *
 * 应用程序无数据库，用户注册的 Example 代码
 * 使用到的接口函数：
 * uc_user_register()	必须，注册用户数据
 * uc_authcode()	可选，借用用户中心的函数加解密 Cookie
 */


//在UCenter注册用户信息
$t=time();
$email=$_G['gp_email']?$_G['gp_email']:$t.'@bw.com';
$mobile=$_G['gp_mobile'];
$username=$mobile;
$password=$mobile;
$realname=$_G['gp_realname'];
if(empty($realname)) {
    $realname = $mobile;
}
if($username && $password &&  $email)
{
    $uid = uc_user_register($username, $password, $email);

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
    $data['title']    = "uid";
    $data['data']     = $uid;

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

        $post_string = "&username=".$username."&password=".$password."";
        $info = request_by_curl($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);
        echo 'thisisasplit';
		$realname =urldecode($realname);
		$realname =iconv("gb2312","UTF-8",$realname); 
		//DB::query("UPDATE jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'");
		DB::query("UPDATE ".DB::table('common_member_profile')." SET realname='$realname',mobile='$mobile',cron_fensi_state='0'  WHERE uid='$uid'"); 
		DB::query("UPDATE ".DB::table('common_member')." SET groupid='40' WHERE uid='$uid'"); 
	    
		setcookie('Example_auth', uc_authcode($uid."\t".$_POST['username'], 'ENCODE'));
	    api_json_result(1,0,"注册成功",$data);
}