<?php
/**
 * 文件名：notice.mod.php
 * 版本号：1.0
 * 最后修改时间：2010-7-23 11:40
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 网站公告模块
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
		  case 'domodify':
				$this->DoModify();
				break;
			default:
				$this->Main();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}

	function Main()
	{$ButtonTitle = '添加';
		$sql = "select `id`,`title`,`dateline` from `".TABLE_PREFIX."notice` order by `id` desc";
	 	$query = $this->DatabaseHandler->Query($sql);
	
	 	$notice_list=array();
		while($row=$query->GetRow())
		{	
			$row['dateline'] = date('Y-m-d H:s:i',$row['dateline']);
			$notice_list[] = $row;	
		}
		
		include $this->TemplateHandler->Template('admin/notice');
	}
	


	function Add()
	{		
			$title   = $this->Post['title'];
			$content = $this->Post['content'];
			$timestamp = time();
	
			
			if(empty($title))
			{
				$this->Messager("请输入公告标题",-1);
			}			
			
			if(empty($content))
			{
				$this->Messager("请输入公告内容",-1);
			}
			
			$content = unfilterHtmlChars($content);
			
			
						if(($filter_msg = filter($content))) return $filter_msg;	
			
			
			$sql = "insert into `".TABLE_PREFIX."notice`(`title`,`content`,`dateline`) values ('{$title}','{$content}','{$timestamp}')";
			$this->DatabaseHandler->Query($sql);
			
			$this->Messager("添加成功",'admin.php?mod=notice');
	}
	
	
	function Modify()
	{	
		 $sql="SELECT * FROM ".TABLE_PREFIX.'notice'." WHERE id=".$this->Get['ids'];
		 $query = $this->DatabaseHandler->Query($sql);
		 $notice_info=$query->GetRow();
		
		 if($notice_info==false) 
		 {
		 		$this->Messager("您要编辑的信息已经不存在!");
		 }
  	  
		 $ButtonTitle = "编辑";
		 $action = "admin.php?mod=notice&code=domodify";
		 
		 $notice_id = $notice_info['id'];
		 $notice_title = $notice_info['title'];
		 $notice_content = $notice_info['content'];
		 
		 include $this->TemplateHandler->Template('admin/notice_info');
	}
	
	function DoModify()
	{	
		
		$title   = $this->Post['title'];
		$content = $this->Post['content'];
	
		if(empty($title))
		{
			$this->Messager("请输入公告标题",-1);
		}			
		
		if(empty($content))
		{
			$this->Messager("请输入公告内容",-1);
		}
				
				if(($filter_msg = filter($content))) 
		$this->Messager("{$filter_msg}");
		
		$content = unfilterHtmlChars($content);
		
		$dateline = time();
		
		$sql = "update `".TABLE_PREFIX."notice` set  `title`='{$title}' ,`content`='{$content}' ,`dateline` ='{$dateline}'  where `id`=".$this->Post['notice_id'];	
		$this->DatabaseHandler->Query($sql);
		
		$this->Messager("编辑成功",'admin.php?mod=notice');
		
	}

	function Delete()
	{
		$ids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		
		$sql = "delete from `".TABLE_PREFIX."notice` where `id` in (".implode(",",$ids).")";
		
		$this->DatabaseHandler->Query($sql);
				
		
		$this->Messager($return ? $return : "操作成功");
		
	}
		
}

?>
