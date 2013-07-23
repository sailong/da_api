<?php /* 2013-03-13 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><div style="height:20px;"></div> <div class="topbg"> <span> <a href="index.php?mod=topic&code=add" title="发微博">发微博</a>  |  
<a href="#" id="top" title="返回顶部">返回顶部</a> </span> </div> <div class=""> <span> <a href="index.php?mod=topic&amp;code=new" title="最新微博">微博广场</a>  |   
<a href="index.php?mod=topic&amp;code=myhome" title="我的首页">我的首页</a> <?php if($member['uid'] == MEMBER_ID) { ?>
| <a href="index.php?mod=login&amp;code=logout">退出</a> <?php } ?> </span> </div> <div style="height:20px;"></div> <div><?php echo $this->Config['tongji']; ?><!--
<?php $__server_execute_time = round(microtime(true) - $GLOBALS['_J']['time_start'], 5) . " Second "; ?> <?php $__gzip_tips = ((defined('GZIP') && GZIP) ? "&nbsp;Gzip Enable." : "Gzip Disable."); ?> <span title="网页执行信息：<?php echo $__server_execute_time; ?>,<?php echo $__gzip_tips; ?>">
&nbsp; Execute：<?php echo $__server_execute_time; ?>,<?php echo $__gzip_tips; ?></span>
--> </div> <?php echo $GLOBALS['schedule_html']; ?> </body> </html> <?php if($this->MemberHandler) $this->MemberHandler->UpdateSessions(); ?>