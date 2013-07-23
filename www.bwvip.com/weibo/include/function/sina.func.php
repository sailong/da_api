<?php
/**
 * 文件名：sina.func.php
 * 版本号：1.0
 * 最后修改时间：2010年12月6日 17:15:24
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 新浪微博接口函数
 */

if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}


function sina_enable($sys_config = array())
{
	return sina_weibo_enable($sys_config);
}
function sina_weibo_enable($sys_config = array())
{
	if(!$sys_config) 
	{
		$sys_config = ConfigHandler::get();
	}
	
	if(!$sys_config['sina_enable']) 
	{
		return false;
	}
    
    if(!$sys_config['sina'])
    {
        $sys_config['sina'] = ConfigHandler::get('sina');
    }
	
	return $sys_config;
}


function sina_weibo_login($ico='s')
{
	$return = '';
	
	if (($sys_config = sina_weibo_enable()) && $sys_config['sina']['is_account_binding']) 
	{
		$icos = array
		(
			's' => $sys_config['site_url'] . '/include/xwb/images/bgimg/loginHeader_16.png',
			'm' => $sys_config['site_url'] . '/include/xwb/images/bgimg/loginHeader_24.png',
			'b' => $sys_config['site_url'] . '/include/xwb/images/bgimg/sina_login_btn.gif',
		);
		$ico = (isset($icos[$ico]) ? $ico : 's');
		$img_src = $icos[$ico];
		
		$return = '<a title="使用新浪微博帐号登录 '.$sys_config['site_name'].'" href="#" onclick="window.location.href=\''.$sys_config['site_url'].'/index.php?mod=xwb&m=xwbAuth.login\';return false;"><img src="'.$img_src.'" alt="使用新浪微博帐号登录 '.$sys_config['site_name'].'" /></a>';
	}
	
	return $return;
}

function sina_weibo_bind($uid=0)
{
    $bind_info = sina_weibo_bind_info($uid);
    
    return ($bind_info && $bind_info['sina_uid'] > 0);
}
function sina_weibo_has_bind($uid=0)
{
    return sina_weibo_bind($uid);
}

function sina_weibo_bind_setting($uid=0)
{
    $return = true;
    
    $row = (is_array($uid) ? $uid : sina_weibo_bind_info((int) $uid));
    
    if($row['profile'] && false!==strpos($row['profile'],'bind_setting') && preg_match('~[\"\']bind_setting[\"\']\s*\:\s*0~',$row['profile']))
    {
        $return = false;
    } 
    
    return $return;   
}
function sina_weibo_synctopic_tojishigou($uid=0)
{
    $return = false;
    
    $row = (is_array($uid) ? $uid : sina_weibo_bind_info((int) $uid));
    
    if($row['profile'] && false!==strpos($row['profile'],'synctopic_tojishigou') && preg_match('~[\"\']synctopic_tojishigou[\"\']\s*\:\s*1~',$row['profile']))
    {
        $return = true;
    } 
    
    return $return;
}
function sina_weibo_syncreply_tojishigou($uid=0)
{
    $return = false;
    
    $row = (is_array($uid) ? $uid : sina_weibo_bind_info((int) $uid));
    
    if($row['profile'] && false!==strpos($row['profile'],'syncreply_tojishigou') && preg_match('~[\"\']syncreply_tojishigou[\"\']\s*\:\s*1~',$row['profile']))
    {
        $return = true;
    } 
    
    return $return;
}

function sina_weibo_bind_info($uid=0)
{
    static $DatabaseHandler,$sXWB_bind_infos;
	
	$return = array();
	
	$uid = max(0,(int) ($uid ? $uid : MEMBER_ID));
	
    if($uid > 0)
    {		
        if(null===($return = $sXWB_bind_infos[$uid]))
		{
			if(!$DatabaseHandler) $DatabaseHandler = Obj::registry('DatabaseHandler');

			$query = $DatabaseHandler->Query("select * from ".TABLE_PREFIX."xwb_bind_info where `uid`='{$uid}'");
			$return = $query->GetRow();
			
			$sXWB_bind_infos[$uid] = $return;
		}
    }
    
    return $return;
}
function sina_weibo_bind_topic($tid)
{
    static $DatabaseHandler,$sXWB_bind_topics;
	
	$return = array();
	
	$tid = max(0,(int) $tid);
	
    if($tid > 0)
    {		
        if(null===($return = $sXWB_bind_topics[$tid]))
		{
			if(!$DatabaseHandler) $DatabaseHandler = Obj::registry('DatabaseHandler');

			$query = $DatabaseHandler->Query("select * from ".TABLE_PREFIX."xwb_bind_topic where `tid`='{$tid}'");
			$return = $query->GetRow();
			
			$sXWB_bind_topics[$tid] = $return;
		}
    }
    
    return $return;
}


function sina_weibo_bind_icon($uid=0)
{	
	$return = '';
	
	$uid = max(0,(int) ($uid ? $uid : MEMBER_ID));
	
	if ($uid > 0 && ($sys_config = sina_weibo_enable())) 
	{
		
		$return = "<img src='{$sys_config['site_url']}/include/xwb/images/bgimg/sinawebo_off.gif' alt='未绑定新浪微博' />";
		
		if (sina_weibo_bind($uid)) 
		{
			$return = "<img src='{$sys_config['site_url']}/include/xwb/images/bgimg/sinawebo_on.gif' alt='已经绑定新浪微博' />";
            
            $MemberHandler = Obj::registry('MemberHandler');
            
            if($sys_config['sina']['is_synctopic_tojishigou'] && sina_weibo_synctopic_tojishigou($uid))
            {
                $_read_now = true;
                
                if($sys_config['sina']['syncweibo_tojishigou_time'] > 0)
                {
                    $xwb_bind_info = sina_weibo_bind_info($uid);
                    
                    if($xwb_bind_info['last_read_time'] + $sys_config['sina']['syncweibo_tojishigou_time'] > time())
                    {
                        $_read_now = false;
                    }
                }
                
                if($_read_now && !($MemberHandler->HasPermission('xwb','__synctopic',0,$uid)))
                {
                    $_read_now = false;
                }
                
                if($_read_now)
                {
                    $return .= "<img src='{$sys_config['site_url']}/index.php?mod=xwb&code=synctopic&uid={$uid}' width='0' height='0' style='display:none' />";
                }
            }
            
            if($sys_config['sina']['is_syncreply_tojishigou'] && is_numeric($_GET['code']) && sina_weibo_syncreply_tojishigou($uid) && ($xwb_bind_topic = sina_weibo_bind_topic($_GET['code'])) && ($topic_info = $MemberHandler->DatabaseHandler->FetchFirst("select * from ".TABLE_PREFIX."topic where `tid`='{$_GET['code']}'")))
            {
                $_read_now = true;
                
                if($sys_config['sina']['syncweibo_tojishigou_time'] > 0)
                {
                    if($xwb_bind_topic['last_read_time'] + $sys_config['sina']['syncweibo_tojishigou_time'] > time())
                    {
                        $_read_now = false;
                    }
                }
                
                if($_read_now && !($MemberHandler->HasPermission('xwb','__syncreply',0,$topic_info['uid'])))
                {
                    $_read_now = false;
                }              
                
                if($_read_now)
                {
                    $return .= "<img src='{$sys_config['site_url']}/index.php?mod=xwb&code=syncreply&tid={$_GET['code']}' width='0' height='0' style='display:none' />";
                }
            }
		}            
		
		if (MEMBER_ID>0) 
		{
			$return = "<a href='#' title='新浪微博绑定设置' onclick=\"window.location.href='{$sys_config['site_url']}/index.php?mod=tools&code=sina';return false;\">{$return}</a>";
		}
	}
	
	return $return;
}


function sina_weibo_syn()
{
	$return = '';
	
	$uid = max(0,(int) ($uid ? $uid : MEMBER_ID));
	
	if ($uid > 0 && ($sys_config = sina_weibo_enable()) && (ConfigHandler::get('sina','is_synctopic_toweibo'))) 
	{		
		$row = sina_weibo_bind_info($uid);
		
		$a = $b = $c = $d = $e = '';
		if ($row && $row['sina_uid']) 
		{
			$b = "{$sys_config['site_url']}/include/xwb/images/bgimg/icon_on.gif";
			
			$d = "checked='checked'";
			if (!sina_weibo_bind_setting($row)) 
			{
				$d = "";
			}
			$e = "<label for='syn_to_sina'><i></i><img src='{$b}' title='同步发到新浪微博'/></label>";			
		}
		else 
		{
			$b = "{$sys_config['site_url']}/include/xwb/images/bgimg/icon_off.gif";
			$c = "disabled='disabled'";
			$e = "<a href='{$sys_config['site_url']}/index.php?mod=tools&code=sina' title='开通此功能（将打开新窗口）'><i></i><img src='{$b}' title='同步发到新浪微博'/></a>";			
		}
		
		$return = "{$a}{$e}<input type='checkbox' id='syn_to_sina' name='syn_to_sina' value='1' {$c} {$d} />";
	}
	
	return $return;
}


function sina_weibo_share($tid='')
{
	$return = '';
	
	if(($sys_config = sina_weibo_enable()) && (ConfigHandler::get('sina','is_rebutton_display')))
	{
		$tid = max(0,(int) ($tid ? $tid : $GLOBALS['jsg_tid']));
		
		$uid = max(0,(int) ($uid ? $uid : MEMBER_ID));
		
		$link = "javascript:void((function(s,d,e,r,l,p,t,z,c) {var%20f='http:/"."/v.t.sina.com.cn/share/share.php?appkey={$sys_config[sina][app_key]}',u=z||d.location,p=['&url=',e(u),'& title=',e(t||d.title),'&source=',e(r),'&sourceUrl=',e(l),'& content=',c||'gb2312','&pic=',e(p||'')].join('');function%20a() {if(!window.open([f,p].join(''),'mb', ['toolbar=0,status=0,resizable=1,width=440,height=430,left=',(s.width- 440)/2,',top=',(s.height-430)/2].join('')))u.href=[f,p].join('');}; if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();}) (screen,document,encodeURIComponent,'','','','','',''));";	
		if ($uid > 0 && $tid > 0) 
		{	
			if (sina_weibo_bind($uid)) 
			{
				$link = "{$sys_config['site_url']}/index.php?mod=xwb&m=xwbSiteInterface.share&tid={$tid}";
				$link = "javascript:void( window.open('". urlencode($link). "', '', 'toolbar=0,status=0,resizable=1,width=680,height=500') );";		
			}
		}	
		
		$return = ' | <a title="转发到新浪微博" href="'.$link.'" id="sina_weibo_share">转发到<img src="'.$sys_config['site_url'].'/include/xwb/images/bgimg/icon_logo.png" /></a>';
	}
	
	return $return;
}

?>