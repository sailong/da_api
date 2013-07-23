<?php
/**
 * 文件名：get_password.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年10月27日 10时05分58秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 取回密码模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	

	function ModuleObject($config)
	{
		$this->MasterObject($config);
		
		if (MEMBER_ID>0) {
			}
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();		
		switch ($this->Code) {
			case 'do_send':
				$this->DoSend();
				break;
			case 'do_reset';
				$this->DoReset();
				break;
				
			default:
				$this->Main();
		}				
		$body=ob_get_clean();
		
		$this->ShowBody($body);
	}

	function Main() 
	{
		$act_list = array('base'=>'取回密码','reset'=>'重设密码',);
		$act = isset($act_list[$this->Code]) ? $this->Code : 'base';
		$act_name = $act_list[$act];
		
		Load::lib('form');
		$FormHandler = new FormHandler();		
		
		if('base' == $act) {
			
			;	
			
		} elseif ('reset' == $act) {
			
			extract($this->_resetCheck());
						
		}
		
		
		$this->Title = $act_list[$act];
		include($this->TemplateHandler->Template('get_password_main'));
	}
	
	function DoSend()
	{
		$to = $this->Post['to'];
		
		$sql="
		SELECT
			M.uid,MF.authstr,M.email
		FROM
			".TABLE_PREFIX.'members'." M LEFT JOIN ".TABLE_PREFIX.'memberfields'." MF ON(M.uid=MF.uid)
		WHERE
			BINARY M.email='{$to}'";
		$query = $this->DatabaseHandler->Query($sql);
		$member=$query->GetRow();
		if ($member==false)$this->Messager("用户已经不存在");
		$timestamp=time();
		if ($member['authstr']!='')
		{
			list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
			$inteval=600;			if ($dateline+$inteval>$timestamp)
			{
				$this->Messager("请不要重复恶意发送，您的请求已经发送到您的信箱中，如有问题，请与管理员联系。",-1,null);
			}
		}

		$idstring = random(6);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'memberfields');
		$member['authstr']="$timestamp\t1\t$idstring";
		$result=$this->DatabaseHandler->Update($member,"uid={$member['uid']}");
		if ($result==false)
		{
			$this->DatabaseHandler->Insert($member);
		}
		$onlineip= client_ip();
				$email_message="您好：
您收到这封邮件，是因为Email地址在{$this->Config['site_name']}上被登记为用户邮箱，
且用户请求使用 Email 密码重置功能所致。
----------------------------------------------------------------------
重要：如果您没有提交密码重置的请求或不是{$this->Config['site_name']}的注册用户，请立即忽略
并删除这封邮件。
----------------------------------------------------------------------
如果是您发起了找回密码申请，请在三天之内，通过点击下面的链接重置您的密码：
{$this->Config['site_url']}/index.php?mod=get_password&code=reset&uid={$member['uid']}&id={$idstring}
(如果上面不是链接形式，请将地址手工粘贴到浏览器地址栏再访问)

上面的页面打开后，输入新的密码后提交，之后您即可使用新的密码登录
{$this->Config['site_name']}了。您可以在个人设置中随时修改您的密码。

本请求提交者的 IP 为 $onlineip
此致
{$this->Config['site_name']} 管理团队.
{$this->Config['site_url']}";
			include(ROOT_PATH . 'include/lib/mail.han.php');
			$subject="[{$this->Config['site_name']}] 取回密码说明";
			send_mail($member['email'],$subject,
			$email_message,$this->Config['site_name'],$this->Config['site_admin_email'],
			array(),3,$html=false) ;
		$mail_service=strstr($member['email'], '@');		
		$message=array(
		"标题为\"<b>{$subject}</b>\"的邮件已经发送到您后缀为<b>\"{$mail_service}\"</b>的信箱中，请在 3 天之内修改您的密码。",
		"邮件发送可能会延迟几分钟，请耐心等待。",
		"部分邮件提供商会将本邮件当成垃圾邮件来处理，您或许可以进垃圾箱找到此邮件。",
		);
		$this->Messager($message,null,null);
	}
	
	function DoReset()
	{
		$this->_resetCheck();
		
		$uid=(int)($this->Get['uid']?$this->Get['uid']:$this->Post['uid']);		
		if($this->Post['password']!=$this->Post['confirm'] or $this->Post['password']=='')
		{
			$this->Messager('两次输入的密码不一致,或新密码不能为空。',-1,null);
		}
        
        $member_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='$uid'");
        if(!$member_info)
        {
            $this->Messager("用户已经不存在了",null);
        }
        
		$password=md5($this->Post['password']);
		$sql="UPDATE ".TABLE_PREFIX.'members'." SET `password`='{$password}' WHERE uid='$uid'";
		$this->DatabaseHandler->Query($sql);
		$sql="UPDATE ".TABLE_PREFIX.'memberfields'." SET `authstr`='' WHERE uid='$uid'";
		$this->DatabaseHandler->Query($sql);
        
                if(true===UCENTER)
        {
            include(ROOT_PATH . 'uc_client/client.php');
            
            uc_user_edit($member_info['username'],'',$this->Post['password'],'',1);
        }
        
		$this->Messager("新密码设置成功,现在为您转入登录界面.",$this->Config['site_url'] . "/index.php?mod=login");
	}
	
	function _resetCheck()
	{
		$uid=(int)($this->Post['uid'] ? $this->Post['uid'] : $this->Get['uid']);
		$id=$this->Post['id'] ? $this->Post['id'] : $this->Get['id'];
		if ($uid<1 or $id=='') $this->Messager("请求错误",null);

		$sql="
		SELECT
			M.uid,M.username,MF.authstr,M.email,M.secques
		FROM
			".TABLE_PREFIX.'members'." M LEFT JOIN ".TABLE_PREFIX.'memberfields'." MF ON(M.uid=MF.uid)
		WHERE
			BINARY M.uid='$uid'";
		$query = $this->DatabaseHandler->Query($sql);
		$member=$query->GetRow();
		if ($member==false)$this->Messager("用户已经不存在",null,null);
		$timestamp=time();
		list($dateline, $operation, $idstring) = explode("\t", $member['authstr']);
		if($dateline < $timestamp - 86400 * 3 || $operation != 1 || $idstring != $id) 
		{
			$message=array(
				"重置密码的请求不存在或已经过期，无法取回密码。",
				"如您想重新设置密码，请<a href='index.php?mod=get_password'>单击此处</a>。"
			);
			$this->Messager($message,null,null);
		}
		$member['id'] = $id;
		
		return $member;
	}
	
}

?>
