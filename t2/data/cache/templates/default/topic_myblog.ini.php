<?php /* 2013-11-04 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> 
<div> <div class="mainR"> <?php if($this->Config['ad_enable']) { ?> <?php if('myhome'== $this->Code) { ?> <?php if($this->Config['ad']['ad_list']['group_myhome']['middle_right']) { ?> <div class="R_AD"> <?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_right']; ?></div> <?php } ?> <?php } elseif('tag'== $this->Get['mod']) { ?> <?php if($this->Config['ad']['ad_list']['tag_view']['middle_right']) { ?> <div class="R_AD"><?php echo $this->Config['ad']['ad_list']['tag_view']['middle_right']; ?></div> <?php } ?> <?php } ?> <?php } ?> <div id="topic_right_ajax_list"></div> <?php echo $this->hookall_temp['global_usernav_extra2']; ?> <?php if('pic' == $this->Get['type']) { ?> <script type="text/javascript">
$(document).ready(function(){
right_show_ajax('<?php echo $member['uid']; ?>','photo','photo');
});
</script> <div class="side"> <h3><a href="index.php?mod=topic&code=photo&uid=<?php echo $member['uid']; ?>">关注人的图片</a></h3> <div id="<?php echo $member['uid']; ?>_photo"></div> </div><?php } elseif('my_reply' == $this->Get['type']) { ?> <script type="text/javascript">
$(document).ready(function(){
right_show_ajax('<?php echo $member['uid']; ?>','mycomment_user','mycomment_user');
});
</script> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
本周我常评论的人
<?php } else { ?>本周<?php echo $member['gender_ta']; ?>常评论的人
<?php } ?> <a class="btn SC_to_user_tag" href="javascript:void(0);"></a> </h3> <ul class="FTL SC_to_user_tag_box"> <div id="<?php echo $member['uid']; ?>_mycomment_user"></div> </ul> </div><?php } elseif('video' == $this->Get['type']) { ?> <script type="text/javascript">
$(document).ready(function(){
right_show_ajax('<?php echo $member['uid']; ?>','video_content','video_content');
});
</script> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我关注人的视频
<?php } else { ?><?php echo $member['gender_ta']; ?>关注人的视频
<?php } ?> <a class="btn SC_to_user_tag" href="javascript:void(0);"></a> </h3> <ul class="FTL" id="<?php echo $member['uid']; ?>_video_content"></ul> </div><?php } elseif('music' == $this->Get['type']) { ?> <script type="text/javascript">
$(document).ready(function(){
right_show_ajax('<?php echo $member['uid']; ?>','music_user','music_user');
});
</script> <div class="side"> <h3>本月音乐达人
<a class="btn SC_to_user_tag" href="javascript:void(0);"></a> </h3> <ul class="FTL SC_to_user_tag_box"> <div id="<?php echo $member['uid']; ?>_music_user"></div> </ul> </div> <?php } else { ?> <?php if($member['vip_info']) { ?> <div class="side S15"> <div class="vipBox"> <div class="ico_vData"><img src="images/vip_c2.png" /></div> <p class="vipBox_info"><?php echo $member['vip_info']; ?></p> <p class="vipBox_info" style="text-align:right;"><a href="index.php?mod=other&code=vip_intro">点击申请认证</a></p> </div> </div> <?php } ?> <?php if(MEMBER_ID != $member['uid']) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_guanyu").click(function(){$(this).parent().toggleClass("fold_guanyu");$(".SC_guanyu_box").toggle();});
});
</script> <div class="side"> <h3>关于<?php echo $member['nickname']; ?><a class="btn SC_guanyu" href="javascript:void(0)"></a></h3> <ul class="FTL SC_guanyu_box"> <?php if($member['aboutme']) { ?> <li>&nbsp;<?php echo $member['aboutme']; ?></li><?php } elseif(MEMBER_ID==$member['uid'] && !$_GET['mod_original']) { ?><li><a href="index.php?mod=settings">快来写一句话</a>，向大家介绍一下吧</li> <?php } else { ?>这家伙很懒，什么都没留下。
<?php } ?> </ul> </div> <?php } ?> <script type="text/javascript">
$(document).ready(function(){
//属于他的标签
get_to_user_tag();
//他关注的人
get_user_follow();
//关注的话题
get_Right3();
$(".SC_to_user_tag").click(function(){$(this).parent().toggleClass("fold_to_user_tag");$(".SC_to_user_tag_box").toggle();});
$(".SC_woguanzhu").click(function(){$(this).parent().toggleClass("fold_woguanzhu");$(".SC_woguanzhu_box").toggle();});
});
function get_to_user_tag(){
//属于他的标签
right_show_ajax('<?php echo $member['uid']; ?>','to_user_tag','to_user_tag');
}
function get_user_follow(){
//他关注的人
right_show_ajax('<?php echo $member['uid']; ?>','user_follow','user_follow');
}
function get_Right3(){
right_show_ajax('<?php echo $member['uid']; ?>','myfavoritetags','right_myfavoritetags');
}
</script> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
属于我的标签
<?php } else { ?>属于<?php echo $member['gender_ta']; ?>的标签
<?php } ?> <a class="btn SC_to_user_tag" href="javascript:void(0);"></a> </h3> <ul class="FTL SC_to_user_tag_box"> <div id="<?php echo $member['uid']; ?>_to_user_tag"></div> </ul> </div> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我关注的人
<?php } else { ?><?php echo $member['gender_ta']; ?>关注的人
<?php } ?>
(<?php echo $member['follow_count']; ?>)
<a class="btn SC_taguanzhu" href="javascript:void(0)"></a> </h3> <ul class="FTL FTL2 SC_taguanzhu_box"> <div id="<?php echo $member['uid']; ?>_user_follow"></div> </ul> </div> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我关注的话题
<?php } else { ?><?php echo $member['gender_ta']; ?>关注的话题
<?php } ?> <a class="btn SC_woguanzhu" href="javascript:void(0);" ></a></h3> <ul class="FTL SC_woguanzhu_box"> <div id="<?php echo $member['uid']; ?>_right_myfavoritetags"></div> </ul> </div> <?php $qun_setting = ConfigHandler::get('qun_setting'); ?> <?php if($qun_setting['qun_open']) { ?> <script type="text/javascript">
$(document).ready(function(){
if(<?php echo $member['uid']; ?> > 0){
get_my_qun();
}
$(".SC_my_qun").click(function(){$(this).parent().toggleClass("fold_qun");$(".SC_qun_box").toggle();});
});
function get_my_qun(){
right_show_ajax('<?php echo $member['uid']; ?>','my_qun','qun_box');
}
</script> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我加入的微群
<?php } else { ?><?php echo $member['gender_ta']; ?>加入的微群
<?php } ?> <a class="btn SC_my_qun" href="javascript:void(0);" onclick="right_show_ajax('<?php echo $member['uid']; ?>','my_qun','qun_box'); return false;"> </a> </h3> <ul class="SC_qun_box"> <div id="<?php echo $member['uid']; ?>_qun_box"></div> </ul> </div> <?php } ?> <?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_yijianfankui").click(function(){$(this).parent().toggleClass("fold_yijianfankui");$(".SC_yijianfankui_box").toggle();});
});
</script> <div class="side"> <h3><?php echo $this->Config['site_name']; ?>意见反馈<a class="btn SC_yijianfankui" href="javascript:void(0)"></a></h3> <ul class="FTL SC_yijianfankui_box"> <li>众人拾柴火焰高，如您有任何建议欢迎点<a href="index.php?mod=tag&code=意见反馈" target="_blank">意见反馈</a>告诉我们。</li> <li>&nbsp;</li> </ul> </div> <?php if($member['uid']) { ?> <div id="add_remark_<?php echo $member['uid']; ?>" class="alertBox" style="display:none" > <ul class="manBox"> <li> <div class="tt1"> <span>设置备注名</span> <div class="mclose" onclick="getElementById('add_remark_<?php echo $member['uid']; ?>').style.display=(getElementById('add_remark_<?php echo $member['uid']; ?>').style.display=='none')?'':'none'"></div> </div> <div class="mWarp"> <form action="ajax.php?mod=topic&code=add_remark" method="POST"  name="remarkform"   onsubmit="publishSubmit_remark('remark_<?php echo $member['uid']; ?>',<?php echo $member['uid']; ?>);return false;"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/>
给朋友加个备注名，方便认出他是谁（0~6个字符）
<table > <tr> <td><input name="remark_<?php echo $member['uid']; ?>" type="text" id="remark_<?php echo $member['uid']; ?>" class="text-area2" value="<?php echo $buddys['remark']; ?>" size="6" maxlength="6"/> </td> <td align="left"> <input type="button" class="shareI" value="保 存" onclick="publishSubmit_remark('remark_<?php echo $member['uid']; ?>',<?php echo $member['uid']; ?>);return false;" /> </td> </tr> </table> </form> </div> </li> </ul> </div> <?php } ?> <?php } ?> </div> </div>