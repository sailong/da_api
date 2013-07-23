<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename map.inc.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:44 54913317 1638498721 693 $
 *******************************************************************/


if(!defined('IN_JISHIGOU'))
{
    exit('invalid request');
}
define('QUERY_SAFE_DACTION_3', true);
$sql = "select t.*,m.province,m.city from ".TABLE_PREFIX."topic AS t, ".TABLE_PREFIX."members AS m WHERE t.uid = m.uid AND t.uid IN(SELECT uid FROM ".TABLE_PREFIX."members WHERE province <>'' OR city <>'') ORDER BY t.tid DESC LIMIT 20";
$query = $this->DatabaseHandler->Query($sql);
$list = array();
$i = 0;
while ($row = $query->GetRow()) 
{
	$list[$i] = $this->TopicLogic->Make($row);
	$list[$i]['address'] = ($row['province'].$row['city'] == '其他其他') ? '浙江省杭州市' : $row['province'].$row['city'];
	$list[$i] = str_replace("'","\'",str_replace("\n","",$list[$i]));
	$i = $i + 1;
}
$topics = $list;
?>