<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename cache.php $
 *
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-09 10:58:45 1916089825 1674401771 854 $
 *******************************************************************/

 
  
$config['cache']=array (
  'topic_index' => 
  array (
    'new_user' => '600',
    'guanzhu' => '180',
    'recommend_topic' => '180',
    'hot_tag' => '3600',
  ),
  'topic_new' => 
  array (
    'topic' => '300',
    'day_tag' => '2592000',
    'tag' => '300',
  ),
  'topic_hot' => 
  array (
    'day1' => '300',
    'day7' => '7200',
    'day14' => '86400',
    'day30' => '86400',
  ),
  'reply_hot' => 
  array (
    'day1' => '300',
    'day7' => '7200',
    'day14' => '86400',
    'day30' => '86400',
    'reply' => '14400',
  ),
  'topic_top' => 
  array (
    'guanzhu' => '3600',
    'renqi' => '43200',
    'huoyue' => '43200',
    'yingxiang' => '43200',
  ),
  'tag_index' => 
  array (
    'guanzhu' => '3600',
    'hot' => '3600',
    'day7' => '3600',
    'day7_guanzhu' => '3600',
    'tag_tuijian' => '3600',
  ),
);
?>