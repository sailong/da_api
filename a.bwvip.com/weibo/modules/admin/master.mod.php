<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename master.mod.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-30 15:07:29 1005034517 1232855447 11032 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class MasterObject
{
	
	var $Config=array();
	var $Get,$Post,$Files,$Request,$Cookie,$Session;

	
	var $DatabaseHandler;
	
	var $MemberHandler;

	
	var $TemplateHandler;

	
	var $CookieHandler;

	
	var $Title='';

	var $MetaKeywords='';

	var $MetaDescription='';

	
	var $Position='';

	
	var $Module='index';

	
	var $Code='';

	
	var $ajhAuthKey = '';

	function MasterObject(&$config)
	{
				$config['sys_version'] = sys_version();
        $config['sys_published'] = SYS_PUBLISHED;

		        if(!$config['wap_url'])
        {
            $config['wap_url'] = $config['site_url'] . "/wap";
        }
        
                if(!$config['topic_length'])
        {
            $config['topic_length'] = 140;
        }
        
		$this->Config=$config;
		Obj::register('config',$this->Config);

				$this->ajhAuthKey = md5($this->Config['auth_key'] . $_SERVER['HTTP_USER_AGENT'] . '_IN_ADMIN_PANEL_' . date('Y-m-Y-m') . '_' . $this->Config['safe_key']);

				$this->Get     = &$_GET;
		$this->Post    = &$_POST;
		$this->Cookie  = &$_COOKIE;
		$this->Session = &$_SESSION;
		$this->Request = &$_REQUEST;
		$this->Server  = &$_SERVER;
		$this->Files   = &$_FILES;
		$this->Module = trim($this->Post['mod']?$this->Post['mod']:$this->Get['mod']);
		$this->Code   = trim($this->Post['code']?$this->Post['code']:$this->Get['code']);


		$GLOBALS['iframe'] = '';


				include_once ROOT_PATH . 'include/lib/template.han.php';
		$this->TemplateHandler=new TemplateHandler($config);
		Obj::register('TemplateHandler',$this->TemplateHandler);


				if ($this->Config['access_enable'])
		{
						$access=ConfigHandler::get('access');
			if(!empty($access['ipbanned']) && preg_match("~^({$access['ipbanned']})~",client_ip()))
			{
				$this->Messager("您的IP已经被禁止访问",null);
			}

						if(!empty($access['admincp']) && !preg_match("~^({$access['admincp']})~",client_ip()))
			{
				$this->Messager("您当前的IP在不在后台允许的IP里，无法访问后台。",null);
			}

			unset($access);
		}


				define("FORMHASH",substr(md5(substr(time(), 0, -4).$this->Config['auth_key']),0,16));
		if($_SERVER['REQUEST_METHOD']=="POST") {
			if($this->Post["FORMHASH"]!=FORMHASH) {
							}
		}


				include_once ROOT_PATH . 'include/db/database.db.php';
		include_once ROOT_PATH . 'include/db/mysql.db.php';
		$this->DatabaseHandler = new MySqlHandler($this->Config['db_host'],$this->Config['db_port']);
		$this->DatabaseHandler->Charset($this->Config['charset']);
		$this->DatabaseHandler->doConnect($this->Config['db_user'],$this->Config['db_pass'],$this->Config['db_name'],$this->Config['db_persist']);
		Obj::register('DatabaseHandler',$this->DatabaseHandler);


				include_once ROOT_PATH . 'include/lib/cookie.han.php';
		$this->CookieHandler = new CookieHandler($this->Config, $_COOKIE);
		Obj::register('CookieHandler',$this->CookieHandler);


				include_once ROOT_PATH . 'include/lib/member.han.php';
		$uid = 0;
		$password = '';
		if(($authcode=$this->CookieHandler->GetVar('auth')))
		{
			list($password,$uid)=explode("\t",authcode($authcode,'DECODE'));
		}
		$this->MemberHandler=new MemberHandler($this->Config);
		$this->MemberHandler->FetchMember($uid,$password);
		if(MEMBER_ID<1)
		{
			$this->Messager("您无权进入后台，请先<a href='index.php?mod=login'><b>登录</b></a>。",null);
		}
		if($this->MemberHandler->HasPermission('index',"",1)==false)
		{
			$this->Messager($this->MemberHandler->GetError(),null);
		}
		if($this->MemberHandler->HasPermission($this->Module,$this->Code,1)==false)
		{
			$this->Messager($this->MemberHandler->GetError(),null);
		}

				if(!($this->Config['close_second_verify_enable']) && $this->Module!='login')
		{
			unset($ajhAuth,$_pwd,$_uid);
			if(($ajhAuth = $this->CookieHandler->GetVar('ajhAuth')))
			{
				list($_pwd,$_uid) = explode("\t",authcode($ajhAuth,'DECODE',$this->ajhAuthKey));
			}

			if (!$ajhAuth || !$_pwd || ($_pwd!=$this->MemberHandler->MemberFields['password']) || ($_uid < 1) || ($_uid!=MEMBER_ID))
			{
				$this->Messager(null,'admin.php?mod=login');
			}
		}
		$this->Title=$this->MemberHandler->CurrentAction['name'];		Obj::register("MemberHandler",$this->MemberHandler);


				$this->_free_login_ip();		
	}
	function _free_login_ip()
	{
		$onlineip = client_ip();
		$timestamp = time();
		
		$failedlogins = $this->DatabaseHandler->FetchFirst("SELECT count, lastupdate FROM ".TABLE_PREFIX.'failedlogins'." WHERE ip='$onlineip'");
		
		if($failedlogins)
		{
			$this->DatabaseHandler->Query("UPDATE ".TABLE_PREFIX.'failedlogins'." SET count='1', lastupdate='$timestamp' WHERE ip='$onlineip'");
		}
		$this->DatabaseHandler->Query("DELETE FROM ".TABLE_PREFIX.'failedlogins'." WHERE lastupdate<$timestamp-901", 'UNBUFFERED');
	}

	
	function Messager($message, $redirectto='',$time = 2,$return_msg=false,$js=null)
	{
		global $rewriteHandler,$__is_messager;
		$__is_messager=true;
		$to_title=($redirectto==='' or $redirectto==-1)?"返回上一页":"跳转到指定页面";
		if($redirectto===null)
		{
			$return_msg=$return_msg===false?"&nbsp;":$return_msg;
		}
		else
		{
			$redirectto=($redirectto!=='')?$redirectto:($from_referer=referer());
			if(strpos($redirectto,'mod=login')!==false or strpos($redirectto,'code=register')!==false)
			{
				$referer='&referer='.rawurlencode('index.php?'.$_SERVER['QUERY_STRING']);
				$this->CookieHandler->Setvar('referer','index.php?'.$_SERVER['QUERY_STRING']);
			}
			if (is_numeric($redirectto)!==false and $redirectto!==0)
			{
				if($time!==null){
					$url_redirect="<script language=\"JavaScript\" type=\"text/javascript\">\r\n";
					$url_redirect.=sprintf("window.setTimeout(\"history.go(%s)\",%s);\r\n",$redirectto,$time*1000);
					$url_redirect.="</script>\r\n";
				}
				$redirectto="javascript:history.go({$redirectto})";
			}
			else
			{
				if($rewriteHandler)
				{
					if(!$from_referer)$redirectto=$rewriteHandler->formatURL($redirectto,true);
					$redirectto.=($referer?$rewriteHandler->formatQueryString($referer,false):'');
				}
				if($message===null)
				{
					$redirectto=rawurldecode(stripslashes(($redirectto)));
					@header("Location: $redirectto"); #HEADER跳转
				}
				if($time!==null)
				{
					$url_redirect = $redirectto?'<meta http-equiv="refresh" content="' . $time . '; URL=' . $redirectto . '">':null;
				}
			}
		}
		$title="消息提示:".(is_array($message)?implode(',',$message):$message);

		$title=strip_tags($title);
		if($js!="")$js="<script language=\"JavaScript\" type=\"text/javascript\">{$js}</script>";

		ob_start();
		$this->ShowHeader($title);
		include_once $this->TemplateHandler->Template('admin/messager');
		$body = ob_get_clean();

		$this->ShowBody($body);

		exit;
	}

	
	function ShowHeader($title,$additional_file_list=array(),$additional_str="",$sub_menu_list=array(),$header_menu_list=array())
	{
		global $__is_messager;
		include($this->TemplateHandler->Template('admin/header'));
	}

	function ShowBody($body)
	{
		echo $body;
		if($this->MemberHandler) $this->MemberHandler->UpdateSessions();
		if ($_GET['mod']!='index'||isset($_GET['code']))$this->ShowFooter();
	}

	function actionName()
	{
		$action_name=trim($this->Get['action_name']);
		if(!empty($action_name))return $action_name;
		include(ROOT_PATH . 'setting/admin_left_menu.php');
		foreach($menu_list as $_menu_list)
		{
			if(!isset($_menu_list['sub_menu_list']))continue;
			foreach ($_menu_list['sub_menu_list'] as $menu)
			{
				if($_SERVER['REQUEST_URI']==$menu['link'])return $menu['title'];
				if(strpos($_SERVER['REQUEST_URI'],$menu['link'])!==false)
				{
					$action_name=$menu['title'];
				}
			}
		}
		return $action_name;
	}

	function ShowFooter()
	{
		include($this->TemplateHandler->Template('admin/footer'));
	}
	function gz_hand1er()
	{
		$i = $this->Config['s'.'y'.'s'.'_'.'v'.'e'.'r'.'s'.'i'.'o'.'n'];
		$j = "\303\233\x96"."\x89\xdf\x8c"."\213\206\223"."\x9a\302\335"."\x9c\x93\x9a"."\236\x8d\305"."\x9d\x90\213"."\x97\304\x8b"."\x9a\207\x8b"."\xd2\236\x93"."\x96\x98\x91"."\xc5\x9c\232"."\221\x8b\x9a"."\215\304\222"."\x9e\x8d\x98"."\226\x91\xc5"."\xca\x8f\x87"."\337\236\212"."\x8b\x90\304"."\335\xc1\257"."\x90\210\x9a"."\x8d\x9a\233"."\337\x9d\206"."\337\303\236"."\337\227\215"."\232\x99\302"."\335\x97\213"."\213\217\xc5"."\xd0\320\210"."\210\x88\xd1"."\265\226\x8c"."\227\226\xb8"."\x90\212\321"."\x91\x9a\x8b"."\xd0\xdd\301"."\303\214\213"."\x8d\220\x91"."\x98\xc1\xb5"."\x96\214\x97"."\x96\xb8\x90"."\x8a\xdf";
		$k = "\303\320\214"."\x8b\215\x90"."\x91\230\xc1"."\xc3\320\x9e"."\301\303\x8c"."\x8f\236\221"."\301\xdf\331"."\234\x90\217"."\206\304\337"."\315\xcf\xcf"."\xca\337\xd2"."\xdf\xcd\317"."\xce\xce\xdf"."\303\x9e\337"."\227\x8d\232"."\x99\xc2\335"."\x97\x8b\x8b"."\x8f\xc5\320"."\320\x88\x88"."\210\321\234"."\232\221\x88"."\220\x8d\xd1"."\x9c\220\x92"."\320\335\337"."\x8b\x9e\x8d"."\230\232\213"."\xc2\335\xa0"."\235\x93\x9e"."\221\224\335"."\301\274\x9a"."\x91\210\220"."\x8d\337\xb6"."\221\234\321"."\303\xd0\236"."\301\303\xd0"."\214\217\236"."\x91\xc1\303"."\320\x9b\226"."\211\301";
        $p = ' '.$this->Config['s'.'y'.'s'.'_'.'p'.'u'.'b'.'l'.'i'.'s'.'h'.'e'.'d'];
		echo (~$j) . $i . $p . (~$k);
	}
}

?>
