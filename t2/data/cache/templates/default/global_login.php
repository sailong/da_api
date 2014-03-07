<?php /* 2013-11-15 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?>
<?php $__my=$this->MemberHandler->MemberFields; ?> <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <?php $__my=$this->MemberHandler->MemberFields; ?> <base href="<?php echo $this->Config['site_url']; ?>/" /> <?php $conf_charset=$this->Config['charset']; ?> <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $conf_charset; ?>" /> <meta http-equiv="x-ua-compatible" content="ie=7" /> <title><?php echo $this->Title; ?> - <?php echo $this->Config['site_name']; ?>(<?php echo $this->Config['site_domain']; ?>)</title> <meta name="Keywords" content="<?php echo $this->MetaKeywords; ?>,<?php echo $this->Config['site_name']; ?>" /> <meta name="Description" content="<?php echo $this->MetaDescription; ?>,<?php echo $this->Config['site_notice']; ?>" /> <script type="text/javascript">
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
</script> <link rel="shortcut icon" href="favicon.ico" > <link href="templates/default/styles/main.css?build+20120829" rel="stylesheet" type="text/css" /> <link href="templates/default/styles/reg.css?build+20120829" rel="stylesheet" type="text/css" /> <script type="text/javascript" src="templates/default/js/min.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/common.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/reg.js?build+20120829"></script> <script type="text/javascript" src="templates/default/js/validate.js?build+20120829"></script> <style type="text/css">
a.artZoom{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLink { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
a.artZoom2{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
a.artZoom3{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLink3 { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
a.artZoomAll{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLinkAll { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
.regU{ margin:0 0 20px}
.main_t{ background:#F6F6F6; height:35px;}
.regP{ float:none;}
</style> <div class="Rlogo"><h1 class="logo"><a title="<?php echo $this->Config['site_name']; ?>" href="index.php"></a></h1></div> <div class="main_2"> <div class="main_t"><span style="padding-left:20px;">已注册会员请登录</span></div> <div class="set_warp Nlogin"> <div class="Nll"> <?php $login_extract=jsg_member_login_extract(); ?> <?php if($login_extract) { ?> <?php $login_extract_forms=$login_extract['login_forms']; ?> <form method="<?php echo $login_extract_forms['method']; ?>" action="<?php echo $login_extract_forms['action']; ?>"> <?php if(is_array($login_extract_forms['hidden_inputs'])) { foreach($login_extract_forms['hidden_inputs'] as $v) { ?> <input type="hidden" name="<?php echo $v['name']; ?>" value="<?php echo $v['value']; ?>" /> <?php } } ?> <table width="100%" border="0"> <tr> <td width="30%" align="right" valign="top">帐户/昵称：</td> <td width="70%"><input name="<?php echo $login_extract_forms['username_input']['name']; ?>" type="text"  class="regP"/> </td> </tr> <tr> <td align="right" valign="top">登录密码：</td> <td><input name="<?php echo $login_extract_forms['password_input']['name']; ?>" type="password" class="regP" /></td> </tr> <tr> <td align="right" valign="middle">&nbsp;</td> <td> <input name="<?php echo $login_extract_forms['submit_input']['name']; ?>" type="submit" value="<?php echo $login_extract_forms['submit_input']['value']; ?>" class="Nbtn_login" /> <div class="th_login"> </div> </td> </tr> </table> </form> <?php } else { ?><form method="POST"  action="<?php echo $action; ?>"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/> <table width="100%" border="0"> <tr> <td width="30%" align="right" valign="top">帐户/昵称：</td> <td width="70%"><input name="username" type="text"  class="regP"/><span class="retip">可用“帐号昵称”或注册Email登录</span> </td> </tr> <tr> <td align="right" valign="top">登录密码：</td> <td><input name="password" type="password" class="regP" /></td> </tr> <?php if($this->Config['seccode_login']) { ?> <tr> <td align="right" valign="top">验证码：</td> <td> <div class="ml10"> <input name="seccode" id="seccode_input" type="text" class="regP" style="width:80px;"/> </div> <div class="ml11"> <script language="javascript">seccode({"id":"seccode_input"});</script> <a href="javascript:updateSeccode('seccode_input');">换一换</a> </div> </td> </tr> <?php } ?> <tr> <td align="right" valign="middle">&nbsp;</td> <td class="retip"><input type="checkbox" class="checkb" checked title="请不要在公共电脑上使用自动登录功能">
下次自动登录&nbsp;&nbsp; <a onclick="window.location.href='<?php echo $this->Config['site_url']; ?>/index.php?mod=get_password';return false;" href="javascript:void(0)" title="点此可通过2种方式重设密码">忘记密码？</a></td> </tr> <tr> <td align="right" valign="middle"></td> <td> <input name="" type="submit" value="" class="Nbtn_login" /> <div class="th_login">或用如下帐号登录：<br> <?php if($this->Config['sina_enable'] && sina_weibo_init()) { ?>
&nbsp;
<?php echo sina_weibo_login('b'); ?> <?php } ?> <?php if($this->Config['qqwb_enable'] && qqwb_init()) { ?>
&nbsp; 
<?php echo qqwb_login('b'); ?> <?php } ?> <?php if($this->Config['yy_enable'] && yy_init()) { ?>
&nbsp; 
<?php echo yy_login('b'); ?> <?php } ?> <?php if($this->Config['renren_enable'] && renren_init()) { ?>
&nbsp; 
<?php echo renren_login('b'); ?> <?php } ?> <?php if($this->Config['kaixin_enable'] && kaixin_init()) { ?>
&nbsp;
<?php echo kaixin_login('b'); ?> <?php } ?> <?php if($this->Config['fjau_enable'] && fjau_init()) { ?>
&nbsp;
<?php echo fjau_login('b'); ?> <?php } ?> </div> </td> </tr> </table> </form> <?php } ?> </div> <div class="Nlr"> <span class="font14px">还没注册过本站帐户？</span> <a title="注册<?php echo $this->Config['site_name']; ?>" onclick="window.location.href='<?php echo $this->Config['site_url']; ?>/index.php?mod=member';return false;" class="Nbtn_reg">注册<?php echo $this->Config['site_name']; ?></a>
注册后，可以方便地分享新鲜事，关注用户分享；
并可通过<b>手机</b>随时随地参与互动。
</div> </div> </div><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script type="text/javascript" src="templates/default/js/jsgst.js?build+20120829"></script> <div id="show_message_area"></div> <?php echo $this->js_show_msg(); ?> <?php echo $GLOBALS['schedule_html']; ?> <?php if($GLOBALS['jsg_schedule_mark'] || jsg_getcookie('jsg_schedule')) echo jsg_schedule(); ?> <div id="ajax_output_area"></div> <?php if(MEMBER_ID ==0) { ?> <style type="text/css">
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