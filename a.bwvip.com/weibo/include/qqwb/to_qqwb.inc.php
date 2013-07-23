<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename to_qqwb.inc.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:40 1931872732 809515759 4185 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}


			$tid = (is_numeric($tid) ? $tid : 0);
            if($tid < 1)
            {
                return ;
            }            
            
            $uid = (is_numeric($uid) ? $uid : 0);
            if($uid < 1)
            {
                return ;
            }
            
                        if(false!==strpos($content,'['))
            {
                $face_config = ConfigHandler::get('face');
                
                if(false===strpos($content,'#['))
                {
                    if (preg_match_all('~\[(.+?)\]~', $content, $match))
                    {
                        foreach($match[1] as $k=>$v)
                        {
                            if(isset($face_config[$v]))
                            {
                                $content = str_replace($match[0][$k], '/' . $v, $content);
                            }
                        }
                    }
                }
            }
            $content = array_iconv($this->Config['charset'],'UTF-8',trim(strip_tags($content)));
            if(!$content)
            {
                return ;
            }            
            $content .= ' ' . get_full_url($this->Config['site_url'],'index.php?mod=topic&code=' . $tid);
                        
            
            $qqwb_bind_topic = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."qqwb_bind_topic where `tid`='$tid'");
            if($qqwb_bind_topic)
            {
                return ;
            }
            
            $qqwb_bind_info = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."qqwb_bind_info where `uid`='$uid'");
            if(!$qqwb_bind_info)
            {
                return ;
            }
            
            if(!$qqwb_bind_info['qqwb_username'] || !$qqwb_bind_info['token'] || !$qqwb_bind_info['tsecret'])
            {
                return ;
            }
            
            require_once(ROOT_PATH . 'include/qqwb/qqoauth.php');            
            $QQAuth = new QQOAuth($qqwb_config['qqwb']['app_key'],$qqwb_config['qqwb']['app_secret'],$qqwb_bind_info['token'],$qqwb_bind_info['tsecret']);
            
            $t_result = array();
            if($totid < 1)
            {
                                
            	$imageid = (int) $imageid;                 if($imageid > 0 && ($topic_image = $this->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic_image where `id`='$imageid'")) && (is_file(($p_path = topic_image($imageid,'original')))) && ($ps = getimagesize($p_path)) && ($p_data = file_get_contents($p_path)))
                {
                    $p_name = basename($topic_image['name'] ? $topic_image['name'] : $p_path);
                    if(!$p_name) 
                    {
                        $p_name = mt_rand();
                    }
                    $p_name = array_iconv($this->Config['charset'],'UTF-8',$p_name);
                    
                    $pic = array($ps['mime'],$p_name,$p_data);
                    
                    $t_result = $QQAuth->tAddPic($content,$pic);
                }
                else
                {
                    $t_result = $QQAuth->tAdd($content);
                }
            }
            else
            {
                $reid = $this->DatabaseHandler->ResultFirst("select `qqwb_id` from ".TABLE_PREFIX."qqwb_bind_topic where `tid`='$totid'");
                if($reid < 1)
                {
                    return ;
                }
                
                $t_result = $QQAuth->tReply($reid,$content);
            }
            
            $qqwb_id = (($t_result['data']['id'] && is_numeric($t_result['data']['id'])) ? $t_result['data']['id'] : 0);
            if($qqwb_id > 0)
            {
                $return = $this->DatabaseHandler->Query("insert into ".TABLE_PREFIX."qqwb_bind_topic (`tid`,`qqwb_id`) values ('$tid','$qqwb_id')");
            }

?>