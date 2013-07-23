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

		 				case 'setverify':
				$this->DoSetVerify();
				break;
						case 'check_modify_emali':
				$this->DoModifyEmali();
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
		$this->Messager("验证成功，您现在可以正常体验本网站了",'index.php?mod=member&code=step1');

		$this->PromotionLogic->Register();
		$this->Messager("验证成功，您现在可以正常体验本网站了",'index.php?mod=member&code=step1');
	}


	
	function Register()
	{
		extract($this->Get);

		$action="index.php?mod=member&code=doregister";
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
					
										if($this->Get['uid']){
						$invite_admin_uid = $this->Get['uid'];
					}
					
					$sql = "select `uid`,`invitecode` from `".TABLE_PREFIX."members` where `uid`='{$invite_admin_uid}'";
					$query = $this->DatabaseHandler->Query($sql);
					$invite_admins = $query->GetRow();
					if($invite_admins && $invite_admins['invitecode']) {
						$this->Messager(null,"{$this->Config['site_url']}/index.php?mod=member&code=".urlencode($invite_admins['uid']."|".$invite_admins['invitecode']));
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
			$action .= "&invite_code=" . urlencode($this->Code);


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
			$noemail = ConfigHandler::get('sms', 'register_verify', 'noemail');
		}
		
		Load::lib('form');
		$FormHandler = new FormHandler();
				$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."common_district where upid = 0 order by list");
		while ($rsdb = $query->GetRow()){
			$province[$rsdb['id']]['value']  = $rsdb['id'];
			$province[$rsdb['id']]['name']  = $rsdb['name'];
		}
		$province = $FormHandler->Select("province",$province,null,"onchange=\"changeProvince();\"");

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
		
		
		
		if ($this->Config['seccode_register']) {
			$seccode = $this->Post['seccode'];
			if (!ckseccode($seccode)) {
				$this->Messager("验证码输入错误",-1);
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
			
		
		
		$username = $this->Post['username'];
		$password = $this->Post['password'];
		$email = $this->Post['email'];
		$nickname = $this->Post['nickname'];
		
		
		if(strlen($password) < 5) 
        {
			$this->Messager("密码过短，请设置至少5位",-1);
		}
		if($password != $this->Post['password2']) 
		{
			$this->Messager("两次输入的密码不相同",-1);
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
		if($this->Post['province']){
			$datas['province'] = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['province']); 		}
		if($this->Post['city']){
			$datas['city'] = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['city']);		}
		if($this->Post['area']){
			$datas['area'] = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['area']);		}
		if($this->Post['street']){
			$datas['street'] = $this->DatabaseHandler->ResultFirst("select name from ".TABLE_PREFIX."common_district where id = ".(int) $this->Post['street']);		}
				
		if($this->_sms_register())
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

		
		$rets = jsg_member_login($username, $password);
		if($rets['uc_syn_html'])
		{
			$message[] = $rets['uc_syn_html'];
		}


		
		if($this->Config['reg_email_verify']!='1')
		{

			$this->Messager(null,'index.php?mod=member&code=step1',0);

			
		}
		else
		{
												
																	
						$this->Messager(null,'index.php?mod=member&code=setverify&ids='.$uid,0);
			
		}
	}
	
	
		function DoSetVerify()
	{	
		$uid = (int) $this->Get['ids'];

		$action = "index.php?mod=member&code=check_modify_emali";
		
	
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'role');
		$role=$this->DatabaseHandler->Select($this->Config['no_verify_email_role_id']);
		
		$sql = "SELECT `uid`,`ucuid`,`nickname`,`username`,`email` from `".TABLE_PREFIX."members` where `uid` = '{$uid}'  LIMIT 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$members = $query->GetRow();
		
				$emali_url = email_url($members['email']);
		
		
		include($this->TemplateHandler->Template('member_verify'));
	}
	
		function DoModifyEmali() 
	{
	
			$uid = $this->Post['uid'];

						$email = $this->Post['email'];
			
						$checktype = $this->Post['checktype'];
	
			if($email)
			{
				if($checktype == 'modify')
				{	
					$sql = "SELECT `uid`,`ucuid`,`nickname`,`username`,`email` from `".TABLE_PREFIX."members` where `uid` = '{$uid}'  LIMIT 0,1";
					$query = $this->DatabaseHandler->Query($sql);
					$members = $query->GetRow();
					
					
					Load::functions('member');
					$jsg_result = jsg_member_checkemail($email, $members['ucuid']);
					
					if($jsg_result < 1)
					{
						$rets = array(
				        	'0' => '未知错误',
				        	'-4' => 'Email 不合法',
				        	'-5' => 'Email 不允许注册',
				        	'-6' => 'Email 已经存在了',
				        );
				        
				        				        echo $rets[$jsg_result];
				        die;
					}
										$sql = "update `".TABLE_PREFIX."members` set  `email`='{$email}' where `uid`='{$uid}'";
					$this->DatabaseHandler->Query($sql);
				}
				
				
				$sys_config = ConfigHandler::get();
				
				if($sys_config['reg_email_verify'])
				{
					Load::functions('my');
					
					my_member_validate($uid,$email,(int) $sys_config['normal_default_role_id']);
					
					echo "邮件已重新发送成功";
					echo "<script language='Javascript'>";				
					echo "parent.document.getElementById('user_email').innerHTML='{$email}';";			
					echo "</script>";
				    die;
									}
				
			}
	}
	
	function Step1()
	{ 
		if (MEMBER_ID < 1) {
			$this->Messager('请先登录或者注册','index.php?mod=login');
		}
		
				$follow_type = 'recommend';
		$this->ShowConfig = ConfigHandler::get('show');
		
		$day = 7;
		$time = $day * 86400;
		$limit = (int) $this->ShowConfig['reg_follow']['user'];
		if($limit < 1) $limit = 20;
		
				Load::logic('topic');
		$TopicLogic = new TopicLogic($this);
		$regfollow = ConfigHandler::get('regfollow');
		
		if (!empty($regfollow)) {
			$count = count($regfollow);
			if ($count > $limit) {
				$keys = array_rand($regfollow, $limit);
				foreach ($keys as $k) {
					$uids[] = $regfollow[$k];	
				}
			} else {
				$uids = $regfollow;
			}
		} else {	
						if (false === ($uids = cache("misc/RTU-{$day}-{$limit}",900))) {
				$dateline = time() - $time;
				$sql = "SELECT DISTINCT(uid) AS uid, COUNT(tid) AS topics FROM `".TABLE_PREFIX."topic` WHERE dateline>=$dateline GROUP BY uid ORDER BY topics DESC LIMIT {$limit}";
				$query = $this->DatabaseHandler->Query($sql);
				$uids = array();
				while ($row = $query->GetRow())
				{
					$uids[$row['uid']] = $row['uid'];
				}
	
				cache($uids);
			}
		}

		if(!$uids) {
			$uids[] = 1;
		}

		$list = array();
		if($uids) {
			$_list = $TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`,`aboutme`");
			foreach ($uids as $uid) {
				if ($uid > 0 && isset($_list[$uid]) && $uid!=MEMBER_ID) {
					$list[$uid] = $_list[$uid];
				}
			}
		}
		
				$user_count = count($uids);

		$this->Title = "关注热门人物";
		include($this->TemplateHandler->Template('member_step1'));
	}
	function DoStep1()
	{	
		$uid = MEMBER_ID;
		
				$uids = (array) $this->Post['ids'];
		$tagid = (int) $this->Post['tag'] ? $this->Post['tag'] : $this->Get['tag'];
		
		if($uids || $tagid)
		{
						$default_regfollow = ConfigHandler::get('default_regfollow');

			if($default_regfollow)
			{
								$array_value = array_merge($default_regfollow,$uids);
			
								$array_value = array_unique($array_value);
				
			} else{
			
				$array_value = $uids;
			}
			
			if(MEMBER_ID > 0 && $array_value) {
				foreach ($array_value as $id) {
					$id = (int) $id;
					if($id > 0) {
						buddy_add($id);
					}
				}
			}
			
						if($tagid)
			{
		    	
			    Load::logic('other');
		    	$OtherLogic = new OtherLogic();
		    	$jsg_result = $OtherLogic->AddFavoriteTag($uid,$tagid);	
		    	
			}

			
					}
	$this->Messager(null,$this->Config['site_url'].'/index.php?mod=topic&code=myhome',0);
	}

	function Step2()
	{
		$this->Title = '发布第一条微博';
		include($this->TemplateHandler->Template('member_step2'));
	}
	function DoStep2()
	{
		if (($content = $this->Post['content'])) {
			Load::logic('topic');
			$TopicLogic = new TopicLogic($this);
			$return = $TopicLogic->Add($content);

			if ($return['tid'] < 1) {
				$this->Messager(is_string($return) ? $return : "未知错误",'?');
			}
		}

		$this->Messager("注册成功",'index.php?mod=topic&code=myhome',0);
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
