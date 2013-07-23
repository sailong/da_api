<?php
	
	if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
		exit('Access Denied');
	}
	cpheader();
	
	if($operation == 'list') {
		
		if(getgpc('volunteer_or_player') == ''){
			$volunteer_or_player = '全部';
		}else{
			$volunteer_or_player = getgpc('volunteer_or_player');
		}
	
		if( getgpc('trainer_joining_game') == '' || getgpc('trainer_joining_game') == '0'){
			$trainer_joining_game_is_checked = false;
			$trainer_joining_game = 0;
		}else{
			$trainer_joining_game_is_checked = true;
			$trainer_joining_game = 1;
		}
		
		if(getgpc('referee_joining_game') == '' || getgpc('referee_joining_game') == '0'){
			$referee_joining_game_is_checked = false;
			$referee_joining_game = 0;
		}else{
			$referee_joining_game_is_checked = true;
			$referee_joining_game = 1;
		}
		
		$keyword = getgpc('keyword');
		
		$start_time = getgpc('start_time');
		$end_time = getgpc('end_time');
		
		if(getgpc('area') == ''){
			$area = '全部';
		}else{
			$area = getgpc('area');
		}
		
		if(getgpc('trainer_certificate') == '' || getgpc('trainer_certificate') == '0'){
			$trainer_certificate_is_checked = false;
			$trainer_certificate = 0;
		}else{
			$trainer_certificate_is_checked = true;
			$trainer_certificate = 1;
		}
		
		if(getgpc('recommendation_letter') == '' || getgpc('recommendation_letter') == '0'){
			$recommendation_letter_is_checked = false;
			$recommendation_letter = 0;
		}else{
			$recommendation_letter_is_checked = true;
			$recommendation_letter = 1;
		}
		
		if(getgpc('referee_certificate') == '' || getgpc('referee_certificate') == '0'){
			$referee_certificate_is_checked = false;
			$referee_certificate = 0;
		}else{
			$referee_certificate_is_checked = true;
			$referee_certificate = 1;
		}
		
		if(getgpc('passing_or_not') == ''){
			$passing_or_not = '全部';
		}else{
			$passing_or_not = getgpc('passing_or_not');
		}
		
		if(getgpc('game_beginning_or_not') == ''){
			$game_beginning_or_not = '全部';
		}else{
			$game_beginning_or_not = getgpc('game_beginning_or_not');
		}
		
		if(getgpc('paying_or_not') == ''){
			$paying_or_not = '全部';
		}else{
			$paying_or_not = getgpc('paying_or_not');
		}
		
		if(getgpc('comment_type') == ''){
			$comment_type = '全部';
		}else{
			$comment_type = getgpc('comment_type');
		}
		
		if(getgpc('len_per_page') == ''){
			$len_per_page = 10;
		}else{
			$len_per_page = getgpc('len_per_page');
		}
		
		$page = max(1, $_G['page']);
		$start = ($page - 1) * $len_per_page;
		
		
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_member_and_enrolling_person.php');
		$maep = new member_and_enrolling_person();
		$record_amount = $maep->get_record_amount_by_condition(
			$volunteer_or_player,
			$trainer_joining_game,
			$referee_joining_game,
			$keyword,
			$start_time,
			$end_time,
			$area,
			$trainer_certificate,
			$recommendation_letter,
			$referee_certificate,
			$passing_or_not,
			$game_beginning_or_not,
			$paying_or_not,
			$comment_type
		);
		
		
		
		echo '<script type="text/javascript" src="static/js/calendar.js"></script>';
		$multipage = multi($record_amount, $len_per_page, $page, ADMINSCRIPT."?action=join&operation=list&submit=yes".$urladd.'&want_search_num='.$len_per_page.'&volunteer_or_player='.$volunteer_or_player.'&trainer_joining_game='.$trainer_joining_game.'&referee_joining_game='.$referee_joining_game.'&keyword='.$keyword.'&start_time='.$start_time.'&end_time='.$end_time.'&area='.$area.'&trainer_certificate='.$trainer_certificate.'&recommendation_letter='.$recommendation_letter.'&referee_certificate='.$referee_certificate.'&passing_or_not='.$passing_or_not.'&game_beginning_or_not='.$game_beginning_or_not.'&paying_or_not='.$paying_or_not.'&comment_type='.$comment_type);
	
		$_G['lang']['admincp']['join_list'] = '俱乐部报名管理';
		showsubmenu('join_list');
		
		$_G['lang']['admincp']['join_tip'] = '<ul><li>搜索-不填写关键词 提交搜素 = 等于全部记录</li><li>你可以搜索 【姓名】【手机】【电话】</li></ul>';
		showtips('join_tip');

		$_G['lang']['admincp']['join_list_amount_desc'] = '共搜索到?名符合条件的报名用户 ';
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_select_component_getter.php');
		$scg = new select_component_getter();
		$volunteer_or_player_component = $scg->get("volunteer_or_player",$volunteer_or_player,array(
			'全部',
			'球员',
			'志愿者'
		));
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_checkbox_component_getter.php');
		$ccg = new checkbox_component_getter();
		$trainer_joining_game_component = $ccg->get('trainer_joining_game',$trainer_joining_game_is_checked);
		
		$referee_joining_game_component = $ccg->get('referee_joining_game',$referee_joining_game_is_checked);
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_text_component_getter.php');
		$tcg = new text_component_getter();
		$keyword_component = $tcg->get('keyword',$keyword);
		
		$start_time_component = $tcg->get('start_time',$start_time,'showcalendar(event, this, 1)');
		$end_time_component = $tcg->get('end_time',$end_time,'showcalendar(event, this, 1)');
		
		$area_component = $scg->get("area",$area,array(
			'全部',
			'华北地区',
			'华东地区',
			'华南地区',
			'中南地区'
		));
		
		$trainer_certificate_component = $ccg->get('trainer_certificate',$trainer_certificate_is_checked);
		$recommendation_letter_component = $ccg->get('recommendation_letter',$recommendation_letter_is_checked);
		$referee_certificate_component = $ccg->get('referee_certificate',$referee_certificate_is_checked);
		
		$passing_or_not_component = $scg->get("passing_or_not",$passing_or_not,array(
			'全部',
			'审核通过',
			'未审核'
		));
		
		$game_beginning_or_not_component = $scg->get("game_beginning_or_not",$game_beginning_or_not,array(
			'全部',
			'已经比赛',
			'还未比赛'
		));
		
		$paying_or_not_component = $scg->get("paying_or_not",$paying_or_not,array(
			'全部',
			'已支付',
			'未支付'
		));
		
		$comment_type_component = $scg->get("comment_type",$comment_type,array(
			'全部',
			'未填写备注',
			'客户报名咨询',
			'客户信息审核-已核实',
			'客户信息审核-待核实',
			'客户信息审核-未联系上',
			'客户信息审核-条件不符已告知',
			'客户信息审核-身份证明未提供',
			'客户信息审核-身份证明已提供',
			'通知缴款-已通知，待缴',
			'通知缴款-未通知',
			'通知缴款-已通知，不参加',
			'通知缴款-已支付，待核实',
			'通知缴款-已支付，到账',
			'通知缴款-更改至其他分站',
			'分组通知-已发短信',
			'分组通知-已电话通知',
			'分组通知-电话未联系上',
			'分组通知-要求更改分组',
			'赛前--其他',
			'赛中-其他',
			'赛后-其他',
			'不参加-需退款',
			'通知缴款-已通知，现场缴费',
			'通知缴款-已支付，球场代收',
			'总决赛-已发入围通知',
			'总决赛-已回执入围通知，确认参赛',
			'总决赛-已发航班信息',
			'总决赛-已回复航班信息',
			'总决赛-已发邀请函',
			'已回执入围通知，确认不参加',
			'通知缴款-已短信通知，待缴',
			'总决赛-联系选手，未发通知',
			'总决赛-已发送航班信息',
			'总决赛-选手收到航班信息'
		));
		
		$len_per_page_component = $tcg->get('len_per_page',$len_per_page);
		
		$table_header = '
			&nbsp;&nbsp;参赛类型：
			'.$volunteer_or_player_component.'

			球员类型：
			'.$trainer_joining_game_component.'教练
			'.$referee_joining_game_component.'裁判<br/>
			
			关键字：'.$keyword_component.'&nbsp;
			
			报名时间范围：'.$start_time_component.'&nbsp;'.$end_time_component.'&nbsp;

			分站名称：
			'.$area_component.'&nbsp;

			未上传证件类型：
			'.$trainer_certificate_component.'教练证书&nbsp;
			'.$recommendation_letter_component.'机构推荐信&nbsp;
			'.$referee_certificate_component.'裁判证书<br/>

			审核状态：
			'.$passing_or_not_component.'&nbsp;

			比赛状态：
			'.$game_beginning_or_not_component.'&nbsp;

			支付状态：
			'.$paying_or_not_component.'&nbsp;

			备注类型：
			'.$comment_type_component.'

			每页显示：'.$len_per_page_component.'多少条记录&nbsp;
			
			<input type="submit" value="搜索" />&nbsp;

			<input type="button" value="导出Excel"/>';
		
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_member_and_enrolling_person.php');
		$maep = new member_and_enrolling_person();
		$rows = $maep->get_records(
			$start,
			$len_per_page,
			$volunteer_or_player,
			$trainer_joining_game,
			$referee_joining_game,
			$keyword,
			$start_time,
			$end_time,
			$area,
			$trainer_certificate,
			$recommendation_letter,
			$referee_certificate,
			$passing_or_not,
			$game_beginning_or_not,
			$paying_or_not,
			$comment_type
		);
		
		//var_dump($rows);
		showformheader('join&operation=list');
		
		showtableheader(cplang('join_list_amount_desc', array()).$table_header);
		
		$_G['lang']['admincp']['join_list_realname_desc'] = '姓名';
		$_G['lang']['admincp']['join_list_handicap_desc'] = '差点';
		$_G['lang']['admincp']['join_list_mobile_desc'] = '手机';
		$_G['lang']['admincp']['join_list_volunteer_or_player_desc'] = '参赛类型';
		$_G['lang']['admincp']['join_list_trainer_certificate_desc'] = '教练证书';
		$_G['lang']['admincp']['join_list_recommendation_letter_desc'] = '机构推荐信';
		$_G['lang']['admincp']['join_list_referee_certificate_desc'] = '裁判证书';
		$_G['lang']['admincp']['join_list_paying_or_not_desc'] = '支付状态';
		$_G['lang']['admincp']['join_list_passing_or_not_desc'] = '审核状态';
		$_G['lang']['admincp']['join_list_enrolling_time_desc'] = '报名时间';
		$_G['lang']['admincp']['join_list_volunteer_or_player_desc'] = '参赛类型';
		$_G['lang']['admincp']['join_list_operating_desc'] = '操作';
		
		showsubtitle(array('','join_list_realname_desc','join_list_handicap_desc','join_list_mobile_desc','join_list_volunteer_or_player_desc','join_list_trainer_certificate_desc','join_list_recommendation_letter_desc','join_list_referee_certificate_desc','join_list_paying_or_not_desc','join_list_passing_or_not_desc','join_list_enrolling_time_desc','join_list_volunteer_or_player_desc','join_list_operating_desc'));
		for($i=0;$i<count($rows);$i++){

			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_uploaded_avatar_filename_getter.php');
                	$uafg = new uploaded_avatar_filename_getter();
                	$uploaddir = '/home/www/tmp/';
			$uid = $rows[$i]['uid'];
                	$trainer_certificate_filename = $uafg->get($uid,$uploaddir,'_trainer_certificate');
			
			
			if($trainer_certificate_filename != ''){
				$trainer_certificate_img = '<a target="_blank" href="join.php?mod=read_trainer_certificate_original_size&uid='.$uid.'"><img src="join.php?mod=read_trainer_certificate&uid='.$uid.'"/></a>';
			}else{
				$trainer_certificate_img = '';
			}

			$uafg = new uploaded_avatar_filename_getter();
                        $uploaddir = '/home/www/tmp/';
                        $uid = $rows[$i]['uid'];
			$recommendation_letter_filename = $uafg->get($uid,$uploaddir,'_recommendation_letter');

			if($recommendation_letter_filename != ''){
				$recommendation_letter_img = '<a target="_blank" href="join.php?mod=read_recommendation_letter_original_size&uid='.$uid.'"><img src="join.php?mod=read_recommendation_letter&uid='.$uid.'"/></a>';
			}else{
				$recommendation_letter_img = '';
			}

			$uafg = new uploaded_avatar_filename_getter();
                        $uploaddir = '/home/www/tmp/';
                        $uid = $rows[$i]['uid'];
			$referee_certificate_filename = $uafg->get($uid,$uploaddir,'_referee_certificate');

			if($referee_certificate_filename != ''){
				$referee_certificate_img = '<a target="_blank" href="join.php?mod=read_referee_certificate_original_size&uid='.$uid.'"><img src="join.php?mod=read_referee_certificate&uid='.$uid.'"/></a>';
			}else{
				$referee_certificate_img = '';
			}

			if($rows[$i]["paying_or_not"] == 1){
				//$paying_link = '<a href="admin.php?action=join&operation=handle_canceling_paying&uid='.$rows[$i]['uid'].'">取消支付</a>';
				$paying_link = '<a href="admin.php?action=join&operation=handle_canceling_paying&uid='.$rows[$i]['uid'].'">取消支付</a>';
			}else{
				$paying_link = '<a href="admin.php?action=join&operation=handle_paying&uid='.$rows[$i]['uid'].'">支付</a>';
			}

			if($rows[$i]["passing_or_not"] == 0){
				$passing_link = '<a href="admin.php?action=join&operation=handle_passing&uid='.$rows[$i]['uid'].'">审核通过</a>';
			}else{
				$passing_link = '<a href="admin.php?action=join&operation=handle_canceling_passing&uid='.$rows[$i]['uid'].'">取消审核通过</a>';
			}

			if($rows[$i]["paying_or_not"] == 0){
				$rows[$i]["paying_or_not"] = '未支付';
			}else{
				$rows[$i]["paying_or_not"] = '已支付';
			}
			if($rows[$i]["passing_or_not"] == 0){
				$rows[$i]["passing_or_not"] = '未审核';
			}else{
				$rows[$i]["passing_or_not"] = '审核通过';
			}

			if($rows[$i]["volunteer_or_player"]=='志愿者'){
				$editing_link = '<a target="_blank" href="join.php?mod=volunteer&page=1&uid='.$rows[$i]['uid'].'">编辑</a>';
			}

			if($rows[$i]["volunteer_or_player"]=='球员'){
				$editing_link = '<a target="_blank" href="join.php?mod=player&page=1&uid='.$rows[$i]['uid'].'">编辑</a>';
			}

			echo showtablerow('', array('class="td25"', 'class="td28"'),array('<input type="checkbox"/>',$rows[$i]["realname"],$rows[$i]["handicap"],$rows[$i]["mobile"],$rows[$i]["volunteer_or_player"],$trainer_certificate_img,$recommendation_letter_img,$referee_certificate_img,$rows[$i]["paying_or_not"],$rows[$i]["passing_or_not"],$rows[$i]["enrolling_time"],$rows[$i]["volunteer_or_player"],$editing_link.'<br/>'.$passing_link.'<br/>'.$paying_link.'<br/><a href="admin.php?action=join&operation=add_comment&uid='.$rows[$i]['uid'].'">添加备注</a><br/><a href="admin.php?action=join&operation=comment_list&uid='.$rows[$i]['uid'].'">查看备注</a><br/>','<a href="admin.php?action=join&operation=set_game_beginning_or_not&uid='.$rows[$i]['uid'].'&game_beginning_or_not='.$rows[$i]['game_beginning_or_not'].'">设置比赛状态</a>'),true);
		}
		showtablefooter();
		showformfooter();
		echo $multipage;
	}
	
	if($operation == 'add_comment') {
	
		$uid = getgpc('uid');
	
		$comment_type = '未填写备注';
	
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_select_component_getter.php');
		$scg = new select_component_getter();
		$comment_type_component = $scg->get("comment_type",$comment_type,array(
			'未填写备注',
			'客户报名咨询',
			'客户信息审核-已核实',
			'客户信息审核-待核实',
			'客户信息审核-未联系上',
			'客户信息审核-条件不符已告知',
			'客户信息审核-身份证明未提供',
			'客户信息审核-身份证明已提供',
			'通知缴款-已通知，待缴',
			'通知缴款-未通知',
			'通知缴款-已通知，不参加',
			'通知缴款-已支付，待核实',
			'通知缴款-已支付，到账',
			'通知缴款-更改至其他分站',
			'分组通知-已发短信',
			'分组通知-已电话通知',
			'分组通知-电话未联系上',
			'分组通知-要求更改分组',
			'赛前--其他',
			'赛中-其他',
			'赛后-其他',
			'不参加-需退款',
			'通知缴款-已通知，现场缴费',
			'通知缴款-已支付，球场代收',
			'总决赛-已发入围通知',
			'总决赛-已回执入围通知，确认参赛',
			'总决赛-已发航班信息',
			'总决赛-已回复航班信息',
			'总决赛-已发邀请函',
			'已回执入围通知，确认不参加',
			'通知缴款-已短信通知，待缴',
			'总决赛-联系选手，未发通知',
			'总决赛-已发送航班信息',
			'总决赛-选手收到航班信息'
		));
		
		
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_text_component_getter.php');
		$tcg = new text_component_getter();
		$comment_title_component = $tcg->get('comment_title',$comment_title);
		
		echo '<form action="admin.php?action=join&operation=handle_adding_comment" method="post">';
		
		echo '备注类型：'.$comment_type_component;
		echo '<br/>备注标题：'.$comment_title_component;
		echo '<br/>备注内容：<br/><textarea name="comment_content"></textarea>';
		echo '<input name="uid" type="hidden" value="'.$uid.'"/>';
		echo '<br/><input type="submit" value="提交"/>';
		
		echo '</form>';
		
	}
	
	if($operation == 'handle_adding_comment') {
		
		$comment_type = getgpc('comment_type');
		$comment_title = getgpc('comment_title');
		$comment_content = getgpc('comment_content');
		$uid = getgpc('uid');
		
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_comments_adder.php');
		$ca = new comments_adder();
		$ca->add($comment_type,$comment_title,$comment_content,$uid);
		
		header('Location: admin.php?action=join&operation=list');
	}
	
	if($operation == 'comment_list'){
		
		$uid = getgpc('uid');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_comments_getter.php');
		$cg = new comments_getter();
		$rows = $cg->get_by_uid($uid);
		
		//var_dump($rows);
		
		echo '<table border="1">';
		echo '<tr><td><input type="checkbox"/></td><td>id</td><td>类型</td><td>标题</td><td>内容</td><td>&nbsp;</td></tr>';
		for($i=0;$i<count($rows);$i++){
			echo '<tr><td><input type="checkbox"/></td><td>'.$rows[$i]["id"].'</td><td>'.$rows[$i]["type"].'</td><td>'.$rows[$i]["title"].'</td><td>'.$rows[$i]["content"].'</td><td><a href="admin.php?action=join&operation=handle_deleting_comment&uid='.$uid.'&comment_id='.$rows[$i]["id"].'">删除</a></td></tr>';
		}
		echo '</table>';
		
	}
	
	if($operation == 'handle_deleting_comment'){
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_comments_deleter.php');
		$uid = getgpc('uid');
		$comment_id = getgpc('comment_id');
		$cd = new comments_deleter();
		$cd->delete_by_comment_id($comment_id);
		header('Location: admin.php?action=join&operation=comment_list&uid='.$uid);
	}

	if($operation == 'handle_paying'){
		$uid = getgpc('uid');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_updater.php');
		$vapu = new volunteers_and_players_updater();
		$vapu->update_paying_or_not_by_uid($uid,1);
		header('Location: admin.php?action=join&operation=list');
	}

	if($operation == 'handle_canceling_paying'){
		$uid = getgpc('uid');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_updater.php');
                $vapu = new volunteers_and_players_updater();
                $vapu->update_paying_or_not_by_uid($uid,0);
                header('Location: admin.php?action=join&operation=list');
	}

	if($operation == 'handle_passing'){
		$uid = getgpc('uid');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_updater.php');
		$vapu = new volunteers_and_players_updater();
		$vapu->update_passing_or_not_by_uid($uid,1);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_getter.php');
		$vapg = new volunteers_and_players_getter();
		$row = $vapg->get_record_by_uid($uid);
		if($row['trainer_certificate_inexistence']==0 || $row['recommendation_letter_inexistence']==0){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_getter.php');
			$gg = new guanxi_getter();
			$tmp = $gg->get_record_amount(1899467,$uid);
			if($tmp==0){
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_adder.php');
				$ga = new guanxi_adder();
				$ga->add(1899467,$uid,1);			
			}else{
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
				$gu = new guanxi_updater();
				$gu->update_iscomp(1899467,$uid,1);
			}
		}

		if($row['referee_certificate_inexistence']==0){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_getter.php');
			$gg = new guanxi_getter();
                        $tmp = $gg->get_record_amount(1899466,$uid);
                        if($tmp==0){
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_adder.php');
                                $ga = new guanxi_adder();
                                $ga->add(1899466,$uid,1);
			}else{
				require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                                $gu = new guanxi_updater();
                                $gu->update_iscomp(1899466,$uid,1);
			}
		}

		header('Location: admin.php?action=join&operation=list');
	}

	if($operation == 'handle_canceling_passing'){
		$uid = getgpc('uid');
                require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_updater.php');
		$vapu = new volunteers_and_players_updater();
		$vapu->update_passing_or_not_by_uid($uid,0);

		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_getter.php');
                $vapg = new volunteers_and_players_getter();
                $row = $vapg->get_record_by_uid($uid);

		if($row['trainer_certificate_inexistence']==0 || $row['recommendation_letter_inexistence']==0){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899467,$uid,0);
		}

		if($row['referee_certificate_inexistence']==0){
			require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_guanxi_updater.php');
                        $gu = new guanxi_updater();
                        $gu->update_iscomp(1899466,$uid,0);
		}

		header('Location: admin.php?action=join&operation=list');
	}

	if($operation == 'set_game_beginning_or_not'){
		$uid = getgpc('uid');
		$game_beginning_or_not = getgpc('game_beginning_or_not');

		echo '<form method="post" action="admin.php?action=join&operation=handle_setting_game_beginning_or_not">';

		if($game_beginning_or_not == 0){
			echo '比赛状态：<select name="game_beginning_or_not"><option value="0">还未比赛</option><option value="1">已经比赛</option></select>';
		}else{
			echo '比赛状态：<select name="game_beginning_or_not"><option>已经比赛</option><option>还未比赛</option></select>';
		}

		echo '<input name="uid" type="hidden" value="'.$uid.'"/>';

		echo '<br/><input type="submit" value="提交"/>';
		echo '</form>';
	}

	if($operation == 'handle_setting_game_beginning_or_not'){
		$uid = getgpc('uid');
                $game_beginning_or_not = getgpc('game_beginning_or_not');
		require_once($_SERVER["DOCUMENT_ROOT"].'/source/class/join/class_volunteers_and_players_updater.php');
		$vapu = new volunteers_and_players_updater();
		$vapu->update_game_beginning_or_not_by_uid($uid,$game_beginning_or_not);
		header('Location: admin.php?action=join&operation=list');
	}

?>
