<?php
/*******************************************************************
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename jishigou.php $
 *
 * @Author 狐狸<foxis@qq.com> $
 *
 * @Date 2010-12-06 04:58:24 $
 *******************************************************************/ 


if( !defined('IS_IN_XWB_PLUGIN') ){
    exit('Access Denied!');
}


function jsg_sys_config($cfg='')
{
	static $static_jsg_sys_configs;
	
	if(!($this_config = $static_jsg_sys_configs[$cfg]))
	{
		$this_config = array();
		if (!$cfg) 
		{
			include(XWB_S_ROOT . '/setting/settings.php');		
			$this_config = $config;				
			
			
			if ($config['ucenter_enable']) 
			{
				if (!defined('ROOT_PATH')) 
				{
					define('ROOT_PATH',XWB_S_ROOT . '/');
				}
				
				include(XWB_S_ROOT . '/setting/constants.php');
			}
		}
		else 
		{
			include(XWB_S_ROOT . "/setting/{$cfg}.php");
			$this_config = $config[$cfg];
		}
		
		$static_jsg_sys_configs[$cfg] = $this_config;
	}
	return $this_config;
}


function jsg_showmessage($message, $redirectto = null, $time = null)
{	
	$to_title=($redirectto==='' or $redirectto==-1)?"返回上一页":"跳转到指定页面";
    
    if($time!==null)
	{
		$url_redirect = ($redirectto?'<meta http-equiv="refresh" content="' . $time . '; URL=' . $redirectto . '">':null);
	}

	$message .= jsg_sina_footer();

	include(XWB_P_ROOT . '/tpl/jsg_showmessage.tpl.php');
	exit;
}


function jsg_face_path($uid) 
{
	$key = "ww"."w.jis"."higo"."u.c"."om"; 	$hash = md5($key."\t".$uid."\t".strlen($uid)."\t".$uid % 10);
	$path = $hash{$uid % 32} . "/" . abs(crc32($hash) % 100) . "/";
	
	return $path;
}


function jsg_make_dir($dir_name, $mode = 0777)
{
	if(false!==strpos($dir_name,'\\')) 
	{
		$dir_name = str_replace("\\", "/", $dir_name);
	}
	if(false!==strpos($dir_name,'/'.'/')) 
	{
		$dir_name = preg_replace("#(/"."/+)#", "/", $dir_name);
	}
	if (is_dir($dir_name))
	{
		return true;
	}
    
    $dirs = '';
    $_dir_name = $dir_name;
	$dir_name = explode("/", $dir_name);
    if('/'==$_dir_name{0})
    {
        $dirs = '/';
    } 

	foreach($dir_name as $dir)
	{
		$dir = trim($dir);
        if ('' != $dir)
        {
            $dirs .= $dir;

            if ('..' == $dir || '.' == $dir)
            {
                
                $dirs .= '/';

                continue;
            }
        }
        else
        {
            continue;
        }

        $dirs .= '/';
            
        if (!is_dir($dirs))
		{
			if(!mkdir($dirs, $mode)) 
			{
				return false;
			}
		}       
	}
	return true;
}


function jsg_sina_footer()
{
	$tipsType = $GLOBALS['xwb_tips_type'];
	$site_uid = XWB_S_UID;
	$sina_uid = XWB_plugin::getBindInfo("sina_uid");
	$siteVer = XWB_S_VERSION ;
	$siteName = str_replace("'","\'", $GLOBALS['jsg_sys_config']['site_name'] ) ;
	$pName = CURSCRIPT. '_'. CURMODULE;
	$regUrl = XWB_plugin::URL("xwbSiteInterface.reg");
	$setUrl = XWB_plugin::URL("xwbSiteInterface.bind");
	$bindUrl = XWB_plugin::URL("xwbSiteInterface.bind");
	$signerUrl = XWB_plugin::URL("xwbSiteInterface.signer");
	$authUrl = XWB_plugin::URL("xwbAuth.login");
	$getTipsUrl = XWB_plugin::URL("xwbSiteInterface.getTips");
	$attentionUrl = XWB_plugin::URL("xwbSiteInterface.attention");
	$wbxUrl = XWB_plugin::pCfg("wbx_url");
	$xwb_loadScript1 =  XWB_plugin::getPluginUrl('images/dlg.js');
	$xwb_loadScript2 =  XWB_plugin::getPluginUrl('images/xwb.js');
	$xwb_css_base = XWB_plugin::getPluginUrl('images/xwb_base.css');
	$xwb_css_append = XWB_plugin::getPluginUrl('images/xwb_'. XWB_S_VERSION. '.css');


$return = <<<EOF
<script language="javascript">
var _xwb_cfg_data ={
	tipsType:	'$tipsType',site_uid:	'$site_uid',sina_uid:	'$sina_uid',
	siteVer:	'$siteVer',siteName:	'$siteName',pName:'$pName',
	regUrl:		'$regUrl',
	setUrl:		'$setUrl',
	bindUrl:	'$bindUrl',
	signerUrl:	'$signerUrl',
	authUrl:	'$authUrl',
	getTipsUrl:	'$getTipsUrl',
	attentionUrl:	'$attentionUrl',
	wbxUrl:		'$wbxUrl'
};

function xwb_loadScript(file, charset){
	var script = document.createElement('SCRIPT');
	script.type = 'text/javascript'; script.charset = charset; script.src = file;
	document.getElementsByTagName('HEAD')[0].appendChild(script);
}
xwb_loadScript("$xwb_loadScript1", "UTF-8");
xwb_loadScript("$xwb_loadScript2", "UTF-8");
</script>
<link href="$xwb_css_base" rel="stylesheet" type="text/css" />
<link href="$xwb_css_append" rel="stylesheet" type="text/css" />

EOF;

	
	
	return $return;
}


$GLOBALS['jsg_sys_config'] = jsg_sys_config();

$GLOBALS['jsg_sys_config']['sina'] = jsg_sys_config('sina');

define('XWB_APP_KEY',			($GLOBALS['jsg_sys_config']['sina']['app_key'] ? $GLOBALS['jsg_sys_config']['sina']['app_key'] : '3015840342'));
define('XWB_APP_SECRET_KEY',	($GLOBALS['jsg_sys_config']['sina']['app_secret'] ? $GLOBALS['jsg_sys_config']['sina']['app_secret'] : '484175eda3cf0da583d7e7231c405988'));



define('XWB_S_CHARSET',		str_replace("-","",strtoupper($GLOBALS['jsg_sys_config']['charset'])));
define('XWB_S_TBPRE',		$GLOBALS['jsg_sys_config']['db_table_prefix']);
define('XWB_S_VERSION',		'2.5.0');
define('XWB_S_NAME',		'JishiGou');
define('XWB_S_TITLE',		XWB_plugin::convertEncoding($GLOBALS['jsg_sys_config']['site_name'], XWB_S_CHARSET, 'UTF-8'));
define('XWB_S_SITEURL',		$GLOBALS['jsg_sys_config']['site_url'] . "/");



if(!$GLOBALS[XWB_SITE_GLOBAL_V_NAME]['site_db'])
{
	include_once(XWB_P_ROOT . '/lib/xwbDB.class.php');
	$GLOBALS[XWB_SITE_GLOBAL_V_NAME]['site_db'] = new xwbDB();
	$GLOBALS[XWB_SITE_GLOBAL_V_NAME]['site_db']->connect($GLOBALS['jsg_sys_config']['db_host'],$GLOBALS['jsg_sys_config']['db_user'],$GLOBALS['jsg_sys_config']['db_pass'],$GLOBALS['jsg_sys_config']['db_name'],$GLOBALS['jsg_sys_config']['db_persist'],true,XWB_S_CHARSET);
}



if (!defined('MEMBER_ID')) 
{
	$jsg_authcode = $_COOKIE["{$GLOBALS['jsg_sys_config']['cookie_prefix']}auth"];
	list($jsg_password,$jsg_uid)=($jsg_authcode ? explode("\t",authcode($jsg_authcode,'DECODE')) : array('','',0));
	if ($jsg_uid && $jsg_password) 
	{
		$jsg_members = $GLOBALS[XWB_SITE_GLOBAL_V_NAME]['site_db']->fetch_first("select `uid`,`username`,`password`,`secques`,`role_type` from ".XWB_S_TBPRE."members where `uid`='$jsg_uid'");
		if ($jsg_members && $jsg_password==$jsg_members['password']) 
		{
			define('MEMBER_ID',(int) $jsg_members['uid']);
			define('MEMBER_NAME',$jsg_members['username']);
			define('MEMBER_ROLE_TYPE',$jsg_members['role_type']);
		}
	}
}


define('XWB_S_UID',			(int)(MEMBER_ID));

define('XWB_S_IS_ADMIN',	( (MEMBER_ROLE_TYPE == 'admin') ? true : false ));


?>