<?php /* 2013-03-13 in jishigou invalid request template */ if(!defined("IN_JISHIGOU")) exit("invalid request"); ?>
<link href="templates/default/css/style1.css" rel="stylesheet" type="text/css" />

<div id="wary">
   <div class="he_one">
    <div class="logo fl"><a href="#"><img src="templates/default/images/logo.jpg" width="129" height="42" /></a></div>
    <div class="re fr"><p>客服热线：<span class="yanse">400 810 9966</span></p>
    <p style="float:right; padding-right:20px;"> <a href="#"></a>  <a href="#"></a> </p></div>
 </div>
 <!--nav-->
 <div id="nav">
	  <div id="nav_one">
		<ul>
			<li>
			<A href="/wap/index.php">首页</A></li>
			<li><A href="/wap/index.php?mod=news&ac=group_list&groupid=25">赛事</A></li>
			<!--<li><A href="/wap/index.php?mod=news&ac=group_list&groupid=26">旅游</A></li>-->
			<!--<li><A href="/wap/index.php?mod=news&ac=group_list&groupid=22">球具</A></li>-->	
			<li><A href="/wap/index.php?mod=news&ac=group_list&groupid=21">球场 </A></li>
            <li style="width:94px; text-align:center;"><A href="/wap/index.php?mod=news&ac=group_list&uid=1889180">手机报 </A></li>
		</ul>
	  </div>
	  <div id="nav_two">
			<ul>
				
                <!--<li><A href="/wap/index.php?mod=news&ac=group_list&groupid=23">教学</A></li> -->
				<li><A href="/weibo/wap/">微博</A></li>
				<li><A href="/wap/index.php?mod=news&ac=group_list&uid=1889013">资讯</A></li>
				<li style="float: left;width: 94px;height: 22px;line-height: 22px;text-align: center;display: block;margin-top: 2px;">
				   <A href="/wap/index.php?mod=news&ac=group_list&groupid=20">品牌俱乐部</A>
				</li>
			</ul>
	  </div>
 </div>
 <!--/nav-->
 <!--/nav-->
  <div id="centenr">
      <div id="cen1" style="height:auto;">
         <div id="con1_s"></div>
			<div id="con1_t" style="height:auto;">
				   <div id="t_bok">
<?php echo '<'.'?xml version="1.0" encoding="utf-8"?'.'>'; ?>

<?php $__my=$this->MemberHandler->MemberFields; ?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
<head>
<base href="<?php echo $this->Config['wap_url']; ?>/" />
<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8"/>
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0"/>
<meta name="MobileOptimized" content="236"/>
<meta http-equiv="Cache-Control" content="no-cache"/>
<title><?php echo $this->Title; ?> - 
<?php echo array_iconv($this->Config['charset'],'utf-8',$this->Config['site_name']); ?>
微博手机版 - 
<?php echo array_iconv($this->Config['charset'],'utf-8',$this->Config['page_title']); ?>
</title>
<link href="templates/default/images/wap_style.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php if(MEMBER_ID) { ?>
	<div class="logo">
	<a href="index.php"><img src="<?php echo $this->Config['site_url']; ?>/wap/templates/default/images/wap_logo.gif" /></a>
	</div>
<?php $member=($member ? $member : $this->MemberHandler->MemberFields); ?>
<div class="topbg">
	<span>
<?php if($this->Code == 'new') $new_hb = 'hb'; ?>

<?php if($this->Code == 'myhome') $myhome_hb = 'hb'; ?>

<?php if($this->Code == 'myat') $myat_hb = 'hb'; ?>

<?php if($this->Code == 'mycomment') $reply_hb = 'hb'; ?>

<?php if($this->Get['mod'] == 'tag') $tag_hb = 'hb'; ?>

<?php if($this->Get['mod'] == 'pm') $mypm = 'hb'; ?>
 
<?php if($this->Code == 'myfavorite' || $this->Code == 'favoritemy') $myfavorite = 'hb'; ?>
 

	<a href="index.php?mod=topic&amp;code=new" title="最新微博"><span class="<?php echo $new_hb; ?>">广场</span></a>|
	<a href="index.php?mod=tag"><span class="<?php echo $tag_hb; ?>">话题</span></a>|
	<a href="index.php?mod=topic&amp;code=myhome" title="我的首页"><span class="<?php echo $myhome_hb; ?>">首页</span></a>|
	<a href="index.php?mod=topic&amp;code=myat" title="@提到我的"><span class="<?php echo $myat_hb; ?>">@我</span></a>|
	<a href="index.php?mod=topic&amp;code=mycomment" title="评论我的"><span class="<?php echo $reply_hb; ?>">评论</span></a> |
	<a href="index.php?mod=pm" title="站内私信"><span class="<?php echo $mypm; ?>">私信</span></a> |
	<a href="index.php?mod=topic&amp;code=myfavorite" title="我的收藏"><span class="<?php echo $myfavorite; ?>">收藏</span></a>

	</span>
	</div>
<?php if(in_array($this->Code,array('myhome','myblog','fans','follow')) || $params['code'] == 'myblog' || $this->Code > 0) { ?>
	<div class="u">
		<span>
			<a href="index.php"><img onerror="javascript:faceError(this);" src="<?php echo $member['face']; ?>" style="width:45px; height:45px;padding:1px;border:1px solid #ccc; background:#fff;"/></a>
			<a href="index.php?mod=<?php echo $member['username']; ?>"><?php echo $member['nickname']; ?></a>
<?php if($member['uid'] == MEMBER_ID) { ?>
(<a href="index.php?mod=login&amp;code=logout">退出</a>)
<?php } ?>
<br />
<?php if($this->Code == 'follow') $follow_hb = 'hb'; ?>
 
<?php if($this->Code == 'fans') $fans_hb = 'hb'; ?>
 
<?php if($topic_selected == 'myblog') $myblog_hb = 'hb'; ?>
 
		  <span class="<?php echo $follow_hb; ?>"><a href="index.php?mod=<?php echo $member['username']; ?>&amp;code=follow">关注<?php echo $member['follow_count']; ?>人</a> |</span> 
		  <span class="<?php echo $fans_hb; ?>"><a href="index.php?mod=<?php echo $member['username']; ?>&amp;code=fans">粉丝<?php echo $member['fans_count']; ?>人</a></span><span class="<?php echo $fans_hb; ?>"> | </span> <span class="<?php echo $myblog_hb; ?>"><a href="index.php?mod=<?php echo $member['username']; ?>">微博<?php echo $member['topic_count']; ?>条</a></span><span class="<?php echo $fans_hb; ?>"> | </span> <span class="<?php echo $myblog_hb; ?>"><a href="/nd/nd.php?uid=<?php echo $member['uid']; ?>">实时比分</a></span> <br />
		  
<?php if($this->Code != 'fans' && $this->Code != 'follow') { ?>
		  <?php echo $member['follow_html']; ?>
		  
<?php } ?>
</span> 
	</div>
<?php } ?>
<?php if($this->Code == 'pmsend' || $this->Get['mod'] == 'pm') { ?>
	<div style="padding:2px;">
<?php if($this->Code == '') $hb_mypm = 'hb'; ?>
 
<?php if($this->Code == 'pmsend') $hb_addpm = 'hb'; ?>
 
		<!-- <span class="<?php echo $hb_mypm; ?>"><a href="index.php?mod=pm">我的私信</a></span> -->
		<p>
		<span class="<?php echo $hb_addpm; ?>"><a href="index.php?mod=pm&amp;new=weidu">未读私信</a></span>|
		<span class="<?php echo $hb_addpm; ?>"><a href="index.php?mod=pm&amp;new=yidu">已读私信</a></span>|
		<span class="<?php echo $hb_addpm; ?>"><a href="index.php?mod=pm&amp;code=pmsend">发送私信</a></span>
		</p>
	</div>
	
<?php } ?>

<?php if($member['uid'] == MEMBER_ID) { ?>
	<div class="t_ttip">
<?php if($member['comment_new']>0) { ?>
	<a href="index.php?mod=topic&code=mycomment"><?php echo $member['comment_new']; ?>条评论</a>，
	
<?php } ?>

<?php if($member['at_new']>0) { ?>
	<a href="index.php?mod=topic&code=myat"><?php echo $member['at_new']; ?>人@我</a>，
	
<?php } ?>

<?php if($member['newpm'] >0) { ?>
	<a href="index.php?mod=pm"><?php echo $member['newpm']; ?>条私信</a>，
	
<?php } ?>

<?php if($member['fans_new']>0) { ?>
	<a href="index.php?mod=<?php echo $__my['username']; ?>&code=fans"><?php echo $member['fans_new']; ?>人关注了我</a>
	
<?php } ?>
</div> 
	
<?php } ?>
<?php if(in_array($this->Code,array('myhome','new')) || !empty($tag_value)) { ?>
	<div class="m">
		<div style="padding:2px;">随便说说：(<?php echo $this->Config['topic_length']; ?>字以内)</div>
			<div class="u2">
			<form action="index.php?mod=topic&amp;code=do_add" method="post" enctype="multipart/form-data" name="forminfo" id="forminfo">
				 <div>
<?php $i_already_value = $tag_value ? $tag_value : ''; ?>
<textarea name="content" id="content" style="width:98%;" rows="2" cols="" /><?php echo $i_already_value; ?></textarea>
				 </div>
				<input name="topictype" type="hidden" id="topictype" value="first" />
				 <div style="margin-top:3px;">
					 
<?php if($this->Config['sina_enable'] && sina_weibo_init()) { ?>
<?php echo array_iconv($this->Config['charset'],'utf-8',sina_weibo_syn()); ?>
 
<?php } ?>

<?php if($this->Config['qqwb_enable'] && qqwb_init()) { ?>
<?php echo array_iconv($this->Config['charset'],'utf-8',qqwb_syn()); ?>
<?php } ?>

<?php if($this->Config['kaixin_enable'] && kaixin_init()) { ?>
<?php echo array_iconv($this->Config['charset'],'utf-8',kaixin_syn_html()); ?>
<?php } ?>

<?php if($this->Config['renren_enable'] && renren_init()) { ?>
<?php echo array_iconv($this->Config['charset'],'utf-8',renren_syn_html()); ?>
<?php } ?>
 </div>
				 <div>
				 <input name="tag" type="hidden" id="tag" value="<?php echo $tag_value; ?>" />
				 <input type="submit" name="addTopic" value="发布" />	
				 <input type="submit" name="addPic" value="插图片" />	
				 </div>
				<input name="return_code" type="hidden" id="return_code" value="mod=<?php echo $this->Get['mod']; ?>&code=<?php echo $this->Code; ?>" />
				<input type="hidden" />
			</form> 
			</div>
	</div>
	
<?php } ?>
<?php } else { ?><div class="logo">
		<a href="index.php"><img src="<?php echo $this->Config['site_url']; ?>/wap/templates/default/images/wap_logo.gif" /></a>
	</div>
	<div class="topbg">
		<span>
		<a href="index.php?mod=topic&amp;code=new" title="最新微博">广场</a>|
		<a href="index.php?mod=tag">话题</a>|
		<a href="index.php?mod=login">登录</a>|
		<a href="index.php?mod=member"><font color="red">免费注册</font></a>
		</span>
	</div>
<?php } ?></div>
					   <div class="nav_u">
							   <ul>	
							      <div class="u2">
											<div style="padding:2px;">
<?php if($params['code'] == myblog ) { ?>
<?php if(MEMBER_ID == $member['uid']) $_my = '我'; else $_my = $member['nickname']; ?>
<?php } ?>

<?php if($this->Code == 'myhome') { ?>
												<span>我的首页</span><?php } elseif($this->Code == 'myat') { ?><span>提到我的</span>
												
<?php } ?>

<?php if(in_array($this->Code,array('mycomment','tocomment'))) { ?>
<?php if($this->Code == 'mycomment') $mycomment = 'hb'; ?>
 
<?php if($this->Code == 'tocomment') $tocomment = 'hb'; ?>
 
												<span class="<?php echo $mycomment; ?>"><a href="index.php?mod=topic&code=mycomment">评论我的</a></span>
												<span class="<?php echo $tocomment; ?>" style="margin-left:10px;"><a href="index.php?mod=topic&code=tocomment">我评论的</a></span>
												
<?php } ?>

<?php if(in_array($this->Code,array('myfavorite','favoritemy'))) { ?>
<?php if($this->Code == 'myfavorite') $my_favorite = 'hb'; ?>
 
<?php if($this->Code == 'favoritemy') $favoritemy = 'hb'; ?>
 
												<span class="<?php echo $my_favorite; ?>"><a href="index.php?mod=topic&amp;code=myfavorite">我收藏的</a></span>
												<span class="<?php echo $favoritemy; ?>"><a href="index.php?mod=topic&amp;code=favoritemy">收藏我的</a></span>
												
<?php } ?>
</div>
										</div>	

										<div class="m">
										  <div>
											<!--列表开始-->
											<div>
											  
<?php if($topic_list) { ?>
											  <!-- 收藏我的开始 -->
											  
<?php if('favoritemy'==$this->
											  Code) { ?>
											  
<?php if(is_array($topic_list)) { foreach($topic_list as $val) { ?>
											  
<?php $counts++; ?>
  <!--列表区块开始-->
											  <div>
												<div>
												  <!--右边主体-->
												  <div>
													<!--微博主体-->
													<div>
													  <div> <span><a href="index.php?mod=<?php echo $val['username']; ?>"><?php echo $val['nickname']; ?></a>
<?php if($val['validate_html']) { ?>
														<img class='vipImg' title='<?php echo $val['vip_info']; ?>' src='<?php echo $this->Config['site_url']; ?>/images/vip.gif' />
														
<?php } ?>
<?php echo $val['content']; ?></span> <a href="index.php?mod=topic&amp;code=<?php echo $val['tid']; ?>" target="_blank">原文评论
<?php if($val['replys']) { ?>
														<?php echo $val['replys']; ?>
														
<?php } ?>
</a> <a href="index.php?mod=topic&amp;code=<?php echo $val['tid']; ?>" target="_blank">原文转发
<?php if($val['forward']) { ?>
														<?php echo $val['forward']; ?>
														
<?php } ?>
</a> </div>
													</div>
													<!--微博管理-->
												  </div>
												  <!--右边主体结束-->
												</div>
											  </div>
											  <!--列表区块结束-->
											  
<?php } } ?>
  <!-- 收藏我的结束 -->
											  
<?php } else { ?>  
<?php if(is_array($topic_list)) { foreach($topic_list as $val) { ?>
											  
<?php $counts++; ?>
  <!--列表区块开始-->
											  <div>
												<div style="padding:2px;">
												  <div class="t_list_b">
													<!--微博主体-->
													<div> <a title="<?php echo $val['username']; ?>" href="index.php?mod=<?php echo $val['username']; ?>"><?php echo $val['nickname']; ?></a>
													  
<?php if($val['validate_html']) { ?>
													  <img class='vipImg' title='<?php echo $val['vip_info']; ?>' src='<?php echo $this->Config['site_url']; ?>/images/vip.gif' />
													  
<?php } ?>
  :<span><?php echo $val['content']; ?></span> 
<?php if($val['longtextid']) { ?>
 <a href="index.php?mod=topic&amp;code=<?php echo $val['tid']; ?>">[查看全文]</a>
<?php } ?>
<br />
													</div>
<?php if($val['imageid'] && $val['image_list']) { ?>
													<div class="t_list_b"> 
<?php if(is_array($val['image_list'])) { foreach($val['image_list'] as $iv) { ?>
														<a href="index.php?mod=topic&code=<?php echo $val['tid']; ?>"><img src="<?php echo $iv['image_small']; ?>" /></a> 
														
<?php } } ?>
</div>
													
<?php } ?>

<?php if(($tpid=$val['top_parent_id'])>0) { ?>
													<div class="t_list_b">
													  <div class="transpond">
<?php if(($pt=$parent_list[$tpid])) { ?>
															<p>
																<span>
																<a href="index.php?mod=<?php echo $pt['username']; ?>"><?php echo $pt['nickname']; ?></a>
<?php if($pt['validate_html']) { ?>
<img class='vipImg' title='<?php echo $pt['vip_info']; ?>' src='<?php echo $this->Config['site_url']; ?>/images/vip.gif' />
<?php } ?>
: 
																<?php echo $pt['content']; ?> 
																</span>
															</p>
<?php if($pt['imageid'] && $pt['image_list']) { ?>
															<div class="Im">
<?php if(is_array($pt['image_list'])) { foreach($pt['image_list'] as $iv) { ?>
																<img src="<?php echo $iv['image_small']; ?>" />
																
<?php } } ?>
</div>
															
<?php } ?>
<a href="index.php?mod=topic&amp;code=<?php echo $tpid; ?>">原文评论(<?php echo $pt['replys']; ?>)</a> <a href="index.php?mod=topic&amp;code=<?php echo $tpid; ?>">原文转发(<?php echo $pt['forwards']; ?>)</a>
<?php } else { ?><?php $val['reply_disable']=0; ?>
<span>原始微博已删除</span>
														
<?php } ?>
  </div>
													</div>
													
<?php } ?>
<!--微博管理--><!--微博管理-->
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
													<!--微博管理-->
												  </div>
												  <!--右边主体结束-->
												</div>
											  </div>
											  <div class="lineDot"></div>
											  <!--列表区块结束-->
											  
<?php } } ?>
  
<?php } ?>
  
<?php if($page_arr['html']) { ?>
											  <div style="margin-top:15px; "> <span><?php echo $page_arr['html']; ?></span> </div>
											  
<?php } ?>
  
<?php } ?>
  <!-- 正文列表结束 -->
											</div>
										  </div>
										  <!--微博区块结束-->
										  <div id="topic_list_{<?php echo $counts; ?>++}" class="t_list_b">
<?php if('myat'== $this->
											Code) { ?>
 
											这里会显示含有"@<?php echo $__my['nickname']; ?>"的微博。<BR /><?php } elseif('mycomment' == $this->
											Code) { ?> 
											这里会显示他人对你微博所做的评论。<BR /><?php } elseif('myfavorite' == $this->
											Code) { ?> 
											这里会显示你所收藏的微博。<BR />
											在登录状态下，每个微博的下方都有一个收藏连接，点击即可自动完成收藏，然后你就可以在这里看到了。你可以访问<A HREF="index.php?mod=topic&code=hot">热门微博</A>来发现有收藏价值的内容；<?php } elseif('favoritemy' == $this->
											Code) { ?> 
											这里会显示谁收藏了你的微博。<BR />
											赶快分享些有价值的新鲜事吧，当有人收藏后，你就会在这里看到。<BR />
											
<?php } ?>
  </div>
										  <!-- 显示帮助信息结束 -->
										</div>
										</div>
								
							  </ul>
					   </div>
			</div>
         <div id="con1_p"></div>
     </div>
 </div>


  <!--copyright-->
 <div id="copyright" >
   <p style="padding-top:10px;">大正高尔夫©2011&nbsp;&nbsp;&nbsp;&nbsp;京ICP证110339号</p>
   <p> 京公网安备110105008914号</p>
 </div>
     
  </div>
</div><br />
<?php echo $GLOBALS['schedule_html']; ?>
</body> 
</html>
<?php if($this->MemberHandler) $this->MemberHandler->UpdateSessions(); ?>