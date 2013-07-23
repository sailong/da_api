<?php
/**
 * 文件名：schedule.mod.php
 * 版本号：1.0
 * 作     者：狐狸<foxis@qq.com>
 * 创建时间：2010年10月29日
 * 功能描述: 即时任务执行模块
 * @todo 增加同步到人人、开心接口 2011年9月22日
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
    var $ScheduleInfo = array();


	function ModuleObject($config)
	{
		$this->MasterObject($config);
        

		$this->Execute();
	}

	function Execute()
	{
        ob_start();	
           
		switch($this->Code)
		{
            case 'execute':
                $this->DoExecute();
                break;		      

			default:
				$this->Main();
				break;
		}
        
        response_text(ob_get_clean());
	}

	function Main()
    {
        response_text('正在建设中……');
    }
    
    function DoExecute()
    {
        $id = ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);        
        
                Load::functions('schedule');
        
                if(!($this->ScheduleInfo = schedule_get($id, 1)))
        {
            return false;
        }
        
                if($this->ScheduleInfo['type'] && method_exists($this,($method = "_exec_{$this->ScheduleInfo['type']}")))
        {
                        $this->_init_uid($this->ScheduleInfo['uid']);
            
            $ret = $this->$method();
            
                        response_text((string) $ret);
        }                    
    }
    function _init_uid($uid = 0)
    {
        $uid = max(0, (int) $uid);
        if(!defined('MEMBER_ID') && $uid > 0 && ($members = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='$uid'")))
        {
            define("MEMBER_ID",(int) $members['uid']);
			define("MEMBER_UCUID",(int) $members['ucuid']);
			define("MEMBER_NAME",$members['username']);
			define("MEMBER_NICKNAME",$members['nickname']);
			define("MEMBER_ROLE_TYPE",$members['role_type']);
			define("MEMBER_FOLLOW",(int) $members['follow_count']);
			define("MEMBER_FANS",(int) $members['fans_count']);
			define("MEMBER_TOPIC",(int) $members['topic_count']);
        }
        else
        {
            response_text('');
        }            
    }    
    function _exec_imjiqiren_send()
    {
        $return = '';
        $to = '';
        $message = '';
        
        if(imjiqiren_init($this->Config))
        {
            @extract($this->ScheduleInfo['vars']);
            
            $return = imjiqiren_send($to,$message);
        }
        
        return $return;
    }    
    function _exec_sms_send()
    {
        $return = '';
        $to = '';
        $message = '';
        
        if(sms_init($this->Config))
        {
            @extract($this->ScheduleInfo['vars']);
            
            $return = sms_send($to,$message);
        }
        
        return $return;
    }    
    function _exec_syn_to_sina()
    {
        $return = '';
        
        $data = array();
        if(sina_weibo_init($this->Config))
        {
            @extract($this->ScheduleInfo['vars']);
            
            $_POST['syn_to_sina'] = 1;
            $GLOBALS['jsg_tid'] = $data['tid'];
            $GLOBALS['jsg_totid'] = $data['totid'];
            $GLOBALS['jsg_message'] = $data['content'];
            $GLOBALS['jsg_imageid'] = $data['imageid'];

            require_once(ROOT_PATH . 'include/xwb/sina.php');
            require_once(XWB_plugin::hackFile('newtopic'));
        }
        
        return $return;
    }
    function _exec_syn_to_qqwb()
    {
        $return = '';
        
        if(false != ($qqwb_config = qqwb_init($this->Config)))
        {
            @extract($this->ScheduleInfo['vars']);
            
            include(ROOT_PATH . 'include/qqwb/to_qqwb.inc.php');
        }        
        
        return $return;
    }
    function _exec_syn_qqwb_face()
    {
        $return = '';
        if(qqwb_init($this->Config) && (ConfigHandler::get('qqwb','is_sync_face')))
        {
            @extract($this->ScheduleInfo['vars']);
            
            
            $uid = (is_numeric($uid) ? $uid : 0);
            if($uid < 1)
            {
                $uid = MEMBER_ID;
            } 
            if($uid < 1)
            {
                return ;
            }
            $user_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."members where `uid`='$uid'");
            if(!$user_info || $user_info['face'])
            {
                return ;
            }
            
            
            $src_x = $src_y = $src_w = $src_h = 0;
            $face = ($face ? $face : ($this->Post['face'] ? $this->Post['face'] : $this->Get['face']));
            if(!$face || false === strpos($face,':/'.'/'))
            {
                return ;
            }
            $src_file = ROOT_PATH . 'cache/misc/'.md5($face).'.jpg';
    
            
            Load::lib('io');
            $IoHandler = new IoHandler();
    
            
            if(false != ($image_data = dfopen($face)))
            {
                $IoHandler->WriteFile($src_file,$image_data);
            } 
            if(!is_image($src_file))
            {
                $IoHandler->DeleteFile($src_file);
    
                return ;
            }
            
    
            
            $image_path = RELATIVE_ROOT_PATH . 'images/face/' . face_path($uid);
            if(!is_dir($image_path))
            {
                $IoHandler->MakeDir($image_path);
            }
    
            
            $image_file = $dst_file = $image_path . $uid . '_b.jpg';
            $make_result = makethumb($src_file,$dst_file,128,128,0,0,$src_x,$src_y,$src_w,$src_h);
    
            
            $image_file_small = $dst_file = $image_path . $uid . '_s.jpg';
            $make_result = makethumb($src_file,$dst_file,50,50,0,0,$src_x,$src_y,$src_w,$src_h);
            
            
            $face_url = '';
            if($this->Config['ftp_enable'])
            {
                $face_url = ConfigHandler::get('ftp','attachurl');
                
                $ftp_result = ftpcmd('upload',$image_file);
                if($ftp_result > 0)
                {
                    ftpcmd('upload',$image_file_small);
                    
                    $IoHandler->DeleteFile($image_file);
                    $IoHandler->DeleteFile($image_file_small);
                }
            }
            
    
            
            $sql = "update `".TABLE_PREFIX."members` set `face_url`='$face_url',`face`='{$dst_file}' where `uid`='".$uid."'";
    		$this->DatabaseHandler->Query($sql);
    
            
            $IoHandler->DeleteFile($src_file);
    
            
            if($this->Config['extcredits_enable'] && $uid > 0)
    		{
    			
    			update_credits_by_action('face',$uid);
    		}
        }
        
        return $return;
    }
	
    function _exec_syn_to_kaixin()
    {
    	$ret = '';
    	
    	if(kaixin_init($this->Config))
    	{
    		$data = $this->ScheduleInfo['vars'];
    		
    		$ret = kaixin_sync($data);
    	}
    	
    	return $ret;
    }
	
    function _exec_syn_to_renren()
    {
    	$ret = '';
    	
    	if(renren_init($this->Config))
    	{
    		$data = $this->ScheduleInfo['vars'];
    		
    		$ret = renren_sync($data);
    	}
    	
    	return $ret;
    }

}

?>
