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
 * @Date 2011-09-09 10:58:39 329791265 624188750 4534 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class MasterObject
{
	
	var $Config=array();
	var $Get;
	var $Post;
	var $Cookie;
	var $Session;

	
	var $DatabaseHandler;
	
	var $MemberHandler;

	
	var $TemplateHandler;
	
	var $CommonLogic;

	
	var $CookieHandler;


	
	var $Title='';

	
	var $Module='index';

	
	var $Code='';


	function MasterObject(&$config)
	{

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

		

		$this->Get     =  &$_GET;

		$this->Post    =  &$_POST;

		$this->Cookie  =  &$_COOKIE;

		$this->Session =  &$_SESSION;

		$this->Request =  &$_REQUEST;

		$this->Server  = &$_SERVER;

		$this->Files   =   &$_FILES;

		$this->Module = $this->Post['mod']?$this->Post['mod']:$this->Get['mod'];
		$this->Code   = $this->Post['code']?$this->Post['code']:$this->Get['code'];	
		
		$GLOBALS['iframe'] = '';
		$GLOBALS['schedule_html'] = '';

						
				include_once ROOT_PATH . 'include/lib/template.han.php';
		$this->TemplateHandler=new TemplateHandler($config);
		Obj::register('TemplateHandler',$this->TemplateHandler);
		
		
				if($this->Config['ipbanned_enable']) {
			$ipbanned=ConfigHandler::get('access','ipbanned');
			if(!empty($ipbanned) && preg_match("~^({$ipbanned})~",client_ip())) {
				exit("您的IP已经被禁止访问。");
			}
			unset($ipbanned);
		}		
		
		

				include_once ROOT_PATH . 'include/lib/cookie.han.php';
		$this->CookieHandler = new CookieHandler($this->Config, $_COOKIE);
		Obj::register('CookieHandler',$this->CookieHandler);		
		
		
		
		
				include_once ROOT_PATH . 'include/db/database.db.php';
		include_once ROOT_PATH . 'include/db/mysql.db.php';
		$this->DatabaseHandler = new MySqlHandler($this->Config['db_host'],$this->Config['db_port']);
		$this->DatabaseHandler->Charset($this->Config['charset']);
		$this->DatabaseHandler->doConnect($this->Config['db_user'],$this->Config['db_pass'],$this->Config['db_name'],$this->Config['db_persist']);
		Obj::register('DatabaseHandler',$this->DatabaseHandler);

	}
	
	function initMemberHandler()
	{
		include_once ROOT_PATH . 'include/lib/member.han.php';
		list($password,$uid)=explode("\t",authcode($this->CookieHandler->GetVar('auth'),'DECODE'));
		$this->MemberHandler=new MemberHandler($this->Config);
		$member=$this->MemberHandler->FetchMember($uid,$password);
		Obj::register("MemberHandler",$this->MemberHandler);
		return $member;
	}

    function js_show_msg()
    {        
        $return = "{$GLOBALS['schedule_html']}";

        if($this->Config['jsg_schedule'] || jsg_getcookie('jsg_schedule'))
		{
			$return .= jsg_schedule();
		}        
        
        if(!$GLOBALS['js_show_msg_executed'] && ($js_show_msg=($_REQUEST['js_show_msg'] ? $_REQUEST['js_show_msg'] : $this->CookieHandler->GetVar('js_show_msg'))))
        {
            $GLOBALS['js_show_msg_executed'] = 1;
            $this->CookieHandler->DeleteVar('js_show_msg');
            jsg_setcookie('js_show_msg','',-86400000);
            unset($_REQUEST['js_show_msg'],$_COOKIE['js_show_msg']);
            
                        $return .= "<script language='javascript'>
            	$(document).ready(function(){show_message('{$js_show_msg}');});
            </script>";
        }
        
        return $return;
    }
}
?>