<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_menu.php 21972 2011-04-19 02:51:52Z monkey $
 */

global $_G;
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

$isfounder = isset($isfounder) ? $isfounder : isfounder();

$topmenu = $menu = array();

$topmenu = array (
	'index' => '',
	'global' => '',
	'style' => '',
	'topic' => '',
	'user' => '',
	'portal' => '',
	//'forum' => '',
	//'group' => '',
	'extended' => '',
	//'plugin' => $isfounder ? 'plugins' : '',
	'tools' => '',
	'daz_bmsystem'=>'',  //大正后台 头部导航 angf doit 2012/2/22
);



/*大正后台  侧边栏导航  angf doit ....2012/2/22*/

$menu['daz_bmsystem'] = array(
   array('menu_dazbm_manage', 'dazbm_manage'),
   array('menu_dazappguess_manage', 'dazappguess_guess.activity.list'),
   array('menu_dazappattr_manage', 'dazappattr_manage'),
   array('menu_dazweibo_recommend','dazweibo_recommend'),
   array('menu_resultcard_manage','resultcard_manage'),
   array('menu_nduser_manage','nduser_manage'),  //angf do it  2012/4/23
   array('menu_jdt','jdt'),                      //add by xgw on 2012年4月5日
   array('menu_shoujibao','shoujibao'),          //add by xgw on 2012年4月16日
   array('menum_dazheng_tianyi','tianyi'),       //add by xgw on 2012年6月18日
   array("menum_dazheng_realbum","realbum"),     //add by xgw on 2012年4月18日
   array('menum_dazheng_baofen','baofen'),       //add by xgw on 2012年4月19日
   array('menu_dazapp_tickling','dazapp_tickling'),
   array('教练赛报名管理','join_list'),
   array('徽章审核','badge'),
   array('关键词管理','seolink_manage'),
   array('分站信息管理','scommend'),
   array('ABCD场管理','course'),
);


$menu['index'] = array(
	array('menu_home', 'index'),
	array('menu_custommenu_manage', 'misc_custommenu'),
);

$custommenu = get_custommenu();
$menu['index'] = array_merge($menu['index'], $custommenu);

$menu['global'] = array(
	array('menu_setting_basic', 'setting_basic'),
	array('menu_setting_access', 'setting_access'),
	array('menu_setting_functions', 'setting_functions'),
	array('menu_setting_optimize', 'setting_cachethread'),
	array('menu_setting_seo', 'setting_seo'),
	array('menu_setting_domain', 'domain'),
	array('menu_setting_home', 'setting_home'),
	array('menu_setting_user', 'setting_permissions'),
	array('menu_setting_credits', 'setting_credits'),
	array('menu_setting_sec', 'setting_sec'),
	array('menu_setting_datetime', 'setting_datetime'),
	array('menu_setting_attachments', 'setting_attach'),
	array('menu_setting_imgwater', 'setting_imgwater'),
	array('menu_posting_attachtypes', 'misc_attachtype'),
	array('menu_setting_search', 'setting_search'),
	array('menu_setting_district', 'district'),
	array('menu_setting_auto', 'auto'),
	array('menu_setting_ranklist', 'setting_ranklist'),
	array('menu_setting_mobile', 'setting_mobile'),
);

$menu['style'] = array(
	//array('menu_setting_customnav', 'nav_headernav'),
	//array('menu_setting_styles', 'setting_styles'),   //chang by xgw on 2012年3月26日
	//array('menu_styles', 'styles'),
	//$isfounder ? array('menu_styles_templates', 'templates') : null,
	array('menu_posting_smilies', 'smilies'),
	array('menu_click', 'click'),
	//array('menu_thread_stamp', 'misc_stamp'),
	array('menu_posting_editor', 'setting_editor'),
    //array('menu_misc_onlinelist', 'misc_onlinelist'),
);

$menu['topic'] = array(
	array('menu_moderate_posts', 'moderate'),
	array('menu_posting_censors', 'misc_censor'),
	array('menu_maint_report', 'report'),
	array('menu_setting_tag', 'tag'),
	/*array(cplang('nav_forum'), '', 1),       //chang by xgw on 2012年3月30日
		array('menu_maint_threads', 'threads'),
		array('menu_maint_prune', 'prune'),
		array('menu_maint_attaches', 'attach'),
	array(cplang('nav_forum'), '', 2),
	array(cplang('nav_group'), '', 1),
		array('menu_maint_threads_group', 'threads_group'),
		array('menu_maint_prune_group', 'prune_group'),
		array('menu_maint_attaches_group', 'attach_group'),
	array(cplang('nav_group'), '', 2),*/
	array(cplang('thread'), '', 1),
    		array('menu_moderate_recyclebin', 'recyclebin'),
		array('menu_moderate_recyclebinpost', 'recyclebinpost'),
		array('menu_threads_forumstick', 'threads_forumstick'),
		array('menu_post_position_index', 'threads_postposition'),
		array('menu_postcomment', 'postcomment'),
	array(cplang('thread'), '', 2),
	array(cplang('nav_home'), '', 1),
		array('menu_maint_doing', 'doing'),
		array('menu_maint_blog', 'blog'),
		array('menu_maint_feed', 'feed'),
		array('menu_maint_album', 'album'),
		array('menu_maint_pic', 'pic'),
		array('menu_maint_comment', 'comment'),
		array('menu_maint_share', 'share'),
	array(cplang('nav_home'), '', 2),
);

$menu['user'] = array(
	array('menu_members_edit', 'members_search'),
	array('menu_members_add', 'members_add'),
	array('menu_members_profile', 'members_profile'),
	array('menu_members_stat', 'members_stat'),
	array('menu_members_newsletter', 'members_newsletter'),
	array('menu_members_edit_ban_user', 'members_ban'),
	array('menu_members_ipban', 'members_ipban'),
	array('menu_members_credits', 'members_reward'),
	array('menu_moderate_modmembers', 'moderate_members'),
	array('menu_admingroups', 'admingroup'),
	array('menu_usergroups', 'usergroups'),
	array('menu_hotuser', 'specialuser_hotuser'),
	array('menu_defaultuser', 'specialuser_defaultuser'),
	array('members_verify_profile', 'verify_verify'),
	array('menu_members_verify_setting', 'verify'),
	array('comp', 'comp'),
	array('menu_apply', 'apply')
);

if(is_array($_G['setting']['verify'])) {
	foreach($_G['setting']['verify'] as $vid => $verify) {
		if($vid != 7 && $verify['available']) {
			$menu['user'][] = array($verify['title'], "verify_verify_$vid");
		}
	}
}

$menu['portal'] = array(
	array('menu_portalcategory', 'portalcategory'),
	array('menu_article', 'article'),
	array('menu_topic', 'topic'),
	array('menu_diytemplate', 'diytemplate'),
	array('menu_block', 'block'),
	array('menu_blockstyle', 'blockstyle'),
	array('menu_blockxml', 'blockxml'),
	array('menu_blogcategory', 'blogcategory'),
	array('menu_albumcategory', 'albumcategory'),
	array('menu_recommend', 'recommend'),
);
/*
$menu['forum'] = array(
	array('menu_forums', 'forums'),
	array('menu_forums_merge', 'forums_merge'),
	array('menu_forums_infotypes', 'threadtypes'),
);

$menu['group'] = array(
	array('menu_group_setting', 'group_setting'),
	array('menu_group_type', 'group_type'),
	array('menu_group_manage', 'group_manage'),
	array('menu_group_userperm', 'group_userperm'),
	array('menu_group_level', 'group_level'),
    array('menu_shoujibao','shoujibao'),
);
*/
$menu['extended'] = array(
	array('menu_misc_announce', 'announce'),
	//array('menu_adv_custom', 'adv'),
	array('menu_tasks', 'tasks'),
	array('menu_magics', 'magics'),
	array('menu_medals', 'medals'),
	array('menu_misc_help', 'faq'),
	array('menu_ec', 'setting_ec'),
	array('menu_misc_link', 'misc_link'),
	array('menu_misc_spacelink', 'misc_spacelink'),
	array('menu_misc_advert', 'misc_advert'),
	array('memu_focus_topic', 'misc_focus'),
	array('menu_misc_relatedlink', 'misc_relatedlink'),
	array('menu_card', 'card')
);

if(file_exists($menudir = DISCUZ_ROOT.'./source/admincp/menu')) {
	$adminextend = $adminextendnew = array();
	if(file_exists($adminextendfile = DISCUZ_ROOT.'./data/cache/cache_adminextend.php')) {
		@include $adminextendfile;
	}
	$menudirhandle = dir($menudir);
	while($entry = $menudirhandle->read()) {
		if(!in_array($entry, array('.', '..')) && preg_match("/^menu\_([\w\.]+)$/", $entry, $entryr) && substr($entry, -4) == '.php' && strlen($entry) < 30 && is_file($menudir.'/'.$entry)) {
			@include_once $menudir.'/'.$entry;
			$adminextendnew[] = $entryr[1];
		}
	}
	if($adminextend != $adminextendnew) {
		@unlink($adminextendfile);
		if($adminextendnew) {
			require_once libfile('function/cache');
			writetocache('adminextend', getcachevars(array('adminextend' => $adminextendnew)));
		}
		unset($_G['lang']['admincp']);
	}
}
/*
if($isfounder) {
	$menu['plugin'] = array(
		array('menu_addons', 'addons'),
		array('menu_plugins', 'plugins'),
	);
}*/
loadcache('adminmenu');
if(is_array($_G['cache']['adminmenu'])) {
	foreach($_G['cache']['adminmenu'] as $row) {
		$menu['plugin'][] = array($row['name'], $row['action']);
	}
}
/*
if(!$menu['plugin']) {
	unset($topmenu['plugin']);
}*/

$menu['tools'] = array(
	array('menu_tools_updatecaches', 'tools_updatecache'),
	array('menu_tools_updatecounters', 'counter'),
	array('menu_logs', 'logs'),
	array('menu_misc_cron', 'misc_cron'),
	$isfounder ? array('menu_tools_fileperms', 'tools_fileperms') : null,
	$isfounder ? array('menu_tools_filecheck', 'checktools_filecheck') : null,
);
if($isfounder) {
	$topmenu['founder'] = '';

	$menu['founder'] = array(
		array('menu_founder_perm', 'founder_perm'),
		array('menu_setting_mail', 'setting_mail'),
		array('menu_setting_uc', 'setting_uc'),
		array('menu_db', 'db_export'),
		array('menu_postsplit', 'postsplit_manage'),
		array('menu_threadsplit', 'threadsplit_manage'),
	);

	$menu['uc'] = array();
}

if(!isfounder() && !isset($GLOBALS['admincp']->perms['all'])) {
	$menunew = $menu;
	foreach($menu as $topkey => $datas) {
		if($topkey == 'index') {
			continue;
		}
		$itemexists = 0;
		foreach($datas as $key => $data) {
			if(array_key_exists($data[1], $GLOBALS['admincp']->perms)) {
				$itemexists = 1;
			} else {
				unset($menunew[$topkey][$key]);
			}
		}
		if(!$itemexists) {
			unset($topmenu[$topkey]);
			unset($menunew[$topkey]);
		}
	}
	$menu = $menunew;
}

?>