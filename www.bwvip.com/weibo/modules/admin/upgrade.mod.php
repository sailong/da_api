<?php

/**
 *[JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 系统升级,客户端。
 *
 * @author 狐狸<foxis@qq.com>
 * @package www.jishigou.net
 */
if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}

class ModuleObject extends MasterObject
{
	var $server="";
    var $upsDataDIR = '';
    
	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
        $this->__upsEnvInit();
		$this->Execute();
	}
    
    function __upsEnvInit()
    {
                include_once ROOT_PATH.'include/lib/io.han.php';
        include_once ROOT_PATH.'include/function/zip.moyo.php';
        $this->upsDataDIR = ROOT_PATH.'install/udata/';
        IoHandler::initPath($this->upsDataDIR);
    }
    
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			case 'check':
				$this->check();
				break;
			case 'download':
				$this->download();
				break;
			case 'install':
				$this->install();
				break;
            case 'signup':
                 $this->Signup();
                 break;
			case 'clear_cache':
				$this->clearCache();
			default:
				$this->Main();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}
    
	function Main()
	{
				$dir_list=array("api","backup","cache","errorlog","images","include","install","modules","setting","templates","./",);
		foreach ($dir_list as $dir)
		{
			$path=ROOT_PATH.$dir;
			if(is_writable($path)==false)
            {
                $this->Messager("{$path}目录不可写，请将其属性改成0777",null);
            }
		}
		
				if(!function_exists("gzopen"))
        {
            $this->Messager("您的服务器不支持gzopen函数，不能执行升级。",null);
        }	
		
		
		$this->Messager("正在检查是否有新版本，请稍候……<br /><br />1、升级前请做好文件和数据库的备份，<br />2、在升级过程中前台将临时无法访问。","admin.php?mod=upgrade&code=check",10);
	}
	function Signup()
    {
        $_GET['op'] == 'request' && $this->Signup_request();
        $this->checkResponse('acl.denied');
    }
    function Signup_request()
    {
        $account = $this->Post['account'];
        $password = $this->Post['password'];
        $result = upsCtrl()->Signup($account, $password);
        if ($result != 'ok')
        {
            $this->Messager($result, -1);
        }
        $aclData = upsCtrl()->Account();
        $uStop = $aclData['upgrade']['stop'];
        if ($uStop)
        {
            $this->Messager($uStop, null);
        }
        header('Location: admin.php?mod=upgrade');
    }
    private function checkResponse($response)
    {
        if (!is_string($response)) return $response;
        if (upsCtrl()->RPSFailed($response))
        {
            include $this->TemplateHandler->Template('admin/upgrade_acl_signup');
            exit;
        }
        return $response;
    }
	
	function check()
	{
		$this->_upgradeLock(false);
		$response = $this->checkResponse(request('upgrade', array(), $error));
		if ($response == SYS_VERSION)
        {
            $this->Messager('您使用的已是最新版本，无需升级', null);
        }
        elseif (is_string($response))
        {
            $this->Messager('获取版本信息时出错，请重试！('.$response.')', null);
        }
        else
        {
            $next_url = 'admin.php?mod=upgrade&code=download&version='.$response['version'].'&build='.$response['build'].'&file='.$response['file'].'&size='.$response['file_size'].'&hash='.$response['file_hash'].'&start=1';
            include $this->TemplateHandler->Template('admin/upgrade_change_log');
            exit;
        }
	}
	
	function download()
	{
        $file=$this->Post['file']?$this->Post['file']:$this->Get['file'];
		$size=$this->Post['size']?$this->Post['size']:$this->Get['size'];
		$hash=$this->Post['hash']?$this->Post['hash']:$this->Get['hash'];
		$version=$this->Post['version']?$this->Post['version']:$this->Get['version'];
		$build = $this->Post['build']?$this->Post['build']:$this->Get['build'];
        $quick = $this->Post['quick']?$this->Post['quick']:$this->Get['quick'];
		
		if (!$size || !$hash || !$version || !$build)$this->Messager("参数错误",null);

		$url="admin.php?mod=upgrade&code=download&version={$version}&build={$build}&file={$file}&size={$size}&hash={$hash}&quick={$quick}";
				if($this->Get['start'])
		{
			$this->Messager("正在启用下载进程...",$url,0);
		}
		
		$upgrade_data_dir = $this->upsDataDIR.'upgrade/';
        is_dir($upgrade_data_dir) || @mkdir($upgrade_data_dir);
        $tmp_file = $upgrade_data_dir.SYS_VERSION.'~'.$version.".zip";
        $tmp_exists=is_file($tmp_file);
        if($tmp_exists)$tmp_md5=md5_file($tmp_file);
        $offset=$tmp_exists?@filesize($tmp_file):0;
        
                if($offset>=$size && $tmp_md5!=$hash)
        {
            @unlink($tmp_file);
            $this->Messager(null,$url);
        }
        
        if($offset==$size && $tmp_md5==$hash)
        {
                        $this->_upgradeLock(true);
            $this->Messager("升级包已经成功下载,正在开始升级...","admin.php?mod=upgrade&code=install&step=check&version={$version}&quick={$quick}",0);
        }
        
                $length=rand(102400,102400*2);
        $request=array('version'=>$version,'build'=>$build,'file'=>$file,'hash'=>$hash,'offset'=>$offset,'length'=>$length);
        $data=$this->checkResponse(request('download', $request, $error));
        if($error) $this->Messager($data,null);
        
                $md5=$data['hash'];
        $data=$data['upgrade_data'];
        if ($md5!=md5($data)) {
            @unlink($tmp_file);
            $this->Messager("程序传输过程中数据出错，请重新升级。",null);
        }
        
        if(!$data && $tmp_exists==false)$this->Messager("请求失败，请稍候在试。",null);
        
                $fp=fopen($tmp_file,$tmp_exists?"ab":"wb");
        if($fp==false)$this->Messager($tmp_file."文件无法写入");
        $write_length=fwrite($fp,$data,$length);
        fclose($fp);
        $percent=(number_format($offset/$size,2)*100)."%";
        $this->Messager("正在下载升级包，已下载{$percent}",$url,0);

	}
	
	function install()
	{
		@set_time_limit(120);
        $version=$this->Post['version']?$this->Post['version']:$this->Get['version'];
        $step=$this->Get['step'];
        $status=(int)$this->Get['status'];        if(empty($version))$this->Messager("参数错误");
        
        $odver = $this->Get['odver'] ? $this->Get['odver'] : SYS_VERSION;
        
        $url="admin.php?mod=upgrade&code=install&version=$version&odver=$odver";
        
                $upgrade_data_dir = $this->upsDataDIR.'upgrade/';
        $upgrade_file = $upgrade_data_dir.$odver.'~'.$version.".zip";
        if (is_file($upgrade_file)==false)
        {
            $this->Messager("升级包已经不存在，请重新下载",null);
        }
        $upgrade_tmp_dir = $upgrade_data_dir.$odver.'~'.$version.'/';
        is_dir($upgrade_tmp_dir) || @mkdir($upgrade_tmp_dir);
        
                if($step=='check')
        {
            $quick = $this->Get['quick'];
            $check_url=$url."&step=check&quick={$quick}";
            if($status===0) $this->Messager("正在释放临时文件...",$check_url.'&status=1',0);
            $files = zip2web($upgrade_file, $upgrade_tmp_dir);
            $backup_url=$url."&step=backup";
            if ($quick == 'yes')
            {
                $this->Messager('正在开始升级...', $backup_url, 0);
            }
            include $this->TemplateHandler->Template('admin/upgrade_change_list');
            exit;
        }
        
                if ($step=='backup') 
        {
            $original_path=ROOT_PATH;            $backup_path=ROOT_PATH.'backup/'.SYS_VERSION.'-'.SYS_BUILD.'/';            if(!is_dir($backup_path)) {
                IoHandler::MakeDir($backup_path,0777);
            }
            clearstatcache();
            
            $files = IoHandler::ReadDir($upgrade_tmp_dir, 1);
            $unbackup_file_list=array();
            foreach ($files as $i => $upfile_path)
            {
                $baseFileName = substr($upfile_path, strlen($upgrade_tmp_dir));
                list($baseDIR) = explode('/', $baseFileName);
                if ($baseDIR == '__upgrade__') continue;
                $webfile_path = IoHandler::initPath(ROOT_PATH.$baseFileName);
                $bakfile_path = IoHandler::initPath($backup_path.$baseFileName);
                                if (is_file($webfile_path))
                {
                    IoHandler::CopyFile($webfile_path, dirname($bakfile_path)) || $unbackup_file_list[$webfile_path] = $bakfile_path;
                }
                                                                                $const_file = 'setting/constants.php';
                if (substr($baseFileName, -strlen($const_file)) != $const_file)
                {
                	                	
                    IoHandler::CopyFile($upfile_path, dirname($webfile_path)) || $unbackup_file_list[$upfile_path] = $webfile_path;
                }
            }

            if ($unbackup_file_list) 
            {
                $msg="<b>以下文件或目录无法备份或安装，程序无法继续执行</b>:<br><ul>";
                foreach ($unbackup_file_list as $backup_file=>$original_file)
                {
                    $msg.="<li>".$original_file;
                }
                $msg.="</ul>";
                $msg.="<br/><br/>请您检查相应文件权限后，<a href='{$url}&step=backup'>点击此处</a>重新升级";
                $this->Messager($msg,null);
            }
            $this->Messager("正在升级中，请勿关闭窗口...", $url."&odver=".SYS_VERSION);
        }

        $upgrade_script_dir = $upgrade_tmp_dir.'__upgrade__/';
                if(is_dir($upgrade_script_dir)) {
            $files = IoHandler::ReadDir($upgrade_script_dir);
            foreach ($files as $i => $path)
            {
                include $path;
            }
        }
                $const_file = 'setting/constants.php';
        $copy_from = $upgrade_tmp_dir.$const_file;
        $copy_to = ROOT_PATH.$const_file;
        IoHandler::CopyFile($copy_from, dirname($copy_to));
        
                $upgrade_script_file_name = 'upgrade.php';
        if(is_file(ROOT_PATH . $upgrade_script_file_name))
        {
        	$this->Messager('正在执行升级脚本，请稍候……', $this->Config['site_url'] . '/' . $upgrade_script_file_name);
        }

                $this->_upgradeLock(false);
        
                clearcache();
        
        $this->Messager("新版本安装成功,正在清空缓存...","admin.php?mod=upgrade&code=clear_cache");
	}
	function clearCache()
	{
		$this->_upgradeLock(false);
		
		clearcache();

		$msg="缓存已清空，升级完成。<br>";
		$this->Messager($msg,null);
	}
	
	function _upgradeLock($lock=true) 
	{
		if ($lock) 
		{
						touch(ROOT_PATH . 'cache/upgrade.lock');
			
			$config = array();
			include(ROOT_PATH . 'setting/settings.php');
            $config_default = $config;
            if($config_default['upgrade_lock_time'] < (time() - 600))
            {
                $config['upgrade_lock_time'] = time();                
            }			
			
			if($config_default != $config)
            {
                ConfigHandler::set($config);
            }
		}
		else 
		{
						@unlink(ROOT_PATH . 'cache/upgrade.lock');
			
			$config = array();
			include(ROOT_PATH . 'setting/settings.php');
            $config_default = $config;
            if($config_default['upgrade_lock_time'])
            {
                unset($config['upgrade_lock_time']);            
            }			
			
			if($config_default != $config)
            {
                ConfigHandler::set($config);
            }
            
            @unlink(ROOT_PATH . 'upgrade.php');
		}		
	}
	
	function _upgrade_file($from, $to)
	{
		$fn = basename($from);
		$tn = basename($to);
		if($fn != $tn)
		{
			return ;
		}
		
		$un_upgrade_files = array(
			"robots.txt"=>1,
		    "favicon.ico"=>1,
			"error_404.php"=>1,
		    "set.data.php"=>1,
			"watermark.png"=>1,
		    "logo.png"=>1,
		    "wap_logo.gif"=>1,
			"ad.php"=>1,
			"link.php"=>1,
			"settings.php"=>1,
		);
		if($un_upgrade_files[$fn])
		{
			return ;
		}
	}
}
?>