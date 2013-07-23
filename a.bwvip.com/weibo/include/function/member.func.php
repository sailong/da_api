<?php

/*******************************************************************

 * [JishiGou] (C)2005 - 2099 Cenwor Inc.

 *

 * This is NOT a freeware, use is subject to license terms

 *

 * @Package JishiGou $

 *

 * @Filename member.func.php $

 *

 * @Author http://www.jishigou.net $

 *

 * @Date 2011-09-28 19:16:47 553640259 1268846474 13913 $

 *******************************************************************/




if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

/*
	[JishiGou] (C)2005 - 2099 Cenwor Inc.
	
	用户注册登录函数

	$RCSfile: member.func.php,v $
	$Revision: 1.0 $
	$Author 狐狸<foxis@qq.com>$
	$Date: 2011年5月26日 17:21:25$
*/



function jsg_member_register($username, $password, $email, $nickname = '', $ucuid = 0)
{
	$return = 0;
	
	
	$username = trim(strip_tags($username));
	$jsg_result = jsg_member_checkname($username, 0, $ucuid);
	if($jsg_result < 1)
	{
		return $jsg_result;
	}
	
	
	if(!$nickname)
	{
		$nickname = $username;
	}
	else 
	{
		$nickname = trim(strip_tags($nickname));
	}
	$jsg_result = jsg_member_checkname($nickname, 1, $ucuid);
	if($jsg_result < 1)
	{
		return $jsg_result;
	}
	
	
	$jsg_result = jsg_member_checkemail($email, $ucuid);
	if($jsg_result < 1)
	{
		return $jsg_result;
	}
	
	
	if(true === UCENTER && $ucuid < 1)
	{
        include_once (ROOT_PATH . 'uc_client/client.php');	       
	   
		$uc_result = uc_user_register($username, $password, $email);
		if($uc_result < 1)
		{
			return $uc_result;
		}
		$ucuid = $uc_result;
	}	
	
	
	$sys_config = ConfigHandler::get();
	
	
	$DatabaseHandler = &Obj::registry('DatabaseHandler');
	
	
	$timestamp = time();
	$sql_datas = array();
    $sql_datas['ucuid'] 	= $ucuid;
    $sql_datas['password']	= md5($password);
    $sql_datas['username']	= mysql_escape_string($username);
    $sql_datas['nickname']  = ($nickname ? mysql_escape_string($nickname) : $sql_datas['username']);
    $sql_datas['email'] 	= mysql_escape_string($email);
    $sql_datas['role_type']	= 'normal';
    $sql_datas['role_id'] 	= (int) ($sys_config['reg_email_verify'] ? $sys_config['no_verify_email_role_id'] : $sys_config['normal_default_role_id']);
    $sql_datas['secques'] 	= '';
    $sql_datas['invitecode']= substr(md5(mt_rand()),-16);
    $sql_datas['regdate']	= $sql_datas['lastactivity'] = $sql_datas['lastvisit'] = $timestamp;
	$sql_datas['regip']		= $sql_datas['lastip'] = client_ip();
		
        if ($sys_config['extcredits_enable']) 
    {
        $credits = ConfigHandler::get('credits');
        foreach ($credits['ext'] as $_k=>$_v)
        {
        	if ($_v['enable'] && $_v['default']) 
        	{
        		$sql_datas[$_k] = (int) $_v['default'];
        	}
        }
    }
    
    
    $DatabaseHandler->Query("insert into `" . TABLE_PREFIX . "members` (`" . implode("`,`", array_keys($sql_datas)) . "`) values ('".implode("','",$sql_datas)."')");
    $uid = (int) $DatabaseHandler->Insert_ID();
    if($uid < 1)
    {
        return 0;
    }
    $DatabaseHandler->Query("insert into `".TABLE_PREFIX."memberfields` (`uid`,`nickname`) values ('$uid','{$sql_datas['nickname']}')");
    	if($sys_config['reg_email_verify'])
	{
		Load::functions('my');

		my_member_validate($uid,$sql_datas['email'],(int) $sys_config['normal_default_role_id']);
	}
	
	
	return $uid;
}

 
function jsg_member_login($username, $password, $isuid = 0)
{	
    
    $DatabaseHandler = &Obj::registry('DatabaseHandler');
    
    
    $uc_syn_html = '';
    if(true === UCENTER)
	{
		
        include_once (ROOT_PATH . 'uc_client/client.php');
        
        
        $member = jsg_get_member($username, $isuid);
        $_uid = 0;
        if($member)
        {
        	$_member = jsg_member_login_check($username, $password, $isuid);
        	$_uid = $_member['uid'];
        	if(-3==$_uid) return array('uid' => -3);
        	$username = $member['username'];
        	$isuid = 0;
        }
        
        
        list($uc_uid, $uc_username, $uc_password, $uc_email) = uc_user_login($username, $password);                        
        
        
		if($uc_uid > 0 && $_uid < 1) 		{
			if(!$member) 			{
				$_new_uid = jsg_member_register($uc_username, $password, $uc_email, '', $uc_uid);
				if($_new_uid < 1)
				{
				}        			
			}
			else 			{
				$DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `password`='".md5($password)."' where `uid`='{$member['uid']}'");
			}
		}		
		elseif($uc_uid < 1 && $_uid > 0) 		{
			if(-1 == $uc_uid) 			{
				$uc_uid = uc_user_register($member['username'], $password, $member['email']);
			}
		}
        
        if($uc_uid < 1)         {
        	return array('uid' => 0);
        }

		if($member['uid'] > 0 && $uc_uid != $member['ucuid']) 		{
			$DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `ucuid`='$uc_uid' where `uid`='{$member['uid']}'");
		}
		
		$uc_syn_html = uc_user_synlogin($uc_uid);     }   
    
    
    $member = jsg_member_login_check($username, $password, $isuid);    
    $_uid = $member['uid'];
    if($_uid < 1)
    {
    	return array('uid' => $_uid);
    }
    else 
    {
    	$member['uc_syn_html'] = $uc_syn_html;
    	
    	
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
			uid={$_uid}";
		$DatabaseHandler->Query($sql);
    }
    
    
    $member = jsg_member_login_set_status($member);
    
    
    return $member;
}


function jsg_member_login_check($username, $password, $isuid = 0, $checkip = 1)
{    
	
    if($checkip)
    {
    	
		$DatabaseHandler = &Obj::registry('DatabaseHandler');
		
    	$ip = client_ip();
    	$timestamp = time();
    	
    	$failed = $DatabaseHandler->FetchFirst("SELECT * FROM ".TABLE_PREFIX.'failedlogins'." WHERE ip='{$ip}'");
    	if($failed)
        {
            if($failed['lastupdate'] + 900 > $timestamp)
            {
                if($failed['count'] > 5)
                {
                    return array('uid'=>-3);
                }
            }
            else
            {
                $DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'failedlogins'." SET count='1', lastupdate='{$timestamp}' WHERE ip='{$ip}'");
				$DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'failedlogins'." WHERE lastupdate<{$timestamp}-901", 'UNBUFFERED');
            }
        }
    }
	
    
    $member = jsg_get_member($username, $isuid);

    $rets = array();
    
    
    if(!$member || $member['uid'] < 1)
    {
    	$rets = array('uid'=>-1);
    }
    else 
    {
    	
	    if(md5($password) != $member['password'])
	    {
	        $rets = array('uid'=>-2);
	    }
    }
    
    if($rets)
    {
    	if($checkip && $failed)
        {
            $DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'failedlogins'." SET count=count+1, lastupdate='$timestamp' WHERE ip='$ip'");
        }
        else
        {
            $DatabaseHandler->Query("REPLACE INTO ".TABLE_PREFIX.'failedlogins'." (ip, count, lastupdate) VALUES ('$ip', '1', '$timestamp')");
        }
    	
    	return $rets;
    }
    
    
    return $member;
}


function jsg_member_login_set_status($member)
{
	if(is_numeric($member))
	{
		$member = DB::fetch_first("select * from ".DB::table('members')." where `uid`='$member'");
	}
	
	if(!$member)
	{
		return array();
	}
	
	
    jsg_setcookie('sid', '', -86400000);
    jsg_setcookie('referer', '', -86400000);
   	jsg_setcookie('auth',authcode("{$member['password']}\t{$member['uid']}",'ENCODE'),(365*86400));
   	
   	
   	return $member;
}


function jsg_get_member($username, $isuid = 0)
{
    
    $DatabaseHandler = &Obj::registry('DatabaseHandler');
    
    
    $where = '';
    if($isuid)
    {
        $username = (int) $username;
        $where = "`uid`='$username'";
    }
    else
    {
        $username = addslashes($username);
        $where = "`username`='$username'";
    }
    $member = $DatabaseHandler->FetchFirst("SELECT `uid`,`username`,`password`,`email`,`ucuid` FROM `".TABLE_PREFIX."members` WHERE $where ");
    
    return $member;
}


function jsg_member_checkname($username, $is_nickname = 0, $ucuid = 0)
{	
	$username = trim(strip_tags($username));
	
	
	$username_len = strlen($username);
	if($username_len < 3 || $username_len > 15)
	{
		return -1;
	}
	
		if($ucuid < 1)
	{
		
		if($is_nickname)
		{
						if(false != preg_match('~[\`\~\!\@\$\^\*\/\&\?\-\.\#\%\+\=\[\]\{\}\|\\\\]+~',$username))
			{
				return -1;
			}
		}	
		else 
		{		
						if(is_numeric($username) || (false == preg_match('~^[\w\d\_]+$~',$username)))
			{
				return -1;
			}
		}
	}
	
	
	if(false != filter($username))
	{
		return -2;
	}
	
	
	$censoruser = ConfigHandler::get('user','forbid');
	$censoruser .= "|topic|index|admin|ajax|login|member|profile|tag|get_password|report|weather|master|setting|wap|include|cache|data|api|error_log|iis_rewrite|images|install|modules|templates|uc_client|backup|imjiqiren|sms|qqwb|url|wall|qun|vote|account|yy|renren|douban|kaixin";
	$censorexp = '/^('.trim(str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(trim($censoruser), '/')),'| ').')$/i';
	if($censoruser && preg_match($censorexp, $username)) 
	{
		return -2;
	}
	
		
	$DatabaseHandler = &Obj::registry('DatabaseHandler');
	
	$username = addslashes($username);
	$row = $DatabaseHandler->fetch_first("select `uid` from `" . TABLE_PREFIX . "members` where `username`='$username' or `nickname`='$username' limit 1");
	if($row)
	{
		return -3;
	}
	
	
	if(true === UCENTER && $ucuid < 1)
	{
		include_once ROOT_PATH . 'uc_client/client.php';
		
		$uc_result = uc_user_checkname($username);		
		if($uc_result < 1)
		{
			return $uc_result;
		}
	}
	
	
	return 1;
}



function jsg_member_checkemail($email, $ucuid = 0)
{
	$email = trim(strip_tags($email));
	
	
	$email_len = strlen($email);
	if($email_len < 6 || $email_len > 50)
	{
		return -4;
	}
	if(false == jsg_is_email($email))
	{
		return -4;
	}
	
	$sys_config = ConfigHandler::get();
	
	
	if($sys_config['reg_email_forbid'])
	{
		$email_host = strstr($email,'@');
		if (false !== stristr($sys_config['reg_email_forbid'],$email_host))
		{
			return -5;
		}
	}
		
	
	if(false == $sys_config['reg_email_doublee'])
	{
		$DatabaseHandler = &Obj::registry('DatabaseHandler');
		
		$email = addslashes($email);
		$row = $DatabaseHandler->fetch_first("select `uid` from `" . TABLE_PREFIX . "members` where `email`='$email' limit 1");		
		if($row)
		{
			return -6;
		}
	}
	
	
	if(true === UCENTER && $ucuid < 1)
	{
		include_once ROOT_PATH . 'uc_client/client.php';
		
		$uc_result = uc_user_checkemail($email);
		if($uc_result < 1)
		{
			return $uc_result;
		}
	}
	
	
	
	return 1;
}


?>