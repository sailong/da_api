<?php

/**
 * 缓存管理
 *
 * @author 狐狸<foxis@qq.com>
 * @package JishiGou
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
		
		$this->Execute();
	}
	function Execute()
	{
		ob_start();
		switch($this->Code)
		{
			
			default:
				$this->Main();
				break;
		}
		$body = ob_get_clean();
		
		$this->ShowBody($body);
		
	}
	function Main()
	{		
		$this->clearAll();
	}
	function clearAll()
	{
		Load::lib('io');
		$IoHandler = new IoHandler();
		
		$IoHandler->ClearDir(ROOT_PATH . 'cache/');
        $IoHandler->ClearDir(ROOT_PATH . 'wap/cache/');
        
		$this->_removeTopicImage();
		
		$this->Messager("缓存已清空，同时清理了用户上传但未使用的图片", null);
	}

	function _removeTopicImage()
	{
		Load::logic('image');		
		$ImageLogic = new ImageLogic();
		
				$ImageLogic->clear_invalid(120);
	}
}
?>