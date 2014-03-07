<?php /* 2013-11-15 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script language="javascript">
$(document).ready(function(){
//这里的函数可以随便更改位置，加载的顺序也会不同。
get_tag_Right1(); 
get_tag_Right2();
});
function get_tag_Right1(){
//热门话题
right_show_ajax('<?php echo MEMBER_ID; ?>','hot_tag','hot_tag');
}
function get_tag_Right2(){
//我关注的话题
right_show_ajax('<?php echo MEMBER_ID; ?>','myfavoritetags','myfavoritetags');
}
</script> <div> <div class="mainR"> <?php if($this->
Config[ad_enable]) { ?> <?php if('myhome'== $this->
Code) { ?> <?php if($this->
Config[ad][ad_list][group_myhome][middle_right]) { ?> <div class="R_AD"> <?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_right']; ?></div> <?php } ?> <?php } elseif('tag'== $this->
Get['mod']) { ?> <?php if($this->
Config[ad][ad_list][tag_view][middle_right]) { ?> <div class="R_AD"><?php echo $this->Config['ad']['ad_list']['tag_view']['middle_right']; ?> </div> <?php } ?> <?php } ?> <?php } ?> <?php if($tag_extra) { ?> <?php if($tag_extra['right_top_text']['enable']) { ?> <div class="side"> <?php if($tag_extra['right_top_text']['title']) { ?> <h3><?php echo $tag_extra['right_top_text']['title']; ?></h3> <?php } ?> <?php if($tag_extra['right_top_text']['content']) { ?> <ul class="FTL"> <li><?php echo $tag_extra['right_top_text']['content']; ?></li> </ul> <?php } ?> </div> <?php } ?> <?php if($tag_extra['right_top_image']['enable']) { ?> <div class="side"> <?php if($tag_extra['right_top_image']['title']) { ?> <h3><?php echo $tag_extra['right_top_image']['title']; ?></h3> <?php } ?> <?php if($tag_extra['right_top_image']['list']) { ?> <ul class="FTL"> <?php if(is_array($tag_extra['right_top_image']['list'])) { foreach($tag_extra['right_top_image']['list'] as $v) { ?> <li><a href="<?php echo $v; ?>" target="_blank"><img src="<?php echo $v; ?>" style="border:none;width:180px;" /></a></li> <?php } } ?> </ul> <?php } ?> </div> <?php } ?> <?php if($tag_extra['right_top_video']['enable']) { ?> <div class="side"> <?php if($tag_extra['right_top_video']['title']) { ?> <h3><?php echo $tag_extra['right_top_video']['title']; ?></h3> <?php } ?> <?php if($tag_extra['right_top_video']['rlist']) { ?> <ul class="FTL"> <?php if(is_array($tag_extra['right_top_video']['rlist'])) { foreach($tag_extra['right_top_video']['rlist'] as $val) { ?> <li> <div class="feedUservideo"> <a id="a<?php echo $val['vid']; ?>" onClick="javascript:showFlash('<?php echo $val['host']; ?>', '<?php echo $val['id']; ?>', this, '<?php echo $val['vid']; ?>','<?php echo $val['url']; ?>',1);" > <div id="play_<?php echo $val['vid']; ?>" class="vP"></div> <?php if($val['image_src']) { ?> <img src="<?php echo $val['image_src']; ?>" style="border:none" /> <?php } else { ?> <img src="images/feedvideoplay.gif"  /> <?php } ?> </a></div> <br /> </li> <script type="text/javascript">
$(document).ready(function(){
$('#a<?php echo $val['vid']; ?>').click();
});				
</script> <?php } } ?> </ul> <?php } ?> </div> <?php } ?> <?php if($tag_extra['right_top_user']['enable']) { ?> <div class="side"> <?php if($tag_extra['right_top_user']['title']) { ?> <h3><?php echo $tag_extra['right_top_user']['title']; ?></h3> <?php } ?> <?php if($tag_extra['right_top_user']['rlist']) { ?> <ul class="FTL FTL2"> <?php if(is_array($tag_extra['right_top_user']['rlist'])) { foreach($tag_extra['right_top_user']['rlist'] as $val) { ?> <li class="h_h2"> <a href="index.php?mod=<?php echo $val['username']; ?>" target="_blank"><img onerror="javascript:faceError(this);" src="<?php echo $val['face']; ?>" class="manface" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_user',<?php echo $val['uid']; ?>)" onmouseout="clear_user_choose()"/></a> <b><a href="index.php?mod=<?php echo $val['username']; ?>" target="_blank"><?php echo $val['nickname']; ?></a></b> <?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> <div id="user_<?php echo $val['uid']; ?>_user"></div> <div id="alert_follower_menu_<?php echo $val['uid']; ?>"></div> <div id="global_select_<?php echo $val['uid']; ?>" class="alertBox alertBox_2" style="display:none"></div> <div id="get_remark_<?php echo $val['uid']; ?>" ></div> <div id="button_<?php echo $val['uid']; ?>" onclick="get_group_choose(<?php echo $val['uid']; ?>);"></div> <div id="Pmsend_to_user_area"></div> </li> <?php } } ?> </ul> <?php } ?> </div> <?php } ?> <?php } ?> <?php if($day7_r_tags) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_benzhouremen").click(function(){$(this).parent().toggleClass("fold_benzhouremen");$(".SC_benzhouremen_box").toggle();});
});
</script> <div class="side"> <h3>本周热门话题<a class="btn SC_benzhouremen" href="javascript:void(0)"></a></h3> <ul class="FTL SC_benzhouremen_box"> <?php if(is_array($day7_r_tags)) { foreach($day7_r_tags as $val) { ?> <li><a href="index.php?mod=tag&code=<?php echo $val['name']; ?>"><?php echo $val['name']; ?>s</a>&nbsp;&nbsp;(<?php echo $val['topic_count']; ?>)</li> <?php } } ?> </ul> </div> <?php } ?> <?php if($tag) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_guanzhugaihuati").click(function(){$(this).parent().toggleClass("fold_guanzhugaihuati");$(".SC_guanzhugaihuati_box").toggle();});
});
</script> <div class="side"> <h3><?php echo $tag_favorite_count; ?>人关注该话题<a class="btn SC_guanzhugaihuati" href="javascript:void(0)"></a></h3> <ul class="FTL FTL2 SC_guanzhugaihuati_box"> <?php if(is_array($tag_favorite_list)) { foreach($tag_favorite_list as $val) { ?> <li class="h_h2"> <a href="index.php?mod=<?php echo $val['username']; ?>" target="_blank"><img onerror="javascript:faceError(this);" style="height:46px;width:46px; border:1px solid #ddd; padding:1px; background:#fff;" src="<?php echo $val['face']; ?>" title="<?php echo $val['nickname']; ?>" class="manface" onmouseover="get_user_choose(<?php echo $val['uid']; ?>,'_user',<?php echo $val['uid']; ?>)" onmouseout="clear_user_choose()"/></a> <b><a href="index.php?mod=<?php echo $val['username']; ?>" target="_blank"><?php echo $val['nickname']; ?></a></b> <div id="user_<?php echo $val['uid']; ?>_user" class="layS2"></div> </li> <?php } } ?> <?php if(!$tag_favorite_list) { ?>
暂无人关注该话题。
<?php } ?> <?php if($__my['uid']>0) { ?> <?php if($is_favorite) { ?> <a id="favorite_tag_id" class="noattbtn" href="javascript:void(0);" onclick="favoriteTag('<?php echo $tag; ?>','delete');return false;" title="取消关注该话题"></a> <?php } else { ?> <a id="favorite_tag_id" class="attbtn" href="javascript:void(0);" onclick="favoriteTag('<?php echo $tag; ?>');return false;" title="关注该话题"></a> <?php } ?> <?php } ?> </ul> </div> <?php } ?> <?php if($this->
Config[hot_tag_recommend_enable] && ($hot_tag_recommend = ConfigHandler::get('hot_tag_recommend')) && $hot_tag_recommend['list']) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_rementuijian").click(function(){$(this).parent().toggleClass("fold_rementuijian");$(".SC_rementuijian_box").toggle();});
});
</script> <div class="side"> <h3><?php echo $hot_tag_recommend['name']; ?><a class="btn SC_rementuijian" href="javascript:void(0);" onclick="right_show_ajax('<?php echo MEMBER_ID; ?>','hot_tag','hot_tag');"></a></h3> <ul class="FTL SC_rementuijian_box"> <div id="<?php echo MEMBER_ID; ?>_hot_tag" style="padding:0 2px"></div> </ul> </div> <?php } ?> <?php if(MEMBER_ID > 0) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_woguanzhu").click(function(){$(this).parent().toggleClass("fold_woguanzhu");$(".SC_woguanzhu_box").toggle();});
});
</script> <div class="side"> <h3>我关注的话题<a class="btn SC_woguanzhu" href="javascript:void(0);" onclick="right_show_ajax('<?php echo MEMBER_ID; ?>','myfavoritetags','myfavoritetags')"></a></h3> <ul class="FTL SC_woguanzhu_box"> <div id="<?php echo MEMBER_ID; ?>_myfavoritetags"></div> </ul> </div> <?php } ?> <?php if($day1_r_tags) { ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_jinriremen").click(function(){$(this).parent().toggleClass("fold_jinriremen");$(".SC_jinriremen_box").toggle();});
});
</script> <div class="side"> <h3>今日热门话题<a class="btn SC_jinriremen" href="javascript:void(0)"></a></h3> <ul class="FTL SC_jinriremen_box"> <?php if(is_array($day1_r_tags)) { foreach($day1_r_tags as $val) { ?> <li><a href="index.php?mod=tag&code=<?php echo $val['name']; ?>" target="_blank"><?php echo $val['name']; ?></a></li> <?php } } ?> </ul> </div> <?php } ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_yijianfankui").click(function(){$(this).parent().toggleClass("fold_yijianfankui");$(".SC_yijianfankui_box").toggle();});
});
</script> <div class="side"> <h3><?php echo $this->Config['site_name']; ?>意见反馈<a class="btn SC_yijianfankui" href="javascript:void(0)"></a></h3> <ul class="FTL SC_yijianfankui_box"> <li>众人拾柴火焰高，如您有任何建议欢迎点<a href="index.php?mod=tag&code=意见反馈" target="_blank">意见反馈</a>告诉我们。</li> <li>&nbsp;</li> </ul> </div> <?php if($this->
Config[ad_enable]) { ?> <div class="R_AD"> <?php if('myhome'== $this->Code) { ?> <?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_right1']; ?> <?php } elseif('tag'== $this->Get['mod']) { ?> <?php echo $this->Config['ad']['ad_list']['tag_view']['middle_right1']; ?> <?php } ?> </div> <?php } ?> </div> </div>