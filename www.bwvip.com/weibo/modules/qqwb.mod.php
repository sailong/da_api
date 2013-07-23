<?php
/**
 * 文件名：qqwb.mod.php
 * 版本号：1.0
 * 最后修改时间：2011年3月3日
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: QQ微博接口模块
 */


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
    
    var $QQWBConfig = array();    
    
    var $QQWBBindInfo = array();
    
    var $QQWBApi = array();
    
    var $UserInfo = array();
    

	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->_init_qqwb();

		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch ($this->Code)
        {                
            case 'login':
                $this->Login();
                break;          
            
            case 'auth_callback':
                $this->AuthCallback();
                break;
            
            case 'login_check':
                $this->LoginCheck();
                break;
            
            case 'do_login':
                $this->DoLogin();
                break;
                    
            case 'reg_check':
                $this->RegCheck();
                break;
                
            case 'do_reg':
                $this->DoReg();
                break;  
                
            case 'unbind':
                $this->UnBind();
                break;
                
            case 'do_modify_bind_info':
                $this->DoModifyBindInfo();
                break;

			default:
				$this->Main();
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}

	function Main()
	{
		$this->Messager("未定义的操作",null);
        
        	}
    
    function Login()
    {
        $aurl = $this->_get_oauth_url();
        
                
        $this->Messager(null,$aurl);
    }
    
    function AuthCallback()
    {
        $qqwb_oauth_token_secret = $_SESSION['qqwb_oauth_token_secret'] ? $_SESSION['qqwb_oauth_token_secret'] : jsg_getcookie('qqwb_oauth_token_secret');
        if(!$qqwb_oauth_token_secret || !$_REQUEST['oauth_token'])
        {
            $this->Messager(null,'?');
        }
        
        require_once(ROOT_PATH . 'include/qqwb/qqoauth.php');        
        
        $QQAuth = new QQOAuth($this->QQWBConfig['app_key'],$this->QQWBConfig['app_secret'],$_REQUEST['oauth_token'],$qqwb_oauth_token_secret);
        
        $last_keys = $QQAuth->getAccessToken($_REQUEST['oauth_verifier']);
        
        if(!$last_keys['oauth_token'] || !$last_keys['oauth_token_secret'])
        {
            $this->Messager(null,'?');
        }        
        
                $QQAuth = new QQOAuth($this->QQWBConfig['app_key'],$this->QQWBConfig['app_secret'],$last_keys['oauth_token'],$last_keys['oauth_token_secret']);
        
        $QQInfo = $QQAuth->OAuthRequest('http:/'.'/open.t.qq.com/api/user/info?format=json', 'GET',array()); 
        
        unset($_SESSION['qqwb_oauth_token_secret']);
        jsg_setcookie('qqwb_oauth_token_secret','');
        
        if(!$QQInfo)
        {
            $this->Messager('连接失败',null);
        }       
        if('no auth'==$QQInfo)
        {
            $this->Messager($QQInfo,null);
        }
        
        $QQInfo = json_decode($QQInfo);
        if(!$QQInfo || !$QQInfo->data)
        {
            $this->Messager('解析失败',null);
        }
        
        $QQInfo = $QQInfo->data;
        if(!$QQInfo->name)
        {
            $this->Messager('内容错误',null);
        }
        
        $qqwb_bind_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."qqwb_bind_info where `qqwb_username`='{$QQInfo->name}'");
        
        if($qqwb_bind_info)
        {
            if($last_keys['token']!=$last_keys['oauth_token'] || $last_keys['ksecret']!=$last_keys['oauth_token_secret'])
            {
                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."qqwb_bind_info set `token`='{$last_keys['oauth_token']}',`tsecret`='{$last_keys['oauth_token_secret']}' where `qqwb_username`='{$QQInfo->name}'");
            }
            
                        if(false != ($user_info = $this->_user_login($qqwb_bind_info['uid'])))
            {
                if(true === UCENTER && ($ucuid = (int) $user_info['ucuid']) > 0)
                {
                    include_once(ROOT_PATH . 'uc_client/client.php');
                    
                    $uc_syn_html = uc_user_synlogin($ucuid);
                    
                    $this->Messager("登录成功，正在为您跳转到首页。{$uc_syn_html}",'?',5);
                }
                
                $this->Messager(null,'?');
            }
            else
            {
                $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."qqwb_bind_info where `qqwb_username`='{$QQInfo->name}'");
                
                $this->Messager("绑定的用户已经不存在了",'?');
            }    
        }
        else
        {
            if(MEMBER_ID > 0)
            {
                $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."qqwb_bind_info (`uid`,`qqwb_username`,`token`,`tsecret`,`dateline`) values ('".MEMBER_ID."','{$QQInfo->name}','{$last_keys['oauth_token']}','{$last_keys['oauth_token_secret']}','".time()."')");
                
                $this->Messager(null,'index.php?mod=tools&code=qqwb');
            }
            else
            {
                                $qqwb_username = $QQInfo->name;
                $token = $last_keys['oauth_token'];
                $tsecret = $last_keys['oauth_token_secret'];
                
                $reg = array();
                $reg['username'] = $QQInfo->name;
                $reg['nickname'] = array_iconv('utf-8',$this->Config['charset'],$QQInfo->nick);
                if($this->QQWBConfig['is_sync_face'] && $QQInfo->head)
                {
                    $reg['face'] = $QQInfo->head . '/180';
                }
                
                
                $this->Title = 'QQ微博帐号绑定';
                include($this->TemplateHandler->Template('qqwb_bind_info'));
            }
        }
    }
    
    function RegCheck()
    {
        exit($this->_reg_check());
    }
    function _reg_check()
    {
        $in_ajax = $this->Request['in_ajax'];
        if($in_ajax)
        {
            $this->Post = array_iconv('utf-8',$this->Config['charset'],$this->Post);
        }
        
        $username = trim($this->Post['username']);
        $nickname = trim($this->Post['nickname']);
        $email = trim($this->Post['email']);
        
        Load::functions('member');
        
        $rets = array(
        	'0' => '未知错误',
        	'-1' => '不合法',
        	'-2' => '不允许注册',
        	'-3' => '已经存在了',
        	'-4' => '不合法',
        	'-5' => '不允许注册',
        	'-6' => '已经存在了',
        );
        
        $ret = jsg_member_checkname($username);        
        if($ret < 1)
        {
        	return "用户名 " . $rets[$ret];
        }
        
        $ret = jsg_member_checkname($nickname, 1);
        if($ret < 1)
        {
        	return "昵称 " . $rets[$ret];
        }
        
        $ret = jsg_member_checkemail($email);
        if($ret < 1)
        {
        	return "Email " . $rets[$ret];
        }
        
        return '';
    }    
    function DoReg()
    {
        if(false != ($check_result = $this->_reg_check()))
        {
            $this->Messager($check_result,null);
        }
        
        $username = trim($this->Post['username']);
        $nickname = trim($this->Post['nickname']);
        $password = trim($this->Post['password']);
        $email = trim($this->Post['email']);
        
        Load::functions('member');
        
        $ret = jsg_member_register($username, $password, $email, $nickname);
        if($ret < 1)
        {
        	$this->Messager("注册失败{$ret}",null);
        }
        
        $rets = jsg_member_login($username, $password);
        
        
        if($this->QQWBConfig['reg_pwd_display'])
        {
            $this->Messager("您的帐户 <strong>{$rets['username']}</strong> 已经注册成功，请牢记您的密码 <strong>{$password}</strong>，现在为您转入到首页{$rets['uc_syn_html']}","?",10);
        }
        else
        {
            if($rets['uc_syn_html'])
            {
                $this->Messager("注册成功，现在为您转入到首页{$rets['uc_syn_html']}",'?');
            }
            else
            {
                $this->Messager(null,'?');
            }
        }
    }
    
    function LoginCheck()
    {
        exit($this->_login_check());
    }
    function _login_check()
    {   
        $in_ajax = $this->Request['in_ajax'];
        if($in_ajax)
        {
            $this->Post = array_iconv('utf-8',$this->Config['charset'],$this->Post);
        }
        
        $username = trim($this->Post['username']);
        $password = trim($this->Post['password']);
        
        Load::functions('member');
        $rets = jsg_member_login_check($username, $password);
		$ret = $rets['uid'];
        if($ret < 1)
        {
        	$rets = array(
        		'0' => '未知错误 ',
        		'-1' => '用户名或者密码错误',
        		'-2' => '用户名或者密码错误',
        		'-3' => '累计 5 次错误尝试，15 分钟内您将不能登录',
        	);
        	
        	return $rets[$ret];
        }
        
        $this->UserInfo = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `username`='{$username}'");
        
        return '';
    }
    function DoLogin()
    {
        if(false != ($check_result = $this->_login_check()))
        {
            $this->Messager($check_result,null);
        }
        
        $timestamp = time();
        $username = trim($this->Post['username']);
        $password = trim($this->Post['password']);
        
        Load::functions('member');
        $rets = jsg_member_login($username, $password);             
        
        $qqwb_username = $this->Post['qqwb_username'];
        if($qqwb_username)
        {
            $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."qqwb_bind_info (`uid`,`qqwb_username`,`token`,`tsecret`,`dateline`) values ('{$this->UserInfo['uid']}','$qqwb_username','{$this->Post['token']}','{$this->Post['tsecret']}','$timestamp')");
        }
        
    	if($rets['uc_syn_html'])
        {            
            $this->Messager("登录成功，现在为您转入到首页{$rets['uc_syn_html']}","?",5);
        }
        else
        {
        	$this->Messager(null,'?');
        }
    }
    
    function UnBind()
    {
        $uid = max(0, (int) MEMBER_ID);
        if($uid < 1)
        {
            $this->Messager("请先登录",null);
        }
        
        $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."qqwb_bind_info where `uid`='$uid'");
        
        
        $this->Messager("已经成功解除绑定");
    }
    
    function DoModifyBindInfo()
    {
        $uid = max(0, (int) MEMBER_ID);
        if($uid < 1)
        {
            $this->Messager("请先登录",null);
        }
        
        $synctoqq = ($this->Request['synctoqq'] ? 1 : 0);
        
        $this->DatabaseHandler->Query("update ".TABLE_PREFIX."qqwb_bind_info set `synctoqq`='$synctoqq' where `uid`='$uid'");
        
        
        $this->Messager("设置成功");
    }
    
    function _init_qqwb()
    {
        if ($this->Config['qqwb_enable'] && qqwb_init($this->Config))
		{ 
            if(!session_id())
            {
                session_start();
            }
            
            $this->QQWBConfig = ConfigHandler::get('qqwb');
                           		  
			if(MEMBER_ID > 0)
            {
                $this->QQWBBindInfo = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."qqwb_bind_info where `uid`='".MEMBER_ID."'");
            }
            
            require_once(ROOT_PATH . 'include/qqwb/oauth.php');
		}
		else
		{
			$this->Messager("整合QQ微博的功能未开启",null);
		}
        
        ;
    }

    function _get_oauth_url()
    {
        $callback = $this->Config['site_url'] . "/index.php?mod=qqwb&code=auth_callback";
        
        require_once(ROOT_PATH . 'include/qqwb/qqoauth.php');        
        
        $QQAuth = new QQOAuth($this->QQWBConfig['app_key'],$this->QQWBConfig['app_secret']);
        
        $keys = $QQAuth->getRequestToken($callback);
        
        $_SESSION['qqwb_oauth_token_secret'] = $keys['oauth_token_secret'];        
        jsg_setcookie('qqwb_oauth_token_secret',$_SESSION['qqwb_oauth_token_secret']);
        
        $aurl = $QQAuth->getAuthorizeURL($keys['oauth_token'], $callback);     
        
        return $aurl;   
    }
    
    function _user_login($uid)
    {
    	Load::functions('member');
    	
    	return jsg_member_login_set_status($uid);
    }
}


?>
