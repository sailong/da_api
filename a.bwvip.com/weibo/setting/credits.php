<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename credits.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:45 1258453452 204205702 279 $
 *******************************************************************/

 
  
$config['credits']=array (
  'ext' => 
  array (
    'extcredits2' => 
    array (
      'enable' => 1,
      'ico' => '',
      'name' => '金币',
      'unit' => '',
      'default' => 0,
    ),
  ),
  'formula' => '$member[topic_count]+$member[extcredits2]',
);
?>