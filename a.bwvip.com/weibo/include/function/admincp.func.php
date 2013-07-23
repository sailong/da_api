<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename admincp.func.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:26 718212014 2049106429 424 $
 *******************************************************************/




if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}


function get_sub_menu($mod)
{
	global $menu_list;
	if (!@include_once(ROOT_PATH."./setting/admin_page_menu.php")) {
		return false;
	}
	return $menu_list[$mod];
}

?>
