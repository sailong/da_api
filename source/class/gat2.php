<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>大正网-竞猜页面</title>
<link rel="stylesheet" href="css/jingcai.css" type="text/css">
</head>

<body>
<?php
	
	
	require_once './source/class/class_core.php';
	require_once './source/function/function_home.php';
	$discuz = & discuz_core::instance();
	$cachelist = array('index', 'list');
	$discuz->cachelist = $cachelist;
	$discuz->init();
	
	$page = getgpc('page');
	if($page==''){
		$page = 1;
	}
	$len = 20;
	
	if($page==1){
		$start_pos = 0;
	}else{
		$start_pos = ($page-1)*$len;
	}
	 
	
	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$published_guess_activity_amount = $gag->get_published_guess_activity_amount();
	$where = " WHERE publish_or_not='是' ";
	
	$rows = $gag->get_info_perpage($where,$start_pos,$len);
	//var_dump($rows);
	
	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	
	?>
<div id="jingcai_wrapper">
          <!-- head部分开始 -->
          <div id="jingcai_head">
                  

                  <form action="#" method="post" name="search">
                  <div class="search">
                              <ul>
                                   
									<li><a href="/index.php">首页</a></li>
									<li><a>|</a></li>
									<li><a href="star.php">球星</a></li>
									<li><a>|</a></li>
									<li><a href="/event.php">赛事</a></li>
									<li><a>|</a></li>
									<li><a href="/field.php">球场</a></li>
									<li><a>|</a></li>
									<li><a href="/teach.php">教学</a></li>
									<li><a>|</a></li>
									<li><a href="/infomation.php">协会</a></li>
									<li><a>|</a></li>
									<li><a href="/mobilelist.php">手机报</a></li>
									<li><a>|</a></li>
									<li><a href="/field.php">品牌俱乐部</a></li>
                               </ul>
                  </div>
                  </form>

                  <div class="datu"><img src="images/head.jpg"></div>
          </div>
          <div class="clear"></div>
          <!-- head部分结束 -->
          <!-- body开始部分 -->
          <div id="jingcai_content_wrapper">
                    <div id="content_1">
                             <div id="content_2">
                                        <table cellpadding="10" cellspacing="1">

                                                 <tr class="content_tr_1">
                                                      <td>竞猜活动名</td>
                                                      <td>活动开始时间</td>
                                                      <td>活动结束时间</td>
                                                      <td>竞猜类型</td>
                                                  </tr>
                                                  
												  <?php for($i=0;$i<count($rows);$i++){ ?>
												  <tr class="content_tr_2">
													<td valign="middle"><?php echo $rows[$i]['name']?></td>
													<td valign="middle"><?php echo $rows[$i]['start_time']?></td>
													<td valign="middle"><?php echo $rows[$i]['end_time']?></td>
													<td valign="middle">
													<ul>
												  <?php 
		
		if(time() > strtotime($rows[$i]['end_time'])){
			$records = $gagtg->get_by_activity_id($rows[$i]['id']);
			//var_dump($records[0]["guess_activity_id"]);
			//var_dump($records[0]["guessing_type_id"]);
			require_once libfile ( 'class/guess_result_address_getter' );
			$grag = new guess_result_address_getter();
			$record = $grag->get($records[0]["guess_activity_id"],$records[0]["guessing_type_id"]);
			
			if($record[0]['address']==''){
				?>
				<li><a href="grs.html">查看竞猜结果</a></li>
				<?php
			}else{
				?>
				<li><a href="<?php echo $record[0]['address']?>">查看竞猜结果</a></li>
				<?php
			}
			
		
		}else{
			$records = $gagtg->get_by_activity_id($rows[$i]['id']);
		
			$guessing_type_ids = array();
			for($j=0;$j<count($records);$j++){
				$guessing_type_ids[] = $records[$j]['guessing_type_id'];
			}
		
			$guessing_type_id_str = implode(",",$guessing_type_ids);
			//var_dump($guessing_type_id_str);
			require_once libfile ( 'class/guess_type_getter' );
			$gtg = new guess_type_getter();
			$records2 = $gtg->get_info_by_id_str($guessing_type_id_str);
			//var_dump($records2);
		
			for($k=0;$k<count($records2);$k++){
			?>
				<li><a href="gtfi.php?activity_id=<?php echo $rows[$i]['id'];?>&type_id=<?php echo $records2[$k]['id'];?>&group_id=<?php echo $rows[$i]['group_id']; ?>"><?php echo $records2[$k]['name'];?></a></li>                                                    
			<?php
			}
		
		}
		?>
		
	</ul>
	</td></tr>
	<?php } ?>
                                         </table>
                                         <div><?php
	
	$multipage = multi($published_guess_activity_amount,$len,$page);
	echo $multipage;
?></div>
                             </div>
                             
                    </div>

                    
          </div>
          <div class="clear"></div>
          <!-- body开始部分 -->
          <!-- foot开始部分-->
          <div id="foot">
                         <div class="logo"><img src="images/foot_logo.jpg"></div>
                         <div class="right">
                                <A  href="/about.php">关于大正</A>&nbsp;|&nbsp;

                                <A class=f9 href="/about.php">商务合作</A>&nbsp;|&nbsp;
                                <A class=f9 href="/zhaopin.php">招贤纳士</A>&nbsp;|&nbsp;
                                <A class=f9  href="/huoban.php" >合作伙伴</A>&nbsp;|&nbsp;
                                <A class=f9  href="/lianxi.php" >联系我们</A>&nbsp;|&nbsp;
                                <A class=f9  href="/shengming.php" >免责声明</A>&nbsp;|&nbsp;

                                <A class=f9  href="/sitemap.php" >网站地图</A><br>
                                大正网版权归北京大正承平文化传播有限公司<br> 
                                京ICP证110339号    京公网安备110108008116号 
                         </div>
                         <div class="clear"></div>
          </div>
          <!-- foot结束部分-->
</div>
   
</body>

</html>
