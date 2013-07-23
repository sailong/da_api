<?php
/**
 * 文件名：login.mod.php
 * 版本号：(1.0)
 * 最后修改时间：2006年8月22日 18:58:20
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：用户登录
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{


	
	var $Code = false;

	
	var $Username = '';

	
	var $Password = '';

	var $Secques = '';

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->Username = isset($this->Post['username'])?trim($this->Post['username']):"";
		$this->Password = isset($this->Post['password'])?trim($this->Post['password']):"";

		if(MEMBER_ID > 0) {
			$this->IsAdmin = $this->MemberHandler->HasPermission('member','admin');
		}
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			case 'dologin':
				$this->DoLogin();
				break;
			case 'logout':
				$this->LogOut();
				break;
			default:
				$this->login();
				break;
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}
	
	function login()
	{
		if(MEMBER_ID != 0 AND false == $this->IsAdmin)
		{
			$this->Messager("您已经使用用户名 ". MEMBER_NAME ." 登录系统，无需再次登录！",null);
		}
		$loginperm = $this->_logincheck();
		if(!$loginperm) {
			$this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。",null);
		}
		$this->Title="用户登录";
		if ($this->CookieHandler->GetVar("referer")=="")
		{
			$this->CookieHandler->Setvar("referer",referer());
		}
		$action="index.php?mod=login&code=dologin";


		include($this->TemplateHandler->Template("global_login"));

	}


	
	function DoLogin()
	{
		extract($this->Get);
		extract($this->Post);


        if($this->Username=="" || $this->Password=="")
		{
			$this->Messager("无法登录,用户名或密码不能为空");
		}
        
		
		$loginperm = $this->_logincheck();
		if(!$loginperm) {
			$this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。",null);
		}	
        
        if(is_numeric($this->Username))
        {            
            if($this->Config['imjiqiren_enable'] && $this->Username > 10000 && $this->Username < 1999999999 && imjiqiren_init($this->Config))
            {
                $_imjiqiren_client_user = _imjiqiren_client_user($this->Username);
                if($_imjiqiren_client_user && $_imjiqiren_client_user['uid'] > 0)
                {
                    $this->Username = $this->DatabaseHandler->ResultFirst("select `username` from `".TABLE_PREFIX."members` where `uid`='{$_imjiqiren_client_user['uid']}'");
                }
            }
            elseif($this->Config['sms_enable'] && jsg_is_mobile($this->Username))
            {
                $this->Username = $this->DatabaseHandler->ResultFirst("select `username` from `".TABLE_PREFIX."members` where `phone`='{$this->Username}'");
            }
        }
        else
        {
            if (false!==strpos($this->Username,'@') and strlen($this->Username)>3) 
            {
                $this->Username = $this->DatabaseHandler->ResultFirst("select `username` from `".TABLE_PREFIX."members` where `email`='{$this->Username}'");
    		}
        }
        
        if($this->Username=="" || $this->Password=="")
		{
			$this->Messager("无法登录,用户名或密码不能为空");
		}
        
        $login_msg = '';
		if(true === UCENTER)
		{
						include_once(ROOT_PATH . 'uc_client/client.php');


			list($uc_uid,$uc_username,$uc_password,$uc_email,$uc_same_username) = uc_user_login($this->Username,$this->Password); 

			$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."members where username='{$this->Username}'");
			$check = 0;
			$member_info = $query->GetRow();
			if($member_info)
			{
				if($member_info['password']==md5($this->Password))
				{
					$check = 1;
				}
				else
				{
					$check = -1;
				}

                if($uc_uid > 0 && $member_info['ucuid']!=$uc_uid)
                {
                    $member_info['ucuid']=$uc_uid;

                    $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `ucuid`='$uc_uid' where `uid`='{$member_info['uid']}'");
                }
			}
			if($uc_uid < 0 && $check < 1) 			{
				$this->_loginfailed($loginperm);

				$this->Messager("无法登录,用户名或者密码错误,您可以有至多 5 次尝试。",-1);
			}
			else
			{
				if ($uc_uid > 0 && $check < 1) 				{
					if($check == 0) 					{
												$invitecode = substr(md5(@implode("\t",(array) $check_result) . random(16)),0,16);

						$this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."members set `username`='{$uc_username}',`nickname`='{$uc_username}',`password`='".(md5($this->Password))."',`email`='{$uc_email}',`role_id`='{$this->Config['normal_default_role_id']}',`ucuid`='{$uc_uid}',`invitecode`='{$invitecode}'");
						$newuid = $this->DatabaseHandler->Insert_ID();
						$this->DatabaseHandler->Query("replace into ".TABLE_PREFIX."memberfields(`uid`,`nickname`) values('{$newuid}','{$uc_username}')");
					}
					else 					{
						if($member_info['uid'] > 0) 						{
							$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `password`='".(md5($this->Password))."' where `uid`='{$member_info['uid']}'");
						}
						else 						{
							$this->Messager("登录失败",null);
						}
					}
				}

                if ($uc_uid < 0 && $check == 1) 				{
					if ($uc_uid == -1) 					{
						$uc_uid = uc_user_register($this->Username,$this->Password,$member_info['email']); 
					}
                    elseif(-2==$uc_uid)                     {
						
                    }
                    elseif(-3==$uc_uid)                     {
                        list($uc_uid,$uc_username,$uc_email) = uc_get_user($this->Username);
                    }
				}
			}

            if($uc_uid > 0)
            {
                if($member_info['uid'] > 0 && $member_info['ucuid']!=$uc_uid)
                {
                    $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `ucuid`='$uc_uid' where `uid`='{$member_info['uid']}'");
                }

                $login_msg = uc_user_synlogin($uc_uid);             }
            else
            {
                $this->Messager("在UC中登录失败",null);
            }
		}

		$check=$this->MemberHandler->CheckMember($this->Username,$this->Password);

		$Auth=false;
		switch($check)
		{
			case -1:
				$this->_loginfailed($loginperm);
				$this->Messager("无法登录,用户密码错误,您可以有至多 5 次尝试。",-1);
				break;
			case 0:
				$this->_loginfailed($loginperm);
				$this->Messager("无法登录,用户不存在，您可以有至多 5 次尝试。",-1);
				break;
			case 1:
				$Auth=true;
				break;
		}

		if($Auth==true)
		{
			$UserFields=$this->MemberHandler->GetMemberFields();


			$authcode=authcode("{$UserFields['password']}\t{$UserFields['uid']}",'ENCODE');

			$this->CookieHandler->setVar('sid','',-86400000);
			$this->CookieHandler->SetVar('auth',$authcode,($this->Config['cookie_expire']*86400));


			$referer=$this->CookieHandler->GetVar('referer');
			$this->CookieHandler->SetVar('referer','');
			$this->_updateLoginFields($UserFields['uid']);


			$redirecto=($referer?$referer:referer());
			if(strpos($redirecto,'login')!==false)
            {
                $redirecto = "index.php?mod=topic&code=myhome" ;
            }



			if($this->Config['extcredits_enable'] && $UserFields['uid'] > 0)
			{
				
				update_credits_by_action('login',$UserFields['uid']);
			}

						if($this->Post['loginType'] == 'share')
			{
				$redirecto = $this->Post['return_url'];
				$this->Messager(null,$redirecto,0);
			}


			if($login_msg)
            {
                $this->Messager("{$login_msg}登录成功",$redirecto,3);
            }
            else
            {
                $this->Messager(null,$redirecto);
            }
					}

	}

	
	function _updateLoginFields($uid)
	{
		$timestamp=time();
		$last_ip=client_ip();
		$sql="
		UPDATE
			".TABLE_PREFIX.'members'."
		SET
			`login_count`='login_count'+1,
			`lastvisit`='{$timestamp}',
			`lastactivity`='{$timestamp}',
			`lastip`='{$last_ip}'
		WHERE
			uid={$uid}";
		$query = $this->DatabaseHandler->Query($sql);
		Return $query;
	}

	
	function LogOut()
	{
		$this->CookieHandler->ClearAll();

		$this->MemberHandler->SessionExists=false;
		$this->MemberHandler->MemberFields=array();

		$msg = null;
		if (true === UCENTER) {
						include_once(ROOT_PATH . 'uc_client/client.php');

			$msg = '已经成功退出，现在为您跳转到首页';
			$msg .= uc_user_synlogout();

					}

		$this->Messager($msg,'?',0);
	}

	function _logincheck() {
		$onlineip= client_ip();
		$timestamp=time();
		$query = $this->DatabaseHandler->Query("SELECT count, lastupdate FROM ".TABLE_PREFIX.'failedlogins'." WHERE ip='$onlineip'");
		if($login = $query->GetRow()) {
			if($timestamp - $login['lastupdate'] > 900) {
				return 3;
			} elseif($login['count'] < 5) {
				return 2;
			} else {
				return 0;
			}
		} else {
			return 1;
		}
	}

	function _loginfailed($permission) {
		$onlineip= client_ip();
		$timestamp=time();
		switch($permission) {
			case 1:	$this->DatabaseHandler->Query("REPLACE INTO ".TABLE_PREFIX.'failedlogins'." (ip, count, lastupdate) VALUES ('$onlineip', '1', '$timestamp')");
				break;
			case 2: $this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'failedlogins'." SET count=count+1, lastupdate='$timestamp' WHERE ip='$onlineip'");
				break;
			case 3: $this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'failedlogins'." SET count='1', lastupdate='$timestamp' WHERE ip='$onlineip'");
				$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'failedlogins'." WHERE lastupdate<$timestamp-901", 'UNBUFFERED');
				break;
		}
	}

}

?>