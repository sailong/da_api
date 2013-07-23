<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename uninstall.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:44 534545176 356402462 144 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}
$sql = <<<EOF
DROP TABLE IF EXISTS {table_pre}plugin_company;
EOF;
?>