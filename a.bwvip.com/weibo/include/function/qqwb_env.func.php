<?php
/**
 * 文件名：qqwb_env.func.php
 * 版本号：1.0
 * 最后修改时间：2010年12月6日 17:15:24
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: QQ微博接口函数
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}



function qqwb_env()
{    
	$msgs = array();
    if(!defined('ROOT_PATH'))
    {
        define('ROOT_PATH',substr(dirname(__FILE__),0,-17) . '/');
    }
	
	
	$files = array(ROOT_PATH . 'include/qqwb/qqoauth.php',ROOT_PATH . 'include/qqwb/oauth.php',ROOT_PATH . 'modules/qqwb.mod.php',);
	foreach ($files as $f)
	{
		if (!is_file($f)) 
		{
			$msgs[] = "文件<b>{$f}</b>不存在";
		}
	}
	
	
	$funcs = array('version_compare',array('fsockopen', 'pfsockopen'),'curl_init','preg_replace',array('iconv','mb_convert_encoding'),array("hash_hmac","mhash"));
	foreach ($funcs as $func)
	{
		if (!is_array($func)) 
		{
			if (!function_exists($func)) 
			{
				$msgs[] = "函数<b>{$func}</b>不可用";
			}
		}
		else 
		{
			$t = false;
			foreach ($func as $f)
			{
				if(function_exists($f))
				{
					$t = true;
					break;
				}
			}
			
			if (!$t) 
			{
				$msgs[] = "函数<b>".implode(" , ",$func)."</b>都不可用";
			}
		}
	}
	
	
	
	if (version_compare(PHP_VERSION,'4.3') < 0) 
	{
		$msgs[] = "PHP版本需要<b>4.3</b>及以上";
	}
	
	
	
	$files = array(ROOT_PATH . 'setting/',ROOT_PATH . 'setting/settings.php',ROOT_PATH . 'setting/qqwb.php',ROOT_PATH . 'cache/',);
	foreach ($files as $file)
	{
		$test_file_name = "write_test_".date("Ymd").".test";
		if (is_dir($file)) 
		{
			$test_file = "{$file}/{$test_file_name}";
			if (!(@file_put_contents($test_file,$test_file_name))) 
			{
				$msgs[] = "目录<b>{$file}</b>不可写";
			}
			else 
			{
				if(!(@unlink($test_file)))
				{
					$msgs[] = "目录<b>{$file}</b>没有删除文件的权限";
				}
			}
		}
		else 
		{
			if (!(file_exists($file))) 
			{
				@file_put_contents($file,$test_file_name);
				if(!(file_exists($file) && @unlink($file)))
				{
					$msgs[] = "文件<b>{$file}</b>不可写";
				}
			}
			else 
			{
				if(!($fp = fopen($file,'a+')))
				{
					$msgs[] = "文件<b>{$file}</b>不可写";
				}
				@fclose($fp);
			}
		}			
	}
	
	
	
    include(ROOT_PATH . 'setting/settings.php');
    if(!defined('TABLE_PREFIX'))
    {
        define('TABLE_PREFIX',$config['db_table_prefix']);
    }
	include_once(ROOT_PATH . 'include/xwb/lib/xwbDB.class.php');
	$db = new xwbDB();
	$db->connect(($config['db_host'] . ($config['db_port'] ? ":{$config['db_port']}" : "")),$config['db_user'],$config['db_pass'],$config['db_name'],$config['db_persist'],true,str_replace("-","",$config['charset']));
	if (!$db) 
	{
		$msgs[] = "数据库连接失败";
	}
	else 
	{
		$query = $db->query("select count(*) from ".TABLE_PREFIX."xwb_bind_info",'SKIP_ERROR');
		if (!$query && '1146'==$db->errno()) 
		{
			$query = $db->query("CREATE TABLE `".TABLE_PREFIX."qqwb_bind_info` (
  `uid` int(10) unsigned NOT NULL,
  `qqwb_username` char(20) NOT NULL default '',
  `token` char(32) NOT NULL,
  `tsecret` char(32) NOT NULL,
  `dateline` int(10) unsigned NOT NULL,
  `synctoqq` tinyint(1) NOT NULL,
  PRIMARY KEY  (`uid`),
  UNIQUE KEY `qqwb_username` (`qqwb_username`)
) ENGINE=MyISAM");
		}
		if (!$query) 
		{
			$msgs[] = "数据表<b>".TABLE_PREFIX."xwb_bind_info</b>创建失败";
		}
		
		$query = $db->query("select count(*) from ".TABLE_PREFIX."xwb_bind_topic",'SKIP_ERROR');
		if (!$query && '1146'==$db->errno()) 
		{
			$query = $db->query("CREATE TABLE `".TABLE_PREFIX."qqwb_bind_topic` (
  `tid` int(10) unsigned NOT NULL,
  `qqwb_id` bigint(20) unsigned NOT NULL,
  KEY `tid` (`tid`),
  KEY `qqwb_id` (`qqwb_id`)
) ENGINE=MyISAM");
		}
		if (!$query) 
		{
			$msgs[] = "数据表<b>".TABLE_PREFIX."xwb_bind_topic</b>创建失败";
		}			
	}		
		
	
    return $msgs;
}

?>