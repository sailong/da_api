<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename app.mod.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:39 424808602 919910655 3841 $
 *******************************************************************/



 

if(!defined('IN_JISHIGOU')) {
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);	
		$this->initMemberHandler();
		
				if (MEMBER_ID < 1) {
			response_text("请先登录");	
		}
		
		Load::logic('vote');
		$this->VoteLogic = new VoteLogic();
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic();
		
		$this->Execute();
	}

	
		

	function Execute()
	{
		switch($this->Code)
		{
			case 'list_topic':
				$this->getTopicList();
				break;
			case 'list_fenlei':
				$this->getFeiLeiList();
				break;
			case 'list_event':
				$this->getEventList();
				break;
			default:
				$this->Main();
				break;
		}
		
		exit;
	}
	
	function Main()
	{
		exit;
	}
	
	
	function getTopicList()
	{	
		$item = trim($this->Post['item']);
		$item_id = intval(trim($this->Post['item_id']));
		
		if ($item_id < 1) {
						exit;
		}
		
				Load::functions('app');
		$ret = app_check($item, $item_id);
		if (!$ret) {
						exit;
		}
		
				$gets = array(
			'mod' => $item,
			'code' => $this->Post['oc'],					'vid' => $item_id,
		);
		$page_url = 'index.php?'.url_implode($gets);
		$where = '';
		if ($item == 'qun') {
			$where = " type !='reply' ";
		}
		$options = array(
			'where' => $where,
			'page' => true,
			'perpage' => 5,				'page_url' => $page_url,
		);
		$topic_info = app_get_topic_list($item, $item_id, $options);
		$topic_list = array();
		if (!empty($topic_info)) {
			$topic_list = $topic_info['list'];
			$page_arr['html'] = $topic_info['page']['html'];
			$no_from = true;
			if ($item == 'qun') {
				$allow_list_manage = true;
				$no_from = false;
				include(template('qun/topic_list_ajax'));
			} else {
				include(template('topic_list_ajax'));
			}
		}
		exit;
	}
	
	function getFeiLeiList(){
		Load::functions('app');
		$item = "fenlei";
		$item_id = intval(trim($this->Get['item_id']));
		
		if ($item_id < 1) {
						exit;
		}
				$gets = array(
			'mod' => "fenlei",
			'code' => "detail",
			'fid' => $this->Get['fid'],
			'id' => $item_id,
		);
		$page_url = 'index.php?'.url_implode($gets);
		$where = '';
		$options = array(
			'where' => $where,
			'page' => true,
			'perpage' => 5,				'page_url' => $page_url,
		);
		$topic_info = app_get_topic_list($item, $item_id, $options);
		$topic_list = array();
		if (!empty($topic_info)) {
			$topic_list = $topic_info['list'];
			$page_arr['html'] = $topic_info['page']['html'];
			$no_from = true;
			include(template('topic_list_ajax'));
		}
		exit;
	}
	
	function getEventList(){
		Load::functions('app');
		$item = "event";
		$item_id = intval(trim($this->Get['item_id']));
		
		if ($item_id < 1) {
						exit;
		}
				$gets = array(
			'mod' => $item,
			'code' => "detail",
			'id' => $item_id,
		);
		$page_url = 'index.php?'.url_implode($gets);
		$where = '';
		$options = array(
			'where' => $where,
			'page' => true,
			'perpage' => 5,				'page_url' => $page_url,
		);
		$topic_info = app_get_topic_list($item, $item_id, $options);
		$topic_list = array();
		if (!empty($topic_info)) {
			$topic_list = $topic_info['list'];
			$page_arr['html'] = $topic_info['page']['html'];
			$no_from = true;
			include(template('topic_list_ajax'));
		}
		exit;
	}
}
?>
