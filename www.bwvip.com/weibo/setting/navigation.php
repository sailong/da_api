<?php
/*******************************************************************
 * [JishiGou] (C)2005 - 2099 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename navigation.php $ 
 * 
 * @Author http://www.jishigou.net $
 *
 * @Date 2011-09-21 14:57:44 1978903570 1673606975 7779 $
 *******************************************************************/

 
  
$config['navigation']=array (
  'list' => 
  array (
    0 => 
    array (
      'order' => 100,
      'name' => '广场',
      'code' => 'new',
      'url' => 'index.php?mod=topic&code=new',
      'target' => '_parent',
      'type_list' => 
	  array (
        1 => 
        array (
          'order' => 99,
          'name' => '最新微博',
          'code' => 'topic',
          'url' => 'index.php?mod=topic&code=new',
          'target' => '_parent',
        ),
        2 => 
        array (
          'order' => 98,
          'name' => '最新评论',
          'code' => 'reply',
          'url' => 'index.php?mod=topic&code=newreply',
          'target' => '_parent',
        ),
        3 => 
        array (
          'order' => 97,
          'name' => '热门转发',
          'code' => 'hot_forward',
          'url' => 'index.php?mod=topic&code=hotforward',
          'target' => '_parent',
        ),
        4 => 
        array (
          'order' => 96,
          'name' => '热门评论',
          'code' => 'hot_reply',
          'url' => 'index.php?mod=topic&code=hotreply',
          'target' => '_parent',
        ),
        5 => 
        array (
          'order' => 95,
          'name' => '达人榜',
          'code' => 'top',
          'url' => 'index.php?mod=topic&code=top',
          'target' => '_parent',
        ),
        6 => 
        array (
          'order' => 94,
          'name' => '话题榜',
          'code' => 'tag',
          'url' => 'index.php?mod=tag',
          'target' => '_parent',
        ),
        7 => 
        array (
          'order' => 93,
          'name' => '媒体汇',
          'code' => 'media',
          'url' => 'index.php?mod=other&code=media',
          'target' => '_parent',
        ),
      ),
    ),
    1 => 
    array (
      'order' => 99,
      'name' => '微群',
      'code' => 'qun',
      'url' => 'index.php?mod=qun',
      'target' => '_parent',
      'type_list' => 
      array (
        0 => 
        array (
          'code' => 'myqun',
          'name' => '我的微群',
          'order' => 90,
          'url' => 'index.php?mod=qun&code=profile',
          'target' => '_parent',
          'type_list' => NULL,
        ),
        1 => 
        array (
          'order' => 60,
          'name' => '我的群推荐',
          'code' => 'qunrecd',
          'url' => 'index.php?mod=topic&code=qun&view=recd',
          'target' => '_parent',
        ),
        2 => 
        array (
          'order' => 80,
          'name' => '我的群内容',
          'code' => 'quntopic',
          'url' => 'index.php?mod=topic&code=qun',
          'target' => '_parent',
        ),
        3 => 
        array (
          'order' => 100,
          'name' => '发现新群',
          'code' => 'findqun',
          'url' => 'index.php?mod=qun',
          'target' => '_parent',
        ),
        4 => 
        array (
          'order' => 70,
          'name' => '我的群评论',
          'code' => 'qunreply',
          'url' => 'index.php?mod=topic&code=tag&view=new_reply',
          'target' => '_parent',
        ),
      ),
    ),
    2 => 
    array (
      'order' => 90,
      'name' => '应用',
      'code' => 'app',
      'url' => '#',
      'target' => '_parent',
      'type_list' => 
      array (
        0 => 
        array (
          'order' => 100,
          'name' => '投票',
          'code' => 'vote',
          'url' => 'index.php?mod=vote',
          'target' => '_parent',
        ),
        1 => 
        array (
          'order' => 99,
          'name' => '上墙',
          'code' => 'wall',
          'url' => 'index.php?mod=wall&code=control',
          'target' => '_parent',
        ),
        2 => 
        array (
          'order' => 98,
          'name' => '活动',
          'code' => 'event',
          'url' => 'index.php?mod=event',
          'target' => '_parent',
        ),
        3 => 
        array (
          'order' => 0,
          'name' => '勋章',
          'code' => 'medal',
          'url' => 'index.php?mod=other&code=medal',
          'target' => '_parent',
        ),
        /* //已暂时关闭
        4 => 
        array (
          'order' => 0,
          'name' => '分类',
          'code' => 'fenlei',
          'url' => 'index.php?mod=fenlei',
          'target' => '_parent',
        ),
        array (
          'order' => 0,
          'name' => '帐号',
          'code' => 'account',
          'url' => 'index.php?mod=account',
          'target' => '_parent',
        ),
        //*/
      ),
    ),
    3 => 
    array (
      'order' => 9,
      'name' => '工具',
      'code' => 'tools',
      'url' => 'index.php?mod=tools',
      'target' => '_parent',
      'type_list' => 
      array (
        0 => 
        array (
          'order' => 0,
          'name' => '签名档',
          'code' => 'qmd',
          'url' => 'index.php?mod=tools&code=qmd',
          'target' => '_parent',
        ),
        1 => 
        array (
          'order' => 0,
          'name' => '分享到微博',
          'code' => 'share',
          'url' => 'index.php?mod=tools&code=share',
          'target' => '_parent',
        ),
        2 => 
        array (
          'order' => 0,
          'name' => '微博秀',
          'code' => 'show',
          'url' => 'index.php?mod=show&code=show',
          'target' => '_parent',
        ),
        3 => 
        array (
          'order' => 0,
          'name' => '新浪微博',
          'code' => 'sina',
          'url' => 'index.php?mod=tools&code=sina',
          'target' => '_parent',
        ),
        4 => 
        array (
          'order' => 0,
          'name' => '腾讯微博',
          'code' => 'qqwb',
          'url' => 'index.php?mod=tools&code=qqwb',
          'target' => '_parent',
        ),
        5 => 
        array (
          'order' => 0,
          'name' => 'QQ机器人',
          'code' => 'robot',
          'url' => 'index.php?mod=tools&code=imjiqiren',
          'target' => '_parent',
        ),
      ),
    ),
    4 => 
    array (
      'order' => 8,
      'name' => '手机',
      'code' => 'wap',
      'url' => 'index.php?mod=other&code=wap',
      'target' => '_parent',
      'type_list' => 
      array (
        0 => 
        array (
          'order' => 0,
          'name' => '手机WAP',
          'code' => 'wap',
          'url' => 'index.php?mod=other&code=wap',
          'target' => '_parent',
        ),
        1 => 
        array (
          'order' => 0,
          'name' => '短信微博',
          'code' => 'sms',
          'url' => 'index.php?mod=tools&code=sms',
          'target' => '_parent',
        ),
      ),
    ),
    5 => 
    array (
      'order' => 7,
      'name' => '找人',
      'code' => 'search',
      'url' => 'index.php?mod=profile&code=search',
      'target' => '_parent',
      'type_list' => 
      array (
        0 => 
        array (
          'order' => 0,
          'name' => '同城用户',
          'code' => 'samecity',
          'url' => 'index.php?mod=profile&code=search',
          'target' => '_parent',
        ),
        1 => 
        array (
          'order' => 0,
          'name' => '同兴趣',
          'code' => 'maybe_friend',
          'url' => 'index.php?mod=profile&code=maybe_friend',
          'target' => '_parent',
        ),
        2 => 
        array (
          'order' => 0,
          'name' => '同类人',
          'code' => 'usertag',
          'url' => 'index.php?mod=profile&code=usertag',
          'target' => '_parent',
        ),
        3 => 
        array (
          'order' => 0,
          'name' => '邀请好友',
          'code' => 'invite',
          'url' => 'index.php?mod=profile&code=invite',
          'target' => '_parent',
        ),
      ),
    ),
  ),
  'pluginmenu' => 
  array (
  ),
);
?>