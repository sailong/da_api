<?php /* 2013-11-11 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><div id="apply_qun_wp" style="display:none;"> <div class="dialog_inner"> <div>此群需要申请后才可以加入</div> <div style="text-align:right;"> <ul class="mycon fontGreen"> <?php if($this->Config['topic_input_length']>0) { ?> <li>还可以输入</li> <li style="width:auto"><span style='color:#ff0000;' id="apply_msg_wordc"><?php echo $this->Config['topic_input_length']; ?></span></li> <li style="width:40px;">个字符</li> <?php } ?> </ul> </div> <div> <textarea name="apply_msg" id="apply_msg" style="width:450px;" onkeyup="checkPublishText('<?php echo $this->Config['topic_input_length']; ?>', 'apply_msg', 'apply_msg_wordc');"></textarea> </div> <div style="margin-top:10px;"> <input id="apply_qun_btn" type="button" value="申 请" class="shareI"/> <input id="cancel_btn" type="button" value="取 消" onclick="" class="shareI"/> </div> </div> </div>