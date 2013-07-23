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
 * @Date 2011-09-30 15:07:41 593586719 683225057 9416 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

define("JSG_WAP", true);

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

	function MasterObject(&$config)
	{
				$config['sys_version'] = sys_version();

		        if(!$config['wap_url'])
        {
            $config['wap_url'] = $config['site_url'] . "/wap";
        }

		        if(!$config['topic_length'])
        {
            $config['topic_length'] = 140;
        }

		$this->Config=$config;

				if (!$this->Config['ad']['enable']) {
			unset($this->Config['ad']);
		}

		Obj::register('config',$this->Config);

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
		$GLOBALS['schedule_html'] = '';


				include_once ROOT_PATH . 'include/lib/template.han.php';
		$this->TemplateHandler=new TemplateHandler($config);
		Obj::register('TemplateHandler',$this->TemplateHandler);


				if($this->Config['ipbanned_enable']) {
			$ipbanned=ConfigHandler::get('access','ipbanned');
			if(!empty($ipbanned) && preg_match("~^({$ipbanned})~",client_ip())) {
				$this->Messager("您的IP已经被禁止访问。",null);
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


				if($this->Config['robot']['turnon'])
		{
			include_once ROOT_PATH . 'include/logic/robot.logic.php';
			$RobotLogic=new RobotLogic();
			$robot_name = $RobotLogic->isRobot();
			if($robot_name)
			{
								if ($this->Config['robot']['list'][$robot_name]['disallow']) {
					exit('Access Denied');
				}

				$RobotLogic->statistic();
				include_once ROOT_PATH . 'include/logic/robot_log.logic.php';
				$RobotLogLogic=new RobotLogLogic($robot_name);
				$RobotLogLogic->statistic();
				unset($RobotLogLogic);
			}
			unset($RobotLogic);
		}
		unset($this->Config['robot']);


				include_once ROOT_PATH . 'include/lib/member.han.php';
		$uid = 0;
		$password = '';
		if(($authcode=$this->CookieHandler->GetVar('auth')))
		{
			list($password,$uid)=explode("\t",authcode($authcode,'DECODE'));
		}
		$this->MemberHandler=new MemberHandler($this->Config);
		$this->MemberHandler->FetchMember($uid,$password);
		if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			$member_error = $this->MemberHandler->GetError();
			$member_error = array_iconv($this->Config['charset'],'utf-8',$member_error);
			$this->Messager($member_error,null);
		}
		if($this->MemberHandler->MemberFields)
		{
            $V = $this->MemberHandler->MemberFields;

			$V['username'] = wap_iconv($V['username']);
			$V['nickname'] = wap_iconv($V['nickname']);
            if($V['face'])
            {
                $V['face'] = face_get($V);
            }

            $this->MemberHandler->MemberFields = $V;
		}


		$this->Title=$this->MemberHandler->CurrentAction['name'];		Obj::register("MemberHandler",$this->MemberHandler);

	}


	function Messager($message, $redirectto='',$time = -1,$return_msg=false,$js=null,$returntype='')
	{
		global $rewriteHandler;


		if ($time===-1) $time = (is_numeric($this->Config['msg_time'])?$this->Config['msg_time']:5);
		$to_title=($redirectto==='' or $redirectto==-1)?"返回上一页":"跳转到指定页面";
		if($redirectto===null)
		{
			$return_msg=$return_msg===false?"  ":$return_msg;
		}
		else
		{
			$redirectto=($redirectto!=='')?$redirectto:($from_referer=referer());
			if(str_exists($redirectto,'mod=login','code=register','/login','/register'))
			{
				$referer='&referer='.urlencode('index.php?'.'mod=topic&code=new');

				$this->CookieHandler->Setvar('referer','index.php?'.'mod=topic&code=new');
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
					$redirectto .= $referer;
					if(!$from_referer && !$referer) {
						$redirectto=$rewriteHandler->formatURL($redirectto,true);
					}
				}
				if($message===null)
				{
					$redirectto=rawurldecode(jstripslashes(($redirectto)));

					@header("Location: $redirectto"); #HEADER跳转
				}
				if($time!==null)
				{
					$url_redirect = ($redirectto?'<meta http-equiv="refresh" content="' . $time . '; URL=' . $redirectto . '">':null);
				}
			}
		}

		$title="消息提示:".(is_array($message)?implode(',',$message):$message);

		$title=strip_tags($title);
		if($js!="") {
			$js="<script language=\"JavaScript\" type=\"text/javascript\">{$js}</script>";
		}
		$additional_str = $url_redirect.$js;

		$this->Title = '操作提示';

				$return_Url = $_SERVER['HTTP_REFERER'];

		include $this->TemplateHandler->Template('messager');

		exit;
	}

	function ShowBody($body)
	{
		echo $body;

		//echo "P"."o"."w"."e"."r"."e"."d"." b"."y"." D"."a"."z"."h"."e"."n"."g";
	}

    function _topicLogicGet($ids,$fields='*',$process='Make',$table="",$prikey='tid')
    {
        $data = $this->TopicLogic->Get($ids,$fields,$process,$table,$prikey);

        if($data)
        {
            $data = wap_iconv($data);
        }

        return $data;
    }
    function _topicLogicMake($topic,$actors=array())
    {
        $data = $this->TopicLogic->Make($topic,$actors);

        if($data)
        {
            $data = wap_iconv($data);
        }

        return $data;
    }
    function _topicLogicGetMember($ids,$fields = '*')
    {
        $data = $this->TopicLogic->GetMember($ids,$fields);

        if($data)
        {
            $data = wap_iconv($data);
        }

        return $data;
    }

        function _LongtextLogic($ids,$fields = '*')
    {
        $data = $this->LongtextLogic->get_info($ids,$fields);

        if($data)
        {
            $data = wap_iconv($data);
        }

        return $data;
    }


        function _OtherLogicFavorite($uid=0,$tid=0,$act='')
    {
    		include_once ROOT_PATH . 'include/logic/other.logic.php';

				$OtherLogic = new OtherLogic();
    		$data = $OtherLogic->TopicFavorite($uid,$tid,$act);

    		if($data)
    		{
    			$data = wap_iconv($data);
    		}

    		return $data;
    }

}
?>