<?php /* 2013-03-13 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?><!--微博管理-->
<div class="t_manger"> 
  <span class="p_time">
  <?php echo $val['dateline']; ?> <?php echo $val['from_string']; ?>
  </span> 
  <span>
  
<?php if(MEMBER_ID>0) { ?>
  
<?php if(!$val['reply_disable']) { ?>
   <a href="index.php?mod=topic&amp;code=<?php echo $val['tid']; ?>">评论(<?php echo $val['replys']; ?>)</a>
  
<?php } ?>
  
<?php $tpid=$val['top_parent_id']; if ($tpid&&$parent_list[$tpid]) $root_type=$parent_list[$tpid]['type']; ?>
  
<?php if((!isset($root_type)) || (isset($root_type) && in_array($root_type, get_topic_type('forward')))) { ?>
	  
<?php if(in_array($val['type'], get_topic_type('forward'))) { ?>
		<a href="index.php?mod=topic&amp;code=forward&tid=<?php echo $val['tid']; ?>">(转发<?php echo $val['forwards']; ?>)</a>
	  
<?php } else { ?>
<?php if(isset($val['fansgroup'])) { ?>
		<span><?php echo $val['fansgroup']; ?></span>
<?php } else { ?><span title="设置了特殊的权限，不允许转发">转发</span>
		
<?php } ?>
  
<?php } ?>
  
<?php } ?>
  
 
<?php } ?>
  
<?php if($val['uid']==MEMBER_ID || 'admin'==MEMBER_ROLE_TYPE) { ?>
  <a href="index.php?mod=topic&amp;code=del&amp;tid=<?php echo $val['tid']; ?>">删除</a>   
  
<?php if(time() - $val['addtime'] < 3600 || 'admin'==MEMBER_ROLE_TYPE) { ?>
  
<?php if($val['replys'] <= 0 && $val['forwards'] <= 0 || 'admin'==MEMBER_ROLE_TYPE) { ?>
  <a href="index.php?mod=topic&amp;code=modify&amp;tid=<?php echo $val['tid']; ?>">编辑</a>
  
<?php } ?>
  
<?php } ?>
  
<?php } ?>
  
  
<?php if($this->Code == 'myfavorite') { ?>
  <a href="index.php?mod=topic&amp;code=favorite&amp;tid=<?php echo $val['tid']; ?>&amp;act=delete">取消收藏</a>
  
<?php } else { ?>  <a href="index.php?mod=topic&amp;code=favorite&amp;tid=<?php echo $val['tid']; ?>&amp;act=add">收藏</a>
  
<?php } ?>
  </span>
</div>
<!--微博管理-->