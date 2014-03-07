<?php /* 2013-11-16 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> <html xmlns="http://www.w3.org/1999/xhtml"> <head> <?php $__my=$this->MemberHandler->MemberFields; ?> <base href="<?php echo $this->Config['site_url']; ?>/" /> <meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->Config['charset']; ?>" /> <meta http-equiv="x-ua-compatible" content="ie=7" /> <title><?php echo $this->Title; ?> - <?php echo $this->Config['site_name']; ?>(<?php echo $this->Config['site_domain']; ?>)</title> <meta name="Keywords" content="<?php echo $this->MetaKeywords; ?>,<?php echo $this->Config['site_name']; ?>" /> <meta name="Description" content="<?php echo $this->MetaDescription; ?>,<?php echo $this->Config['site_notice']; ?>" /> <script type="text/javascript">
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
a.artZoom2{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; 
a.artZoom3{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLink3 { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
a.artZoomAll{ cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_b.cur), pointer; }
.artZoomBox a.maxImgLinkAll { cursor:url(<?php echo $this->Config['site_url']; ?>/templates/default/images/magnifier_s.cur), pointer; }
.regU{ margin:0 0 20px}
.main_t{ background:#F6F6F6; height:35px;}
.regP{ float:none;}
</style> <div class="Rlogo"><h1 class="logo"><a title="<?php echo $this->Config['site_name']; ?>" href="index.php"></a></h1></div> <div class="main_2"> <div style="margin:30px;"> <div style="text-align:center; border-bottom:2px solid #555; margin-bottom:20px;"><h2 class="largeFont">《使用协议》</h2></div> <p> 使用网站，您必须同意遵守以下条款（使用条款）。 </p> <h3 class="fontL">基本条款 </h3> <ol class="listNumber"> <li>年龄必须达到15周岁以上。</li> <li>您必须为你自己注册用户下的行为负责。</li> <li>您有责任妥善保存你的密码。</li> <li>您不能使用本站的服务来进行任何非法或者未经授权的行为。</li> <li>本站点上发布的内容和禁止内容：</li> <ol class="listDefault" style="list-style:lower-alpha;"> <li>除了可识别个人的信息之外，因为它与可根据该信息（将遵守隐私政策来使用）来识别个人有关，任何您传输或在此网站上公布的资料都将视为非机密和非专有的。 我们对该类资料将不承担责任。 我们和我们指定的人员可自由复制、披露、散布、整合和以其他方式使用该类资料和所有数据、图像、声音、文本和其中包含的其他内容，以用作任何和全部商业或非商业目的。 </li> <li>本站有权审查并删除任何内容、邮件、照片或档案（统称为“内容”），只要本站经过合理判断，认为该类内容违反了本协议，或具有攻击性、违法性，或侵犯权利，损害或威胁会员安全。 </li> <li>您声称并保证您在任何时候公布的内容是 (a) 准确的、(b) 未违反本协议和 (c) 无论如何都不会伤害到任何人。 </li> <li>通过将内容公布到本站的公共区域，表示您同意，您自动授予，并且你声称并保证您有权授予本站一项不可取消的、永久的、非独占的、完全付费的、世界范围的许可，以使用、复制、执行、显示并散布该类信息和内容，也可准备该类信息和内容的衍生作品或整合到其他作品中，以及授予上述权利的再授权。 </li> <li>以下是违法的或本网站禁止内容种类的部分列表。 本站保留以下权利：随时修改该列表、根据自己独立的判断对违反本规定的任何人进行调查并采取适当的法律行动，包括但不限于，从本服务上删除侮辱性通信和终止该类侵犯者的会员身份。 包括的内容有： </li> <ol style="list-style:disc"> <li>对在线社区有明显攻击性，如宣传对任何团体或个人的种族歧视、偏见、憎恨或对任何群组或个人带来实际伤害的内容：</li> <li>未经第三人允许，构建其档案或使用其照片。</li> <li>骚扰或鼓动对其他人进行骚扰；</li> <li>参与传播“垃圾邮件”、“连锁信”或未经请求的大量邮件或“兜售信息”；</li> <li>宣传您知道是错误的、误导性信息，或宣传违法活动，或宣扬辱骂的、威胁性、猥亵、诽谤或损害他人名誉的行为；</li> <li>宣传其他人受版权保护作品的违法的、未经授权的副本，如提供盗版计算机程序或这些程序的链接、提供如何规避制造商所安装之副本保护设备的信息、或提供盗版音乐或这些盗版音乐文件夹的链接；</li> <li>包含受限的或需要密码才可访问的页面，或隐藏页面或图像（未链接至或未链接自其他可访问页面）； </li> <li>提供以性或暴力的方式利用 18周岁以下青少年的资料，或从低于 18 周岁的人那里索取个人信息；</li> <li>提供关于违法活动的指导性信息，如制造或购买非法武器、侵犯他人隐私、提供或制造计算机病毒；</li> <li>从其他用户处索取密码或个人标识信息，用作商业或非法之目的；</li> <li>以及未经我们事先书面许可，参加商业活动和/或销售活动，如竞赛、赌博、交易、广告和金字塔计划。</li> </ol> </ol> <li>使用本服务时，您必须遵守任何和全部的中华人民共和国适用法律、法规和行为守则。 </li> <li>您不应从事通过本服务向其他会员推销或请求其购买或出售任何产品或服务的行为，也不应因商业目的参加团体或其他社会活动或网络。 您不可以传播任何连锁信或垃圾邮件给其他会员。 </li> </ol> <h3 class="fontL">一般条款 </h3> <ol class="listNumber"> <li>我们保留以任何理由，在任何没有提前通知的情况下修改或终止本站服务的权利。 </li> <li>我们保留在任何时候改变这些服务条款的权利。如果对使用条款有重大的改变，我们将通过您帐户中最常用的电子邮件通知您。什么是“重大改变”取决于我们的诚信，使用常识和合理判断。 </li> <li>我们保留在任何时候，以任何理由向任何人拒绝提供服务的权利。 </li> <li>本站可以把本站上的图片和文字发布到外部网站。我们许可并鼓励这种方式。但是，发布到其它网站上的资料必须显示有返回到本站的链接。 </li> <li>我们保留收回企业或个人拥有法律权利或商标权的用户名称的权利。 </li> </ol> <h3 class="fontL">版  权</h3> <ol class="listNumber"> <li>我们要求您在本站上使用没有知识产权的材料。您上传的个人资料和材料仍然是您的。您可以在任何注销您的帐户的时候删除您的个人资料，同时也会删除任何您储存在系统中的文字和图片。</li> <li>我们鼓励用户向公众公开自己创作的作品或考虑接受逐步许可条款。 </li> </ol> <p>如有关于本协议的任何问题，请联系我们。 <br />
我已阅读了该协议，而且同意以上包含的各项规定。 </p> </div> </div><?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script type="text/javascript" src="templates/default/js/jsgst.js?build+20120829"></script> <div id="show_message_area"></div> <?php echo $this->js_show_msg(); ?> <?php echo $GLOBALS['schedule_html']; ?> <?php if($GLOBALS['jsg_schedule_mark'] || jsg_getcookie('jsg_schedule')) echo jsg_schedule(); ?> <div id="ajax_output_area"></div> <?php if(MEMBER_ID ==0) { ?> <style type="text/css">
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