<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename vote.mod.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:39 1031444599 1133493463 5495 $
 *******************************************************************/




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

		Load::logic('vote');
		$this->VoteLogic = new VoteLogic($this);
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{	
			case 'delete':
				$this->delete();
				break;			
			case 'edit':
				$this->edit();
				break;
		  	case 'doedit':
				$this->doedit();
				break;
		  	case 'batch':
		  		$this->batch();
		  		break;
		  	case 'setting':
		  		$this->setting();
		  		break;
		  	case 'dosetting':
		  		$this->dosetting();
		  		break;
			default:
				$this->Code = 'index';
				$this->index();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}
	
	function index()
	{
		$per_page_num = min(500,max((int) $_GET['per_page_num'],(int) $_GET['pn'],20));
		$gets = array(
			'mod' => 'vote',
			'pn' => $this->Get['pn'],
			'vid' => $this->Get['vid'],
			'subject' => $this->Get['subject'],
		);
		$page_url = 'admin.php?'.url_implode($gets);
		
		$where_sql = " 1 ";
		
				$vid = $this->Get['vid'];
		if (!empty($vid)) {
			if (strpos($vid, ',') !== false) {
				$vids = explode(',', $vid);
				foreach ($vids as $key => $val) {
					$vids[$key] = intval($val);
				}
				$where_sql .= " AND vid IN(".jimplode($vids).") ";
			} else {
				$where_sql .= " AND vid=".intval($vid)." "; 
			}
		}
		
				$subject = $this->Get['subject'];
		if (!empty($subject)) {
			$subject_sql = addcslashes($subject, '_%');;
			$where_sql .= " AND subject like('%{$subject_sql}%') ";
			$subject = jstripslashes($subject);
		}
		
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table("vote")." WHERE {$where_sql}");
		$list = array();
		if ($count) {
			$page_arr = page($count,$per_page_num,$page_url,array('return'=>'array',));
			$query = DB::query("SELECT * 
								FROM ".DB::table("vote")." 
								WHERE {$where_sql} 
								ORDER BY dateline DESC 
								{$page_arr['limit']}");
			while ($value = DB::fetch($query)) {
				if ($value['recd']) {
					$value['recd_checked']  = 'checked="checked"';
				}
				$list[] = $value;
			}
		}
		include template('admin/vote');
	}
	
		function batch()
	{
		$del_ids = $this->Post['del_ids'];
		$recd_ids = $this->Post['recd_ids'];
		$vids = $this->Post['vids'];
		if (!empty($del_ids)) {
			$this->VoteLogic->delete($del_ids);
		}

		if (!empty($vids)) { 
			foreach ($vids as $vid) {
				$data = array(
					'vid' => $vid,
					'recd' => 0,
				);
				if (isset($recd_ids[$vid])) {
					$data['recd'] = 1;
				}
				$this->VoteLogic->update_recd($data);
			}
		}
		$this->Messager("操作成功了");
	}
	
	
	function delete()
	{
		$ids = (array) ($this->Post['ids'] ? $this->Post['ids'] : $this->Get['ids']);
		if(!$ids) {
			$this->Messager("请指定要删除的对象");
		}
		$return = $this->VoteLogic->delete($ids);
		if (empty($return)) {
			$this->Messager("操作失败");
		}
		$this->Messager("操作成功");
	}
	
		function edit()
	{
		$vid = empty($this->Get['vid']) ? 0 : intval($this->Get['vid']);
		$vote = $this->VoteLogic->id2voteinfo($vid);
		if (empty($vote)) {
			$this->Messager("当前投票不存在");
		}
		$range = range(0, 19);
		$opt_info = $this->VoteLogic->get_option_by_vid($vid);
		$options = $opt_info['option'];
		$checked = array();
		$checked['is_view'][$vote['is_view']] = 'checked="checked"';
		$checked['recd']= $vote['recd'] ? 'checked="checked"' : '';
		$selected[$vote['maxchoice']] = 'selected="selected"';
		$date = my_date_format($vote['expiration'], 'Y-m-d');
		$hour_select = mk_time_select('hour', my_date_format($vote['expiration'], 'H'));
		$min_select = mk_time_select('min', my_date_format($vote['expiration'], 'i'));
		include template('admin/vote_edit');
	}
	
	function doedit()
	{
		$vid = empty($this->Post['vid']) ? 0 : intval($this->Post['vid']);
		$vote = $this->VoteLogic->id2voteinfo($vid);
		if (empty($vote)) {
			$this->Messager("当前投票不存在");
		}
		
		$post = &$this->Post;
		$params = array(
			'no_chk_option' => true,
		);
		$ret = $this->VoteLogic->chk_post($post, 'modify', $params);
		$post['newoption'] = array();
		if ($ret == 1) {
			$this->VoteLogic->modify($post);
			$old_options = $this->Post['old_option'];
			$new_options = $this->Post['option'];
			$this->VoteLogic->update_options($vid, $old_options, $new_options);
			$this->Messager("修改投票成功了");
		} else if ($ret == -1) {
			$this->Messager("投票主题字符串长度不能小于2");
		} else if ($ret == -2) {
			$this->Messager("只有一个选项不允许发布");
		} else if ($ret == -3) {
			$this->Messager("截止日期不能小于当期日期");
		} else {
			$this->Messager("未知错误");
		}
	}
	
	function setting()
	{
		$config = ConfigHandler::get('vote');
		$checked = array();
		$checked['vote_open'][$config['vote_open']] = 'CHECKED';
		include template('admin/vote_setting');
	}
	
	function dosetting()
	{
		$config = $this->Post['config'];
		ConfigHandler::set('vote', $config);
		$this->Messager('操作成功了');
	}
}
?>
