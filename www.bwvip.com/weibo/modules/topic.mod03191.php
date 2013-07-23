<?php
/**
 * 文件名：topic.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 微博话题模块
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
		if('fans' == $this->Code) {
			$this->Fans();
		}elseif ('follow' == $this->Code) {
			$this->Follow();
		} elseif ('top' == $this->Code) {
			$this->Top();
		} elseif ('group' == $this->Code) {
			$this->ViewGroup();
		} elseif (in_array($this->Code,array('new','hotforward','hotreply','newreply'))) {
			$this->Hot();
		} elseif ('home' == $this->Code) {
			$this->_guestIndex();
		} elseif ('view' == $this->Code) {
			$this->View();
		} elseif (is_numeric($this->Code)) {
			$this->ID = (int) $this->Code;
			$this->View();
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

		Load::lib('form');
		$FormHandler = new FormHandler();

				if ('topic'==trim($this->Get['mod']) && empty($this->Code) && empty($this->Get['mod_original'])) {

						if (MEMBER_ID > 0) {
				$this->Code = 'myhome';
			} else {
				$this->_guestIndex();
				return ;
			}
		}

		$title = '';
		if(isset($uid)) $uid = (int) $uid;
				$per_page_num = 20;
		$topic_uids = $topic_ids = $order_list = $where_list = $params = array();
		$where = $order = $limit = "";

				$options = array();
		$gets = array(
			'mod' => $_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module,
			'code' => $this->Code,
			'type' => $this->Get['type'],
			'gid' => $this->Get['gid'],
			'qid' => $this->Get['qid'],
			'view' => $this->Get['view'],
		);
		$options['page_url'] = "index.php?".url_implode($gets);
		unset($gets['type']);
		$type_url = "index.php?".url_implode($gets);



				$member = $this->_member();
		if(!$member) {
			$this->_guestIndex();
			return false;
		}

		$params['uid'] = $uid = $member['uid'];
		if($uid != MEMBER_ID || $_GET['mod_original']) {
								} else {


								}

				$is_personal = ($uid == MEMBER_ID);
		$params['is_personal'] = $is_personal;



		$params['code'] = $this->Code;

				$code_ary = array (
			'myblog',
			'mycomment',
			'tocomment',
			'myhome',
			'myat',
			'myfavorite',
			'favoritemy',
			'tag',
						'qun',
			'recd',
			'other',
		);

		if (!in_array($params['code'], $code_ary)) {
						$params['code'] = 'myblog';
		}

				if (($show_topic_num = (int) $this->ShowConfig['topic'][$params['code']]) > 0) {
			$per_page_num = $show_topic_num;
		}

		$options['perpage'] = $per_page_num;

				$groupname = '';
		$groupid = 0;

		Load::logic("topic_list");
		$TopicListLogic = new TopicListLogic();

		if ('myhome'==$params['code']) {



			$topic_selected = 'myhome';

						$gid = (int) $this->Get['gid'];
			if (empty($gid)) {
				$params['gid'] = $gid;
			}

						if ($this->Get['type']) {
				$options['filter'] = $this->Get['type'];
			}

						$topic_myhome_time_limit = 0;
			if($this->Config['topic_myhome_time_limit'] > 0) {
				$topic_myhome_time_limit = (time() - ($this->Config['topic_myhome_time_limit'] * 86400));
          		if ($topic_myhome_time_limit > 0) {
					$options['dateline'] = $topic_myhome_time_limit;
          		}
			}

						$options['uid'] = array($member['uid']);
						
			if ($member['uid'] == MEMBER_ID) {

				                if ($this->Config['ajax_topic_time']) {
                	$this->DatabaseHandler->Query("update `".TABLE_PREFIX."members` set `lastactivity`='".time()."' where `uid`='$uid'");
                }
				$title = '我的首页';

								if (!empty($gid)) {
					$group_info = DB::fetch_first("SELECT *
												   FROM ".DB::table('group')."
												   WHERE uid=".MEMBER_ID." AND id='{$gid}' ");
					if (empty($group_info)) {
						$this->Messager("当前分组不存在",'index.php?mod=myhome');
					}
					$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&code={$this->Code}&type={$this->Get['type']}&gid={$this->Get['gid']}" : "");
					$sql = "select * from `".TABLE_PREFIX."groupfields` where `gid`='{$gid}' and uid = ".MEMBER_ID." ";
					$query = $this->DatabaseHandler->Query($sql);
					$g_view_uids = array();
					$list = array();
					while ($row = $query->GetRow()) {
						$g_view_uids[$row['touid']] = $row['touid'];
						$groupname = $row['g_name'];
						$groupid = $row['gid'];
					}

										if($g_view_uids) {
						$options['uid'] = $g_view_uids;
						
					} else {
						$this->Messager("没有设置用户，无法查看这个组的微博",'index.php?mod=topic&code=group&gid='.$gid);
					}
					$active[$gid] = "current";
				} else {
					$sql_buddy_lastuptime = '';
					if($this->Config['topic_myhome_time_limit'])
					{
						$sql_buddy_lastuptime = " and `buddy_lastuptime`>'" . (time() - 86400 * $this->Config['topic_myhome_time_limit']) ."'";
					}
					$sql = "select `buddyid` from `".TABLE_PREFIX."buddys` where `uid`='{$params['uid']}' $sql_buddy_lastuptime ";
					$query = $this->DatabaseHandler->Query($sql);
					while($row = $query->GetRow()) {
						$options['uid'][] = $row['buddyid'];
					}
					$active['all'] = "current";

				}
			} else {
				$title = "{$member['nickname']}的微博";
				$this->_initTheme($member);
			}
		} else if('tag' == $params['code']) {


			$title = '我关注的话题';
			if ($member['uid'] != MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

			$views = array('new', 'new_reply', 'my_reply', 'recd');
			$view = trim($this->Get['view']);
			if (!in_array($view, $views)) {
				$view = 'new';
			}
			$active[$view] = 'current';

	 		$sql = "select  TF.uid , TF.tag ,T.name , T.*
	 				from `".TABLE_PREFIX."tag` T
	 				left join `".TABLE_PREFIX."tag_favorite` TF
	 				on T.name=TF.tag
	 				where `uid`='".MEMBER_ID."'";
			$query = $this->DatabaseHandler->Query($sql);
			$tag_info = array();
			while ($row = $query->GetRow()) {
				$tag_info[$row['id']] = $row['id'];
			}

			if (!$tag_info) {
				$this->Messager("没有设置关注话题,请到热门话题中选择感兴趣的话题",'index.php?mod=tag');
			}


						$sql = "select `item_id` from `".TABLE_PREFIX."topic_tag` where  `tag_id` in(".implode(",",$tag_info).")";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_ids = array();
			while ($row = $query->GetRow()) {
				$topic_ids[$row['item_id']] = $row['item_id'];
			}

			if (!$topic_ids) {
				$this->Messager("没有设置关注话题,请到热门话题中选择感兴趣的话题",'index.php?mod=tag');
			}

			$options['tid'] = $topic_ids;
			unset($topic_ids);

						if ($this->Get['type']) {
				$options['filter'] = $this->Get['type'];
			}

			if ($view == 'new_reply') {
				$options['where'] = " replys>0 ";
				$options['order'] = " lastupdate DESC ";
			} else if ($view == 'recd') {

				$p = array(
					'where' => " tr.recd >= 1 AND tr.item='tag' AND tr.item_id IN(".jimplode($tag_info).") ",
					'perpage' => 10,
					'filter' => $this->Get['type'],
				);
				$info = $TopicListLogic->get_recd_list($p);
				if (!empty($info)) {
					$total_record = $info['count'];
					$topic_list = $info['list'];
					$page_arr = $info['page'];
				}
				$topic_list_get = true;
			}

						DB::query("UPDATE ".DB::table('members')." SET topic_new=0 WHERE uid='".MEMBER_ID."' ");
			$this->MemberHandler->MemberFields['topic_new'] = 0;

		} else if('other' == $params['code']) {
			$view = $this->Get['view'];
			$view = $view ? $view : 'event';
			$active[$view] = 'current';
			if ($member['uid'] != MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}
			if($view == 'event'){

				$title = '我关注的活动';
								$query = $this->DatabaseHandler->Query("select f.type_id as id,s.type from ".TABLE_PREFIX."event_favorite f left join ".TABLE_PREFIX."event_sort s on s.id = f.type_id where f.uid = ".MEMBER_ID);
				while ($rsdb = $query->GetRow()){
					$event_id_arr[$rsdb['id']] = $rsdb['id'];
					$favorite_event[$rsdb['id']] = $rsdb['type'];
				}
								$topic_ids = array();
				if($event_id_arr){
					$where = " where id not in (".implode(',',$event_id_arr).") ";
										$sql = "select `id` from `".TABLE_PREFIX."event` where `type_id` in(".implode(",",$event_id_arr).")";
					$query = $this->DatabaseHandler->Query($sql);
					while ($row = $query->GetRow()) {
						$topic_ids[$row['id']] = $row['id'];
					}
				}else{
					$topic_list_get = true;
				}
				$query = $this->DatabaseHandler->Query("select * from ".TABLE_PREFIX."event_sort $where order by id ");
				while ($rsdb = $query->GetRow()){
					$event_sort[$rsdb['id']]['value'] = $rsdb['id'];
					$event_sort[$rsdb['id']]['name'] = $rsdb['type'];
				}
				if($event_sort){
					$event_sort_list = $FormHandler->Select("event",$event_sort);
				}else{
					$event_sort_list = '';
				}

				if($topic_ids){
					$options['item_id'] = $topic_ids;
					unset($topic_ids);

										if ($this->Get['type']) {
						$options['filter'] = $this->Get['type'];
					}

					$options['where'] = " `from` = 'event' and `type` = 'first' ";
					$options['order'] = " lastupdate DESC ";
				}else{
					$topic_list_get = true;
				}
								DB::query("UPDATE ".DB::table('members')." SET event_post_new=0 WHERE uid='".MEMBER_ID."' ");
				$this->MemberHandler->MemberFields['event_post_new'] = 0;
			}elseif($view == 'fenlei'){

				$title = '我关注的分类';
								$query = $this->DatabaseHandler->Query("select f.fid as id,s.name from ".TABLE_PREFIX."fenlei_favorite f left join ".TABLE_PREFIX."fenlei_sort s on s.fid = f.fid where f.uid = ".MEMBER_ID);
				while ($rsdb = $query->GetRow()){
					$fenlei_id_arr[$rsdb['id']] = $rsdb['id'];
					$favorite_fenlei[$rsdb['id']] = $rsdb['name'];
				}

				$topic_ids = array();
				if($fenlei_id_arr){
					$where = " and fid not in (".implode(',',$fenlei_id_arr).") ";
										$sql = "select `id` from `".TABLE_PREFIX."fenlei_content` where `fid` in(".implode(",",$fenlei_id_arr).")";
					$query = $this->DatabaseHandler->Query($sql);
					while ($row = $query->GetRow()) {
						$topic_ids[$row['id']] = $row['id'];
					}
				}else{
					$topic_list_get = true;
				}
				$query = $this->DatabaseHandler->Query("select fid,name from ".TABLE_PREFIX."fenlei_sort where fup <> 0 $where order by fid ");
				while ($rsdb = $query->GetRow()){
					$fenlei_sort[$rsdb['fid']]['value'] = $rsdb['fid'];
					$fenlei_sort[$rsdb['fid']]['name'] = $rsdb['name'];
				}
				if($fenlei_sort){
					$fenlei_sort_list = $FormHandler->Select("fenlei",$fenlei_sort);
				}else{
					$fenlei_sort_list = '';
				}

				if($topic_ids){
					$options['item_id'] = $topic_ids;
					unset($topic_ids);

										if ($this->Get['type']) {
						$options['filter'] = $this->Get['type'];
					}

					$options['where'] = " `from` = 'fenlei' and `type` = 'first' ";
					$options['order'] = " lastupdate DESC ";
				}else{
					$topic_list_get = true;
				}
								DB::query("UPDATE ".DB::table('members')." SET fenlei_post_new=0 WHERE uid='".MEMBER_ID."' ");
				$this->MemberHandler->MemberFields['fenlei_post_new'] = 0;
			}

		} else if ('mycomment' == $params['code']) {



			$title = '评论我的';
			if ($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						if ($member['comment_new']) {
				$sql = "update `".TABLE_PREFIX."members` set `comment_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);
				$this->MemberHandler->MemberFields['comment_new'] = 0;
			}

			$topic_selected = 'mycomment';
			$options['where'] = "`touid`='{$member['uid']}' and `type` in ('both','reply')";

		} else if ('tocomment' == $params['code']) {



			$title = '我评论的';

			if($member['uid']!=MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

			$topic_selected = 'mycomment';
			$options['where'] = "`uid` = '{$member['uid']}' and `type` in ('both','reply')";

		} else if ('myblog' == $params['code']) {


			$where = " 1 ";

						if ($this->Get['type']) {

				if ('my_reply' == $this->Get['type']) {
					$options['type'] = array('reply', 'both');
				}
			 }

			if ($member['uid'] != MEMBER_ID) {
				$title = "{$member['nickname']}的微博";


				if(MEMBER_STYLE_THREE_TOL == 1)
				{
					$my_member = $this->_member(MEMBER_ID);
				}


						        $list_blacklist = array();
		        if (MEMBER_ID > 0) {
		            $sql = "select `uid`,`touid`
		            		from `".TABLE_PREFIX."blacklist`
		            		where `touid` = '{$member['uid']}' and `uid` = '".MEMBER_ID."'";
		    		$query = $this->DatabaseHandler->Query($sql);
		    		$list_blacklist = $query->GetRow();
		        }



								$fg_code = 'hisblog';
                $this->_initTheme($member);
			} else {
				$title = '我的微博';
				$this->MetaKeywords ="{$member['nickname']}的微博";
			}

    $buddys = array();

                        if (MEMBER_ID > 0 && $member['uid'] != MEMBER_ID) {
            	$sql = "select `buddyid` as `id`,`remark`
            			from `".TABLE_PREFIX."buddys`
            			where `uid`='".MEMBER_ID."' and `buddyid` = ".$member['uid'];
				$query = $this->DatabaseHandler->Query($sql);
				$buddys = $query->GetRow();
            }
//徐玉枭增加员工。	
   if (MEMBER_ID > 0 && $member['uid'] != MEMBER_ID) {
$sql = "SELECT  userid from pre_guanxi  where iscomp=1 and compid=".$member['uid'];
 
		$query = $this->DatabaseHandler->Query($sql); 
		while ($row = $query->GetRow())
			{
				$uidss[$row['userid']] = $row['userid'];
			}
	 	
			if($uidss){
			$uidss[]=$member['uid'];
			$options['uid'] = $uidss;
			}else{
			$options['uid'] = $member['uid'];
			} 
}else{
		$options['uid'] = $member['uid'];
}
			
			$topic_selected = 'myblog';
		} else if ('myat' == $params['code']) {



			$title = '@提到我的';

						$topic_selected = 'myat';
			if($member['uid'] != MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						if ($member['at_new']) {
								$sql = "update `".TABLE_PREFIX."members` set `at_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);
				$this->MemberHandler->MemberFields['at_new'] = 0;
			}

			$sql = "select * from `".TABLE_PREFIX."topic_mention` where `uid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$topic_ids[0] = 0;
			while ($row = $query->GetRow())
			{
				$topic_ids[$row['tid']] = $row['tid'];
			}
			$options['tid'] = $topic_ids;

		} else if ('myfavorite' == $params['code']) {



			$topic_selected = 'myfavorite';
			$title = '我的收藏';
			if ($member['uid'] != MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."topic_favorite` TF where TF.uid='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

						$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>"Array"));

						$sql = "select TF.dateline as favorite_time , T.*
					from `".TABLE_PREFIX."topic_favorite` TF
					left join `".TABLE_PREFIX."topic` T
					on T.tid=TF.tid
					where TF.uid='{$uid}'
					order by TF.id desc {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			while ($row = $query->GetRow()) {
				if ($row['tid']<1) {
					continue;
				}
				$row['favorite_time'] = my_date_format2($row['favorite_time']);
				$row = $this->TopicLogic->Make($row);
				$topic_list[$row['tid']] = $row;
			}
			$topic_list_get = true;

		} else if ('favoritemy' == $params['code']) {



			$topic_selected = 'favoritemy';
			$title = '收藏我的';
			if ($member['uid'] != MEMBER_ID) {
				$this->Messager("您无权查看该页面",null);
			}

						if ($member['favoritemy_new'] > 0) {
				$sql = "update `".TABLE_PREFIX."members` set `favoritemy_new`=0 where `uid`='{$member['uid']}'";
				$this->DatabaseHandler->Query($sql);
				$this->MemberHandler->MemberFields['favoritemy_new'] = 0;
			}

						$sql = "select count(*) as `total_record` from `".TABLE_PREFIX."topic_favorite` TF where TF.tuid='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

						$page_arr = page($total_record,$per_page_num,$query_link,array('return'=>"Array"));

						$sql = "select TF.dateline as favorite_time , TF.uid as fuid , T.*
					from `".TABLE_PREFIX."topic_favorite` TF
					left join `".TABLE_PREFIX."topic` T
					on T.tid=TF.tid
					where TF.tuid='{$uid}'
					order by TF.id desc {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			$fuids = array();
			while ($row = $query->GetRow()) {
				if ($row['tid']<1) {
					continue;
				}
				$row['favorite_time'] = my_date_format2($row['favorite_time']);
				$row = $this->TopicLogic->Make($row);
				$topic_list[$row['tid']] = $row;
				$fuids[$row['fuid']] = $row['fuid'];
			}
			$favorite_members = $this->TopicLogic->GetMember($fuids,"`uid`,`ucuid`,`username`,`nickname`,`face_url`,`face`,`validate`");

			$topic_parent_disable = true;
			$topic_list_get = true;

		} else if ('qun' == $params['code']) {
			$title = "我的微群";

			$qun_setting = ConfigHandler::get('qun_setting');
			if (!$qun_setting['qun_open']) {
								DB::query("UPDATE ".DB::table('members')." SET qun_new=0 WHERE uid='".MEMBER_ID."' ");
				$this->MemberHandler->MemberFields['qun_new'] = 0;
				$this->Messager("当前站点没有开放微群功能", null);
			}

			$views = array('new', 'new_reply', 'my_reply', 'recd');
			$view = trim($this->Get['view']);
			if (!in_array($view, $views)) {
				$view = 'new';
			}
			$active[$view] = "current";

			$u = MEMBER_ID;
			$join_qun_count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('qun_user')." WHERE uid='{$u}' ");
			$qun_name = '';
			if ($join_qun_count > 0) {
								$query = DB::query("SELECT qid FROM ".DB::table('qun_user')." WHERE uid='{$u}'");
				while ($value = DB::fetch($query)) {
					$qid_ary[] = $value['qid'];
				}
				if (!empty($qid_ary)) {

					$where_sql = " 1 ";
					$order_sql = " t.dateline DESC ";
					if ($this->Get['type']) {
						if ('pic' == $this->Get['type']){
							$where_sql .= " AND t.`imageid` > 0 ";
						} else if('video' == $this->Get['type']) {
							$where_sql .= " AND t.`videoid` > 0 ";
						} else if('music' == $this->Get['type']) {
							$where_sql .= " AND t.`musicid` > 0 ";
						} else if ('vote' == $this->Get['type']) {
							$where_sql .= " AND t.item='vote' ";
						} else if('longtext' == $this->Get['type']) {
							$where_sql .= " ADN t.`longtextid` > 0 ";
						}
					}

					$topic_get_flg = false;
					if ($view == 'new') {
						$where_sql .= " AND tq.item_id IN(".jimplode($qid_ary).") ";
					} else if ($view == 'new_reply') {
						$where_sql .= " AND tq.item_id IN(".jimplode($qid_ary).") AND t.replys>0 ";
						$order_sql = " t.lastupdate DESC ";
					} else if ($view == 'recd') {
						$p = array(
							'where' => " tr.recd >= 1 AND tr.item='qun' AND tr.item_id IN(".jimplode($qid_ary).") ",
							'perpage' => $options['perpage'],
							'filter' => $this->Get['type'],
						);
						$info = $TopicListLogic->get_recd_list($p);
						if (!empty($info)) {
							$total_record = $info['count'];
							$topic_list = $info['list'];
							$page_arr = $info['page'];
						}
						$topic_get_flg = true;
					}

					if (!$topic_get_flg) {
						$total_record = DB::result_first("SELECT COUNT(*)
												   FROM ".DB::table('topic')." AS t
												   LEFT JOIN ".DB::table('topic_qun')." AS tq
												   USING(tid)
												   WHERE {$where_sql}");
						if ($total_record > 0) {
														$page_arr = page($total_record, $options['perpage'], $options['page_url'], array('return'=>'array'));
							$query = DB::query("SELECT t.*
												FROM ".DB::table('topic')." AS t
												LEFT JOIN ".DB::table('topic_qun')." AS tq
												USING(tid)
												WHERE {$where_sql}
												ORDER BY {$order_sql}
												{$page_arr['limit']} ");
							$topic_list = array();
							while ($value = DB::fetch($query)) {
								$topic_list[$value['tid']] = $this->TopicLogic->Make($value);
							}
						}
					}

										DB::query("UPDATE ".DB::table('members')." SET qun_new=0 WHERE uid='".MEMBER_ID."' ");
					$this->MemberHandler->MemberFields['qun_new'] = 0;
				}
			}

			$topic_list_get = true;

		} else if ('recd' == $params['code']) {

			$title = "今日推荐";
			$view = trim($this->Get['view']);
			$where_sql = '';
			if ($view == 'new_reply') {
				$where_sql = ' AND t.replys>0 ';
			} else {
				$view = 'all';
			}
			$active[$view] = 'current';

			$p = array(
				'where' => ' tr.recd > 2 '.$where_sql,
				'perpage' => $options['perpage'],
				'filter' => $this->Get['type'],
			);
			$info = $TopicListLogic->get_recd_list($p);
			if (!empty($info)) {
				$total_record = $info['count'];
				$topic_list = $info['list'];
				$page_arr = $info['page'];
			}
			$topic_list_get = true;
		}

		if (!$topic_list_get) {

		   /*大正社区判断 过来的值----start*/
			if(defined('FROM_DAZ_MOD') && defined('FROM_DAZ_UID')){
				global  $weibo_new_list ;
				$gets = array(
						'mod' => FROM_DAZ_MOD,
						//'code' => $this->Code,
						//'type' => $this->Get['type'],
						//'gid' => $this->Get['gid'],
						//'qid' => $this->Get['qid'],
						//'view' => $this->Get['view'],
				);
				$options['page_url'] = "index.php?".url_implode($gets);
				$options['uid']      = array(FROM_DAZ_UID);    //数组形式  常量是 1,23,4 字符串
			}
			$info = $TopicListLogic->get_data($options);//原始文件

			if(defined('FROM_DAZ_MOD')){
				if($info){
			    	$weibo_new_list = $info; return;
				}else{
					$weibo_new_list = array(); return;
				}
			}
			/*大正社区判断 过来的值----end*/





			$topic_list = array();
			$total_record = 0;
			if (!empty($info)) {
				$topic_list = $info['list'];
				$total_record = $info['count'];
				$page_arr = $info['page'];
			}
		}
 
		$topic_list_count = 0;
		if ($topic_list)
		{
			$topic_list_count = count($topic_list);
			if (!$topic_parent_disable)
			{

								$parent_list = $this->TopicLogic->GetParentTopic($topic_list, ('mycomment' == $this->Code));
							}
		}

	    $group_list = $grouplist2 = array();
		$group_list = $this->_myGroup($member['uid']);
		$cut_num = 5;
        if ($group_list) {
        	$group_count = count($group_list);
        	if ($group_count > $cut_num) {
        		$grouplist2 = array_slice($group_list,0,$cut_num);
        		$grouplist_more = array_slice($group_list, $cut_num);
        		foreach ($grouplist_more as $key => $value) {
        			if ($value['id'] == $gid) {
        				$tmp = $grouplist2[$cut_num-1];
        				$grouplist2[$cut_num-1] = $value;
        				$grouplist_more[] = $tmp;
        				unset($grouplist_more[$key]);
        				break;
        			}
        		}
        		$group_list = $grouplist_more;
        	} else {
        		$grouplist2 = $group_list;
        		$group_list = array();
        	}
        }


				$member_medal = $my_member ? $my_member : $member;
		if ($member_medal['medal_id']) {
			$medal_list = $this->_Medal($member_medal['medal_id'],$member_medal['uid']);
		}

        $exp_return = user_exp($member_medal['level'],$member_medal['credits']);
		if($exp_return['exp_width'] >= 1){
			$exp_width = $exp_return['exp_width'];
		} else{
			$exp_width = 0;
		}
				$nex_exp_credit  = $exp_return['nex_exp_credit'];
				$nex_level  = $exp_return['nex_exp_level'];

		$event_setting = ConfigHandler::get('event_setting');
		$this->Title = $title;
		include($this->TemplateHandler->Template('topic_index'));
	}


		function View()
	{
		if ($this->ID < 1) {
			$this->Messager("请指定一个ID",null);
		}
		$per_page_num = 10;
		$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");

		$topic_info = $this->TopicLogic->Get($this->ID);

		if (!$topic_info) {
			$this->Messager("您要查看的话题已经不存在了",null);
		}

				$allow_op = 1;


		if ($topic_info['item'] == 'qun' && !empty($topic_info['item_id'])) {
			Load::logic('qun');
			$QunLogic = new QunLogic();
			$qun_info = $QunLogic->get_qun_info($topic_info['item_id']);
			if (!empty($qun_info)) {
				$qun_info['icon'] = $QunLogic->qun_avatar($qun_info['qid'], 's');
				$allow_op = $is_qun_member = $QunLogic->is_qun_member($topic_info['item_id'], MEMBER_ID);
				if ($qun_info['gview_perm'] == 1) {
					if (!$is_qun_member) {
						$this->Messager("你不是当前微群的成员，无法查看", 'index.php?mod=qun&qid='.$topic_info['item_id']);
					}
				}
			}
		} else {
									if ($topic_info['type'] == 'reply') {
				$roottid = $topic_info['roottid'];

								if (empty($roottid)) {
					$root_type = 'reply';
				} else {
					$root_type = DB::result_first("SELECT type FROM ".DB::table('topic')." WHERE tid='{$roottid}'");
				}
			} else {
				$root_type = $topic_info['type'];
			}
			if (!$this->TopicLogic->check_view_perm($topic_info['uid'], $root_type)) {
				$this->Messager("你没有权限查看");
			}
		}


		$parent_list = $t_parent_list = $r_parent_list = array();
		if($topic_info['parent_id'])
		{
			$parent_id_list = array
			(
				$topic_info['parent_id'],
				$topic_info['top_parent_id'],
			);

			if($parent_id_list)
			{
				$t_parent_list = $this->TopicLogic->Get($parent_id_list);
			}
		}

		if ($topic_info['replys'] > 0) {

			  $_config = array(
				'return' => 'array',
			);

			$tids = $this->TopicLogic->GetReplyIds($topic_info['tid']);


            $total_record = $topic_info['replys'];
			$page_arr = page($total_record,$per_page_num,$query_link,$_config);

            if($tids)
            {
                                $condition = "where `tid` in ('".implode("','",$tids)."') order by `dateline` asc {$page_arr['limit']}";

    			$reply_list = $this->TopicLogic->Get($condition);


    			    			$parent_list = $r_parent_list = $this->TopicLogic->GetParentTopic($reply_list, 1);
            }
		}
		if($t_parent_list)
		{
			foreach($t_parent_list as $k=>$v)
			{
				if(!isset($parent_list[$k]))
				{
					$parent_list[$k] = $v;
				}
			}
		}


		if (MEMBER_ID > 0) {
			$sql = "select * from `".TABLE_PREFIX."topic_favorite` where `uid`='".MEMBER_ID."' and `tid`='{$topic_info['tid']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$is_favorite = $query->GetRow();
		}

		$member = $this->_member($topic_info['uid']);
        if(MEMBER_STYLE_THREE_TOL == 1)
        {
        	$my_member = $this->_member(MEMBER_ID);
        }

				$member_medal =  $my_member ? $my_member : $member;
		if($member_medal['medal_id'])
        {
            $medal_list = $this->_Medal($member_medal['medal_id'],$member_medal['uid']);
		}



		$this->Title = cut_str(strip_tags($topic_info['content']),50)." - {$member['nickname']}的微博";

				$Keywords = array();
		if(strpos($topic_info['content'],'#'))
		{
			preg_match_all('~\#([^\#\s\'\"\/\<\>\?\\\\]+?)\#~',strip_tags($topic_info['content']),$Keywords);
		}
		if(is_array($Keywords[1]) && count($Keywords[1]))
		{
			$this->MetaKeywords = implode(',',$Keywords[1]);
		}

		$this->MetaDescription = strip_tags($topic_info['content']);



        if(MEMBER_ID != $member['uid'])
        {
            $this->_initTheme($member);
        }


        $topic_view = 1;

		include($this->TemplateHandler->Template('topic_view'));
	}

	function Follow()
	{
		$member =  $this->_member();

		if (!$member) {
			$this->Messager("请先登录",'index.php?mod=login');
		}

		if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$my_member = $this->_member(MEMBER_ID);
		}



		    	$gid = intval(trim($this->Get['gid']));
		$keyword = $this->Post['nickname'] ? $this->Post['nickname'] : $this->Get['nickname'];

				$per_page_num = empty($this->ShowConfig['topic']['follow']) ? 10 : $this->ShowConfig['topic']['follow'];
		$gets = array(
			'mod' => $_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module,
			'code' => $this->Code,
			'gid' => $this->Get['gid'],
			'nickname' => $this->Get['nickname'],
			'type' => $this->Get['type'],
		);
		$page_url = "index.php?".url_implode($gets);


				$orderBy = ' ORDER BY ';
        if("fans_count" == $this->Get['type']){
    		$orderBy .= " m.`fans_count` DESC ";
    	} else if ("lastpost" == $this->Get['type']) {
    		$orderBy .= " m.`lastpost` DESC ";
    	} else {
    		$orderBy .= " b.`dateline` DESC ";
    	}

    	$member_list = $uids = array();


    	if (!empty($keyword)) {
	    	if (strlen($keyword) < 2) {
				$this->Messager("请输入至少3个字节的关键词", -1);
			}

			$search_sql = build_like_query("m.`nickname`", $keyword);
			$where_sql = " b.uid=".MEMBER_ID." AND {$search_sql} ";
			$count = DB::result_first("SELECT COUNT(*)
									   FROM ".DB::table('buddys')." AS b
									   LEFT JOIN ".DB::table('members')." AS m
									   ON b.buddyid=m.uid
									   WHERE {$where_sql}");
			if ($count > 0) {
				$page_arr = page($count, $per_page_num, $page_url, array('return' => 'array'));
				$query = DB::query("SELECT b.remark,m.*
									FROM ".DB::table('buddys')." AS b
									LEFT JOIN ".DB::table('members')." AS m
									ON b.buddyid=m.uid
									WHERE {$where_sql}
									{$orderBy}
									{$page_arr['limit']} ");
				while ($row = DB::fetch($query)) {
					$member_list[$row['uid']] = $this->TopicLogic->MakeMember($row);
					$uids[] = $row['uid'];
				}
			}
    	} else if($gid) {

    					$group_view = DB::fetch_first("SELECT *
										   FROM ".DB::table("group")."
										   WHERE `id`='{$gid}' AND uid='".MEMBER_ID."'");
			if (empty($group_view)) {
				$this->Messager("这个不是你的分组，你不能查看", -1);
			}

						$count = DB::result_first("SELECT COUNT(*)
									   FROM ".DB::table('groupfields')."
									   WHERE gid='{$gid}' AND `uid` = '".MEMBER_ID."' ");
			if ($count > 0) {
				$page_arr = page($count, $per_page_num, $page_url, array('return' => 'array'));
								$sql = "SELECT m.*,b.remark
						FROM ".DB::table('groupfields')." AS g
						LEFT JOIN ".DB::table('members')." AS m
						ON m.`uid` = g.`touid`
						LEFT JOIN  ".DB::table('buddys')." AS b
						ON b.`buddyid` = m.`uid`
						WHERE g.`gid`='{$gid}' AND  b.uid=".MEMBER_ID."
						{$orderBy}
						{$page_arr['limit']}";
				$query = DB::query($sql);
				while ($row = DB::fetch($query)) {
					$member_list[$row['uid']] = $this->TopicLogic->MakeMember($row);
					$uids[] = $row['uid'];
				}
			}
    	} else {

						$count = $member['follow_count'];
			if ($count > 0) {
							 	$page_arr = page($count, $per_page_num, $page_url, array('return' => 'array'));

			 					$sql = "SELECT b.remark,m.*
						FROM ".DB::table('buddys')." AS b
						LEFT JOIN ".DB::table('members')." AS m
						ON m.`uid` = b.`buddyid`
						WHERE b.`uid`='{$member['uid']}'
						{$orderBy}
						{$page_arr['limit']}";
				$query = DB::query($sql);
				while ($row = $query->GetRow()) {
					$member_list[$row['uid']] = $this->TopicLogic->MakeMember($row);
					$uids[] = $row['uid'];
				}
			}
    	}

		if($uids) {
						$sql = "SELECT `id`,`uid`,`buddyid`
					FROM ".DB::table('buddys')." WHERE `uid` IN (".jimplode($uids).")";
			$query = DB::query($sql);
			$buddys_list = array();
			while ($row = DB::fetch($query)) {
				$buddys_list[] = $row;
			}

			$buddys = array();
			if(MEMBER_ID > 0) {
				$sql = "SELECT `buddyid` AS `id`,`remark`
						FROM ".DB::table('buddys')."
						WHERE `uid`='".MEMBER_ID."' AND `buddyid` IN(".jimplode($uids).")";
				$query = DB::query($sql);
				while ($row = DB::fetch($query)) {
					$buddys[$row['id']] = $row['id'];
				}
			}

			foreach ($member_list as $key => $m) {
				$member_list[$key]['follow_html'] = follow_html($m['uid'], isset($buddys[$m['uid']]));
			}
		}

				$sql = "SELECT  GF.touid , GF.gid, GF.g_name , GF.display ,G.group_name ,G.id , GF.*
				FROM ".DB::table('group')." AS G
				LEFT JOIN ".DB::table('groupfields')." AS GF
				ON G.id=GF.gid
				WHERE G.uid='".MEMBER_ID." ' ";
		$query = DB::query($sql);
		$user_group = array();
		while ($row = DB::fetch($query)) {
			$user_group[$row['id']] = $row;
		}

		        $group_list = $grouplist2 = array();
		$group_list = $this->_myGroup($member['uid']);
        if($group_list) $grouplist2 = array_slice($group_list,0,min(4,count($group_list)));

				$member_medal = $my_member ? $my_member : $member;
		if ($member_medal['medal_id']) {
			$medal_list = $this->_Medal($member_medal['medal_id'],$member_medal['uid']);
		}

		$this->Title = "{$member['nickname']}关注的微博";

		include($this->TemplateHandler->Template('topic_follow'));

	}

	function ViewGroup()
	{
		$member = $this->_member();
		if (!$member) {
			$this->Messager("请先登录",'index.php?mod=login');
		}
				$gid = (int) $this->Get['gid'];

				$sql = "select * from `".TABLE_PREFIX."group` where `id`='{$gid}' limit 0,1";
		$query = $this->DatabaseHandler->Query($sql);
		$group_view = $query->GetRow();


				  $_config = array(
			'return' => 'array',
		);
			$per_page_num = 12;
			$page_arr = page($member['follow_count'],$per_page_num,"index.php?mod=topic&code=group&gid={$gid}",$_config);

			$buddysList = array();
			$sql = "select `buddyid` as id from `".TABLE_PREFIX."buddys` where `uid`='{$member['uid']}' order by `dateline` desc {$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['id']] = $row['id'];
			}


			$buddys = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`nickname`");
			foreach ($uids as $uid) {
				if(isset($buddys[$uid])) {
					$buddysList[$uid] = $buddys[$uid];
				}
			}


		     	$group_list = $grouplist2 = array();
		$group_list = $this->_myGroup($member['uid']);
    	if($group_list) $grouplist2 = array_slice($group_list,0,min(4,count($group_list)));


		include($this->TemplateHandler->Template('topic_group'));
	}

	function Fans()
	{
				if (MEMBER_ID < 1) {
					}

		$member = $this->_member();


		if (!$member) {
			$this->Messager("链接错误，请检查",null);
		}

		if(MEMBER_STYLE_THREE_TOL == 1)
		{
			$my_member = $this->_member(MEMBER_ID);
		}

				$per_page_num = $this->ShowConfig['topic']['fans'];

				if ($member['uid']==MEMBER_ID && $member['fans_new']>0) {
			$sql = "update `".TABLE_PREFIX."members` set `fans_new`=0 where `uid`='{$member['uid']}'";
			$this->DatabaseHandler->Query($sql);

			$this->MemberHandler->MemberFields['fans_new'] = 0;
		}

	    		$orderBy = $orderBy2 = '';
        if("fans_count" == $this->Get['type']){
    		$orderBy = " order by m.`fans_count` DESC ,id ";
    	} else if ("lastpost" == $this->Get['type']){
    		$orderBy = " order by m.`lastpost` DESC ,id ";
    	} else {
    		$orderBy = " order by b.dateline DESC ";
    	}

    	    	$gid = intval(trim($this->Get['gid']));
    	$keyword = trim($this->Post['nickname'] ? $this->Post['nickname'] : $this->Get['nickname']);

    	    	if($keyword)
    	{
			if (strlen($keyword) < 2) {
				$this->Messager("请输入至少3个字符的关键词",-1);
			}

			    		$query = DB::query("SELECT * FROM ".DB::table('buddys')." WHERE buddyid='".MEMBER_ID."' ");
    		$fans_uid = array();
			while ($row = DB::fetch($query)) {
				$fans_uid[$row['uid']] = $row['uid'];
			}
			if(empty($fans_uid)){
				$this->Messager("暂时还没有人关注你",-1);
			}

						$where_list['keyword'] =  build_like_query("`nickname`",$keyword);
			$where = (empty($where_list)) ? null : " WHERE ".implode(' AND ',$where_list)." and `uid` in ('" . implode("','", $fans_uid) . "')";

			    		$page_url = "index.php?mod={$member['username']}&code=fans&nickname=".$keyword;

			$_config = array(
				'return' => 'array',
			);

						$count = DB::result_first("SELECT count(*) FROM ".DB::table('members')." {$where} ");
			$page_arr = page($count, $per_page_num, $page_url, $_config);

			$query = DB::query("SELECT * FROM ".DB::table('members')." {$where} {$page_arr['limit']}");
			$uids = array();
			while ($row = DB::fetch($query)) {
				$uids[$row['uid']] = $row['uid'];
			}
    	}
    			elseif($gid)
		{
						$username = $member['username'];
			$fans_group = DB::fetch_first("SELECT * FROM ".DB::table('fans_group')." WHERE gid='{$gid}'");
			if (empty($fans_group)) {
				$this->Messager('当前分组不存在或或者已经被删除', "index.php?mod={$username}&code=fans");
			}
			if ($fans_group['uid'] != MEMBER_ID) {
				$this->Messager('你没有权限此操作', "index.php?mod={$username}&code=fans");
			}

						$uid = MEMBER_ID;

						$orderType = $this->Get['type'] ? '&type='.$this->Get['type'] : "";

			$page_url = "index.php?mod={$member['username']}&code=fans&gid={$gid}".$orderType;
			$_config = array(
				'return' => 'array',
			);

			$where_sql = " f.gid='{$gid}' AND f.uid='{$uid}' ";

						$count = DB::result_first("SELECT count(*) FROM ".DB::table('fans_group_fields')." f where {$where_sql} ");
						$page_arr = page($count, $per_page_num, $page_url, $_config);

			$query = DB::query("SELECT f.`touid` FROM ".DB::table('fans_group_fields')." f LEFT JOIN ".DB::table('members')." m ON m.`uid` = f.`touid` WHERE {$where_sql}" . $orderBy ."{$page_arr['limit']}");
			$uids = array();
			while ($value = DB::fetch($query))
			{
				$uids[$value['touid']] = $value['touid'];
			}

		}
		else
		{

			$page_url = "index.php?mod={$member['username']}&code=fans";

			$_config = array(
				'return' => 'array',
			);

						$count = DB::result_first("SELECT count(*) FROM ".DB::table('buddys')." where `buddyid`='{$member['uid']}' ");

						$page_arr = page($count, $per_page_num, $page_url, $_config);

			$sql = "select b.`uid` as id from `".TABLE_PREFIX."buddys` b left join `".TABLE_PREFIX."members` m on m.`uid` = b.`uid` where b.`buddyid`='{$member['uid']}' ". $orderBy ."{$page_arr['limit']}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['id']] = $row['id'];
			}
		}

		$member_list = array();
		if($uids) {
			$buddys = array();
			$sql = "select `buddyid` as `id`,remark
					from `".TABLE_PREFIX."buddys`
					where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
			$query = $this->DatabaseHandler->Query($sql);
			$remarks = array();
			while ($row = $query->GetRow())
			{
				$buddys[$row['id']] = $row['id'];
				$remarks[$row['id']] = $row['remark'];
			}

			$_list = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`province`,`city`,`fans_count`,`topic_count`,`validate`,`gender`,`face`,`nickname`,`level`");
			foreach ($uids as $uid) {
				if(isset($_list[$uid])) {

					$_list[$uid]['follow_html'] = follow_html($uid,isset($buddys[$uid]));
					$_list[$uid]['group'] = array();
					$_list[$uid]['remark'] = $remarks[$uid];
					$member_list[$uid] = $_list[$uid];
				}
			}
		}

				$member_medal = $my_member ? $my_member : $member;
		if ($member_medal['medal_id']) {
			$medal_list = $this->_Medal($member_medal['medal_id'],$member_medal['uid']);
		}

		$this->Title = "关注{$member['nickname']}的人";

		include($this->TemplateHandler->Template('topic_fans'));
	}



	function Top()
	{
		if(MEMBER_ID > 0) {
			$member = $this->TopicLogic->GetMember(MEMBER_ID);

						if ($member['medal_id']) {
				$medal_list = $this->_Medal($member['medal_id'],$member['uid']);
			}
		}


		$limit = $this->ShowConfig['topic_top']['guanzhu'];
		if($limit>0 && false === ($r_users = cache("misc/top_users-{$limit}",$this->CacheConfig['topic_top']['guanzhu']))) {
			$r_users = $this->TopicLogic->GetMember("where face!='' order by `fans_count` desc limit {$limit}","`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");

			cache($r_users);
		}

				$limit = $this->ShowConfig['topic_top']['renqi'];

		if ($limit>0 && false == ($day7_r_buddys = cache("misc/top_day7_r_buddys",$this->CacheConfig['topic_top']['renqi']))) {
			$day7_r_buddys = array();

						$sql = "select DISTINCT(B.buddyid) AS buddyid , COUNT(B.uid) AS count  from `".TABLE_PREFIX."buddys` B left join `".TABLE_PREFIX."members` M on B.buddyid=M.uid WHERE B.dateline>='".(time() - 86400 * 7)."' and M.face!='' GROUP BY buddyid ORDER BY count DESC LIMIT {$limit}";

			$query = $this->DatabaseHandler->Query($sql);
			$uids = $_ids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['buddyid']] = $row['buddyid'];
				$_ids[$row['buddyid']] = $row;
			}


			if($_ids) {
				$_list = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");

				foreach ($_ids as $id=>$row) {
					$row['uid'] = $_list[$id]['uid'];
					$row['username'] = $_list[$id]['username'];
					$row['nickname'] = $_list[$id]['nickname'];
					$row['face'] = $_list[$id]['face'];
					$row['from_area'] = $_list[$id]['from_area'];
					$row['validate_html'] = $_list[$id]['validate_html'];
					if(isset($_list[$id])) {
						$day7_r_buddys[$id] = $row;
					}
				}
			}
			cache($day7_r_buddys);
		}

				$limit = $this->ShowConfig['topic_top']['huoyue'];
		if ($limit>0 && false == ($day7_r_users = cache("misc/top_day7_r_users",$this->CacheConfig['topic_top']['huoyue']))) {
						$sql = "select DISTINCT(T.username) AS username , T.uid AS uid , COUNT(T.tid) AS count from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.uid=M.uid WHERE T.dateline>='".(time() - 86400 * 7)."' and M.face!='' GROUP BY username ORDER BY count DESC LIMIT {$limit}";

			$query = $this->DatabaseHandler->Query($sql);
			$uids = $day7_r_users = array();
			while ($row = $query->GetRow())
			{
				$day7_r_users[$row['username']] = $row;
				$uids[$row['username']] = $row['username'];
				$buids[$row['uid']] = $row['uid'];
			}

			if ($day7_r_users) {
			$members = $this->TopicLogic->GetMember("where `username` in ('".implode("','",$uids)."') limit {$limit}","`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");

				foreach ($members as $_m) {
					$day7_r_users[$_m['username']]['validate_html'] = $_m['validate_html'];
					$day7_r_users[$_m['username']]['face'] = $_m['face'];
					$day7_r_users[$_m['username']]['uid'] = $_m['uid'];
					$day7_r_users[$_m['username']]['from_area'] = $_m['from_area'];
					$day7_r_users[$_m['username']]['nickname'] = $_m['nickname'];
				}
			}
			cache($day7_r_users);
		}

				$limit = $this->ShowConfig['topic_top']['yingxiang'];
		if ($limit>0 && false == ($day7_r_topics = cache("misc/top_day7_r_topics",$this->CacheConfig['topic_top']['yingxiang']))) {
						$sql = "select DISTINCT(T.tousername) AS username ,  COUNT(T.tid) AS count, M.face ,M.username from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.tousername=M.username WHERE M.face !='' and  T.dateline>='".(time() - 86400 * 7)."' and T.touid > 0  GROUP BY tousername ORDER BY count DESC LIMIT {$limit}";

			$query = $this->DatabaseHandler->Query($sql);
			$uids = $day7_r_topics = array();
			while ($row = $query->GetRow())
			{
				$day7_r_topics[$row['username']] = $row;
				$uids[$row['username']] = $row['username'];

			}
			if ($day7_r_topics) {
				$members = $this->TopicLogic->GetMember("where `face` !='' and `username` in ('".implode("','",$uids)."') limit {$limit}","`uid`,`ucuid`,`username`,`fans_count`,`validate`,`province`,`city`,`face`,`nickname`");

					foreach ($members as $_m) {
						$day7_r_topics[$_m['username']]['validate_html'] = $_m['validate_html'];
						$day7_r_topics[$_m['username']]['face'] = $_m['face'];
						$day7_r_topics[$_m['username']]['uid'] = $_m['uid'];
						$day7_r_topics[$_m['username']]['from_area'] = $_m['from_area'];
						$day7_r_topics[$_m['username']]['nickname'] = $_m['nickname'];
					}

			}

			cache($day7_r_topics);
		}

		$this->Title = "人气用户推荐";
		include($this->TemplateHandler->Template('topic_top'));
	}

	function Hot()
	{
		if(MEMBER_ID > 0) {
			$member = $this->TopicLogic->GetMember(MEMBER_ID);
						if ($member['medal_id']) {
				$medal_list = $this->TopicLogic->GetMedal($member['medal_id'],$member['uid']);
			}
		}



				Load::logic("topic_list");
		$TopicListLogic = new TopicListLogic();

		if('hotforward' == $this->Code)
		{
			$title = '热门转发';

			$d_list = array(1=>'近一天',7=>'近一周',14=>'近两周',30=>'近一月',);
			$d = isset($d_list[$this->Get['d']]) ? $this->Get['d'] : 7;
			$time = $d * 86400;
		  	$dateline = time() - $time;

						$per_page_num = $this->ShowConfig['topic_hot']["day{$d}"];
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}&d={$d}" : "");

						$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."topic` Where `type`='first' and `forwards` > 0 and  dateline >= $dateline";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

			$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));

						$limit = $this->ShowConfig['topic_hot']["day{$d}"];

			$condition = " where  `type`='first' and `forwards` > 0 and  dateline >= $dateline order by `forwards` DESC , `dateline` DESC {$page_arr['limit']}";

			$cache_time = ($this->CacheConfig['topic_hot']["day{$d}"] ? $this->CacheConfig['topic_hot']["day{$d}"] : $time / 90);


			$topics = $this->TopicLogic->Get($condition);

		} elseif('hotreply' == $this->Code) {

			$title = '热门评论';

			$d_list = array(1=>'近一天',7=>'近一周',14=>'近两周',30=>'近一月',);
			$d = isset($d_list[$this->Get['d']]) ? $this->Get['d'] : 7;
			$time = $d * 86400;
		  	$dateline = time() - $time;

						$per_page_num = $this->ShowConfig['reply_hot']["day{$d}"];
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}&d={$d}" : "");
			$options = array(
				'perpage' => $per_page_num,
				'page_url' => $query_link,
				'type' => 'first',
				'where' => " `replys` > 0 AND  dateline >= {$dateline} ",
				'order' => " `replys` DESC , `dateline` DESC ",
			);
			$info = $TopicListLogic->get_data($options);
			if (!empty($info)) {
				$page_arr = $info['page'];
				$topics = $info['list'];
				$total_record = $info['count'];
			}



		} elseif ('new' == $this->Code) {

			$title = '最新微博';

						$per_page_num = $this->ShowConfig['topic_new']['topic'];
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");





			if($this->ShowConfig['topic_new']['topic'] > 0) {
				$options = array(
					'type' => get_topic_type(),
					'page_url' => $query_link,
					'perpage' => $per_page_num,
					'order' => ' dateline DESC ',
				);
				$info = $TopicListLogic->get_data($options);
				$topics = array();
				$total_record = 0;
				$page_arr = array();
				if (!empty($info)) {
					$topics = $info['list'];
					$total_record = $info['count'];
					$page_arr = $info['page'];
				}
			}

		}
				elseif('newreply' == $this->Code)
		{
			$title = '最新评论';

		  			$per_page_num = $this->ShowConfig['new_reply']['reply'];
			$query_link = "index.php?mod=" . ($_GET['mod_original'] ? get_safe_code($_GET['mod_original']) : $this->Module) . ($this->Code ? "&amp;code={$this->Code}" : "");

						$sql = " select count(*) as `total_record` from `".TABLE_PREFIX."topic` Where `replys` > 0 ";
			$query = $this->DatabaseHandler->Query($sql);
			extract($query->GetRow());

		  	$page_arr = page ($total_record,$per_page_num,$query_link,array('return'=>'array',));

			$condition = " where  `type` IN ('reply','both') order by `dateline` DESC {$page_arr['limit']}";

			$topics = $this->TopicLogic->Get($condition);
		}

				if ('forward' != $this->Code) {
			$parent_id_list = array();
			if ($topics) {
				foreach ($topics as $row) {
					if(0 < ($p = (int) $row['parent_id'])) {
						$parent_id_list[$row['tid']] = $p;
					}
					if (0 < ($p = (int) $row['top_parent_id'])) {
						$parent_id_list[$row['tid']] = $p;
					}
				}
			}

			if($parent_id_list) {
								$pcount = count($parent_id_list);
				$options = array(
					'tid' => $parent_id_list,
					'type' => get_topic_type(),
					'limit' => $pcount,
				);

				$p_info = $TopicListLogic->get_data($options);


						$Gz_limit = $this->ShowConfig['topic_index']['guanzhu'];
			$concern_users = $this->TopicLogic->GetMember("order by `fans_count` desc limit {$Gz_limit}","`uid`,`ucuid`,`username`,`face_url`,`face`,`fans_count`,`validate`,`nickname`");


			$Tg_limit = $this->ShowConfig['topic_new']['tag'];
			$Tg_date  = $this->CacheConfig['topic_new']['day_tag'];

			$dateline = time() - $Tg_date;
			$sql = "select `id`,`name`,`topic_count`,`dateline` from `".TABLE_PREFIX."tag` where `dateline` > '{$dateline}' order by `topic_count` desc limit {$Tg_limit}";
		    $query = $this->DatabaseHandler->Query($sql);
			$tags = $query->GetAll();

				$parent_list = array();
				if (!empty($p_info)) {
					$parent_list = $p_info['list'];
					$keys = array_keys($parent_list);
				} else {
					$keys = array();
				}


								foreach ($parent_id_list as $key => $val) {
					if (in_array($val, $keys)) {
						continue;
					}
					unset($topics[$key]);
				}
			}
		}

				$Gz_limit = $this->ShowConfig['topic_index']['guanzhu'];
		$concern_users = $this->TopicLogic->GetMember("order by `fans_count` desc limit {$Gz_limit}","`uid`,`ucuid`,`username`,`face_url`,`face`,`fans_count`,`validate`,`nickname`");


		$Tg_limit = $this->ShowConfig['topic_new']['tag'];
		$Tg_date  = $this->CacheConfig['topic_new']['day_tag'];

		$dateline = time() - $Tg_date;
		$sql = "select `id`,`name`,`topic_count`,`dateline` from `".TABLE_PREFIX."tag` where `dateline` > '{$dateline}' order by `topic_count` desc limit {$Tg_limit}";
	  	$query = $this->DatabaseHandler->Query($sql);
		$tags = $query->GetAll();


		$this->Title = $title;

		include($this->TemplateHandler->Template('topic_new'));

	}


	function _member($uid=0)
	{
		$member = array();
		if($uid < 1)
        {
			$mod_original = ($this->Post['mod_original'] ? $this->Post['mod_original'] : $this->Get['mod_original']);
			if($mod_original)
			{
				$mod_original = getSafeCode($mod_original);
				$condition = "where `username`='{$mod_original}' limit 1";

				$members = $this->TopicLogic->GetMember($condition);
				if(is_array($members))
                {
					reset($members);
					$member = current($members);
				}
			}
		}

		$uid = (int) ($uid ? $uid : MEMBER_ID);
		if($uid > 0 && !$member)
        {
			$member = $this->TopicLogic->GetMember($uid);
		}
		if(!$member)
        {
			return false;
		}
		$uid = $member['uid'];

		if (!$member['follow_html'] && $uid!=MEMBER_ID && MEMBER_ID>0)
        {
			$sql = "select * from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid`='{$uid}'";
			$query = $this->DatabaseHandler->Query($sql);
			$member['follow_html'] = follow_html($member['uid'],$query->GetNumRows()>0);
		}

                if(true === UCENTER_FACE && MEMBER_ID == $uid && MEMBER_UCUID > 0 && !($member['__face__']))
        {
            include_once(ROOT_PATH . 'uc_client/client.php');

            $uc_check_result = uc_check_avatar(MEMBER_UCUID);

            if($uc_check_result)
            {
                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."members set `face`='./images/no.gif' where `uid`='{$uid}'");
            }
        }

		return $member;
	}


	function _followList($uid,$num=6) {

        $uid = max(0,(int) $uid);

		$member_list = array();
		if($uid)
        {
						$sql = "select `buddyid` as `id` from `".TABLE_PREFIX."buddys` where `uid`='{$uid}' order by `id` desc limit {$num}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['id']] = $row['id'];
			}

			if($uids)
			{
					        	$sql = "select `buddyid` as `id`,remark from `".TABLE_PREFIX."buddys` where `uid`='".MEMBER_ID."' and `buddyid` in(".implode(",",$uids).")";
				$query = $this->DatabaseHandler->Query($sql);
				$buddys = array();
				while ($row = $query->GetRow())
				{
					$buddys[$row['id']] = $row['id'];
				}

					    		$_list = $this->TopicLogic->GetMember($uids);

	    		foreach ($uids as $_uid)
	            {
	    			if(isset($_list[$_uid]))
	                {
	    				$_list[$_uid]['follow_html'] = follow_html2($_uid,isset($buddys[$_uid]));

	    			    $member_list[$_uid] = $_list[$_uid];
	    		    }
	            }
			}
        }

		return $member_list;
	}

	function _fansList($uid,$num=6) {
        $uid = max(0,(int) $uid);

        $member_list = array();


        if($uid > 0)
        {
            $sql = "select `uid` from `".TABLE_PREFIX."buddys` where `buddyid`='{$uid}' order by `id` desc limit {$num}";
    		$query = $this->DatabaseHandler->Query($sql);
    		$ids = array();

    		while ($row = $query->GetRow())
    		{
    			$id = $row['uid'];

    			$ids[$id] = $id;
    		}

            if($ids)
            {
                $_list = $this->TopicLogic->GetMember($ids);

        		foreach ($ids as $id) {
        			if($id > 0 && isset($_list[$id])) {
        				$member_list[$id] = $_list[$id];
        			}
        		}
            }
        }

		return $member_list;
	}

	function _recommendTag($day=1,$limit=12,$cache_time=0)
	{
		if($limit < 1) return false;

		$time = $day * 86400;
		$cache_time = ($cache_time ? $cache_time : $time / 90);

		if (false === ($list = cache("misc/recommendTopicTag-{$day}-{$limit}",$cache_time))) {
			$dateline = time() - $time;
			$sql = "SELECT DISTINCT(tag_id) AS tag_id, COUNT(item_id) AS item_id_count FROM `".TABLE_PREFIX."topic_tag` WHERE dateline>=$dateline GROUP BY tag_id ORDER BY item_id_count DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$ids = array();
			while ($row = $query->GetRow())
			{
				$ids[$row['tag_id']] = $row['tag_id'];
			}
			$list = array();
			if($ids) {
				$sql = "select `id`,`name`,`topic_count` from `".TABLE_PREFIX."tag` where `id` in('".implode("','",$ids)."')";
				$query = $this->DatabaseHandler->Query($sql);
				$list = $query->GetAll();
			}

			cache($list);
		}

		return $list;

	}

	function _recommendUser($day=1,$limit=12,$cache_time=0)
	{
		if($limit < 1) return false;

		$time = $day * 86400;
		$cache_time = ($cache_time ? $cache_time : $time / 90);

		if (false === ($list = cache("misc/recommendTopicUser-{$day}-{$limit}",$cache_time))) {
			$dateline = time() - $time;
						$sql = "select DISTINCT(T.uid) AS uid , COUNT(T.tid) AS topics from `".TABLE_PREFIX."topic` T left join `".TABLE_PREFIX."members` M on T.uid=M.uid WHERE T.dateline>=$dateline and M.face!='' GROUP BY uid ORDER BY topics DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$uids = array();
			while ($row = $query->GetRow())
			{
				$uids[$row['uid']] = $row['uid'];
			}
			$list = array();
			if($uids) {
				$_list = $this->TopicLogic->GetMember($uids,"`uid`,`ucuid`,`username`,`face_url`,`face`,`aboutme`,`validate`,`nickname`");
				foreach ($uids as $uid) {
					if ($uid > 0 && isset($_list[$uid])) {
						$list[$uid] = $_list[$uid];
					}
				}
			}
			cache($list);
		}

		return $list;
	}

	function _recommendTopic($day = 1,$limit=12,$cache_time=0)
	{
		if($limit < 1) return false;

		$time = $day * 86400;
		$cache_time = ($cache_time ? $cache_time : $time / 90);

		if (false === ($list = cache("misc/recommendTopic-{$day}-{$limit}",$cache_time))) {
			$dateline = time() - $time;
			$sql = "SELECT DISTINCT(roottid) AS item_id, COUNT(tid) AS `count` FROM `".TABLE_PREFIX."topic` WHERE dateline>=$dateline GROUP BY roottid ORDER BY `count` DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$list = $ids = array();
			while ($row = $query->GetRow())
			{
				if($row['item_id'] > 0) {
					$ids[$row['item_id']] = $row['item_id'];
				}
			}
			if($ids) {
				$list = $this->TopicLogic->Get(" where `tid` in ('".implode("','",$ids)."') order by `dateline` desc limit {$limit}");
			}

			cache($list);
		}

		return $list;
	}

	function _recommendBuddy($day = 20,$limit=12)
	{
		$time = $day * 86400;

		if (false === ($list = cache("misc/recommendBuddy-{$day}-{$limit}",$time / 90))) {
			$dateline = time() - $time;
			$sql = "SELECT DISTINCT(uid) AS item_id, COUNT(buddyid) AS `count` FROM `".TABLE_PREFIX."buddys` WHERE dateline>=$dateline GROUP BY uid ORDER BY `count` DESC LIMIT {$limit}";
			$query = $this->DatabaseHandler->Query($sql);
			$ids = array();
			while ($row = $query->GetRow())
			{
				$ids[$row['item_id']] = $row['item_id'];
			}
			if($ids) $_list = $this->TopicLogic->GetMember($ids);

			$list = array();
			if($_list) {
				foreach ($_list as $row) {
					$rs = array();
					$rs['username'] = $row['username'];
					$rs['face'] = $row['face'];
					$rs['fans_count'] = $row['fans_count'];

					$list[$row['uid']] = $rs;
				}
			}

			cache($list);
		}

		return $list;
	}


	function _myFavoriteTags($limit=12)
	{
		$uid = MEMBER_ID;

		$sql = "select * from `".TABLE_PREFIX."tag_favorite` where `uid`='{$uid}' order by `id` desc limit {$limit} ";
		$query = $this->DatabaseHandler->Query($sql);
		$list = $query->GetAll();

		return $list;
	}

	function _guestIndex()
	{
		if(MEMBER_ID > 0) {
			$member = $this->_member(MEMBER_ID);
		}

		$time_config = ConfigHandler::get('time');

				$limit = $this->ShowConfig['topic_index']['guanzhu'];
		if ($limit > 0) {
			if(false === ($r_users = cache("index/r_users",$this->CacheConfig['topic_index']['guanzhu']))) {
				$r_users = $this->TopicLogic->GetMember("where face !='' order by `fans_count` desc limit {$limit}","`uid`,`ucuid`,`username`,`face_url`,`face`,`fans_count`,`validate`,`nickname`");

				cache($r_users);
			}
		}

				$day2_r_users = $this->_recommendUser(7,$this->ShowConfig['topic_index']['new_user'],$this->CacheConfig['topic_index']['new_user']);


				$r_tags = $this->_recommendTag(2,$this->ShowConfig['topic_index']['hot_tag'],$this->CacheConfig['topic_index']['hot_tag']);

		define('QUERY_SAFE_DACTION_3', true);
		if ($this->ShowConfig['topic_index']['recommend_topic']) {
			if (false === ($recommend_topics = cache("index/recommend_topics",$this->CacheConfig['topic_index']['recommend_topic']))) {
												Load::logic("topic_list");
				$TopicListLogic = new TopicListLogic();
				$type_sql = jimplode(get_topic_type());
				$fields = " a.* ";
				$table = " ".DB::table("topic")." a,(SELECT uid, max(dateline) max_dateline FROM ".DB::table("topic")." WHERE type IN(".$type_sql.") GROUP BY uid) b";
				$where = "  WHERE a.uid = b.uid AND a.dateline = b.max_dateline AND a.type IN({$type_sql}) ORDER BY a.dateline DESC LIMIT {$this->ShowConfig['topic_index']['recommend_topic']}";

				$recommend_topics = $this->TopicLogic->Get($where, $fields, 'Make', $table);
				if ($recommend_topics) {
					cache($recommend_topics);
				}
			}
		}

		$recommend_count = count($recommend_topics);

				$parent_list = $this->TopicLogic->GetParentTopic($recommend_topics);

		        if (false===($list_notice = cache('misc/_guestIndex-list_notice',86400))) {
        	$sql="select `id`,`title` from ".TABLE_PREFIX.'notice'." order by `id` desc limit 5 ";
	    	$query = $this->DatabaseHandler->Query($sql);
	    	$list_notice = array();
	    	while ($row = $query->GetRow()) {
	    		$row['titles']	= $row['title'];
	    		$row['title'] 	= cutstr($row['title'],30);
	    		$list_notice[] 	= $row;
	    	}
			cache($list_notice);
        }

		$this->MetaKeywords = $this->Config['index_meta_keywords'];
		$this->MetaDescription = $this->Config['index_meta_description'];
		include($this->TemplateHandler->Template('topic_index_guest'));
	}

		function _myGroup($uid=0,$limit='')
	{

		$order = 'order by `group_count` desc';

		$sql="Select `id`,`group_name`,`group_count` From ".TABLE_PREFIX.'group'." where `uid` = '{$uid}' {$order} {$limit}";
		$query = $this->DatabaseHandler->Query($sql);
		$list = $query->GetAll();

		return $list;
	}

  	function _GroupFields($uid=0)
	{
        $list = array();

        if(MEMBER_ID > 0)
        {
            $sql="Select * From ".TABLE_PREFIX.'groupfields'." where `touid` = '{$uid}' and `uid` = '".MEMBER_ID."' order by `id` desc";

    		$query = $this->DatabaseHandler->Query($sql);
    		$list = $query->GetAll();
        }

		return $list;
	}

		function _myRemark()
	{
        $list = array();

        if(MEMBER_ID > 0)
        {
            $sql="SELECT * FROM ".TABLE_PREFIX.'buddys'." where `uid` =".MEMBER_ID."";
    		$query = $this->DatabaseHandler->Query($sql);
    		$list = $query->GetAll();
        }

		return $list;
	}

	function _Medal($medalid=0,$uid=0)
	{




        $uid = (is_numeric($uid) ? $uid : 0);

        $medal_list = array();

        if($uid > 0)
        {
            $sql = "select  U_MEDAL.dateline ,  MEDAL.medal_img , MEDAL.conditions , MEDAL.medal_name ,MEDAL.medal_depict ,MEDAL.id , U_MEDAL.* from `".TABLE_PREFIX."medal` MEDAL left join `".TABLE_PREFIX."user_medal` U_MEDAL on MEDAL.id=U_MEDAL.medalid where U_MEDAL.uid='{$uid}' ";

        	$query = $this->DatabaseHandler->Query($sql);
            while ($row = $query->GetRow())
            {
            	$row['dateline'] = date('m-d日 H:s ',$row['dateline']);
    			$medal_list[$row['id']] = $row;
            }
        }

		return $medal_list;
	}



}

?>
