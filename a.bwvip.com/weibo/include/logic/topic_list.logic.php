<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename topic_list.logic.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:32 1598223354 134163932 12721 $
 *******************************************************************/




if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class TopicListLogic
{

    var $MemberHandler;


    var $Config;

    var $TopicLogic;


	function TopicListLogic()
	{
		$this->MemberHandler = &Obj::registry("MemberHandler");
		$this->Config = &Obj::registry("config");
		Load::logic("topic");
		$this->TopicLogic = new TopicLogic;
	}


	function _process_param($data)
	{
		$d = array();
		if (!empty($data)) {
			if (is_array($data)) {
				$d = (array) $data;
			} else {
				$d = explode(',', $data);
			}
		}
		return $d;
	}


	function filter($type = '', $alias = '')
	{
		$where = '';
		if ($type == 'pic') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `imageid` > 0 ";
		} else if($type == 'video') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `videoid` > 0 ";
		} else if($type == 'music') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `musicid` > 0 ";
		} else if ($type == 'vote') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `item`='vote' ";
		} else if ($type == 'event') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `item`='event' ";
		} else if ($type == 'fenlei') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `item`='fenlei' ";
		} else if ($type == 'longtext') {
			$where = (empty($alias) ? ' ' : $alias.'.')." `longtextid` > 0 ";
		}
		return $where;
	}


	function get_data($param, $caller = "web")
	{
		$where = $order = "";
		$tids = $uids = $usernames = $roottids = $totids = $touids = $tousernames = $types = $item_ids = array();
		$tids = $this->_process_param($param['tid']);
		$uids = $this->_process_param($param['uid']);
		$fuids = $this->_process_param($param['fuid']); //angf do it 为了调用别人给企业会员发的微博  以企业用户读取出 所有的企业下所有员工 或会员 的微博
		$content = trim($param['content']);
		$content2 = trim($param['content2']);
		$kw_content = trim($param['kw_content']);

		$filter = trim($param['filter']);
		$roottids = $this->_process_param($param['roottid']);
		$dateline = isset($param['dateline']) ? intval($param['dateline']) : 0;
		$lastupdate = isset($param['lastupdate']) ? intval($param['lastupdate']) : 0;
		$from = trim($param['from']);
		$types = $this->_process_param($param['type']);
		$item_ids = $this->_process_param($param['item_id']);
		$item = trim($param['item']);

				$sys_def_uid_flg = false;
		$param['my_uid'] = intval($param['my_uid']);
		$my_uid = $param['my_uid'];

				$perm_sql = '';
		if (empty($types)) {

			if (!empty($uids)) {
				$public_con = get_topic_type();
				//$perm_sql .= " uid IN(".jimplode($uids).") AND type IN(".jimplode($public_con).") ";//原来的读取
				$perm_sql .= " uid IN(".jimplode($uids).") OR fuid IN(". jimplode($fuids) .") AND type IN(" . jimplode($public_con) . ") ";//angf do it 2012/5/3

			}
		} else {
			$perm_sql = " type IN(".jimplode($types).") ";
			if (!empty($uids) && !$sys_def_uid_flg) {
				$perm_sql .= " AND uid IN(".jimplode($uids).") ";
			}
		}

		$filter_sql = $this->filter($filter);

		$where_sql = ($perm_sql ? " AND {$perm_sql} " : '').
					 (isset($param['tid']) ? ' AND tid IN ('.jimplode($tids).') ' : '').
					 ($roottids ? ' AND roottid IN ('.jimplode($roottids).') ' : '').
					 ($from ? " AND from='{$from}' " : '').
					 					 ($item_ids ? " AND item_id IN (".jimplode($item_ids).") " : '').
					 ($item ? " AND item='{$item}' " : '').
					 ($filter_sql ? " AND {$filter_sql} " : '').
					 ($content ? " AND content='{$content}' " : '').
					 ($content2 ? " AND content2='{$content2}' " : '');

				if ($dateline) {
			$where_sql .= " AND dateline>{$dateline} ";
		}

				if ($lastupdate) {
			$where_sql .= " AND lastupdate>{$lastupdate} ";
		}

		if ($kw_content) {
						$kw_content = $kw_content ? build_like_query('content,content2', $kw_content) : '';
			$where_sql .= " AND {$kw_content} ";
		}

				if (!empty($param['where'])) {
			$where_sql .= " AND {$param['where']} ";
		}

		if (!empty($param['order'])) {
			$order = " ORDER BY {$param['order']} ";
		} else {
			$order = " ORDER BY dateline DESC ";
		}


		$where_sql = " WHERE 1 {$where_sql} ";

				$sql = " select count(*) from ".DB::table("topic")." {$where_sql} ";
		$total_record = DB::result_first($sql);

				if ($total_record > 0) {

						$limit_sql = '';
			if ($param['perpage']) {
				if ($caller == 'web') {
					$page_arr = page($total_record, $param['perpage'], $param['page_url'], array('return'=>'array'));
				} else if ($caller == "wap") {
					$page_arr = wap_page($total_record, $param['perpage'], $param['page_url'], array('return'=>'array'));
				}
				$limit_sql = $page_arr['limit'];
			} else {
				if (!empty($param['limit'])) {
					if (strpos(strtolower($param['limit']), 'limit') !== false) {
						$limit_sql = " {$param['limit']} ";
					} else {
						$limit_sql = " LIMIT {$param['limit']} ";
					}
				}
			}

			$condition = " {$where_sql} {$order} {$limit_sql} ";


						$fields = isset($param['fields']) ? $param['fields'] : ' * ';

						$table = isset($param['table']) ? $param['table'] : '';

			$proc_func = isset($param['proc_func']) ? trim($param['proc_func']) : 'Make';

						$topic_list = $this->TopicLogic->Get($condition, $fields, $proc_func, $table);
			if (empty($topic_list)) {
				return false;
			}
			$list = array('list' => $topic_list, 'count' => $total_record);
			if (!empty($page_arr)) {
				$list['page'] = $page_arr;
			}
			return $list;
		}
		return false;
	}






	function get_recd_list($param)
	{
		$order_sql = ' tr.recd DESC,tr.display_order DESC,tr.dateline DESC  ';
		if (!empty($param['order_sql'])) {
			$order_sql = $param['order_sql'];
		}
		$where_sql = " tr.tid>0 ";
		if (!empty($param['where'])) {
			$where_sql .= " AND {$param['where']} ";
		}
		$where_sql .= " AND (tr.expiration>".TIMESTAMP." OR tr.expiration=0) ";

		$filter_sql = $this->filter($param['filter'], 't');

		$where_sql .= empty($filter_sql) ? '' : ' AND '.$filter_sql;

		$total_record = DB::result_first("SELECT COUNT(*)
								   		  FROM ".DB::table('topic')." AS t
								   		  LEFT JOIN ".DB::table('topic_recommend')." AS tr
								   		  USING(tid)
								   		  WHERE {$where_sql} ");
		$limit_sql = '';
		$topic_list = array();
		if ($total_record > 0) {
			if ($param['perpage']) {
				$page_arr = page($total_record, $param['perpage'], $param['page_url'], array('return'=>'array'));
				$limit_sql = $page_arr['limit'];
			} else {
				if ($param['limit']) {
					$limit_sql = ' LIMIT '.$param['limit'];
				}
			}
			$query = DB::query("SELECT t.*
								FROM ".DB::table('topic')." AS t
								LEFT JOIN ".DB::table('topic_recommend')." AS tr
								USING(tid)
								WHERE {$where_sql}
								ORDER BY {$order_sql}
								{$limit_sql} ");
			while ($value = DB::fetch($query)) {
				$topic_list[$value['tid']] = $this->TopicLogic->Make($value);
			}
			$info = array(
				'list' => $topic_list,
				'count' => $total_record,
				'page' => $page_arr,
			);
			return $info;
		}
		return false;
	}

}

?>