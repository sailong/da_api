<?php
/**
 * 文件名：pm.mod.php
 * 版本号：2.6.6
 * 最后修改时间：2011-6-8
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 私信模块
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $ShowConfig;

	var $CacheConfig;

	var $TopicLogic;

	var $ID = '';


	function ModuleObject($config)
	{
		$this->MasterObject($config);

		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);

		$this->CacheConfig = ConfigHandler::get('cache');

		$this->ShowConfig = ConfigHandler::get('show');

		$this->Execute();

	}

	
	function Execute()
	{
		ob_start();

	
		if('view' == $this->Code) {
			$this->View();
		} elseif ('pmdel' == $this->Code) {
			$this->PmDelete();
		} elseif ('dosend' == $this->Code) {
			$this->DoSend();
		} elseif ('pmsend' == $this->Code) {
			$this->PmSend();
		} elseif ('isnew' == $this->Code) {
			$this->IsNew();
		} else {
			$this->Main();
		}
		$body=ob_get_clean();

		$this->ShowBody($body);
	}

	function Main()
	{
		extract($this->Get);
		extract($this->Post);
		
		
		$member = $this->_member(MEMBER_ID);
		$topic_selected = 'pm';
		
				$per_page_num = 20;	
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&code={$this->Code}&folder=inbox" : "");	
		
		if($member['newpm'])
		{
						$sql = "update `".TABLE_PREFIX."members` set `newpm`=0 where `uid`='{$member['uid']}'";
			$this->DatabaseHandler->Query($sql);
			$this->MemberHandler->MemberFields['newpm'] = 0;
		}	
	
				if ($this->Get['new'] == 'weidu') {
			
			$pm_new = " and `new` = 1";
		} 
		
		if ($this->Get['new'] == 'yidu') {
			
			$pm_new = " and `new` = 0";
		} 
	
				$sql="SELECT  count(*) as `total_record` from ".TABLE_PREFIX.'pms'." WHERE msgtoid =".MEMBER_ID." $filter AND folder='inbox' AND delstatus!='2' {$pm_new} ORDER BY dateline DESC  ";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
	
	    		$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',));
	
		$filter=trim($this->Get['filter'])=='newpm'?"AND new>0":'';
		$sql="SELECT * from ".TABLE_PREFIX.'pms'." WHERE msgtoid =".MEMBER_ID." $filter AND folder='inbox' AND delstatus!='2' {$pm_new} ORDER BY dateline DESC  {$page_arr['limit']} ";
		
		$query = $this->DatabaseHandler->Query($sql);	
	
		$pm_list=array();
		while($row=$query->GetRow())
		{	
			
			$row['send_time']= wap_my_date_format($row['dateline']);
			$row['msgnickname'] = wap_iconv($row['msgnickname']);
			$row['message'] = wap_iconv($row['message']);
			$pm_list[]=$row;
		}
		
		$title = '我的私信';
		$this->Title = $title;
		include $this->TemplateHandler->Template('pm_list');

	}


		function View()
	{	 
		 $title = '回复私信';
		 
		 $pmid = (int) ($this->Post['pmid'] ? $this->Post['pmid'] : $this->Get['pmid']);
		 
		 		 $this->DatabaseHandler->Query("update `".TABLE_PREFIX."pms` set new=0 where `pmid`='{$pmid}' ");
		
		 		 $sql = " select * from `".TABLE_PREFIX."pms`  where  `pmid` = '{$pmid}'";
		 $query = $this->DatabaseHandler->Query($sql);
		 $pmlist = $query->GetRow();
		 
		 		 $pmlist['msgnickname'] = wap_iconv($pmlist['msgnickname']);
		 $pmlist['message'] = wap_iconv($pmlist['message']);
		 $pmlist['send_time'] = wap_my_date_format($pmlist['dateline']);
	
			
						if($pmlist['msgfromid'] != MEMBER_ID)
			{
				print_r($pmlist['msgtoid']);
				$sql = " select * from `".TABLE_PREFIX."pms`  where  `msgfromid` = '{$pmlist['msgtoid']}' and `msgtoid` = '".MEMBER_ID."' order by `dateline` desc limit 0,1";	 	
		 	 	$query = $this->DatabaseHandler->Query($sql);
		  	$mypmlist = $query->GetRow();
	
						 	 	$mypmlist['msgnickname'] = wap_iconv($mypmlist['msgnickname']);
		 	 	$mypmlist['message'] = wap_iconv($mypmlist['message']);
		  	$mypmlist['send_time'] = wap_my_date_format($mypmlist['dateline']);
			}
			
			$this->Title = $title;
			include($this->TemplateHandler->Template('pm_view'));
	}

	function PmSend()
	{
		
		$title = '发送私信';
	
		$nickname = (string) ($this->Post['nickname'] ? $this->Post['nickname'] : $this->Get['nickname']);
		 
		$this->Title = $title;
		include $this->TemplateHandler->Template('pm_send');	
	}
	
	
		function DoSend()
	{
	
		if(MEMBER_ID < 0)
		{
		  $this->Messager("请先登录在操作",'index.php?mod=login');
		}		
		
		
				if($this->MemberHandler->HasPermission($this->Module,$this->Code)==false)
		{
			$this->Messager($this->MemberHandler->GetError(),null);
		}
			
		$to_user_list=array();
		
		filter($this->Post['message'],'',false,true);
		
		
		if($this->Post['buddy_list']==false && $this->Post['to_user']=="")
		{
			$this->Messager("收件人不能为空",'index.php?&mod=pm&code=pmsend','','','','addpm');
		}
		if($this->Post['message']=='')
		{	
			$this->Messager("私信内容不能为空",'index.php?&mod=pm&code=pmsend&nickname='.$this->Post['to_user'],'','','','addpm');
		}

		if(trim($this->Post['to_user'])!='')
		{
			$to_user = wap_iconv($this->Post['to_user'],'utf-8',$this->Config['charset']);
			
			$in=$this->DatabaseHandler->BuildIn($to_user," `nickname`");
			
						$sql=" select `uid`,`username`,`nickname`,`notice_pm`,`email`,`newpm` from  ".TABLE_PREFIX.'members'." Where {$in} ";
			
			$query = $this->DatabaseHandler->Query($sql);
			while($row=$query->GetRow())
			{
				$to_user_list[$row['uid']]=$row;
			}
			
		}
		
		$to_user_list+=(array)$this->Post['buddy_list'];
	
		if($to_user_list==false)
		{
			$this->Messager("收件人不存在",'index.php?&mod=pm&code=pmsend','','','','addpm');
		}

		
				
		$message = wap_iconv($this->Post['message'],'utf-8',$this->Config['charset']);
		
		foreach($to_user_list as $to_user_id => $to_user_name)
		{
	
				$this->DatabaseHandler->SetTable(TABLE_PREFIX.'pms');
				$data=array(
												"msgfrom"				=>	MEMBER_NAME,
												"msgnickname"		=>	MEMBER_NICKNAME,
												"msgfromid" 		=> 	MEMBER_ID,  															"msgtoid"   		=> 	$to_user_id,															"subject"   		=> 	'',
												"message"   		=> 	$message,
												"new"						=>	'1',
												"dateline"			=>	time(),
										);
				
				$this->DatabaseHandler->Insert($data);
		}
		
		
		$this->Messager("私信发送成功",'index.php?mod=pm','','','','pms');
		
			
				$_tmps=array_keys($to_user_list);
		$to_user_id_list = array();
		foreach($_tmps as $_tmp) {
			$_tmp = (int) $_tmp;
			if($_tmp > 0) {
				$to_user_id_list[$_tmp] = $_tmp;
			}
		}
		$num=$this->Post["save_to_outbox"]?2:1;
		$this->UpdateNewMsgCount($num,$to_user_id_list);
	
	
		

		
	}
	
		function PmDelete()
	{
		
		$pmid = (int) ($this->Post['pmid'] ? $this->Post['pmid'] : $this->Get['pmid']);
		
		if ($pmid < 1) {
			$this->Messager('请指定一个您要删除的私信','index.php?mod=pm');
		}
		
				$sql = " select * from `".TABLE_PREFIX."pms`  where  `pmid` = '{$pmid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$pmlist = $query->GetRow();
		
		if($pmlist['msgtoid'] == MEMBER_ID)
		{
			$sql = "delete from `".TABLE_PREFIX."pms` where `pmid`='{$pmid}' and `msgtoid` =".MEMBER_ID;
	  	$this->DatabaseHandler->Query($sql);	
		}

		$this->Messager(NULL,'index.php?mod=pm');
	}
	
	
		function IsNew()
	{
				
		$pmid = (int) ($this->Post['pmid'] ? $this->Post['pmid'] : $this->Get['pmid']);
		
		$this->DatabaseHandler->Query("update `".TABLE_PREFIX."pms` set new=1 where `pmid`='{$pmid}' ");	
		
		$this->Messager(NULL,'index.php?mod=pm');
	}
	
	
		function _member($uid=0)
	{
		$member = array();
		if($uid < 1) {
			$mod_original = ($this->Post['mod_original'] ? $this->Post['mod_original'] : $this->Get['mod_original']);
			if($mod_original)
			{
				$mod_original = getSafeCode($mod_original);
				$condition = "where `username`='{$mod_original}' limit 1";
				$members = $this->_topicLogicGetMember($condition);
				if(is_array($members)) {
					reset($members);
					$member = current($members);
				}
			}
		}
		$uid = (int) ($uid ? $uid : MEMBER_ID);
		if($uid > 0 && !$member) {
			$member = $this->_topicLogicGetMember($uid);
		}
		if(!$member) {
			return false;
		}
		$uid = $member['uid'];

		if (!$member['follow_html'] && $uid!=MEMBER_ID) {
			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member['follow_html'] = wap_follow_html($member['uid'],$query->GetNumRows()>0);
		}


		return $member;
	}

		function UpdateNewMsgCount($num,$uids='')
	{
		if($uids=='')$uids=MEMBER_ID;
		
		$uids=$this->DatabaseHandler->BuildIn($uids,'uid');
		if(!$uids) return ;
		
		$strpos = strpos($num,'-');
		if($strpos!==0)
		{
			$num="+".$num;
		}
		
		$sql="
		UPDATE
			".TABLE_PREFIX.'members'."
		SET
			newpm=newpm $num
		WHERE
			$uids";
			
		$this->DatabaseHandler->Query($sql);
		
		$ret = $this->DatabaseHandler->AffectedRows();;

		if(0 === $strpos) 
		{
			$this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `newpm`=0 where $uids and `newpm`<0");
		}
		
		return $ret;
	}

}

?>
