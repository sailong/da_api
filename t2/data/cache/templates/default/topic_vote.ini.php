<?php /* 2013-11-10 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> 
<div> <div class="mainR"> <?php if($this->Config['ad_enable']) { ?> <?php if('myhome'== $this->Code) { ?> <?php if($this->Config['ad']['ad_list']['group_myhome']['middle_right']) { ?> <div class="R_AD"> <?php echo $this->Config['ad']['ad_list']['group_myhome']['middle_right']; ?></div> <?php } ?> <?php } elseif('tag'== $this->Get['mod']) { ?> <?php if($this->Config['ad']['ad_list']['tag_view']['middle_right']) { ?> <div class="R_AD"><?php echo $this->Config['ad']['ad_list']['tag_view']['middle_right']; ?></div> <?php } ?> <?php } ?> <?php } ?> <?php echo $this->hookall_temp['global_usernav_extra2']; ?> <script type="text/javascript">
$(document).ready(function(){
<?php if('created' == $filter) { ?>
right_show_ajax('<?php echo $member['uid']; ?>','buddys_create_vote','buddys_create_vote');
<?php } ?> <?php if('joined' == $filter) { ?>
right_show_ajax('<?php echo $member['uid']; ?>','buddys_joined_vote','buddys_joined_vote');
<?php } ?>
right_show_ajax('<?php echo $member['uid']; ?>','recd_vote','recd_vote');
});
</script> <?php if('created' == $filter) { ?> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我关注人发起的投票
<?php } else { ?><?php echo $member['gender_ta']; ?>关注人发起的投票
<?php } ?> </h3> <ul id="<?php echo $member['uid']; ?>_buddys_create_vote" class="FTL"></ul> </div> <?php } ?> <?php if('joined' == $filter) { ?> <div class="side"> <h3> <?php if($member['uid']==MEMBER_ID) { ?>
我关注人参与的投票
<?php } else { ?><?php echo $member['gender_ta']; ?>关注人参与的投票
<?php } ?> </h3> <ul id="<?php echo $member['uid']; ?>_buddys_joined_vote" class="FTL"></ul> </div> <?php } ?> <div class="side"> <h3>热点推荐</h3> <ul id="<?php echo $member['uid']; ?>_recd_vote" class="FTL"></ul> </div> <?php /* 2013-07-19 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?> <script type="text/javascript">
$(document).ready(function(){
$(".SC_yijianfankui").click(function(){$(this).parent().toggleClass("fold_yijianfankui");$(".SC_yijianfankui_box").toggle();});
});
</script> <div class="side"> <h3><?php echo $this->Config['site_name']; ?>意见反馈<a class="btn SC_yijianfankui" href="javascript:void(0)"></a></h3> <ul class="FTL SC_yijianfankui_box"> <li>众人拾柴火焰高，如您有任何建议欢迎点<a href="index.php?mod=tag&code=意见反馈" target="_blank">意见反馈</a>告诉我们。</li> <li>&nbsp;</li> </ul> </div> <?php if($member['uid']) { ?> <div id="add_remark_<?php echo $member['uid']; ?>" class="alertBox" style="display:none" > <ul class="manBox"> <li> <div class="tt1"> <span>设置备注名</span> <div class="mclose" onclick="getElementById('add_remark_<?php echo $member['uid']; ?>').style.display=(getElementById('add_remark_<?php echo $member['uid']; ?>').style.display=='none')?'':'none'"></div> </div> <div class="mWarp"> <form action="ajax.php?mod=topic&code=add_remark" method="POST"  name="remarkform"   onsubmit="publishSubmit_remark('remark_<?php echo $member['uid']; ?>',<?php echo $member['uid']; ?>);return false;"> <input type="hidden" name="FORMHASH" value='<?php echo FORMHASH; ?>'/>
给朋友加个备注名，方便认出他是谁（0~6个字符）
<table > <tr> <td><input name="remark_<?php echo $member['uid']; ?>" type="text" id="remark_<?php echo $member['uid']; ?>" class="text-area2" value="<?php echo $buddys['remark']; ?>" size="6" maxlength="6"/> </td> <td align="left"> <input type="button" class="shareI" value="保 存" onclick="publishSubmit_remark('remark_<?php echo $member['uid']; ?>',<?php echo $member['uid']; ?>);return false;" /> </td> </tr> </table> </form> </div> </li> </ul> </div> <?php } ?> </div> </div>