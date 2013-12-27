<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_dazbm.php 20126 2012/2/22 02:42:26Z angf $
 */




if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
cpheader();

defined('GUESS_GROUP_ID') ? '' : define('GUESS_GROUP_ID','25');//定义赛事id

/*分页设置*/
$_G['setting']['guess_perpage'] = isset($_G['gp_want_search_num']) ? (intval($_G['gp_want_search_num'])==0 ? 20 : intval($_G['gp_want_search_num'])) : 20;
$page = max(1, $_G['page']);
$start_limit = ($page - 1) * $_G['setting']['guess_perpage'];


$where ="";
if(getgpc('guess_search')){
  $where = " where name like '%".trim(getgpc('guess_search'))."%' ";
}
/*竞猜对象*/
$guess_object  = array('1'=>'针对-球星竞猜','2'=>'针对-国家竞猜','3'=>'针对-趣味竞猜','自定义竞猜');


/*属性列表*/
if($operation == 'manage') {
		if(submitcheck('del_guess_submit')){
			$gu_id = implode(',',getgpc('guess_delete'));
			cpmsg('attr_delete_sure',"action=dazappguess&operation=manage&action_status=yes_delete_guess",'form','',"<input name=\"gu_id\" value=\"".$gu_id."\" type=\"hidden\"/>");
		}

		if($_G['gp_action_status']=='yes_delete_guess'){
			DB::delete('daz_guessing', " gu_id  IN(".$_G['gp_gu_id'].") ");
		   	DB::delete('daz_guess_options', " guess_id  IN(".$_G['gp_gu_id'].") ");
		}

	    $query = DB::query("SELECT * FROM ". DB::table('daz_guessing')." " .$where. " ORDER BY gu_id desc limit ".$start_limit."," .$_G['setting']['guess_perpage']." " );

		$query_num = DB::query("SELECT `gu_id` FROM ". DB::table('daz_guessing')." " .$where. " " );
		$guess_num = DB::num_rows($query_num);


		while($guess_result = DB::fetch($query)) {
			$guess_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"guess_delete[]\" value=\"$guess_result[gu_id]\" />",
				"<a href=\"home.php?mod=space&uid=$dazbm_result[uid]\" target=\"_blank\"> $guess_result[title]</a>",
				date('Y-m-d H:i:s',$guess_result['start_time']),
				date('Y-m-d H:i:s',$guess_result['end_time']),
				$guess_object[$guess_result['guess_object']],
				$guess_result['sort_order'],
                "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=edit_guess&gu_id=$guess_result[gu_id]\" /> ".cplang('edit')."|</a>".
				"<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&guess_id=$guess_result[gu_id]&guess_tag=$guess_result[guess_tag]&guess_object=$guess_result[guess_object]\" />".cplang('guess_tag_view')."</a>"
				),TRUE);
		}

	    $multipage = multi($guess_num, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=dazappguess&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage']);



		showsubmenu('guess_manage',array( array('guess_add', 'dazappguess&operation=add_guess', 1) ) );

		showtips('guess_export_tips');
		showformheader('dazappguess&operation=manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $guess_num)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="guess_search" > <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 显示所有竞猜 ">');
		showsubtitle(array('', 'guess_title','guess_start_time','guess_end_time','guess_object','sort_order', 'groups_type_operation'));
		  echo $guess_rows;
		showsubmit('del_guess_submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'guess_delete\')" class="checkbox">'.cplang('del'), '', $multipage);
		showtablefooter();
		showformfooter();

}

elseif($operation =='guess_tag_view'){

	$guess_object  = getgpc('guess_object');
	$guess_tag     = getgpc('guess_tag');
    $guess_id      = getgpc('guess_id');
    $tag_name      = getgpc('tag_name');

	if(submitcheck('submit_guess_jieguo')){
		if(empty($_G['gp_jieguo_guess_check'])){
			cpmsg('plase_checked_object');
		}
		cpmsg('sure_jieguo',"action=dazappguess&operation=update_guess_jieguo",'form','',"<input name=\"op_id\" value=\"".implode(',',$_G['gp_jieguo_guess_check'])."\" type=\"hidden\"/>");
	}
	/*修改 竞猜 结果*/
   if($_G['gp_update_guess_jieguo']==1){

       DB::query("update ".DB::table('daz_guess_options')." set is_answer='".$_G['gp_status']."' WHERE op_id='".$_G['gp_op_id']."'");
	   cpmsg('编辑成功','action=dazappguess&operation=manage');
   }

	$guess_tags_for_object = DB::query(" SELECT `attr_id`,`attr_show_value` FROM ".DB::table('daz_attr')." where attr_id  in(".	$guess_tag .")");
	$attrs_botton ="";
	while($result = DB::fetch($guess_tags_for_object)){
	   $attrs_botton .= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"btn\" type=\"button\" value=\" $result[attr_show_value] \" onclick=\"javascript:window.location.href='".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&guess_id=".$guess_id."&guess_tag=".$guess_tag."&guess_object=".$guess_object."&attr_id=".$result['attr_id']."&guess_num=".$result['guess_num']."&tag_name=".$result['attr_show_value']."'\"> ";
	}



	/*针对球星的列表页*/
	if(getgpc('attr_id')  && $guess_object==1 )
    {
		$query = DB::query(" SELECT `uid`,`realname` from ".DB::table('common_member_profile')."  where uid IN( select `relevance_id` from ".DB::table('daz_guess_options')." where guess_id='".$guess_id."' and guess_object =1 and guess_tag_id='".getgpc('attr_id')."')");

		while($result_users =  DB::fetch($query)){
		    $query_num = DB::fetch_first(" select `op_id`,`is_answer`,`guess_num` from ".DB::table('daz_guess_options')." where guess_id='".$guess_id."' and guess_object =1 and relevance_id='".$result_users['uid']."' and guess_tag_id='".getgpc('attr_id')."'");
			$users[$result_users['uid']]['name']         = !empty($result_users['realname']) ?  $result_users['realname'] :'大正明星';
			$users[$result_users['uid']]['tag_id']       = getgpc('attr_id');
			$users[$result_users['uid']]['guess_object'] = '球星';
			$users[$result_users['uid']]['uid']          = $result_users['uid'];
			$users[$result_users['uid']]['image']        = avatar($result_users['uid'], 'small', false, false, false);
			$users[$result_users['uid']]['guess_num']    = $query_num['guess_num'];
			$users[$result_users['uid']]['op_id']        = $query_num['op_id'];
			$users[$result_users['uid']]['is_answer']    = $query_num['is_answer'];
		}

		$guess_object_details ='';
		foreach($users as $value){

        $is_answer_img_yes = "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&op_id=".$value['op_id']."&update_guess_jieguo=1&status=0\"><img src=\"static/space/daz_app/guess/images/succeed.gif\"></a>";

        $is_answer_img_no = "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&op_id=".$value['op_id']."&update_guess_jieguo=1&status=1\"><img src=\"static/space/daz_app/guess/images/wen.gif\"></a>";

		   $guess_object_details .=showtablerow('',array('class="td25"', 'class="td28"'),array(
			   "<input type=\"checkbox\" class=\"checkbox\" name=\"jieguo_guess_check[]\" value=\"".$value['op_id']."\" />",
			   "<a target=\"_blank\" href=\"home.php?mod=space&uid=".$value['uid']."&do=profile\">".$value['image']."</a>",
			   "<a target=\"_blank\" href=\"home.php?mod=space&uid=".$value['uid']."&do=profile\">".$value['name']."</a>",
     		   $value['guess_object'],
			   "<h2>$value[guess_num]</h2>",
			   ($value['is_answer']==1) ? $is_answer_img_yes : $is_answer_img_no,
			   "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_object_user_info&op_id=".$value['op_id']."&guess_object=1&guess_id=".$guess_id."&tag_name=".$tag_name."\">查看 竞猜用户</a>",
		   ),TRUE);
		}
	}



   /*针对国际的列表页*/
	elseif(getgpc('attr_id') && $guess_object==2 ){

		$query_guojia = DB::query(" SELECT * FROM ".DB::table('daz_guess_options')." where guess_object = '2' and guess_id ='".$guess_id."' and guess_tag_id='".getgpc('attr_id')."'");
		while($guojia_result = DB::fetch($query_guojia)){
			$guojia[]  = array(
				'op_id'=>$guojia_result['op_id'],
				'name'=>$guojia_result['relevance_value'],
				'guess_object'=>'国家',
				'guess_id'=>$guess_id,
				'tag_id'=>getgpc('attr_id'),
				'guess_num' => $guojia_result['guess_num'],
				'is_answer' => $guojia_result['is_answer'],
				);
		}
		$guess_object_details ='';

		foreach($guojia as $value){
			$is_answer_img_yes = "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&op_id=".$value['op_id']."&update_guess_jieguo=1&status=0\"><img src=\"static/space/daz_app/guess/images/succeed.gif\"></a>";

			$is_answer_img_no = "<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_tag_view&op_id=".$value['op_id']."&update_guess_jieguo=1&status=1\"><img src=\"static/space/daz_app/guess/images/wen.gif\"></a>";

			$guess_object_details .=showtablerow('',array('class="td25"', 'class="td28"'),array(
			"<input type=\"checkbox\" class=\"checkbox\" name=\"jieguo_guess_check[]\" value=\"$value[guess_num]\" />",
			$value['image'],
			$value['name'],
			$value['guess_object'],
			'<h2>'.$value['guess_num'].'</h2>',
			($value['is_answer']==1) ? $is_answer_img_yes : $is_answer_img_no,
			"<a href=\"".ADMINSCRIPT."?action=dazappguess&operation=guess_object_user_info&op_id=".$value['op_id']."&guess_object=2&guess_id=".$guess_id."&tag_name=".$tag_name."\"> 查看 竞猜用户 </a>",

		   ),TRUE);
		}

	}


	showsubmenu('guess_manage',array( array('guess_manage_list', 'dazappguess&operation=manage', 1) ) );
	showformheader('dazappguess&operation=manage&operation=guess_tag_view');
	showtableheader(cplang('guess_tags').$attrs_botton);
	showsubtitle(array('', 'view_value','object_name','guess_object','join_member_num','is_answer','groups_type_operation'));
	  echo $guess_object_details;
	showsubmit('submit_guess_jieguo', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'jieguo_guess_check\')" class="checkbox"> '.cplang('jieguo_guess'), '', $multipage);


	showtablefooter();
	showformfooter();

}

/*编辑 竞猜活动*/
elseif($operation == 'edit_guess'){
	   $gu_id = getgpc('gu_id');
	   $guess_info =  DB::fetch_first(" select * from ".DB::table('daz_guessing')." where gu_id ='".$gu_id."'");
	   $img_src = $guess_info['guess_picture'] ? '<a  href="data/attachment/daz_app/'.$guess_info['guess_picture'].'">点击查看活动图片</a>' : '';
	   $checked_tag = explode(',',$guess_info['guess_tag']);
//var_dump(" select * from ".DB::table('daz_guessing')." where gu_id ='".$gu_id."'");var_dump($checked_tag);echo 'line204';

		/*赛事组*/
		$saishi_option = "";
		$query = DB::query(" SELECT cm.uid,mp.field1 FROM ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('common_member_profile')." as mp ON cm.uid=mp.uid  where cm.groupid = '".GUESS_GROUP_ID."' group by mp.uid");

		while($result_saishis = DB::fetch($query)){
			$saishi_option[]=array($result_saishis['uid'],$result_saishis['field1']);
		}
		$saishi_option[]=array('0',cplang('guess_no_relevance'));//暂时不开启独立 自定义投票


		/*属性组*/
		$attrs_query = DB::query(" select * from ".DB::table('daz_attr')." where enable=0 ");
		while($result_attrs = DB::fetch($attrs_query)){
			$attrs_option[]=array($result_attrs['attr_id'],$result_attrs['attr_show_value']);
		}

		showsubmenu('guess_add',array( array('back_guess_list', 'dazappguess&operation=manage', 1) ) );
		//showtips('add_guess_tips');


		showformheader('dazappguess&operation=edit&gu_id='.$gu_id, 'enctype="multipart/form-data" onsubmit="edit_save();"');
		echo '<script type="text/javascript" src="'.STATICURL.'image/editor/editor_function.js"></script>';
		echo "<input type=\"hidden\" name=\"feednew[feedid]\" value=\"$feed[feedid]\" /><input type=\"hidden\" name=\"feednew[feeduid]\" value=\"$feed[uid]\" />";
		showtableheader();
		$option = array(
			array('1', cplang('dazapp_guess_qiuxing')),
			array('2', cplang('dazapp_guess_country')),
			array('3', cplang('dazapp_guess_quwei')),
			array('4', cplang('dazapp_guess_user_defined'),'announce_color','sdfefefe'),
			);

		/*竞猜标题*/
		showsetting(cplang('guess_title'), 'newsubject','',
		"<input type=\"text\" class=\"txt\" id=\"newsubject\" name=\"title\" style=\"float:left; width:160px;\" value=\"$guess_info[title]\">
		<input id=\"announce_color\" onclick=\"change_title_color('announce_color')\" type=\"button\" class=\"colorwd\" value=\"\">
		<div class=\"fwin\"><div class=\"ss\">
		<em id=\"announce_bold\" onclick=\"change_title('bold');change_choose(this.id);\"><b>B</b></em>
		<em id=\"announce_italic\" onclick=\"change_title('italic');change_choose(this.id);\"><i>I</i></em>
		<em id=\"announce_underline\" onclick=\"change_title('underline');change_choose(this.id);\"><u>U</u></em>
		</div></div>
		"
		);

		/*关联对象*/
		showsetting('select_guess_saishi', array('guess_saishi',$saishi_option), $guess_info['saishi_id'], 'select','','','这里竞猜都是以 赛事为单位','onclick="sdf()"');

		//var_dump($option);var_dump($guess_info['guess_object']);echo 'line252';
		showsetting('select_guess_type', array('guess_object',$option),$guess_info['guess_object'], 'mradio2');
		showsetting('guess_tag', array('guess_tag',$attrs_option),$checked_tag , 'mcheckbox');

		$newstarttime = dgmdate(TIMESTAMP, 'Y-n-j H:i');
		$newendtime = dgmdate(TIMESTAMP + 86400* 7, 'Y-n-j H:i');
		showsetting($lang['start_time'], 'start_time',date('Y-m-d H:i',$guess_info['start_time']) , 'calendar', '', 0,'', 1);
		showsetting($lang['end_time'], 'end_time', date('Y-m-d H:i',$guess_info['end_time']), 'calendar', '', 0, '', 1);

		showsetting('guess_picture', 'guess_picture','', 'file','','',$img_src);
	    echo " <input name=\"guess_picture_value\" value=\"$guess_info[guess_picture]\" type=\"hidden\">";
		showsetting('sort_order', 'sort_order',$guess_info['sort_order'], 'text','','','填写你排序的 数字  0-100..之间 数字越小活动越靠前');
		showsetting('guess_describe', 'describe', $guess_info['describe'], 'textarea');
		$src = 'home.php?mod=editor&charset='.CHARSET.'&allowhtml=0&doodle=0';
			print <<<EOF
			<tr><td>{$lang['message']}</td><td></td></tr>
			<tr>
				<td colspan="2">
					<textarea class="userData" name="guess_content" id="uchome-ttHtmlEditor" style="height:100%;width:100%;display:none;border:0px">
				    $guess_info[content]
				   </textarea>
					<iframe src="$src" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;border: 1px solid #C5C5C5;" height="400"></iframe>
				<td>
			</tr>
EOF;

		showsubmit('add_guess_submit', 'submit');
		showtablefooter();
		showformfooter();



}

/*添加竞猜活动*/
elseif($operation == 'add_guess'){

        /*赛事组*/
        $saishi_option = "";
        $query = DB::query(" SELECT cm.uid,mp.field1 FROM ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('common_member_profile')." as mp ON cm.uid=mp.uid  where cm.groupid = '".GUESS_GROUP_ID."' group by mp.uid");


		while($result_saishis = DB::fetch($query)){
			$saishi_option[]=array($result_saishis['uid'],$result_saishis['field1']);
		}
		$saishi_option[]=array('0',cplang('guess_no_relevance'));//暂时不开启独立 自定义投票

	    /*属性组*/
		$attrs_query = DB::query(" select * from ".DB::table('daz_attr')." where enable=0 ");
		while($result_attrs = DB::fetch($attrs_query)){
			$attrs_option[]=array($result_attrs['attr_id'],$result_attrs['attr_show_value']);
		}

	    showformheader('dazappguess&operation=add', 'enctype="multipart/form-data" onsubmit="edit_save();"');
        // showformheader('dazappguess&operation=add','enctype');
		showsubmenu('guess_add',array( array('back_guess_list', 'dazappguess&operation=manage', 1) ) );
		echo '<script type="text/javascript" src="'.STATICURL.'image/editor/editor_function.js"></script>';

		showtableheader();
		$option = array(
			array('1', cplang('dazapp_guess_qiuxing')),
			array('2', cplang('dazapp_guess_country')),
			array('3', cplang('dazapp_guess_quwei')),
			array('4', cplang('dazapp_guess_user_defined'),'announce_color','sdfefefe'),
			);

        /*竞猜标题*/
        showsetting(cplang('guess_title'), 'newsubject', '',
		"<input type=\"text\" class=\"txt\" id=\"newsubject\" name=\"title\" style=\"float:left; width:160px;\" value=\"\">
		<input id=\"announce_color\" onclick=\"change_title_color('announce_color')\" type=\"button\" class=\"colorwd\" value=\"\">
		<div class=\"fwin\"><div class=\"ss\">
		<em id=\"announce_bold\" onclick=\"change_title('bold');change_choose(this.id);\"><b>B</b></em>
		<em id=\"announce_italic\" onclick=\"change_title('italic');change_choose(this.id);\"><i>I</i></em>
		<em id=\"announce_underline\" onclick=\"change_title('underline');change_choose(this.id);\"><u>U</u></em>
		</div></div>
		"
		);

        /*关联对象*/
	    showsetting('select_guess_saishi', array('guess_saishi',$saishi_option), '', 'select','','','这里竞猜都是以 赛事为单位','onclick=""');
		showsetting('select_guess_type', array('guess_object',$option),'', 'mradio2');
		showsetting('guess_tag', array('guess_tag',$attrs_option), '', 'mcheckbox');
        //showsetting('guess_tag', array('guess_tag',$attrs_option), '', 'mcheckbox');


        $newstarttime = dgmdate(TIMESTAMP, 'Y-n-j H:i');
		$newendtime = dgmdate(TIMESTAMP + 86400* 7, 'Y-n-j H:i');
		showsetting($lang['start_time'], 'start_time', $newstarttime, 'calendar', '', 0, '', 1);
		showsetting($lang['end_time'], 'end_time', $newendtime, 'calendar', '', 0, '', 1);
        showsetting('guess_picture', 'guess_picture','', 'file');
		showsetting('sort_order', 'sort_order','', 'text','','','填写你排序的 数字  0-100..之间 数字越小活动越靠前');
		showsetting('guess_describe', 'describe', '', 'textarea');

		$src = 'home.php?mod=editor&charset='.CHARSET.'&allowhtml=1&doodle=0';
		print <<<EOF
		<tr><td>{$lang['message']}</td><td></td></tr>
		<tr>
			<td colspan="2">
				<textarea class="userData" name="guess_content" id="uchome-ttHtmlEditor" style="height:100%;width:100%;display:none;border:0px"></textarea>
				<iframe src="$src" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;border: 1px solid #C5C5C5;" height="400"></iframe>
			<td>
		</tr>
EOF;

        showsubmit('add_guess_submit', 'submit');
        showtablefooter();
        showformfooter();



}

/*编辑竞猜活动的 描述信息 和 相关竞猜 选项  ***** 很关键*/
elseif($operation == 'edit'){

$profile =array();
/*上传文件处理*/
	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			$upload->init ( $file, 'daz_app' );
			$attach = $upload->attach;

			if (! $upload->error ()) {
				$upload->save ();

				if (! $upload->get_image_info ( $attach ['target'] )) {
					@unlink ( $attach ['target'] );
					continue;
				}

				$attach ['attachment'] = dhtmlspecialchars ( trim ( $attach ['attachment'] ) );
				if ($_G ['cache'] ['fields_register'] [$field_key] ['needverify']) {
					$verifyarr [$key] = $attach ['attachment'];
				} else {
					$profile [$key] = $attach ['attachment'];
				}
			}
		}
	}


	$gu_id = getgpc('gu_id');
    $guess_data['title']        = getgpc('title');                    //竞猜标题
    $guess_data['start_time']   = strtotime($_G['gp_start_time']);    //活动开始时间  活动
    $guess_data['end_time']     = strtotime($_G['gp_end_time']);     //活动结束时间
    $guess_data['describe']     = getgpc('describe');                 //活动的简单描述
    $guess_data['content']      = trim($_G['gp_guess_content']);                //详细 说明· 活动。。
	$guess_data['guess_object'] = getgpc('guess_object')<=2 ? getgpc('guess_object') : 1  ;     //竞猜对象
    $guess_data['guess_tag']    = implode(',',getgpc('guess_tag'));   //竞猜标签的集合
	$guess_data['saishi_id']    = getgpc('guess_saishi');             //赛事ID
	$guess_data['sort_order']   = getgpc('sort_order');               //活动排序
	$guess_data['guess_picture'] = $profile['guess_picture'] ?  $profile['guess_picture'] : getgpc('guess_picture_value');

	$sai_id                     = getgpc('guess_saishi');               //关联id  这里关联id    目前只以赛事关联起来·记录的是赛事ID 赛事有是会员组为 25的会员
    $guess_tag                  = implode(',',getgpc('guess_tag'));     //这里是要是·竞猜的标签··如果 猜第一门 冠军是谁··这样的标签云
    $guess_object               = & $guess_data['guess_object'] ;  //竞猜类型·  1是球星 2是国家 趣味竞猜 和 自定义竞猜 赞未开通

	if(empty($guess_tag)){
		cpmsg('请选择 竞猜相关标签'); //提示信息
	}
    if(empty($guess_object)){
	    cpmsg('选择竞猜的对象'); //提示信息
	}

    //竞猜对象是 球星的话· 就查出 相关赛事下的球星 针对赛事的的竞猜只能一次加一个赛事的球星 或者 对应的国家
	if($guess_object == 1){
	   $guess_object_rows = DB::query("SELECT `userid` from ".DB::table('home_saishi_csqy')." where groupid = '".$sai_id."'");
	   while($result = DB::fetch($guess_object_rows)){
             $qiuxing[]= $result['userid'];
	   }
	}


   //竞猜对象是 国家的 就查出海外所有的国家
   elseif($guess_object==2){
	   $guess_object_rows = DB::query("SELECT `name` FROM ".DB::table('common_district')." WHERE upid = 35 ");
	   while($result = DB::fetch($guess_object_rows)){
	      $guojia[] = $result['name'];
	   }
	}

	if(empty($qiuxing) && $guess_object == 1){
	    cpmsg('你选择的赛事没有相关的球星','action=dazappguess&operation=add_guess'); //提示信息
	}
	if(empty($guojia)  && $guess_object == 2){
	    cpmsg('你选择的赛事 没有相关的国家','action=dazappguess&operation=add_guess'); //提示信息
	}


	//$guess_info = DB::fetch_first("SELECT * FROM ".DB::table('daz_guessing')." where gu_id = ".$gu_id);
    $guess_tag_array = getgpc('guess_tag');
    DB::update('daz_guessing',$guess_data," gu_id = '".$gu_id."'");

    /*处理都市想通 标签 和 球星的 修改*/
    if($guess_object == 1){
	    foreach($guess_tag_array  as $tag_id){
			$result_option = DB::fetch_first(" Select `guess_id` FROM ".DB::table('daz_guess_options')." where guess_tag_id='".$tag_id."' and guess_id= '".$gu_id."' and guess_object='".$guess_object."'");
			if(empty($result_option)){
			    foreach($qiuxing as $qiuxing_id){
					DB::insert('daz_guess_options',array('guess_tag_id'=>$tag_id,'guess_id'=>$gu_id,'guess_object'=>$guess_object,'relevance_id'=>$qiuxing_id));
				}
			}
		}
		DB::delete('daz_guess_options', " guess_tag_id NOT IN($guess_tag) and guess_id ='$gu_id' and guess_object='$guess_object' ");
	}

	/*处理  标签 和 国家的 修改*/
    elseif($guess_object == 2){
	    foreach($guess_tag_array  as $tag_id){
			$result_option = DB::fetch_first(" Select `guess_tag_id` FROM ".DB::table('daz_guess_options')." where guess_tag_id='".$tag_id."' and guess_id ='".$gu_id."' and guess_object= '".$guess_object."'");
			if(!$result_option){
			    foreach($guojia as $name){
					DB::insert('daz_guess_options',array('guess_tag_id'=>$tag_id,'guess_id'=>$gu_id,'guess_object'=>$guess_object,'relevance_value'=>$name));
				}
			}
		}
		DB::delete('daz_guess_options', " guess_tag_id NOT IN($guess_tag) ");
	}

      cpmsg('编辑成功','action=dazappguess&operation=manage'); //提示信息

}



elseif($operation == 'add'){
/*上传文件处理*/
	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			$upload->init ( $file, 'daz_app' );
			$attach = $upload->attach;

			if (! $upload->error ()) {
				$upload->save ();

				if (! $upload->get_image_info ( $attach ['target'] )) {
					@unlink ( $attach ['target'] );
					continue;
				}

				$attach ['attachment'] = dhtmlspecialchars ( trim ( $attach ['attachment'] ) );
				if ($_G ['cache'] ['fields_register'] [$field_key] ['needverify']) {
					$verifyarr [$key] = $attach ['attachment'];
				} else {
					$profile [$key] = $attach ['attachment'];
				}
			}
		}
	}

    $guess_data['title']        = getgpc('title');                    //竞猜标题
	$guess_data['start_time']   = strtotime($_G['gp_start_time']);    //活动开始时间  活动
    $guess_data['end_time']     = strtotime($_G['gp_end_time']);      //活动结束时间
    $guess_data['describe']     = getgpc('describe');                 //活动的简单描述
    $guess_data['content']      = trim(getgpc('guess_content'));            //详细 说明· 活动。。
	$guess_data['guess_object'] = getgpc('guess_object');             //竞猜对象
    $guess_data['guess_tag']    = implode(',',getgpc('guess_tag'));   //竞猜标签的集合
	$guess_data['saishi_id']    = getgpc('guess_saishi');             //赛事ID
    $guess_data['sort_order']   = getgpc('sort_order');               //活动排序
	$guess_data['guess_picture']= $profile['guess_picture'];          //活动图片


    $sai_id                     = getgpc('guess_saishi');               //关联id  这里关联id    目前只以赛事关联起来·记录的是赛事ID 赛事有是会员组为 25的会员
    $guess_tag                  = implode(',',getgpc('guess_tag'));     //这里是要是·竞猜的标签··如果 猜第一门 冠军是谁··这样的标签云
    $guess_object               = getgpc('guess_object')<=2 ? getgpc('guess_object') : 1 ;  //竞猜类型·  1是球星 2是国家 趣味竞猜 和 自定义竞猜 赞未开通

    if(empty($guess_tag)){
		cpmsg('请选择 竞猜相关标签'); //提示信息
	}
    if(empty($guess_object)){
	    cpmsg('选择竞猜的对象'); //提示信息
	}


    //竞猜对象是 球星的话· 就查出 相关赛事下的球星 针对赛事的的竞猜只能一次加一个赛事的球星 或者 对应的国家
	if($guess_object == 1){
	   $guess_object_rows = DB::query("SELECT `userid` from ".DB::table('home_saishi_csqy')." where groupid = '".$sai_id."'");
	   while($result = DB::fetch($guess_object_rows)){
             $qiuxing[]= $result['userid'];
	   }
	}


   //竞猜对象是 国家的 就查出海外所有的国家
   elseif($guess_object==2){
	   $guess_object_rows = DB::query("SELECT `name` FROM ".DB::table('common_district')." WHERE upid = 35 ");
	   while($result = DB::fetch($guess_object_rows)){
	      $guojia[] = $result['name'];
	   }
	}

	if(empty($qiuxing) && $guess_object == 1){
	    cpmsg('你选择的赛事没有相关的球星','action=dazappguess&operation=add_guess'); //提示信息
	}
	if(empty($guojia)  && $guess_object == 2){
	    cpmsg('你选择的赛事 没有相关的国家','action=dazappguess&operation=add_guess'); //提示信息
	}

	/*插入活动基本信息表*/
	DB::insert("daz_guessing",$guess_data,true);
	$guess_id = DB::insert_id();                                        //竞猜活动的id



   /*------------------------------------------------------上下代码 不同 需开发 仔细看----------------------------------------------------------------------*/


   //把针对球星的·option 选项放入数据库
	if($guess_object == 1){
		foreach(explode(',',$guess_tag) as $tag_value_id){
              foreach($qiuxing as $q_id){
			    DB::insert('daz_guess_options',array('guess_tag_id'=>$tag_value_id,'guess_id'=>$guess_id,'guess_object'=>$guess_object,'relevance_id'=>$q_id));
			  }
		}
       cpmsg('添加成功','action=dazappguess&operation=manage'); //提示信息
	}

   //把针对的国家·option 选项放入数据库
	elseif($guess_object == 2){
		foreach(explode(',',$guess_tag) as $tag_value_id){
              foreach($guojia as $gj_value){
			    DB::insert('daz_guess_options',array('guess_tag_id'=>$tag_value_id,'guess_id'=>$guess_id,'guess_object'=>$guess_object,'relevance_value'=>$gj_value));
			  }
		}
		cpmsg('添加成功','action=dazappguess&operation=manage'); //提示信息
	}


}


/*查看都谁猜了谁*/
elseif($operation == 'guess_object_user_info'){

	 $guess_object =  getgpc('guess_object');

	 $guess_info= DB::fetch_first(" SELECT * FROM ".DB::table('daz_guessing')." where gu_id = '".getgpc('guess_id')."'"); //活动主表

     $guess_options = DB::fetch_first(" SELECT * FROM ".DB::table('daz_guess_options')." where op_id='".getgpc('op_id')."'"); //活动选项附表

     if(!empty($guess_options['join_guess_users'])){

		 $guess_users_query = DB::query(" SELECT `uid`,`username` FROM ".DB::table('common_member')." where uid IN( ".$guess_options['join_guess_users']."0) ");

         $guess_users_list_buttom = "";

		 while($result = DB::fetch($guess_users_query)){
           $guess_users_list_buttom .= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class=\"btn\" type=\"button\" value=\" $result[username] \" onclick=\"　window.open('home.php?mod=space&uid=".$result['uid']."&do=profile','win'); \"> ";
         }
	 }

    if($guess_users_list_buttom=="") $guess_users_list_buttom = "<font color=\"red\"><h2>  Sorry -_-! Manager 还没有用户参加 竞猜...</h2></font>";
	showformheader('dazappguess&operation=manage');
    showsubmenu('guess_manage',array( array('guess_manage_list', 'dazappguess&operation=manage', 1) ) );
    showtips('以下是参加本次标签 竞猜的用户<br><br>');
      echo $guess_users_list_buttom;
	showformfooter();
}

/*公布结果操作*/
elseif($operation =='update_guess_jieguo'){
    DB::query("update ".DB::table('daz_guess_options')." set is_answer=1 WHERE op_id IN (".$_G['gp_op_id'].")");
    cpmsg(" 结果 已经公布 操作成功！",'action=dazappguess&operation=manage');
}elseif($operation == 'add_guess_type'){
	//echo '添加竞猜类型<br/><br/>';

	showformheader('dazappguess&operation=handle_adding_guess_type', '');

	$_G['lang']['admincp']['guess_type_desc'] = '竞猜类型描述';
	$_G['lang']['admincp']['guess_type_list_desc'] = '返回竞猜类型列表';
	showsubmenu('guess_type_desc',array( array('guess_type_list_desc', 'dazappguess&operation=guess_type_list', 1) ) );
	$_G['lang']['admincp']['guess_type'] = '竞猜类型';
	showsetting('guess_type', 'guess_type','', 'text','','','');
	echo '<br/><br/>';

	$_G['lang']['admincp']['guess_start_pos'] = '竞猜开始名次';
	showsetting('guess_start_pos', 'guess_start_pos','', 'text','','','');
	echo '<br/><br/>';

	$_G['lang']['admincp']['guess_end_pos'] = '竞猜结束名次';
	showsetting('guess_end_pos', 'guess_end_pos','', 'text','','','');
	echo '<br/><br/>';

	showsubmit('add_guess_type_submit', 'submit');

	showformfooter();

}elseif($operation == 'handle_adding_guess_type'){

	$guess_type_data['name']        = getgpc('guess_type');//竞猜类型
	$guess_type_data['start_pos']   = getgpc('guess_start_pos');//竞猜开始名次
    $guess_type_data['end_pos']     = getgpc('guess_end_pos');//竞猜结束名次

	if(mb_strlen($guess_type_data['name'],"UTF-8")==0){
		cpmsg('必须填写竞猜类型');
	}

	if(mb_strlen($guess_type_data['name'],"UTF-8")>30){
		cpmsg('竞猜类型必须在30字以内');
	}

	if(!ereg("^[1-9]{0,1}[0-9]{1}$",$guess_type_data['start_pos'])||$guess_type_data['start_pos']==0){
		cpmsg('竞猜开始名次必须是非零两位数字');
	}

	if(!ereg("^[1-9]{0,1}[0-9]{1}$",$guess_type_data['end_pos'])||$guess_type_data['end_pos']==0){
		cpmsg('竞猜结束名次必须是非零两位数字');
	}

	if($guess_type_data['start_pos']>$guess_type_data['end_pos']){
		cpmsg('竞猜开始名次大于竞猜结束名次');
	}

	$dgt = DB::table('daz_guessing_types');
	$sql = "LOCK TABLES ".$dgt." WRITE";
	DB::query($sql);

	$rows = DB::query("SELECT `name` from ".DB::table('daz_guessing_types')." where start_pos = ".$guess_type_data['start_pos']." AND end_pos=".$guess_type_data['end_pos']);
	$result = DB::fetch($rows);
	if($result==false){
		$rows = DB::query("SELECT `name` from ".DB::table('daz_guessing_types')." where name = '".$guess_type_data['name']."'");
		$result = DB::fetch($rows);
		if($result==false){
			DB::insert("daz_guessing_types",$guess_type_data,true);
			$sql = "UNLOCK TABLES";
			DB::query($sql);
			cpmsg('添加成功','action=dazappguess&operation=guess_type_list');
		}else{
			$sql = "UNLOCK TABLES";
			DB::query($sql);
			cpmsg('竞猜类型重复');
		}
	}else{
		$sql = "UNLOCK TABLES";
		DB::query($sql);
		cpmsg('竞猜开始名次和结束名次重复');
	}



}elseif($operation == 'guess_type_list'){

	if(submitcheck('del_guess_submit')){
		$id = implode(',',getgpc('id_list'));

		require_once libfile ( 'class/users_activities_types_getter' );
		$uatg = new users_activities_types_getter();

		$id_set = getgpc('id_list');
		for($i=0;$i<count($id_set);$i++){
			$amount = $uatg->get_amount_by_type_id($id_set[$i]);
			if($amount>0){
				cpmsg('已经有用户参与此类型的竞猜');
			}
		}

		require_once libfile ( 'class/guess_activities_guessing_types_getter' );
		$gagtg = new guess_activities_guessing_types_getter();
		$id_set = getgpc('id_list');
		for($i=0;$i<count($id_set);$i++){
			$amount = $gagtg->get_amount_by_type_id($id_set[$i]);
			if($amount>0){
				cpmsg('已经有活动与此类型关联');
			}
		}

		cpmsg('attr_delete_sure',"action=dazappguess&operation=guess_type_list&action_status=deleting_flag",'form','',"<input name=\"id\" value=\"".$id."\" type=\"hidden\"/>");

	}

	if($_G['gp_action_status']=='deleting_flag'){

			if(strlen($_G['gp_id'])!=0){
				DB::delete('daz_guessing_types', " id  IN(".$_G['gp_id'].") ");
			}

	}

	$query = DB::query("SELECT * FROM ". DB::table('daz_guessing_types')." " .$where. " ORDER BY id desc limit ".$start_limit."," .$_G['setting']['guess_perpage']." " );
	$query_num = DB::query("SELECT `id` FROM ". DB::table('daz_guessing_types')." " .$where. " " );
	$guess_type_num = DB::num_rows($query_num);


	while($r = DB::fetch($query)) {

		$rows.=showtablerow(
			'',
			array(),
			$tdtext = array(
				'<input type="checkbox" class="checkbox" name="id_list[]" value="'.$r['id'].'" />',
				$r['name'],
				$r['start_pos'],
				$r['end_pos'],
				'<a href="'.ADMINSCRIPT.'?action=dazappguess&operation=edit_guess_type&id='.$r['id'].'" />编辑</a>'
			),
			TRUE);

	}

	$multipage = multi($guess_type_num, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=dazappguess&operation=guess_type_list&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage']);

	$_G['lang']['admincp']['adding_guess_type'] = '添加竞猜类型';
	$_G['lang']['admincp']['guess_activity_list_desc'] = '竞猜活动列表';
	$_G['lang']['admincp']['activity_result_desc'] = '竞猜结果列表';
	showsubmenu('guess_type',array( array('adding_guess_type', 'dazappguess&operation=add_guess_type', 1),array('guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1),array('activity_result_desc', 'dazappguess&operation=types_activities_list', 1) ) );

	$_G['lang']['admincp']['adding_guess_type_tips'] = '<li>搜索-请填写你的竞猜类型名称</li> <li>这里支持 模糊搜索和精确搜索 同时你也可以选择一页显示多少条记录</li>';
	showtips('adding_guess_type_tips');

	showformheader('dazappguess&operation=guess_type_list');

	$_G['lang']['admincp']['guess_type_tips'] = '共搜索到<font color="red"><strong> {guess_type_num} </strong></font>名符合条件的竞猜类型';
	showtableheader(cplang('guess_type_tips', array('guess_type_num' => $guess_type_num)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="guess_search" > <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 显示所有竞猜 ">');

	$_G['lang']['admincp']['guess_type_name'] = "竞猜类型名";
	$_G['lang']['admincp']['guess_type_start_pos'] = "竞猜开始名次";
	$_G['lang']['admincp']['guess_type_end_pos'] = "竞猜结束名次";
	$_G['lang']['admincp']['guess_type_operation'] = "操作";
	showsubtitle(array('', 'guess_type_name','guess_type_start_pos','guess_type_end_pos','guess_type_operation'));
	echo $rows;

	showsubmit('del_guess_submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'id_list\')" class="checkbox">'.cplang('del'), '', $multipage);
	showtablefooter();
	showformfooter();
}elseif($operation == 'edit_guess_type'){

	$id = getgpc('id');

	$query = DB::query("SELECT name,start_pos,end_pos FROM ". DB::table('daz_guessing_types')." WHERE id=".$id);
	$r = DB::fetch($query);

	echo '编辑竞猜类型<br/><br/>';

	showformheader('dazappguess&operation=handle_editing_guess_type', '');
	$_G['lang']['admincp']['guess_type_desc'] = '竞猜类型描述';
	$_G['lang']['admincp']['guess_type_list_desc'] = '返回竞猜类型列表';
	showsubmenu('guess_type_desc',array( array('guess_type_list_desc', 'dazappguess&operation=guess_type_list', 1) ) );
	$_G['lang']['admincp']['guess_type'] = '竞猜类型';
	showsetting('guess_type', 'guess_type',$r['name'], 'text','','','');
	echo '<br/><br/>';

	$_G['lang']['admincp']['guess_start_pos'] = '竞猜开始名次';
	showsetting('guess_start_pos', 'guess_start_pos',$r['start_pos'], 'text','','','');
	echo '<br/><br/>';

	$_G['lang']['admincp']['guess_end_pos'] = '竞猜结束名次';
	showsetting('guess_end_pos', 'guess_end_pos',$r['end_pos'], 'text','','','');
	echo '<br/><br/>';

	echo '<input type="hidden" name="id" value="'.$id.'"/>';

	showsubmit('add_guess_type_submit', 'submit');

	showformfooter();

}elseif($operation == 'handle_editing_guess_type'){


	$id        = getgpc('id');

	$guess_type_data['name']        = getgpc('guess_type');//竞猜类型
	$guess_type_data['start_pos']   = getgpc('guess_start_pos');//竞猜开始名次
    $guess_type_data['end_pos']     = getgpc('guess_end_pos');//竞猜结束名次

	if(mb_strlen($guess_type_data['name'],"UTF-8")==0){
		cpmsg('必须填写竞猜类型');
	}

	if(mb_strlen($guess_type_data['name'],"UTF-8")>30){
		cpmsg('竞猜类型必须在30字以内');
	}

	if(!ereg("^[1-9]{0,1}[0-9]{1}$",$guess_type_data['start_pos'])||$guess_type_data['start_pos']==0){
		cpmsg('竞猜开始名次必须是非零两位数字');
	}

	if(!ereg("^[1-9]{0,1}[0-9]{1}$",$guess_type_data['end_pos'])||$guess_type_data['end_pos']==0){
		cpmsg('竞猜结束名次必须是非零两位数字');
	}

	if( $guess_type_data['start_pos'] > $guess_type_data['end_pos'] ){
		cpmsg('竞猜开始名次大于竞猜结束名次');
	}

	require_once libfile ( 'class/users_activities_types_getter' );
	$uatg = new users_activities_types_getter();
	$amount = $uatg->get_amount_by_type_id($id);
	if($amount>0){
		cpmsg('已经有用户参与此类型的竞猜');
	}

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$amount = $gagtg->get_amount_by_type_id($id);
	if($amount>0){
		cpmsg('已经有活动与此类型关联');
	}

	$dgt = DB::table('daz_guessing_types');
	$sql = "LOCK TABLES ".$dgt." WRITE";
	DB::query($sql);

	$rows = DB::query("SELECT COUNT(*) from ".DB::table('daz_guessing_types')." where start_pos = ".$guess_type_data['start_pos']." AND end_pos=".$guess_type_data['end_pos']);
	$result = DB::fetch($rows);
	if($result["COUNT(*)"]<=1){
		$rows = DB::query("SELECT `name` from ".DB::table('daz_guessing_types')." where name = '".$guess_type_data['name']."'");
		$result = DB::fetch($rows);
		if($result==false){

			DB::update('daz_guessing_types',$guess_type_data," id = ".$id);
			$sql = "UNLOCK TABLES";
			DB::query($sql);
			cpmsg('编辑成功','action=dazappguess&operation=guess_type_list');
		}else{
			$sql = "UNLOCK TABLES";
			DB::query($sql);
			cpmsg('竞猜类型重复');
		}
	}else{
		$sql = "UNLOCK TABLES";
		DB::query($sql);
		cpmsg('竞猜开始名次和结束名次重复');
	}

}elseif($operation == 'add_guess_activity'){

	date_default_timezone_set("Asia/Shanghai");

	$game_list = array();
	$cmp = DB::table('common_member_profile');
	$cm = DB::table('common_member');
	$sql = "SELECT ".$cmp.".uid,".$cmp.".field1 FROM ".$cm.",".$cmp." WHERE ".$cm.".groupid = '25' AND ".$cm.".uid=".$cmp.".uid";
	$tmp = DB::query($sql);
	while($r = DB::fetch($tmp)){
		$game_list[] = array($r['uid'],$r['field1']);
	}

	$guess_type_list = array();
	$dgt = DB::table('daz_guessing_types');
	$sql = "SELECT id,name FROM ".$dgt;
	$tmp = DB::query($sql);
	while($r = DB::fetch($tmp)){
		$guess_type_list[] = array($r['id'],$r['name']);
	}

	showformheader('dazappguess&operation=handle_adding_guess_activity', 'enctype="multipart/form-data" onsubmit="edit_save()"');

	$_G['lang']['admincp']['adding_guess_activity_desc'] = '添加竞猜活动';
	$_G['lang']['admincp']['returning_guess_activity_list_desc'] = '返回竞猜活动列表';
	showsubmenu('adding_guess_activity_desc',array( array('returning_guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1) ) );
	echo '<script type="text/javascript" src="'.STATICURL.'image/editor/editor_function.js"></script>';
	showtableheader();

	$_G['lang']['admincp']['guess_activity_name_desc'] = '竞猜活动名称';
	showsetting('guess_activity_name_desc', 'name','', 'text','','','');

	$_G['lang']['admincp']['game_name_desc'] = '与活动相关的赛事';
	showsetting('game_name_desc', array('uid',$game_list), '', 'select','','','','onclick=""');

	$_G['lang']['admincp']['publish_or_not_desc'] = '是否发布活动';
	$option = array(
		array('是','是'),
		array('否','否')
	);
	showsetting('publish_or_not_desc', array('publish_or_not',$option),'', 'mradio2');

	$_G['lang']['admincp']['guess_type_desc'] = '请选择竞猜的类型';
	showsetting('guess_type_desc', array('guess_type_id',$guess_type_list), '', 'mcheckbox');

	$start_time = dgmdate(TIMESTAMP, 'Y-n-j H:i');

	showsetting('活动开始时间', 'start_time', $start_time, 'calendar', '', 0, '', 1);

	$end_time = dgmdate(TIMESTAMP + 86400* 7, 'Y-n-j H:i');
	showsetting('活动结束时间', 'end_time', $end_time, 'calendar', '', 0, '', 1);

	$_G['lang']['admincp']['guess_activity_pic_desc'] = '与竞猜活动相关的图片';
	showsetting('guess_activity_pic_desc', 'pic_name','', 'file');

	$_G['lang']['admincp']['sorting_num_desc'] = '活动排序数字';
	showsetting('sorting_num_desc', 'sorting_num','', 'text','','','填写你排序的 数字  0-100..之间 数字越小活动越靠前');

	$_G['lang']['admincp']['activity_desc1'] = '活动描述1';
	showsetting('activity_desc1', 'desc1', '', 'textarea');

	$src = 'home.php?mod=editor&charset='.CHARSET.'&allowhtml=1&doodle=0';
	echo '
		<tr><td></td><td></td></tr>
		<tr>
			<td colspan="2">
				<textarea class="userData" name="desc2" id="uchome-ttHtmlEditor" style="height:100%;width:100%;display:none;border:0px"></textarea>
				<iframe src="'.$src.'" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;border: 1px solid #C5C5C5;" height="400"></iframe>
			<td>
		</tr>
	';

	showsubmit('add_guess_submit', 'submit');
	showtablefooter();
	showformfooter();
}elseif($operation == 'handle_adding_guess_activity'){


	$guess_activity_data['name'] = getgpc('name');
	$guess_activity_data['uid'] = getgpc('uid');
	$guess_activity_data['publish_or_not'] = getgpc('publish_or_not');
	$guess_activity_data['guess_type_id'] = getgpc('guess_type_id');
	$guess_activity_data['start_time'] = getgpc('start_time');
	$guess_activity_data['end_time'] = getgpc('end_time');
	$guess_activity_data['sorting_num'] = getgpc('sorting_num');
	$guess_activity_data['desc1'] = getgpc('desc1');
	$guess_activity_data['desc2'] = getgpc('desc2');




	require_once libfile ( 'class/str_len_checker' );
	$slc = new str_len_checker();
	$tmp = $slc->check($guess_activity_data['name'],1,30,'竞猜活动名称');
	if($tmp!==true){
		cpmsg($tmp);
	}
	/*
	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	if($gag->name_exist_or_not($guess_activity_data['name'])==1){
		cpmsg('活动名称重复');
	}
	*/
	require_once libfile ( 'class/game_getter' );
	$ug = new game_getter();
	$r = $ug->get();
	$count = 0;
	for($i=0;$i<count($r);$i++){
		if($guess_activity_data['uid']==$r[$i][0]){
			break;
		}
		$count++;
	}

	if($count==count($r)){
		cpmsg('与活动相关的赛事无效');
	}

	if($guess_activity_data['publish_or_not']==''){
		cpmsg('发布活动的值必须填写');
	}

	if( !($guess_activity_data['publish_or_not']=='是'||$guess_activity_data['publish_or_not']=='否') ){
		cpmsg('是否发布活动的值无效');
	}

	if($guess_activity_data['guess_type_id']==''){
		cpmsg('至少选择一个竞猜类型');
	}else{
		require_once libfile ( 'class/guess_type_getter' );
		$gtg = new guess_type_getter();
		$r = $gtg->get();
		$tmp = array();
		for($i=0;$i<count($r);$i++){
			$tmp[] = $r[$i][0];
		}

		for($i=0;$i<count($guess_activity_data['guess_type_id']);$i++){
			if (!in_array($guess_activity_data['guess_type_id'][$i],$tmp)) {
				cpmsg('竞猜类型无效');
				break;
			}
		}

	}

	require_once libfile ( 'class/date_time_checker' );
	$dtc = new date_time_checker();
	$tmp = $dtc->check($guess_activity_data['start_time']);
	if($tmp!==true){
		cpmsg('活动开始'.$tmp);
	}

	$tmp = $dtc->check($guess_activity_data['end_time'],'活动结束');
	if($tmp!==true){
		cpmsg('活动结束'.$tmp);
	}

	require_once libfile ( 'class/sorting_num_checker' );
	$snc = new sorting_num_checker();
	$tmp = $snc->check($guess_activity_data['sorting_num']);
	if($tmp!==true){
		cpmsg($tmp);
	}

	$slc = new str_len_checker();
	$tmp = $slc->check($guess_activity_data['desc1'],1,200,'活动描述1');
	if($tmp!==true){
		cpmsg($tmp);
	}

	$tmp = $slc->check($guess_activity_data['desc2'],1,200,'活动描述2');
	if($tmp!==true){
		cpmsg($tmp);
	}

	require_once libfile ( 'class/game_player_counter' );
	$gpc = new game_player_counter();
	$game_player_amount = $gpc->get_game_player_amount($guess_activity_data['uid']);

	require_once libfile ( 'class/guess_type_max_player_counter' );
	$gtmpc = new guess_type_max_player_counter();

	$guess_type_max_player_amount = $gtmpc->get_guess_type_max_player_amount(implode(",", $guess_activity_data['guess_type_id']));

	//var_dump($game_player_amount);var_dump($guess_type_max_player_amount);var_dump($game_player_amount<$guess_type_max_player_amount);exit;

	if($game_player_amount<$guess_type_max_player_amount){
		cpmsg("赛事与竞猜类型不匹配");
	}


	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			$upload->init ( $file, 'daz_app' );
			$attach = $upload->attach;




			if (! $upload->error ()) {
				$upload->save ();


				if (! $upload->get_image_info ( $attach ['target'] )) {
					@unlink ( $attach ['target'] );
					continue;
				}

				$attach ['attachment'] = dhtmlspecialchars ( trim ( $attach ['attachment'] ) );
				if ($_G ['cache'] ['fields_register'] [$field_key] ['needverify']) {
					$verifyarr [$key] = $attach ['attachment'];
				} else {
					$profile [$key] = $attach ['attachment'];
				}
			}

		}
	}
	//
	$dga = DB::table('daz_guess_activities');
	$sql = "LOCK TABLES ".$dga." WRITE";
	DB::query($sql);
	//
	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	if($gag->name_exist_or_not($guess_activity_data['name'])==1){
		//
		$sql = "UNLOCK TABLES";
		DB::query($sql);
		//
		cpmsg('活动名称重复');
	}

	require_once libfile ( 'class/guess_activity_adder' );
	$gaa = new guess_activity_adder();
	$guess_activity_id = $gaa->add($guess_activity_data['name'],$guess_activity_data['uid'],$guess_activity_data['publish_or_not'],$guess_activity_data['start_time'],$guess_activity_data['end_time'],$attach["attachment"],$guess_activity_data['sorting_num'],$guess_activity_data['desc1'],$guess_activity_data['desc2']);
	//
	$sql = "UNLOCK TABLES";
	DB::query($sql);
	//

	require_once libfile ( 'class/guess_activities_guessing_types_adder' );

	$gagta = new guess_activities_guessing_types_adder();
	//
	$dga = DB::table('daz_guess_activities_guessing_types');
	$sql = "LOCK TABLES ".$dga." WRITE";
	DB::query($sql);
	//
	for($i=0;$i<count($guess_activity_data['guess_type_id']);$i++){
		$gagta->add($guess_activity_id,$guess_activity_data['guess_type_id'][$i]);
	}
	//
	$sql = "UNLOCK TABLES";
	DB::query($sql);
	//
	cpmsg('添加竞猜活动成功','action=dazappguess&operation=guess.activity.list');

}elseif($operation == 'guess.activity.list'){

	if(submitcheck('del_guess_submit')){

		$activity_id_set = getgpc('id_list');

		require_once libfile ( 'class/guess_activities_guessing_types_getter' );
		$gagtg = new guess_activities_guessing_types_getter();

		$guessing_type_id_set = array();
		for($i=0;$i<count($activity_id_set);$i++){
			$tmp = $gagtg->get_by_activity_id($activity_id_set[$i]);
			$guessing_type_id_set[] = $tmp[$i]['guessing_type_id'];
		}


		require_once libfile ( 'class/users_activities_types_getter' );
		$uatg = new users_activities_types_getter();

		/*
		for($i=0;$i<count($guessing_type_id_set);$i++){
			$amount = $uatg->get_amount_by_type_id($guessing_type_id_set[$i]);
			if($amount>0){

				cpmsg('已经有用户参与此类型的竞猜');
			}
		}
		*/

		for($i=0;$i<count($activity_id_set);$i++){
			$amount = $uatg->get_amount_by_activity_id($activity_id_set[$i]);
			if($amount>0){

				cpmsg('已经有用户参与此竞猜');
			}
		}

		$id = implode(',',getgpc('id_list'));
		cpmsg('attr_delete_sure',"action=dazappguess&operation=guess.activity.list&action_status=deleting_flag",'form','',"<input name=\"id\" value=\"".$id."\" type=\"hidden\"/>");

	}

	if($_G['gp_action_status']=='deleting_flag'){
			/*
			if(strlen(getgpc('id'))!=0){
				require_once libfile ( 'class/guess_activity_deleter' );
				$gad = new guess_activity_deleter();
				$gad->delete(getgpc('id'));

				require_once libfile ( 'class/guess_activity_type_deleter' );
				$gatd = new guess_activity_type_deleter();
				$gatd->delete(getgpc('id'));
			}
			*/

			$activity_id_str = getgpc('id');
			$activity_id_set = explode(",",$activity_id_str);


			if(strlen(getgpc('id'))!=0){

				require_once libfile ( 'class/guess_activities_guessing_types_getter' );
				$gagtg = new guess_activities_guessing_types_getter();

				require_once libfile ( 'class/daz_guess_activities_guessing_types_result_deleter' );
				$dgagtrd = new daz_guess_activities_guessing_types_result_deleter();

				//
				$dgagt = DB::table('daz_guess_activities_guessing_types');
				$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
				$dga = DB::table('daz_guess_activities');
				$sql = "LOCK TABLES ".$dgagt." WRITE,".$dgagtr." WRITE,".$dga." WRITE";
				DB::query($sql);
				//

				for($i=0;$i<count($activity_id_set);$i++){
					$tmp = $gagtg->get_by_activity_id($activity_id_set[$i]);
					for($j=0;$j<count($tmp);$j++){

						$dgagtrd->delete($tmp[$j]['id']);
					}
				}

				require_once libfile ( 'class/guess_activities_guessing_types_deleter' );
				$gagtd = new guess_activities_guessing_types_deleter();
				for($i=0;$i<count($activity_id_set);$i++){
					$gagtd->physically_delete_by_activity_id($activity_id_set[$i]);
				}

				require_once libfile ( 'class/guess_activity_deleter' );
				$gad = new guess_activity_deleter();
				$gad->delete($activity_id_str);

				//
				$sql = "UNLOCK TABLES";
				DB::query($sql);
				//

			}

	}

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$guess_activity_amount = $gag->get_guess_activity_amount();

	$r = $gag->get_info_perpage($where,$start_limit,$_G['setting']['guess_perpage']);

	for($i=0;$i<count($r);$i++){
		$rows.=showtablerow('', array(), array(
				'<input type="checkbox" class="checkbox" name="id_list[]" value="'.$r[$i]['id'].'" />',
				$r[$i]['name'],
				$r[$i]['publish_or_not'],
				$r[$i]['start_time'],
				$r[$i]['end_time'],
				$r[$i]['sorting_num'],
                "<a href='admin.php?action=dazappguess&operation=edit_guess_activity&id=".$r[$i]['id']."'>编辑</a>&nbsp;|&nbsp;<a href='admin.php?action=dazappguess&operation=guess_result_list&id=".$r[$i]['id']."'>查看竞猜结果</a>&nbsp;|&nbsp;<a href='admin.php?action=dazappguess&operation=add_guess_result_address&id=".$r[$i]['id']."'>填写竞猜结果的地址</a>"
				),TRUE);
	}

	$multipage = multi($guess_activity_amount, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=dazappguess&operation=guess.activity.list&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage']);

	$_G['lang']['admincp']['managing_guess_activity_desc'] = '管理竞猜活动';
	$_G['lang']['admincp']['adding_guess_activity_desc'] = '添加竞猜活动';

	$_G['lang']['admincp']['guess_type_list_desc'] = '竞猜类型列表';
	$_G['lang']['admincp']['activity_result_desc'] = '竞猜结果列表';

	showsubmenu('managing_guess_activity_desc',array( array('adding_guess_activity_desc', 'dazappguess&operation=add_guess_activity', 1),array('guess_type_list_desc', 'dazappguess&operation=guess_type_list', 1),array('activity_result_desc', 'dazappguess&operation=types_activities_list', 1) ) );

	$_G['lang']['admincp']['searching_guess_activity_tip_desc'] = '<li>搜索-请填写你的竞猜活动名称</li> <li>这里支持 模糊搜索和精确搜索 同时你也可以选择一页显示多少条记录</li>';
	showtips('searching_guess_activity_tip_desc');

	showformheader('dazappguess&operation=guess.activity.list');

	$_G['lang']['admincp']['searching_bar_desc'] = '共搜索到<font color="red"><strong> {guess_activity_amount} </strong></font>个符合条件的竞猜活动';
	showtableheader(cplang('searching_bar_desc', array('guess_activity_amount' => $guess_activity_amount)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="guess_search" > <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 显示所有竞猜 ">');

	$_G['lang']['admincp']['guess_activity_name_desc'] = "竞猜活动名";
	$_G['lang']['admincp']['guess_activity_publish_desc'] = "是否发布活动";
	$_G['lang']['admincp']['guess_activity_start_time_desc'] = "竞猜活动开始时间";
	$_G['lang']['admincp']['guess_activity_end_time_desc'] = "竞猜活动结束时间";
	$_G['lang']['admincp']['guess_activity_sorting_num_desc'] = "竞猜活动排序数字";
	$_G['lang']['admincp']['guess_activity_operation_desc'] = "操作";

	showsubtitle(array('', 'guess_activity_name_desc','guess_activity_publish_desc','guess_activity_start_time_desc','guess_activity_end_time_desc','guess_activity_sorting_num_desc','guess_activity_operation_desc'));
	echo $rows;

	showsubmit('del_guess_submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'id_list\')" class="checkbox">'.cplang('del'), '', $multipage);
	showtablefooter();
	showformfooter();
}elseif($operation == 'edit_guess_activity'){



	$game_list = array();
	$cmp = DB::table('common_member_profile');
	$cm = DB::table('common_member');
	$sql = "SELECT ".$cmp.".uid,".$cmp.".field1 FROM ".$cm.",".$cmp." WHERE ".$cm.".groupid = '25' AND ".$cm.".uid=".$cmp.".uid";
	$tmp = DB::query($sql);
	while($r = DB::fetch($tmp)){
		$game_list[] = array($r['uid'],$r['field1']);
	}

	$guess_type_list = array();
	$dgt = DB::table('daz_guessing_types');
	$sql = "SELECT id,name FROM ".$dgt;
	$tmp = DB::query($sql);
	while($r = DB::fetch($tmp)){
		$guess_type_list[] = array($r['id'],$r['name']);
	}

	$id = getgpc('id');

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$rows = $gagtg->get_by_activity_id($id);
	for($i=0;$i<count($rows);$i++){
		$guessing_type_ids[] = $rows[$i]['guessing_type_id'];
	}

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$r = $gag->get_info_by_id($id);

	for($i=0;$i<count($guess_type_list);$i++){
		if($guess_type_list[$i]['id']==$r['id']){
			$tmp = $guess_type_list[$i];
			$guess_type_list[$i] = $guess_type_list[0];
			$guess_type_list[0] = $tmp;
		}
	}

	showformheader('dazappguess&operation=handle_editing_guess_activity&id', 'enctype="multipart/form-data" onsubmit="edit_save()"');

	$_G['lang']['admincp']['editing_guess_activity_desc'] = '修改竞猜活动';
	$_G['lang']['admincp']['returning_guess_activity_list_desc'] = '返回竞猜活动列表';
	showsubmenu('editing_guess_activity_desc',array( array('returning_guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1) ) );

	echo '<script type="text/javascript" src="'.STATICURL.'image/editor/editor_function.js"></script>';
	showtableheader();

	echo ' <input name="id" value="'.$id.'" type="hidden">';

	$_G['lang']['admincp']['guess_activity_name_desc'] = '竞猜活动名称';
	showsetting('guess_activity_name_desc', 'name',$r['name'], 'text','','','');

	$_G['lang']['admincp']['game_name_desc'] = '与活动相关的赛事';
	showsetting('game_name_desc', array('uid',$game_list), '', 'select','','','','onclick=""');

	$_G['lang']['admincp']['publish_or_not_desc'] = '是否发布活动';
	$option = array(
		array('是','是'),
		array('否','否')
	);
	showsetting('publish_or_not_desc', array('publish_or_not',$option),$r['publish_or_not'], 'mradio2');



	$_G['lang']['admincp']['guess_type_desc'] = '请选择竞猜的类型';
	showsetting('guess_type_desc', array('guess_type_id',$guess_type_list), $guessing_type_ids, 'mcheckbox');


	showsetting('活动开始时间', 'start_time', $r['start_time'], 'calendar', '', 0, '', 1);

	showsetting('活动结束时间', 'end_time', $r['end_time'], 'calendar', '', 0, '', 1);

	$_G['lang']['admincp']['guess_activity_pic_desc'] = '与竞猜活动相关的图片';
	showsetting('guess_activity_pic_desc', 'pic_name','', 'file');

	$_G['lang']['admincp']['sorting_num_desc'] = '活动排序数字';
	showsetting('sorting_num_desc', 'sorting_num',$r['sorting_num'], 'text','','','填写你排序的 数字  0-100..之间 数字越小活动越靠前');

	$_G['lang']['admincp']['activity_desc1'] = '活动描述1';
	showsetting('activity_desc1', 'desc1', $r['desc1'], 'textarea');

	$src = 'home.php?mod=editor&charset='.CHARSET.'&allowhtml=1&doodle=0';
	echo '
		<tr><td></td><td></td></tr>
		<tr>
			<td colspan="2">
				<textarea class="userData" name="desc2" id="uchome-ttHtmlEditor" style="height:100%;width:100%;display:none;border:0px">'.$r['desc2'].'</textarea>
				<iframe src="'.$src.'" name="uchome-ifrHtmlEditor" id="uchome-ifrHtmlEditor" scrolling="no" border="0" frameborder="0" style="width:100%;border: 1px solid #C5C5C5;" height="400"></iframe>
			<td>
		</tr>
	';

	showsubmit('add_guess_submit', 'submit');
	showtablefooter();
	showformfooter();

}elseif($operation == 'handle_editing_guess_activity'){
	$guess_activity_data['name'] = getgpc('name');
	$guess_activity_data['uid'] = getgpc('uid');
	$guess_activity_data['publish_or_not'] = getgpc('publish_or_not');
	$guess_activity_data['guess_type_id'] = getgpc('guess_type_id');
	$guess_activity_data['start_time'] = getgpc('start_time');
	$guess_activity_data['end_time'] = getgpc('end_time');
	$guess_activity_data['sorting_num'] = getgpc('sorting_num');
	$guess_activity_data['desc1'] = getgpc('desc1');
	$guess_activity_data['desc2'] = getgpc('desc2');



	require_once libfile ( 'class/str_len_checker' );
	$slc = new str_len_checker();
	$tmp = $slc->check($guess_activity_data['name'],1,30,'竞猜活动名称');
	if($tmp!==true){
		cpmsg($tmp);
	}
	/*
	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	if($gag->name_exist_or_not($guess_activity_data['name'])==1){
		cpmsg('活动名称重复');
	}
	*/
	require_once libfile ( 'class/game_getter' );
	$ug = new game_getter();
	$r = $ug->get();
	$count = 0;
	for($i=0;$i<count($r);$i++){
		if($guess_activity_data['uid']==$r[$i][0]){
			break;
		}
		$count++;
	}

	if($count==count($r)){
		cpmsg('与活动相关的赛事无效');
	}

	if($guess_activity_data['publish_or_not']==''){
		cpmsg('发布活动的值必须填写');
	}

	if( !($guess_activity_data['publish_or_not']=='是'||$guess_activity_data['publish_or_not']=='否') ){
		cpmsg('是否发布活动的值无效');
	}

	if($guess_activity_data['guess_type_id']==''){
		cpmsg('至少选择一个竞猜类型');
	}else{
		require_once libfile ( 'class/guess_type_getter' );
		$gtg = new guess_type_getter();
		$r = $gtg->get();
		$tmp = array();
		for($i=0;$i<count($r);$i++){
			$tmp[] = $r[$i][0];
		}

		for($i=0;$i<count($guess_activity_data['guess_type_id']);$i++){
			if (!in_array($guess_activity_data['guess_type_id'][$i],$tmp)) {
				cpmsg('竞猜类型无效');
				break;
			}
		}

	}

	require_once libfile ( 'class/date_time_checker' );
	$dtc = new date_time_checker();
	$tmp = $dtc->check($guess_activity_data['start_time']);
	if($tmp!==true){
		cpmsg('活动开始'.$tmp);
	}

	$tmp = $dtc->check($guess_activity_data['end_time'],'活动结束');
	if($tmp!==true){
		cpmsg('活动结束'.$tmp);
	}

	require_once libfile ( 'class/sorting_num_checker' );
	$snc = new sorting_num_checker();
	$tmp = $snc->check($guess_activity_data['sorting_num']);
	if($tmp!==true){
		cpmsg($tmp);
	}

	$slc = new str_len_checker();
	$tmp = $slc->check($guess_activity_data['desc1'],1,200,'活动描述1');
	if($tmp!==true){
		cpmsg($tmp);
	}

	$tmp = $slc->check($guess_activity_data['desc2'],1,200,'活动描述2');
	if($tmp!==true){
		cpmsg($tmp);
	}

	require_once libfile ( 'class/game_player_counter' );
	$gpc = new game_player_counter();
	$game_player_amount = $gpc->get_game_player_amount($guess_activity_data['uid']);

	require_once libfile ( 'class/guess_type_max_player_counter' );
	$gtmpc = new guess_type_max_player_counter();

	$guess_type_max_player_amount = $gtmpc->get_guess_type_max_player_amount(implode(",", $guess_activity_data['guess_type_id']));

	if($game_player_amount<$guess_type_max_player_amount){
		cpmsg("赛事与竞猜类型不匹配");
	}


	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			$upload->init ( $file, 'daz_app' );
			$attach = $upload->attach;




			if (! $upload->error ()) {
				$upload->save ();


				if (! $upload->get_image_info ( $attach ['target'] )) {
					@unlink ( $attach ['target'] );
					continue;
				}

				$attach ['attachment'] = dhtmlspecialchars ( trim ( $attach ['attachment'] ) );
				if ($_G ['cache'] ['fields_register'] [$field_key] ['needverify']) {
					$verifyarr [$key] = $attach ['attachment'];
				} else {
					$profile [$key] = $attach ['attachment'];
				}
			}

		}
	}


	/*
	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$tmp = $gagtg->get_by_activity_id(getgpc('id'));

	$original_type_id_set = array();
	for($i=0;$i<count($tmp);$i++){
		$original_type_id_set[] = $tmp[$i]["guessing_type_id"];
	}


	require_once libfile ( 'class/deleting_type_id_set_getter' );
	$dtisg = new deleting_type_id_set_getter();
	$deleting_type_id_set = $dtisg->get($original_type_id_set,$guess_activity_data['guess_type_id']);


	require_once libfile ( 'class/users_activities_types_getter' );
	$uatg = new users_activities_types_getter();
	for($i=0;$i<count($deleting_type_id_set);$i++){
		$amount = $uatg->get_amount_by_type_id($deleting_type_id_set[$i]);
		if($amount>0){
			cpmsg('已经有用户参与此类型的竞猜');
		}
	}
	*/

	$guessing_type_ids = getgpc('guess_type_id');
	//
	$dga = DB::table('daz_guess_activities');
	$sql = "LOCK TABLES ".$dga." WRITE";
	DB::query($sql);
	//
	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	if($gag->name_exist_or_not($guess_activity_data['name'])>1){
		//
		$sql = "UNLOCK TABLES";
		DB::query($sql);
		//
		cpmsg('活动名称重复');
	}

	require_once libfile ( 'class/guess_activity_updater' );
	$gau = new guess_activity_updater();
	$gau->update(getgpc('id'),$guess_activity_data['name'],$guess_activity_data['publish_or_not'],$guess_activity_data['uid'],$guess_activity_data['start_time'],$guess_activity_data['end_time'],$attach["attachment"],$guess_activity_data['sorting_num'],$guess_activity_data['desc1'],$guess_activity_data['desc2']);
	//
	$sql = "UNLOCK TABLES";
	DB::query($sql);
	//

	//
	$dgagt = DB::table('daz_guess_activities_guessing_types');
	$uat = DB::table('users_activities_types');
	$sql = "LOCK TABLES ".$uat." READ,".$dgagt." WRITE";
	DB::query($sql);
	//
	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$tmp = $gagtg->get_by_activity_id(getgpc('id'));

	$original_type_id_set = array();
	for($i=0;$i<count($tmp);$i++){
		$original_type_id_set[] = $tmp[$i]["guessing_type_id"];
	}

	require_once libfile ( 'class/deleting_type_id_set_getter' );
	$dtisg = new deleting_type_id_set_getter();
	$deleting_type_id_set = $dtisg->get($original_type_id_set,$guess_activity_data['guess_type_id']);


	require_once libfile ( 'class/users_activities_types_getter' );
	$uatg = new users_activities_types_getter();
	for($i=0;$i<count($deleting_type_id_set);$i++){
		$amount = $uatg->get_amount_by_type_id($deleting_type_id_set[$i]);
		if($amount>0){
			//
			$sql = "UNLOCK TABLES";
			DB::query($sql);
			//
			cpmsg('已经有用户参与此类型的竞猜');
		}
	}

	require_once libfile ( 'class/guess_activities_guessing_types_deleter' );
	$gagtd = new guess_activities_guessing_types_deleter();
	$gagtd->delete_by_activity_id(getgpc('id'));

	require_once libfile ( 'class/guess_activities_guessing_types_adder' );
	$gagta = new guess_activities_guessing_types_adder();
	for($i=0;$i<count($guessing_type_ids);$i++){
		$gagta->add(getgpc('id'),$guessing_type_ids[$i]);
	}

	//
	$sql = "UNLOCK TABLES";
	DB::query($sql);
	//

	cpmsg('修改竞猜活动成功','action=dazappguess&operation=guess.activity.list');




}elseif($operation == 'types_activities_list'){


	if(submitcheck('del_guess_submit')){
		$id = implode(',',getgpc('id_list'));
		cpmsg('attr_delete_sure',"action=dazappguess&operation=guess.activity.list&action_status=deleting_flag",'form','',"<input name=\"id\" value=\"".$id."\" type=\"hidden\"/>");
	}

	if($_G['gp_action_status']=='deleting_flag'){


			if(strlen(getgpc('id'))!=0){
				require_once libfile ( 'class/guess_activity_deleter' );
				$gad = new guess_activity_deleter();
				$gad->delete(getgpc('id'));

				require_once libfile ( 'class/guess_activity_type_deleter' );
				$gatd = new guess_activity_type_deleter();
				$gatd->delete(getgpc('id'));
			}

	}
	echo '1668';
	require_once libfile ( 'class/types_activities_getter' );
	$tag = new types_activities_getter();
	$amount = $tag->get_amount(' WHERE delete_or_not=0 ');
	$r = $tag->get_info_perpage($start_limit,$_G['setting']['guess_perpage']);

	for($i=0;$i<count($r);$i++){
		$rows.=showtablerow('', array(), array(
			$r[$i]['activity_name'],
			$r[$i]['type_name'],
			$r[$i]['start_time'],
			$r[$i]['end_time'],
			"<a href='admin.php?action=dazappguess&operation=add_guess_result&activity_id=".$r[$i]['activity_id']."&type_id=".$r[$i]['type_id']."'>填写竞猜结果</a>"
		),TRUE);
	}

	$multipage = multi($amount, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=dazappguess&operation=types_activities_list&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage']);

	$_G['lang']['admincp']['activity_result_desc'] = '浏览竞猜结果';
	$_G['lang']['admincp']['guess_activity_list_desc'] = '竞猜活动列表';
	$_G['lang']['admincp']['guess_type_list_desc'] = '竞猜类型列表';
	showsubmenu('activity_result_desc',array( array('', 'dazappguess&operation=add_guess_activity', 1),array('guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1),array('guess_type_list_desc', 'dazappguess&operation=guess_type_list', 1) ) );

	$_G['lang']['admincp']['searching_guess_activity_tip_desc'] = '<li>搜索-请填写你的竞猜活动名称</li> <li>这里支持 模糊搜索和精确搜索 同时你也可以选择一页显示多少条记录</li>';
	showtips('searching_guess_activity_tip_desc');

	showformheader('dazappguess&operation=types_activities_list');

	$_G['lang']['admincp']['searching_bar_desc'] = '共搜索到<font color="red"><strong> {guess_activity_amount} </strong></font>个符合条件的竞猜活动';
	showtableheader(cplang('searching_bar_desc', array('guess_activity_amount' => $amount)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="guess_search" > <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 显示所有竞猜 ">');

	$_G['lang']['admincp']['guess_activity_name_desc'] = "竞猜活动名";
	$_G['lang']['admincp']['guess_activity_type_name_desc'] = "竞猜类型名";
	$_G['lang']['admincp']['guess_activity_start_time_desc'] = "竞猜活动开始时间";
	$_G['lang']['admincp']['guess_activity_end_time_desc'] = "竞猜活动结束时间";
	$_G['lang']['admincp']['guess_activity_operation_desc'] = "操作";

	showsubtitle(array( 'guess_activity_name_desc','guess_activity_type_name_desc','guess_activity_start_time_desc','guess_activity_end_time_desc','guess_activity_operation_desc'));
	echo $rows;

	showsubmit('', '', '', '', $multipage);
	showtablefooter();
	showformfooter();

}elseif($operation == 'add_guess_result'){

	$activity_id = getgpc('activity_id');
	$type_id = getgpc('type_id');

	showformheader('dazappguess&operation=handle_adding_guess_result', '');

	$_G['lang']['admincp']['editing_guess_result_desc'] = '填写竞猜结果';
	$_G['lang']['admincp']['returning_guess_result_list_desc'] = '返回竞猜结果列表';
	showsubmenu('editing_guess_result_desc',array( array('returning_guess_result_list_desc', 'dazappguess&operation=types_activities_list', 1) ) );

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$r = $gag->get_info_by_id($activity_id);
	$activity_name = $r["name"];
	$group_id  = $r['group_id'];

	echo '<input name="activity_id" type="hidden" value="'.$activity_id.'" />';
	echo '<input name="type_id" type="hidden" value="'.$type_id.'" />';
	echo '<input name="group_id" type="hidden" value="'.$group_id.'" />';

	showtableheader();

	require_once libfile ( 'class/guess_type_getter' );
	$gtg = new guess_type_getter();
	$r = $gtg->get_info_by_id_str($type_id);

	$type_name = $r[0]["name"];
	$type_start_pos = $r[0]["start_pos"];
	$type_end_pos = $r[0]["end_pos"];

	$input_amount = $type_end_pos - $type_start_pos + 1;

	require_once libfile ( 'class/player_info_getter' );
	$pig = new player_info_getter();
	$records = $pig->get_player_info_by_group_id($group_id);

	$player_list = array();
	for($i=0;$i<count($records);$i++){
		$player_list[] = array($records[$i][0],$records[$i][1]);
	}
	//var_dump($player_list);
	echo $activity_name."&nbsp;".$type_name;

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$activity_type_id = $gagtg->get_id_by_activity_id_type_id($activity_id,$type_id);


	require_once libfile ( 'class/daz_guess_activities_guessing_types_result_getter' );
	$dgagtrg = new daz_guess_activities_guessing_types_result_getter();
	$r = $dgagtrg->get_by_activity_type_id($activity_type_id);
	//var_dump($r);
	//var_dump($player_list);
	if($r==''){
		$player_list[] = array('','尚未指定球员');
		for($i=0;$i<$input_amount;$i++){
			$_G['lang']['admincp']['player_list_desc'] = '获得第'.($type_start_pos+$i).'名的球员';
			showsetting('player_list_desc', array('uid[]',$player_list), '', 'select','','','','onclick=""');
		}
	}else{

		for($j=0;$j<count($r);$j++){

			for($k=0;$k<count($player_list);$k++){
				if($r[$j]["uid"]==$player_list[$k][0]){
					$tmp = $player_list[0];
					$player_list[0] = $player_list[$k];
					$player_list[$k] = $tmp;

					break;
				}
			}
			$_G['lang']['admincp']['player_list_desc'] = '获得第'.($type_start_pos+$j).'名的球员';
			showsetting('player_list_desc', array('uid[]',$player_list), '', 'select','','','','onclick=""');

		}


	}

	showsubmit('add_guess_submit', 'submit');
	showtablefooter();
	showformfooter();

}elseif($operation == 'handle_adding_guess_result'){

	$activity_id = getgpc('activity_id');
	$type_id = getgpc('type_id');
	$group_id = getgpc('group_id');
	$uid_arr = getgpc('uid');

	for($i=0;$i<count($uid_arr);$i++){
		if($uid_arr[$i]==''){
			cpmsg('需要指定球员');
		}
	}

	require_once libfile ( 'class/guess_type_getter' );
	$gtg = new guess_type_getter();
	$r = $gtg->get_info_by_id_str($type_id);
	$start_pos = $r[0]['start_pos'];
	$end_pos = $r[0]['end_pos'];
	if(count($uid_arr)!=($end_pos-$start_pos+1)){
		cpmsg('球员数目不符');
	}

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$r = $gag->get_info_by_id($activity_id);

	if($r["group_id"]!=$group_id){
		cpmsg('赛事与活动不符');
	}

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$r = $gagtg->get_by_activity_id($activity_id);



	$guessing_type_id_arr = array();
	for($i=0;$i<count($r);$i++){
		$guessing_type_id_arr[] = $r[$i]["guessing_type_id"];
	}

	if(!in_array($type_id,$guessing_type_id_arr)){
		cpmsg('没有此类型的活动');
	}
	/*
	$uid_arr_len = count($uid_arr);

	if(count($uid_arr)>1){

		for($i=0;$i<$uid_arr_len-1;$i++){

			for($j=$i;$j<($uid_arr_len-($i+1));$j++){
				if($uid_arr[$i]==$uid_arr[$j+1]){
					cpmsg('球员名不允许重复');
				}
			}

		}

	}
	*/

	if(count(array_unique($uid_arr))!=count($uid_arr)){
		cpmsg('球员名不允许重复');
	}

	require_once libfile ( 'class/player_info_getter' );
	$pig = new player_info_getter();
	$records = $pig->get_player_info_by_group_id($group_id);

	$player_id_arr = array();
	for($i=0;$i<count($records);$i++){
		$player_id_arr[] = $records[$i][0];
	}


	for($i=0;$i<$uid_arr_len;$i++){
		if(!in_array($uid_arr[$i],$player_id_arr)){
			cpmsg('球员没有参加与活动相关的赛事');
		}
	}

	/*
	require_once libfile ( 'class/users_activities_types_getter' );
	$uatg = new users_activities_types_getter();
	$amount = $uatg->get_amount_by_type_id($type_id);
	if($amount>0){
		cpmsg('已经有用户参与此类型的竞猜');
	}
	*/
	/*
	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$amount = $gagtg->get_amount_by_type_id($id);
	if($amount>0){
		cpmsg('已经有活动与此类型关联');
	}
	*/

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();
	$activity_type_id = $gagtg->get_id_by_activity_id_type_id($activity_id,$type_id);



	require_once libfile ( 'class/daz_guess_activities_guessing_types_result_getter' );
	$dgagtrg = new daz_guess_activities_guessing_types_result_getter();

	$dgagtr = DB::table('daz_guess_activities_guessing_types_result');
	$sql = "LOCK TABLES ".$dgagtr." WRITE";
	DB::query($sql);

	$r = $dgagtrg->get_by_activity_type_id($activity_type_id);


	if($r==''){

		require_once libfile ( 'class/daz_guess_activities_guessing_types_result_adder' );
		$dgagtra = new daz_guess_activities_guessing_types_result_adder();
		for($i=0;$i<count($uid_arr);$i++){
			$dgagtra->add($activity_type_id,$uid_arr[$i]);
		}

	}else{

		require_once libfile ( 'class/daz_guess_activities_guessing_types_result_updater' );
		$dgagtru = new daz_guess_activities_guessing_types_result_updater();

		for($i=0;$i<count($r);$i++){
			$dgagtru->update($r[$i]["id"],$activity_type_id,$uid_arr[$i]);
		}


	}

	$sql = "UNLOCK TABLES";
	DB::query($sql);

	cpmsg('填写竞猜结果成功','action=dazappguess&operation=types_activities_list');

}elseif($operation == 'guess_result_list'){


	$activity_id = getgpc('id');

	require_once libfile ( 'class/users_activities_types_getter' );

	$uatg = new users_activities_types_getter();
	$amount = $uatg->get_amount_by_activity_id($activity_id);

	$info_perpage = $uatg->get_info_perpage($activity_id,$start_limit,$_G['setting']['guess_perpage']);


	$rows = '';

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();

	require_once libfile ( 'class/guess_type_getter' );
	$gtg = new guess_type_getter();

	require_once libfile ( 'class/common_member_profile_getter' );
	$cmpg = new common_member_profile_getter();

	require_once libfile ( 'class/users_activities_types_getter' );
	$uatg = new users_activities_types_getter();

	require_once libfile ( 'class/guess_result_getter' );
	$grg = new guess_result_getter();

	require_once libfile ( 'class/guess_activities_guessing_types_getter' );
	$gagtg = new guess_activities_guessing_types_getter();

	require_once libfile ( 'class/activity_type_result_getter' );
	$atrg = new activity_type_result_getter();

	for($i=0;$i<count($info_perpage);$i++){

		$record1 = $gag->get_info_by_id($activity_id);
		$record_set1 = $gtg->get_info_by_id_str($info_perpage[$i]["type_id"]);
		$record2 = $cmpg->get_realname_by_uid($info_perpage[$i]["uid"]);

		$users_activities_types_id = $uatg->get_id_by_user_id_activity_id_type_id($info_perpage[$i]["uid"],$activity_id,$info_perpage[$i]["type_id"]);
		$record_set2 = $grg->get_uid_by_users_activities_types_id($users_activities_types_id);

		$record_set3 = array();
		for($j=0;$j<count($record_set2);$j++){
			$record_set3[] = $record_set2[$j]["user_id"];
		}


		$record_set4 = $cmpg->get_realname_by_uid_str(implode(',',$record_set3));
		$record_set5 = array();
		for($j=0;$j<count($record_set4);$j++){
			$record_set5[] = $record_set4[$j]["realname"];
		}

		$activity_type_id = $gagtg->get_id_by_activity_id_type_id($activity_id,$info_perpage[$i]["type_id"]);

		$record_set6 = $atrg->get_uid_by_activity_type_id($activity_type_id);

		if(count($record_set6)!=0){
			$record_set7 = array();
			for($j=0;$j<count($record_set6);$j++){
				$record_set7[] = $record_set6[$j]["uid"];
			}

			$record_set8 = $cmpg->get_realname_by_uid_str(implode(',',$record_set7));
			$record_set9 = array();
			for($j=0;$j<count($record_set8);$j++){
				$record_set9[] = $record_set8[$j]["realname"];
			}

			if(implode(',',$record_set9)!=''){
				if(implode(',',$record_set5)==implode(',',$record_set9)){
					$good_luck_or_not = '猜中';
				}else{
					$good_luck_or_not = '未猜中';
				}
			}else{
				$good_luck_or_not = 'N/A';
			}
		}else{
			$record_set9 = array();
			$good_luck_or_not = 'N/A';
		}


		$rows.=showtablerow('', array(), array(
				$record1["name"],
				$record_set1[0]['name'],
				"<a href=\"home.php?mod=space&uid=".$info_perpage[$i]["uid"]." \" target=\"_blank\">".$record2["realname"]."</a>",
				implode(',',$record_set5),
				implode(',',$record_set9),
				$good_luck_or_not
				),TRUE);

	}

	var_dump($amount);echo 'line2031';

	$multipage = multi($amount, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=dazappguess&operation=guess_result_list&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage'].'&id='.$activity_id);

	$_G['lang']['admincp']['guess_result_desc'] = '竞猜结果';
	$_G['lang']['admincp']['guess_activity_list_desc'] = '竞猜活动列表';
	showsubmenu('guess_result_desc',array( array('guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1) ) );

	//$_G['lang']['admincp']['searching_guess_activity_tip_desc'] = '<li>搜索-请填写你的竞猜活动名称</li> <li>这里支持 模糊搜索和精确搜索 同时你也可以选择一页显示多少条记录</li>';
	//showtips('searching_guess_activity_tip_desc');

	//showformheader('dazappguess&operation=guess_result_list');

	//$_G['lang']['admincp']['searching_bar_desc'] = '共搜索到<font color="red"><strong> {amount} </strong></font>个符合条件的竞猜活动';
	//showtableheader(cplang('searching_bar_desc', array('amount' => $amount)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="guess_search" > <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 显示所有竞猜 ">');
	showtableheader('', '', '', 15);

	$_G['lang']['admincp']['guess_activity_name_desc'] = "竞猜活动";
	$_G['lang']['admincp']['guess_activity_type_desc'] = "竞猜活动类型";
	$_G['lang']['admincp']['guess_user_desc'] = "竞猜人";
	$_G['lang']['admincp']['guess_result_everyone_desc'] = "竞猜人的竞猜结果";
	$_G['lang']['admincp']['guess_result_desc'] = "竞猜结果";
	$_G['lang']['admincp']['good_luck_or_not_desc'] = "是否猜中";

	showsubtitle(array('guess_activity_name_desc', 'guess_activity_type_desc','guess_user_desc','guess_result_everyone_desc','guess_result_desc','good_luck_or_not_desc'));
	echo $rows;
	showsubmit('', '', '', '', $multipage);
	showtablefooter();
}elseif($operation == 'add_guess_result_address'){

	showformheader('dazappguess&operation=handle_adding_guess_result_address', '');
	echo ' <input name="id" value="'.getgpc('id').'" type="hidden">';

	$_G['lang']['admincp']['adding_guess_result_address_desc'] = '填写竞猜结果地址';
	$_G['lang']['admincp']['returning_guess_activity_list_desc'] = '返回竞猜活动列表';
	showsubmenu('adding_guess_result_address_desc',array( array('returning_guess_activity_list_desc', 'dazappguess&operation=guess.activity.list', 1) ) );
	showtableheader();

	require_once libfile ( 'class/guess_activity_getter' );
	$gag = new guess_activity_getter();
	$rows = $gag->get_info_by_id(getgpc('id'));

	$_G['lang']['admincp']['guess_result_address_desc'] = $rows['name'].'的竞猜结果地址';
	showsetting('guess_result_address_desc', 'address',$rows['result_address'], 'text','','','');

	$_G['lang']['admincp']['submitting_guess_result_address_desc'] = '提交';
	showsubmit('submitting_guess_result_address_desc', 'submit');

	showtablefooter();
	showformfooter();
}elseif($operation == 'handle_adding_guess_result_address'){

	$id = $guess_activity_data['name'] = getgpc('id');
	$address = $guess_activity_data['name'] = getgpc('address');

	require_once libfile ( 'class/str_len_checker' );
	$slc = new str_len_checker();
	$tmp = $slc->check($address,1,100,'竞猜结果地址');
	if($tmp!==true){
		cpmsg($tmp);
	}



	require_once libfile ( 'class/guess_activity_updater' );
	$gau = new guess_activity_updater();
	$gau->update_result_address($id,$address);
	cpmsg('填写竞猜结果地址成功','action=dazappguess&operation=guess.activity.list');
}


echo <<<EOT
<script type="text/javascript" src="static/js/calendar.js"></script>
<script type="text/JavaScript">
function change_title(type) {
	if(type == 'bold') {
		old = $('newsubject').value.replace(/<b>(.*?)<\/b>/i, '$1');
		if(old == $('newsubject').value) {
			$('newsubject').value = '<b>'+old+'</b>';
		} else {
			$('newsubject').value = old;
		}
	} else if(type == 'italic') {
		old = $('newsubject').value.replace(/<i>(.*?)<\/i>/i, '$1');
		if(old == $('newsubject').value) {
			$('newsubject').value = '<i>'+old+'</i>';
		} else {
			$('newsubject').value = old;
		}
	} else if(type == 'underline') {
		old = $('newsubject').value.replace(/<u>(.*?)<\/u>/i, '$1');
		if(old == $('newsubject').value) {
			$('newsubject').value = '<u>'+old+'</u>';
		} else {
			$('newsubject').value = old;
		}
	}
}

function change_choose(id) {
	className = $(id).className;
	if(className == '') {
		$(id).className = 'a';
	} else {
		$(id).className = '';
	}
}

function title_replace(a) {
	old = $('newsubject').value;
	old = old.replace(/<font(.*?)>(.*?)<\/font>/i, '$2');
	if(a) {
		$('newsubject').value = '<font color='+a+'>'+old+'</font>';
	} else {
		$('newsubject').value = old;
	}
}

function change_title_color(hlid) {
	var showid = hlid;
	if(!$(showid + '_menu')) {
		var str = '';
		var coloroptions = {'0' : '#000', '1' : '#EE1B2E', '2' : '#EE5023', '3' : '#996600', '4' : '#3C9D40', '5' : '#2897C5', '6' : '#2B65B7', '7' : '#8F2A90', '8' : '#EC1282'};
		var menu = document.createElement('div');
		menu.id = showid + '_menu';
		menu.className = 'cmen';
		menu.style.display = 'none';
		for(var i in coloroptions) {
			str += '<a href="javascript:;" onclick="title_replace(\'' + coloroptions[i] + '\');$(\'' + showid + '\').style.backgroundColor=\'' + coloroptions[i] + '\';hideMenu(\'' + menu.id + '\')" style="background:' + coloroptions[i] + ';color:' + coloroptions[i] + ';">' + coloroptions[i] + '</a>';
		}
		menu.innerHTML = str;
		$('append_parent').appendChild(menu);
	}
	showMenu({'ctrlid':hlid + '_ctrl','evt':'click','showid':showid});

}



</script>
EOT;

?>