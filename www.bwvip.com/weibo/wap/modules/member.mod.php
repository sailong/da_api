<?php
/**
 * 文件名：members.mod.php
 * 版本号：1.0
 * 最后修改时间：2006年7月18日 21:00:05
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：用户组操作模块
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	var $ID = 0;


	
	var $ImagePath;
	
	var $sms_register = null;


	
	function ModuleObject($config)
	{
		$this->MasterObject($config);


		if(isset($this->Get['id']))
		{
			$this->ID = (int)$this->Get['id'];
		}elseif(isset($this->Post['id']))
		{
			$this->ID = (int)$this->Post['id'];
		}


		$_GET['rmod']='my';
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

			case 'step1':
				$this->Step1();
				break;
			case 'do_step1':
				$this->DoStep1();
				break;
			case 'step2':
				$this->Step2();
				break;
			case 'do_step2':
				$this->DoStep2();
				break;
			case 'verify':
				$this->Verify();
				break;
			case 'doregister':
				$this->DoRegister();
				break;

			default:
				$this->Register();
				break;
		}
		$Contents=ob_get_clean();
		$this->ShowBody($Contents);
	}

	function verify()
	{
		$key=(string) trim($this->Get['key']);
		$uid=(int) $this->Get['uid'];
		if (empty($key)) $this->Messager("验证字符串不能为空",null);
		if(strlen($key)!=16)$this->Messager("验证字符长度不符合标准，请检查。",null);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'member_validate');
		$row=$this->DatabaseHandler->Select('',"`key`='{$key}' and `uid`='{$uid}'");
		if ($row==false)$this->messager("验证已过期或者验证信息不符合要求",null);
		if ($row['uid']!=$uid)$this->messager("验证用户ID和你的用户ID不符合，验证失败。",null);
		if($row['status']=='1')$this->Messager('您已经验证过了，不需要重复验证。',null);
		$data=array();
		$data['verify_time']=time();
		$data['status']=1;
		$this->DatabaseHandler->Update($data,"`key`='{$key}' and `uid`='{$uid}'");

				$sql = "delete from `".TABLE_PREFIX."member_validate` where `uid`='{$uid}' and `status`=0";
		$this->DatabaseHandler->Query($sql);


		$data=array();
		$data['role_id']=$row['role_id'];

		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		$this->DatabaseHandler->Update($data,"uid={$row['uid']}");
		$this->Messager("验证成功，您现在可以正常体验本网站了",'?');

		$this->PromotionLogic->Register();
		$this->Messager("验证成功，您现在可以正常体验本网站了",'?');
	}


	
	function Register()
	{
		extract($this->Get);
		
		if ($this->Config['invite_enable']) {
			if(!$this->Code) {
								if ($this->Config['invite_by_admin']) {
					if (false === ($invite_admin_uids = cache('misc/invite-admin_uids',-1))) {
						$sql = "select `uid` from `".TABLE_PREFIX."members` where `role_type`='admin'";
						$query = $this->DatabaseHandler->Query($sql);
						$invite_admin_uids = array();
						while ($row = $query->GetRow())
						{
							$invite_admin_uids[$row['uid']] = $row['uid'];
						}

						cache($invite_admin_uids);
					}
					$invite_admin_uid = array_rand($invite_admin_uids);

					$sql = "select `uid`,`invitecode` from `".TABLE_PREFIX."members` where `uid`='{$invite_admin_uid}'";
					$query = $this->DatabaseHandler->Query($sql);
					$invite_admins = $query->GetRow();
					if($invite_admins && $invite_admins['invitecode']) {
						$this->Messager(null,"{$this->Config['wap_url']}/index.php?mod=member&code=".urlencode($invite_admins['uid']."|".$invite_admins['invitecode']));                        
					} else {
						cache('misc/invite-admin_uids',0);
					}
				}

				$this->Messager("非常抱歉，本站目前需要有邀请链接才能注册。",null);
			} else {
				$check_result = $this->_checkInvite($this->Code);
				if(!$check_result) {
					$this->Messager("对不起，您访问的邀请链接不正确或者因邀请数已满而失效，请重新与邀请人索取链接。",null);
				}
			}
			$sql = "select `uid`,`ucuid`,`username`,`nickname`,`face_url`,`face` from `".TABLE_PREFIX."members` where `uid`='{$check_result['uid']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$inviter_member = $query->GetRow();
			if($inviter_member) {
				$inviter_member['face'] = face_get($inviter_member);
			}


			if (MEMBER_ID > 0) {
				$this->Messager("您已经是注册用户，无需再注册！您可以将地址栏中的网址复制给好友，邀请其注册并关注你。",null);
			}
		}

		if(MEMBER_ID != 0 AND false == $this->IsAdmin)
		{
			$this->Messager('您已经是注册用户，无需再注册！',-1);
		}
		if($this->CookieHandler->GetVar('referer')=='')
		{
			$this->CookieHandler->Setvar('referer',referer());
		}
		
		
		$noemail = 0;
		if($this->_sms_register())
		{
			$sms_bind_num = $this->Post['sms_bind_num'] ? $this->Post['sms_bind_num'] : $this->Get['sms_bind_num'];
			if(!sms_is_phone($sms_bind_num))
			{
				$action = "index.php?mod=member&invite_code=".urlencode($this->Code);
				$this->Title = "请输入您的手机号码";
				include $this->TemplateHandler->Template('member_register_sms');
				return ;
			}
			else
			{
				if(($_user_info = _sms_client_user($sms_bind_num)) && $_user_info['uid'])
				{
					$this->Messager('此手机号已经绑定了其他的帐号');
				}
				
				if($_user_info['bind_key_time'] + 60 > time())
				{
					$this->Messager('60秒内仅发送一次，请稍候再试');
				}
				
				$bind_key = mt_rand(100000,999999);
				
				$sets = array(
					'user_im' => $sms_bind_num,
					'bind_key' => $bind_key,
					'bind_key_time' => time(),
				);
				
				sms_client_user_update($sets, $_user_info);
				
				
				$sms_msg = "您的验证码为 {$bind_key}";
				$sms_msg = array_iconv('UTF-8', $this->Config['charset'], $sms_msg);
				
				
				sms_send($sms_bind_num, $sms_msg, 0);
			}

			$noemail = ConfigHandler::get('sms', 'register_verify', 'noemail');
		}
		
		
		$action="index.php?mod=member&code=doregister&invite_code=".urlencode($this->Code);
		$this->Title="注册新用户";
		include $this->TemplateHandler->Template('member_register');
	}


	
	function DoRegister()
	{		
		$message = array();
		$timestamp = time();
		
		$noemail = 0;
		$sms_ckret = 0;
		if($this->_sms_register())
		{
						$sms_bind_num = $this->Post['sms_bind_num'];
			$sms_bind_key = $this->Post['sms_bind_key'];
			
			$sms_ckret = sms_check_bind_key($sms_bind_num, $sms_bind_key);
			if($sms_ckret)
			{
				$this->Messager($sms_ckret, -1);
			}

			$noemail = ConfigHandler::get('sms', 'register_verify', 'noemail');
			if($noemail)
			{
				$this->Post['email'] = $sms_bind_num . '@139.com';
			}
		}
				
		
		if ($this->Config['invite_enable'])
        {

			if(!$this->Code)
            {
				$this->Messager("非常抱歉，本站目前需要有好友邀请链接才能注册。<br><br>看看<a href=\"?mod=topic&code=top\">达人榜</a>中有没有你认识的人，让他给你发一个好友邀请。",null);
			}

			$check_result = $this->_checkInvite();
			if(!$check_result)
            {
				$this->Messager("对不起，您访问的邀请链接不正确或者因邀请数已满而失效，请重新与邀请人索取链接。",null);
			}
		}
		
		
		
        $this->Post = array_iconv('UTF-8', $this->Config['charset'], $this->Post);
		$username = $this->Post['username'];
		$password = $this->Post['password'];
		$email = $this->Post['email'];
		$nickname = $this->Post['nickname'];

		
		if(strlen($password) < 5) 
        {
			$this->Messager("密码过短，请设置至少5位",-1);
		}
        		
		
		Load::functions('member');
		$uid = jsg_member_register($username, $password, $email, $nickname);
		if($uid < 1)
		{
			$rets = array(
	        	'0' => '未知错误',
	        	'-1' => '用户名或者昵称 不合法',
	        	'-2' => '用户名或者昵称 不允许注册',
	        	'-3' => '用户名或者昵称 已经存在了',
	        	'-4' => 'Email 不合法',
	        	'-5' => 'Email 不允许注册',
	        	'-6' => 'Email 已经存在了',
	        );
	        
	        $this->Messager($rets[$uid], null);
		}
		
		
		$datas = array();	
		$datas['uid'] = $uid;	
		$datas['province'] = $this->Post['province']; 		$datas['city'] = $this->Post['city'];		if($this->_sms_register())
		{
			$datas['phone'] = $sms_bind_num;
		}
		
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		$this->DatabaseHandler->Update($datas);
		
		
				if($this->_sms_register())
		{
			$_sms_info = _sms_client_user($sms_bind_num);
			
			$username = $username ? $username : $this->Post['username'];
			$_sms_sets = array(
				'uid' => $uid,
				'username' => $username,
				'bind_key' => 0,
	            'bind_key_time' => 0,
	            'try_bind_times' => '+1',
	            'last_try_bind_time' => $timestamp,
			);
			
			sms_client_user_update($_sms_sets, $_sms_info);
		}
		
		
				
		
				$followgroup_ary = ConfigHandler::get('follow');
		if (empty($followgroup_ary)) {
			$followgroup_ary = get_def_follow_group();
		}
		if (!empty($followgroup_ary)) {
			foreach ($followgroup_ary as $value) {
				$insert_ary = array(
					'uid' => $uid,
					'group_name' => $value,
					'group_count' => 0,
				);
				DB::insert("group", $insert_ary);
			}
		}

				if ($this->Config['invite_enable']) {
			$u = $check_result['uid'];
			$c = $check_result['code'];
			if(0 < ($invite_id = $check_result['invite_id'])) {
				$sql = "select * from `".TABLE_PREFIX."invite` where `id`='{$invite_id}'";
				$query = $this->DatabaseHandler->Query($sql);
				$row = $query->GetRow();
				if ($row) {
					$sql = "update `".TABLE_PREFIX."invite` set `fuid`='{$uid}',`fusername`='{$username}' where `id`='{$row['id']}'";
					$this->DatabaseHandler->Query($sql);
				}
			} else {
				$sql = "insert into `".TABLE_PREFIX."invite` (`uid`,`code`,`dateline`,`fuid`,`fusername`,`femail`) values ('{$u}','{$c}','{$timestamp}','{$uid}','{$username}','{$email}')";
				$this->DatabaseHandler->Query($sql);
			}

			buddy_add($u,$uid);
			buddy_add($uid,$u);


						$sql = "update `".TABLE_PREFIX."members` set `invite_count`=`invite_count`+1 where `uid`='{$u}'";
			$this->DatabaseHandler->Query($sql);


						if ($this->Config['invite_enable'] > 1) {
				$code_invite_count = 0;
				$sql = "select count(*) as code_invite_count from `".TABLE_PREFIX."invite` where `uid`='{$u}' and `code`='{$c}'";
				$query = $this->DatabaseHandler->Query($sql);
				extract($query->GetRow());

				if ($code_invite_count > $this->Config['invite_enable']) {
					$this->_checkInvite($u,1);
				}
			} else {
				$this->_checkInvite($u,1);
			}

			if($this->Config['extcredits_enable'] && $u > 0)
			{
				
				update_credits_by_action('register',$u);
			}
		}

		
		$rets = jsg_member_login_set_status($uid);				
		

		
		$this->Messager(null,'index.php');
	}
	
	function _checkInvite($invite_code='',$reset=0)
	{
		$invite_code = $invite_code ? $invite_code : ($this->Post['invite_code'] ? $this->Post['invite_code'] : $this->Get['invite_code']);
		$invite_max = (int) $this->Config['invite_count_max'];
		$result = false;
		
		if($invite_code) {
			
			if(is_numeric($invite_code)) {
				$u = $invite_code;
			} else {
				$invite_code = str_replace(array('@','#',),'|',(string) $invite_code);
				list($u,$c) = explode('|',$invite_code);
			}
			
			if(($u = (int) $u) > 0) {
				$c_l = strlen(($c = trim($c)));
				
				if(32 == $c_l) {
					$sql = "select * from `".TABLE_PREFIX."invite` where `id`='{$u}'";
					$query = $this->DatabaseHandler->Query($sql);
					if (($row = $query->GetRow())) {
						$result = ($c==md5($row['id'].$row['code'].$row['dateline'].$row['femail']));
						$invite_id = $u;
						$u = $row['uid'];
						$c = $row['code'];		
					}										
				}
				 
				$sql = "select `uid`,`invite_count`,`invitecode`,`role_type` from `".TABLE_PREFIX."members` where `uid`='{$u}'";
				$query = $this->DatabaseHandler->Query($sql);
				if(($row = $query->GetRow())) {
					if($c && !$result && ('admin' == $row['role_type'] || $invite_max < 1 || $invite_max >= $row['invite_count'])) {
						$result = ($row['invitecode'] == $c);
						
					}
				}

				if ($reset && $row['uid']>0) {
					$sql = "update `".TABLE_PREFIX."members` set `invitecode`='".(substr(md5($row['uid'] . $row['invitecode'] . random(16) . time()),0,16))."' where `uid`='{$row['uid']}'";
					$result = $this->DatabaseHandler->Query($sql);
				}
			}
		}
		$result = ($result ? array('uid'=>$u,'code'=>$c,'invite_id'=>$invite_id) : false);

		return $result;
	}
	
	function _sms_register()
	{
		if(!isset($this->sms_register))
		{
			$this->sms_register = ($this->Config['sms_enable'] && $this->Config['sms_register_verify_enable'] && sms_init($this->Config));		
		}
		
		return $this->sms_register;
	}

}

?>
