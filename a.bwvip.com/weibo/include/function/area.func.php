<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename area.func.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:26 1106455899 1688152476 663 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}



function area_config_to_json() {
	include(ROOT_PATH . 'setting/area.php');
	
	$json = "";
	foreach($config['area'] as $key=>$val) {
		$j = '';
		foreach($val as $k=>$v) {
			$j .= "'{$k}':'{$v}',";
		}
		$j = trim($j,' ,');
		
		$json .= "'{$key}':{'key':'{$key}','values':{{$j}}},";
	}
	$json = trim($json,',');
	$json = "{'请选择…':{'key':'0','defaultvalue' : '0','values':{'请选择…':'0'}},{$json}}";

	return $json;
}



?>