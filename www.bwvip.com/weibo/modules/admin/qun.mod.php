<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename qun.mod.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:41 112615356 946079938 17098 $
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

		Load::logic('qun');
		$this->QunLogic = new QunLogic($this);
		
		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{	
			case 'setting':
				$this->setting();
				break;
			case 'dosetting':
				$this->dosetting();
				break;					
			case 'category':
				$this->category();
				break;
			case 'docategory':
				$this->docategory();
				break;
			case 'delcat':
				$this->delcat();
				break;
		  	case 'manage':
				$this->manage();
				break;
		  	case 'domanage':
		  		$this->domanage();
		  		break;
			case 'delqun':
				$this->delqun();
				break;
			case 'editqun':
				$this->editqun();
				break;
		  	case 'level':
		  		$this->level();
		  		break;
		  	case 'dolevel':
		  		$this->dolevel();
		  		break;
		  	case 'ploy':
		  		$this->ploy();
		  		break;
		  	case 'doploy':
		  		$this->doploy();
		  		break;
			default:
				$this->Code = 'setting';
				$this->setting();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}
	
	
	function setting()
	{
		$config = ConfigHandler::get('qun_setting');
		$checked = array();
		$checked['qun_open'][$config['qun_open']] = 'CHECKED';
		$checked['new_qun'][$config['new_qun']] = 'CHECKED';
		$config['img_size'] = empty($config['img_size']) ? '' : $config['img_size'];
		$config['fans_limit'] = empty($config['fans_limit']) ? '' : $config['fans_limit'];
		
				$credits_type = array(
			1 => array(
				'id' => 1,
				'name' => '原创值',
			),
			2 => array(
				'id' => 2,
				'name' => '互动值',
			),
		);
		$credits_rule = $config['credits_rule'];
		include template('admin/qun_setting');
	}
	
	function dosetting()
	{
		$old_config = ConfigHandler::get('qun_setting');
		$config = &$this->Post['config'];
		$config['img_size'] = intval($config['img_size']);
		$config['member_num'] = intval($config['member_num']);
		if ($config['member_num'] > 999999999) {
			$config['member_num'] = 999999999;
		}
		$config['admin_num'] = intval($config['admin_num']);
		if ($config['admin_num'] > 999999999) {
			$config['admin_num'] = 999999999;
		}
				$config['qun_ploy']['avatar'] = $old_config['qun_ploy']['avatar'];
		ConfigHandler::set('qun_setting', $config);
		$this->Messager('操作成功了');
	}
	
	
	function category()
	{
		$tree = $this->QunLogic->get_category_tree();
		include template('admin/qun_category');
	}

	function docategory()
	{
				$cat_ary = &$this->Post['cat'];
		if (!empty($cat_ary)) {
			$cat_order_ary = &$this->Post['cat_order'];
			foreach ($cat_ary as $key => $cat) {
				$cat_name = getstr($cat, 30, 1, 1);
								$display_order = intval($cat_order_ary[$key]);
				$this->QunLogic->update_category($key, $cat_name, $display_order);
			}
		}
		
				$tcat_ary = &$this->Post['new_tcat'];
		if (!empty($tcat_ary)) {
			$tcat_order_ary = &$this->Post['new_tcat_order'];
			$this->_batch_add_category($tcat_ary, $tcat_order_ary);
		}
		
				$scat_ary = &$this->Post['new_scat'];
		if (!empty($scat_ary)) {
			$scat_order = &$this->Post['new_scat_order'];
			foreach ($scat_ary as $p => $cats) {
				$this->_batch_add_category($cats, $scat_order[$p], $p);
			}
		}
		
				$this->QunLogic->update_category_cache();
		$this->Messager('操作成功了');
	}
	
	
	function _batch_add_category($cat_ary, $order_ary, $parent_id = 0)
	{
		foreach ($cat_ary as $key => $cat) {
						$cat_name = getstr($cat, 30, 1, 1);
			if (empty($cat_name) || $this->QunLogic->category_exists($cat_name, $parent_id)) {
				continue;
			}
			$display_order = intval($order_ary[$key]);
			$this->QunLogic->add_category($cat_name, $display_order, $parent_id);
		}
	}
	
	
	function delcat()
	{
		$cat_id = empty($this->Get['cat_id']) ? 0 : intval($this->Get['cat_id']);
		if (empty($cat_id)) {
			$this->Messager('没有指定分类ID');
		}
		
		$ret = $this->QunLogic->delete_category($cat_id);
		
				$this->QunLogic->update_category_cache();
		
		if ($ret == 1) {
			$this->Messager('删除分类成功');
		} else if ($ret == -1) {
			$this->Messager('当前分类不存在');
		} else if ($ret == -2) {
			$this->Messager('当前分类下面存在组群，不能被删除');
		} else if ($ret == -3) {
			$this->Messager('下级分类不为空，请先返回删除本分类或分类的下级微群');
		}
	}
	
	
	function manage()
	{
				$op = trim($this->Get['op']);
		if ($op == 'edit') {
			$qid = intval(trim($this->Get['qid']));
			if (empty($qid)) {
				$this->Messager('请指定要编辑的微群');
			}
			
			$qun_info = $this->QunLogic->get_qun_info($qid);
			if (empty($qun_info)) {
				$this->Messager('当前群不存在或者已经被删除了');
			}
			$catselect = $this->QunLogic->get_catselect(0, $qun_info['cat_id']);
			$tag = $this->QunLogic->get_qun_strtag($qid);
			$icon = $this->QunLogic->qun_avatar($qid);
			$checked = array();
			$checked['gview_perm'][$qun_info['gview_perm']] = 'checked="checked"';
			$checked['join_type'][$qun_info['join_type']] = 'checked="checked"';
			$checked['recd'] = $qun_info['recd'] == 1 ? 'checked="checked"' : '';
			$u_tips = $this->QunLogic->upload_tips();
			include template('admin/qun_edit');
		} else {
			$perpage = 20;
			$gets = array (
				'mod' => 'qun',
				'code' => 'manage',
			);
			$page_url = 'admin.php?'.url_implode($gets);
			
						
			$level_ary = ConfigHandler::get('qun_level');
			
			$where_sql = ' 1 ';
			
			$qun_level = $this->Get['qun_level'];
			if ($qun_level > 0) {
				$cur_level = $level_ary[$qun_level];
				$credits_higher = $cur_level['credits_higher'];
				$credits_lower = $cur_level['credits_lower'];
				$where_sql .= " AND q.credits<{$credits_lower} AND q.credits>={$credits_higher} ";
			}
			
			$qun_name = trim($this->Get['qun_name']);
			if (!empty($qun_name)) {
				$key_qun_name = jstripslashes($qun_name);
				$q_sql = addcslashes($qun_name, '_%');
				$where_sql .= " AND q.name LIKE('%{$q_sql}%') ";
			}
			
			$nickname = trim($this->Get['nickname']);
			if (!empty($nickname)) {
				$key_nickname = jstripslashes($nickname);
				$n_sql = addcslashes($nickname, '_%');
				$where_sql .= " AND m.nickname LIKE('%{$n_sql}%') ";
			}
			
			$count = DB::result_first("SELECT COUNT(*) 
									   FROM ".DB::table('qun')." AS q 
									   LEFT JOIN ".DB::table('members')." AS m
									   ON q.founderuid=m.uid 
								  	   WHERE {$where_sql}");
			$qun_list = array();
			if ($count) {
				$_config = array(
					'return' => 'array',
				);
				$page_arr = page($count, $perpage, $page_url, $_config);
				
								$cat_ary = ConfigHandler::get('qun_category');
				$query = DB::query("SELECT q.*, m.nickname  
									FROM ".DB::table('qun')." AS q 
									LEFT JOIN ".DB::table('members')." AS m
									ON q.founderuid=m.uid 
									WHERE {$where_sql} 
									ORDER BY dateline DESC 
									{$page_arr['limit']}");
				while ($value = DB::fetch($query)) {
					$level = $this->QunLogic->qun_level($value['qid'], $value['credits']);
					$value['level'] = $level['level_name'];
					$parent_id = $cat_ary['second'][$value['cat_id']]['parent_id'];
					$value['top_cat'] = $cat_ary['first'][$parent_id]['cat_name'];
					$value['top_cat_id'] = $parent_id;
					$value['sub_cat'] = $cat_ary['second'][$value['cat_id']]['cat_name'];
					$value['dateline'] = my_date_format($value['dateline'], 'Y-m-d');
					if ($value['recd']) {
						$value['recd_checked']  = 'checked="checked"';
					}
					$qun_list[]= $value;
				}
			}
			include template('admin/qun_manage');
		}
	}
	
	function domanage()
	{
		$op = trim($this->Get['op']);
		if ($op == 'edit') {
			$post = $this->Post;
			$ret = $this->QunLogic->edit($post);
			if ($ret == -1) {
				$this->Messager('微群名称为空或者群组名称长度小于3');
			} else if ($ret == -2) {
				$this->Messager('请选择一个分类');
			} else if ($ret == -3) {
				$this->Messager('请选择所在的省份');
			} else {
				$this->Messager('操作成功了');
			}
		} else if ($op == 'delete') {
			$qid = intval(trim($this->Get['qid']));
			if (empty($qid)) {
				$this->Messager('错误的操作');
			}
			$qun_info = $this->QunLogic->get_qun_info($qid);
			if (empty($qun_info)) {
				$this->Messager('当前群不存在或者已经被删除');
			}
			$this->QunLogic->delete_qun($qid, $qun_info['cat_id']);
			$this->Messager('操作成功了');
		} else {
						$ids = $this->Post['ids'];
			$qids = $this->Post['qids'];
			$recd_ids = $this->Post['recd_ids'];
			if (!empty($ids)) {
				$where_sql = " qid IN(".jimplode($ids).") ";
				$qun_info_list = array();
				$query = DB::query("SELECT qid,cat_id FROM ".DB::table('qun')." WHERE {$where_sql}");
				while ($value = DB::fetch($query)) {
					$qun_info_list[] = $value;
				}
				foreach ($qun_info_list as $qun_info) {
					$this->QunLogic->delete_qun($qun_info['qid'], $qun_info['cat_id']);	
				}
			}
			
			if (!empty($qids)) {
				foreach ($qids as $qid) {
					$data = array(
						'recd' => 0,
					);
					if (isset($recd_ids[$qid])) {
						$data['recd'] = 1;
					}
					DB::update('qun', $data, array('qid'=>$qid));
				}
			}
			$this->Messager('操作成功了');
		}
	}
	
	
	function level()
	{
		$level_list = $this->QunLogic->get_level_list();
		include template('admin/qun_level');
	}
	
	function dolevel()
	{
		$new_level_ary = $level_ary = array();
		$post = &$this->Post;
		if (!empty($post['new_level'])) {
			foreach ($post['new_level']['level_name'] as $key => $value) {
				$new_level_ary[] = array(
					'level_name' => getstr($value, 40, 1, 1),
					'member_num' => intval($post['new_level']['member_num'][$key]),
					'admin_num' => intval($post['new_level']['admin_num'][$key]),
					'credits_higher' => $post['new_level']['credits_higher'][$key],
				);
			}
		}
		
		$level_ary = $post['level'];
		$level_keys = array();
		if(!empty($level_ary)) {
			$level_keys = array_keys($level_ary);
			$max_levelid = max($level_keys);
		}

		foreach($new_level_ary as $k=>$v) {
			$level_ary[$k+$max_levelid+1] = $v;
		}
		$del_ids = &$post['del_ids'];
		$order_ary = array();
		if(is_array($level_ary)) {
			foreach($level_ary as $id => $val) {
				if((is_array($del_ids) && in_array($id, $del_ids))) {
					unset($level_ary[$id]);
				} else {
					$order_ary[$val['credits_higher']] = $id;
				}
			}
		}
		ksort($order_ary);
		$range_ary = array();
		$lower_limit = array_keys($order_ary);
		for($i = 0; $i < count($lower_limit); $i++) {
			$range_ary[$order_ary[$lower_limit[$i]]] = array (
					'credits_higher' => isset($lower_limit[$i - 1]) ? $lower_limit[$i] : -999999999,
					'credits_lower' => isset($lower_limit[$i + 1]) ? $lower_limit[$i + 1] : 999999999
			);
		}
		foreach($level_ary as $id => $level) {
			$credits_higher_new = $range_ary[$id]['credits_higher'];
			$credits_lower_new = $range_ary[$id]['credits_lower'];
			if($credits_higher_new == $credits_lower_new) {
				$this->Messager('积分设置重复');
			}
			if(in_array($id, $level_keys)) {
				$data = array(
					'level_id' => $id,
					'level_name' => getstr($level['level_name'], 40, 1, 1),
					'credits_lower' => $credits_lower_new,
					'credits_higher' => $credits_higher_new,
					'member_num' => intval($level['member_num']),
					'admin_num' => intval($level['admin_num']),
				);
				$this->QunLogic->update_level($data);
			} else {
				$data = array (
					'level_name' =>	$level['level_name'],
					'credits_lower' => $credits_lower_new,
					'credits_higher' => $credits_higher_new,
					'member_num' => intval($level['member_num']),
					'admin_num' => intval($level['admin_num']),
				);
				$this->QunLogic->add_level($data);
			}
		}
		
				if (!empty($del_ids)) {
			$count = $this->QunLogic->level_nums();
			if ($count == count($del_ids)) {
				$this->QunLogic->update_level_cache();
				$this->Messager('操作成功，但不允许删除全部等级');
			}
			$this->QunLogic->batch_delete_level($del_ids);
		}
		
				$this->QunLogic->update_level_cache();
		$this->Messager('操作成功了');
	}
	
	
	function ploy()
	{
		$ploy_list = $this->QunLogic->get_ploy_list();
		$config = ConfigHandler::get('qun_setting');
		$checked = array();
		$checked[$config['qun_ploy']['avatar']] = 'CHECKED';
		include template('admin/qun_ploy');
	}
	
	function doploy()
	{
				$config = ConfigHandler::get('qun_setting');
		$config['qun_ploy']['avatar'] = $this->Post['config']['qun_ploy']['avatar'];
		ConfigHandler::set('qun_setting', $config);
		
				$new_ploy_ary = array();
		if (!empty($this->Post['new_qun_ploy'])) {
			$new_fans_nums = $this->Post['new_qun_ploy']['fans_num_min'];
			foreach ($new_fans_nums as $key => $value) {
				$qun_num = $this->Post['new_qun_ploy']['qun_num'][$key];
				$topics_lower = $this->Post['new_qun_ploy']['topics_lower'][$key];
				if ($qun_num && $value) {
					$new_ploy_ary[] = array(
						'fans_num_min' => intval($value),
						'topics_lower' => $topics_lower,
						'qun_num' => intval($qun_num),
					);
				}
			}
		}
		
		$qun_ploy = $this->Post['qun_ploy'];
		$new_ploy_keys = array();
		if(!empty($qun_ploy)) {
			$new_ploy_keys = array_keys($qun_ploy);
			$max_ployid = max($new_ploy_keys);
		}

		foreach($new_ploy_ary as $k=>$v) {
			$qun_ploy[$k+$max_ployid+1] = $v;
		}

		$del_ids = &$this->Post['del_ids'];
		$order_ary = array();
		$order_ary_2 = array();
		if(is_array($qun_ploy)) {
			foreach($qun_ploy as $id => $val) {
				if((is_array($del_ids) && in_array($id, $del_ids)) || ($id == 0 && (!$val['fans_num_min'] || !$val['topics_lower'] || !$val['qun_num']))) {
					unset($qun_ploy[$id]);
				} else {
					$order_ary[$val['fans_num_min']] = $id;
					$order_ary_2[$val['topics_lower']] = $id;
				}
			}
		}
		ksort($order_ary);
		ksort($order_ary_2);
		
		$range_ary = array();
		$range_ary_2 = array();
		$min_limit = array_keys($order_ary);
		$min_limit_2 = array_keys($order_ary_2);
		
		for($i = 0; $i < count($min_limit); $i++) {
			$range_ary[$order_ary[$min_limit[$i]]] = array (
				'fans_num_max' => isset($min_limit[$i + 1]) ? $min_limit[$i + 1] : 999999999,
				'fans_num_min' => $min_limit[$i],
			);
		}
		
		for($i = 0; $i < count($min_limit_2); $i++) {
			$range_ary_2[$order_ary_2[$min_limit_2[$i]]] = array (
				'topics_higher' => isset($min_limit_2[$i + 1]) ? $min_limit_2[$i + 1] : 999999999,
				'topics_lower' => $min_limit_2[$i],
			);
		}
		
		foreach($qun_ploy as $id => $ploy) {
			$fans_num_max_new = $range_ary[$id]['fans_num_max'];
			$fans_num_min_new = $range_ary[$id]['fans_num_min'];
			
			$topics_higher_new = $range_ary_2[$id]['topics_higher'];
			$topics_lower_new = $range_ary_2[$id]['topics_lower'];
			
			if($fans_num_max_new == $fans_num_min_new) {
				$this->Messager('策略粉丝限制重复');
			}
			
			if(in_array($id, $new_ploy_keys)) {
				$data = array(
					'id' => $id,
					'qun_num' => abs($ploy['qun_num']),
					'fans_num_min' => $fans_num_min_new,
					'fans_num_max' => $fans_num_max_new,
					'topics_lower' => $topics_lower_new,
					'topics_higher' => $topics_higher_new,
				);
				$this->QunLogic->update_ploy($data);
			} else {
				$data = array (
					'qun_num' => abs($ploy['qun_num']),
					'fans_num_min' => $fans_num_min_new,
					'fans_num_max' => $fans_num_max_new,
					'topics_lower' => $topics_lower_new,
					'topics_higher' => $topics_higher_new,
				);
				$this->QunLogic->add_ploy($data);
			}
		}
		
				if (!empty($del_ids)) {
			$count = $this->QunLogic->ploy_nums();
			if ($count == count($del_ids)) {
				$this->QunLogic->update_ploy_cache();
				$this->Messager('操作成功，但不允许删除全部策略');
			}
			$this->QunLogic->delete_ploy($del_ids);
		}
		
				$this->QunLogic->update_ploy_cache();
		$this->Messager('操作成功了');
	}
	
}
?>
