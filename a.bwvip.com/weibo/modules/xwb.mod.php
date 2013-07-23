<?php
/**
 * 文件名：xwb.mod.php
 * 版本号：1.0
 * 最后修改时间：2010年12月6日 17:15:24
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 新浪微博接口模块
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
        
        
        set_time_limit(300);
        

		if ($this->Config['sina_enable'] && sina_weibo_init($this->Config))
		{
			;
		}
		else
		{
			$this->Messager("整合新浪微博的功能未开启",null);
		}

		$this->Execute();
	}

	
	function Execute()
	{
		ob_start();
		switch ($this->Code)
        {
			case 'enter':
				$this->Enter();
				break;

            case 'synctopic':
                $this->SyncTopic();
                break;
                
            case 'syncreply':
                $this->SyncReply();
                break;

			default:
				$this->Main();
		}

		exit;
	}


	function Main()
	{
		
				require_once ROOT_PATH . 'include/xwb/sina.php';

						XWB_plugin::init();
		XWB_plugin::request();

	}


    
	function Enter()
	{
	    $share_time = $this->Request['share_time'];
		if (MEMBER_ID > 0 && $share_time>0 && ($share_time + 300 > time()))
		{
			$bind_info = sina_weibo_bind_info(MEMBER_ID);

            if($share_time==$bind_info['share_time'])
            {
                $_site_url = substr($this->Config['site_url'],strpos($this->Config['site_url'],':/'.'/') + 3);

                $share_msg = "我刚绑定了新浪微博帐户，可以使用新浪微博帐户登录{$this->Config['site_name']}(".$_site_url.")、不再担心忘记密码；还可以在{$this->Config['site_name']}发微博同步发到新浪上，吸引更多人关注；你也来试试吧 ".get_full_url($this->Config['site_url'],"index.php?mod=tools&code=sina")." ";

                Load::logic('topic');
                $TopicLogic = new TopicLogic($this);

                
                $_POST['syn_to_sina'] = (sina_weibo_bind_setting($bind_info) ? 1 : 0);
                $add_result = $TopicLogic->Add($share_msg);

                
                $this->DatabaseHandler->Query("update ".TABLE_PREFIX."xwb_bind_info set `share_time`='".mt_rand(1,1111111111)."' where `uid`='".MEMBER_ID."'");
            }
		}

        exit;
	}

    function SyncTopic()
    {
        $sina = ConfigHandler::get('sina');
        if(!$sina['is_synctopic_tojishigou'])
        {
            return ;
        }
        
        $info = array();
        
        $uid = max(0, (int) ($this->Post['uid'] ? $this->Post['uid'] : $this->Get['uid']));
        if(!$uid) 
        {
            $uid = MEMBER_ID;
        }
        
        if(!$uid)
        {
            return ;
        }
        
        $info = sina_weibo_bind_info($uid);      
        
        if(!$info)
        {
            return ;
        }
        
        $uid = max(0, (int) $info['uid']);
        if(!$uid)
        {
            return ;
        }
        
        $sina_uid = $info['sina_uid'];
        if(!$sina_uid)
        {
            return ;
        }

        if(!(sina_weibo_synctopic_tojishigou($uid)))
        {
            return ;
        }
        
        if($sina['syncweibo_tojishigou_time'] > 0 && ($info['last_read_time'] + $sina['syncweibo_tojishigou_time'] > time()))
        {
            return ;
        }        
        
        $member = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='{$uid}'");
        if(!$member)
        {
            return ;
        }
        
        if(!($this->MemberHandler->HasPermission('xwb','__synctopic',0,$uid)))
        {
            return ;
        }
        

        require_once ROOT_PATH . 'include/xwb/sina.php';
        $wb = XWB_plugin::getWB();
        $datas = $wb->getUserTimeline(null,$sina_uid);
        
        
        if($datas)
        {
            krsort($datas);
                        
            
            Load::logic('topic');
            $TopicLogic = new TopicLogic($this);

            foreach($datas as $data)
            {
                $mid = $data['id'];

                if(!($this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."xwb_bind_topic where `mid`='{$mid}'")) && ($content = trim(strip_tags(array_iconv('utf-8',$this->Config['charset'],$data['text'] . (isset($data['retweeted_status']) ? " /"."/@{$data['retweeted_status']['user']['name']}: {$data['retweeted_status']['text']}" : ""))))))
                {                    
                    $_t = time();
                    if($data['created_at'])
                    {
                        $_t = strtotime($data['created_at']);
                    }
                    $_t = (is_numeric($_t) ? $_t : 0);                    
                    $add_datas = array(
                        'content' => $content,
                        'from' => 'sina',
                        'type' => 'first',
                        'uid' => $uid,
                        'timestamp' => $_t,
                    );
                    $add_result = $TopicLogic->Add($add_datas);
                    
                    if(is_array($add_result) && count($add_result))
                    {
                        $tid = max(0, (int) $add_result['tid']);
                        
                        if($tid) 
                        {
                            if($sina['is_syncimage_tojishigou'] && $data['original_pic'])
                            {
                                $TopicLogic->_parse_url_image($add_result,$data['original_pic']);
                            }
                            if($sina['is_syncimage_tojishigou'] && $data['retweeted_status']['original_pic'])
                            {
                                $TopicLogic->_parse_url_image($add_result,$data['retweeted_status']['original_pic']);
                            }
                            
                            $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."xwb_bind_topic (`tid`,`mid`) values ('{$tid}','{$mid}')");
                        }
                    }
                    else
                    {
                    }
                }
            }            
        }
        
        $this->DatabaseHandler->Query("update ".TABLE_PREFIX."xwb_bind_info set `last_read_time`='".time()."',`last_read_id`='{$mid}' where `sina_uid`='{$sina_uid}'");
    }
    
    function SyncReply()
    {
        $sina = ConfigHandler::get('sina');
        if(!$sina['is_syncreply_tojishigou'])
        {
            return ;
        }
        
        $tid = max(0, (int) ($this->Post['tid'] ? $this->Post['tid'] : $this->Get['tid']));
        if(!$tid)
        {
            return ;
        }
        
        $info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."xwb_bind_topic where `tid`='$tid'");
        if(!$info)
        {
            return ;
        }
        
        $mid = $info['mid'];
        if(!$mid)
        {
            return ;
        }
        
        if($sina['syncweibo_tojishigou_time'] > 0 && ($info['last_read_time'] + $sina['syncweibo_tojishigou_time'] > time()))
        {
            return ;
        }

        if(!($topic_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic where `tid`='$tid'")))
        {
            return ;
        }
        
        if(!(sina_weibo_syncreply_tojishigou($topic_info['uid'])))
        {
            return ;
        }
        
        if(!($this->MemberHandler->HasPermission('xwb','__syncreply',0,$topic_info['uid'])))
        {
            return ;
        }        
        
        
        require_once ROOT_PATH . 'include/xwb/sina.php';
        $wb = XWB_plugin::getWB();
        $datas = $wb->getComments($mid);
        
        
        if($datas)
        {
            krsort($datas);
            
            
            Load::logic('topic');
            $TopicLogic = new TopicLogic($this);
            
            foreach($datas as $data)
            {
                $mid = $data['id'];
                
                $sina_uid = $data['user']['id'];
                
                if(($bind_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."xwb_bind_info where `sina_uid`='$sina_uid'")) && !($this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."xwb_bind_topic where `mid`='{$mid}'")) && ($content = trim(strip_tags(array_iconv('utf-8',$this->Config['charset'],$data['text'] . (isset($data['retweeted_status']) ? " /"."/@{$data['retweeted_status']['user']['name']}: {$data['retweeted_status']['text']}" : ""))))))
                {
                    $_t = time();
                    if($data['created_at'])
                    {
                        $_t = strtotime($data['created_at']);
                    }
                    $_t = (is_numeric($_t) ? $_t : 0);                    
                    $add_datas = array(
                        'content' => $content,
                        'from' => 'sina',
                        'type' => 'reply',
                        'uid' => $bind_info['uid'],
                        'timestamp' => $_t,
                    );
                    $add_result = $TopicLogic->Add($add_datas);
                    
                    if(is_array($add_result) && count($add_result))
                    {
                        $_tid = max(0, (int) $add_result['tid']);
                        
                        if($_tid) 
                        {
                            if($sina['is_syncimage_tojishigou'] && $data['original_pic'])
                            {
                                $TopicLogic->_parse_url_image($add_result,$data['original_pic']);
                            }
                            
                            $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."xwb_bind_topic (`tid`,`mid`) values ('{$_tid}','{$mid}')");
                        }
                    }
                    else
                    {
                    }
                }
            }
        }
        
        $this->DatabaseHandler->Query("update `".TABLE_PREFIX."xwb_bind_topic` set `last_read_time`='".time()."' where `tid`='{$tid}'");
    }

}


?>
