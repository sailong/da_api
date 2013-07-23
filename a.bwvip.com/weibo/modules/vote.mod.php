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
 * @Date 2011-09-09 10:58:44 1423413519 267555532 8675 $
 *******************************************************************/



 

if(!defined('IN_JISHIGOU')) {
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $item = 'vote';
	var $item_id = 0;
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		
				$this->ShowConfig = ConfigHandler::get('show');
		
		Load::logic('vote');
		$this->VoteLogic = new VoteLogic();
		
		Load::logic('topic');
		$this->TopicLogic = new TopicLogic();
		
		if (MEMBER_ROLE_TYPE != 'admin') {
			$vote_setting = ConfigHandler::get('vote');
			if (!$vote_setting['vote_open']) {
				$this->Messager("当前站点没有开放投票功能", null);
			}
		}
		
				$code = &$this->Code;
		ob_start();
		if (!empty($this->Get['vid']) && empty($code)) {
			$code = 'view';
		} else if (empty($code)) {
			$code = 'index';
			if (!empty($this->Get['uid']) && empty($this->Get['view'])){
				$this->Get['view'] = 'me';
			}
		}
		
		if (in_array($code, array('create'))) {
						$this->_check_login();
		}
		
		if (method_exists('ModuleObject', $code)) {
			$this->$code();
		} else {
			$this->index();
		}
		$body = ob_get_clean();
		$this->ShowBody($body);
	}
	
	
	function index()
	{
		
				$view = empty($this->Get['view']) ? '' : trim($this->Get['view']);
		$filter = empty($this->Get['filter']) ? '' : trim($this->Get['filter']);
		$uid = empty($this->Get['uid']) ? 0 : intval($this->Get['uid']); 
		
		$gets = array(
			'mod' => 'vote',
			'view' => $this->Get['view'],
			'filter' => $this->Get['filter']
		);
		$page_url = 'index.php?'.url_implode($gets);
		
				$perpage = $this->ShowConfig['vote']['list'];
		$perpage = empty($perpage) ? 20 : $perpage;
		
				$tpl = 'vote_index';

		$where_sql = " 1 ";
		switch ($view) {
			case 'new':
				$this->Title = '最新投票';
				$order_sql = ' ORDER BY dateline DESC ';
				break;
			case 'me':
				if ($uid && $uid != MEMBER_ID) {
										$user_info = $this->TopicLogic->GetMember($uid);
					$this->Title = $user_info['nickname'].'发起的投票';
					if (empty($user_info)) {
												$this->Messager("当前页面不存在", 'index.php?mod=vote');
					}
					$tpl = 'vote_me';
				} else {
					$this->_check_login();
					$this->Title = '我的投票';
					$uid = MEMBER_ID;
				}
				
								if ($filter == 'joined') {
										$vids = $this->VoteLogic->get_joined($uid);
					if (!empty($vids)) {
						$where_sql .= " AND `v`.`vid` IN(".jimplode($vids).") ";
					} else {
						$where_sql = ' 0 ';
					}
				} else if ($filter == 'new_update') {
					
										DB::query("UPDATE ".DB::table('members')." SET vote_new=0 WHERE uid='{$uid}'");
					$this->MemberHandler->MemberFields['vote_new'] = 0;
					
					$vids = $this->VoteLogic->get_joined($uid);
					if (!empty($vids)) {
						$where_sql .= " AND `v`.`vid` IN(".jimplode($vids).") ";
					}
					$where_sql .= " OR `v`.`uid`='{$uid}' ";
				}  else {
					$where_sql .= " AND `v`.`uid`='{$uid}' ";
					$filter = 'created';
				}
				$order_sql = ' ORDER BY lastvote DESC ';
				break;
			case 'fllow':
				$this->_check_login();
				$this->Title = '我关注的人的投票';
				
								$buddyids = $this->_get_buddy(MEMBER_ID);
				if ($filter == 'joined') {
										$vids = $this->VoteLogic->get_joined($buddyids);
					if (!empty($vids)) {
						$where_sql .= " AND `v`.`vid` IN(".jimplode($vids).") ";
					} else {
						$where_sql = ' 0 ';
					}
				} else {
					if (!empty($buddyids)) {
						$where_sql .= " AND `v`.`uid` IN (".jimplode($buddyids).") ";
					} else {
						$where_sql = ' 0 ';
					}
					$filter = 'created';
				}
				$order_sql = ' ORDER BY dateline DESC ';
				break;
			default:
				$this->Title = '热门投票';
				$view = 'hot';
				
								if ($filter == 'w') {
										$range_day = 7*24;
				} else if ($filter == 'm') {
										$range_day = 30*24;
				} else {
										$range_day = 24;
					$filter = 't';
				}
				$timerange = TIMESTAMP - $range_day*3600;
				$where_sql .= " AND v.lastvote >= '{$timerange}' ";
				$order_sql = " ORDER BY v.voter_num DESC";
				break;
		}
		
		if (!empty($filter)) {
			$filter_on[$filter] = 'class="v_on"';
		}
		
		$param = array(
			'where' => $where_sql,
			'order' => $order_sql,
			'page' => true,
			'perpage' => $perpage,
			'page_url' => $page_url,
		);
		$vote_info = $this->VoteLogic->find($param);
		$count = 0;
		$vote_list = array();
		$page_arr['html'] = '';
		$uid_ary = array();
		if (!empty($vote_info)) {
			$count = $vote_info['count'];
			$vote_list = $vote_info['vote_list'];
			$page_arr['html'] = $vote_info['page']['html'];
			$uid_ary = $vote_info['uids'];
		}
		
					
				if (!empty($uid_ary)) {
			$members = $this->TopicLogic->GetMember($uid_ary);
		}
		$this->Title .= ' | 微博投票';
		$active[$view] = 'class="tago"';
		$member = $this->TopicLogic->GetMember(MEMBER_ID);
		
				$recd_list = $this->VoteLogic->get_recd_list();

		include template($tpl);
	}
	
	
	function view()
	{
		$vid = empty($this->Get['vid']) ? 0 : intval($this->Get['vid']);
			
		$newpoll = $hotpoll = $poll = $option = array();
		$vote = $this->VoteLogic->id2voteinfo($vid);
		if(empty($vote)) {
			$this->Messager('当前投票不存在!');
		}
		
		$this->item_id = $vid;
		$ret = $this->VoteLogic->process_detail($vote, MEMBER_ID);
		extract($ret);
		
				$member = $this->TopicLogic->GetMember($vote['uid']);
		
		if ($member['uid'] != MEMBER_ID) {
			$fllow = chk_follow(MEMBER_ID, $member['uid']);
			$follow_html = follow_html($member['uid'], $fllow);
			$all_vote_btn = "他的全部投票";
		} else {
			$follow_html = '我自己';
			$all_vote_btn = "我的全部投票";
		}
		
		if ($member['uid'] == MEMBER_ID || MEMBER_ROLE_TYPE == 'admin') {
			$exp_info = $this->VoteLogic->get_publish_form_param();
			extract($exp_info);
		}
		
				$recd_list = $this->VoteLogic->get_recd_list();
		
				Load::functions('app');
		$gets = array(
			'mod' => 'vote',
			'code' => 'view',
			'vid' => $vid,
		);
		$page_url = 'index.php?'.url_implode($gets);
		$options = array(
			'page' => true,
			'perpage' => 5,				'page_url' => $page_url,
		);
		$topic_info = app_get_topic_list($this->item, $vid, $options);
		$topic_list = array();
		if (!empty($topic_info)) {
			$topic_list = $topic_info['list'];
			$page_arr['html'] = $topic_info['page']['html'];
		}
		
		$params = array(
			'item' => $this->item,
			'item_id' => $vid,
			'oc' => 'view',
		);
		
		$no_from = true;
		
				if(MEMBER_STYLE_THREE_TOL){
			$my_member = $this->TopicLogic->GetMember(MEMBER_ID);
		}
		
		$this->Title = '投票 - '.$vote['subject'];
		include template("vote_view");
	}
	
	
	function create()
	{	
		$this->Title = "我的投票";
		$max_option = 20;
		$perpage = 10;
		$options = range(1, $perpage);
		
		$exp_info = $this->VoteLogic->get_publish_form_param();
		extract($exp_info);
		
				if(MEMBER_STYLE_THREE_TOL){
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
		}
		
		include template('vote_create');
	}
	
	
	
	
	
	function _get_buddy($uid)
	{
		$buddyids = array();
		$query = DB::query("SELECT `buddyid`  
							FROM ".DB::table("buddys")." 
							WHERE `uid`='{$uid}'");
		while ($value = DB::fetch($query)) {
			$buddyids[] = $value['buddyid'];
		}
		return $buddyids;
	}
	
	
	function _check_login()
	{
		if (MEMBER_ID < 1) {
			$this->Messager("你需要先登录才能继续本操作", 'index.php?mod=login');	
		}
	}
}
?>
