<?php

$extend_lang = array
(
	'menu_dazbm_manage' =>         '在线报名管理',
	'menu_dazappguess_manage'=>    '大正竞猜管理',
	'menu_dazappattr_manage' =>    '大正属性仓库',
	'menu_dazweibo_recommend'=>    '大正微博推荐',
	'menu_resultcard_manage'=>     '成绩卡-管理',
	'menu_dazapp_tickling'   =>    '大正Bug反馈',
	'menu_nduser_manage'   =>      '赛事分组管理',
);

/*载入后台 权限权限  给予授权 Angf DOit 2012/2/22*/
$GLOBALS['admincp_actions_normal'][] = 'dazbm';
$GLOBALS['admincp_actions_normal'][] = 'dazappguess';
$GLOBALS['admincp_actions_normal'][] = 'dazappattr';
$GLOBALS['admincp_actions_normal'][] = 'dazweibo';
$GLOBALS['admincp_actions_normal'][] = 'resultcard';
$GLOBALS['admincp_actions_normal'][] = 'nduser';
$GLOBALS['admincp_actions_normal'][] = 'seolink';


?>