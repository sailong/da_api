<?php
if(!defined('IN_DISCUZ'))
{
    exit('Access Denied');
}


//get username by mobile     jack add 20130916
$username = $_G['gp_username'];
preg_match_all("/13[0-9]{9}|15[0|1|2|3|5|6|7|8|9]\d{8}|18[0|5|6|7|8|9]\d{8}/",$_G['gp_username'], $if_mobile);
if(!empty($if_mobile[0]))
{
	$sql = "select uid,mobile from pre_common_member_profile where mobile='{$username}' order by uid desc";
	$rs=DB::fetch_first($sql);
	$sql = "select uid,username from pre_common_member where uid='".$rs['uid']."' order by uid desc";
	$rs=DB::fetch_first($sql);
	$username=$rs['username'];
}


//通过接口判断登录帐号的正确性，返回值为数组

    list($uid, $username, $password, $email) = uc_user_login($username,$_G['gp_password']);


	$row['touxiang']=$site_url."/uc_server/avatar.php?uid=".$row['uid']."&size=small";
	
	$user_info=DB::fetch_first("select mobile,is_zimeiti from pre_common_member_profile where uid='{$uid}' ");
	

/*初始化接口返回的参数*/
    $response         = 0;
    $error_state      = 0;
    $data['title']    = "userinfo";
    $data['data'] = array(
                        'uid'=>$uid,
                        //'username'=>$realname,
                        'password'=>$password,
                        'mobile'=>$user_info['mobile'],
                        'touxiang'=>"".$site_url."/uc_server/avatar.php?uid=".$uid."&size=middle",
						'is_zimeiti'=>$user_info['is_zimeiti'],
                        //'email'   =>$email,
                         );



    setcookie('Example_auth', '', -86400);
    if($uid > 0)
	{
		
		$email_info = DB::fetch_first( "select email  from " . DB::table ( 'common_member' ) . "  where uid='$uid' ");
		$data['data']['email']=$email_info['email'];
		
		$realname = DB::fetch_first( "select realname,chadian  from " . DB::table ( 'common_member_profile' ) . "  where uid='$uid' ");
		$data['data']['username']=$realname['realname'];
		$data['data']['chadian']=$realname['chadian'];

		


		//print_r($data);

         //用户登陆成功，设置 Cookie，加密直接用 uc_authcode 函数，用户使用自己的函数
          setcookie('Example_auth', uc_authcode($uid."\t".$username, 'ENCODE'));
         //生成同步登录的代码
         $ucsynlogin = uc_user_synlogin($uid);
		 
		 
		 
		//添加日志
		$tj_sql="";
		$tj_sql .=" insert into tbl_app_log ( ";
		$tj_sql .=" uid, ";
		$tj_sql .=" field_uid, ";
		$tj_sql .=" app_log_mod, ";
		$tj_sql .=" ac, ";
		$tj_sql .=" ip, ";
		$tj_sql .=" province, ";
		$tj_sql .=" user_agent, ";
		$tj_sql .=" versions, ";
		$tj_sql .=" url, ";
		$tj_sql .=" sn, ";
		$tj_sql .=" app_log_addtime ";
		$tj_sql .=" ) values( ";
		$tj_sql .=" '".$uid."', ";
		$tj_sql .=" '".$log_field_uid."', ";
		$tj_sql .=" '".$mod."', ";
		$tj_sql .=" '".$ac."', ";
		$tj_sql .=" '".get_real_ip()."', ";
		$tj_sql .=" '".$province."', ";
		$tj_sql .=" '".$userAgent."', ";
		$tj_sql .=" '".$versions."', ";
		$tj_sql .=" '".$_SERVER['REQUEST_URI']."', ";
		$tj_sql .=" '".$sn."', ";
		$tj_sql .=" '".time()."' ";
		$tj_sql .=" ) ";
		$tj_up=DB::query($tj_sql);
		 
		 
		 
		 
        api_json_result(1,0,$api_error['login']['10000'],$data);
    }
	elseif($uid == -1)
	{
         api_json_result(1,10001,$api_error['login']['10001'],$data);
    }
	elseif($uid == -2)
	{
         api_json_result(1,10002,$api_error['login']['10002'],$data);
    }
	else
	{
         api_json_result(1,10003,$api_error['login']['10003'],$data);
    }

?>