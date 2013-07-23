<?php
/*********************************************************
 *文件名： image.logic.php
 *作  者： 狐狸<foxis@qq.com>
 *创建时间： 2010年6月22日
 *修改时间：
 *功能描述：微博图片逻辑操作
 *使用方法：

 ******************************************************/
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

/**
 * 
 * 微博图片的数据库逻辑操作类
 * 
 * @author 狐狸<foxis@qq.com>
 *
 */
class ImageLogic
{
	
	var $table;
	
	function ImageLogic()
	{
		$this->table = 'topic_image';	
	}
	
	
	function get($p)
	{
		$wheres = array();

		if(isset($p['id']))
		{
			$p['id'] = max(0, (int) $p['id']);
			if($p['id'] > 0) $wheres[] = " `id`='{$p['id']}' ";
		}
		if(isset($p['ids']))
		{
			$p['ids'] = $this->get_ids($p['ids'], 0);
			if($p['ids']) $wheres[] = " `id` in ({$p['ids']}) ";
		}
		if(isset($p['tid']))
		{
			$p['tid'] = max(0, (int) $p['tid']);
			$wheres[] = " `tid`='{$p['tid']}' ";
		}
		if(isset($p['tids']))
		{
			$p['tids'] = $this->get_ids($p['tids'], 0);
			if($p['tids']) $wheres[] = " `tid` in ({$p['tids']}) ";
		}
		if(isset($p['dateline_min']))
		{
			$p['dateline_min'] = max(0, (int) $p['dateline_min']);
			$wheres[] = " `dateline`>='{$p['dateline_min']}' ";
		}
		if(isset($p['dateline_max']))
		{
			$p['dateline_max'] = max(0, (int) $p['dateline_max']);
			$wheres[] = " `dateline`<='{$p['dateline_max']}' ";
		}
		if(isset($p['uid']))
		{
			$p['uid'] = max(0, (int) $p['uid']);
			$wheres[] = " `uid`='{$p['uid']}' ";
		}
		if(isset($p['uids']))
		{
			$p['uids'] = $this->get_ids($p['uids'], 0);
			if($p['uids']) $wheres[] = " `uid` in ({$p['uids']}) ";
		}
		
		$sql_where = ($wheres ? " where " . implode(" and ", $wheres) : "");
		
		$count = max(0, (int) $p['count']);
		if($count < 1)
		{
			$count = DB::result_first("select count(*) as `count` from ".DB::table($this->table)." $sql_where ");
		}
		$list = array();
		$page = array();		
		if($count > 0)
		{
			$sql_limit = '';
			if($p['per_page_num'])
			{
				$page = page($count, $p['per_page_num'], $p['page_url'], array('return' => 'Array'));
				
				$sql_limit = " {$page['limit']} ";
			}
			elseif($p['limit']) 
			{
				if(false !== strpos(strtolower($p['limit']), 'limit '))
				{
					$sql_limit = " {$p['limit']} ";
				}
				else 
				{
					$sql_limit = " limit {$p['limit']} ";
				}
			}
			
			$sql_order = '';
			if($p['order'])
			{
				if(false !== strpos(strtolower($p['order']), 'order by '))
				{
					$sql_order = " {$p['order']} ";
				}
				else 
				{
					$sql_order = " order by {$p['order']} ";
				}
			}
			
			$sql_fields = ($p['fields'] ? $p['fields'] : "*");
			
			$query = DB::query("select $sql_fields from ".DB::table($this->table)." $sql_where $sql_order $sql_limit ");
			while(false != ($r = DB::fetch($query)))
			{
				$list[] = $r;
			}
			
			if($list)
			{
				return array('count'=>$count, 'list'=>$list, 'page'=>$page);
			}
		}
		
		return array();
	}
		
	
	function get_info($id)
	{
		$id = max(0, (int) $id);
		if($id < 1) return array();

		$p = array(
			'id' => $id,
			'count' => 1,
		);
		$rets = $this->get($p);
		
		$ret = $rets['list'][0];
		
		return $ret;
	}
	
	
	function add($uid, $username = '')
	{
		$uid = is_numeric($uid) ? $uid : 0;
		if($uid < 1)
		{
			$uid = MEMBER_ID;
		}
		if($uid < 1) return 0;
		
		if(!$username)
		{
			$username = DB::result_first("select `username` from ".DB::table('members')." where `uid`='$uid'");
		}
		if(!$username) return 0;
		
		$arr = array(
			'uid' => $uid,
			'username' => $username,
			'dateline' => time(),
		);
		$ret = DB::insert($this->table, $arr, 1);
		
		return $ret;
	}
	
	
	function modify($p)
	{		
		$id = (is_numeric($p['id']) ? $p['id'] : 0);
		if($id < 1) return 0;
		
		$info = $this->get_info($id);
		if(!$info) return 0;
		
		$sets = array();
		
		$_int_fields = array('tid', 'filesize', 'width', 'height', 'uid', 'dateline', 'views');
		foreach($_int_fields as $_field)
		{
			if(isset($p[$_field]))
			{
				$sets[$_field] = (int) $p[$_field];
			}
		}
		
		$_str_fields = array('site_url', 'photo', 'name', 'description', 'username', 'image_url');
		foreach($_str_fields as $_field)
		{
			if(isset($p[$_field]))
			{
				$sets[$_field] = trim(strip_tags($p[$_field]));
			}
		}
		
		$ret = 0;
		
		if($sets)
		{
			$ret = DB::update($this->table, $sets, array('id' => $id));
			
						if(isset($sets['tid']))
			{
				$tid = $sets['tid'] ? $sets['tid'] : $info['tid'];
				
				$this->set_topic_imageid($tid);
			}
		}
		
		return $ret;
	}
	
	
	function delete($ids)
	{
		$p = array('ids' => $ids);
		$rets = $this->get($p);
		if(!$rets) return 0;
		
		Load::lib('io');
		$IoHandler = new IoHandler();
		
		$ret = 1;
		foreach($rets['list'] as $r)
		{
			$id = $r['id'];
			
			$IoHandler->DeleteFile(topic_image($id, 'small'));
			$IoHandler->DeleteFile(topic_image($id, 'original'));
			
			$ret = $ret && DB::query("delete from ".DB::table($this->table)." where `id`='$id'");
			
			if($r['tid'] > 0)
			{
				$this->set_topic_imageid($r['tid']);
			}
		}
		
		return $ret;
	}
	
	
	function set_tid($ids, $tid, $set_topic_imageid = 0)
	{
		$ids = $this->get_ids($ids);
		if(!$ids) return 0;
		
		$tid = max(0, (int) $tid);
		
		$ret = DB::query("update ".DB::table($this->table)." set `tid`='$tid' where `id` in ($ids)");
		
		if($tid > 0 && $set_topic_imageid)
		{
			$this->set_topic_imageid($tid);
		}
		
		return $ret;
	}
	
	
	function set_views($ids, $views)
	{
		$ids = $this->get_ids($ids);
		if(!$ids) return 0;
		
		$views = is_numeric($views) ? $views : 0;
		$sign = substr((string) $views, 0, 1);
		$views_set = " `views`=" . (('-' == $sign || '+' == $sign) ? "`views`{$sign}" : "$views") . " ";
		
		$ret = DB::query("update ".DB::table($this->table)." set $views_set where `id` in ($ids)");
		
		return $ret;
	}

	
	function get_ids($ids, $checks = array('uid' => -1, 'tid' => null), $ret_arr = 0)
    {    	
    	$_ids = array();
    	if(is_numeric($ids))
    	{
    		$_ids[$ids] = $ids;
    	}
    	elseif(is_string($ids))
    	{
    		$_rs = explode(',', $ids);
            foreach($_rs as $_r)
            {
                $_ids[$_r] = $_r;
            }
    	}
        else
        {
            if($ids)
            {
                $_ids = (array) $ids;
            }
        }
    	
        $ids = array();
        if($_ids)
        {
            foreach($_ids as $_r)
            {
            	$_r = trim($_r , ' ,"\'');
                $_r = is_numeric($_r) ? $_r : 0;
                if($_r > 0)
                {
                    $ids[$_r] = $_r;
                }
            }
        }
        
        if($ids && $checks)
        {        	
        	        	$_checks = array('uid' => 1, 'tid' => 0);
        	
        	if(is_numeric($checks))
        	{
        		$checks = array('uid' => $checks);
        		if($checks['uid'] >= $_checks['uid'])
        		{
        			$checks['tid'] = $_checks['tid'];
        		}
        	}
        	
        	$check_sql = '';
        	foreach($_checks as $k => $_v)
        	{
        		if(isset($checks[$k]))
        		{
        			$v = $checks[$k];
        			
        			if(is_numeric($v) && $v >= $_v)
        			{
        				$check_sql .= " and `$k`='$v' ";
        			}
        			elseif(is_string($v) && false !== strpos(" and ", strtolower($v)))
        			{
        				$check_sql .= " $v ";
        			}
        		}
        	}
        	
            $query = DB::query("select `id` from ".DB::table($this->table)." where `id` in ('".implode("','", $ids)."') $check_sql ");
            $rets = array();
            while(false != ($rs = DB::fetch($query)))
            {
                $rets[$rs['id']] = $rs['id'];
            }
            
            $ids = $rets;
        }
        
    	if($ret_arr)
        {
        	return $ids;
        }
        else
        {
            return implode(",", $ids);
        }
    }
    
    
    function clear_invalid($time = 300)
    {
    	$p = array(
    		'tid' => 0,
    	);
    	if($time)
    	{
    		$p['dateline_max'] = time() - $time;
    	}
    	
    	$rets = $this->get($p);
    	if(!$rets) return 0;
    	
    	$ids = array();
    	foreach($rets['list'] as $r)
    	{
    		$ids[] = $r['id'];
    	}
    	
    	return $this->delete($ids);
    }
    
    
    function set_topic_imageid($tid, $imageid = null)
    {
    	$tid = is_numeric($tid) ? $tid : 0;
    	if($tid < 1) return 0;
    	
    	if(!isset($imageid))
    	{
    		$imageids = array();
    		$p = array(
    			'tid' => $tid,
    		);
    		$rets = $this->get($p);
    		if($rets)
    		{
    			foreach($rets['list'] as $r)
    			{
    				$imageids[$r['id']] = $r['id'];
    			}
    		}
    		
    		$imageid = implode(",", $imageids);
    	}
    	else
    	{
    		$imageid = $this->get_ids($imageid);
    	}
    	
    	return DB::query("update ".DB::table('topic')." set `imageid`='$imageid' where `tid`='$tid'");
    }
    
    
    function image_list($ids)
    {
    	$ids = $this->get_ids($ids, 0, 1);
    	
    	$list = array();	
    	if($ids)
    	{
    		foreach($ids as $id)
    		{
    			$list[$id] = array(
    				'id' => $id,
    				'image_small' => topic_image($id, 'small', 0),
    				'image_original' => topic_image($id, 'original', 0),
    			); 
    		}
    	}
    	
    	return $list;
    }
	
}

?>