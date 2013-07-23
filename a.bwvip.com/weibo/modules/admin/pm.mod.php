<?php
/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博模块
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
	{
		
		$per_page_num = min(500,max((int) $_GET['per_page_num'],(int) $_GET['pn'],10));
		$where_list = array();
		$query_link = 'admin.php?mod=pm';
		
		$username = trim($this->Get['username']);
		$keyword  = trim($this->Get['keyword']);
		$tousername = trim($this->Get['tousername']);
		if($username)
		{
			$where_list['username'] = "`msgnickname`='{$username}'";
			$query_link .= "&msgnickname=".urlencode($username);	
		}
		if($tousername)
		{
			$sql = "select `uid`,`username`,`nickname` from `".TABLE_PREFIX."members` where `nickname`='{$tousername}' limit 0,1";
			$query = $this->DatabaseHandler->Query($sql);
			$members=$query->GetRow();

			$where_list['msgtoid'] = "`msgtoid`='{$members['uid']}'";
			$query_link .= "&nickname=".urlencode($members['nickname']);	
		}
		if($keyword)
		{
			$where_list['keyword'] = build_like_query('subject,message',$keyword);
			$query_link .= "&keyword=".urlencode($keyword);
		}
		
		$where = (empty($where_list)) ? null : ' WHERE '.implode(' AND ',$where_list).' ';

		$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."pms` {$where}";
		$result = mysql_query($sql); 
		$total_records = mysql_fetch_array($result);
		$total_record = $total_records[0];

		$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>'array',),'20 50 100 200,500');		

		$sql = " select * from `".TABLE_PREFIX."pms` {$where} order by `pmid` desc {$page_arr['limit']} ";
		$query = $this->DatabaseHandler->Query($sql);	

		$pm_list=array();
		$tomenberid = array();
		while($row=$query->GetRow())
		{	
			$row['dateline']=my_date_format($row['dateline'],"Y-m-d H:i");
						$row['user_id']=$folder!="inbox"?$row['msgtoid']:$row['msgfromid'];
			$row['style']=$row['new']!="0"?' class="style08"':'';
			$row['id']=$row['pmid'];
			
			$row['nickname'] = $row['msgnickname'] ? $row['msgnickname']:$row['msgfrom'];
			
			if('track' == $folder and $row['is_hi'])
			{
				$row['user_id'] = '';
				$row['user'] = '<span style="color:green">匿名会员</span>';
			}
			
			$tomenberid[] = $row['msgtoid'];
			$pm_list[]=$row;
			
		}
		if($tomenberid)
		{
				$sql = " select `uid`,`username`,`nickname` from `".TABLE_PREFIX."members` where `uid` in (".implode(",", $tomenberid).") ";
				$query = $this->DatabaseHandler->Query($sql);	
				$tonickname = array();
				while($row=$query->GetRow())
				{
					$tonickname[] = $row;
				}
		}
		include($this->TemplateHandler->Template('admin/pm'));
		
	}
	

	function Delete()
	{
		$ids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		
		for($i=0; $i < count($ids); $i++)
		{
				$pm_id 	= $ids[$i];
				$pmid.= ','.$pm_id;
		}
		
		$sql = "delete from `".TABLE_PREFIX."pms` where `pmid` in (0{$pmid})";
		$this->DatabaseHandler->Query($sql);		
		
		
		$this->Messager($return ? $return : "操作成功");
	}
		
}

?>
