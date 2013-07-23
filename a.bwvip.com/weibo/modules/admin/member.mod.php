<?php
/**
 * 文件名：member.mod.php
 * 版本号：1.0
 * 最后修改时间：2006年8月16日 1:59:36
 * 作者：狐狸<foxis@qq.com>
 * 功能描述：用户管理模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{


	
	var $Code = array();


	
	var $ID = 0;

	
	var $IDS;

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);

		if(isset($this->Get['code']))
		{
			$this->Code = $this->Get['code'];
		}elseif(isset($this->Post['code']))
		{
			$this->Code = $this->Post['code'];
		}

		if(isset($this->Get['id']))
		{
			$this->ID = (int)$this->Get['id'];
		}elseif(isset($this->Post['id']))
		{
			$this->ID = (int)$this->Post['id'];
		}

		if(isset($this->Get['ids']))
		{
			$this->IDS = $this->Get['ids'];
		}elseif(isset($this->Post['ids']))
		{
			$this->IDS = $this->Post['ids'];
		}

		Load::lib('form');
		$this->FormHandler = new FormHandler();

		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			case 'list':
			$this->Main();
			break;

			case 'add':
			$this->Add();
			break;

			case 'doadd':
			$this->DoAdd();
			break;

			case 'delete':
			case 'dodelete':
			$this->DoDelete();
			break;

			case 'search':
			$this->search();
			break;
			case 'dosearch':
			$this->DoSearch();
			break;

			case 'modify':
			$this->Modify();
			break;
			case 'domodify':
			$this->DoModify();
			break;
			
						case 'follow_user_recommend':
			$this->Follow_User_Recommend();
			break;
			case 'do_follow_user_recommend':
			$this->Do_Follow_User_Recommend();
			break;
			case 'del_follow_user_recommend':
			$this->Del_Follow_User_Recommend();
			break;
			
			case 'export_all_user':
				$this->ExportAllUser();
				break;
			
			default:
			$this->Main();
			break;
		}
		$body = ob_get_clean();

		$this->ShowBody($body);

	}

	
	function Main()
	{
		$this->DoSearch();
	}

	
	function Add()
	{
		$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[] = array('name' => $row['name'], 'value' => $row['id']);
		}
		$role_select = $this->FormHandler->Select('role_id', $role_list, $this->Config['default_role_id']);
		$action = "admin.php?mod=member&code=doadd";
		$title = "添加";
		include $this->TemplateHandler->Template('admin/member_add');
	}

	
	function DoAdd()
	{
        $password_unhash = trim($this->Post['password']);

		$data = array();
		$data['username'] = trim($this->Post['username']);
		$data['nickname'] = trim($this->Post['username']);
		$data['password'] = md5($password_unhash);
		$data['email'] = trim($this->Post['email']);
		$data['role_id'] = (int)$this->Post['role_id'];

		if ($data['username']=='' or $password_unhash=='')
		{
			$this->Messager("用户名或密码不能为空");
		}
		if ($data['role_id']===0)
        {
			$this->Messager("角色编号未指定");
		}
				$sql="select * from ".TABLE_PREFIX."role where id=".$data['role_id'];
		$query = $this->DatabaseHandler->Query($sql);
		$role=$query->GetRow();
		if ($role==false)
        {
			$this->Messager("角色已经不存在");
		}
		$data['role_type']=$role['type'];
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		$is_exists = $this->DatabaseHandler->Select('', "username='{$data['username']}'");
		$nickname_exists = $this->DatabaseHandler->Select('', "nickname='{$data['username']}'");
		if($is_exists != false)
		{
			$this->Messager("用户名　{$data['username']}　已经被注册");
		}
		if($nickname_exists != false)
		{
			$this->Messager("昵称 {$data['nickname']}　已经被注册");
		}

        if(true === UCENTER)
        {
            			include_once(ROOT_PATH . 'uc_client/client.php');

            $uc_result = uc_user_register($data['username'],$password_unhash,$data['email']);

            if($uc_result < 0)
			{
				if($uc_result > -4) $this->Messager("您输入的用户名无效或已被他人使用");
				if($uc_result > -7) $this->Messager("您输入的Email地址无效或已被他人使用");

				$this->Messager("新用户注册失败");
			}

			$data['ucuid'] = $uc_result;        }

		$result = $this->DatabaseHandler->Insert($data);
		if($result != false)
		{
			$this->Messager("添加成功", 'admin.php?mod=member');
		}
		else
		{
			$this->Messager("添加失败");
		}
	}

	
	function Search()
	{
		$action = "admin.php?mod=member&code=dosearch";
				$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[] = $row;
		}
		$role_count = count($role_list) + 1;

                $credit_list = array();
        if($this->Config['extcredits_enable'])
        {
            $credit_list[] = array('name' => "lower[credits]", 'describe' => "总积分 低于");
			$credit_list[] = array('name' => "higher[credits]", 'describe' => "总积分 高于");

    		foreach($this->Config['credits']['ext'] as $key => $val)
    		{
    			$credit_list[] = array('name' => "lower[{$key}]", 'describe' => "{$val[name]} 低于");
				$credit_list[] = array('name' => "higher[{$key}]", 'describe' => "{$val[name]} 高于");
    		}
        }


		include $this->TemplateHandler->Template('admin/member_search');
	}

	
	function DoSearch()
	{
		extract($this->Get);

		$where_list = array();
		if($nickname != '')
		{
						$where_list[] = build_like_query('nickname', $nickname);
		}
		if($username != '')
		{
						$where_list[] = build_like_query('username', $username);
		}
		if($email != '')
		{
						$where_list[] = build_like_query('email', $email);
		}
		if($regip != '')
		{
			$where_list['regip'] = "regip like '{$regip}%'";
		}
		if($lastip != '')
		{
			$where_list['lastip'] = "lastip like '{$lastip}%'";
		}
		if(is_string($role_ids)==false)
		{
			if($role_id[0] != 'all' and is_array($role_id) and count($role_id) > 0)
			{
				$where_list['role_id'] = $this->DatabaseHandler->BuildIn($role_id, 'role_id');
				$_GET['role_ids']=implode(",",$role_id);
			}
			if($role_id[0]=='all')
			{
				unset($where_list['role_id']);
			}
		}
		else
		{
			$where_list['role_id'] ="role_id in($role_ids)";
		}

		if(is_array($lower))
		{
			foreach($lower as $field => $val)
			{
				if($val != '')
				{
					$where_list[$field . '_lower'] = "{$field}<=" . (int)$val;
				}
			}
		}

		if(is_array($higher))
		{
			foreach($higher as $field => $val)
			{
				if($val != '')
				{
					$where_list[$field . '_higher'] = "{$field}>=" . (int)$val;
				}
			}
		}

				$sql = "
		 SELECT
			 id,name,`type`
		 FROM
			 " . TABLE_PREFIX.'role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[$row['id']] = $row;
		}
		$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';
		
		
				$sql = "
		  SELECT
			 count(1) total
		  FROM
			  " . TABLE_PREFIX.'members' . "
		  $where";
		
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());

		$page_num=20;
		$p=max($p,1);
		$offset=($p-1)*$page_num;
		$pages=page($total,$page_num,'',array('var'=>'p'));
		$sql = "
		  SELECT
			  *
		  FROM
			  " . TABLE_PREFIX.'members' . "
		  $where
		  LIMIT $offset,$page_num";
		 
		$query = $this->DatabaseHandler->Query($sql);
        $uids = array();
		while($row = $query->GetRow())
		{
            $uids[$row['uid']] = $row['uid'];		      
                      
			$role = $role_list[$row['role_id']];
			$member_fields = $memberfields[$row['uid']];
			if($role != false)
			{
				if($role['is_system'] == 1)
				{
					$row['role_name'] = "<B>{$role['name']}</B>";
				}
				else
				{
					$row['role_name'] = $role['name'];
				}
			}
			if($member_fields != false)
			{
				$row['validate_remark'] = $member_fields['validate_remark'];
			}
			$member_list[] = $row;
		}
        
        
        		$sql = "
		  SELECT
			 `uid`,`nickname`,`validate_remark`
		  FROM
			  " . TABLE_PREFIX.'memberfields' . "
		  WHERE
              `uid` in ('".implode("','",$uids)."') ";
		$query = $this->DatabaseHandler->Query($sql);
		$memberfields = array();
		while ($row = $query->GetRow()) 
        {
			$memberfields[$row['uid']] = $row;
		}
        
		
		$action = 'admin.php?mod=member&code=delete';
		include $this->TemplateHandler->Template('admin/member_search_list');
	}

	
	function DoDelete()
	{
		$this->IDS = (array) ($this->IDS ? $this->IDS : $this->ID);
		$ids = array();
		foreach ($this->IDS as $v) 
		{
			$v = is_numeric($v) ? $v : 0;
			if($v > 0) $ids[$v] = $v;
		}
		if (!$ids) 
		{
			$this->Messager("请先指定一个要删除的用户ID",null);
		}
		
		
				if(true === UCENTER)
		{
			include_once(ROOT_PATH . 'uc_client/client.php');
		}
		
		
		$member_ids = array();
		$admin_list = array();
		
		
		$query = $this->DatabaseHandler->Query("select * from `".TABLE_PREFIX."members` where `uid` in('".implode("','",$ids)."')");
		while (false != ($row = $query->GetRow()))
		{
			if(1==$row['uid'] || $row['role_type']=='admin') 
			{
				$admin_list[$row['uid']] = $row['username'];
			}
			else 
			{
				$member_ids[$row['uid']] = $row['uid'];
				
								if(true === UCENTER && $row['ucuid'] > 0) 
				{
					uc_user_delete($row['ucuid']);
				}
			}
		}
		$member_ids_count = count($member_ids);
		
				if(isset($member_ids[1]))
		{
			unset($member_ids[1]);
		}


		if (0 < $member_ids_count)
        {
        	$member_ids_in = "'".implode("','",$member_ids)."'";
        	
        		        $need_update_uids = array();
			$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid` in({$member_ids_in})";
			$query = $this->DatabaseHandler->Query($sql);
			while (false != ($row = $query->GetRow()))
			{
				$need_update_uids[$row['buddyid']] = $row['buddyid'];
			}	
			$sql = "select `uid` from `".TABLE_PREFIX."buddys` where `buddyid` in({$member_ids_in})";
			$query = $this->DatabaseHandler->Query($sql);
			while (false != ($row = $query->GetRow()))
			{
				$need_update_uids[$row['uid']] = $row['uid'];
			}
        	
						
						Load::logic('topic');
			$TopicLogic = new TopicLogic($this);
			$TopicLogic->Delete("where `uid` in({$member_ids_in}) limit 999999999 ");
			
			
			$tbs = array(
				'blacklist' => array('uid', 'touid'),
				'buddys' => array('uid', 'buddyid'),
				'credits_log' => 'uid',
				'credits_rule_log' => 'uid',
				'cron' => 'touid',
												'group' => 'uid',
				'groupfields' => 'uid',
				'imjiqiren_client_user' => 'uid',
				'invite' => array('uid', 'fuid'),
				'log' => 'uid',
				'member_validate' => 'uid',
				'memberfields' => 'uid',
				'members' => 'uid',
				'my_tag' => 'user_id',
				'my_topic_tag' => 'user_id',
				'pms' => array('msgfromid', 'msgtoid'),
				'qqwb_bind_info' => 'uid',
				'report' => 'uid',
				'schedule' => 'uid',
				'sessions' => 'uid',
				'sms_client_user' => 'uid',
				'sms_receive_log' => 'uid',
				'sms_send_log' => 'uid',
				'tag_favorite' => 'uid',
				'task_log' => 'uid',
				'topic_favorite' => 'uid',
				'topic_longtext' => 'uid',
				'topic_mention' => 'uid',
				'user_medal' => 'uid',
				'user_tag_fields' => 'uid',
				'wall' => 'uid',
				'xwb_bind_info' => 'uid',
			);
			foreach($tbs as $k=>$vs)
			{
				$vs = (array) $vs;
				
				foreach($vs as $v)
				{
					$this->DatabaseHandler->Query("delete from `".TABLE_PREFIX."{$k}` where `{$v}` in ({$member_ids_in})", "SKIP_ERROR");
				}
			}
			
			

			            if($need_update_uids)
            {
                foreach($need_update_uids as $_uid)
                {
                    update_my_fans_follow_count($_uid);
                }
            }
		}

		$msg = '';
		$msg .= "成功删除<b>{$member_ids_count}</b>位会员";
		if($admin_list) 
		{
			$msg .= "，其中<b>".implode(' , ',$admin_list)."</b>是管理员，不能直接删除";
		}
		$this->Messager($msg);
	}

	
	function Modify()
	{
		$this->Title="编辑用户";
		$action="admin.php?mod=member&code=domodify";
				$sql="
		 SELECT
			 *
		 FROM
			 ".TABLE_PREFIX.'members'." M LEFT JOIN ".TABLE_PREFIX.'memberfields'." MF ON(M.uid=MF.uid)
		 WHERE
			 M.uid={$this->ID}";
		$query = $this->DatabaseHandler->Query($sql);
		$member_info=$query->GetRow();

		if($member_info==false)
		{
			$this->Messager("用户已经不存在");
		}
		extract($member_info);

		$sql = "select `nickname`,`username` from `".TABLE_PREFIX."members` where `username` = '{$member_info['username']}' limit 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$nicknames = $query->GetRow();
		$nickname = $nicknames['nickname'];

				$sql = "
		 SELECT
			 id,name
		 FROM
			 " . TABLE_PREFIX.'role' . "
		 WHERE
			 id!=1";
		$query = $this->DatabaseHandler->Query($sql);
		while($row = $query->GetRow())
		{
			$role_list[$row['id']] = array('name' => $row['name'], 'value' => $row['id']);
		}

		$role_select = $this->FormHandler->Select('role_id', $role_list,$role_id);

		$role_name = $role_list[$role_id]['name'];
		$gender_radio=$this->FormHandler->Radio('gender',array(
		array('name'=>"男",'value'=>'1'),
		array('name'=>"女",'value'=>'2'),
		array('name'=>"保密",'value'=>'0'),
		),$gender);
		list($year,$month,$day)=explode('-',$bday);
		$year_select=$this->FormHandler->NumSelect('year','1920','2006',$year!='0000'?$year:1980);
		$month_select=$this->FormHandler->NumSelect('month','1','12',$month);
		$day_select=$this->FormHandler->NumSelect('day','1','31',$day);
		$validate_radio = $this->FormHandler->YesNoRadio('validate',$member_info['validate']);
		$_options = array(
			'0' => array(
				'name' => '请选择',
				'value' => '0',
			),
			'身份证' => array(
				'name' => '身份证',
				'value' => '身份证',
			),
			'学生证' => array(
				'name' => '学生证',
				'value' => '学生证',
			),
			'军官证' => array(
				'name' => '军官证',
				'value' => '军官证',
			),
			'护照' => array(
				'name' => '护照',
				'value' => '护照',
			),
			'其他' => array(
				'name' => '其他',
				'value' => '其他',
			),
		);
		$validate_card_type_select = $this->FormHandler->Select('validate_card_type',$_options,$member_info['validate_card_type']);


		$uid = $this->ID;

		include $this->TemplateHandler->Template('admin/member_info');
	}

	 
	function DoModify()
	{
        $uid = (int) ($this->Post['uid']);
        if($uid < 1)
        {
            $this->Messager("请指定一个正确的UID");
        }

        $query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."members where `uid`='{$uid}'");
        $member_info = $query->GetRow();
        if(!$member_info)
        {
            $this->Messager("您要编辑的用户已经不存在了");
        }


        extract($this->Post);


		if($password=='')
		{
			unset($this->Post['password']);
		}
		else
		{
            $this->Post['password_unhash'] = $password;

			$this->Post['password']=md5($password);
		}


		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		if($old_username!=$username)
		{
			$is_exists=$this->DatabaseHandler->Select('',"username='$username'");
			if($is_exists || ($this->DatabaseHandler->Select('',"nickname='$username'")))
			{
				$this->Messager("用户名{$username}已经存在");
			}
		}

        if($nickname!=$member_info['nickname'])
        {
            $is_exists=$this->DatabaseHandler->Select('',"nickname='$nickname'");
			if($is_exists || ($this->DatabaseHandler->Select('',"username='$nickname'")))
			{
				$this->Messager("姓名/昵称{$nickname}已经存在");
			}
        }

		if ((int)$this->Post['role_id']!=0)
		{
			$this->DatabaseHandler->SetTable(TABLE_PREFIX.'role');
			$role=$this->DatabaseHandler->Select((int)$this->Post['role_id']);

			if($role!=false)
			{
				$this->Post['role_type']=$role['type'];
			}
			else {
				$this->messager("角色已经不存在");
			}
		}
		elseif($this->ID > 1)
		{
			$this->messager("角色必须选择");
		}
		if (1==$this->ID) 
		{
			unset($this->Post['role_id']);
			$this->Post['role_type'] = 'admin';
		}


		$condition = " `uid` = '$uid' ";
		$this->Post['bday']=$year.'-'.$month.'-'.$day;
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'members');
		$table1=$this->DatabaseHandler->Update($this->Post,$condition);

		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'memberfields',$condition);
		$table2=$this->DatabaseHandler->Replace($this->Post);

		if($table1 !==false)
		{
			if ($this->Config['extcredits_enable'] && $this->Post['validate'] && $this->Post['uid']>0)
			{
				
				update_credits_by_action('vip',$this->Post['uid']);
			}
			
			
			Load::logic('credits');
			$CreditsLogic = new CreditsLogic();			
			$CreditsLogic->CountCredits($this->Post['uid']);
						

                        if($this->Post['password_unhash'] && true === UCENTER)
            {
                include(ROOT_PATH . 'uc_client/client.php');

                uc_user_edit($username,'',$this->Post['password_unhash'],'',1);
            }

			$this->Messager("编辑成功");
		}
		else
		{
			$this->Messager("编辑失败");
		}
	}
	
	
	
	
	
	function Follow_User_Recommend()
	{	
		$this->Title="设置关注用户";
		
		$button_title = '添加';
		
		$action="admin.php?mod=member&code=do_follow_user_recommend";
		
		
		$per_page_num = min(500,max((int) $_GET['per_page_num'],(int) $_GET['pn'],10));
		
		if($_GET['pn']) $pn = '&pn='.$_GET['pn'];
		
	
		$query_link = 'admin.php?mod=member&code=follow_user_recommend'.$pn;
	
				$_follow_info = ConfigHandler::get('follow_user_recommend');
		
		
		if(!$this->Get['type'] || $this->Get['type'] == 'recommend'){	
			$type = 'recommend';
			$uids = explode(',',$_follow_info['recommend_uid']);
			$where = " `uid`  in ('".implode("','",$uids)."')  ";	
		} else{
			$type = 'default';
			$uids = explode(',',$_follow_info['default_uid']);
			$where = " `uid`  in ('".implode("','",$uids)."')  ";
		}
		
		$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."members`  where {$where} ";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
	
		$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',),'10 20 50 100 200,500');		
		
	
				
		$sql = " select `uid`,`nickname`,`username` from `".TABLE_PREFIX."members` where  {$where} order by `uid` {$page_arr['limit']} ";
		$query = $this->DatabaseHandler->Query($sql);
		$members = array();
		while ($row = $query->GetRow()) {
			$members[] = $row;
		}
		
		include $this->TemplateHandler->Template('admin/follow_user_recommend');
	}
	
	function Do_Follow_User_Recommend() 
	{
		
		$this->Title="设置关注用户";
		
		$nickname = trim($this->Post['nickname']);
		$follow_type = $this->Post['follow_type'];
		
		
				$sql = " select `uid` from `".TABLE_PREFIX."members`  where  `nickname` = '{$nickname}'";
		$query = $this->DatabaseHandler->Query($sql);
		$member = $query->GetRow();		
		
		if(empty($member))
		{
		  $this->Messager("用户 {$nickname} 不存在",-1);
		}
		
				if($follow_type == 'recommend'){	
			$recommend_uids = $member['uid'];
		} else {
			$default_uids = $member['uid'];
		}
		
				$_follow_info = ConfigHandler::get('follow_user_recommend');
		
				$follow_uid_recommend = array_unique(explode(',',$_follow_info['recommend_uid'].','.$recommend_uids));
		$follow_uid_recommend = array_filter($follow_uid_recommend);

				$follow_uid_default = array_unique(explode(',',$_follow_info['default_uid'].','.$default_uids));
		$follow_uid_default = array_filter($follow_uid_default);
	
						$set['recommend_uid']	=	implode(',',$follow_uid_recommend);
		
				$set['default_uid']		=	implode(',',$follow_uid_default);
		
		$set = jstripslashes($set);
		ConfigHandler::set('follow_user_recommend',$set);
		
		
		$this->Messager("编辑成功");
	
	}
	
		function Del_Follow_User_Recommend()
	{   
				$type = $this->Post['type'];
	
		$ids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
	
		
				$_follow_info = ConfigHandler::get('follow_user_recommend');
		
		
		if($type == 'recommend')
		{
			$recommend_uids = $_follow_info['recommend_uid'].',';

			for ($i = 0; $i < count($ids); $i++) 
			{	
				$recommend_uids = str_replace($ids[$i].',','',$recommend_uids);
			}

		}
		
		if($type == 'default')
		{
			$default_uids = $_follow_info['default_uid'].',';

			for ($i = 0; $i < count($ids); $i++) 
			{	
				$default_uids = str_replace($ids[$i].',','',$default_uids);
			}
			
		}
	
				$follow_uid_recommend = explode(',',$recommend_uids ? $recommend_uids : $_follow_info['recommend_uid']);		
		$follow_uid_recommend = array_filter($follow_uid_recommend);
		
		
				$follow_uid_default = array_unique(explode(',',$default_uids ? $default_uids : $_follow_info['default_uid']));
		$follow_uid_default = array_filter($follow_uid_default);

				
				$set['recommend_uid']	=	implode(',',$follow_uid_recommend);
		
				$set['default_uid']		=	implode(',',$follow_uid_default);
		
		$set = jstripslashes($set);
		ConfigHandler::set('follow_user_recommend',$set);
		
		
		$this->Messager("取消成功");
	}
	
		
	function ExportAllUser()
	{
		$query = $this->DatabaseHandler->Query("select M.`uid`, M.`username`, M.`nickname`, M.`email`, M.`phone`, M.`gender`, M.`credits`, M.`province`, M.`city`, M.`regdate`, M.`regip`, M.`lastip`, M.`aboutme`, M.`validate`, MF.`validate_remark`, MF.`validate_true_name`, MF.`validate_card_type`, MF.`validate_card_id` from ".TABLE_PREFIX."members M left join ".TABLE_PREFIX."memberfields MF on MF.`uid`=M.`uid` ");
		$list = array();
		$list[] = array('用户ID', '用户名', '用户昵称', 'Email 邮箱', '手机号码', '性别', '用户积分', '省份', '城市', '注册时间', '注册IP', '最后登录IP', '一句话介绍', 'V身份认证', 'V认证备注', '真实姓名', '证件类型', '证件号码');
		$genders = array('1'=>'男', '2'=>'女');
		while(false != ($row = $query->GetRow()))
		{
			$row['regdate'] = my_date_format($row['regdate']);
			$row['gender'] = isset($genders[$row['gender']]) ? $genders[$row['gender']] : '未知';
			$row['validate'] = $row['validate'] ? "是" : "否";
			
			$list[] = $row;
		}

		
		$this->_excel($list, "all-user-".date("YmdH"));
		
	}
	
	function _excel($list, $filename = '')
	{
		if(!$filename)
		{
			$filename = date('YmdHis');
		}
		
		
		Load::lib('php-excel');
		$XLS = new Excel_XML($this->Config['charset']);	
				$XLS->addArray($list);
		$XLS->generateXML($filename);
	}
	
	
}

?>