<?php /* 2013-11-11 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <?php $__my=$this->MemberHandler->MemberFields; ?> <base href="<?php echo $this->Config['site_url']; ?>/" /> <?php $conf_charset=$this->Config['charset']; ?> <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $conf_charset; ?>" /> <title> <?php echo htmlspecialchars($this->Title); ?>
- <?php echo $this->Config['site_name']; ?>(<?php echo $this->Config['site_domain']; ?>)<?php echo $this->Config['page_title']; ?></title> <meta name="Keywords" content="
<?php echo htmlspecialchars($this->MetaKeywords); ?>
,<?php echo $this->Config['site_name']; ?><?php echo $this->Config['meta_keywords']; ?>" /> <meta name="Description" content="
<?php echo htmlspecialchars($this->MetaDescription); ?>
,<?php echo $this->Config['site_notice']; ?><?php echo $this->Config['meta_description']; ?>" /> <link rel="shortcut icon" href="favicon.ico" > <link href="templates/default/styles/main.css?build+20120829" rel="stylesheet" type="text/css" /> <?php if($this->Config['qun_theme_id']) { ?> <link href="templates/default/qunstyles/<?php echo $this->Config['qun_theme_id']; ?>.css" rel="stylesheet" type="text/css" /><?php } elseif($this->Config['theme_id']) { ?><link href="theme/<?php echo $this->Config['theme_id']; ?>/theme.css?v=<?php echo SYS_BUILD; ?>" rel="stylesheet" type="text/css" /> <?php } ?> <style type="text/css"> <?php if($this->Config['theme_text_color']) { ?>
body{ color:<?php echo $this->Config['theme_text_color']; ?>; }
<?php } ?> <?php if($this->Config['theme_bg_color']) { ?>
body{ background-color:<?php echo $this->Config['theme_bg_color']; ?>; }
<?php } ?> <?php if($this->Config['theme_bg_image']) { ?>
body{ background-image:url(<?php echo $this->Config['theme_bg_image']; ?>); }
<?php } ?> <?php if($this->Config['theme_bg_position']) { ?>
body{ background-position:<?php echo $this->Config['theme_bg_position']; ?>; }
<?php } ?> <?php if($this->Config['theme_bg_repeat']) { ?>
body{ background-repeat:<?php echo $this->Config['theme_bg_repeat']; ?>; }
<?php } ?> <?php if($this->Config['theme_bg_fixed']) { ?>
body{ background-attachment:<?php echo $this->Config['theme_bg_fixed']; ?>; }
<?php } ?> <?php if($this->Config['theme_text_color']) { ?>
body{ color:<?php echo $this->Config['theme_text_color']; ?>; }
<?php } ?> <?php if($this->Config['theme_link_color']) { ?>
a:link{ color:<?php echo $this->Config['theme_link_color']; ?>; }
<?php } ?>
a.artZoom{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLink { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
a.artZoom2{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
a.artZoom3{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLink3 { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
a.artZoomAll{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLinkAll { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
</style> <script type="text/javascript">
var thisSiteURL = '<?php echo $this->Config['site_url']; ?>/';
var thisTopicLength = '<?php echo $this->Config['topic_input_length']; ?>';
var thisMod = '<?php echo $this->Module; ?>';
var thisCode = '<?php echo $this->Code; ?>';
var thisFace = '<?php echo $__my['face_small']; ?>';
<?php $qun_setting = ConfigHandler::get('qun_setting'); ?> <?php if($qun_setting['qun_open']) { ?>
var isQunClosed = false;
<?php } else { ?>var isQunClosed = true;
<?php } ?>
function faceError(imgObj)
{
var errorSrc = '<?php echo $this->Config['site_url']; ?>/images/noavatar.gif';
imgObj.src = errorSrc;
}
</script> <script type="text/javascript">var __ALERT__='<?php echo $this->Config['verify_alert']; ?>';</script> <script type="text/javascript" src="templates/default/js/min.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/common.js?build+20120829"></script> <?php if(in_array($this->Code, array("follow","fans"))) { ?> <script type="text/javascript" src="templates/default/js/relation.js?build+20120829"></script> <?php } ?> <?php if($this->Get['mod']=="vote") { ?> <script type="text/javascript" src="templates/default/js/vote.js?build+20120829"></script> <?php } ?> <?php if($this->Get['mod']=="qun") { ?> <script type="text/javascript" src="templates/default/js/qun.js?build+20120829"></script> <?php } ?> </head> <?php echo $additional_str; ?> <body> <?php if(MEMBER_ID) { ?> <?php if(MEMBER_STYLE_THREE_TOL == 1) { ?> <?php $t_col_header ='t_col_header';  $t_col_logo ='t_col_logo'; $t_col_main='t_col_main'; $t_col_main_side='t_col_main_side'; $t_col_foot='t_col_foot'; $t_col_backTop='t_col_backTop'; $t_col_main_rn='t_col_main_rn'; $t_col_main_lb='t_col_main_lb'; $t_col_tagBox='t_col_tagBox';$bL_info_three='bL_info_three';  ?> <?php } ?> <?php if($member['open_extra'] and MEMBER_ID != $member['uid']) { ?> <?php $t_col_header ='t_col_header';  $t_col_logo ='t_col_logo'; $t_col_main='t_col_main'; $t_col_main_side='t_col_main_side'; $t_col_foot='t_col_foot'; $t_col_backTop='t_col_backTop'; $t_col_main_rn='t_col_main_rn'; $t_col_main_lb='t_col_main_lb'; $t_col_tagBox='t_col_tagBox';$bL_info_three='bL_info_three';  ?> <?php } ?> <?php } ?> <?php if($this->Config['company_enable']) { ?> <?php $d_c_name = $this->Config['default_company'] ? $this->Config['default_company'] : '单位'; $d_d_name = $this->Config['default_department'] ? $this->Config['default_department'] : '部门';  ?> <?php } ?> <div class="header"> <div class="headerNav <?php echo $t_col_header; ?>"> <ul class="hleft"> <?php if(defined('NEDU_MOYO')) { ?> <li class="logo2_nedu"><a href="index.php" title="<?php echo $this->Config['site_name']; ?>"></a></li> <?php } else { ?><li class="logo2"><a href="index.php" title="<?php echo $this->Config['site_name']; ?>"></a></li> <?php } ?> <?php $navigation_config=ConfigHandler::get('navigation');global $rewriteHandler; ?> <?php if(is_array($navigation_config['list'])) { foreach($navigation_config['list'] as $_v) { ?> <?php if($_v['avaliable'] == 1) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".t_c<?php echo $_v['code']; ?>").mouseover(function(){$(".t_c<?php echo $_v['code']; ?>_box").show();$(".t_c<?php echo $_v['code']; ?>").addClass("on");});
$(".t_c<?php echo $_v['code']; ?>").mouseout(function(){$(".t_c<?php echo $_v['code']; ?>_box").hide();$(".t_c<?php echo $_v['code']; ?>").removeClass("on");});
$(".t_c2").mouseover(function(){$(".t_c2_box").show();$(".t_c2").addClass("on");});
$(".t_c2").mouseout(function(){$(".t_c2_box").hide();$(".t_c2").removeClass("on");});
$(".t_c4").mouseover(function(){$(".t_c4_box").show();$(".t_c4").addClass("on");});
$(".t_c4").mouseout(function(){$(".t_c4_box").hide();$(".t_c4").removeClass("on");});
$(".t_c5").mouseover(function(){$(".t_c5_box").show();$(".t_c5").addClass("on");});
$(".t_c5").mouseout(function(){$(".t_c5_box").hide();$(".t_c5").removeClass("on");});
$(".t_c6").mouseover(function(){$(".t_c6_box").show();$(".t_c6").addClass("on");});
$(".t_c6").mouseout(function(){$(".t_c6_box").hide();$(".t_c6").removeClass("on");});
});
</script> <?php if($rewriteHandler)$_v['url']=$rewriteHandler->formatURL($_v['url']); ?> <li class="t_c<?php echo $_v['code']; ?>"><a href="<?php echo $_v['url']; ?>" target="<?php echo $_v['target']; ?>" title="<?php echo $_v['name']; ?>"><?php echo $_v['name']; ?></a> <?php if(!empty($_v['type_list'])  && $_v['avaliable'] == 1) { ?> <ul class="t_c1_box t_c<?php echo $_v['code']; ?>_box" style="display:none;"> <?php if(is_array($_v['type_list'])) { foreach($_v['type_list'] as $val) { ?> <?php if($val['name']  && $val['avaliable'] == 1) { ?> <?php if($rewriteHandler)$val['url']=$rewriteHandler->formatURL($val['url']); ?> <li><a href="<?php echo $val['url']; ?>" target="<?php echo $val['target']; ?>"><?php echo $val['name']; ?></a></li> <?php } ?> <?php } } ?> <?php if(!empty($navigation_config['pluginmenu']) && $_v['code'] == 'app') { ?> <?php if(is_array($navigation_config['pluginmenu'])) { foreach($navigation_config['pluginmenu'] as $pmenus) { ?> <?php if(is_array($pmenus)) { foreach($pmenus as $pmenu) { ?> <?php if($pmenu['type'] == 1) { ?> <?php if($rewriteHandler)$pmenu['url']=$rewriteHandler->formatURL($pmenu['url']); ?> <li><a href="<?php echo $pmenu['url']; ?>" target="<?php echo $pmenu['target']; ?>"><?php echo $pmenu['name']; ?></a></li> <?php } ?> <?php } } ?> <?php } } ?> <?php } ?> </ul> <?php } ?> </li> <?php } ?> <?php } } ?> <li class="sweibo"> <div class="searchTool"> <form method="get" action="#" name="headSearchForm" id="headSearchForm" onsubmit="return headDoSearch();"> <input id="headSearchType" name="searchType" type="hidden" value="topicSearch"> <div class="selSearch"> <div class="nowSearch" id="headSlected" onclick="if(document.getElementById('headSel').style.display=='none'){document.getElementById('headSel').style.display='block';}else {document.getElementById('headSel').style.display='none';};return false;" onmouseout="drop_mouseout('head');">微博</div> <div class="btnSel"><a href="#" onclick="if(document.getElementById('headSel').style.display=='none'){document.getElementById('headSel').style.display='block';}else {document.getElementById('headSel').style.display='none';};return false;" onmouseout="drop_mouseout('head');"></a></div> <div class="clear"></div> <ul class="selOption" id="headSel" style="display:none;"> <li><a href="#" onclick="return search_show('head','userSearch',this)" onmouseover="drop_mouseover('head');" onmouseout="drop_mouseout('head');">用户</a></li> <li><a href="#" onclick="return search_show('head','tagSearch',this)" onmouseover="drop_mouseover('head');" onmouseout="drop_mouseout('head');">话题</a></li> <li><a href="#" onclick="return search_show('head','topicSearch',this)" onmouseover="drop_mouseover('head');" onmouseout="drop_mouseout('head');">微博</a></li> <?php $vote_setting = ConfigHandler::get('vote'); ?> <?php if($vote_setting['vote_open']) { ?> <li><a href="#" onclick="return search_show('head','voteSearch',this)" onmouseover="drop_mouseover('head');" onmouseout="drop_mouseout('head');">投票</a></li> <?php } ?> <?php $qun_setting = ConfigHandler::get('qun_setting'); ?> <?php if($qun_setting['qun_open']) { ?> <li><a href="#" onclick="return search_show('head','qunSearch',this)" onmouseover="drop_mouseover('head');" onmouseout="drop_mouseout('head');">微群</a></li> <?php } ?> </ul> </div> <input class="txtSearch" id="headq" name="headSearchValue" type="text" value="请输入关键字" onfocus="this.value=''" onblur="if(this.value==''){this.value='请输入关键字';}"/> <div class="btnSearch"> <a href="#" onclick="javascript:return headDoSearch();"><span class="lbl"></span></a></div> </form> </div> </li> </ul> <ul class="hright"> <li class="pweibo" style="cursor:pointer;" onclick="showMainPublishBox();" title="发微博">发博
<input type="hidden" name="check_PublishBox_uid" id="check_PublishBox_uid"  value="<?php echo MEMBER_ID; ?>"/> <input type="hidden" id="verify" name="verify" value="<?php echo $this->Config['verify']; ?>"> </li> <?php if(MEMBER_ID > 0) { ?> <li class="t_c4"><a href="index.php" title="<?php echo $this->Config['site_name']; ?>">我的首页</a> <ul class="t_c4_box"> <?php if($this->Config['qun_setting']['qun_open']) { ?> <li><a href="index.php?mod=topic&code=qun">我的微群
<?php if($__my['qun_new']>0) { ?> <span>+<?php echo $__my['qun_new']; ?></span> <?php } ?> </a></li> <?php } ?> <li><a href="index.php?mod=topic&code=tag">关注话题
<?php if($__my['topic_new']>0) { ?> <span>+<?php echo $__my['topic_new']; ?></span> <?php } ?> </a></li> <?php if($this->Config['dzbbs_enable'] || ($this->Config['phpwind_enable'] && $this->Config['pwbbs_enable'])) { ?> <li><a href="index.php?mod=topic&code=bbs">我的论坛</a></li> <?php } ?> <?php if(($this->Config['dedecms_enable'] == 1)) { ?> <li><a href="index.php?mod=topic&code=cms">我的资讯</a></li> <?php } ?> <li><a href="index.php?mod=topic&code=recd">官方推荐</a></li> <li><a href="index.php?mod=topic&code=myfavorite">我的收藏</a></li> </ul> </li> <li class="t_c2"><a title="<?php echo MEMBER_NICKNAME; ?>" href="index.php?mod=<?php echo MEMBER_NAME; ?>"><?php echo MEMBER_NICKNAME; ?></a> <ul class="t_c2_box"> <li><a href="index.php?mod=<?php echo MEMBER_NAME; ?>&type=hot_reply">被热评的</a></li> <li><a href="index.php?mod=<?php echo MEMBER_NAME; ?>&type=hot_forward">被热转的</a></li> <li><a href="index.php?mod=topic&code=myfavorite">我收藏的</a></li> <li><a href="index.php?mod=<?php echo MEMBER_NAME; ?>&type=my_verify">待审核的</a></li> </ul> </li> <li class="t_c6">消息
<ul class="t_c6_box"> <li><a href="index.php?mod=topic&code=mycomment">评论我的
<?php if($__my['comment_new']>0) { ?> <span>+<?php echo $__my['comment_new']; ?></span> <?php } ?> </a></li> <li><a href="index.php?mod=topic&code=myat">@我的
<?php if($__my['at_new']>0) { ?> <span>+<?php echo $__my['at_new']; ?></span> <?php } ?> </a></li> <li><a href="index.php?mod=pm&code=list">私信我的
<?php if($__my['newpm']>0) { ?> <span>+<?php echo $__my['newpm']; ?></span> <?php } ?> </a></li> <li><a href="index.php?mod=<?php echo $__my['username']; ?>&code=fans">关注我的
<?php if($__my['fans_new']>0) { ?> <span>+<?php echo $__my['fans_new']; ?></span> <?php } ?> </a></li> <li><a href="index.php?mod=topic&code=favoritemy">收藏我的
<?php if($__my['favoritemy_new']>0) { ?> <span>+<?php echo $__my['favoritemy_new']; ?></span> <?php } ?> </a></li> </ul> </li> <li class="t_c5">帐号
<ul class="t_c5_box"> <li><a href="index.php?mod=settings">资料设置</a></li> <li><a href="index.php?mod=settings&code=face">修改头像</a></li> <li><a href="index.php?mod=settings&code=secret">修改密码</a></li> <li><a href="index.php?mod=account">帐户绑定</a></li> <li><a href="index.php?mod=other&code=wap">手机</a></li> <li><a href="index.php?mod=skin">换肤</a></li> <li><a href="index.php?mod=profile&code=invite">邀请关注</a></li> <?php if($params['code'] == 'myhome') { ?> <li> <span id="web_list_type_<?php echo MEMBER_ID; ?>"> <input type="hidden" id="web_style" name="web_style" value="<?php echo MEMBER_STYLE_THREE_TOL; ?>"/> <?php if (MEMBER_STYLE_THREE_TOL == 1) $ajax_list = 'right'; else $ajax_list = 'left'; ?> <a href="javascript:void(0);" title="推荐三栏，导航更清晰" onclick="web_list_type(<?php echo MEMBER_ID; ?>,'web_style','<?php echo $params['code']; ?>','<?php echo $ajax_list; ?>','<?php echo $member['uid']; ?>'); return false;"> <?php if(MEMBER_STYLE_THREE_TOL == 1) { ?>
换为两栏
<?php } else { ?>换为三栏
<?php } ?> </a> <input type="hidden" name='hid_type' id='hid_type' value='<?php echo $type; ?>'> </span> </li> <?php } ?> <?php if('admin'==MEMBER_ROLE_TYPE) { ?> <li><a href="admin.php" target=_blank>后台管理</a></li> <?php } ?> <li><a href="<?php echo $this->Config['site_url']; ?>/index.php?mod=login&code=logout" rel="nofollow">退出</a> </li> </ul> </li> <?php } else { ?> <li><a href="javascript:viod(0)" rel="nofollow" title="登录即可分享新鲜事" onclick="ShowLoginDialog(); return false;">快捷登录</a></li> <?php } ?> </ul> </div> </div> <div class="logow <?php echo $t_col_logo; ?>"> <?php if(MEMBER_ID>0) { ?> <?php if($__my['comment_new']>0 || $__my['fans_new']>0 || $__my['at_new']>0 || $__my['newpm']>0 || $__my['favoritemy_new']>0 || $__my['vote_new']>0 || $__my['qun_new']>0 || $__my['event_new']>0 || $__my['topic_new']>0 || $__my['event_post_new']>0 || $__my['fenlei_post_new']>0) { ?> <?php $__tagBoxStyle='display:block;visibility:visible;'; ?> <?php } else { ?><?php $__tagBoxStyle='display:none;visibility:hidden;'; ?> <?php } ?> <?php if(defined('NEDU_MOYO')) { ?> <?php if(nlogic('notify')->ups_haved()) { ?> <?php $__tagBoxStyle='display:block;visibility:visible;'; ?> <?php } ?> <?php } ?> <script type="text/javascript">
function tagBox_close()
{
var obj = document.getElementById("tagBox_<?php echo MEMBER_ID; ?>");
obj.style.visibility = "hidden";
}
</script> <div class="tagBox <?php echo $t_col_tagBox; ?>" id="tagBox_<?php echo MEMBER_ID; ?>" style="<?php echo $__tagBoxStyle; ?>"> <div id="tagBoxContent_<?php echo MEMBER_ID; ?>"> <ul> <?php if($__my['newpm']>0) { ?> <li><a href="index.php?mod=pm&code=list"><?php echo $__my['newpm']; ?>条新私信，查看</a></li> <?php } ?> <?php if($__my['comment_new']>0) { ?> <li><a href="index.php?mod=topic&code=mycomment"><?php echo $__my['comment_new']; ?>条新评论，查看</a></li> <?php } ?> <?php if($__my['fans_new']>0) { ?> <li><a href="index.php?mod=<?php echo $__my['username']; ?>&code=fans"><?php echo $__my['fans_new']; ?>人关注了我，查看</a></li> <?php } ?> <?php if($__my['at_new']>0) { ?> <li><a href="index.php?mod=topic&code=myat"><?php echo $__my['at_new']; ?>人@提到我，查看</a></li> <?php } ?> <?php if($__my['favoritemy_new']>0) { ?> <li><a href="index.php?mod=topic&code=favoritemy"><?php echo $__my['favoritemy_new']; ?>人收藏了我的，查看</a></li> <?php } ?> <?php if($__my['vote_new']>0) { ?> <li><a href="index.php?mod=<?php echo $__my['uid']; ?>&type=vote&filter=new_update">投票新增<?php echo $__my['vote_new']; ?>人参与，查看</a></li> <?php } ?> <?php if($__my['qun_new']>0) { ?> <li><a href="index.php?mod=topic&code=qun">微群新增<?php echo $__my['qun_new']; ?>条内容，查看</a></li> <?php } ?> <?php if($__my['event_new']>0) { ?> <li><a href="index.php?mod=<?php echo $__my['uid']; ?>&type=event&filter=new_update">活动新增<?php echo $__my['event_new']; ?>人报名，查看</a></li> <?php } ?> <?php if($__my['topic_new']>0) { ?> <li><a href="index.php?mod=topic&code=tag">话题新增<?php echo $__my['topic_new']; ?>条微博，查看</a></li> <?php } ?> <?php if($__my['event_post_new']>0) { ?> <li><a href="index.php?mod=topic&code=other&view=event">新增<?php echo $__my['event_post_new']; ?>个关注的活动，查看</a></li> <?php } ?> <?php if($__my['fenlei_post_new']>0) { ?> <li><a href="index.php?mod=topic&code=other&view=fenlei">新增<?php echo $__my['fenlei_post_new']; ?>个关注的分类信息，查看</a></li> <?php } ?> <?php if(defined('NEDU_MOYO')) { ?> <?php echo nlogic('notify')->ups_tips_html();; ?> <?php } ?> </ul> </div> <div class="tagBox_del"><a href="javascript:tagBox_close();" title="关闭" javascript:void(0)><img src="templates/default/images/imgdel.gif" /></a></div> </div> <?php } ?> </div> <div class="changeTheme"><a href="index.php?mod=skin" title="更换皮肤" javascript:void(0)></a></div> <div class="main t_col_main"> <div class="t_col_main_si t_col_main_side"> <div class="t_col_main_fl"> <div id="topic_index_left_ajax_list"> <?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> <div class="t_col_main_ln <?php echo $t_col_main_lb; ?>"> <script type="text/javascript">
$(document).ready(function(){
$(".member_exp").mouseover(function(){$(".member_exp_c").show();});
$(".member_exp").mouseout(function(){$(".member_exp_c").hide();});
$("#m_avatar2").mouseover(function(){$(".avatar_tips").show();});
$("#m_avatar2").mouseout(function(){$(".avatar_tips").hide();});
});
</script> <?php if($my_member || $member) { ?> <?php $_mymember = $my_member ? $my_member : $member ?> <div class="sideBox" style="margin:0; padding:0;"> <div class="avatar2" id="m_avatar2"> <p class="avatar2_i"><a href="index.php?mod=<?php echo $_mymember['username']; ?>" title="<?php echo $_mymember['username']; ?>"><img src="<?php echo $_mymember['face_original']; ?>" alt="<?php echo $_mymember['nickname']; ?>" onerror="javascript:faceError(this);" /></a></p> <?php if(MEMBER_ID == $_mymember['uid']) { ?> <p class="avatar_tips"><a id="avatar_upload" href="index.php?mod=settings&code=face">上传头像</a></p> <?php } ?> </div> <div class="avatar2_info"> <p class="nameBox"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>" title="@<?php echo $_mymember['nickname']; ?>"><b><?php echo $_mymember['nickname']; ?></b></a><?php echo $_mymember['validate_html']; ?> <?php if($this->Config['level_radio']) { ?> <?php if($this->Config['topic_level_radio']) { ?> <span class="wb_l_level"> <a class="ico_level wbL<?php echo $_mymember['level']; ?>" title="微博等级：<?php echo $_mymember['level']; ?>级"  href="index.php?mod=settings&code=exp" target="_blank"><?php echo $_mymember['level']; ?></a> </span> <?php } ?> <?php } ?> </p> <?php if($_mymember['credits']) { ?> <div class="integral">积分：<a title="点击查看我的积分" href="index.php?mod=settings&code=extcredits"><?php echo $_mymember['credits']; ?></a> </div> <?php } ?> <p class="signBox" onclick="follower_choose(<?php echo $_mymember['uid']; ?>,'<?php echo $_mymember['nickname']; ?>','topic_signature'); return false;"> <?php $member_signature = cut_str($_mymember['signature'],20); ?> <?php if($_mymember['uid'] == MEMBER_ID ) { ?> <span ectype="user_signature_ajax_left_<?php echo $_mymember['uid']; ?>"> <span  title="个人签名：<?php echo $_mymember['signature']; ?>"> <?php if($_mymember['signature']) { ?> <?php echo $member_signature; ?> <?php } else { ?>编辑个人签名
<?php } ?> </span> </span> <?php } else { ?><span  title="个人签名：<?php echo $_mymember['signature']; ?>"> <?php if($_mymember['signature']) { ?> <?php if('admin' == MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="follower_choose(<?php echo $_mymember['uid']; ?>,'<?php echo $_mymember['nickname']; ?>','topic_signature');" title="点击修改个人签名"> <em ectype="user_signature_ajax_<?php echo $_mymember['uid']; ?>"><?php echo $member_signature; ?></em> </a> <?php } ?> <?php } ?> </span> <?php } ?> </p> <?php if(defined('NEDU_MOYO')) { ?> <?php echo nui('jsg')->hook('topic.member.left.inc.info');; ?> <?php } ?> <?php echo $this->hookall_temp['global_user_extra1']; ?> <?php echo $this->hookall_temp['global_user_extra2']; ?> <?php echo $this->hookall_temp['global_user_extra3']; ?> </div> </div> <div class="sideBox"> <div class="user_atten"> <div class="person_atten_l"> <p><span class="num"><a href="index.php?mod=<?php echo $_mymember['username']; ?>&code=follow" title="<?php echo $_mymember['nickname']; ?>关注的"><?php echo $_mymember['follow_count']; ?></a></span></p> <p><a href="index.php?mod=<?php echo $_mymember['username']; ?>&code=follow" title="<?php echo $_mymember['nickname']; ?>关注的">关注</a> </p> </div> <div class="person_atten_l"> <p><span class="num"><a href="index.php?mod=<?php echo $_mymember['username']; ?>&code=fans" title="关注<?php echo $_mymember['nickname']; ?>的"><?php echo $_mymember['fans_count']; ?></a></span></p> <p><a href="index.php?mod=<?php echo $_mymember['username']; ?>&code=fans" title="关注<?php echo $_mymember['nickname']; ?>的">粉丝</a> </p> </div> <div class="person_atten_r"> <p><span class="num"><a href="index.php?mod=<?php echo $_mymember['username']; ?>" title="<?php echo $_mymember['nickname']; ?>的微博"><?php echo $_mymember['topic_count']; ?></a></span></p> <p><a href="index.php?mod=<?php echo $_mymember['username']; ?>" title="<?php echo $_mymember['nickname']; ?>的微博">微博</a> </p> </div> </div> <?php echo $this->hookall_temp['global_user_extra4']; ?> </div> <?php } ?> <script type="text/javascript">
$(document).ready(function(){
$(".sina_weibo").mouseover(function(){$(".sina_weibo_c").show();});
$(".sina_weibo").mouseout(function(){$(".sina_weibo_c").hide();});
$(".qqwb").mouseover(function(){$(".qqwb_c").show();});
$(".qqwb").mouseout(function(){$(".qqwb_c").hide();});
$(".qqim").mouseover(function(){$(".qqim_c").show();});
$(".qqim").mouseout(function(){$(".qqim_c").hide();});
$(".tel").mouseover(function(){$(".tel_c").show();});
$(".tel").mouseout(function(){$(".tel_c").hide();});
<?php if(is_array($medal_list)) { foreach($medal_list as $v) { ?>
$(".medal_<?php echo $v['id']; ?>").mouseover(function(){$(".medal_c_<?php echo $v['id']; ?>").show();});
$(".medal_<?php echo $v['id']; ?>").mouseout(function(){$(".medal_c_<?php echo $v['id']; ?>").hide();});
<?php } } ?>
});
</script> <ul class="Vimg"> <?php if('tag'!=$this->Get['mod'] || $_mymember['style_three_tol'] == 1) { ?> <?php if($this->Config['sina_enable'] && sina_weibo_init($this->Config)) { ?> <li class="sina_weibo"> <?php echo sina_weibo_bind_icon($_mymember['uid']); ?>
&nbsp; 
<div class="sina_weibo_c"> <div class="VM_box"> <div class="VM_top"> <div class="med_img"><img src="./templates/default/images/medal/M_sina.gif"></div> <div class="med_intro"> <p>新浪微博</p>
绑定后，可以使用新浪微博帐号进行登录，在本站发的微博可以同步发到新浪微博<br /> <?php $sina_return  = sina_weibo_has_bind($member['uid']); ?> <?php if(!$sina_return) { ?> <a href="index.php?mod=account&code=sina">绑定新浪微博</a> |
<?php } ?> <a target="_blank" href="index.php?mod=other&code=medal&view=my">查看我的勋章</a> </div> </div> </div> </div> </li> <?php } ?> <?php if($this->Config['qqwb_enable'] && qqwb_init($this->Config)) { ?> <li class="qqwb"> <?php echo qqwb_bind_icon($_mymember['uid']); ?>
&nbsp; 
<div class="qqwb_c"> <div class="VM_box"> <div class="VM_top"> <div class="med_img"><img src="./templates/default/images/medal/qqwb.png"></div> <div class="med_intro"> <p>腾讯微博</p>
绑定后，可以使用腾讯微博帐号进行登录，在本站发的微博可以同步发到腾讯微博<br /> <?php $qqwb_return  = qqwb_bind_icon($member['uid']); ?> <?php if(!$qqwb_return) { ?> <a href="index.php?mod=account&code=qqwb">绑定腾讯微博</a> |
<?php } ?> <a target="_blank" href="index.php?mod=other&code=medal&view=my">查看我的勋章</a> </div> </div> </div> </div> </li> <?php } ?> <?php if($this->Config['imjiqiren_enable'] && imjiqiren_init($this->Config)) { ?> <li class="qqim"> <?php echo imjiqiren_bind_icon($_mymember['uid']); ?>
&nbsp; 
<div class="qqim_c"> <div class="VM_box"> <div class="VM_top"> <div class="med_img"><img src="./templates/default/images/medal/M_qq.gif"></div> <div class="med_intro"> <p>QQ机器人</p>
用自己的QQ发微博、通过QQ签名发微博，如果有人@你、评论你、关注你、给你发私信，你都可以第一时间收到QQ机器人的通知<br /> <?php $imjiqiren_return  = imjiqiren_has_bind($member['uid']); ?> <?php if(!$imjiqiren_return) { ?> <a href="index.php?mod=tools&code=imjiqiren">绑定QQ机器人</a> |
<?php } ?> <a target="_blank" href="index.php?mod=other&code=medal&view=my">查看我的勋章</a> </div> </div> </div> </div> </li> <?php } ?> <?php if($this->Config['sms_enable'] && sms_init($this->Config)) { ?> <li class="tel"> <?php echo sms_bind_icon($_mymember['uid']); ?>
&nbsp; 
<div class="tel_c"> <div class="VM_box"> <div class="VM_top"> <div class="med_img"><img src="./templates/default/images/medal/Tel.gif"></div> <div class="med_intro"> <p>手机短信</p>
用自己的手机发微博、通过手机签名发微博，如果有人@你、评论你、关注你、给你发私信，你都可以第一时间收到手机短信的通知<br /> <?php $sms_return  = sms_has_bind($_mymember['uid']); ?> <?php if(!$sms_return) { ?> <a href="index.php?mod=other&code=sms">绑定手机短信</a> |
<?php } ?> <a target="_blank" href="index.php?mod=other&code=medal&view=my">查看我的勋章</a> </div> </div> </div> </div> </li> <?php } ?> <?php } ?> <?php if($member['validate'] || $medal_list) { ?> <?php if(is_array($medal_list)) { foreach($medal_list as $val) { ?> <?php $medal_type = unserialize($val['conditions']); ?> <li class="medal_<?php echo $val['id']; ?>"><a href="index.php?mod=other&code=medal" target="_blank"><img src="<?php echo $val['medal_img']; ?>"/></a> &nbsp; 
<div class="medal_c medal_c_<?php echo $val['id']; ?>"> <div class="VM_box"> <div class="VM_top"> <div class="med_img"><img src="<?php echo $val['medal_img']; ?>"/></div> <div class="med_intro"> <p><?php echo $val['medal_name']; ?></p> <?php echo $val['medal_depict']; ?> <br /> <?php if(MEMBER_ID != $member['uid']) { ?>
(他于：<?php echo $val['dateline']; ?> 获得) <br /> <?php if($medal_type['type'] == 'topic') { ?> <a href="index.php?mod=topic&code=myhome" target="_blank">我要发微博</a> |<?php } elseif($medal_type['type'] == 'reply') { ?><a href="index.php?mod=topic&code=new" target="_blank">我要发评论</a> |<?php } elseif($medal_type['type'] == 'tag') { ?><a href="index.php?mod=tag&code=<?php echo $medal_type['tagname']; ?>" target="_blank">我要发话题</a> |<?php } elseif($medal_type['type'] == 'invite') { ?><a href="index.php?mod=profile&code=invite" target="_blank">马上去邀请好友</a> |<?php } elseif($medal_type['type'] == 'shoudong') { ?>管理员手动发放  |	
<?php } ?> <?php } else { ?>(我于：<?php echo $val['dateline']; ?> 获得) <br /> <?php } ?> <a target="_blank" href="index.php?mod=other&code=medal&view=my">查看我的勋章</a> </div> </div> </div> </div> </li> <?php } } ?> <?php } ?> <?php if($this->Config['yy_enable'] && yy_init($this->Config)) { ?> <li class="yy"> <?php echo yy_bind_icon($_mymember['uid']); ?>
&nbsp;</li> <?php } ?> <?php if($this->Config['renren_enable'] && renren_init($this->Config)) { ?> <li class="renren"> <?php echo renren_bind_icon($_mymember['uid']); ?>
&nbsp;</li> <?php } ?> <?php if($this->Config['kaixin_enable'] && kaixin_init($this->Config)) { ?> <li class="kaixin"> <?php echo kaixin_bind_icon($_mymember['uid']); ?>
&nbsp;</li> <?php } ?> <?php if($this->Config['fjau_enable'] && fjau_init($this->Config)) { ?> <li class="fjau"> <?php echo fjau_bind_icon($_mymember['uid']); ?>
&nbsp;</li> <?php } ?> </ul> <?php if(MEMBER_ID == $_mymember['uid'] ) { ?> <div class="blackBox"></div> <ul class="boxRNav2"> <?php if(in_array($this->Code,array('myhome','tag','groupview','qun','cms','bbs','recd'))) $current_myhome = 'current' ?> <?php if('myat'== $this->Code) $current_myat = 'current' ?> <?php if('mycomment'== $this->Code) $current_mycomment = 'current' ?> <?php if('myfavorite'== $this->Code) $current_myfavorite = 'current' ?> <?php if('company'== $_GET['mod']) $current_company = 'current' ?> <?php if('department'== $_GET['mod']) $current_department = 'current' ?> <li class="index <?php echo $current_myhome; ?>"> <a href="index.php?mod=topic&code=myhome" hidefocus="true" title="我的首页">我的首页</a> </li> <li class="about <?php echo $current_myat; ?>"> <a href="index.php?mod=topic&code=myat" hidefocus="true" title="提到我的">提到我的</a> </li> <li class="letter <?php echo $current_mycomment; ?>"> <a href="index.php?mod=topic&code=mycomment" hidefocus="true" title="评论我的">评论我的</a> </li> <li class="myfav <?php echo $current_myfavorite; ?>"> <a href="index.php?mod=topic&code=myfavorite" hidefocus="true" title="我的收藏">我的收藏</a> </li> <?php if($this->Config['company_enable']) { ?> <li class="index <?php echo $current_company; ?>"> <a href="index.php?mod=company" hidefocus="true" title="我的<?php echo $d_c_name; ?>">我的<?php echo $d_c_name; ?></a> </li> <?php if($this->Config['department_enable']) { ?> <li class="letter <?php echo $current_department; ?>"> <a href="index.php?mod=department" hidefocus="true" title="我的<?php echo $d_d_name; ?>">我的<?php echo $d_d_name; ?></a> </li> <?php } ?> <?php } ?> <?php if(defined('NEDU_MOYO')) { ?> <?php echo nui('jsg')->hook('topic.member.left.inc.nav');; ?> <?php } ?> </ul> <?php } ?> <div class="blackBox"></div> <ul class="boxRNav2"> <?php if(MEMBER_ID == $_mymember['uid']) $_my = '我'; elseif(1==$_mymember['gender']) $_my = '他';else $_my = '她'; ?> <?php if('myblog'== $params['code'] && !$type) $current_myblog = 'current' ?> <?php if('myblog'== $params['code'] && 'pic' == $type) $current_pic = 'current' ?> <?php if('myblog'== $params['code'] && 'video' == $type) $current_video = 'current' ?> <?php if('myblog'== $params['code'] && 'music' == $type) $current_music = 'current' ?> <?php if('myblog'== $params['code'] && 'attach' == $type) $current_attach = 'current' ?> <?php if('myblog'== $params['code'] && 'my_reply' == $type) $current_my_reply = 'current' ?> <?php if('myblog'== $params['code'] && 'vote' == $type) $current_vote = 'current' ?> <?php if('myblog'== $params['code'] && 'event' == $type) $current_event = 'current' ?> <li class="mypub <?php echo $current_myblog; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>" hidefocus="true" title="<?php echo $_my; ?>的微博"><?php echo $_my; ?>的微博</a> </li> <li class="mycomment <?php echo $current_my_reply; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=my_reply" hidefocus="true" title="<?php echo $_my; ?>的评论"><?php echo $_my; ?>的评论</a> </li> <li class="mypic <?php echo $current_pic; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=pic" hidefocus="true" title="<?php echo $_my; ?>的图片"><?php echo $_my; ?>的图片</a> </li> <li class="myvoid <?php echo $current_video; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=video" hidefocus="true" title="<?php echo $_my; ?>的视频"><?php echo $_my; ?>的视频</a> </li> <li class="mymusic <?php echo $current_music; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=music" hidefocus="true" title="<?php echo $_my; ?>的音乐"><?php echo $_my; ?>的音乐</a> </li> <li class="myatt <?php echo $current_attach; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=attach" hidefocus="true" title="<?php echo $_my; ?>的附件"><?php echo $_my; ?>的附件</a> </li> <?php if($this->Config['vote_open']) { ?> <li class="myvote <?php echo $current_vote; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=vote" hidefocus="true" title="<?php echo $_my; ?>的投票"><?php echo $_my; ?>的投票</a> </li> <?php } ?> <?php if($this->Config['event_open']) { ?> <li class="myact <?php echo $current_event; ?>"> <a href="index.php?mod=<?php echo $_mymember['username']; ?>&type=event" hidefocus="true" title="<?php echo $_my; ?>的活动"><?php echo $_my; ?>的活动</a> </li> <?php } ?> <?php $navigation_config=ConfigHandler::get('navigation') ?> <?php if(!empty($navigation_config['pluginmenu'])) { ?> <?php if(is_array($navigation_config['pluginmenu'])) { foreach($navigation_config['pluginmenu'] as $pmenus) { ?> <?php if(is_array($pmenus)) { foreach($pmenus as $pmenu) { ?> <?php if($pmenu['type'] == 3) { ?> <?php if('topic'==$this->require) { ?> <li class="mypub current"> <?php } else { ?> <li class="mypub"> <?php } ?> <a href="<?php echo $pmenu['url']; ?>&require=topic" hidefocus="true" title="<?php echo $pmenu['name']; ?>"><?php echo $pmenu['name']; ?></a></li> <?php } ?> <?php } } ?> <?php } } ?> <?php } ?> </ul> <div class="blackBox2"></div> </div> </div> </div> </div> <div class="main3Box_m HotW "> <div class="Hotwarp"> <div class="e_list_title"><?php echo $rs['title']; ?> <?php if($from['name']) { ?>
(来自<a href="<?php echo $from['url']; ?>" target='_blank'><?php echo $from['name']; ?></a>)
<?php } ?> </div> <div class="e_list_box"> <div class="left_user_info"> <div class="avatar_left"> <a target="_blank" href="<?php echo $rs['image']; ?>"> <img src="<?php echo $rs['image']; ?>" height="128" width="100"></img> </a> </div> <div class="avatar2_info event_d avatar2_info_2" style="margin-right:10px; border-right:1px dashed #ccc;"> <p class="left_t2">
开始时间：<?php echo $rs['fromt']; ?>（<?php echo $rs['fromt_day']; ?>）
</p> <p class="left_t2">
结束时间：<?php echo $rs['tot']; ?>（<?php echo $rs['tot_day']; ?>）
</p> <p class="left_t2">
地点：<?php echo $rs['address']; ?> </p> <p class="left_t2">
发起人：<a href="index.php?mod=<?php echo $rs['username']; ?>"  target="_blank"><?php echo $rs['nickname']; ?></a><a href="index.php?mod=event&code=myevent&uid=<?php echo $rs['uid']; ?>" target="_blank">(查看他的活动)</a> </p> <p class="left_t2">
费用：<?php echo $rs['money']; ?> </p> <p class="left_t21"> <a href="javascript:void(0);" onclick="share();return false;" class="efx">分享</a> <?php if(!$rs['store']) { ?> <a href="javascript:void(0);" onclick="store('store');return false;" class="esc">收藏</a> <?php } else { ?> <a href="javascript:void(0);" onclick="store('cancle');return false;" class="esc">取消收藏</a> <?php } ?> <?php if(MEMBER_ID == $rs['postman']) { ?> <a href="javascript:void(0);" onclick="manage(1);return false;" class="egl">管理</a> <a href="javascript:void(0);" onclick="window.location.href='index.php?mod=event&code=export_to_excel&id=<?php echo $id; ?>';return false;" class="edc">导出</a> <?php } ?> </p> <?php if(MEMBER_ID == $rs['postman']) { ?> <p class="left_t21"> <a href="index.php?mod=event&code=editevent&id=<?php echo $rs['id']; ?>" class="exg">修改活动</a> <a href="index.php?mod=event&code=del&id=<?php echo $rs['id']; ?>" onclick="return confirm('你确实要删除吗?不可恢复');" class="edel">删除活动</a> </p> <?php } ?> </div> <div class="e_detail_sign"> <p class="ri_t2"><?php echo $rs['app_num']; ?>人报名</p> <p class="ri_t2">同意<?php echo $rs['play_num']; ?>人 参与</p> <p id="event_type"><span><?php echo $rs['event_type']; ?></span></p> <p id="app"> <?php if($rs['app'] == 1 || $rs['app']=='已报名') { ?> <a href="javascript:void(0);" onclick="app('cancle');return false;">取消报名</a> <?php } else { ?> <a href="javascript:void(0);" onclick="app('app');return false;">我要报名</a> <?php } ?> </p> </div> </div> </div> <div class="e_idS" style="overflow:visible"> <div class="e_idSpTitle"> <b>活动简介</b> </div> <div class="e_intro"> <?php echo $rs['content']; ?> </div> </div> <div class="topic_new_add"> <script language="javascript">
__APPITEM__ = 'event';
__APPITEM_ID__ = <?php echo $rs['id']; ?>;
</script> <?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><style type="text/css">ul.mycon li{ width:65px;}</style> <script type="text/javascript" src="templates/default/js/publishbox.js?build+20120829"></script> <div id="zz_main_publish"> <div id="tigBox_2" class="tigBox_2">点<a href="javascript:" onClick="thread_insert()" style="cursor:pointer">#插入自定义话题#</a>给微博添加话题，方便关注</div> <div class="issueBox"> <div class="fbqCount"> <div class="fLeft"> <?php if($this->Get['mod'] == 'member') { ?> <?php $content = '#新人报到# 我是'.$this->Config['site_name'].'新加入的成员@'.MEMBER_NICKNAME.' ，欢迎大家来关注我！'; ?>
所有关注我的人将看到我分享的信息<?php } elseif($defaust_value) { ?><?php echo $defaust_value; ?> <?php } else { ?><span> <?php $__member_fans_count=(int)($GLOBALS['_J']['member']['fans_count']?$GLOBALS['_J']['member']['fans_count']:$member['fans_count']); ?> <?php if($__member_fans_count>0) { ?> <?php echo $__member_fans_count; ?> <?php } else { ?>0
<?php } ?> </span>人在关注我，
<A href="index.php?mod=profile&code=invite" style="cursor:pointer">邀请</A>更多人
<?php } ?> </div> <ul class="mycon"> <?php if($this->Config['topic_input_length']>0) { ?> <li>还可以输入</li><li style="width:auto"><span id="wordCheck<?php echo $h_key; ?>" style='font-family:Georgia;font-size:24px;'><?php echo $this->Config['topic_input_length']; ?></span></li><li style="width:14px;">字</li> <?php } ?> </ul> </div> <div class="box_1" style="display:block"> <?php $i_already_value = $content ? $content : $this->Config['today_topic'];$this->totid = $this->totid ? $this->totid : 0; ?> <script type="text/javascript">
var __I_ALREADY_VALUE__ = '<?php echo $i_already_value; ?>';
var __ALERT__='<?php echo $this->Config['verify_alert']; ?>';
</script> <textarea name="content" id="i_already<?php echo $h_key; ?>"  onkeyup="javascript:checkWord('<?php echo $this->Config['topic_input_length']; ?>',event,'wordCheck<?php echo $h_key; ?>')" onkeydown="ctrlEnter(event, 'publishSubmit<?php echo $h_key; ?>')"><?php echo $i_already_value; ?></textarea> <?php $_get_type=str_safe($this->Get['type']); ?> <input name="topic_type" type="hidden" id="topic_type<?php echo $h_key; ?>" value="<?php echo $_get_type; ?>" /> <input name="totid" type="hidden" id="totid<?php echo $h_key; ?>" value="<?php echo $this->totid; ?>" /> <input name="touid" type="hidden" id="touid<?php echo $h_key; ?>" value="<?php echo $this->touid; ?>" /> <input name="item" type="hidden" id="mapp_item<?php echo $h_key; ?>" value="<?php echo $this->item; ?>" /> <input name="item_id" type="hidden" id="mapp_item_id<?php echo $h_key; ?>" value="<?php echo $this->item_id; ?>" /> <input name="xiami_id" type="hidden" id="xiami_id" value="" /> </div> <?php if(!($type == 'design' || $type == 'btn_wyfx')) { ?> <div class="box_3"> <script type="text/javascript">
$(document).ready(function() {	 	
//表情
$(".menu_bqb_c").click(function(){
$("#showface<?php echo $h_key; ?>").show();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$(".menu_spb").hide();
$('.menu_htb').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$('#showface<?php echo $h_key; ?>').click(function(){return false;});
//图片 
$(".menu_tqb_c").click(function(){
$(".menu_tqb").show();
$(".menu_fjb").hide();
$(".menu_spb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$('#pubImg').click(function(){
$("#publisher_file").style.posLeft=event.x-offsetWidth/2;$("#publisher_file").style.posTop=event.y-offsetHeight/2;});
$(".menu_tqb_c1").click(function(){$(".menu_tqb").hide();});
$("#publishSubmit").click(function(){$(".menu_tqb").hide();});
//附件 
$(".menu_fjb_c").click(function(){
$(".menu_fjb").show();
$(".menu_tqb").hide();
$(".menu_spb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$('#pubAttach').click(function(){
$("#publisher_file_attach").style.posLeft=event.x-offsetWidth/2;$("#publisher_file_attach").style.posTop=event.y-offsetHeight/2;});
$(".menu_fjb_c1").click(function(){$(".menu_fjb").hide();});
$("#publishSubmit").click(function(){$(".menu_fjb").hide();});
//视频
$(".menu_spb_c").click(function(){
$(".menu_spb").show();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$(".menu_spb_c1").click(function(){$(".menu_spb").hide();});
//音乐
$(".menu_m_c").click(function(){
$(".menu_music").show();
$(".menu_wqb").hide();
$(".menu_tqb").hide();
$(".menu_spb").hide();
$('#showface').hide();
$('.menu_htb').hide();
$(".menu_vsb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_fjb").hide();
});
$(".menu_music_c1").click(function(){$(".menu_music").hide();});
//话题
$(".menu_htb_c").click(function(){
$(".menu_htb").show();
$(".menu_spb").hide();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$('.menu_htb').click(function(){return false;});
//签到
$(".menu_hts_c").click(function(){
$(".menu_hts").show();
$(".menu_htb").hide();
$(".menu_spb").hide();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_vsb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_music").hide();
});
$(".menu_hts_c1").click(function(){$(".menu_hts").hide();});
//投票
$(".menu_vsb_c").click(function(){
getVoteAvtivityFromIndex('vote_publish', 'con_vote_content_1');
$(".menu_vsb").show();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$(".menu_spb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$(".menu_wqb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$(".menu_vsb_c1").click(function(){$(".menu_vsb").hide();});
//活动
$(".menu_hdb_c").click(function(){
getEventPost();
$(".menu_hdb").show();
$(".menu_vsb").hide();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$(".menu_spb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$(".menu_wqb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$(".menu_hdb_c1").click(function(){$(".menu_hdb").hide();});
//微群
$(".menu_wqb_c").click(function(){
getMyQun();
$(".menu_wqb").show();
$(".menu_tqb").hide();
$(".menu_fjb").hide();
$(".menu_spb").hide();
$('#showface<?php echo $h_key; ?>').hide();
$('.menu_htb').hide();
$(".menu_vsb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$(".menu_wqb_c1").click(function(){$(".menu_wqb").hide();});
//长文本
$(".menu_cwb_c").click(function(){
initKindEditor();
/*
get_longtext_info();
$(".menu_cwb").show();
$(".menu_tqb").hide();
$(".menu_spb").hide();
$('#showface').hide();
$('.menu_htb').hide();
$(".menu_vsb").hide();
$(".menu_hdb").hide();
$(".menu_wqb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
//*/
});
$(".menu_cwb_c1").click(function(){$(".menu_cwb").hide();});
$(".menu_xb_c").click(function(){
getClassPost();
$(".menu_xb").show();
$(".menu_wqb").hide();
$(".menu_tqb").hide();
$(".menu_spb").hide();
$('#showface').hide();
$('.menu_htb').hide();
$(".menu_vsb").hide();
$(".menu_hts").hide();
$(".menu_music").hide();
});
$(".menu_xb_c1").click(function(){$(".menu_xb").hide();});
//同步
$(".box_4_open_span").click(function(){
$(".box_4_open_Box").show();
$(".menu_wqb").hide();
$(".menu_tqb").hide();
$(".menu_spb").hide();
$('#showface').hide();
$('.menu_htb').hide();
$(".menu_vsb").hide();
$(".menu_hdb").hide();
$(".menu_cwb").hide();
$(".menu_hts").hide();
$(".menu_fjb").hide();
});
$(".box_4_open_span_c1").click(function(){$(".box_4_open_Box").hide();});
//$(".box_4_open_Box").mouseout(function(){$(".box_4_open_Box").hide();});
});
//-----------------------------------
function setTab(name,cursel,n){
for(i=1;i<=n;i++){
var menu=document.getElementById(name+i);
var con=document.getElementById("con_"+name+"_"+i);
menu.className=i==cursel?"vhover":"";
con.style.display=i==cursel?"block":"none";
}
}
function setTab1(name,cursel,n){
for(i=1;i<=n;i++){
var menu=document.getElementById(name+i);
var con=document.getElementById("con_"+name+"_"+i);
menu.className=i==cursel?"vhover":"";
con.style.display=i==cursel?"block":"none";
}
}
function setTab2(name,cursel,n){
for(i=1;i<=n;i++){
var menu2=document.getElementById(name+i);
var con2=document.getElementById("con2_"+name+"_"+i);
menu2.className=i==cursel?"vhover2":"";
con2.style.display=i==cursel?"block":"none";
}
}
</script> <?php if($this->Config['sign']['sign_enable']) { ?> <div class="menu" > <div class="menu_ht menu_qd" id="sign_<?php echo MEMBER_ID; ?>"><span onclick="getSignTag(<?php echo MEMBER_ID; ?>);return false;" class="menu_hts_c">签到</span> <div class="menu_hts"> <div id="sign_tag_<?php echo MEMBER_ID; ?>"></div> </div> </div> </div> <?php } ?> <div class="menu"> <div class="menu_bq" id="editface" ><b class="menu_bqb_c"><a href="javascript:void(0);" onclick="topic_face('showface<?php echo $h_key; ?>','i_already<?php echo $h_key; ?>','topic_face');return false;">表情</a></b> <div id="showface<?php echo $h_key; ?>" class="showface"></div> </div></div> <?php $image_uploadify_topic = array(); ?> <?php $image_uploadify_from = 'topic_publish'; ?> <?php $image_uploadify_only_js = 1; ?> <?php $image_uploadify_id=$content_textarea_id.$image_uploadify_type.($image_uploadify_topic['tid']>0?"_".$image_uploadify_topic['tid']:""); ?> <?php $image_uploadify_image_small_size=$image_uploadify_image_small_size?$image_uploadify_image_small_size:45; ?> <?php $content_textarea_id=$content_textarea_id?$content_textarea_id:'i_already'.$h_key; ?> <?php $content_textarea_empty_val=isset($content_textarea_empty_val)?$content_textarea_empty_val:'分享图片'; ?> <?php $image_uploadify_queue_size_limit=max(0, (int) $this->Config['image_uploadify_queue_size_limit']);if($image_uploadify_queue_size_limit<1)$image_uploadify_queue_size_limit=3; ?> <?php $img_item=isset($this->item)?$this->item:''; ?> <?php $img_itemid=isset($this->item_id)?$this->item_id:0; ?> <success></success> <script type="text/javascript">
var __IMAGE_IDS__ = {};
$(document).ready(function(){
$('#publisher_file<?php echo $image_uploadify_id; ?>').uploadify({
'uploader'  : '<?php echo $this->Config['site_url']; ?>/images/uploadify/uploadify.swf?id=<?php echo $image_uploadify_id; ?>&random=' + Math.random(),
'script'    : '<?php echo urlencode($this->Config['site_url'] . "/ajax.php?mod=uploadify&code=image&iitem=$img_item&iitemid=$img_itemid"); ?>',
'cancelImg' : '<?php echo $this->Config['site_url']; ?>/images/uploadify/cancel.png',
'buttonImg'	: '<?php echo $this->Config['site_url']; ?>/images/uploadify/addatt.gif',
'width'		: 111,
'height'	: 17,
'multi'		: true,
'auto'      : true,
'sizeLimit' : '2097152',
'fileExt'	: '*.jpg;*.png;*.gif;*.jpeg',
'fileDesc'	: '请选择图片文件(*.jpg;*.png;*.gif;*.jpeg)',
'queueID'	: 'uploadifyQueueDiv<?php echo $image_uploadify_id; ?>',
'wmode'		: 'transparent',
'fileDataName'	 : 'topic',
'queueSizeLimit' : <?php echo $image_uploadify_queue_size_limit; ?>,
'simUploadLimit' : 1,
'scriptData'	 : {
<?php if($image_uploadify_topic_uid) { ?>
'topic_uid'  : '<?php echo $image_uploadify_topic_uid; ?>',
<?php } ?>
'cookie_auth': '<?php echo urlencode(jsg_getcookie("auth")); ?>'
},
'onSelect'		 : function(event, ID, fileObj) {
},
'onSelectOnce'	 : function (event, data) {
imageUploadifySelectOnce<?php echo $image_uploadify_id; ?>();			    	
},
'onProgress'     : function(event, ID, fileObj, data) {
return false;
},
'onComplete'	 : function(event, ID, fileObj, response, data) {
eval('var r = ' + response + ';');
if (r.done) {					
var rv = r.retval;
if ( rv.id > 0 && rv.src.length > 0 ) {
imageUploadifyComplete<?php echo $image_uploadify_id; ?>(rv.id, rv.src, fileObj.name);
}
}
},
'onError'        : function (event, ID, fileObj, errorObj) {
alert(errorObj.type + ' Error: ' + errorObj.info);
},
'onAllComplete'	 : function(event, data) {
imageUploadifyAllComplete<?php echo $image_uploadify_id; ?>();
}
});
$("#viewImgDiv<?php echo $image_uploadify_id; ?> img").each(function() {
if($(this).width() > $(this).parent().width()) {
$(this).width("100%");
}
});
});
//删除一张图片
function imageUploadifyDelete<?php echo $image_uploadify_id; ?>(idval)
{
var idval = ('undefined'==typeof(idval) ? 0 : idval);
if(idval > 0) 
{
$.post
(
'ajax.php?mod=uploadify&code=delete_image',
{
'id' : idval
},
function (r) 
{				
if(r.done)
{
$('#uploadImgSpan_' + idval).remove();
if( ($.trim(($('#viewImgDiv<?php echo $image_uploadify_id; ?>').html()))).length < 1 )
{
$('#viewImgDiv<?php echo $image_uploadify_id; ?>').css('display', 'none');
$('#insertImgDiv<?php echo $image_uploadify_id; ?>').css('display', 'block');
}
if( 'undefined' != typeof(__IMAGE_IDS__[idval]) )
{
__IMAGE_IDS__[idval] = 0;
}
}
else
{
if(r.msg)
{
MessageBox('warning', r.msg);
}
}
},
'json'
);
}
}
function imageUploadifySelectOnce<?php echo $image_uploadify_id; ?>()
{
$('#uploading<?php echo $image_uploadify_id; ?>').html("<img src='images/loading.gif'/>&nbsp;上传中，请稍候……");
}
function imageUploadifyComplete<?php echo $image_uploadify_id; ?>(idval, srcval, nameval)
{
var imageIdsCount = 0;
$.each( __IMAGE_IDS__, function( k, v ) { if( v > 0 ) { imageIdsCount += 1; } } );
if( imageIdsCount >= <?php echo $image_uploadify_queue_size_limit; ?> )
{
MessageBox('warning', '本次图片数量超过限制了');
return false;
}
var idval = ('undefined' == typeof(idval) ? 0 : idval);
var srcval = ('undefined' == typeof(srcval) ? 0 : srcval);
var nameval = ('undefined' == typeof(nameval) ? '' : nameval);
<?php if('topic_publish'==$image_uploadify_from) { ?>
$('#viewImgDiv<?php echo $image_uploadify_id; ?>').prepend('<li id="uploadImgSpan_' + idval + '" class="menu_ps vv_2"><img sr' + 'c' + '=' + '"' + srcval + '"/> <p><i>' + nameval + ' <a title="删除图片" onclick="imageUploadifyDelete<?php echo $image_uploadify_id; ?>(' + idval + ');return false;" href="javascript:;">删除</a></i></p></li>');<?php } elseif('topic_longtext_info_ajax'==$image_uploadify_from) { ?>$('#viewImgDiv<?php echo $image_uploadify_id; ?>').append('<span id="uploadImgSpan_' + idval + '"><img s' + 'rc' + '="' + srcval + '" width="<?php echo $image_uploadify_image_small_size; ?>" alt="点击图片插入到文中" onclick="longtext_info_img_insert(\'' + srcval + '\');" /><a href="javascript:void(0);" onclick="imageUploadifyDelete<?php echo $image_uploadify_id; ?>(' + idval + '); return false;" title="删除图片">×</a></span>');
<?php } else { ?>$('#viewImgDiv<?php echo $image_uploadify_id; ?>').append('<span id="uploadImgSpan_' + idval + '"><img sr' + 'c' + '="' + srcval + '" width="<?php echo $image_uploadify_image_small_size; ?>" /><a href="javascript:void(0);" onclick="imageUploadifyDelete<?php echo $image_uploadify_id; ?>(' + idval + '); return false;" title="删除图片">×</a></span>');
<?php } ?>
$('#normalUploadFile<?php echo $image_uploadify_id; ?>').val('');
__IMAGE_IDS__[idval] = idval;
}
function imageUploadifyAllComplete<?php echo $image_uploadify_id; ?>()
{
$('#uploading<?php echo $image_uploadify_id; ?>').html('');			    	
$('#viewImgDiv<?php echo $image_uploadify_id; ?>').css('display', 'block');
//$('#insertImgDiv<?php echo $image_uploadify_id; ?>').css('display', 'none');
if( $.trim(($('#<?php echo $content_textarea_id; ?>').val())).length < 1 ) {
$('#<?php echo $content_textarea_id; ?>').val('<?php echo $content_textarea_empty_val; ?>');	
}
$('#<?php echo $content_textarea_id; ?>').focus();
}
function normalUploadFormSubmit<?php echo $image_uploadify_id; ?>()
{
var fileVal = $('#normalUploadFile<?php echo $image_uploadify_id; ?>').val();
if(($.trim(fileVal)).length < 1)
{
MessageBox('warning', '请选择一个正确的图片文件');
return false;
}
else
{
if(!(/\.(jpg|png|gif|jpeg)$/i.test(fileVal)))
{
MessageBox('warning', '请选择一个正确的图片文件');
return false;
}
else
{
imageUploadifySelectOnce<?php echo $image_uploadify_id; ?>();
return true;
}
}
}
function imageUploadifyModuleSwitch<?php echo $image_uploadify_id; ?>()
{
if('none' == $('#normalUploadDiv<?php echo $image_uploadify_id; ?>').css('display'))
{
$('#uploadDescModuleSpan<?php echo $image_uploadify_id; ?>').html('快速');
$('#swfUploadDiv<?php echo $image_uploadify_id; ?>').css('display', 'none');
$('#normalUploadDiv<?php echo $image_uploadify_id; ?>').css('display', 'block');
}
else
{
$('#uploadDescModuleSpan<?php echo $image_uploadify_id; ?>').html('普通');
$('#normalUploadDiv<?php echo $image_uploadify_id; ?>').css('display', 'none');
$('#swfUploadDiv<?php echo $image_uploadify_id; ?>').css('display', 'block');
}
}
</script> <?php if(!$image_uploadify_only_js) { ?> <div id="insertImgDiv<?php echo $image_uploadify_id; ?>" class="insertImgDiv" > <i class="insertImgDiv_up_<?php echo $image_uploadify_id; ?> insertImgDiv_up" onclick="$(this).parent().hide()"><img src="templates/default/images/imgdel.gif" title="关闭" /></i> <div id="swfUploadDiv<?php echo $image_uploadify_id; ?>"><input type="file" id="publisher_file<?php echo $image_uploadify_id; ?>" name="publisher_file<?php echo $image_uploadify_id; ?>" style="display:none;" />（按ctrl键可一次选多图上传）</div> <iframe id="imageUploadifyIframe<?php echo $image_uploadify_id; ?>" name="imageUploadifyIframe<?php echo $image_uploadify_id; ?>" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank" style="display:none;"></iframe> <div id="normalUploadDiv<?php echo $image_uploadify_id; ?>" style="display:none;"> <form id="normalUploadForm<?php echo $image_uploadify_id; ?>" method="post"  action="ajax.php?mod=uploadify&code=image&type=normal&iitem=<?php echo $img_item; ?>&iitemid=<?php echo $img_itemid; ?>" enctype="multipart/form-data" target="imageUploadifyIframe<?php echo $image_uploadify_id; ?>" onsubmit="return normalUploadFormSubmit<?php echo $image_uploadify_id; ?>()"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input type="hidden" name="image_uploadify_id" value="<?php echo $image_uploadify_id; ?>" /> <input type="file" id="normalUploadFile<?php echo $image_uploadify_id; ?>" name="topic" /> <input type="submit" value="上传" class="tul" /> </form> </div> <span id="uploading<?php echo $image_uploadify_id; ?>"></span> <div id="uploadDescDiv<?php echo $image_uploadify_id; ?>"> <span class="fontRed">*</span> 如果您不能上传图片，可以<a href="javascript:;" onclick="imageUploadifyModuleSwitch<?php echo $image_uploadify_id; ?>();">点击这里</a>尝试<span id="uploadDescModuleSpan<?php echo $image_uploadify_id; ?>">普通</span>模式上传
<?php if('topic_longtext_info_ajax'==$image_uploadify_from) { ?> <br /><span class="fontRed">*</span> 图片上传成功后，可以点击图片将图片插入到您想要的位置
<?php } ?> </div> <div id="uploadifyQueueDiv<?php echo $image_uploadify_id; ?>" style="display:none;"></div> <div id="viewImgDiv<?php echo $image_uploadify_id; ?>" class="viewImgDiv"> <?php if((!$image_uploadify_new || $image_uploadify_modify) && $image_uploadify_topic['imageid']) { ?> <?php if(is_array($image_uploadify_topic['image_list'])) { foreach($image_uploadify_topic['image_list'] as $ik => $iv) { ?> <script type="text/javascript"> __IMAGE_IDS__[<?php echo $ik; ?>] = <?php echo $ik; ?>; </script> <span id="uploadImgSpan_<?php echo $ik; ?>"> <img src="<?php echo $iv['image_small']; ?>" width="<?php echo $image_uploadify_image_small_size; ?>" /> <a href="javascript:void(0);" onclick="imageUploadifyDelete<?php echo $image_uploadify_id; ?>('<?php echo $ik; ?>'); return false;" title="删除图片">×</a> </span> <?php } } ?> <?php } ?> </div> </div> <?php } ?> <div class="menu"> <div class="menu_tq" ><b class="menu_tqb_c">图片</b> <div class="menu_tqb"> <div class="menu_pi insertImgDiv" id="insertImgDiv"> <div id="swfUploadDiv"><input type="file" id="publisher_file" name="publisher_file" style="display:none;" />（按ctrl键可一次选多图上传）</div> <iframe id="imageUploadifyIframe" name="imageUploadifyIframe" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank" style="display:none;"></iframe> <div id="normalUploadDiv" style="display:none;"> <form id="normalUploadForm" method="post"  action="ajax.php?mod=uploadify&code=image&type=normal&iitem=<?php echo $img_item; ?>&iitemid=<?php echo $img_itemid; ?>" enctype="multipart/form-data" target="imageUploadifyIframe" onsubmit="return normalUploadFormSubmit()"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input type="file" id="normalUploadFile" name="topic" /> <input type="submit" value="上传" class="tul" /> </form> </div> <i class="menu_tqb_c1"><img src="templates/default/images/imgdel.gif" title="关闭" /></i> <em>
1、如您不能上传图片，请<a href="javascript:;" onclick="imageUploadifyModuleSwitch();">点击这里</a>用<span id="uploadDescModuleSpan">普通</span>模式上传 ；<br />
2、网上图片URL地址可直接复制到上面发布框来发布。
</em> <span id="uploading"></span> <div class="viewImgDiv" id="viewImgDiv"></div> </div> <div id="uploadifyQueueDiv" style="display:none;"></div> </div> </div> </div> <?php if(($this->Config['attach_enable'] && $this->Module!='qun') || ($this->Config['qun_attach_enable'] && $this->Module=='qun')) $allow_attach = 1; else $allow_attach = 0  ?> <?php $attach_uploadify_topic = array(); ?> <?php $attach_uploadify_from = 'topic_publish'; ?> <?php $attach_uploadify_only_js = 1; ?> <?php $attach_num = min(max(1,(int)$this->Config['attach_files_limit']),5); ?> <?php $attach_size = min(max(1,(int)$this->Config['attach_size_limit']),5120); ?> <?php $attach_size = $attach_size >= 1024 ? round(($attach_size/1024),1).'M' : $attach_size.'KB'; ?> <?php if($allow_attach) { ?> <?php $attach_uploadify_id=$topic_textarea_id.$attach_uploadify_type.($attach_uploadify_topic['tid']>0?"_".$attach_uploadify_topic['tid']:""); ?> <?php $attach_img_siz=$attach_img_siz?$attach_img_siz:32; ?> <?php $attach_fz_siz=min(max(1,(int)$this->Config['attach_size_limit']),5120)*1024; ?> <?php $topic_textarea_id=$topic_textarea_id?$topic_textarea_id:'i_already'.$h_key; ?> <?php $topic_textarea_empty_val=isset($topic_textarea_empty_val)?$topic_textarea_empty_val:'分享文件'; ?> <?php $attach_uploadify_queue_size_limit=min(max(1,(int)$this->Config['attach_files_limit']),5); ?> <?php $attach_item=isset($this->item)?$this->item:''; ?> <?php $attach_itemid=isset($this->item_id)?$this->item_id:0; ?> <success></success> <script type="text/javascript">
var __ATTACH_IDS__ = {};
$(document).ready(function(){			
var upfilename = '';
$('#publisher_file_attach<?php echo $attach_uploadify_id; ?>').uploadify({
'uploader'  : '<?php echo $this->Config['site_url']; ?>/images/uploadify/uploadify.swf?id=<?php echo $attach_uploadify_id; ?>&random=' + Math.random(),
'script'    : '<?php echo urlencode($this->Config['site_url'] . "/ajax.php?mod=uploadattach&code=attach&aitem=$attach_item&aitemid=$attach_itemid"); ?>',
'cancelImg' : '<?php echo $this->Config['site_url']; ?>/images/uploadify/cancel.png',
'buttonImg'	: '<?php echo $this->Config['site_url']; ?>/images/uploadify/addatta.gif',
'width'		: 111,
'height'	: 17,
'multi'		: true,
'auto'      : true,
'sizeLimit' : <?php echo $attach_fz_siz; ?>,
'fileExt'	: '*.rar;*.zip;*.txt;*.doc;*.xls;*.pdf;*.ppt;*.docx;*.xlsx;*.pptx',
'fileDesc'	: '*.rar;*.zip;*.txt;*.doc;*.xls;*.pdf;*.ppt;*.docx;*.xlsx;*.pptx',
'queueID'	: 'uploadifyQueueDivAttach<?php echo $attach_uploadify_id; ?>',
'wmode'		: 'transparent',
'fileDataName'	 : 'topic',
'queueSizeLimit' : <?php echo $attach_uploadify_queue_size_limit; ?>,
'simUploadLimit' : 1,
'scriptData'	 : {
<?php if($attach_uploadify_topic_uid) { ?>
'topic_uid'  : '<?php echo $attach_uploadify_topic_uid; ?>',
<?php } ?>
'cookie_auth': '<?php echo urlencode(jsg_getcookie("auth")); ?>'
},
'onSelect'		 : function(event, ID, fileObj) {
},
'onSelectOnce'	 : function (event, data) {
attachUploadifySelectOnce<?php echo $attach_uploadify_id; ?>();			    	
},
'onProgress'     : function(event, ID, fileObj, data) {
return false;
},
'onComplete'	 : function(event, ID, fileObj, response, data) {
eval('var r = ' + response + ';');
if (r.done) {					
var rv = r.retval;
if ( rv.id > 0 && rv.src.length > 0 ) {
attachUploadifyComplete<?php echo $attach_uploadify_id; ?>(rv.id, rv.src, fileObj.name);
upfilename = fileObj.name;
}
}
else
{
if(r.msg)
{
if(r.msg=='forbidden'){
MessageBox('warning','您没有上传文件的权限，无法继续操作！');
}else{
MessageBox('warning', '上传失败，文件过大或过多或格式错误！');
}
}
}
},
'onError'        : function (event, ID, fileObj, errorObj) {
alert(errorObj.type + ' Error: ' + errorObj.info);
},
'onAllComplete'	 : function(event, data) {
attachUploadifyAllComplete<?php echo $attach_uploadify_id; ?>(upfilename);
}
});
$("#viewAttachDiv<?php echo $attach_uploadify_id; ?> img").each(function() {
if($(this).width() > $(this).parent().width()) {
$(this).width("100%");
}
});
});
//删除一个文件
function attachUploadifyDelete<?php echo $attach_uploadify_id; ?>(idval)
{
var idval = ('undefined'==typeof(idval) ? 0 : idval);
if(idval > 0) 
{
$.post
(
'ajax.php?mod=uploadattach&code=delete_attach',
{
'id' : idval
},
function (r) 
{				
if(r.done)
{
$('#uploadAttachSpan_' + idval).remove();
if( ($.trim(($('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').html()))).length < 1 )
{
$('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').css('display', 'none');
$('#insertAttachDiv<?php echo $attach_uploadify_id; ?>').css('display', 'block');
}
if( 'undefined' != typeof(__ATTACH_IDS__[idval]) )
{
__ATTACH_IDS__[idval] = 0;
}
}
else
{
if(r.msg)
{
MessageBox('warning', r.msg);
}
}
},
'json'
);
}
}
function attachUploadifySelectOnce<?php echo $attach_uploadify_id; ?>()
{
$('#uploadingAttach<?php echo $attach_uploadify_id; ?>').html("<img src='images/loading.gif'/>&nbsp;上传中，请稍候……");
}
function attachUploadifyComplete<?php echo $attach_uploadify_id; ?>(idval, srcval, nameval)
{
var attachIdsCount = 0;
$.each( __ATTACH_IDS__, function( k, v ) { if( v > 0 ) { attachIdsCount += 1; } } );
if( attachIdsCount >= <?php echo $attach_uploadify_queue_size_limit; ?> )
{
MessageBox('warning', '本次文件数量超过限制了');
return false;
}
var idval = ('undefined' == typeof(idval) ? 0 : idval);
var srcval = ('undefined' == typeof(srcval) ? 0 : srcval);
var nameval = ('undefined' == typeof(nameval) ? '' : nameval);
<?php if('topic_publish'==$attach_uploadify_from) { ?>
$('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').prepend('<li id="uploadAttachSpan_' + idval + '" class="menu_ps vv_2"><img src="' + srcval + '" class="uploadAttachSpan_img_type"/> <p class="uploadAttachSpan_doc_type"><i>' + nameval + '</i></p><p>（<a title="删除文件" onclick="attachUploadifyDelete<?php echo $attach_uploadify_id; ?>(' + idval + ');return false;" href="javascript:;">删</a>）需<input title="填写用户下载该附件所需贡献给你的积分" size="1" type="text" onblur="set_attach_score(this.value,' + idval + ');return false;">积分 </p></li>');<?php } elseif('topic_longtext_info_ajax'==$attach_uploadify_from) { ?>$('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').append('<span id="uploadAttachSpan_' + idval + '"><img src="' + srcval + '" width="<?php echo $attach_img_siz; ?>" alt="点击文件插入到文中" onclick="longtext_info_img_insert(\'' + srcval + '\');" />（<a href="javascript:void(0);" onclick="attachUploadifyDelete<?php echo $attach_uploadify_id; ?>(' + idval + '); return false;" title="删除文件">删</a>）需<input title="填写用户下载该附件所需贡献给你的积分" size="1" type="text" onblur="set_attach_score(this.value,' + idval + ');return false;">积分</span>');
<?php } else { ?>$('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').append('<span id="uploadAttachSpan_' + idval + '"><img src="' + srcval + '" width="<?php echo $attach_img_siz; ?>" />（<a href="javascript:void(0);" onclick="attachUploadifyDelete<?php echo $attach_uploadify_id; ?>(' + idval + '); return false;" title="删除文件">删</a>）需<input title="填写用户下载该附件所需贡献给你的积分" size="1" type="text" onblur="set_attach_score(this.value,' + idval + ');return false;">积分</span>');
<?php } ?>
$('#normalAttachUploadFile<?php echo $attach_uploadify_id; ?>').val('');
__ATTACH_IDS__[idval] = idval;
}
function attachUploadifyAllComplete<?php echo $attach_uploadify_id; ?>(nameval)
{
var nameval = ('undefined' == typeof(nameval) ? '' : nameval);
$('#uploadingAttach<?php echo $attach_uploadify_id; ?>').html('');			    	
$('#viewAttachDiv<?php echo $attach_uploadify_id; ?>').css('display', 'block');
//$('#insertAttachDiv<?php echo $attach_uploadify_id; ?>').css('display', 'none');
if( $.trim(($('#<?php echo $topic_textarea_id; ?>').val())).length < 1 ) {
$('#<?php echo $topic_textarea_id; ?>').val('<?php echo $topic_textarea_empty_val; ?>' + ':' + nameval);	
}
$('#<?php echo $topic_textarea_id; ?>').focus();
}
function normalAttachUploadFormSubmit<?php echo $attach_uploadify_id; ?>()
{
var fileVal = $('#normalAttachUploadFile<?php echo $attach_uploadify_id; ?>').val();
if(($.trim(fileVal)).length < 1)
{
MessageBox('warning', '请上传正确格式的附件文件');
return false;
}
else
{
if(!(/\.(zip|rar|txt|doc|xls|pdf)$/i.test(fileVal)))
{
MessageBox('warning', '请选择一个正确格式的附件文件');
return false;
}
else
{
attachUploadifySelectOnce<?php echo $attach_uploadify_id; ?>();
return true;
}
}
}
function attachUploadifyModuleSwitch<?php echo $attach_uploadify_id; ?>()
{
if('none' == $('#normalAttachUploadDiv<?php echo $attach_uploadify_id; ?>').css('display'))
{
$('#uploadDescModuleSpanAttach<?php echo $attach_uploadify_id; ?>').html('快速');
$('#swfUploadDivAttach<?php echo $attach_uploadify_id; ?>').css('display', 'none');
$('#normalAttachUploadDiv<?php echo $attach_uploadify_id; ?>').css('display', 'block');
}
else
{
$('#uploadDescModuleSpanAttach<?php echo $attach_uploadify_id; ?>').html('普通');
$('#normalAttachUploadDiv<?php echo $attach_uploadify_id; ?>').css('display', 'none');
$('#swfUploadDivAttach<?php echo $attach_uploadify_id; ?>').css('display', 'block');
}
}
</script> <?php if(!$attach_uploadify_only_js) { ?> <div id="insertAttachDiv<?php echo $attach_uploadify_id; ?>" class="insertAttachDiv" style="border-bottom:1px solid #ddd;"> <i class="insertAttachDiv_up" onclick="$(this).parent().hide()"><img src="templates/default/images/imgdel.gif" title="关闭" /></i> <div id="swfUploadDivAttach<?php echo $attach_uploadify_id; ?>"><input type="file" id="publisher_file_attach<?php echo $attach_uploadify_id; ?>" name="publisher_file<?php echo $attach_uploadify_id; ?>" style="display:none;" />（按ctrl键可一次选多个文件）</div> <iframe id="attachUploadifyIframe<?php echo $attach_uploadify_id; ?>" name="attachUploadifyIframe<?php echo $attach_uploadify_id; ?>" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank" style="display:none;"></iframe> <div id="normalAttachUploadDiv<?php echo $attach_uploadify_id; ?>" style="display:none;"> <form id="normalAttachUploadForm<?php echo $attach_uploadify_id; ?>" method="post"  action="ajax.php?mod=uploadattach&code=attach&type=normal&aitem=<?php echo $attach_item; ?>&aitemid=<?php echo $attach_itemid; ?>" enctype="multipart/form-data" target="attachUploadifyIframe<?php echo $attach_uploadify_id; ?>" onsubmit="return normalAttachUploadFormSubmit<?php echo $attach_uploadify_id; ?>()"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input type="hidden" name="attach_uploadify_id" value="<?php echo $attach_uploadify_id; ?>" /> <input type="file" id="normalAttachUploadFile<?php echo $attach_uploadify_id; ?>" name="topic" /> <input type="submit" value="上传" class="tul" /> </form> </div> <span id="uploadingAttach<?php echo $attach_uploadify_id; ?>"></span> <div id="uploadDescDivAttach<?php echo $attach_uploadify_id; ?>"> <span class="fontRed">*</span> 如果您不能上传文件，可以<a href="javascript:;" onclick="attachUploadifyModuleSwitch<?php echo $attach_uploadify_id; ?>();">点击这里</a>尝试<span id="uploadDescModuleSpanAttach<?php echo $attach_uploadify_id; ?>">普通</span>模式上传
<?php if('topic_longtext_info_ajax'==$attach_uploadify_from) { ?> <br /><span class="fontRed">*</span> 文件上传成功后，可以点击文件将文件插入到您想要的位置
<?php } ?> </div> <div id="uploadifyQueueDivAttach<?php echo $attach_uploadify_id; ?>" style="display:none;"></div> <div id="viewAttachDiv<?php echo $attach_uploadify_id; ?>" class="viewAttachDiv"> <?php if((!$attach_uploadify_new || $attach_uploadify_modify) && $attach_uploadify_topic['attachid']) { ?> <?php if(is_array($attach_uploadify_topic['attach_list'])) { foreach($attach_uploadify_topic['attach_list'] as $ik => $iv) { ?> <script type="text/javascript"> __ATTACH_IDS__[<?php echo $ik; ?>] = <?php echo $ik; ?>; </script> <span id="uploadAttachSpan_<?php echo $ik; ?>"> <img src="<?php echo $iv['attach_img']; ?>" width="<?php echo $attach_img_siz; ?>" />（<a href="javascript:void(0);" onclick="attachUploadifyDelete<?php echo $attach_uploadify_id; ?>('<?php echo $ik; ?>'); return false;" title="删除文件">删</a>）下载附件需消耗<input title="填写用户下载该附件所需贡献给你的积分" size="1" type="text" value="<?php echo $iv['attach_score']; ?>" onblur="set_attach_score(this.value,<?php echo $iv['id']; ?>);return false;">积分
</span> <?php } } ?> <?php } ?> </div> </div> <?php } ?> <?php } ?> <div class="menu"> <div class="menu_sp"><b class="menu_spb_c">视频</b> <div class="menu_spb" id="upload_ajax_video"> <div class="menu_tb"><span style="float:left; padding-left:5px;">支持如下视频的站内播放</span><div class="menu_spb_c1"></div></div> <p class="menu_p"><a href="http://video.sina.com.cn/" rel="nofollow" target="_blank">新浪</a>、<a href="http://www.youku.com/" rel="nofollow" target="_blank">优酷</a>、<a href="http://v.blog.sohu.com/" rel="nofollow" target="_blank">搜狐</a>、<a href="http://www.ku6.com/" rel="nofollow" target="_blank">酷6</a>、<a href="http://www.tudou.com/" rel="nofollow" target="_blank">土豆</a><br>请复制视频播放页网站地址即可</p> <div id="upload_video_list" class="menu_p" style="display:none;"> <span id="return_ajax_video_title"></span> <span><img id="video_img" width="130" /></span> <p> <input id="videoid" type="hidden" name="video_id" /> <span><a href="" onclick="DelVideo('videoid','video_ajax'); return false;" title="删除视频">删除视频</a></span> </p> </div> <div id="add_video" class="menu_p" style=" margin-bottom:6px; padding-top:0"> <iframe id="upload_video_frame" name="upload_video_frame" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank"></iframe> <form action="ajax.php?mod=topic&code=dovideo" method="post"  enctype="multipart/form-data" name="upload_video" id="upload_video" target="upload_video_frame"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input name="url" type="text" id="url" class="sc_r_t_a" style=" width:220px; display:inline;"/> <input type="submit" name="Submit" value="提交" class="c_b1" /> </form> </div> </div></div> </div> <div class="menu"> <div class="menu_m"> <b class="menu_m_c">音乐</b> <div class="menu_music"> <div class="menu_tb"> <span style="float:left; padding-left:10px;">请在下面输入歌曲名或歌手名字搜索</span> <sub class="menu_music_c1"></sub> </div> <div id="add_music" class="menu_m_s" style=" margin-bottom:6px; padding:15px 10px 0; float:left;"> <form action="javascript:void(0);" method="post"  enctype="multipart/form-data" name="upload_music" id="upload_music"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input name="url" type="text" id="music_name" class="sc_r_t_a" style=" width:220px;"> <input type="submit" name="Submit" value="搜索" class="c_b1" onclick="music_serach();"> </form> </div> <p class="menu_p" style="padding:0 10px;">音乐后缀的url请直接粘贴到上面的发布框中</p> <div id="music_list" class="menu_m_l"></div> </div> </div> </div> <?php if($allow_attach) { ?> <div class="menu"> <div class="menu_fj" ><b class="menu_fjb_c">附件</b> <div class="menu_fjb"> <div class="menu_pi insertImgDiv" id="insertAttachDiv"> <div id="swfUploadDivAttach"><input type="file" id="publisher_file_attach" name="publisher_file" style="display:none;" />（按ctrl键可一次选多个文件）</div> <iframe id="attachUploadifyIframe" name="attachUploadifyIframe" width="0" height="0" marginwidth="0" frameborder="0" src="about:blank" style="display:none;"></iframe> <div id="normalAttachUploadDiv" style="display:none;"> <form id="normalAttachUploadForm" method="post"  action="ajax.php?mod=uploadattach&code=attach&type=normal&aitem=<?php echo $attach_item; ?>&aitemid=<?php echo $attach_itemid; ?>" enctype="multipart/form-data" target="attachUploadifyIframe" onsubmit="return normalAttachUploadFormSubmit()"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <input type="file" id="normalAttachUploadFile" name="topic" /> <input type="submit" value="上传" class="tul" /> </form> </div> <i class="menu_fjb_c1"><img src="templates/default/images/imgdel.gif" title="关闭" /></i> <em>
1、如您不能上传文件，请<a href="javascript:;" onclick="attachUploadifyModuleSwitch();">点击这里</a>用<span id="uploadDescModuleSpanAttach">普通</span>模式上传.<br />
2、一次最多可上传<?php echo $attach_num; ?>个文件，单个文件大小不超过<?php echo $attach_size; ?>。
</em> <span id="uploadingAttach"></span> <div class="viewImgDiv" id="viewAttachDiv"></div> </div> <div id="uploadifyQueueDivAttach" style="display:none;"></div> </div> </div> </div> <?php } ?> <div class="menu" > <div class="menu_ht" id="button_<?php echo MEMBER_ID; ?>"><span onclick="get_tag_choose(<?php echo MEMBER_ID; ?>,'my_tag','<?php echo $h_key; ?>');return false;" class="menu_htb_c">话题</span> <div class="menu_htb"><div id="<?php echo $h_key; ?>tag_<?php echo MEMBER_ID; ?>"></div></div> </div> </div> <?php if($this->Config['vote_open'] && !$set_vote_closed && !($this->Get['mod'] == 'talk' || $this->Get['mod'] == 'live' || $type == 'answer' || $type == 'ask')) { ?> <div class="menu"> <div class="menu_vs"><b class="menu_vsb_c">投票</b> <div class="menu_vsb"> <div class="menu_vsbox"> <p class="stitle"> <b id="vote_content1" class="vhover" onclick="setTab('vote_content',1,3)">创建新的投票</b> <b id="vote_content2" onclick="setTab('vote_content',2,3);getMyVoteList(1);">我发起的</b> <b id="vote_content3" onclick="setTab('vote_content',3,3);getMyJoinList(1);">我参与的</b> <sub class="menu_vsb_c1"></sub> </p> <div class="vcontent" id="con_vote_content_1"> <p>正在加载...</p> </div> <div class="vcontent vote_conLi" id="con_vote_content_2" style="display:none;"> <p>正在加载...</p> </div> <div class="vcontent vote_conLi" id="con_vote_content_3" style="display:none;"> <p>正在加载...</p> </div> </div> </div> </div> </div> <?php } ?> <?php if($this->Config['event_open'] == 1 && !$set_event_closed && !($this->Get['mod'] == 'talk' || $this->Get['mod'] == 'live' || $type == 'answer' || $type == 'ask')) { ?> <div class="menu"> <div class="menu_hd"><b class="menu_hdb_c">活动</b> <div class="menu_hdb"> <div class="menu_hdbox"> <p class="stitle"> <b id="event_content1" class="vhover" onclick="setTab1('event_content',1,3)">发起新的活动</b> <b id="event_content2" onclick="setTab1('event_content',2,3);getMyEventList(1);">我发起的</b> <b id="event_content3" onclick="setTab1('event_content',3,3);getJoinEventList(1);">我参与的</b> <sub class="menu_hdb_c1"></sub> </p> <div class="vcontent" id="con_event_content_1"> <p>正在加载...</p> </div> <div class="vcontent vote_conLi" id="con_event_content_2" style="display:none;"> <p>正在加载...</p> </div> <div class="vcontent vote_conLi" id="con_event_content_3" style="display:none;"> <p>正在加载...</p> </div> </div> </div> </div> </div> <?php } ?> <?php if($this->Config['qun_setting']['qun_open'] && !$set_qun_closed && !($this->Get['mod'] == 'talk' || $this->Get['mod'] == 'live' || $type == 'answer' || $type == 'ask')) { ?> <div class="menu"> <div class="menu_wq"> <b class="menu_wqb_c">微群</b> <div class="menu_wqb"> <div class="menu_wqbox" style="width:210px;"> <div class="menu_tb" style="width:210px;"> <span style="float:left; padding-left:5px;">选择你要发布到的群</span> <sub class="menu_wqb_c1"></sub> </div> <div class="wcontent" id="wcontent_wp"></div> </div> </div> </div> </div> <?php } ?> <?php if($this->Config['fenlei_open'] == 1 && !$set_fenlei_closed) { ?> <div class="menu"> <div class="menu_x"> <b class="menu_xb_c">分类</b> <div class="menu_xb"> <div class="menu_xbox"> <p class="stitle"> <b id="vote2_content1" class="vhover2" onclick="setTab2('vote2_content',1,2)">新的分类</b> <b id="vote2_content2" onclick="setTab2('vote2_content',2,2);getMyFenleiList(1);">我发布的分类</b> <sub class="menu_xb_c1"></sub> </p> <div class="vcontent" id="con2_vote2_content_1"> <p>正在加载...</p> </div> <div class="vcontent vote_conLi" id="con2_vote2_content_2" style="display:none;"> <p>正在加载...</p> </div> </div> </div> </div> </div> <?php } ?> <?php echo $this->hookall_temp['global_publish_extra1']; ?> <?php } else { ?><div class="box_3ajax"> <?php } ?> </div> <div class="box_4"> <?php if ($this->Get['mod'] == 'tag') $type = 'tagview' ;elseif ($this->Get['mod'] == 'member') $type = 'tohome';elseif ($this->Get['mod'] == 'vote') $type='vote';elseif ($this->Get['mod'] == 'live') $type='live';elseif ($this->Get['mod'] == 'talk') $type='talk';elseif ($this->Get['mod'] == 'fenlei') $type='fenlei';elseif ($this->Get['mod'] == 'event') $type='event';elseif ($this->Get['mod'] == 'reward') $type='reward'; else $type = $params['code']; ?> <?php $type = $type ? $type : $this->Code; ?> <input type="button" class="indexBtn" id="publishSubmit<?php echo $h_key; ?>" title="按Ctrl+Enter快捷发布"/> <?php if(in_array($this->Get['mod'], array('qun','live','talk','event','vote','fenlei','reward')) || $this->Get['type'] == 'ask') { ?> <?php $topic_type_value = $this->Get['type'] == 'ask' ? $this->Get['item'] : $this->Get['mod']; ?> <div class="box_4_open"> <b class="box_4_open_span" style="padding:0;"> <label><input id="chk_toweibo<?php echo $h_key; ?>" type="checkbox" checked="checked" onclick="selectAppTopicType(this.id, {toid:'topic_type<?php echo $h_key; ?>', defTopicType:'<?php echo $topic_type_value; ?>'})">同步发作微博</label></b> </div> <?php } else { ?><div class="box_4_open" id="weibo_syn_wp"> <b class="box_4_open_span">同步发到</b> <div class="box_4_open_Box"> <sub class="box_4_open_span_c1"></sub> <?php if($this->Config['sina_enable'] && sina_weibo_init()) { ?> <p> <?php echo sina_weibo_syn(); ?> </p> <?php } ?> <?php if($this->Config['qqwb_enable'] && qqwb_init()) { ?> <p> <?php echo qqwb_syn(); ?> </p> <?php } ?> <?php if($this->Config['kaixin_enable'] && kaixin_init()) { ?> <p> <?php echo kaixin_syn_html(); ?> </p> <?php } ?> <?php if($this->Config['renren_enable'] && renren_init()) { ?> <p> <?php echo renren_syn_html(); ?> </p> <?php } ?> </div> </div> <?php if($this->Channel_enable) { ?> <script>
$(document).ready(function(){
$("#p_channel,#t_pb").bind('mouseover', function(){$('#p_channel').show();$('#t_pb').addClass('hover');});
$("#p_channel,#t_pb").bind('mouseout', function(){$('#p_channel').hide();$('#t_pb').removeClass('hover');});
});
function c_hide(){$('#p_channel').hide();$('#t_pb').removeClass('hover');}
function c_cut(){$('#t_channel').html('');$('#mapp_item<?php echo $h_key; ?>').val('<?php echo $this->item; ?>');$('#mapp_item_id<?php echo $h_key; ?>').val('<?php echo $this->item_id; ?>');}
function c_int(n,s){$('#p_channel').hide();$('#t_pb').removeClass('hover');$('#t_channel').html(s+'<em onclick="c_cut();">×</em>');$('#mapp_item<?php echo $h_key; ?>').val('channel');$('#mapp_item_id<?php echo $h_key; ?>').val(n);}
</script> <div class="box_4_channel"> <span class="select" id="t_pb">发布到频道</span><span class="channel" id="t_channel"></span> <div class="channels" id="p_channel"> <span class="close" onclick="c_hide();">×</span> <?php if($this->Channels) { ?> <?php if(is_array($this->Channels)) { foreach($this->Channels as $val) { ?> <dl> <dt><a onclick="c_int(<?php echo $val['ch_id']; ?>,'<?php echo $val['ch_name']; ?>');"><?php echo $val['ch_name']; ?></a></dt> <dd> <?php if($val['child']) { ?> <?php if(is_array($val['child'])) { foreach($val['child'] as $v) { ?> <a onclick="c_int(<?php echo $v['ch_id']; ?>,'<?php echo $v['ch_name']; ?>');"><?php echo $v['ch_name']; ?></a> <?php } } ?> <?php } else { ?><a href="javascript:void(0);">&nbsp;</a> <?php } ?> </dd> </dl> <?php } } ?> <?php } else { ?><p>没有频道可供发布</p> <?php } ?> </div> </div> <?php } ?> <?php } ?> </div> </div> </div> <script type="text/javascript">		
$("#i_already<?php echo $h_key; ?>").bind('focus', function(){
$('#tigBox_2').css('visibility', 'visible');
var i=0;
setTimeout(function(){i+=1; $('#tigBox_2').css('visibility', 'hidden'); },5000);
});
$("#publishSubmit<?php echo $h_key; ?>").bind('click',function() {
publishSubmit('i_already<?php echo $h_key; ?>','totid<?php echo $h_key; ?>','<?php echo $type; ?>','topic_type<?php echo $h_key; ?>','','',$('#mapp_item<?php echo $h_key; ?>').val(),$('#mapp_item_id<?php echo $h_key; ?>').val(),$('#xiami_id').val(),$('#touid<?php echo $h_key; ?>').val());
return false;
});
$(document).ready(function(){
initAiInput('i_already<?php echo $h_key; ?>');
checkWord('<?php echo $this->Config['topic_input_length']; ?>','i_already<?php echo $h_key; ?>','wordCheck<?php echo $h_key; ?>');
});
/*
$("#i_already").bind('keydown',function(event) {
event = event || window.event;
if (event.keyCode == 13 && event.ctrlKey) {
$("#publishSubmit").click();
};
});*/
</script> </div> <div id="listTopicArea"><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> <?php $pagehtml_not = $pagehtml_not ? $pagehtml_not : false; ?> <div class="Listmain"> <?php if($topic_list) { ?> <?php if('favoritemy'==$this->Code) { ?> <?php if(is_array($topic_list)) { foreach($topic_list as $val) { ?> <?php $counts++; ?> <script type="text/javascript">
$(document).ready(function(){
var objStr = "#topic_lists_<?php echo $val['tid']; ?>";
$(objStr).mouseover(function(){$(objStr + " i").show();});
$(objStr).mouseout(function(){$(objStr + " i").hide();});
});
</script> <div class="feedCell" id="topic_list_<?php echo $val['tid']; ?>"><div class="avatar"> <a href="index.php?mod=<?php echo $favorite_members[$val['fuid']]['username']; ?>"> <img onerror="javascript:faceError(this);" src="<?php echo $favorite_members[$val['fuid']]['face']; ?>" /> </a> </div> <div class="Contant"> <div id="topic_lists_<?php echo $val['tid']; ?>" style="_overflow:hidden;"> <div class="oriTxt"> <p> <a title="<?php echo $val['username']; ?>" href="index.php?mod=<?php echo $favorite_members[$val['fuid']]['username']; ?>"> <?php echo $favorite_members[$val['fuid']]['nickname']; ?> </a> <?php echo $favorite_members[$val['fuid']]['validate_html']; ?>：
<span style="color:#666; font-size:12px;">收藏于<?php echo $val['favorite_time']; ?></span> </p> <span> <a href="index.php?mod=<?php echo $val['username']; ?>"> <?php echo $val['nickname']; ?> </a><?php echo $val['validate_html']; ?>:<?php echo $val['content']; ?> </span> <?php if($val['attachid'] && $val['attach_list']) { ?> <?php $val['attach_key']=$val['tid'].'_'.mt_rand(); ?> <ul class="attachList" id="attach_area_<?php echo $val['attach_key']; ?>"> <?php if(is_array($val['attach_list'])) { foreach($val['attach_list'] as $iv) { ?> <li><img src="<?php echo $iv['attach_img']; ?>" class="attachList_img" /> <div class="attachList_att"> <p class="attachList_att_name"><b><?php echo $iv['attach_name']; ?></b>
&nbsp;（<?php echo $iv['attach_size']; ?>）</p> <p class="attachList_att_doc"><a href="javascript:void(0);"
onclick="downattach(<?php echo $iv['id']; ?>);">点此下载</a>（需<?php echo $iv['attach_score']; ?>积分，已下载<?php echo $iv['attach_down']; ?>次）</p> </div> </li> <?php } } ?> </ul> <?php } ?> <?php if($val['imageid'] && $val['image_list']) { ?> <?php $val['image_key']=$val['tid'].'_'.mt_rand(); ?> <ul class="imgList" id="image_area_<?php echo $val['image_key']; ?>"> <?php if(is_array($val['image_list'])) { foreach($val['image_list'] as $iv) { ?> <?php $ivw=min(460, $iv['image_width']); ?> <li><a href="<?php echo $iv['image_original']; ?>" class="artZoomAll"
rel="<?php echo $iv['image_small']; ?>" rev="<?php echo $val['image_key']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_small']; ?>" /></a> <div class="artZoomBox" style="display: none;"> <div class="tool"><a title="向左转" href="#" class="imgLeft">向左转</a><a 
title="向右转" href="#" class="imgRight">向右转</a><a title="查看原图"
href="<?php echo $iv['image_original']; ?>" class="viewImg">查看原图</a></div> <a class="maxImgLinkAll" href="<?php echo $iv['image_original']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_original']; ?>" maxWidth="460" width="<?php echo $ivw; ?>" class="maxImg"></a></div> </li> <?php } } ?> </ul> <?php } ?> <?php if($val['is_vote'] > 0) { ?> <?php $val['vote_key']=$val['tid'].'_'.$val['random'] ?> <ul class="imgList" id="vote_detail_<?php echo $val['vote_key']; ?>"> <li><a href="javascript:;"
onclick="getVoteDetailWidgets('<?php echo $val['vote_key']; ?>', <?php echo $val['is_vote']; ?>);"><img
src='./images/vote_pic_01.gif' /></a></li> </ul> <div id="vote_area_<?php echo $val['vote_key']; ?>" style="display: none;"> <div class="blogTxt"> <div class="top"></div> <div class="mid" id="vote_content_<?php echo $val['vote_key']; ?>"></div> <div class="bottom"></div> </div> </div> <?php } ?> <?php if($val['videoid'] and $this->Config['video_status']) { ?> <div class="feedUservideo"><a
onClick="javascript:showFlash('<?php echo $val['VideoHosts']; ?>', '<?php echo $val['VideoLink']; ?>', this, '<?php echo $val['VideoID']; ?>','<?php echo $val['VideoUrl']; ?>');"> <div id="play_<?php echo $val['VideoID']; ?>" class="vP"><img
src="images/feedvideoplay.gif" /></div> <img src="<?php echo $val['VideoImg']; ?>" style="border: none" /> </a></div> <?php } ?> <?php if($val['musicid']) { ?> <?php if($val['xiami_id']) { ?> <div class="feedUserImg"><embed width="257" height="33"
wmode="transparent" type="application/x-shockwave-flash"
src="http://www.xiami.com/widget/0_<?php echo $val['xiami_id']; ?>/singlePlayer.swf"></embed></div> <?php } else { ?><div class="feedUserImg"> <div id="play_<?php echo $val['MusicID']; ?>"></div> <img src="images/music.gif" title="点击播放音乐"
onClick="javascript:showFlash('music', '<?php echo $val['MusicUrl']; ?>', this, '<?php echo $val['MusicID']; ?>');"
style="cursor: pointer; border: none;" /></div> <?php } ?> <?php } ?><script type="text/javascript"> var __TOPIC_VIEW__ = '<?php echo $topic_view; ?>'; </script> <?php if(($tpid=$val['top_parent_id'])>0 && !in_array($this->Code, array('forward', 'reply'))) { ?> <?php if(('mycomment'==$this->Code || $topic_view) && 'reply'==$val['type'] && $tpid!=($pid=$val['parent_id']) && $parent_list[$pid]) { ?> <p class="feedP">回复{<a
href="index.php?mod=<?php echo $parent_list[$pid]['username']; ?>"><?php echo $parent_list[$pid]['nickname']; ?>：</a><span><?php echo $parent_list[$pid]['content']; ?></span>}</p> <?php } ?> <?php if(!$topic_view) { ?> <?php $pt=$parent_list[$tpid]; ?> <div class="blogTxt"> <div class="top"></div> <div class="mid"><p> <?php if($pt) { ?> <span> <a
href="index.php?mod=<?php echo $pt['username']; ?>"
onmouseover="get_user_choose(<?php echo $pt['uid']; ?>,'_reply_user',<?php echo $val['tid']; ?>);"
onmouseout="clear_user_choose();"> <?php echo $pt['nickname']; ?> </a> <?php echo $pt['validate_html']; ?> :   <span
id="user_<?php echo $val['tid']; ?>_reply_user"></span> </span> <?php $TPT_ = $TPT_ ? $TPT_ : 'TPT_'; ?> <span id="topic_content_<?php echo $TPT_; ?><?php echo $pt['tid']; ?>_short"><?php echo $pt['content']; ?></span> <span
id="topic_content_<?php echo $TPT_; ?><?php echo $pt['tid']; ?>_full"></span> <?php if($pt['longtextid'] > 0) { ?> <span> <a href="javascript:;"
onclick="view_longtext('<?php echo $pt['longtextid']; ?>', '<?php echo $pt['tid']; ?>', this, '<?php echo $TPT_; ?>', '<?php echo $val['tid']; ?>');return false;">查看全文</a> </span> <?php } ?> <?php if($pt['attachid'] && $pt['attach_list']) { ?> <table class="attachst" style="width:440px;"> <?php if(is_array($pt['attach_list'])) { foreach($pt['attach_list'] as $iv) { ?> <tr> <td class="attachst_img"><img src="<?php echo $iv['attach_img']; ?>" /></td> <td class="attachst_att"> <p class="attachList_att_name"><b><?php echo $iv['attach_name']; ?></b>&nbsp;（<?php echo $iv['attach_size']; ?>）</p> <p class="attachList_att_doc"><a href="javascript:void(0);"
onclick="downattach(<?php echo $iv['id']; ?>);">点此下载</a>（需<?php echo $iv['attach_score']; ?>积分，已下载<?php echo $iv['attach_down']; ?>次）</p> </td> </tr> <?php } } ?> </table> <?php } ?> <?php if($pt['imageid'] && $pt['image_list']) { ?> <?php $pt['image_key']=$pt['tid'].'_'.mt_rand(); ?> <ul class="imgList" id="image_area_<?php echo $pt['image_key']; ?>"> <?php if(is_array($pt['image_list'])) { foreach($pt['image_list'] as $iv) { ?> <?php $ivw=min(440, $iv['image_width']); ?> <li><a href="<?php echo $iv['image_original']; ?>" class="artZoomAll"
rel="<?php echo $iv['image_small']; ?>" rev="<?php echo $pt['image_key']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_small']; ?>" /></a> <div class="artZoomBox" style="display:none;"> <div class="tool"><a title="向左转" href="#" class="imgLeft">向左转</a><a 
title="向右转" href="#" class="imgRight">向右转</a><a title="查看原图"
href="<?php echo $iv['image_original']; ?>" class="viewImg">查看原图</a></div> <a class="maxImgLinkAll" href="<?php echo $iv['image_original']; ?>"> <img
src="./images/grey.gif" data-original="<?php echo $iv['image_original']; ?>" maxWidth="440" width="<?php echo $ivw; ?>" class="maxImg"></a></div> </li> <?php } } ?> </ul> <?php } ?> <?php if($pt['is_vote'] > 0) { ?> <?php $__vote_key = $pt['tid'].'_'.$pt['random'] ?> <ul class="imgList" id="vote_detail_<?php echo $__vote_key; ?>"> <li><a href="javascript:;" onclick="getVoteDetailWidgets('<?php echo $__vote_key; ?>', <?php echo $pt['is_vote']; ?>);"><img src='./images/vote_pic_01.gif' /></a></li> </ul> <div id="vote_area_<?php echo $__vote_key; ?>" style="display: none;"> <div class="vote_zf_box" id="vote_content_<?php echo $__vote_key; ?>"></div> </div> <?php } ?> <?php if($pt['videoid'] and $this->Config['video_status']) { ?> <div class="feedUservideo"> <a onClick="javascript:showFlash('<?php echo $pt['VideoHosts']; ?>', '<?php echo $pt['VideoLink']; ?>', this, '<?php echo $pt['VideoID']; ?>','<?php echo $pt['VideoUrl']; ?>');"> <div id="play_<?php echo $pt['VideoID']; ?>" class="vP"><img src="images/feedvideoplay.gif" /></div> <img src="<?php echo $pt['VideoImg']; ?>" style="border: none" /> </a></div> <?php } ?> <?php if($pt['musicid']) { ?> <?php if($pt['xiami_id']) { ?> <div class="feedUserImg"> <embed width="257" height="33" wmode="transparent" type="application/x-shockwave-flash" src="http://www.xiami.com/widget/0_<?php echo $pt['xiami_id']; ?>/singlePlayer.swf"></embed></div> <?php } else { ?><div class="feedUserImg"> <div id="play_<?php echo $pt['MusicID']; ?>"></div> <img src="images/music.gif" title="点击播放音乐" onClick="javascript:showFlash('music', '<?php echo $pt['MusicUrl']; ?>', this, '<?php echo $pt['MusicID']; ?>');" style="cursor: pointer; border: none;" /></div> <?php } ?> <?php } ?> </p> <div style="float:left; padding:5px 0; margin:0;"> <a href="index.php?mod=topic&code=<?php echo $tpid; ?>" target="_blank">原文评论(<?php echo $pt['replys']; ?>)</a>&nbsp;
<a onclick="get_forward_choose(<?php echo $tpid; ?>);return false;"href="index.php?mod=topic&code=<?php echo $tpid; ?>" target="_blank">转发原文(<?php echo $pt['forwards']; ?>)</a>&nbsp;
<?php echo $pt['from_html']; ?></div> <?php } else { ?> <?php $val['reply_disable']=0; ?> <p><span>原始微博已删除</span></p> <?php } ?> </div> <div class="bottom"></div> </div> <?php } ?> <?php } ?> </div> <div class="from"> <span class="option"></span> <span class="mycome"></span> </div> </div> <div id="reply_area_<?php echo $val['tid']; ?>"></div> <div id="modify_topic_<?php echo $val['tid']; ?>"></div> </div> <div class="mBlog_linedot"></div> </div> <?php } } ?> <?php } else { ?> <?php if($this->Code=='bbs' || $this->Code=='cms') { ?> <script type="text/javascript">
function item_longtext(pidval){
var full_id = 'c_' + pidval + '_full';
var short_id = 'c_' + pidval + '_short';
var link_id = 'linktext_' + pidval;
if (document.getElementById(full_id).style.display == 'none'){
document.getElementById(full_id).style.display = 'block';
document.getElementById(short_id).style.display = 'none';
document.getElementById(link_id).innerHTML = '收起全文';
}else{
document.getElementById(full_id).style.display = 'none';
document.getElementById(short_id).style.display = 'block';
document.getElementById(link_id).innerHTML = '查看全文';
}
}
</script> <?php } ?> <?php if(is_array($topic_list)) { foreach($topic_list as $val) { ?> <?php $counts++; ?> <div class="feedCell" id="topic_list_<?php echo $val['tid']; ?>"> <?php if($this->Config['ad_enable']) { ?> <?php if($counts == 3 && $this->Config['ad']['ad_list']['group_myhome']['middle_center']) { ?> <div class="L_AD"><?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_center']; ?></div> <?php } ?> <?php if($counts == 10 && $this->Config['ad']['ad_list']['group_myhome']['middle_center1']) { ?> <div class="L_AD"><?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_center1']; ?></div> <?php } ?> <?php } ?> <?php if($this->Code=='bbs') { ?> <?php if($val['uid']) { ?> <div class="wb_l_face"> <div class="avatar"> <?php if($this->Code != '') { ?> <?php if(MEMBER_ID != $val['uid']) { ?> <a href="javascript:void(0)" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_user<?php echo $talkanswerid; ?>',<?php echo $val['tid']; ?>);" onmouseout="clear_user_choose();"> <img src="./images/noavatar.gif" data-original="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" class="lazyload" /> </a> <?php } else { ?> <img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /> <?php } ?> <?php } else { ?><a href="index.php?mod=<?php echo $val['username']; ?>"><img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /></a> <?php } ?> <?php if($this->Config['is_topic_user_follow'] && !$val['user_css']) { ?> <?php echo $val['follow_html']; ?> <?php } ?> </div> <?php if($val['user_css']) { ?> <p class="<?php echo $val['user_css']; ?>"><?php echo $val['user_str']; ?></p> <?php } ?> </div> <div id="user_<?php echo $val['tid']; ?>_user<?php echo $talkanswerid; ?>"></div> <div id="Pmsend_to_user_area" style="width:430px;display:none"></div> <div id="alert_follower_menu_<?php echo $val['uid']; ?>" style="display:none"></div> <div id="button_<?php echo $val['uid']; ?>" onclick="get_group_choose(<?php echo $val['uid']; ?>);" style="display:none"></div> <div id="global_select_<?php echo $val['uid']; ?>" class="alertBox" style="display:none"></div> <div id="get_remark_<?php echo $val['uid']; ?>" style="display:none"></div> <?php } else { ?><div class="wb_l_face"><div class="avatar"><img src="<?php echo $val['face']; ?>" title="未在微博登录的论坛用户" onerror="javascript:faceError(this);" /></div></div> <?php } ?> <div class="Contant"> <div style="_overflow:hidden"> <div class="oriTxt"> <p class="utitle"> <?php if($val['uid']) { ?> <span class="un"> <a title="查看<?php echo $val['nickname']; ?>的微博" href="index.php?mod=<?php echo $val['username']; ?>" class="photo_vip_t_name"  onmouseover="get_at_user_choose('<?php echo $val['nickname']; ?>',this)"><?php echo $val['nickname']; ?></a> <?php if($val['validate_html']) { ?> <?php echo $val['validate_html']; ?>&nbsp;
<?php } else { ?> <?php if($this->Config['topic_level_radio']) { ?> <span class="wb_l_level"> <a class="ico_level wbL<?php echo $val['level']; ?>" title="微博等级：<?php echo $val['level']; ?>级" href="index.php?mod=settings&code=exp" target="_blank"><?php echo $val['level']; ?></a> </span> <?php } ?> <?php } ?> <?php if($this->Config['is_signature']) { ?> <?php if(!$_GET['mod_original'] && 'photo'!=$this->Code) { ?> <?php if($val['signature']) { ?> <span class="signature"> <?php if($val['uid'] == MEMBER_ID ||  'admin' == MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="follower_choose(<?php echo $val['uid']; ?>,'<?php echo $val['nickname']; ?>','topic_signature');" title="点击修改个人签名"> <em ectype="user_signature_ajax_<?php echo $val['uid']; ?>">(<?php echo $val['signature']; ?>)</em> </a> <?php } else { ?><em>(<?php echo $val['signature']; ?>)</em> <?php } ?> </span> <?php } ?> <?php } ?> <?php } ?> <?php echo $this->hookall_temp['global_topiclist_extra1']; ?> </span> <?php echo $this->hookall_temp['global_topiclist_extra2']; ?> <?php } else { ?><span class="un"><a title="未在微博登录的论坛用户" href="javascript:;" ><?php echo $val['nickname']; ?></a></span> <?php } ?> <span class="ut"><a href="<?php echo $val['bbsurl']; ?>" target="_blank"><?php echo $val['dateline']; ?></a></span> </p> <?php if($val['title']) { ?> <p><b><?php echo $val['title']; ?></b></p> <?php } ?> <span id="c_<?php echo $val['pid']; ?>_short"><?php echo $val['content']; ?></span> <span id="c_<?php echo $val['pid']; ?>_full" style="display:none;"><?php echo $val['content_full']; ?></span> <?php if($val['longtext']) { ?> <span> <a id="linktext_<?php echo $val['pid']; ?>" href="javascript:;" onclick="item_longtext('<?php echo $val['pid']; ?>');return false;">查看全文</a> </span> <?php } ?> <?php if($val['first'] == 0) { ?> <div class="blogTxt"> <div class="top"></div> <div class="mid"> <?php if($val['tuid']) { ?> <span> <a href="index.php?mod=<?php echo $val['t_username']; ?>" onmouseover="get_user_choose(<?php echo $val['tuid']; ?>,'_reply_user',<?php echo $val['tid']; ?>);" onmouseout="clear_user_choose();"><?php echo $val['t_nickname']; ?></a><?php echo $val['t_validate_html']; ?> : 
<span id="user_<?php echo $val['tid']; ?>_reply_user"></span> </span> <?php } else { ?><span><a title="未在微博登录的论坛用户" href="javascript:;"><?php echo $val['t_nickname']; ?></a>: </span> <?php } ?> <span><?php echo $val['t_title']; ?></span> <div>发布于：<?php echo $val['t_dateline']; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="<?php echo $val['bbsurl']; ?>" target="_blank">回帖(<?php echo $val['replys']; ?>)</a>&nbsp;&nbsp;&nbsp;&nbsp; <a href="<?php echo $val['lasturl']; ?>" target="_blank"><?php echo $val['lastpost']; ?></a></div> </div> <div class="bottom"></div> </div> <div class="from"><div class="option"><ul><li></li></ul></div><div class="mycome">来自<a href="<?php echo $val['forumurl']; ?>" target="_blank">论坛 - <?php echo $val['forumtitle']; ?></a></div></div> <?php } else { ?><div class="from"><div class="option"><ul><li><span> <?php if($val['replys']) { ?> <a id="topic_list_reply_<?php echo $val['tid']; ?>_aid" href="javascript:void(0)" onclick="replyTopic(<?php echo $val['rid']; ?>,'reply_area_<?php echo $val['tid']; ?>','<?php echo $val['replys']; ?>',0,0,{item:'bbs'});return false;">回帖 (<?php echo $val['replys']; ?>)</a></span></li><li><span><a href="<?php echo $val['lasturl']; ?>" target="_blank"><?php echo $val['lastpost']; ?></a> <?php } else { ?>回帖 (<?php echo $val['replys']; ?>)&nbsp;&nbsp;</span></li><li><span><?php echo $val['lastpost']; ?> <?php } ?> </span></li></ul></div><div class="mycome">来自<a href="<?php echo $val['forumurl']; ?>" target="_blank">论坛 - <?php echo $val['forumtitle']; ?></a></div></div> <?php } ?> </div> </div> <div id="reply_area_<?php echo $val['tid']; ?>"></div> </div><?php } elseif($this->Code=='cms') { ?> <?php if($val['uid']) { ?> <div class="wb_l_face"> <div class="avatar"> <?php if($this->Code != '') { ?> <?php if(MEMBER_ID != $val['uid']) { ?> <a href="javascript:void(0)" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_user<?php echo $talkanswerid; ?>',<?php echo $val['tid']; ?>);" onmouseout="clear_user_choose();"> <img src="./images/noavatar.gif" data-original="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" class="lazyload" /> </a> <?php } else { ?> <img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /> <?php } ?> <?php } else { ?><a href="index.php?mod=<?php echo $val['username']; ?>"><img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /></a> <?php } ?> <?php if($this->Config['is_topic_user_follow'] && !$val['user_css']) { ?> <?php echo $val['follow_html']; ?> <?php } ?> </div> <?php if($val['user_css']) { ?> <p class="<?php echo $val['user_css']; ?>"><?php echo $val['user_str']; ?></p> <?php } ?> </div> <div id="user_<?php echo $val['tid']; ?>_user<?php echo $talkanswerid; ?>"></div> <div id="Pmsend_to_user_area" style="width:430px;display:none"></div> <div id="alert_follower_menu_<?php echo $val['uid']; ?>" style="display:none"></div> <div id="button_<?php echo $val['uid']; ?>" onclick="get_group_choose(<?php echo $val['uid']; ?>);" style="display:none"></div> <div id="global_select_<?php echo $val['uid']; ?>" class="alertBox" style="display:none"></div> <div id="get_remark_<?php echo $val['uid']; ?>" style="display:none"></div> <?php } else { ?><div class="wb_l_face"><div class="avatar"><img src="<?php echo $val['face']; ?>" title="未在微博登录的网站用户" onerror="javascript:faceError(this);" /></div></div> <?php } ?> <div class="Contant"> <div style="_overflow:hidden"> <div class="oriTxt"> <p class="utitle"> <?php if($val['uid']) { ?> <span class="un"> <a title="查看<?php echo $val['nickname']; ?>的微博" href="index.php?mod=<?php echo $val['username']; ?>" class="photo_vip_t_name"  onmouseover="get_at_user_choose('<?php echo $val['nickname']; ?>',this)"><?php echo $val['nickname']; ?></a> <?php if($val['validate_html']) { ?> <?php echo $val['validate_html']; ?>&nbsp;
<?php } else { ?> <?php if($this->Config['topic_level_radio']) { ?> <span class="wb_l_level"> <a class="ico_level wbL<?php echo $val['level']; ?>" title="微博等级：<?php echo $val['level']; ?>级" href="index.php?mod=settings&code=exp" target="_blank"><?php echo $val['level']; ?></a> </span> <?php } ?> <?php } ?> <?php if($this->Config['is_signature']) { ?> <?php if(!$_GET['mod_original'] && 'photo'!=$this->Code) { ?> <?php if($val['signature']) { ?> <span class="signature"> <?php if($val['uid'] == MEMBER_ID ||  'admin' == MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="follower_choose(<?php echo $val['uid']; ?>,'<?php echo $val['nickname']; ?>','topic_signature');" title="点击修改个人签名"> <em ectype="user_signature_ajax_<?php echo $val['uid']; ?>">(<?php echo $val['signature']; ?>)</em> </a> <?php } else { ?><em>(<?php echo $val['signature']; ?>)</em> <?php } ?> </span> <?php } ?> <?php } ?> <?php } ?> <?php echo $this->hookall_temp['global_topiclist_extra1']; ?> </span> <?php echo $this->hookall_temp['global_topiclist_extra2']; ?> <?php } else { ?><span class="un"><a title="未在微博登录的网站用户" href="javascript:;" ><?php echo $val['nickname']; ?></a></span> <?php } ?> <span class="ut"><a href="<?php echo $val['cmsurl']; ?>" target="_blank"><?php echo $val['dateline']; ?></a></span> </p> <?php if($val['title']) { ?> <p>〖<?php echo $val['title']; ?>〗</p> <?php } ?> <span id="c_<?php echo $val['pid']; ?>_short"><?php echo $val['content']; ?></span> <span id="c_<?php echo $val['pid']; ?>_full" style="display:none;"><?php echo $val['content_full']; ?></span> <?php if($val['longtext']) { ?> <span> <a id="linktext_<?php echo $val['pid']; ?>" href="javascript:;" onclick="item_longtext('<?php echo $val['pid']; ?>');return false;">查看全文</a> </span> <?php } ?> <div class="from"><div class="option"><ul><li><span> <?php if($val['replys']) { ?> <a id="topic_list_reply_<?php echo $val['tid']; ?>_aid" href="javascript:void(0)" onclick="replyTopic(<?php echo $val['tid']; ?>,'reply_area_<?php echo $val['tid']; ?>','<?php echo $val['replys']; ?>',0,0,{item:'cms'});return false;">评论 (<?php echo $val['replys']; ?>)</a></span></li><li><span><a href="<?php echo $val['replyurl']; ?>" target="_blank"><?php echo $val['replytime']; ?></a> <?php } else { ?>评论 (<?php echo $val['replys']; ?>)&nbsp;&nbsp;</span></li><li><span><?php echo $val['replytime']; ?> <?php } ?> </span></li></ul></div><div class="mycome">来自<a href="<?php echo $val['typeurl']; ?>" target="_blank">网站 - <?php echo $val['typetitle']; ?></a></div></div> </div> </div> <div id="reply_area_<?php echo $val['tid']; ?>"></div> </div> <?php } else { ?> <?php $talkanswerid = ''; ?> <div class="wb_l_face"> <div class="avatar"> <?php if($this->Code != '') { ?> <?php if(MEMBER_ID != $val['uid']) { ?> <a href="javascript:void(0)" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_user<?php echo $talkanswerid; ?>',<?php echo $val['tid']; ?>);" onmouseout="clear_user_choose();"> <img src="./images/noavatar.gif" data-original="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" class="lazyload" /> </a> <?php } else { ?> <img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /> <?php } ?> <?php } else { ?><a href="index.php?mod=<?php echo $val['username']; ?>"><img src="<?php echo $val['face']; ?>" onerror="javascript:faceError(this);" /></a> <?php } ?> <?php if($this->Config['is_topic_user_follow'] && !$val['user_css']) { ?> <?php echo $val['follow_html']; ?> <?php } ?> </div> <?php if($val['user_css']) { ?> <p class="<?php echo $val['user_css']; ?>"><?php echo $val['user_str']; ?></p> <?php } ?> </div> <div id="user_<?php echo $val['tid']; ?>_user<?php echo $talkanswerid; ?>"></div> <div id="Pmsend_to_user_area" style="width:430px;display:none"></div> <div id="alert_follower_menu_<?php echo $val['uid']; ?>" style="display:none"></div> <div id="button_<?php echo $val['uid']; ?>" onclick="get_group_choose(<?php echo $val['uid']; ?>);" style="display:none"></div> <div id="global_select_<?php echo $val['uid']; ?>" class="alertBox" style="display:none"></div> <div id="get_remark_<?php echo $val['uid']; ?>" style="display:none"></div> <div class="Contant"> <div id="topic_lists_<?php echo $val['tid']; ?>" style="_overflow:hidden"> <div class="oriTxt"> <?php if('myfavorite'==$this->Code && $val['favorite_time']) { ?> <p style="color:#666; font-size:12px;">收藏于：<?php echo $val['favorite_time']; ?></p> <?php } ?> <p class="utitle"> <span class="un"> <a title="查看<?php echo $val['nickname']; ?>的微博" href="index.php?mod=<?php echo $val['username']; ?>" class="photo_vip_t_name"  onmouseover="get_at_user_choose('<?php echo $val['nickname']; ?>',this)"><?php echo $val['nickname']; ?></a> <?php if($val['validate_html']) { ?> <?php echo $val['validate_html']; ?>&nbsp;
<?php } else { ?> <?php if($this->Config['topic_level_radio']) { ?> <span class="wb_l_level"> <a class="ico_level wbL<?php echo $val['level']; ?>" title="微博等级：<?php echo $val['level']; ?>级" href="index.php?mod=settings&code=exp" target="_blank"><?php echo $val['level']; ?></a> </span> <?php } ?> <?php } ?> <?php if($this->Config['is_signature']) { ?> <?php if(!$_GET['mod_original'] && 'photo'!=$this->Code) { ?> <?php if($val['signature']) { ?> <span class="signature"> <?php if($val['uid'] == MEMBER_ID ||  'admin' == MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="follower_choose(<?php echo $val['uid']; ?>,'<?php echo $val['nickname']; ?>','topic_signature');" title="点击修改个人签名"> <em ectype="user_signature_ajax_<?php echo $val['uid']; ?>">(<?php echo $val['signature']; ?>)</em> </a> <?php } else { ?><em>(<?php echo $val['signature']; ?>)</em> <?php } ?> </span> <?php } ?> <?php } ?> <?php } ?> <?php echo $this->hookall_temp['global_topiclist_extra1']; ?> </span> <?php echo $this->hookall_temp['global_topiclist_extra2']; ?> <span class="ut"><a href="index.php?mod=topic&code=<?php echo $val['tid']; ?>" target="_blank"><?php echo $val['dateline']; ?> </a></span> </p> <span id="topic_content_<?php echo $val['tid']; ?>_short"><?php echo $val['content']; ?></span> <span id="topic_content_<?php echo $val['tid']; ?>_full"></span> <?php if($val['longtextid'] > 0) { ?> <span> <a href="javascript:;" onclick="view_longtext('<?php echo $val['longtextid']; ?>', '<?php echo $val['tid']; ?>', this);return false;">查看全文</a> </span> <?php } ?> <?php if($val['attachid'] && $val['attach_list']) { ?> <?php $val['attach_key']=$val['tid'].'_'.mt_rand(); ?> <ul class="attachList" id="attach_area_<?php echo $val['attach_key']; ?>"> <?php if(is_array($val['attach_list'])) { foreach($val['attach_list'] as $iv) { ?> <li><img src="<?php echo $iv['attach_img']; ?>" class="attachList_img" /> <div class="attachList_att"> <p class="attachList_att_name"><b><?php echo $iv['attach_name']; ?></b>
&nbsp;（<?php echo $iv['attach_size']; ?>）</p> <p class="attachList_att_doc"><a href="javascript:void(0);"
onclick="downattach(<?php echo $iv['id']; ?>);">点此下载</a>（需<?php echo $iv['attach_score']; ?>积分，已下载<?php echo $iv['attach_down']; ?>次）</p> </div> </li> <?php } } ?> </ul> <?php } ?> <?php if($val['imageid'] && $val['image_list']) { ?> <?php $val['image_key']=$val['tid'].'_'.mt_rand(); ?> <ul class="imgList" id="image_area_<?php echo $val['image_key']; ?>"> <?php if(is_array($val['image_list'])) { foreach($val['image_list'] as $iv) { ?> <?php $ivw=min(460, $iv['image_width']); ?> <li><a href="<?php echo $iv['image_original']; ?>" class="artZoomAll"
rel="<?php echo $iv['image_small']; ?>" rev="<?php echo $val['image_key']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_small']; ?>" /></a> <div class="artZoomBox" style="display: none;"> <div class="tool"><a title="向左转" href="#" class="imgLeft">向左转</a><a 
title="向右转" href="#" class="imgRight">向右转</a><a title="查看原图"
href="<?php echo $iv['image_original']; ?>" class="viewImg">查看原图</a></div> <a class="maxImgLinkAll" href="<?php echo $iv['image_original']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_original']; ?>" maxWidth="460" width="<?php echo $ivw; ?>" class="maxImg"></a></div> </li> <?php } } ?> </ul> <?php } ?> <?php if($val['is_vote'] > 0) { ?> <?php $val['vote_key']=$val['tid'].'_'.$val['random'] ?> <ul class="imgList" id="vote_detail_<?php echo $val['vote_key']; ?>"> <li><a href="javascript:;"
onclick="getVoteDetailWidgets('<?php echo $val['vote_key']; ?>', <?php echo $val['is_vote']; ?>);"><img
src='./images/vote_pic_01.gif' /></a></li> </ul> <div id="vote_area_<?php echo $val['vote_key']; ?>" style="display: none;"> <div class="blogTxt"> <div class="top"></div> <div class="mid" id="vote_content_<?php echo $val['vote_key']; ?>"></div> <div class="bottom"></div> </div> </div> <?php } ?> <?php if($val['videoid'] and $this->Config['video_status']) { ?> <div class="feedUservideo"><a
onClick="javascript:showFlash('<?php echo $val['VideoHosts']; ?>', '<?php echo $val['VideoLink']; ?>', this, '<?php echo $val['VideoID']; ?>','<?php echo $val['VideoUrl']; ?>');"> <div id="play_<?php echo $val['VideoID']; ?>" class="vP"><img
src="images/feedvideoplay.gif" /></div> <img src="<?php echo $val['VideoImg']; ?>" style="border: none" /> </a></div> <?php } ?> <?php if($val['musicid']) { ?> <?php if($val['xiami_id']) { ?> <div class="feedUserImg"><embed width="257" height="33"
wmode="transparent" type="application/x-shockwave-flash"
src="http://www.xiami.com/widget/0_<?php echo $val['xiami_id']; ?>/singlePlayer.swf"></embed></div> <?php } else { ?><div class="feedUserImg"> <div id="play_<?php echo $val['MusicID']; ?>"></div> <img src="images/music.gif" title="点击播放音乐"
onClick="javascript:showFlash('music', '<?php echo $val['MusicUrl']; ?>', this, '<?php echo $val['MusicID']; ?>');"
style="cursor: pointer; border: none;" /></div> <?php } ?> <?php } ?><script type="text/javascript"> var __TOPIC_VIEW__ = '<?php echo $topic_view; ?>'; </script> <?php if(($tpid=$val['top_parent_id'])>0 && !in_array($this->Code, array('forward', 'reply'))) { ?> <?php if(('mycomment'==$this->Code || $topic_view) && 'reply'==$val['type'] && $tpid!=($pid=$val['parent_id']) && $parent_list[$pid]) { ?> <p class="feedP">回复{<a
href="index.php?mod=<?php echo $parent_list[$pid]['username']; ?>"><?php echo $parent_list[$pid]['nickname']; ?>：</a><span><?php echo $parent_list[$pid]['content']; ?></span>}</p> <?php } ?> <?php if(!$topic_view) { ?> <?php $pt=$parent_list[$tpid]; ?> <div class="blogTxt"> <div class="top"></div> <div class="mid"><p> <?php if($pt) { ?> <span> <a
href="index.php?mod=<?php echo $pt['username']; ?>"
onmouseover="get_user_choose(<?php echo $pt['uid']; ?>,'_reply_user',<?php echo $val['tid']; ?>);"
onmouseout="clear_user_choose();"> <?php echo $pt['nickname']; ?> </a> <?php echo $pt['validate_html']; ?> :   <span
id="user_<?php echo $val['tid']; ?>_reply_user"></span> </span> <?php $TPT_ = $TPT_ ? $TPT_ : 'TPT_'; ?> <span id="topic_content_<?php echo $TPT_; ?><?php echo $pt['tid']; ?>_short"><?php echo $pt['content']; ?></span> <span
id="topic_content_<?php echo $TPT_; ?><?php echo $pt['tid']; ?>_full"></span> <?php if($pt['longtextid'] > 0) { ?> <span> <a href="javascript:;"
onclick="view_longtext('<?php echo $pt['longtextid']; ?>', '<?php echo $pt['tid']; ?>', this, '<?php echo $TPT_; ?>', '<?php echo $val['tid']; ?>');return false;">查看全文</a> </span> <?php } ?> <?php if($pt['attachid'] && $pt['attach_list']) { ?> <table class="attachst" style="width:440px;"> <?php if(is_array($pt['attach_list'])) { foreach($pt['attach_list'] as $iv) { ?> <tr> <td class="attachst_img"><img src="<?php echo $iv['attach_img']; ?>" /></td> <td class="attachst_att"> <p class="attachList_att_name"><b><?php echo $iv['attach_name']; ?></b>&nbsp;（<?php echo $iv['attach_size']; ?>）</p> <p class="attachList_att_doc"><a href="javascript:void(0);"
onclick="downattach(<?php echo $iv['id']; ?>);">点此下载</a>（需<?php echo $iv['attach_score']; ?>积分，已下载<?php echo $iv['attach_down']; ?>次）</p> </td> </tr> <?php } } ?> </table> <?php } ?> <?php if($pt['imageid'] && $pt['image_list']) { ?> <?php $pt['image_key']=$pt['tid'].'_'.mt_rand(); ?> <ul class="imgList" id="image_area_<?php echo $pt['image_key']; ?>"> <?php if(is_array($pt['image_list'])) { foreach($pt['image_list'] as $iv) { ?> <?php $ivw=min(440, $iv['image_width']); ?> <li><a href="<?php echo $iv['image_original']; ?>" class="artZoomAll"
rel="<?php echo $iv['image_small']; ?>" rev="<?php echo $pt['image_key']; ?>"><img
src="./images/grey.gif" data-original="<?php echo $iv['image_small']; ?>" /></a> <div class="artZoomBox" style="display:none;"> <div class="tool"><a title="向左转" href="#" class="imgLeft">向左转</a><a 
title="向右转" href="#" class="imgRight">向右转</a><a title="查看原图"
href="<?php echo $iv['image_original']; ?>" class="viewImg">查看原图</a></div> <a class="maxImgLinkAll" href="<?php echo $iv['image_original']; ?>"> <img
src="./images/grey.gif" data-original="<?php echo $iv['image_original']; ?>" maxWidth="440" width="<?php echo $ivw; ?>" class="maxImg"></a></div> </li> <?php } } ?> </ul> <?php } ?> <?php if($pt['is_vote'] > 0) { ?> <?php $__vote_key = $pt['tid'].'_'.$pt['random'] ?> <ul class="imgList" id="vote_detail_<?php echo $__vote_key; ?>"> <li><a href="javascript:;" onclick="getVoteDetailWidgets('<?php echo $__vote_key; ?>', <?php echo $pt['is_vote']; ?>);"><img src='./images/vote_pic_01.gif' /></a></li> </ul> <div id="vote_area_<?php echo $__vote_key; ?>" style="display: none;"> <div class="vote_zf_box" id="vote_content_<?php echo $__vote_key; ?>"></div> </div> <?php } ?> <?php if($pt['videoid'] and $this->Config['video_status']) { ?> <div class="feedUservideo"> <a onClick="javascript:showFlash('<?php echo $pt['VideoHosts']; ?>', '<?php echo $pt['VideoLink']; ?>', this, '<?php echo $pt['VideoID']; ?>','<?php echo $pt['VideoUrl']; ?>');"> <div id="play_<?php echo $pt['VideoID']; ?>" class="vP"><img src="images/feedvideoplay.gif" /></div> <img src="<?php echo $pt['VideoImg']; ?>" style="border: none" /> </a></div> <?php } ?> <?php if($pt['musicid']) { ?> <?php if($pt['xiami_id']) { ?> <div class="feedUserImg"> <embed width="257" height="33" wmode="transparent" type="application/x-shockwave-flash" src="http://www.xiami.com/widget/0_<?php echo $pt['xiami_id']; ?>/singlePlayer.swf"></embed></div> <?php } else { ?><div class="feedUserImg"> <div id="play_<?php echo $pt['MusicID']; ?>"></div> <img src="images/music.gif" title="点击播放音乐" onClick="javascript:showFlash('music', '<?php echo $pt['MusicUrl']; ?>', this, '<?php echo $pt['MusicID']; ?>');" style="cursor: pointer; border: none;" /></div> <?php } ?> <?php } ?> </p> <div style="float:left; padding:5px 0; margin:0;"> <a href="index.php?mod=topic&code=<?php echo $tpid; ?>" target="_blank">原文评论(<?php echo $pt['replys']; ?>)</a>&nbsp;
<a onclick="get_forward_choose(<?php echo $tpid; ?>);return false;"href="index.php?mod=topic&code=<?php echo $tpid; ?>" target="_blank">转发原文(<?php echo $pt['forwards']; ?>)</a>&nbsp;
<?php echo $pt['from_html']; ?></div> <?php } else { ?> <?php $val['reply_disable']=0; ?> <p><span>原始微博已删除</span></p> <?php } ?> </div> <div class="bottom"></div> </div> <?php } ?> <?php } ?> <?php if($this->Module=='qun') { ?> <script type="text/javascript">
$(document).ready(function(){
var objStr1 = "#topic_lists_<?php echo $val['tid']; ?>_a";
var objStr2 = "#topic_lists_<?php echo $val['tid']; ?>_b";
$(objStr1).mouseover(function(){$(objStr2).show();});
$(objStr1).mouseout(function(){$(objStr2).hide();});
});
</script> <?php if($this->Config['qun_attach_enable']) $allow_attach = 1; else $allow_attach = 0  ?> <div class="from"> <div class="option"> <ul> <?php if(MEMBER_ID>0) { ?> <li> <?php if($val['managetype'] != 2) { ?> <span> <a href="javascript:void(0);" onclick="
<?php if($allow_list_manage) { ?>
get_forward_choose(<?php echo $val['tid']; ?>,<?php echo $allow_attach; ?>, {appitem:'<?php echo $val['item']; ?>', appitem_id:'<?php echo $val['item_id']; ?>',noReply:1});
<?php } else { ?>get_forward_choose(<?php echo $val['tid']; ?>,<?php echo $allow_attach; ?>);
<?php } ?>
" style="cursor:pointer;">转发
<?php if($val['forwards']) { ?>
(<?php echo $val['forwards']; ?>)
<?php } ?> </a> </span> <?php } else { ?> <span title="设置了特殊的权限，不允许转发">转发</span> <?php } ?> </li> <li class="o_line_l">|</li> <li> <?php if(!$val['reply_disable'] && $val['managetype'] != 2) { ?> <span> <a href="javascript:void(0)" onclick="
<?php if($allow_list_manage) { ?>
replyTopic(<?php echo $val['tid']; ?>,'reply_area_<?php echo $val['tid']; ?>','<?php echo $val['replys']; ?>',1,<?php echo $allow_attach; ?>,{appitem:'<?php echo $val['item']; ?>', appitem_id:'<?php echo $val['item_id']; ?>'});
<?php } else { ?>replyTopic(<?php echo $val['tid']; ?>,'reply_area_<?php echo $val['tid']; ?>','<?php echo $val['replys']; ?>',0,<?php echo $allow_attach; ?>);
<?php } ?>
return false;">评论
<?php if($val['replys']) { ?>
(<?php echo $val['replys']; ?>)
<?php } ?> </a> </span> <?php } else { ?><span>评论</span> <?php } ?> </li> <li class="o_line_l">|</li> <li id="topic_lists_<?php echo $val['tid']; ?>_a" class="mobox"><a href="javascript:void(0)" class="moreti" ><span class="txt">更多</span><span class="more"></span></a> <div id="topic_lists_<?php echo $val['tid']; ?>_b" class="molist" style="display:none"> <?php if(MEMBER_ID>0) { ?> <?php if('myfavorite'==$this->
Code) { ?> <span id="favorite_<?php echo $val['tid']; ?>"><a href="javascript:void(0)" onclick="favoriteTopic(<?php echo $val['tid']; ?>,'delete');return false;">取消收藏</a></span> <?php } else { ?><span id="favorite_<?php echo $val['tid']; ?>"><a href="javascript:void(0)" onclick="favoriteTopic(<?php echo $val['tid']; ?>,'add');return false;">收藏</a></span> <?php } ?> <?php } ?> <?php if($this->Config['is_report'] || MEMBER_ID > 0) { ?> <a href="javascript:void(0)" onclick="ReportBox('<?php echo $val['tid']; ?>')" title="举报不良信息">举报</a> <?php } ?> <?php if($val['uid']==MEMBER_ID || 'admin'==MEMBER_ROLE_TYPE) { ?> <?php if($this->Code > 0  ||  in_array($this->Code,array('list_reply','do_add'))) $eid = 'reply_list'; else $eid = 'topic_list'  ?> <a href="javascript:void(0)" onclick="deleteTopic(<?php echo $val['tid']; ?>,'<?php echo $eid; ?>_<?php echo $val['tid']; ?>');return false;">删除</a> <?php $datetime = time(); $modify_time = $this->Config['topic_modify_time'] * 60 ?> <?php if($datetime - $val['addtime'] < $modify_time || 'admin'==MEMBER_ROLE_TYPE ) { ?> <?php if($val['replys'] <= 0 && $val['forwards'] <= 0 || 'admin'==MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="modifyTopic(<?php echo $val['tid']; ?>,'modify_topic_<?php echo $val['tid']; ?>','<?php echo $eid; ?>',<?php echo $allow_attach; ?>);return false;" style="cursor:pointer;">编辑</a> <?php } ?> <?php } ?> <a href="javascript:void(0)" onclick="showTopicRecdDialog({'tid':'<?php echo $val['tid']; ?>'});return false;">推荐</a> <?php } ?> </div> </li> <?php } ?> </ul> </div> <div class="mycome"> <?php if(!$no_from) { ?> <?php echo $val['from_html']; ?> <?php } ?> </div> </div> <?php } else { ?><script type="text/javascript">
$(document).ready(function(){
var objStr1 = "#<?php echo $talkanswerid; ?>topic_lists_<?php echo $val['tid']; ?>_a";
var objStr2 = "#<?php echo $talkanswerid; ?>topic_lists_<?php echo $val['tid']; ?>_b";
$(objStr1).mouseover(function(){$(objStr2).show();});
$(objStr1).mouseout(function(){$(objStr2).hide();});
});
</script> <?php if($this->Config['attach_enable']) $allow_attach = 1; else $allow_attach = 0  ?> <div class="from"> <div class="option"> <ul> <?php if($this->Get['mod'] == 'talk' &&  $val['reply_ok']) { ?> <li><span id="answer_<?php echo $val['tid']; ?>" class="talkreply" onclick="showMainPublishBox('answer','talk','<?php echo $talk['lid']; ?>','<?php echo $val['tid']; ?>','<?php echo $val['uid']; ?>');return false;">回答</span></li><li class="o_line_l">|</li> <?php } ?> <?php if($this->Get['type'] != 'my_verify') { ?> <?php $tpid=$val['top_parent_id']; if ($tpid&&$parent_list[$tpid]) $root_type=$parent_list[$tpid]['type']; ?> <?php if((!isset($root_type)) || (isset($root_type) && in_array($root_type, get_topic_type('forward')))) { ?> <li> <?php if((in_array($val['type'], get_topic_type('forward')) || $this->Module=='qun') && $val['managetype'] != 2) { ?> <?php $not_allow_forward=0; ?> <span 
<?php if(MEMBER_ID <1 ) { ?>
onclick="ShowLoginDialog();" 
<?php } ?>
> <a href="javascript:void(0);" onclick="get_forward_choose(<?php echo $val['tid']; ?>,<?php echo $allow_attach; ?>);" style="cursor:pointer;">转发
<?php if($val['forwards']) { ?>
(<?php echo $val['forwards']; ?>)
<?php } ?> </a> </span> <?php } else { ?> <?php $not_allow_forward=1; ?> <?php if(isset($val['fansgroup'])) { ?> <span><?php echo $val['fansgroup']; ?></span> <?php } else { ?> <span title="设置了特殊的权限，不允许转发">转发</span> <?php } ?> <?php } ?> </li> <li class="o_line_l">|</li> <?php } else { ?><?php $not_allow_forward=1; ?> <?php } ?> <li> <?php if(!$val['reply_disable'] && $val['managetype'] != 2) { ?> <span> <?php $opstring = chr(123).chr(125) ?> <?php if(defined('NEDU_MOYO')) { ?> <?php $opstring = nlogic('feeds.app.jsg')->topic_comment_ajax_options($options, $val) ?> <?php } ?> <a id="topic_list_reply_<?php echo $val['tid']; ?>_aid" href="javascript:void(0)" 
<?php if(MEMBER_ID <1 ) { ?>
onclick="ShowLoginDialog();" 
<?php } ?>
onclick="replyTopic(<?php echo $val['tid']; ?>,'<?php echo $talkanswerid; ?>reply_area_<?php echo $val['tid']; ?>','<?php echo $val['replys']; ?>',<?php echo $not_allow_forward; ?>,<?php echo $allow_attach; ?>,<?php echo $opstring; ?>);return false;">评论
<?php if($val['replys']) { ?>
(<?php echo $val['replys']; ?>)
<?php } ?> </a> </span> <?php } else { ?> <span>评论</span> <?php } ?> </li> <li class="o_line_l">|</li> <li id="<?php echo $talkanswerid; ?>topic_lists_<?php echo $val['tid']; ?>_a" class="mobox"> <a href="javascript:void(0)" class="moreti" ><span class="txt">更多</span><span class="more"></span></a> <div id="<?php echo $talkanswerid; ?>topic_lists_<?php echo $val['tid']; ?>_b" class="molist" style="display:none"> <?php if('myfavorite'==$this->Code) { ?> <span id="favorite_<?php echo $val['tid']; ?>" 
<?php if(MEMBER_ID <1 ) { ?>
onclick="ShowLoginDialog();" 
<?php } ?>
> <a href="javascript:void(0)" onclick="favoriteTopic(<?php echo $val['tid']; ?>,'delete');return false;">取消收藏</a> </span> <?php } else { ?><span id="favorite_<?php echo $val['tid']; ?>" 
<?php if(MEMBER_ID < 1) { ?>
onclick="ShowLoginDialog();" 
<?php } ?>
> <a href="javascript:void(0)" onclick="favoriteTopic(<?php echo $val['tid']; ?>,'add');return false;">收藏</a> </span> <?php } ?> <?php if($this->Config['is_report'] || MEMBER_ID > 0) { ?> <span 
<?php if(MEMBER_ID <1 ) { ?>
onclick="ShowLoginDialog();" 
<?php } ?>
><a href="javascript:void(0)" onclick="ReportBox('<?php echo $val['tid']; ?>')" title="举报不良信息">举报</a></span> <?php } ?> <?php if($val['uid']==MEMBER_ID || 'admin'==MEMBER_ROLE_TYPE) { ?> <?php if($this->Code > 0  ||  in_array($this->Code,array('list_reply','do_add'))) $eid = 'reply_list'; else $eid = 'topic_list'  ?> <a href="javascript:void(0)" onclick="deleteTopic(<?php echo $val['tid']; ?>,'<?php echo $eid; ?>_<?php echo $val['tid']; ?>');return false;">删除</a> <?php $datetime = time(); $modify_time = $this->Config['topic_modify_time'] * 60 ?> <?php if($datetime - $val['addtime'] < $modify_time || 'admin'==MEMBER_ROLE_TYPE ) { ?> <?php if($val['replys'] <= 0 && $val['forwards'] <= 0 || 'admin'==MEMBER_ROLE_TYPE) { ?> <a href="javascript:void(0);" onclick="modifyTopic(<?php echo $val['tid']; ?>,'modify_topic_<?php echo $val['tid']; ?>','<?php echo $eid; ?>',<?php echo $allow_attach; ?>);return false;" style="cursor:pointer;">编辑</a> <?php } ?> <?php } ?> <a href="javascript:void(0)" onclick="showTopicRecdDialog({'tid':'<?php echo $val['tid']; ?>','tag_id':'<?php echo $tag_id; ?>'});return false;">推荐</a> <?php } ?> <?php if('admin'==MEMBER_ROLE_TYPE) { ?> <a onclick="force_out(<?php echo $val['uid']; ?>);" href="javascript:void(0);">封杀</a> <a onclick="force_ip('<?php echo $val['postip']; ?>');" href="javascript:void(0);">封IP</a> <?php } ?> </div> </li> <?php } else { ?><li id="topic_lists_<?php echo $val['id']; ?>_a" class="mobox"> <?php if($val['uid']==MEMBER_ID || 'admin'==MEMBER_ROLE_TYPE) { ?> <?php if($this->Code > 0  ||  in_array($this->Code,array('list_reply','do_add'))) $eid = 'reply_list'; else $eid = 'topic_list'  ?> <a href="javascript:void(0)" onclick="deleteVerify(<?php echo $val['id']; ?>,'<?php echo $eid; ?>_<?php echo $val['id']; ?>');return false;">删除</a> <?php } ?> </li> <?php } ?> </ul> </div> <div class="mycome"> <?php if(!$no_from) { ?> <?php echo $val['from_html']; ?> <?php } ?> <?php echo $this->hookall_temp['global_topiclist_extra3']; ?> </div> <?php echo $this->hookall_temp['global_topiclist_extra4']; ?> </div> <?php } ?> </div> </div> <div id="reply_area_<?php echo $val['tid']; ?>"></div> <div id="modify_topic_<?php echo $val['tid']; ?>"></div> <div id="forward_menu_<?php echo $val['tid']; ?>" class="Fbox"></div> </div> <?php } ?> <div class="mBlog_linedot" style=""></div> </div> <?php } } ?> <?php } ?> <?php if($page_arr['html'] && !$pagehtml_not) { ?> <div class="pageStyle"> <li><?php echo $page_arr['html']; ?></li> </div> <?php } ?> <?php } else { ?> <?php if('bbs' == $this->Code || 'cms' == $this->Code || 'department' == $this->Code || 'company' == $this->Get['mod']) { ?> <br>暂时没有可显示的微博或数据<?php } elseif('channel' == $this->Code && !$my_buddy_channel) { ?><br>您还没有关注任何频道，请先进入<a href="index.php?mod=channel">频道页</a>，对你所感兴趣的频道进行关注。<br><br>关注后，该频道的微博将显示在这里！
<?php } else { ?><br>分类下暂时没有发布微博。
<?php } ?> <?php } ?> <?php if('groupview'== $this->Code && $counts <=0) { ?> <BR />
"<strong><?php echo $groupname; ?></strong>" 分组下的用户暂时没有发布微博。
<?php } ?> <?php if($counts <=5) { ?> <div id="topic_list_<?php echo $counts; ?>" > <?php if('myat'== $this->Code) { ?> <BR />
这里会显示含有"@<?php echo MEMBER_NICKNAME; ?>"的微博。<BR /> <BR /> <span>@昵称 </span>技巧：注意昵称后面有“空格”，可以理解为向某人说，被@昵称 提到的人如果在系统中存在，那么TA就可在其个人首页“@提到我的”的栏目中看到你发布的内容。
<?php } elseif('mycomment' == $this->Code) { ?> <BR /> <BR /> <BR />
这里会显示他人对你微博所做的评论。<BR /> <BR /> <A HREF="index.php?mod=<?php echo $member['username']; ?>&code=fans" title="关注<?php echo $member['nickname']; ?>的">关注你的</A>人越多，就会有越多人参与你分享内容的讨论，尝试通过<A HREF="index.php?mod=profile&code=invite">邀请好友</A>来让更多人关注你；<BR /><?php } elseif('tocomment' == $this->Code) { ?> <BR /> <BR /> <BR />
这里会显示你对他人微博所做的评论。<BR /> <BR /> <?php } elseif('myfavorite' == $this->Code) { ?> <BR />
这里会显示你所收藏的微博。<BR /> <BR />
在登录状态下，每个微博的下方都有一个收藏连接，点击即可自动完成收藏，然后你就可以在这里看到了。你可以访问<A HREF="index.php?mod=topic&code=hot">热门微博</A>来发现有收藏价值的内容；<BR /> <?php } elseif('favoritemy' == $this->Code) { ?> <BR />
这里会显示谁收藏了你的微博。<BR /> <BR />
赶快分享些有价值的新鲜事吧，当有人收藏后，你就会在这里看到。<BR /><?php } elseif('myhome' == $this->Code ) { ?><BR /><BR />
这里显示我和我关注人的微博。<BR /><BR />
关注你喜欢的人，你就可以在这看到他们分享的内容，尝试通过<A HREF="index.php?mod=topic&code=top">达人榜</A>、<A HREF="index.php?mod=profile&code=search">找人</A>选择用户加关注；<BR /><?php } elseif('tag' == $this->Code ) { ?><BR /><BR />
这里显示我关注话题的相关微博。<BR /><BR />
对你感兴趣的话题进行关注，就可以在这里直接查看相关微博，还可以结识有共同话题的人，尝试通过<A HREF="index.php?mod=tag">话题榜</A> 选择话题关注；<BR /><?php } elseif('event' == $view ) { ?><BR />
这里显示我关注活动的相关微博。<BR />
对你感兴趣的活动进行关注，就可以在这里直接查看相关微博，还可以结识有共同话题的人。<BR /><?php } elseif('qun' == $this->Code ) { ?><BR /><BR />
这里显示我加入的群相关的微博。<BR /><BR />
加入你感兴趣的群，就可以在这里直接查看相关微博，还可以结识有共同话题的人。<a href="index.php?mod=qun" target="_blank">去群里逛逛吧~~</a><BR /><?php } elseif('recd' == $this->Code ) { ?><BR /><BR />
这里显示推荐的微博。<BR /><BR /><?php } elseif('cms' == $this->Code ) { ?><BR /><BR />
这里显示来自<a href="<?php echo $cms_url; ?>" target="_blank">网站资讯</a>的内容。<BR /><BR /> <?php if('admin'==MEMBER_ROLE_TYPE) { ?>
前提条件是：微博必须整合了DedeCMS系统。<BR /><BR /> <?php } ?> <?php } elseif('bbs' == $this->Code ) { ?><BR /><BR /> <?php if($this->Config['dzbbs_enable']) { ?>
这里显示来自<a href="<?php echo $bbs_url; ?>" target="_blank">论坛</a>的帖子。<BR /><BR /> <?php if('admin'==MEMBER_ROLE_TYPE) { ?>
前提条件是：微博必须整合了Ucenter系统和Discuz论坛。<BR /><BR /> <?php } ?> <?php } elseif($this->Config['phpwind_enable']) { ?>这里显示来自<a href="<?php echo $bbs_url; ?>" target="_blank">论坛</a>的帖子。<BR /><BR /> <?php if('admin'==MEMBER_ROLE_TYPE) { ?>
前提条件是：微博必须整合了PhpWind论坛，且同时开启了调用phpwind论坛帖子。<BR /><BR /> <?php } ?> <?php } ?> <?php } elseif('fenlei' == $view ) { ?><BR />
这里显示我关注分类的相关微博。<BR />
对你感兴趣的分类进行关注，就可以在这里直接查看相关微博。<BR /> <?php } ?> </div> <?php } ?> </div> <?php echo $this->js_show_msg(); ?> </div> <div id="share"></div> <div id="need_info"></div> </div> <script type="text/javascript">
function listTopic() {
var id = document.getElementById('hid_id').value;
var myAjax = $.post(
"ajax.php?mod=app&code=list_event&item_id="+id,
function (d) {
$("#listTopicArea").html(d);
}
); 
}
function share(t){
var check_uid = $('#check_PublishBox_uid').val();
var uid ='undefined'==typeof(check_uid)?'0':check_uid;	
if(uid < 1){
ShowLoginDialog();
return false;
}
var handle_key = "share";
$.post(
"ajax.php?mod=event&code=publish_share&id=<?php echo $id; ?>",
function(d){
showDialog(handle_key, 'local', '分享到微博', {"html":d}, 500);
$('#topic_simple_close_btn').click(
function() {
if(t){
location.reload();
}else{
closeDialog(handle_key);
}
}
);
$('#topic_simple_share_btn').click(
function () {
var options = {
response:function(){
closeDialog(handle_key);
}
}
publishSimpleTopic($('#topic_simple_content').val(), 'web', $("#topic_simple_item_id").val(),options);
MessageBox('notice', '分享成功');
}
);
}
);
}
function simpleTalk(){
var handle_key = "simpletalk";
$.post(
"ajax.php?mod=event&code=simple_talk&id=<?php echo $id; ?>",
function(d){
showDialog(handle_key, 'local', '报名成功，说说你对该活动有什么看法？', {"html":d}, 500);
$('#topic_simple_close_btn').click(
function() {
location.reload();
}
);
$('#topic_simple_share_btn').click(
function () {
var options = {
response:function(){
location.reload();
},
topic_type:$('#topic_simple_type').val()
}
if(!$('#topic_simple_content').val()){MessageBox('notice', '请输入评论内容');return false;}
publishSimpleTopic($('#topic_simple_content').val(), 'event', $("#topic_simple_item_id").val(),options);
}
);
}
);
}
function store(t){
var check_uid = $('#check_PublishBox_uid').val();
var uid ='undefined'==typeof(check_uid)?'0':check_uid;	
if(uid < 1){
ShowLoginDialog();
return false;
}
$.post("ajax.php?mod=event&code=store&id=<?php echo $id; ?>&type="+t,
function(d){
location.reload();
}
);
}
function app(t){
var check_uid = $('#check_PublishBox_uid').val();
var uid ='undefined'==typeof(check_uid)?'0':check_uid;	
if(uid < 1){
ShowLoginDialog();
return false;
}
var myAjax=$.post(
"ajax.php?mod=event&code=app&id=<?php echo $id; ?>&type="+t,
function(d){
if(!is_json(d)){
var handle_key = 'need_info';
showDialog(handle_key, 'ajax', '我要参加', {url:"ajax.php?mod=event&code=app&id=<?php echo $id; ?>&type="+t}, 300);
}else{
var json = eval('('+d.toString()+')');
if(json.done){
if(json.msg == '1'){
simpleTalk();
}else{
MessageBox('notice', json.msg);
setTimeout('location.reload()',1000);
}
}else{
MessageBox('warning', json.msg);
}
}
}
);
}
function manage(page){
handle_key = "manage";
showDialog(handle_key, 'ajax', '我要参加', {url:"ajax.php?mod=event&code=manage&id=<?php echo $id; ?>&page="+page}, 600);
}
</script> <div class="Hotright"> <div class="HboxR"> <ul class="event_r_nav"> <li> <a href="index.php?mod=event"><span>活动主页</span></a> </li> <li> <a href="index.php?mod=event&code=myevent"><span>我的活动</span></a> </li> <li> <a href="index.php?mod=event&code=followevent"><span>我关注的人的活动</span></a> </li> <li> <a href="index.php?mod=event&code=pevent"><span>发起活动</span></a> </li> </ul> </div> <div  class="HboxR"> <div class="vote_line">这个活动的参与者</div> <div> <ul class="followList eli" style="overflow:hidden"> <?php if(is_array($play_member)) { foreach($play_member as $val) { ?> <li class="pane" id="follow_user_<?php echo $val['uid']; ?>"> <div class="fBox_l"><img onerror="javascript:faceError(this);" src="<?php echo $val['face']; ?>" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_r_user',<?php echo $val['uid']; ?>);" onmouseout="clear_user_choose();"/> </div> <div id="user_<?php echo $val['uid']; ?>_r_user" class="layS"></div> </li> <?php } } ?> </ul> </div> <div><a href="index.php?mod=event&code=alluser&id=<?php echo $id; ?>&type=play">共<?php echo $play_count; ?>人(查看所有参与者)</a></div> </div> <div class="HboxR"> <div class="vote_line">审核中的报名者</div> <ul class="followList eli"> <?php if(is_array($app_member)) { foreach($app_member as $val) { ?> <li class="pane" id="app_user_<?php echo $val['uid']; ?>"> <div class="fBox_l"><img onerror="javascript:faceError(this);" src="<?php echo $val['face']; ?>" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_r_user',<?php echo $val['uid']; ?>);" onmouseout="clear_user_choose();"/> </div> <div id="user_<?php echo $val['uid']; ?>_r_user" class="layS"></div> </li> <?php } } ?> <li style="width:180px;"><a href="index.php?mod=event&code=alluser&id=<?php echo $id; ?>&type=app">共<?php echo $app_count; ?>人(查看所有审核中报名者)</a> </li> </ul> </div> </div> </div> </div><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script type="text/javascript" src="templates/default/js/jsgst.js?build+20120829"></script> <div id="show_message_area"></div> <?php echo $this->js_show_msg(); ?> <?php echo $GLOBALS['schedule_html']; ?> <?php if($GLOBALS['jsg_schedule_mark'] || jsg_getcookie('jsg_schedule')) echo jsg_schedule(); ?> <div id="ajax_output_area"></div> <?php if(MEMBER_ID ==0) { ?> <style type="text/css">
.bottomLinks{width:930px;}
.bottomLinks .bL_info{width:180px;}
</style> <?php } ?> <div class="bottomLinks_R"> <div class="bottomLinks <?php echo $t_col_foot; ?> bottomLinks_reg"> <div class="bL_List"> <div class="bL_info bL_io1 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">找感兴趣的人</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=people">名人堂</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=media">媒体汇</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=top">排行榜</a></li> <li class="MIB_linkar"><a href="index.php?mod=profile&code=maybe_friend" rel="nofollow">猜你喜欢的</a></li> </ul> </div> <div class="bL_info bL_io2 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">精彩内容</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=live">微直播</a></li> <li class="MIB_linkar"><a href="index.php?mod=talk">微访谈</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=new">最新微博</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=recd">官方推荐</a></li> </ul> </div> <div class="bL_info bL_io3 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">应用热门</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=show&code=show">微博秀</a></li> <li class="MIB_linkar"><a href="index.php?mod=topic&code=photo">图片墙</a></li> <li class="MIB_linkar"><a href="index.php?mod=wall&code=control">上墙</a></li> <li class="MIB_linkar"><a href="index.php?mod=tools&code=qmd">图片签名档</a></li> </ul> </div> <div class="bL_info bL_io4 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">手机玩微博</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=other&code=wap">WAP访问</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=mobile" target=_blank>3G网页</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=android">android客户端</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=iphone">iphone客户端</a></li> </ul> </div> <div class="bL_info bL_io5 <?php echo $bL_info_three; ?>"> <h4 class="MIB_txtar">关于我们</h4> <ul> <li class="MIB_linkar"><a href="index.php?mod=other&code=contact">联系我们</a></li> <li class="MIB_linkar"><a href="index.php?mod=other&code=vip_intro">申请V认证</a></li> <?php if(!empty($navigation_config['pluginmenu'])) { ?> <?php if(is_array($navigation_config['pluginmenu'])) { foreach($navigation_config['pluginmenu'] as $pmenus) { ?> <?php if(is_array($pmenus)) { foreach($pmenus as $pmenu) { ?> <?php if($pmenu['type'] == 2) { ?> <li><a href="<?php echo $pmenu['url']; ?>" target="<?php echo $pmenu['target']; ?>"><?php echo $pmenu['name']; ?></a></li> <?php } ?> <?php } } ?> <?php } } ?> <?php } ?> <li><?php echo $this->Config['tongji']; ?></li> <li class="MIB_linkar"> <a href="http://www.miibeian.gov.cn/" target="_blank" title="网站备案" rel="nofollow"><?php echo $this->Config['icp']; ?></a></li> <li class="MIB_linkar"> <?php $__server_execute_time = round(microtime(true) - $GLOBALS['_J']['time_start'], 5) . " Second "; ?> <?php $__gzip_tips = ((defined('GZIP') && GZIP) ? "&nbsp;Gzip Enable." : "Gzip Disable."); ?> <span title="<?php echo $__server_execute_time; ?>,<?php echo $__gzip_tips; ?>">网页执行信息</span> <?php echo upsCtrl()->Comlic(); ?></li> <li><?php echo $this->Config['copyright']; ?></li> </ul> </div> </div> </div></div> <script type="text/javascript">
$(document).ready(function(){
//图片延迟加载
$("ul.imgList img, div.avatar img.lazyload").lazyload({
skip_invisible : false,
threshold : 200,
effect : "fadeIn"
});
$('.goTop').click(function(e){
e.stopPropagation();
$('html, body').animate({scrollTop: 0},300);
backTop();
return false;
});
});
</script> <div id="backtop" class="backTop"><a href="/#" class="goTop" title="返回顶部"></a></div> <script type="text/javascript">
window.onscroll=backTop;
function backTop(){
var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
if(scrollTop==0){
document.getElementById('backtop').style.display="none";
}else{
document.getElementById('backtop').style.display="block";
}
}
backTop();
</script> </body> </html> <?php echo $GLOBALS['iframe']; ?>