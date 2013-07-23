<?php
/**
 * 文件名：reminded.mod.php
 * 版本号：1.0
 * 最后修改时间：2011年3月31日
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 新消息提醒模块
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

		$this->Execute();
	}

	function Execute()
	{
        ob_start();	
           
		switch($this->Code)
		{
            case 'show':
                $this->ShowReminded();
                break;		      

			default:
				$this->Main();
				break;
		}
        
        response_text(ob_get_clean());
	}

	function Main()
    {
        response_text('正在建设中……');
    }
    
    	function ShowReminded()
	{
        if((int) $this->Config['ajax_topic_time'] < 1)
        {
            exit;
        }
    
    	$uid = max(0,(float) $this->Post['uid']);
        if($uid < 1)
        {
            exit;
        }
    
        $time = time();
		
		$__my = $row = $this->DatabaseHandler->FetchFirst("select * from `".TABLE_PREFIX."members` where `uid`='{$uid}' limit 0,1");
        if(!$row)
        {
            exit;
        }

		$lastactivity = $row['lastactivity'];

		$is_uptime = $this->Post['is_uptime'];

				if($is_uptime == 1)
		{
			$this->DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `lastactivity`='{$time}' where `uid`='$uid'");
			
            echo '<success></success>';
			echo "<script language='Javascript'>";
			            echo "listTopic(0,0);";
			echo "</script>";
			exit;
		}

				$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' and `buddy_lastuptime`>'{$lastactivity}'";
		$query = $this->DatabaseHandler->Query($sql);
                
		$buddy_uids = array();
		while ($row = $query->GetRow())
		{
			$buddy_uids[] = $row['buddyid'];
		}

		$total_record = 0;
        
        if($buddy_uids)
        {
            $total_record = $this->DatabaseHandler->ResultFirst("select count(*) as `total_record` from `".TABLE_PREFIX."topic` where `uid` in ('".implode("','",$buddy_uids)."') and `type` != 'reply' and `dateline`>'{$lastactivity}'");
        }


		include($this->TemplateHandler->Template('ajax_reminded'));
	}
}

?>
