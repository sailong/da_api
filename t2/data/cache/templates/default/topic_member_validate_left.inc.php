<?php /* 2013-11-04 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><script language="javascript">
var vote_open = '<?php echo $this->Config['vote_open']; ?>';
$(document).ready(function(){
//这里的函数可以随便更改位置，加载的顺序也会不同。
get_validate_remark(); 
get_validate_cement();
get_validate_link();
get_validate_video();
if(vote_open){
get_validate_vote();
}
});
function get_validate_remark(){
//认证 专题 简介
right_show_ajax('<?php echo $member['uid']; ?>','validate_remark','remark_content','validate');
}
function get_validate_cement(){
//认证 专题 公告
right_show_ajax('<?php echo $member['uid']; ?>','validate_cement','cement_content','validate');
}
function get_validate_link(){
//认证 专题 公告
right_show_ajax('<?php echo $member['uid']; ?>','validate_link','link_content','validate');
}
function get_validate_video(){
//认证 专题 视频
right_show_ajax('<?php echo $member['uid']; ?>','validate_video','video_content','validate');
}
function get_validate_vote(){
//认证 专题 投票
right_show_ajax('<?php echo $member['uid']; ?>','validate_vote','vote_content','validate');
}
</script> <div> <div class="mainR"> <script type="text/javascript">
$(document).ready(function(){
$(".SC_benzhouremen").click(function(){$(this).parent().toggleClass("fold_benzhouremen");$(".SC_benzhouremen_box").toggle();});
});
</script> <div id="<?php echo $member['uid']; ?>_remark_content"></div> <script type="text/javascript">
$(document).ready(function(){
$(".SC_benzhouremen").click(function(){$(this).parent().toggleClass("fold_benzhouremen");$(".SC_benzhouremen_box").toggle();});
});
</script> <div id="<?php echo $member['uid']; ?>_link_content"></div> <script type="text/javascript">
$(document).ready(function(){
$(".SC_benzhouremen").click(function(){$(this).parent().toggleClass("fold_benzhouremen");$(".SC_benzhouremen_box").toggle();});
});
</script> <div id="<?php echo $member['uid']; ?>_cement_content"></div> <script type="text/javascript">
$(document).ready(function(){
$(".SC_benzhouremen").click(function(){$(this).parent().toggleClass("fold_benzhouremen");$(".SC_benzhouremen_box").toggle();});
});
</script> <div id="<?php echo $member['uid']; ?>_video_content"></div> <div id="<?php echo $member['uid']; ?>_vote_content"></div> <div id="alert_follower_menu_<?php echo $member['uid']; ?>" style="display:none"></div> </div> </div>