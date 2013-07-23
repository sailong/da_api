<?php 
/**
 * 后台管理页面子菜单
 */

$menu_list = array(
	'qun' => array(
		1 => array(
			'name' => '基本设置',
			'code' => 'setting',
			'link' => 'admin.php?mod=qun&code=setting',
		),
		2 => array(
			'name' => '微群分类',
			'code' => 'category',
			'link' => 'admin.php?mod=qun&code=category',
		),
		/*
		3 => array(
			'name' => '微群等级',
			'code' => 'level',
			'link' => 'admin.php?mod=qun&code=level',
		),*/
		4 => array(
			'name' => '微群策略',
			'code' => 'ploy',
			'link' => 'admin.php?mod=qun&code=ploy',
		),
		5 => array(
			'name' => '微群管理',
			'code' => 'manage',
			'link' => 'admin.php?mod=qun&code=manage',
		),
	),
	
	'sms' => array(
		array(
			'name' => '基本设置',
			'code' => 'setting',
			'link' => 'admin.php?mod=sms&code=setting',
		),
		array(
			'name' => '手机用户列表',
			'code' => 'list',
			'link' => 'admin.php?mod=sms&code=list',
		),
		array(
			'name' => '发送记录列表',
			'code' => 'send_log',
			'link' => 'admin.php?mod=sms&code=send_log',
		),
		array(
			'name' => '接收记录列表',
			'code' => 'receive_log',
			'link' => 'admin.php?mod=sms&code=receive_log',
		),
	),
	
	'tag' => array(
		array(
			'name' => '话题管理',
			'code' => 'list',
			'link' => 'admin.php?mod=tag&code=list',
		),
		array(
			'name' => '话题推荐',
			'code' => 'recommend',
			'link' => 'admin.php?mod=tag&code=recommend',
		),
		array(
			'name' => '话题专题',
			'code' => 'extra',
			'link' => 'admin.php?mod=tag&code=extra',
		),
	),

	'plugin' => array(
		array(
			'name' => '插件列表',
			'code' => '',
			'link' => 'admin.php?mod=plugin',
		),
		array(
			'name' => '插件安装',
			'code' => 'add',
			'link' => 'admin.php?mod=plugin&code=add',
		),
		array(
			'name' => '插件设计',
			'code' => 'design',
			'link' => 'admin.php?mod=plugin&code=design',
			'type' => '1',
		),
	),

	'plugindesign' => array(
		array(
			'name' => '插件列表',
			'code' => '',
			'link' => 'admin.php?mod=plugin',
		),
		array(
			'name' => '设置',
			'code' => 'design',
			'link' => 'admin.php?mod=plugindesign&code=design',
		),
		array(
			'name' => '模块',
			'code' => 'modules',
			'link' => 'admin.php?mod=plugindesign&code=modules',
		),
		array(
			'name' => '变量',
			'code' => 'vars',
			'link' => 'admin.php?mod=plugindesign&code=vars',
		),
		array(
			'name' => '导出',
			'code' => 'export',
			'link' => 'admin.php?mod=plugindesign&code=export',
		),
	),
	
	'vote' => array(
		array(
			'name' => '基本设置',
			'code' => 'setting',
			'link' => 'admin.php?mod=vote&code=setting',
		),
		array(
			'name' => '投票管理',
			'code' => 'index',
			'link' => 'admin.php?mod=vote',
		),
	),
	
	/* //已暂时关闭
	'account' => array(
		array(
			'name' => '首页',
			'code' => 'index',
			'link' => 'admin.php?mod=account&code=index',
		),
		array(
			'name' => 'YY设置',
			'code' => 'yy',
			'link' => 'admin.php?mod=account&code=yy',
		),
		array(
			'name' => '人人设置',
			'code' => 'renren',
			'link' => 'admin.php?mod=account&code=renren',
		),
		array(
			'name' => '开心设置',
			'code' => 'kaixin',
			'link' => 'admin.php?mod=account&code=kaixin',
		),
	),
	//*/
);
 ?>