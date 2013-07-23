<?php
/**
 * 文件名：vipintro.mod.php
 * 版本号：1.0
 * 最后修改时间：2011-4-12 
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 用户认证
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $TopicLogic;	

	function ModuleObject($config)
	{
		$this->MasterObject($config);

		Load::logic('topic');
		$this->TopicLogic = new TopicLogic($this);
		
		Load::lib('form');
		$this->FormHandler = new FormHandler();
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{	
			case 'delete':
				$this->Delete();
				break;
			case 'add':
				$this->Add();
				break;
			case 'modify':
				$this->Modify();
				break;
			case 'open_validate':
				$this->open_validate();
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
	
		$per_page_num = min(500,max((int) $_GET['per_page_num'],(int) $_GET['pn'],10));
		if($_GET['pn']) $pn = '&pn='.$_GET['pn'];
		$where_list = array();
		$query_link = 'admin.php?mod=vipintro'.$pn;
		
		$button_title = '提交';
		
		$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';

		$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."members`  where `validate` = 1 ";
		$query = $this->DatabaseHandler->Query($sql);
		extract($query->GetRow());
		
		$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',),'10 20 50 100 200,500');		
		
	
		$sql = "select  MB.nickname , MB.username ,  MB.uid , MF.* from `".TABLE_PREFIX."members` MB left join `".TABLE_PREFIX."memberfields` MF on MB.uid=MF.uid  where MB.validate = 1 order by MB.uid  {$page_arr['limit']}";
		$query = $this->DatabaseHandler->Query($sql);
		$uids = array();
		while ($row = $query->GetRow()) {
			$uids[$row['uid']] = $row['uid'];
		}
	
		$members = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`,`aboutme`");
	
		include $this->TemplateHandler->Template('admin/vipintro');
	}

  
	function Add()
	{   
	
		$nickname =  trim($this->Post['nickname']);
		$validate =  $this->Post['validate'];	
		$validate_remark = $this->Post['validate_remark'];
		
		if(empty($nickname) || empty($validate_remark))
		{
			$this->Messager("用户昵称或者认证备注为空",-1,3);	
		}
		
		$sql = " select `validate`,`uid`,`nickname` from `".TABLE_PREFIX."members`  where  `nickname` = '{$nickname}'";
		$query = $this->DatabaseHandler->Query($sql);
		$member = $query->GetRow();
		
		if(empty($member))
		{
		  $this->Messager("用户 {$nickname} 不存在",-1);
		}
		
				$sql = "update `".TABLE_PREFIX."members` set  `validate`='1'   where `uid` = '{$member['uid']}'";		
		$update = $this->DatabaseHandler->Query($sql);

				$sql = "update `".TABLE_PREFIX."memberfields` set   `validate_remark` = '{$validate_remark}' where `uid` = '{$member['uid']}'";		
		$update = $this->DatabaseHandler->Query($sql);
		
		
		$this->Messager("设置成功",'admin.php?mod=vipintro');
	}
	
	function Modify()
	{
	    $uid = (int) $this->Get['uid'];
	  	
	    $button_title = '编辑';
	    
	    $sql = " select `uid`,`nickname` from `".TABLE_PREFIX."members`  where  `uid` = '{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$member = $query->GetRow();

		$sql = " select `uid`,`validate_remark` from `".TABLE_PREFIX."memberfields`  where  `uid` = '{$uid}'";
		$query = $this->DatabaseHandler->Query($sql);
		$memberfields = $query->GetRow();
    	
		include $this->TemplateHandler->Template('admin/vipintro');
	}
	
		function open_validate()
	{	
		$uid = $this->Get['ids'];

		$sql = "select `uid`,`validate` from `".TABLE_PREFIX."members` where `uid` = {$uid}";
		$query = $this->DatabaseHandler->Query($sql);
		$row = $query->GetRow();
		
		$is_open = $row['validate'] ? 0 : 1;
		
		$sql = "update `".TABLE_PREFIX."members` set  `validate`='{$is_open}'  where `uid` = '{$uid}'";	
		
		$this->DatabaseHandler->Query($sql);
		
		$this->Messager("设置成功",'admin.php?mod=vipintro');
		 
	}
	
	

	
	

	
	

		
}

?>
