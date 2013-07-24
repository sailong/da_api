<?php

if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}


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

if($_G['gp_username'] && $_G['gp_password'] &&  $email && $_G['gp_realname'])
{
    $uid = uc_user_register($_G['gp_username'], $_G['gp_password'], $email);

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

         $post_string = "&username=".$_G['gp_username']."&password=".$_G['gp_password']."";
         $info = request_by_curl_new($site_url.'/member.php?mod=logging&action=login&loginsubmit=yes',$post_string);

		$mobile =$_G['gp_mobile'];
		$realname =urldecode($_G['gp_realname']);
		$realname =iconv("gb2312","UTF-8",$realname); 
		//$realname =iconv("gb2312","UTF-8",$realname); 
		$is_auto_guanzhu =$_G['gp_is_auto_guanzhu'];		

		$sheng=get_city_bymobile($mobile);
		if($sheng=="北京" || $sheng=="上海" || $sheng=="天津" || $sheng=="重庆")
		{
			$sheng=$sheng."市";
		}
		else
		{
			$sheng=$sheng."省";
		}

		if($is_auto_guanzhu && $sheng)
		{
			$city_sql=" and resideprovince='".$sheng."' ";

			$tongcheng=DB::query("select uid from ".DB::table('common_member_profile')." where 1=1 ".$city_sql." order by uid desc limit 60 ");
			while($row = DB::fetch($tongcheng) )
			{
				$aaa=DB::fetch_first("select * from jishigou_buddys where uid='".$uid."' and buddyid='".$row['uid']."' ");
				if(empty($aaa))
				{
					$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$uid."','".$row['uid']."','1','','".time()."','','".time()."') ");
				}

				$bbb=DB::fetch_first("select * from jishigou_buddys where uid='".$row['uid']."' and buddyid='".$uid."' ");
				if(empty($bbb))
				{
					$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$row['uid']."','".$uid."','1','','".time()."','','".time()."') ");
				}

			}
		}
 
		 DB::query("UPDATE ultrax.jishigou_members SET nickname='$realname',validate=1 WHERE ucuid='$uid'"); 
		 DB::query("UPDATE ".DB::table('common_member_profile')."  SET realname='$realname',mobile='$mobile',resideprovince='$sheng',cron_fensi_state=0,reg_source=1186  WHERE uid='$uid'"); 
				 
		setcookie('Example_auth', uc_authcode($uid."\t".$_POST['username'], 'ENCODE'));
		api_json_result(1,0,$api_error['login']['10010'],$data);
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


/*

		//获取所在城市
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
		 
		function getCity($ip)
		{
			$url="http://ip.taobao.com/service/getIpInfo.php?ip=".$ip;
			$ip=json_decode(file_get_contents($url));
			if((string)$ip->code=='1'){
			  return false;
			  }
			  $data = (array)$ip->data;
			return $data;
		}

		$city_info=getCity(get_real_ip());
		$sheng=$city_info['region'];
		$city=$city_info['city'];

		if($city_info['region']==$city_info['city'])
		{
			$city_sql=" and resideprovince='".$city."' ";
			$shi='';
		}
		else
		{
			$city_sql=" and residecity='".$city."' ";
			$shi=$city;
		}

		//处理自动关注
		
		if($is_auto_guanzhu)
		{
			$tongcheng=DB::query("select uid from ".DB::table('common_member_profile')." where 1=1 ".$city_sql." order by uid desc limit 231 ");
			while($row = DB::fetch($tongcheng) )
			{
				$aaa=DB::fetch_first("select * from jishigou_buddys where uid='".$uid."' and buddyid='".$row['uid']."' ");
				if(empty($aaa))
				{
					$res=DB::query("insert into jishigou_buddys (uid,buddyid,grade,remark,dateline,description,buddy_lastuptime) values ('".$uid."','".$row['uid']."','1','','".time()."','','".time()."') ");
				}
			}
		}
		*/



?>