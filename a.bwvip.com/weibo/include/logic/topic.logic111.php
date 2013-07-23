<?php

/*********************************************************
*文件名：  topic.logic.php
*作者：狐狸<foxis@qq.com>
*创建时间：  2010年6月12日
*修改时间：
*功能描述： 话题模块相关的数据库操作
*使用方法：

******************************************************/

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class TopicLogic
{

    
    var $DatabaseHandler;
    
    
    var $MemberHandler;


    
    var $Config;

    
    var $_cache;
    
     
    var $_len = 280;
    
    
    var $ForwardSeprator;


    
    
    function TopicLogic($base = null)
    {
        if ($base)
        {
            $this->DatabaseHandler = &$base->DatabaseHandler;
            
            $this->MemberHandler = &$base->MemberHandler;

            $this->Config = &$base->Config;
        }
        else
        {
            $this->DatabaseHandler = &Obj::registry("DatabaseHandler");
            
            $this->MemberHandler = &Obj::registry("MemberHandler");

            $this->Config = &Obj::registry("config");
        }
        
        if($this->Config['topic_length'] > 0)
        {
            $this->_len = $this->Config['topic_length'] * 2;        }
        
        $this->ForwardSeprator = ' /'.'/@';
    }

        
    function Add($datas, $totid = 0, $imageid = 0, $from = 'web', $type = "first", $uid = 0, $item = '', $item_id = 0)
    {
        if(is_array($datas) && count($datas))
        {
                    	$ks = array(
        		        		'uid'=>1, 
        		        		'content'=>1, 
        		        		'imageid'=>1, 
        		'videoid'=>1, 
        		'musicid'=>1, 
        		'longtextid'=>1, 
        		        		        		        		'totid'=>1, 
        		'touid'=>1, 
        		        		'dateline'=>1, 
        		        		'from'=>1, 
        		'type'=>1, 
        		'item_id'=>1, 
        		'item'=>1,
        	
        		'timestamp'=>1,
        	);
			foreach($datas as $k=>$v)
			{
				if(isset($ks[$k]))
				{
					${$k} = $v;
				}
			}
        }
        else 
        {
        	$content = $datas;
        }
        
                $content = trim(strip_tags((string) $content));
        
                $content_length = strlen($content);
        
        if ($content_length < 2)
        {
            return "内容不允许为空";
        }
        elseif($content_length > $this->_len)
        {
            $content = cut_str($content, $this->_len, '');
        }
        
                if (false != ($filter_msg = filter($content)))
        {
            return $filter_msg;
        }
        
                $totid = max(0, (int)$totid);
        $data = array();
        $parents = '';
        
        
        $_froms = array(
        	'web' => 1, 
        	'wap' => 1, 
        	'qq' => 1, 
        	'msn' => 1, 
        	'mobile' => 1, 
        	'api' => 1,
        	'sina' => 1,
        	'vote'=>1,
        	'qun'=>1,
			'fenlei'=>1,
			'event'=>1,
        );
        $from = (($from && ($_froms[$from])) ? $from : 'web');         
                if (empty ($item) || $item_id < 0) 
        {
        	        	if (!is_numeric($type)) { 
	        	$_types = array('first' => 1, 'forward' => 1, 'reply' => 1, 'both' => 1);
	        		
	        	$type = (($totid < 1 && $type && isset($_types[$type])) ? 'first' :  $type);
				if (empty($type)) {
					$type = 'first';
				}
        	}
        }
        
        
        if ($item == 'qun' && $item_id > 0)
        {
        	$r = $this->is_qun_member($item_id, MEMBER_ID);
        	if (!$r) {
        		return "你没有权限进行当前操作";
        	}
        }
        
        $data['from'] = $from;         if (($type == 'forward' || $type == 'both')  && $item == 'qun') {
        	$data['type'] = $item;
        } else {
        	$data['type'] = $type;         }
        $data['uid'] = $uid = max(0, (int)($uid ? $uid : MEMBER_ID));
        $data['videoid'] = $videoid = max(0, (int)$videoid);
        $data['longtextid'] = $longtextid = max(0 , (int) $longtextid);        $data['dateline'] = $data['lastupdate'] = $timestamp = ($timestamp > 0 ? $timestamp : time());
        $data['totid'] = $totid;
                   
        
                $data['item'] = $item;
        $data['item_id'] = $item_id;

                $member = $this->GetMember($data['uid']);
        if(!$member)
        {
            return "用户不存在";
        }
        $data['username'] = $username = $member['username'];       
        
                
                if($this->MemberHandler && method_exists($this->MemberHandler,'HasPermission'))
        {
            if(!($this->MemberHandler->HasPermission('topic','add',0,$member)))
            {
                return($this->MemberHandler->GetError());
            }
            else
            {
                if(('reply'==$type || 'both'==$type) && !($this->MemberHandler->HasPermission('topic','reply',0,$member)))
                {
                    return($this->MemberHandler->GetError());
                }
                elseif(('forward'==$type || 'both'==$type) && !($this->MemberHandler->HasPermission('topic','forward',0,$member)))
                {
                    return($this->MemberHandler->GetError());
                }
            }
        }
        else
        {
                    }    
         

                if('sina'!=$data['from'] && (($timestamp - $member['lastpost']) < $this->Config['lastpost_time']))         {
                    }
            
                $topic_content_id = abs(crc32(md5($content)));
        
                
        
                if($imageid)
        {
        	Load::logic('image');
        	$ImageLogic = new ImageLogic();
        	
        	$data['imageid'] = $imageid = $ImageLogic->get_ids($imageid, $data['uid']);
        }
        
        
        $longtext_info = array();
        if($data['longtextid'] > 0)
        {
        	Load::logic('longtext');
        	$LongtextLogic = new LongtextLogic();
        	
        	$longtext_info = $LongtextLogic->get_info($data['longtextid']);
        	
        	if(!$longtext_info || $longtext_info['uid'] != $data['uid'] || $longtext_info['tid'] > 0)
        	{
        		return "微博长文本内容有误";
        	}
        }
        
                $topic_more = array();
        $parents = '';
        $data['roottid'] = 0;
        if ($totid > 0)
        {       
        	            $content = $this->GetForwardContent($content);

            
        	$_type_names = array('both'=>'转发和评论', 'forward'=>'转发', 'reply'=>'评论');
        	$_type_name = $_type_names[$type];
        	
        	$to_topic = $row = $this->Get($totid);
            if (!($to_topic))
            {
                return "对不起,由于原微博已删除,不能{$_type_name}";
            }
            $topic_more = $this->GetMore($totid);            
            
            	
            $data['totid'] = $row['tid'];
            $data['touid'] = $row['uid'];
            $data['tousername'] = $row['nickname'];
            $parents = ($topic_more['parents'] ? ($topic_more['parents'] . ',' . $totid) : $totid);
            $data['roottid'] = ($topic_more['parents'] ? substr($parents, 0, strpos($parents,
                ',')) : $totid);
            
            
            if($data['totid']!=$data['roottid'])
            {
            	$rrow = $this->Get($data['roottid']);
            	if(!$rrow)
            	{
            		return "对不起,由于原始微博已删除,不能{$_type_name}";
            	}            	
            	
            	            	if(('forward'==$type || 'both'==$type))
	            {
	            	$content .= $this->ForwardSeprator . "{$row['nickname']} : " . strip_tags($row['content']);
	            	
		            if(strlen($content) > $this->_len)
			        {
			            $content = cut_str($content, $this->_len, '');
			        }
	            }
            }
        }

                
        $_process_result = $this->_process_content($content);
        
        $content = $_process_result['content'];
        
        $at_uids = $_process_result['at_uids'];
        
        $tags = $_process_result['tags'];
        
        $urls = $_process_result['urls'];        
        
        $data['content'] = $content;
        
        if (strlen($content) > 255)
        {
            $content = cut_str($content, 254 * 2, '');
            
            $data['content'] = cut_str($content, 255, '');
            
            $data['content2'] = substr($content, strlen($data['content']));
        }
        
        
                
                $sql = "insert into `" . TABLE_PREFIX . "topic` (`" . implode("`,`", array_keys
            ($data)) . "`) values ('" . implode("','", $data) . "')";
        $this->DatabaseHandler->Query($sql);
        $tid = $this->DatabaseHandler->Insert_ID();
		//20120213徐玉枭添加微博动态	
		if($tid>1)
		{	
		 $subject=$data['content'];
		 
$fans='t/index.php?mod=topic&code='.$tid;
//$fansurl='home.php?mod=space&uid='.$uid.'&do=action&act=weibo&wburl='.base64_encode($fans);
$feed = array();
$feed['icon'] = 'topic';
$feed['title_template'] = '{actor} <a href="'.$fans.'">'.$subject.'</a>'; 

$feed['title_data']='<a href="t/index.php?mod=topic&code='.$tid.'">'.$subject.'</a>';
 
$feed['body_template'] =$_process_result['content'];
$feed['body_data'] = $feed['body_data'];
$feed['idtype'] = 'topicid';
$feed['id'] = $tid;

include_once(ROOT_PATH.'./uc_client/client.php');
uc_feed_add($feed['icon'], $data['uid'],  $data['username'], $feed['title_template'], $feed['title_data'], $feed['body_template'], $feed['body_data'], '', '',$feed['images']); 

	}
        if ($tid < 1)
        {
            return "未知的错误";
        }
        $topic_id = $data['tid'] = $tid;
        
                if (!empty($item) && $item_id > 0) {
	        Load::functions('app');
	        $param = array(
	        	'item' => $item,
	        	'item_id' => $item_id,
	        	'tid' => $tid,
	        );
	        app_add_relation($param);
	        unset($param);
        }        
        
                $this->DatabaseHandler->Query("insert into `" . TABLE_PREFIX . "topic_more`(`tid`,`parents`) values('{$tid}','{$parents}')");
        
                $this->DatabaseHandler->Query("update `" . TABLE_PREFIX . "members` set ".(($data['type'] != 'reply') ? "`topic_count` = `topic_count` + 1 ," : "")." `lastactivity`='{$data['lastupdate']}',`lastpost`='{$data['lastupdate']}',`last_topic_content_id`='{$topic_content_id}' where `uid`='{$data['uid']}'");
        if('reply' != $data['type'])
        {
            $this->DatabaseHandler->Query("update `".TABLE_PREFIX."buddys` set `buddy_lastuptime`='{$data['lastupdate']}' where `buddyid`='{$data['uid']}'");
        }
		

                if ($data['type'] == 'both' || $data['type'] == 'reply' && $parents)
        {
            $sql = "insert into `" . TABLE_PREFIX . "topic_reply`(`tid`,`replyid`) values ";
            $_list = array();
            $_tids = (array) explode(',', $parents);
            foreach ($_tids as $_tid)
            {
                $_tid = max(0, (int) $_tid);
                
                if($_tid > 0)
                {
                    $_list[] = "('{$_tid}','{$tid}')";
                }
            }

            if($_list)
            {
                $sql .= implode(" , ", $_list);
                $this->DatabaseHandler->Query($sql);
            }    
        }

                if ($imageid)
        {
            $ImageLogic->set_tid($imageid, $tid);
        }

                if($longtextid > 0 && $longtext_info)
        {
        	$LongtextLogic->set_tid($longtextid, $tid);
        }
        
                if($urls)
        {
            $this->_process_urls($data,$urls);
        }
    	if ($data['videoid'] > 0)
        {
            $sql = "update `" . TABLE_PREFIX . "topic_video` set `tid`='{$tid}' where `id`='{$data['videoid']}'";
            $this->DatabaseHandler->Query($sql);
        }
       
                if($totid > 0)
        {
                        
        	
        	$reply_count_update = (($type == 'both' || $type == 'reply') ?
                "`replys` = `replys` + 1 , " : "");
            $forward_count_update = (($type == 'both' || $type == 'forward') ?
                "`forwards` = `forwards` + 1 , " : "");
            
                        $update_sql = '';
            if ($type == 'reply') {
           		$update_sql = " `replys` = `replys` + 1 ,`lastupdate`='{$data['lastupdate']}' ";
            } else if ($type == 'both') {
            	$update_sql = " `replys` = `replys` + 1 ,`forwards` = `forwards` + 1,`lastupdate`='{$data['lastupdate']}' ";
            } else if ($type == 'forward') {
            	$update_sql = " `forwards` = `forwards` + 1 ";
            }

                        if ($parents && !empty($update_sql))
            {
                $sql = "update `" . TABLE_PREFIX . "topic` set {$update_sql} where `tid` in ($parents)";
                $this->DatabaseHandler->Query($sql);
            }

                                    if ($data['uid']!=$data['touid'] && ($data['type'] == 'both' || $data['type'] == 'reply'))
            {
                $sql = "update `" . TABLE_PREFIX .
                    "members` set `comment_new`=`comment_new`+1 where `uid`='{$data['touid']}'";
                $this->DatabaseHandler->Query($sql);
            }

			$sql = "select `uid`,`username` from `" . TABLE_PREFIX . "members` where `uid`='{$data['touid']}'";
			$query = $this->DatabaseHandler->Query($sql);
			$row = $query->GetRow();
			if ($row)
			{
								if ($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
				{
					imjiqiren_send_message($row, 'p', $this->Config);
				}

								if ($this->Config['sms_enable'] && sms_init($this->Config))
				{
					sms_send_message($row, 'p', $this->Config);
				}
			}

            
            if($data['touid'] > 0)
            {
                $sql = "select `uid`,`comment_new`,`email`,`notice_reply` from `" . TABLE_PREFIX .
                    "members` where `uid` = '{$data['touid']}'";
                $query = $this->DatabaseHandler->Query($sql);
                $reply_notice = $query->GetRow();

                if ($reply_notice['notice_reply'] == 1) 
                {

                    if ($this->Config['notice_email'] == 1) 
                    {
                        if (!$this->noticeConfig)
                        {
                            $this->noticeConfig = ConfigHandler::get('email_notice');
                        }

                        Load::lib('mail');
                        $mail_to = $reply_notice['email'];

                        $mail_subject = "{$this->noticeConfig['reply']['title']}";
                        $mail_content = "{$this->noticeConfig['reply']['content']}";
                        $send_result = send_mail($mail_to, $mail_subject, $mail_content, array(), 3, false);
                    }
                    else
                    {
                                                Load::logic('notice');
                        $NoticeLogic = new NoticeLogic();
                        $pm_content = $reply_notice['comment_new'] . '人评论你的微博';
                        $NoticeLogic->Insert_Cron($reply_notice['uid'], $reply_notice['email'], $pm_content,
                            'raply');
                    }
                }
            }
            
        }

                if ($at_uids)
        {
            $this->_process_at_uids($data,$at_uids);
        }
        
                if ($item == 'qun' && ($data['type'] == 'qun' || $data['type'] == 'first')) {
        	if (!empty($item_id)) {
        		$query = DB::query("SELECT uid FROM ".DB::table('qun_user')." WHERE qid='{$item_id}'");
        		$uids = array();
        		while ($value=DB::fetch($query)) {
        			if ($value['uid'] != MEMBER_ID) {
        				$uids[] = $value['uid'];
        			}
        		}
        		
        		if (!empty($uids)) {
        			DB::query("UPDATE ".DB::table('members')." 
        					   SET qun_new=qun_new+1 
        					   WHERE uid IN(".jimplode($uids).")");
        		}
        	}
        }
        
                $update_credits = false;

        if ($tags)
        {
            Load::logic('tag');
            $TagLogic = new TagLogic('topic');
            $return = $TagLogic->Add(array('item_id' => $tid, 'tag' => $tags, ), false);

            if ($this->Config['extcredits_enable'] && $data['uid'] > 0)
            {
                
                if (is_array($tags) && count($tags))
                {
                    foreach ($tags as $_t)
                    {
                        if ($_t)
                        {
                            $update_credits = (update_credits_by_action(('_T' . crc32($_t)), $data['uid']) ||
                                $update_credits);
                        }
                    }
                }
            }
            
                        foreach($tags as $val) {
            	$query = DB::query("SELECT uid FROM ".DB::table('tag_favorite')." WHERE tag='{$val}'");
            	$tag_uids = array();
            	while ($value = DB::fetch($query)) {
            		if ($value['uid'] != MEMBER_ID) {
            			$tag_uids[] = $value['uid'];
            		}
            	}
            	if (!empty($tag_uids)) {
            		DB::query("UPDATE ".DB::table('members')." SET topic_new=topic_new+1 WHERE uid IN(".jimplode($tag_uids).")");
            	}
            }
        }
        
                if ($this->Config['extcredits_enable'])
        {
            if (!$update_credits && $data['uid'] > 0)
            {
                if ($totid > 0)
                {
                    
                    update_credits_by_action('reply', $data['uid']);
                }
                else
                {
                    
                    update_credits_by_action('topic', $data['uid']);
                }
            }
        }
        

                if ($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
        {
            $to_admin_robot = ConfigHandler::get('imjiqiren', 'admin_qq_robots');
            if ($to_admin_robot)
            {
                imjiqiren_send_message($to_admin_robot, 'to_admin_robot', array('site_url' => $this->
                    Config['site_url'], 'username' => $data['username'], 'content' => $data['content'],
                    'topic_id' => $topic_id));
            }
        }
        

                if ($this->Config['sms_enable'] && sms_init($this->Config))
        {
            $to_admin_mobile = ConfigHandler::get('sms', 'admin_mobile');
            if ($to_admin_mobile)
            {
                sms_send_message($to_admin_mobile, 'to_admin_mobile', array('site_url' => $this->
                    Config['site_url'], 'username' => $data['username'], 'content' => $data['content'],
                    'topic_id' => $topic_id));
            }
        }
        
		$this->_syn_to($data);
        
                
        $this->_update_level(MEMBER_ID);
        
        unset($this->_cache);
        
        return $data;
    }
    
    
    function Modify($tid,$content,$imageid=0)
    {
        $sql_sets = array();
        
        $timestamp = time();
        
        $tid = max(0, (int) $tid);       
        if($tid < 1)
        {
            return "微博ID错误";
        }
        
        $topic_info = $this->get($tid);        
        if(!$topic_info)
        {
            return "微博已经不存在了";
        }
        
        $content = trim(strip_tags($content));
        $content_length = strlen($content);
        if($content_length < 2)
        {
            return "微博内容不能为空";
        }
        elseif($content_length > $this->_len)
        {
            $content = cut_str($content,$this->_len,'');
        }
        
        if(false != ($filter_result = filter($content)))
        {
            return $filter_result;
        }
        
    	    	if($topic_info['totid'] > 0)
    	{
    		            $content = $this->GetForwardContent($content);
            
    		$row = $this->Get($topic_info['totid']);
    		
    		if($row && ('forward'==$topic_info['type'] || 'both'==$topic_info['type']))
	        {            	
	            $content .= $this->ForwardSeprator . "{$row['nickname']} : " . strip_tags($row['content']);
	            	
		        if(strlen($content) > $this->_len)
			    {
			        $content = cut_str($content, $this->_len, '');
			    }
	        }
    	}
	        

    	        if($imageid != $topic_info['imageid'])
        {   
        	Load::logic('image');
        	$ImageLogic = new ImageLogic();

        	if($imageid)
        	{
	        	$imageid = $ImageLogic->get_ids($imageid, $topic_info['uid']);
	        	     
	            if($imageid)
	            {
	                $ImageLogic->set_tid($imageid, $tid);
	            }
        	}
        	
        	        	$ImageLogic->set_topic_imageid($tid);
        }
        
        
        
                
        $_process_result = $this->_process_content($content);
        
        $content = $_process_result['content'];
        
        $at_uids = $_process_result['at_uids'];
        
        $tags = $_process_result['tags'];
        
        $urls = $_process_result['urls'];        
       
        $sql_sets['content'] = "`content`='{$content}'";
        
        if (strlen($content) > 255)
        {
            $content = cut_str($content, 254 * 2, '');
            
            $_content = cut_str($content, 255, '');
            
            $sql_sets['content'] = "`content`='{$_content}'";
            
            $sql_sets['content2'] = "`content2`='".substr($content, strlen($_content))."'";
        }

                
        $sql_sets['lastupdate'] = "`lastupdate`='$timestamp'";
        
        
        $this->DatabaseHandler->Query("update ".TABLE_PREFIX."topic set ".(implode(" , " , $sql_sets))." where `tid`='$tid'");
        
        
        if($at_uids)
        {
            $this->_process_at_uids($at_uids);
        }        
        
        
        if($tags)
        {
            Load::logic('tag');
            $TagLogic = new TagLogic('topic');
            
            $tags_old = array();
            
            if (false !== strpos($topic_info['content'], '#'))
            {
                preg_match_all('~<T>#(.+?)#</T>~', $topic_info['content'], $subpatterns);
                if ($subpatterns && is_array($subpatterns[1]))
                {
                    $tags_old = $subpatterns['1'];
                }
            }
            
            $TagLogic->Modify(array('item_id' => $tid, 'tag' => $tags), $tags_old);
        }
        
        
                if($urls)
        {
            $this->_process_urls($topic_info,$urls,true);
        }
        
        
        return $topic_info;
    }
    
    
    function Delete($ids)
    {
        $topic = $this->Get($ids);
        if (!$topic)
        {
            return "微博已经不存在了";
        }
        if (!is_array($topic))
        {
            return (is_string($topic) ? $topic : "未知的错误");
        }
        if (isset($topic['tid']) && $topic['tid'] == $ids)
        {
            $topics[] = $topic;
        }
        else
        {
            $topics = $topic;
        }
        $topics = (array) $topics;
        
                $tbs = array(
        	'qqwb_bind_topic' => 'tid',
        	'report' => 'tid',
        	'sms_reveive_log' => 'tid',
        	'topic' => 'tid',
        	'topic_favorite' => 'tid',
        	'topic_image' => 'tid',
        	'topic_item' => 'tid',
        	'topic_longtext' => 'tid',
        	'topic_mention' => 'tid',
        	'topic_more' => 'tid',
        	'topic_music' => 'tid',
        	'topic_qun' => 'tid',
        	'topic_reply' => array('tid', 'replyid'),
        	'topic_tag' => 'item_id',
        	'topic_url' => 'tid',
        	'topic_video' => 'tid',
        	'topic_vote' => 'tid',
        	'wall_draft' => 'tid',
        	'wall_playlist' => 'tid',
        	'xwb_bind_topic' => 'tid',
        	'topic_recommend' => 'tid',
        );

        foreach ($topics as $topic)
        {
        	
        	$tid = $topic['tid'];
        	
        	        	if (!empty($topic['item']) &&  $topic['item_id'] > 0) {
        		Load::functions('app');
        		app_delete_relation($topic['item'], $topic['item_id'], $topic['tid']);
        	}
        	
            $topic_more = $this->GetMore($topic['tid']);

            if ($topic_more['parents'])
            {
                $sql = "update `" . TABLE_PREFIX .
                    "topic` set `replys`=if(`replys`>1,`replys`-1,0) , `forwards`=if(`forwards`>1,`forwards`-1,0) where `tid` in({$topic_more['parents']})";
                $this->DatabaseHandler->Query($sql);
            }
            if (false !== strpos($topic['content'], '#'))
            {
                preg_match_all('~<T>#(.+?)#</T>~', $topic['content'], $subpatterns);
                if ($subpatterns && is_array($subpatterns[1]))
                {
                    Load::logic('tag');
                    $TagLogic = new TagLogic('topic');
                        
                    $TagLogic->Delete(array('item_id' => $topic['tid'], 'tag' => $subpatterns['1'], ));
                }
            }
                    	if ($topic['imageid'])
            {
            	Load::logic('image');
            	$ImageLogic = new ImageLogic();
            	
            	$ImageLogic->delete($topic['imageid']);
            }
            if ($topic['videoid'])
            {
                                $sql = "select `id`,`video_img` from `" . TABLE_PREFIX .
                    "topic_video` where `id`='" . $topic['videoid'] . "' ";
                $query = $this->DatabaseHandler->Query($sql);
                $topic_video = $query->GetRow();

                Load::lib('io');
                IoHandler::DeleteFile($topic_video['video_img']);
            }
            $sql = "update `" . TABLE_PREFIX .
                "members` set `topic_count`=if(`topic_count`>1,`topic_count`-1,0) where `uid`='{$topic['uid']}'";
            $this->DatabaseHandler->Query($sql);
            
                       
			            foreach($tbs as $k=>$vs)
            {
            	$vs = (array) $vs;
            	
            	foreach($vs as $v)
            	{
            		$this->DatabaseHandler->Query("delete from `".TABLE_PREFIX."{$k}` where `{$v}`='{$tid}'", "SKIP_ERROR");
            	}
            }


                        if ($this->Config['extcredits_enable'] && $topic['uid'] > 0)
            {
                
                update_credits_by_action('topic_del', $topic['uid']);
            }
        }

    }

    
    function Get($ids, $fields = '*', $process = 'Make', $table = "", $prikey =
        'tid')
    {
        $table = $table ? $table : TABLE_PREFIX . "topic";

        $cache_key = md5($fields . $process . $table . $prikey);
        $condition = "";
        if (is_numeric($ids))
        {
            if (isset($this->_cache[$cache_key][$ids]))
            {
                return $this->_cache[$cache_key][$ids];
            }

            $condition = "where `{$prikey}`='{$ids}'";
        } elseif (is_array($ids))
        {
            $condition = "where `{$prikey}` in ('" . implode("','", $ids) . "')";
        } elseif (is_string($ids) && false !== strpos(strtolower($ids), ' limit '))
        {
            $condition = $ids;
        }
        else
        {
            return false;
        }

        $sql = "select {$fields} from {$table} {$condition} ";
	
        $query = $this->DatabaseHandler->Query($sql);
        if (!$query || ($num_rows = $query->GetNumRows()) < 1)
        {
            return false;
        }

        $list = array();
        while (false!=($row = $query->GetRow()))
        {
            if ($process)
            {
                $row = isset($this->_cache[$cache_key][$row[$prikey]]) ? $this->_cache[$cache_key][$row[$prikey]] :
                    $this->$process($row);
            }
            if (!isset($this->_cache[$cache_key][$row[$prikey]]))
            {
                $this->_cache[$cache_key][$row[$prikey]] = $row;
            }

            if (is_numeric($ids) && $num_rows < 2)
            {
                $list = $row;
                break;
            }
            else
            {
                $list[$row[$prikey]] = $row;
            }
        }
        return $list;

    }

    
    function Make($topic, $actors = array())
    {
        global $rewriteHandler;
        
                $make_member_fields = "`uid`,`ucuid`,`username`,`nickname`,`signature`,`face_url`,`face`,`validate`,`level`";


        static $topic_content_member_href_pattern_static = '';
        if (!$topic_content_member_href_pattern_static)
        {
            $topic_content_member_href_pattern_static = "index.php?mod=|REPLACE_VALUE|";
            if ($rewriteHandler)
            {
                $topic_content_member_href_pattern_static = $rewriteHandler->formatURL($topic_content_member_href_pattern_static);
            }
        }

                $topic['content'] .= $topic['content2'];
        unset($topic['content2']);

                if(defined(TOPIC_CONTENT_CUT_LENGTH) && TOPIC_CONTENT_CUT_LENGTH > 0)
        {
        	$topic['content'] = cutstr($topic['content'], TOPIC_CONTENT_CUT_LENGTH);
        }
        
                if ($topic['dateline'])
        {
            $topic['addtime'] = $topic['dateline'];
            $topic['dateline'] = my_date_format2($topic['dateline']);
        }
                
                $highlight = $_GET['highlight'];       
        if (is_numeric($highlight))
        {
        	
        }
        elseif (is_string($highlight))
        {
            $topic['content'] = str_replace($highlight, "<font color=red>{$highlight}</font>", $topic['content']);
        }
        		
        $topic['is_vote'] = 0;
        $topic['random'] = random(6);

                if (false !== strpos($topic['content'], $this->Config['site_url']))
        {	
            if (preg_match_all('~(?:https?\:\/\/)(?:[A-Za-z0-9\_\-]+\.)+[A-Za-z0-9]{1,4}(?:\:\d{1,6})?(?:\/[\w\d\/=\?%\-\&_\~\`\:\+\#\.]*(?:[^\;\@\[\]\<\>\'\"\n\r\t\s\x7f-\xff])*)?~i',
                $topic['content'] . " ", $match))
            {
                $cont_rpl = $cont_sch = array();
                foreach ($match[0] as $v)
                {
                    $v = trim($v);
                    if (($vl = strlen($v)) < 8 || $vl > 200)
                    {
                        continue;
                    }
                    if (strtolower($this->Config['site_url']) == strtolower(substr($v, 0, strlen($this->
                        Config['site_url']))))
                    {
                    	
                    	$app_type = '';
                    	$tmp_vid = 0;
                    	if (MEMBER_ID > 0) {	                    	if (preg_match("/mod=vote(?:&code=view)?&vid=([0-9]+)/", $v, $m) || preg_match("/vote(?:\/view)?\/vid\-([0-9]+)/", $v, $m)) {
	                    		$app_type = 'vote';
	                    		$tmp_vid = $m[1];
	                    		if ($topic['is_vote'] === 0) {
	                    			$topic['is_vote'] = $tmp_vid;
	                    		}
	                    	}
                    	}
                        
                                                if ($app_type == 'vote') {
                        	$cont_sch[] = "{$v}";
                        	$vote_key = $topic['tid'].'_'.$topic['random'];
                        	if (JSG_WAP === true) {
                        		$cont_rpl[] = "<a href='{$v}'>{$v}<img src='../images/voteicon.gif'/></a>";
                        	} else {
                        		$cont_rpl[] = "<a onclick='return getVoteDetailWidgets(\"{$vote_key}\", {$tmp_vid});' href='{$v}'>{$v}<img src='./images/voteicon.gif'/></a>";
                        	}
                        } else {
                        	$cont_sch[] = "{$v}";
                        	$cont_rpl[] = "<a href='{$v}'>{$v}</a>";
                        }
                        
                    }
                }
                
                if ($cont_rpl && $cont_sch)
                {
                	                	$cont_sch = array_unique($cont_sch);
                	$cont_rpl = array_unique($cont_rpl);
                    $topic['content'] = trim(str_replace($cont_sch, $cont_rpl, $topic['content']));
                }
            }
        }

        if (false !== strpos($topic['content'], '</M>'))
        {

            preg_match_all("/<M (\d+)>/", $topic['content'], $matches);
                        if ($matches[1])
            {
                $sql = "Select `uid`,`username` From " . TABLE_PREFIX . 'members' .
                    " Where `uid` in ('" . implode("','", $matches[1]) . "')";
                $query = $this->DatabaseHandler->Query($sql);
                $_search = $_replace = array();
                while ($row = $query->GetRow())
                {
                    $_search[] = "<M {$row['uid']}>";
                    $_replace[] = "<M {$row['username']}>";
                }

                if ($_search && $_replace)
                {
                    $topic['content'] = str_replace($_search, $_replace, $topic['content']);

                    $updatatopic = "update `" . TABLE_PREFIX . "topic` set `content`='" . addslashes($topic['content']) .
                        "' where `tid`=" . $topic['tid'];
                    $this->DatabaseHandler->Query($updatatopic);
                }
            }

                        $topic['content'] = preg_replace('~<M ([^>]+?)>\@(.+?)</M>~', '<a href="' .
                str_replace('|REPLACE_VALUE|', '\\1', $topic_content_member_href_pattern_static) .
                '" target="_blank"  onmouseover="get_at_user_choose(\'\\2\',\'_user\','.$topic['tid'].',event);"  onmouseout="clear_user_choose();">@\\2</a>', $topic['content']);
        }

                if (false !== strpos($topic['content'], '<T>#'))
        {
            static $topic_content_tag_href_pattern_static = '';
            if (!$topic_content_tag_href_pattern_static)
            {
                $topic_content_tag_href_pattern_static = "index.php?mod=tag&code=|REPLACE_VALUE|";
                
                
                if ($topic['item'] == 'qun') {
                	$topic_content_tag_href_pattern_static = "index.php?mod=qun&qid={$topic['item_id']}&tag=|REPLACE_VALUE|";
                }
                
                if ($rewriteHandler)
                {
                    $topic_content_tag_href_pattern_static = $rewriteHandler->formatURL($topic_content_tag_href_pattern_static);
                }
            }

           $topic['content'] = preg_replace('~<T>#(.+?)#</T>~e', '"<a href=\"' . str_replace('|REPLACE_VALUE|',
                '" . strip_tags("\\1")', $topic_content_tag_href_pattern_static) . ' . "\">#\\1#</a>"', $topic['content']);
               
        }
        
        if (false !== strpos($topic['content'], '</U>'))
        {
            static $topic_content_url_href_pattern_static = '';
            if (!$topic_content_url_href_pattern_static)
            {
                $topic_content_url_href_pattern_static =
                    "index.php?mod=url&code=|REPLACE_VALUE|";
                if ($rewriteHandler)
                {
                    $topic_content_url_href_pattern_static = ltrim($rewriteHandler->formatURL($topic_content_url_href_pattern_static),
                        '/');
                }
            }
            $sys_site_url = $this->Config['site_url'];
            if ($rewriteHandler)
            {
                $sys_site_url = ((false !== ($_tmp_pos = strpos($sys_site_url, '/', 10))) ?
                    substr($sys_site_url, 0, $_tmp_pos) : $sys_site_url);
            }

            $topic['content'] = preg_replace('~<U ([0-9a-zA-Z]+)>(.+?)</U>~', '<a  title="\\2" href="' .
                ($sys_site_url . '/' . str_replace('|REPLACE_VALUE|', '\\1', $topic_content_url_href_pattern_static)) .
                '"  target="_blank"> ' . ($sys_site_url . '/' . str_replace('|REPLACE_VALUE|', '\\1',
                $topic_content_url_href_pattern_static)) . '</a>', $topic['content']);

        }

                if (false !== strpos($topic['content'], '['))
        {

            if (!($face_conf = $this->_cache['face_conf_default']))
            {
                $face_conf = ConfigHandler::get('face');

                $this->_cache['face_conf_default'] = $face_conf;
            }

            if (false === strpos($topic['content'], '#['))
            {
                if (preg_match_all('~\[(.+?)\]~', $topic['content'], $match))
                {

                    foreach ($match[0] as $k => $v)
                    {
                        if (false != ($img_src = $face_conf[$match[1][$k]]))
                        {
                            $topic['content'] = str_replace($v, '<img src="' . $this->Config['site_url'] .
                                '/' . $img_src . '" border="0"/>', $topic['content']);
                        }
                    }
                }
            }
        }

                if ($topic['touid'])
        {
            $touser = $this->GetMember($topic['touid'], $make_member_fields);
            if ($topic['tousername'] != $touser['nickname'])
            {
                $updatatousername = "update `" . TABLE_PREFIX . "topic` set `tousername`='{$touser['nickname']}' where `tid`=" .
                    $topic['tid'];
                $this->DatabaseHandler->Query($updatatousername);
            }
        }
        

                if($topic['from'])
        {
            $topic['from_string'] = '';
            $topic['from_html'] = '';
            if('qq'==$topic['from'])
            {
                static $static_qqrobot_href = '';
                if(!$static_qqrobot_href)
                {
                    $static_qqrobot_href = 'index.php?mod=tools&code=imjiqiren';
                    if($rewriteHandler)
                    {
                        $static_qqrobot_href = $rewriteHandler->formatURL($static_qqrobot_href);
                    }
                }
                    
                $topic['from_string'] = "通过QQ机器人";           
                $topic['from_html'] = '通过<a href="'.$static_qqrobot_href.'" target="_blank">QQ机器人</a>';           
            }
            elseif('wap'==$topic['from'])
            {
                static $static_wap_href = '';
                if(!$static_wap_href)
                {
                    $static_wap_href = 'index.php?mod=other&code=wap';
                    if($rewriteHandler)
                    {
                        $static_wap_href = $rewriteHandler->formatURL($static_wap_href);
                    }
                }
                
                $topic['from_string'] = "来自手机";
                $topic['from_html'] = '<a href="'.$static_wap_href.'">来自手机</a>';
            }
            elseif('api'==$topic['from'])
            {
                $topic['from_string'] = "来自网站API";
                $topic['from_html'] = '来自网站API';
                
                if(ConfigHandler::get('api', 'from_enable'))
                {                	
                	$api_info = $this->GetApp($topic['item_id'], "`app_name`, `source_url`, `show_from`");
                	
                	if($api_info['show_from'])
                	{
	                	$topic['from_html'] = $topic['from_string'] = "来自{$api_info['app_name']}";
	                	if($api_info['source_url'])
	                	{
	                			                		$topic['from_html'] = "来自<a target='_blank' href='{$api_info['source_url']}'>{$api_info['app_name']}</a>";
	                	}
                	}
                }
            }
            elseif('sina'==$topic['from'])
            {
                static $static_sina_href = '';
                if(!$static_sina_href)
                {
                    $static_sina_href = 'index.php?mod=tools&code=sina';
                    if($rewriteHandler)
                    {
                        $static_sina_href = $rewriteHandler->formatURL($static_sina_href);
                    }
                }
                
                $topic['from_string'] = "来自新浪微博";
                $topic['from_html'] = '来自<a href="'.$static_sina_href.'">新浪微博</a>';
            }
            elseif('mobile'==$topic['from'])
            {
                static $static_sms_href = '';
                if(!$static_sms_href)
                {
                    $static_sms_href = 'index.php?mod=tools&code=sms';
                    if($rewriteHandler)
                    {
                        $static_sms_href = $rewriteHandler->formatURL($static_sms_href);
                    }
                }
                
                $topic['from_string'] = "来自手机短信";
                $topic['from_html'] = '来自<a href="'.$static_sms_href.'">手机短信</a>';
            }
            elseif('web'==$topic['from'])
            {
                $topic['from_string'] = "来自{$this->Config[site_name]}";
                $topic['from_html'] = "来自<a href='{$this->Config[site_url]}'>{$this->Config[site_name]}</a>";
            }
            elseif ('vote'==$topic['from'])		
            {
            					$topic['from_string'] = "来自投票";
                $static_vote_href = 'index.php?mod=vote&code=view&vid='.$topic['item_id'];
                if ($rewriteHandler)
                {
                	$static_vote_href = $rewriteHandler->formatURL($static_vote_href);
                }
                Load::Logic('vote');
                $VoteLogic = new VoteLogic();
                $subject = $VoteLogic->id2subject($topic['item_id']);
                $sub_from = '';
                if (!empty($subject)) {
                	$sub_from = ' - '.$subject;
                }
                $topic['from_html'] = '来自<a href="'.$static_vote_href.'" target="_blank">投票'.$sub_from.'</a>';
            }
            elseif ('qun'==$topic['from']) {
            	
            	            	$topic['from_string'] = "来自微群";
                $static_vote_href = 'index.php?mod=qun&qid='.$topic['item_id'];
                if ($rewriteHandler)
                {
                	$static_vote_href = $rewriteHandler->formatURL($static_vote_href);
                }
                Load::Logic('qun');
                $QunLogic = new QunLogic();
                $qun_info = $QunLogic->get_qun_info($topic['item_id']);
                $sub_from = '';
                if (!empty($qun_info)) {
                	$sub_from = ' - '.$qun_info['name'];
                }
                $topic['from_html'] = '来自<a href="'.$static_vote_href.'" target="_blank">微群'.$sub_from.'</a>';
            }
		  elseif ('fenlei'==$topic['from']) {
            	            	$topic['from_string'] = "来自分类";

                Load::Logic('fenlei');
                $FenleiLogic = new FenleiLogic();
                $fenlei_info = $FenleiLogic->get_fenlei_info($topic['item_id']);
				if($fenlei_info){
	                $static_vote_href = 'index.php?mod=fenlei&code=detail&fid='.$fenlei_info['fid'].'&id='.$topic['item_id'];
			        if ($rewriteHandler)
	                {
	                	$static_vote_href = $rewriteHandler->formatURL($static_vote_href);
	                }
	                
	                $sub_from = '';
	                if (!empty($fenlei_info)) {
	                	$sub_from = ' - '.$fenlei_info['title'];
	                }
	            	$topic['from_html'] = '来自<a href="'.$static_vote_href.'" target="_blank">分类信息'.$sub_from.'</a>';
				}else{
					$topic['from_html'] = '分类信息已删除';
				}
            }
           elseif ('event'==$topic['from']) {
            	            	$topic['from_string'] = "来自活动";
                $static_vote_href = 'index.php?mod=event&code=detail&id='.$topic['item_id'];
                if ($rewriteHandler)
                {
                	$static_vote_href = $rewriteHandler->formatURL($static_vote_href);
                }
                Load::Logic('event');
                $EventLogic = new EventLogic();
                $event_info = $EventLogic->get_event_info($topic['item_id']);
                $sub_from = '';
                if (!empty($event_info)) {
                	$sub_from = ' - '.$event_info['title'];
                }
            	$topic['from_html'] = '来自<a href="'.$static_vote_href.'" target="_blank">活动'.$sub_from.'</a>';
            }
        }
        
                $topic['top_parent_id'] = $topic['roottid'];
        $topic['parent_id'] = $topic['totid'];
        

        if ($topic['imageid'])
        {
                        
			Load::logic('image');
			$ImageLogic = new ImageLogic();

			$topic['image_list'] = $ImageLogic->image_list($topic['imageid']);
        }

                if ($topic['videoid'] > 0 && $this->Config['video_status'])
        {
            $sql = "select `id`,`video_hosts`,`video_link`,`video_img`,`video_img_url`,`video_url` from `" .
                TABLE_PREFIX . "topic_video` where `id`='" . $topic['videoid'] . "' ";
            $query = $this->DatabaseHandler->Query($sql);
            $topic_video = $query->GetRow();

            $topic['VideoID'] = $topic_video['id'];
            $topic['VideoHosts'] = $topic_video['video_hosts'];
            $topic['VideoLink'] = $topic_video['video_link'];
			$topic['VideoUrl'] = $topic_video['video_url'];
        
            if ($topic_video['video_img'])
            {
                $topic['VideoImg'] = ($topic_video['video_img_url'] ? $topic_video['video_img_url'] : $this->Config['site_url']) . '/' . $topic_video['video_img'];
            }
            else
            {
                $topic['VideoImg'] = $this->Config['site_url'] . '/images/vd.gif';
            }
        }

                if ($topic['musicid'] > 0)
        {
            $sql = "select `id`,`music_url` from `" . TABLE_PREFIX .
                "topic_music` where `id`='" . $topic['musicid'] . "' ";
            $query = $this->DatabaseHandler->Query($sql);
            $topic_music = $query->GetRow();

            $topic['MusicID'] = $topic_music['id'];
            $topic['MusicUrl'] = $topic_music['music_url'];
        }


                $topic = array_merge($topic, (array )$this->GetMember($topic['uid'], $make_member_fields));
                        return $topic;

    }

    
    function GetMore($ids)
    {
        return $this->Get($ids, '*', 'MakeMore', TABLE_PREFIX . "topic_more", 'tid');
    }
    
    function MakeMore($row)
    {
        if ($row['replyids'])
        {
            $row['replyids'] = unserialize($row['replyids']);
        }

        return $row;
    }

    
    function GetReply($tid)
    {
        $sql = "select `replyid` as `id` from `" . TABLE_PREFIX .
            "topic_reply` where `tid`='{$tid}' ORDER BY `tid` DESC";
        $query = $this->DatabaseHandler->Query($sql);
        $ids = array();
        while ($row = $query->GetRow())
        {
            $ids[] = $row['id'];
        }

        return $ids;
    }
    
    function GetReplyIds($tid)
    {
        $topic_info = $this->Get($tid);
        if (!$topic_info)
        {
            return false;
        }

        $topic_more = $this->GetMore($tid);
        if (!$topic_more)
        {
            return false;
        }

        if ($topic_more['replyidscount'] != $topic_info['replys'])
        {
            $topic_more['replyids'] = $this->GetReply($tid);
            $topic_more['replyidscount'] = count($topic_more['replyids']);

            $sql = "update `" . TABLE_PREFIX . "topic` set `replys`='{$topic_more['replyidscount']}' where `tid`='{$tid}'";
            $this->DatabaseHandler->Query($sql);

            $sql = "update `" . TABLE_PREFIX . "topic_more` set `replyids`='" . serialize($topic_more['replyids']) .
                "' , `replyidscount`='{$topic_more['replyidscount']}' where `tid`='{$tid}'";
            $this->DatabaseHandler->Query($sql);
        }

        return $topic_more['replyids'];
    }

    
    function GetMember($ids, $fields = '*')
    {
        return $this->Get($ids, $fields, 'MakeMember', TABLE_PREFIX . "members", 'uid');
    }

    
    function MakeMember($row)
    {
        if (isset($row['uid']))
        {
                        $row['__face__'] = $row['face'];

                        if (true !== UCENTER_FACE && !$row['face'])
            {
                $row['face'] = $row['face_small'] = $row['face_original'] = face_get();            }
            else
            {
                $row['face_small'] = $row['face'] = face_get($row);
                $row['face_original'] = face_get($row, 'middle');
            }
        }
        
        if (isset($row['province']) || isset($row['city']))
        {
            $row['from_area'] = "{$row['province']} {$row['city']}";
        }

        if (empty($row['nickname']))
        {
            $row['nickname'] = $row['username'];
        }
      
                if (isset($row['validate']) && $row['validate'])
        {
            $sql = "select `uid`,`validate_remark`,`validate_true_name` from `" . TABLE_PREFIX .
                "memberfields` where `uid`='{$row['uid']}'";
            $query = $this->DatabaseHandler->Query($sql);
            $memberfields = $query->GetRow();

            $row['validate_user'] = $memberfields['validate_true_name'];
            $row['vip_info'] = $memberfields['validate_remark'];
            $row['validate_html'] = ($row['validate'] ? "<a href='index.php?mod=other&code=vip_intro' target='_blank'><img class='vipImg' title='{$row['vip_info']}' src='images/vip.gif' /></a>" :
                "");
        }
		
                if($row['gender'] == 1){
        	$row['gender_ta'] = '他';
        }else {
        	$row['gender_ta'] = '她';
        }
        
       
        return $row;
    }

    
    function GetBuddy($ids, $uid = 0)
    {
        $uid = $uid ? $uid : MEMBER_ID;
        if (!$uid)
            return;
        $ids = (array )$ids;

        $sql = "select * from `" . TABLE_PREFIX . "buddys` where `uid`='{$uid}' and `buddyid` in(" .
            implode(",", $ids) . ") order by `id` desc";
        $query = $this->DatabaseHandler->Query($sql);
        $list = array();
        while ($row = $query->GetRow())
        {
            $list[$row['buddyid']] = $row;
        }

        return $list;
    }

    
    function GetTopicImage($ids)
    {
        return $this->Get($ids, '*', 'MakeTopicImage', TABLE_PREFIX . "topic_image",
            'id');

    }

    
    function MakeTopicImage($row)
    {
                
        return $row;
    }
    
    function GetApi($ids, $fields='*')
    {
    	return $this->GetApp($ids, $fields);
    }
    function GetApp($ids, $fields='*')
    {
    	return $this->Get($ids, $fields, 'MakeApp', TABLE_PREFIX . "app", 'id');
    }
    function MakeApp($row)
    {
    	return $row;
    }
    
    function _syn_to($data)
    {
    	
        $this->_syn_to_sina($data);
        
        
         
        $this->_syn_to_qqwb($data);
        
        
        $this->_syn_to_kaixin($data);
        
        $this->_syn_to_renren($data);
    }

    
    
    function _syn_to_sina($data = array())
    {
        if ($this->Config['sina_enable'] && $data && $data['uid'] > 0 && $data['tid'] > 0 && 'sina'!=$data['from'] &&
            sina_weibo_init($this->Config) && sina_weibo_bind($data['uid']) && !$GLOBALS['imjiqiren_sys_config']['imjiqiren']['sina_update_disable'])
        {
            $sina_config = ConfigHandler::get('sina');
            if (($data['totid'] > 0 && $sina_config['is_syncreply_toweibo'] && sina_weibo_bind_setting($data['uid'])) || ($data['totid'] <
                1 && $sina_config['is_synctopic_toweibo'] && $_POST['syn_to_sina']))
            {
				if( TRUE===IN_JISHIGOU_INDEX || TRUE===IN_JISHIGOU_AJAX || TRUE===IN_JISHIGOU_ADMIN )
				{
					$result = jsg_schedule(array('data'=>$data),'syn_to_sina');
				}
				else
				{
										$_POST['syn_to_sina'] = 1;
					$GLOBALS['jsg_tid'] = $data['tid'];
					$GLOBALS['jsg_totid'] = $data['totid'];
					$GLOBALS['jsg_message'] = $data['content'];
					$GLOBALS['jsg_imageid'] = $data['imageid'];

					require_once(ROOT_PATH . 'include/xwb/sina.php');
					require_once(XWB_plugin::hackFile('newtopic'));
									}
            }
        }
    }
    
    function _syn_to_qqwb($data = array())
    {
        if ($this->Config['qqwb_enable'] && $data['uid'] > 0 && $data['tid'] > 0 && 'qqwb'!=$data['from'] &&
            qqwb_init($this->Config) && qqwb_bind($data['uid']) && !$GLOBALS['imjiqiren_sys_config']['imjiqiren']['qqwb_update_disable'])
        {
            $qqwb_config = ConfigHandler::get('qqwb');
            if (($data['totid'] > 0 && $qqwb_config['is_syncreply_toweibo'] && qqwb_synctoqq($data['uid'])) || ($data['totid'] <
                1 && $qqwb_config['is_synctopic_toweibo'] && $_POST['syn_to_qqwb']))
            {
                if( TRUE===IN_JISHIGOU_INDEX || TRUE===IN_JISHIGOU_AJAX || TRUE===IN_JISHIGOU_ADMIN )
                {
                    $result = jsg_schedule($data,'syn_to_qqwb');
                }
                else
                {
                    @extract($data);
                    
                    include(ROOT_PATH . 'include/qqwb/to_qqwb.inc.php');
                }
            }
        }
    }
    
    function _syn_to_kaixin($data = array())
    {
    	if ($this->Config['kaixin_enable'] && $data['uid'] > 0 && $data['tid'] > 0 && 'kaixin'!=$data['from'] &&
            kaixin_init($this->Config) && kaixin_bind($data['uid']) && !$GLOBALS['imjiqiren_sys_config']['imjiqiren']['kaixin_update_disable'])
        {
            $kaixin_config = ConfigHandler::get('kaixin');
            if (($data['totid'] > 0 && $kaixin_config['is_sync_topic']) || ($data['totid'] <
                1 && $kaixin_config['is_sync_topic'] && $_POST['syn_to_kaixin']))
            {
                if( TRUE===IN_JISHIGOU_INDEX || TRUE===IN_JISHIGOU_AJAX || TRUE===IN_JISHIGOU_ADMIN )
                {
                    $result = jsg_schedule($data, 'syn_to_kaixin');
                }
                else
                {
                    kaixin_sync($data);                    
                }
            }
        }
    }
    
    function _syn_to_renren($data = array())
    {
    	if ($this->Config['renren_enable'] && $data['uid'] > 0 && $data['tid'] > 0 && 'renren'!=$data['from'] &&
            renren_init($this->Config) && renren_bind($data['uid']) && !$GLOBALS['imjiqiren_sys_config']['imjiqiren']['renren_update_disable'])
        {
            $renren_config = ConfigHandler::get('renren');
            if (($data['totid'] > 0 && $renren_config['is_sync_topic']) || ($data['totid'] <
                1 && $renren_config['is_sync_topic'] && $_POST['syn_to_renren']))
            {
                if( TRUE===IN_JISHIGOU_INDEX || TRUE===IN_JISHIGOU_AJAX || TRUE===IN_JISHIGOU_ADMIN )
                {
                    $result = jsg_schedule($data, 'syn_to_renren');
                }
                else
                {
                    renren_sync($data);                    
                }
            }
        }
    }
    
     
    
    function _process_content($content)
    {
        $return = array();        
        
        $content .= ' ';
        
        $cont_sch = $cont_rpl = $at_uids = $tags = $urls = array();
        
        # @user
        if (false !== strpos($content, '@'))
        {
            if (preg_match_all('~\@([\w\d\_\-\x7f-\xff]+)(?:[\r\n\t\s ]+|[\xa1\xa1]+)~', $content, $match))
            {
                if (is_array($match[1]) && count($match[1]))
                {
                    foreach ($match[1] as $k => $v)
                    {
                        $v = trim($v);
                        if ('　' == substr($v, -2))
                        {
                            $v = substr($v, 0, -2);
                        }

                        if ($v && strlen($v) < 16)
                        {
                            $match[1][$k] = $v;
                        }
                    }

                    $sql = "select `uid`,`nickname`,`username`,`email`,`notice_at` from `" .
                        TABLE_PREFIX . "members` where `nickname` in ('" . implode("','", $match[1]) .
                        "') ";
                    $query = $this->DatabaseHandler->Query($sql);
                    while (false != ($row = $query->GetRow()))
                    {
                        $cont_sch[] = "@{$row['nickname']} ";
                        $cont_rpl[] = "<M {$row['username']}>@{$row['nickname']}</M> ";
                        $at_uids[$row['uid']] = $row['uid'];
                    }
                }
            }
        }
                if (false !== strpos($content, '#'))
        {
            $tag_num = ConfigHandler::get('tag_num', 'topic');
            if (preg_match_all('~\#([^\#\\\\]+?)\#~', $content, $match))
            {
                $i = 0;
                foreach ($match[1] as $v)
                {
                    $v = trim($v);
                    if (($vl = strlen($v)) < 2 || $vl > 50)
                    {
                        continue;
                    }

                    $tags[$v] = $v;
                    $cont_sch[] = "#{$v}#";
                    $cont_rpl[] = "<T>#{$v}#</T>";

                    if (++$i >= $tag_num)
                    {
                        break;
                    }
                }
            }
        }
                if (false !== strpos($content, ':/' . '/'))
        {
        	            if (preg_match_all('~(?:https?\:\/\/)(?:[A-Za-z0-9\_\-]+\.)+[A-Za-z0-9]{1,4}(?:\:\d{1,6})?(?:\/[\w\d\/=\?%\-\&_\~\`\:\+\#\.]*(?:[^\;\@\[\]\<\>\'\"\n\r\t\s\x7f-\xff])*)?~i',
                $content, $match))
            {
                foreach ($match[0] as $v)
                {
                    $v = trim($v);
                    if (($vl = strlen($v)) < 8)
                    {
                        continue;
                    }
                    if (strtolower($this->Config['site_url']) == strtolower(substr($v, 0, strlen($this->
                        Config['site_url']))))
                    {
                        continue;
                    }

                    if (!($arr = get_url_key($v)))
                    {
                        continue;
                    }
                    
                    $_process_result = array();
                    if(!isset($urls[$v]) && ($_process_result = $this->_process_url($v)))
                    {
                        $urls[$v] = $_process_result;                
                    }
                    
                    $cont_sch[] = "{$v}";
                    $cont_rpl[] = ($_process_result['content'] ? " {$_process_result['content']} " : "") . "<U {$arr['key']}>{$v}</U>";
                }
            }
        }
        
        if($cont_sch && $cont_rpl)
        {
        	        	uasort($cont_sch, create_function('$a, $b', 'return (strlen($a)<strlen($b));'));
        	
        	foreach($cont_sch as $k=>$v)
        	{
        		if($v && isset($cont_rpl[$k]))
        		{
        			$content = str_replace($v, $cont_rpl[$k], $content);
        		}
        	}
        }
        
        $content = trim($content);
        
        $return['content'] = $content;
        
        $return['at_uids'] = $at_uids;
        
        $return['tags'] = $tags;
        
        $return['urls'] = $urls;
        
        return $return;
    }
    
        
    function _process_url($url)
    {
        $return = array();
        
        $type = '';
        
        $ext = trim(strtolower(substr($url,strrpos($url,'.'))));
        
        if('.swf'==$ext)
        {
            $type = 'flash';
            
            $type_result = array( 
                'id' => $url,    
                'host' => $type, 
                'url' => $url,          
            );
        }
        elseif(in_array($ext,array('.mp3','.wma')))
        {
            $type = 'music';
        }
        elseif(in_array($ext,array('.jpg','jpeg','.gif','.png','.bmp',)))
        {
            $type = 'image';
        }
        else
        {
                        $type_result = $this->_parse_video($url);
            
            if($type_result)
            {
                $type = 'video';
            }
        }
        
        if($type)
        {
            $return['url'] = $url;
            $return['type'] = $type;
            
            if($type_result)
            {
                $return[$type] = $type_result;
                
                if($type_result['title'])
                {
                    $return['content'] = $type_result['title'];
                }
            }
        }
        
        return $return;        
    }
    
    
    function _parse_video($url)
    {        
        $return = array();
        
        $vhconfs = array(
        	"youku.com" => array('p'=>"/\/id_([\w\d\=]+)\.html/",'c'=>'utf-8','tp'=>"/\<title\>(.+?)\s*[\-\_].*?\<\/title\>/i",'ip'=>'~\_href\s*\=\s*[\"\']iku\:\/\/.+?(http\:\/\/[\w\d]+\.ykimg\.com\/[^\|]+?)\|~i', 'ppp'=>1),
        	"sina.com.cn" => array('p'=>"/\/(\d+)\-(\d+)\.html/",'c'=>'utf-8','tp'=>"/\<title\>(.+?)\s*[\-\_].*?\<\/title\>/",'ip'=>"~pic\s*[\:\=]\s*[\'\"](http\:\/\/[^\']+?\.(?:jpg|jpeg|gif|png|bmp))[\'\"]~i",),
        	"tudou.com" => array('p'=>"/\/([\w\d\-\_]+)\/" . "*$/",'c'=>'gbk','tp'=>"/\<title\>(.+?)(?:[\-\_].*?)?\<\/title\>/",'ip'=>"/bigItemUrl\s*[\=\:]\s*[\"\']([^\"\']+?)[\"\']/", 'ppp'=>1, 'gzip'=>1,),
        	"ku6.com" => array('p'=>"/\/([\w\-\_]+)\.html/",'c'=>'gbk','tp'=>"/\<title\>(.+?)\s*[\-\_].*?\<\/title\>/i",'ip'=>"/\<span class=\"s_pic\"\>([^\<]+?)\<\/span\>/",),
        	"sohu.com" => array('p'=>"/\/([\d]+)\/?$/",'c'=>'gbk','tp'=>"/\<title\>(.+?)\s*[\-\_].*?\<\/title\>/i",'ip'=>"/[\'\"]og\:image[\'\"]\s*content\s*\=\s*[\'\"]([^\'\"]+?)[\'\"]/",),
        	"mofile.com" => array('p'=>"/\/(\w+)\/?$/",'c'=>'utf-8','tp'=>"/\<title\>(.+?)\s*[\-\_].*?\<\/title\>/i",'ip'=>"/thumbpath=\"(.*?)\";/i",),
    	);
      
        $urls = parse_url($url);
      
        if(false != ($host = trim(strtolower($urls['host']))))
        {
            foreach($vhconfs as $k=>$v)
            {
                if(false!==strpos($host,$k))
                {
                    if(preg_match($v['p'],$url,$m) && $m[1])
                    {                        
                        $return['id'] = $m[1];
                        $return['host'] = $k;
                        $return['url'] = $url;
                        
                        if(($v['tp'] || $v['ip']) && ($html = dfopen($url)))
                        {      
                        	if($v['gzip'])
                        	{
                        		$html = gzdecode($html);
                        	}
                        	$html = array_iconv($v['c'],$this->Config['charset'], $html);
                        	
                            if($v['tp'] && preg_match($v['tp'],$html,$m) && $m[1])
                            {
                                $return['title'] = $m[1];
                            }
                            
                            if($v['ip'] && preg_match($v['ip'],$html,$m) && $m[1])
                            { 
                                $return['image_src'] = $return['image'] = $m[1];
                            }
                        }  
                    }
                    elseif($v['ppp'])
                    {
                        $vvv = "_parse_video_" . str_replace(array('-','.'),"_",$k);
                        
                        if(method_exists($this,$vvv))
                        {
                            $return = $this->$vvv($url,$v);
                            
                            if($return && !isset($return['host']))
                            {
                                $return['host'] = $k;
                            }
                        }
                    }
                    
                    break;
                }
            }
        }  
        
        return $return;
    }
    function _parse_video_tudou_com($url,$v)
    {
        $return = array();
        
        if(preg_match('~\d+~',$url) && ($html = dfopen($url)))
        {
        	if($v['gzip'])
        	{
        		$html = gzdecode($html);
        	}
        	$html = array_iconv($v['c'], $this->Config['charset'], $html);
        	
                        $iid = 0;
            $icode = '';
            
            if(preg_match('~(?:(?:[\?\&\#]iid\=)|(?:\d+i))(\d+)~',$url,$m) && $m[1])
            {
                $iid = $m[1];
            }
            elseif(preg_match('~(?:(?:\,iid\s*=)|(?:\,defaultIid\s*=)|(?:\.href\)\s*\|\|))\s*(\d+)~',$html,$m) && $m[1])
            {
                $iid = $m[1];
            }
            
            if(is_numeric($iid) && $iid > 0)
            {
            	if(preg_match('~'.$iid.'.*?icode\s*[\:\=]\s*[\"\']([\w\d\-\_]+)[\"\']~s',$html,$m) && $m[1])
            	{
            		$icode = $m[1];
            	}
            }
            
            if($icode)
            {
                $return['id'] = $icode;
                $return['url'] = $url;
                $return['title'] = '';
                
                if($v['tp'] && preg_match($v['tp'],$html,$m) && $m[1])
                {
                    $return['title'] = $m[1];
                }
                if(preg_match('~'.$iid.'.*?title\s*[\:\=]\s*[\"\']([^\"\']+?)[\"\']~s',$html,$m) && $m[1])
                {
                    $return['title'] .= " " . $m[1];
                }                
                
                if(preg_match('~'.$iid.'.*?pic\s*[\:\=]\s*[\"\']([^\"\']+?)[\"\']~s',$html,$m) && $m[1])
                {
                    $return['image_src'] = $return['image'] = $m[1];
                }
            }
        }
        
        return $return;
    }
    function _parse_video_youku_com($url, $v)
    {
    	$return = array();
    	
    	if(preg_match('~\/v\_playlist\/.+?\.htm~',$url) && ($html = dfopen($url)))
        {
        	if($v['gzip'])
        	{
        		$html = gzdecode($html);
        	}
        	$html = array_iconv($v['c'], $this->Config['charset'], $html);
        	
        	$id = '';
        	if(preg_match('~\_href\s*\=\s*[\"\']iku\:\/\/.+?http\:\/\/v\.youku\.com\/v\_show\/id\_([\w\d]+)\.htm~i', $html, $m) && $m[1])
        	{
        		$id = $m[1];
        	}
        	
        	if($id)
        	{
        		$return['id'] = $id;
        		$return['url'] = $url;
        		$return['title'] = '';
        		
        		if($v['tp'] && preg_match($v['tp'],$html,$m) && $m[1])
                {
                	$return['title'] = $m[1];
                }
                if(preg_match('~\_href\s*\=\s*[\"\']iku\:\/\/.+?'. $id .'.+?\|([\%\w\d]+?)\|~', $html, $m) && $m[1])
                {
                	$m[1] = array_iconv($v['c'], $this->Config['charset'], urldecode($m[1]));
                	
                	$return['title'] .= " " . $m[1];
                }
                            
                if($v['ip'] && preg_match($v['ip'],$html,$m) && $m[1])
                { 
                	$return['image_src'] = $return['image'] = $m[1];
                }
        	}
        }
    	
    	return $return;
    }

    
    function _parse_video_image($image_url)
    {
        $return = '';
        
                if ($image_url)
        {
            $img_src_md5 = md5($image_url);
            $img_path = RELATIVE_ROOT_PATH . 'images/video_img/' . $img_src_md5[0] . $img_src_md5[1] .
                '/';
            $img_name = $img_src_md5[2] . $img_src_md5[3] . crc32($image_url) . '.jpg';
            $video_img = $img_path . $img_name;

            if (!is_file($video_img) && ($temp_image = dfopen($image_url)))
            {
                Load::lib('io');
                $IoHandler = new IoHandler();

                if (!is_dir($img_path))
                {
                    $IoHandler->MakeDir($img_path);
                }
                
                $IoHandler->WriteFile($video_img,$temp_image);
                
                if (!is_image($video_img))
                {
                    $IoHandler->DeleteFile($video_img);
                    $video_img = '';
                }
                else
                {
                    if($this->Config['ftp_on'])
                    {
                        $ftp_result = ftpcmd('upload',$video_img);
                        if($ftp_result > 0)
                        {
                            $IoHandler->DeleteFile($video_img);
                        }
                    }
                }
            }
            
            $return = $video_img;
        }
        
        return $return;
    }     
    
    
    function _parse_url_image($data,$image_url)
    {
        $__is_image = false;

        Load::logic('image');
        $ImageLogic = new ImageLogic();
            
        $uid = $data['uid'];
        $username = $data['username'];
        $image_id = $ImageLogic->add($uid, $username);
        
        $p = array(
        	'id' => $image_id,
        
        	'tid' => $data['tid'],
        	'image_url' => $image_url,
        );
        $ImageLogic->modify($p);
        
        $image_path = RELATIVE_ROOT_PATH . 'images/topic/' . face_path($image_id) . '/';
        $image_name = $image_id . "_o.jpg";
        $image_file = $image_path . $image_name;

        if (!is_file($image_file))
        {
            $image_file_small = $image_path . $image_id . "_s.jpg";

            Load::lib('io');
            $IoHandler = new IoHandler();
            
            if (!is_dir($image_path))
            {
                $IoHandler->MakeDir($image_path);
            }
            
            if (($temp_image = dfopen($image_url)) && ($IoHandler->WriteFile($image_file,$temp_image)) && is_image($image_file))
            {
                $IoHandler->WriteFile($image_file_small, $temp_image);

                list($image_width, $image_height, $image_type, $image_attr) = getimagesize($image_file);

                $result = makethumb($image_file, $image_file_small, min($this->Config['thumbwidth'],
                    $image_width), min($this->Config['thumbwidth'], $image_height), $this->Config['maxthumbwidth'],
                    $this->Config['maxthumbheight']);

                $image_size = filesize($image_file);
                
                
                $site_url = '';
                if($this->Config['ftp_on'])
                {
                    $site_url = ConfigHandler::get('ftp','attachurl');
                    
                    $ftp_result = ftpcmd('upload',$image_file);
                    if($ftp_result > 0)
                    {
                        ftpcmd('upload',$image_file_small);
                        
                        $IoHandler->DeleteFile($image_file);
                        $IoHandler->DeleteFile($image_file_small);
                    }
                }
                

                $p = array(
		        	'id' => $image_id,
		        
		        	'site_url' => $site_url,
		        	'photo' => $image_file,
                	'name' => basename($image_url),
					'filesize' => $image_size,
					'width' => $image_width,
					'height' => $image_height,
		        );
		        $ImageLogic->modify($p);

                $__is_image = true;
            }
        }

        if (false === $__is_image && $image_id > 0)
        {
            $ImageLogic->delete($image_id);
            
            $image_id = 0;
        }
        
        return $image_id;
    }
    
    function _process_at_uids($data,$at_uids)
    {
        $_at_uids = array();
        
        $timestamp = time();
        
        $tid = $data['tid'];
                         
        foreach ($at_uids as $at_uid)
        {
            if($at_uid > 0 && $at_uid!=$data['uid'] && check_BlackList($at_uid,$data['uid']) && !($this->DatabaseHandler->FetchFirst("select * from `" . TABLE_PREFIX .
            "topic_mention` where `tid`='$tid' and `uid`='$at_uid'")))
            {
                $_at_uids[$at_uid] = $at_uid;
                
                $this->DatabaseHandler->Query("insert into `" . TABLE_PREFIX . "topic_mention` (`tid`,`uid`,`dateline`) values ('{$tid}','{$at_uid}','{$timestamp}')");
                
                $this->DatabaseHandler->Query("update `" . TABLE_PREFIX .
                    "members` set `at_new`=`at_new`+1 , `at_count`=`at_count`+1 where `uid`='$at_uid'");
                    
                                $user_notice = $this->DatabaseHandler->FetchFirst("select `uid`,`username`,`at_new`,`email`,`notice_at`,`nickname` from `" .
            TABLE_PREFIX . "members` where `uid`='$at_uid'");
                if($user_notice)
                {
                    if ($user_notice['notice_at'] == 1)                     {
                        if ($this->Config['notice_email'] == 1)     
                        {
                            if (!$this->noticeConfig)
                            {
                                $this->noticeConfig = ConfigHandler::get('email_notice');
                            }
    
                            if(!function_exists('send_mail'))
                            {
                                Load::lib('mail');
                                $mail_to = $user_notice['email'];
                            }    
    
                            $mail_subject = "{$this->noticeConfig['at']['title']}";
                            $mail_content = "{$this->noticeConfig['at']['content']}";
                            $send_result = send_mail($mail_to, $mail_subject, $mail_content, array(), 3, false);
                        }
                        else
                        {
                                                        if(!$NoticeLogic)
                            {
                                Load::logic('notice');
                                $NoticeLogic = new NoticeLogic();
                            }
                            
                            $NoticeLogic->Insert_Cron($user_notice['uid']);
                        }
                    }
                    
                                        if ($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config))
                    {
                        imjiqiren_send_message($user_notice, 't', $this->Config);
                    }
                    
                                        if ($this->Config['sms_enable'] && sms_init($this->Config))
                    {
                        sms_send_message($user_notice, 't', $this->Config);
                    }
                }                          
            }
        }
    }
    
    function _process_urls($data,$urls,$is_modify=false)
    {
        $tid = $data['tid'];
        $timestamp = time();
        
        foreach($urls as $k=>$v)
        {
            $url_type = $v['type'];
            
            if('flash'==$url_type || 'video'==$url_type)
            {                
                $videos = $v[$url_type];                
                $video_hosts = $videos['host'];
                $video_link = $videos['id'];
                $video_url = $videos['url'];
                if($is_modify && $data['videoid'] > 0)                 {
                    $topic_video = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic_video where `id`='{$data['videoid']}'");
                    if($topic_video['video_url']==$video_url)
                    {
                        return ;
                    }
                    else
                    {
                        $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."topic_video where `id`='{$data['videoid']}'");
                    }
                }                
                $videos['image_local'] = '';
                if($videos['image_src'])
                {
                    $videos['image_local'] = $this->_parse_video_image($videos['image_src']);
                }
                $video_img = $videos['image_local'];
                $video_img_url = '';
                if($video_img)
                {
                    $video_img_url = ($this->Config['ftp_on'] ? ConfigHandler::get('ftp','attachurl') : "");
                }
                
                $this->DatabaseHandler->Query("insert into `" . TABLE_PREFIX .
                "topic_video`(`uid`,`tid`,`username`,`video_hosts`,`video_link`,`video_url`,`video_img`,`video_img_url`,`dateline`) values ('" .
                $data['uid'] . "','" . $data['tid'] . "','" . $data['username'] . "','" . $video_hosts . "','" . $video_link .
                "','" . $video_url . "','" . $video_img . "','$video_img_url','{$timestamp}')");
            
                $videoid = $this->DatabaseHandler->Insert_ID();
                
                if($videoid > 0)
                {
                    $this->DatabaseHandler->Query("update `" . TABLE_PREFIX . "topic` set `videoid`='{$videoid}' where `tid`='{$data['tid']}'");
                }                
            }
            elseif('music'==$url_type)
            {
                if($is_modify && $data['musicid'] > 0)
                {
                    $topic_music = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic_music where `id`='{$data['musicid']}'");
                    if($topic_music['music_url']==$v['url'])
                    {
                        return ;
                    }
                    else
                    {
                        $this->DatabaseHandler->Query("delete from ".TABLE_PREFIX."topic_music where `id`='{$data['musicid']}'");
                    }
                }                
                
                $this->DatabaseHandler->Query("insert into `" . TABLE_PREFIX .
                "topic_music`(`uid`,`tid`,`username`,`music_url`,`dateline`) values ('" .
                $data['uid'] . "','" . $data['tid'] . "','" . $data['username'] . "','{$v['url']}','{$timestamp}')");
            
                $musicid = $this->DatabaseHandler->Insert_ID();
                
                if($musicid > 0)
                {
                    $this->DatabaseHandler->Query("update `" . TABLE_PREFIX . "topic` set `musicid`='{$musicid}' where `tid`='{$data['tid']}'");
                }
            }
            elseif('image'==$url_type)
            {
            	Load::logic('image');
            	$ImageLogic = new ImageLogic();
            	
                if($is_modify && $data['imageid'])
                {
                    $topic_image = $ImageLogic->get_info($data['imageid']);
                    if($topic_image['image_url']==$v['url'])
                    {
                        return ;
                    }
                    else
                    {
                        $ImageLogic->delete($data['imageid']);
                    }
                }
                
                $this->_parse_url_image($data,$v['url']);
            }
        }
    }
    
    
    function is_qun_member($qid, $uid)
    {
		Load::logic('qun');
		$QunLogic = new QunLogic();
		return $QunLogic->is_qun_member($qid, $uid);
    }
    
    
    function check_view_perm($uid, $type)
    {
		return true;
    }
    
    
    function _update_level($uid=0)
    {
    	if($uid > 0)
    	{	
    		    		$experience = ConfigHandler::get('experience');
			$exp_list = $experience['list'];
			
						$member_info = $this->DatabaseHandler->FetchFirst("select `credits`,`level` from ".DB::table('members')." where uid='{$uid}' ");
 
			
    					$mylevel = $member_info['level'];
			
						$my_credits = $member_info['credits'];
			
						foreach ($exp_list as $v) {
				if($my_credits >= $v['start_credits'])
				{	
					$my_level = $v['level'];
				}
			}
			
			if($mylevel !=  $my_level)
			{
								$sql = "update `" . TABLE_PREFIX . "members` set `level`='{$my_level}' where `uid`='".MEMBER_ID."'";
           		$this->DatabaseHandler->Query($sql);
		
			}

    	}
    	
    }
    
	function GetMedal($medalid=0,$uid=0)
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
    
    
    function GetParentTopic($topic_list, $get_parent = 0)
    {
    	$parent_list = array();
		if ($topic_list) 
		{
						$parent_id_list = array();
			foreach ($topic_list as $row) 
			{
				if($get_parent && 0 < ($p = (int) $row['parent_id'])) 
				{
					$parent_id_list[$p] = $p;
				}
				if (0 < ($p = (int) $row['top_parent_id'])) 
				{
					$parent_id_list[$p] = $p;
				}
			}

			if ($parent_id_list) 
			{
				$parent_list = $this->Get($parent_id_list);
			}
		}
		
		return $parent_list;
    }
    
    
    function GetForwardContent($content)
    {
    	    	$seprator = $this->ForwardSeprator;
    	$seprator = trim($seprator);
    	$strpos = strpos($content, $seprator);
    	
    	if(false !== $strpos)
    	{
    		$content = substr($content, 0, $strpos);
    	}
    	
    	return $content;
    }
}

?>