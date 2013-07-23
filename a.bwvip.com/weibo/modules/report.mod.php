<?php
/**
 * 文件名：report.mod.php
 * 版本号：1.0
 * 最后修改时间：2009年9月28日 14时10分42秒
 * 作者：狐狸<foxis@qq.com>
 * 功能描述: 举报模块
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
		switch($this->Code)
		{	
			case 'do':
				$this->DoReport();
				break;
					
			default:
				$this->Main();
				break;
		}
	}

	function Main()
	{
		$url = urlencode(getSafeCode(urldecode($this->Get['url'])));
		
		$report_config = ConfigHandler::get('report');
				
		
		include($this->TemplateHandler->Template('report.inc'));
	}
	
	function DoReport()
	{
		extract($this->Post);
		$url = getSafeCode(urldecode(urldecode(($url ? $url : $report_url))));		
			
		$data = array(
			'uid' => MEMBER_ID,
			'username' => MEMBER_NAME,
			'ip' => client_ip(),
			'reason' => (int) $report_reason,
			'content' => addslashes(strip_tags($report_content)),
			'url' => addslashes(strip_tags(urldecode($url))),
			'dateline' => time(),
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX . 'report');
		$result = $this->DatabaseHandler->Insert($data);
		
		
		$this->Messager("举报成功");
	}
	
}

?>
