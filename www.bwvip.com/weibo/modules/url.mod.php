<?php

/**
 * 外链跳转模块
 *
 * @author 狐狸<foxis@qq.com>
 * @package jishigou.net
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
		$load_file = array();
		switch ($this->Code) 
        {
            case 'iframe':
                $this->Iframe();
                break;

			default:
				$this->Main();
				break;
		}
	}
	
	function Main()
	{		
		if (!$this->Code) {
			$this->Messager("错误的请求",null);
		}

		$sql = "select * from `".TABLE_PREFIX."url` where `key`='{$this->Code}'";
		$query = $this->DatabaseHandler->Query($sql);
		$url_info = $query->GetRow();
		if (!$url_info) {
			$this->Messager("[错误请求]不存在的链接地址",null);
		}	
		
		$sql = "update `".TABLE_PREFIX."url` set `open_times`=`open_times`+1 where `id`='{$url_info['id']}'";	
		$this->DatabaseHandler->Query($sql);
		
				if($this->Config['url_status'] && $this->Get['tids'])
		{
            Load::logic('topic');
            $this->TopicLogic = new TopicLogic($this);
				  
			$topic_info = $this->TopicLogic->Get($this->Get['tids']);
			
			if ($topic_info && $topic_info['replys'] > 0) {
				$per_page_num = 10;
				$total_record = $topic_info['replys'];
				$_config = array(
					'return' => 'array',
					'extra' => 'onclick="replyList(this.title);return false;"',
					'var' => 'p',
				);
				$page_arr = page($total_record,$per_page_num,"index.php?mod=topic&code={$topic_info['tid']}",$_config);
				$tids = $this->TopicLogic->GetReplyIds($topic_info['tid']);
				$condition = "where `tid` in ('".implode("','",array_slice((array) $tids,$page_arr['offset'],$per_page_num))."') order by `dateline` limit {$per_page_num}";
				$reply_list = $this->TopicLogic->Get($condition);
			}
			
			include($this->TemplateHandler->Template('topic_redirect'));
		}
		else
		{
			$this->Messager(null,$url_info['url']);
		}		
	}
	
    function Iframe()
    {
        $url = ($this->Get['url'] ? $this->Get['url'] : $this->Post['url']);        
        if(!$url)
        {
            exit('url is empty');
        }        
        $url_info = get_url_key($url,$this->Request['title'],$this->Request['description']);
        if(!$url_info)
        {
            exit('url is invalid');
        }
        
        Load::logic('topic');
        $TopicLogic = new TopicLogic(); 
        
        $item = 'url';
        $item_id = $url_info['id'];
        $per_page_num = 20;
        $where = " where `item_id`='$item_id' and `item`='$item' ";
        
        
        $total_record = $this->DatabaseHandler->ResultFirst("select count(*) as `count` from ".TABLE_PREFIX."topic $where ");
        $page_arr = page($total_record,$pre_page_num,$page_link,array('return'=>'Array'));
                
               
        $topic_list = $TopicLogic->Get(" $where order by `tid` desc {$page_arr['limit']} ");        
        
        
        debug($topic_lsit,false);
        $this->item = $item;
        $this->item_id = $item_id;        
        include($this->TemplateHandler->Template('url_iframe'));
    }
}
?>
