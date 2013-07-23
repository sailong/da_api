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

/*分页设置*/
$_G['setting']['perpage'] = isset($_G['gp_want_search_num']) ? (intval($_G['gp_want_search_num'])==0 ? 20 : intval($_G['gp_want_search_num'])) : 20;
$page = max(1, $_G['page']);
$start_limit = ($page - 1) * $_G['setting']['perpage'];
$where = "  ";




/*菜单导航*/
$menu = array(
            array('比赛球员列表', 'nduser&operation=manage',($operation == 'manage') ),
            array('自动生成分组', 'nduser&operation=add_user_item',($operation == 'add_user_item')),
			array('赛事数据列表', 'nduser&operation=sais_data_manage',($operation == 'sais_data_manage'))
		);




/*分站信息*/
$fenz_query = DB::query("SELECT * FROM ".DB::table("home_fenz")." where sais_id = '1000333' ");
$fenz="分站名称：<select name=\"fenz\"><option value=\"0\">请选择分站</option>";
while($result = DB::fetch($fenz_query)){
	$selected = "";
	if(getgpc('fenz')==$result['key']) $selected =" selected " ;
	$fenz.="<option value=\"".$result['key']."\" ".$selected.">".$result['value']."</option>";
	$page_fenz = "&fenz=".getgpc('fenz')."";
}
$fenz.="<option value=\"1900471\" >滨海</option>";
$fenz.="</select>";


$fenz.= ' 球场：<select name="qiuc">
			<option value="1083">长沙梓山湖</option>
			<option value="1290">广州风神</option>
			<option value="1218">海宁尖山高尔夫俱乐部</option>
			<option value="1026">深圳正中</option>
			<option value="1204">天津滨海湖</option>
			<option value="971">北京银泰鸿业</option>
			<option value="1889284">青岛桃园江景</option>
			<option value="1186">上海美兰湖</option>
			<option value="1302">大连红旗谷</option>
			<option value="1341">成都蓝光观岭</option>
			<option value="1113">苏州太湖</option>
			<option value="994">福州长乐海峡</option>
		</select>';




/*赛事列表*/
$saishi_id = '25';
$sql_query=  DB::query(" SELECT cm.uid , cmp.field1 FROM  ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid = cm.uid  where cm.groupid= '".$saishi_id."' ");
$sais_select  = " 选择赛事：<select name=\"sais_id\">";
while($sais_result = DB::fetch($sql_query)){
	if($sais_result['field1']){
	  if($sais_result['field1']=='皇冠杯城市挑战赛'){ //临时这样 筛选出  皇冠杯城市挑战赛
			 $sais_select .= '<option value="'.$sais_result['uid'].'">'.$sais_result['field1'].'</option>';
	   }
	}
}

  $sais_select .= '<option value="1000333">皇冠杯城市挑战赛</option>';
   $sais_select .= '<option value="1203">正信杯</option>';
$sais_select .="</select>";




/*18个洞口*/
$checkbox_tee_kou = ' 选择同时开球T台';
for($i=1;$i<=18;$i++){
    $checkbox_tee_kou.=" <input type=\"checkbox\" name=\"tee_kou[]\" value=\"".$i."\"> Tee".$i."";
}


/*where  sais_id  */
$where_sais_id = empty($_G['gp_sais_id']) ?  " where sais_id ='1000333' " : " where sais_id = '". getgpc('sais_id')."'";
$where_fenz    = empty($_G['gp_fenz'])    ? " " : " and fenz_type='".getgpc('fenz')."'";
$where = $where_sais_id.$where_fenz;



/*生成后的用户列表*/
if($operation == 'manage') {


	/*导出动作*/
	if($_G['gp_export']){
		$sql_query =DB::query(" SELECT `realname`,`chadian`,`item_ident`,`kq_tee`,`start_time`,`am_pm` from ".DB::table('nd_quny_fz').$where." order by item_ident asc " );
		while($result = DB::fetch($sql_query)){
			$result['start_time'] = date("Y-m-d H:i:s",$result['start_time']);
			$result['item_ident'] = $result['item_ident'] ? $result['item_ident'] : "<font color=\"red\">停赛</font>";
            $result['am_pm']      = $result['am_pm']==1 ? "<font color=\"blue\">上午</font>" : "<font color=\"red\" >下午</font>";
			$export_data[] = $result;
		}
	    include_once libfile('class/exportxls');
		$export = new exportxls();
		$keys = array('真实姓名','差点','所在组','开球T台','比赛开始时间','上下/午');
		$export->export_begin($keys,'比赛分组',count($export_data));
		$export->export_rows($export_data);
		$export->export_finish();
	  exit;
	}




	$sql_query =DB::query(" SELECT * from ".DB::table('nd_quny_fz').$where." order by item_ident asc limit ".$start_limit.",".$_G['setting']['perpage']);
	$count_result =DB::fetch_first("SELECT count(*) as num FROM ".DB::table("nd_quny_fz").$where);
    $bs_user_num = $count_result['num'];
	while($result = DB::fetch($sql_query)){

			$bm_users.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"delete_card[]\" value=\"$result[id]\" />",
				"<a href=\"home.php?mod=space&uid=$result[uid]\" target=\"_blank\"> $result[realname]</a>",
				$result['chadian'],
				date('Y-m-d H:i:s',$result['start_time']),
				$result['kq_tee']==0 ? '<font color="red"  >【停赛】</font>' : "TEE_".$result['kq_tee'],
				$result['item_ident']==0 ? '<font color="red">【停赛】' : $result['item_ident']." 队",
				 $am_pm =  $result['am_pm']==1 ?  '上半场' :'下半场',
				"<a href=\"".ADMINSCRIPT."?action=nduser&operation=user_allot&id=".$result['id']."\"> 小组调拨 |</a>".
				"<a href=\"".ADMINSCRIPT."?action=nduser&operation=stop_game&id=".$result['id']."\" />停赛</a>"
				),TRUE);
	}
		$multipage = multi($bs_user_num, $_G['setting']['perpage'], $page, ADMINSCRIPT."?action=nduser&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['perpage'].$page_fenz);

		showsubmenu('生成球员管理',$menu );
		showtips('使用说明 编辑中...');
		showformheader('nduser&operation=manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $bs_user_num)).'
		'.$sais_select.$fenz.'
		每页显示：<input name="want_search_num" value="'.$_G['setting']['perpage'].'" size="3" style="margin-right:10px;vertical-align: middle;">条记录
		<input type="submit" class="btn" value=" 搜 素 "> <input name="export" class="btn" type="submit" value="导 出">'
		);
		showsubtitle(array('', '选手名称','选手差点','比赛开始时间','开球Tee台','分组标示','上/下 半场',  'groups_type_operation'));
		  echo $bm_users;
		showsubmit('submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'dazbm_delete\')" class="checkbox">'.cplang('del'), '', $multipage);
		showtablefooter();
		showformfooter();
}





/*生成球员分组*/
elseif($operation == 'add_user_item'){
	/*引入时间JS*/
	echo $script = <<<SCRIPT
    <script type="text/javascript" src="static/js/calendar.js"></script>
SCRIPT;

	$input_info  = " <br/><br/>比赛开始时间：<input type=\"text\" class=\"txt\" name=\"bs_start_time\" onclick=\"showcalendar(event, this, 1)\">";

	$input_info .= " 休息开始时间：<input type=\"text\" class=\"txt\" name=\"rest_start_time\" onclick=\"showcalendar(event, this, 1)\">".
		           "- <input type=\"text\" class=\"txt\" name=\"rest_end_time\" onclick=\"showcalendar(event, this, 1)\"><br><br>";
    $input_info .= " 比赛间隔时间 <input type=\"input\" name=\"bs_je_time\" value=\"10\" size=\"2\"> 分钟";

	$input_info .= " 选择比赛难度：".
				   " <input type=\"radio\" name=\"tee_type\" value=\"gold_tee\">  金Tee".
		           " <input type=\"radio\" name=\"tee_type\" value=\"blue_tee\" > 蓝Tee".
		           " <input type=\"radio\" name=\"tee_type\" value=\"hei_tee\">   黑Tee".
		           " <input type=\"radio\" name=\"tee_type\" value=\"red_tee\">   红Tee".
				   " <input type=\"radio\" name=\"tee_type\" value=\"white_tee\"> 白Tee <br/>";

    $submit    .= " <br><br><div align=\"center\"><input  type=\"submit\" value=\"提 交\" class=\"btn\" >  <input  align=\"center\" type=\"reset\" value=\"重 置\" class=\"btn\" >";

	showsubmenu('生成球员管理',$menu);
	showtips('选择下面选项 进行生成');
	showformheader('nduser&operation=sc_add');
	showtableheader('<br>'.$sais_select.$fenz.$input_info.'<br>'.$checkbox_tee_kou.$submit);
	showtablefooter();
	showformfooter();
}









/*添加赛事比赛数据··并生成 分组球员*/
elseif($operation == 'sc_add')
{
    //插入比赛相关数据
    $data['sais_id'] = getgpc('sais_id');
	$data['fenz']	 = getgpc('fenz');
	$data['qc_id']   = getgpc('qiuc');
	$data['bs_start_time']   = strtotime(getgpc('bs_start_time'));
	$data['rest_start_time'] = strtotime(getgpc('rest_start_time'));
	$data['rest_end_time']   = strtotime(getgpc('rest_end_time'));
	$data['bs_je_time']      = intval(getgpc('bs_je_time')*60);
	$data['tee_type']        = getgpc('tee_type');
	$data['tee_kou']         = implode('|', getgpc('tee_kou'));
	$tee_kou = getgpc('tee_kou');


    /*查询这个赛事规则是否存在*/
	$checking = DB::fetch_first(" select count(*) as num  from ".DB::table("game_level_data")." where sais_id='".$data['sais_id']."' and fenz = '".$data['fenz']."' ");
	if($checking['num']>0) cpmsg(" 该赛事 该分站下已经 生成 球员分组名单 如果需要从新生成 请删除原来的 比赛规则");



    /*赛事规则 insert*/
	if(empty($data['fenz']) || empty($data['qc_id']) || empty($data['bs_start_time']) || empty($data['rest_start_time'] ) || empty($data['rest_end_time'] ) || empty($data['tee_kou'] ) ){ cpmsg("请认真填写该赛事下的比赛数据");}
	$bs_tee_kou =  getgpc('tee_kou');
	DB::insert('game_level_data',$data,true);
    $insert_id = DB::insert_id();




	$bm_user_query = DB::query("SELECT `uid`,`realname`,`cahdian` FROM ".DB::table('home_dazbm')." where hot_district like '%".$data['fenz']."%' and apply_status='1' and pay_status='1'  and gameover='0' and city_fenz='' order by cahdian ASC " );
    $user_num      = DB::fetch_first(" SELECT count(*) as num FROM ".DB::table('home_dazbm')." where hot_district like '%".$data['fenz']."%' and apply_status='1' and pay_status='1'  and  gameover='0'  ");
	$fz_num        = ceil($user_num['num']/4);
    $tee_rows      = getgpc('tee_kou');
	$tee_num       = count($tee_rows );
    $am_game_time  = $data['rest_start_time']-$data['bs_start_time'];  //上午开球时间
    $am_kq_num     = ceil($am_game_time /(getgpc('bs_je_time')*60));              //计算上午能分出多少组  用上午比赛的时间/间隔时间*开球tee数




   /*上午分组*/
    $k=$kk=$t=0;
    for($j=1;$j<=$tee_num;$j++){
      for($i=1;$i<=$am_kq_num;$i++){
		  $k++;
		  $bs_data[$k]['start_time'] = $data['bs_start_time'] +  ($kk * getgpc('bs_je_time')*60);
		  $bs_data[$k]['am_pm']=1; //上午
		  $bs_data[$k]['kq_tee']=$tee_kou[$t];
		  $bs_data[$k]['item_ident']= $k;
          $kk++;
      }
	  $t++; //空口游标
	  $kk=0;
    }



   /*下午分组*/
   $kk=1;$t=0;
   $pm_kq_num=ceil(($fz_num -($am_kq_num*$tee_num))/$tee_num);

    for($j=1;$j<=$tee_num;$j++){
      for($i=1;$i<=$pm_kq_num;$i++){
		  $k++;
		  $bs_data[$k]['start_time'] =  $data['rest_end_time'] +  ($kk * getgpc('bs_je_time')*60);
		  $bs_data[$k]['am_pm']= 2; //下午
		  $bs_data[$k]['kq_tee']=$tee_kou[$t];
		  $bs_data[$k]['item_ident']= $k;
          $kk++;
      }
	  $t++; //空口游标
	  $kk=1;
    }


	foreach ($bs_data as $key => $row) {
		$start_time[$key] = $row['start_time'];
		//$bs_data[$key]['start_time'] = date("Y-m-d H:i:s",$row['start_time']);
	}


    array_multisort($start_time, SORT_ASC,$bs_data); //对相同差点的人 从新排序

    $i=0; $z=0;
	while($result = DB::fetch($bm_user_query)){
        if($i%4==0 && $i!=0) $z++;
		$bs_data[$z]['users'][$i]['uid']      = $result['uid'];
		$bs_data[$z]['users'][$i]['realname'] = $result['realname'];
		$bs_data[$z]['users'][$i]['chadian']  = $result['cahdian'];
		$i++;
	}


	/*insert 数据库*/
	$ss_info['sais_id']       = getgpc('sais_id');
	$ss_info['fenz_type']     = getgpc('fenz');
	$ss_info['qc_id']         = getgpc('qiuc');
	$ss_info['sc_rule_id']    = $insert_id;

	foreach($bs_data as $key=>$value){
		foreach($value['users'] as $k=>$v){
			$v['start_time'] = $value['start_time'];
			$v['kq_tee']     = $value['kq_tee'];
			$v['am_pm']      = $value['am_pm'];
			$v['item_ident'] = $value['item_ident'];
			$insert_data = array_merge($v,$ss_info);
			DB::insert('nd_quny_fz', $insert_data);

            /*同步插入到nd_score表中*/
            DB::insert('nd_score',array('sais_id'=>$ss_info['sais_id'],'fieldid'=>$ss_info['qc_id'],'uid'=>$v['uid'],'start_time'=>$v['start_time'],'realname'=>$v['realname'],'tee'=>$v['kq_tee'],'zid'=>$v['item_ident']));
		}
	}
	cpmsg("全员名单生成 成功！","action=nduser&operation=manage");

}



/*比赛球员 - 停赛处理*/
elseif($operation=="stop_game"){
	$id = getgpc("id");
	DB::update("nd_quny_fz",array('item_ident'=>0,'kq_tee'=>0),array('id'=>$id));
    /*徐玉枭 更新成绩表*/
    $usid =DB::result_first("SELECT uid FROM ".DB::table("nd_quny_fz")." where id=$id");
	DB::update("nd_score",array('zid'=>0,'tlscore'=>1001),array('uid'=>$usid));
    /*end*/
	cpmsg('操作成功！',"action=nduser&operation=manage");
}







/*分组调拨*/
elseif($operation=='user_allot'){

	/*引入时间JS*/
		$script = <<<SCRIPT
		<script type="text/javascript" src="static/js/calendar.js"></script>
SCRIPT;
	$result = DB::fetch_first(" SELECT  `kq_tee`,`start_time`,`item_ident` FROM ".DB::table("nd_quny_fz")." where id ='".getgpc('id')."'");
	$kq_tee =" 开球Tee台：<input name=\"kq_tee\" size=\"3\" value=\"".$result['kq_tee']."\" >";
	$fenz   =" 分组：<input name=\"item_ident\" size=\"3\"  value=\"".$result['item_ident']."\">";
	$start_time = '比赛时间：<input type="text" class="txt" name="start_time" onclick="showcalendar(event, this, 1)"  value="'.date('Y-m-d H:i:s',$result['start_time']).'">';
	$id     =" <input type=\"hidden\" name=\"update_id\" value=\"".getgpc('id')."\">";
	cpmsg('',"action=nduser&operation=update",'form','',''.$kq_tee.$fenz.$id.$start_time.$script);
}



/*更新数据*/
elseif($operation=='update'){
	$up_data['kq_tee']      = getgpc('kq_tee');
	$up_data['item_ident']  = getgpc('item_ident');
	$up_data['start_time']= strtotime(getgpc('start_time'));
	DB::update('nd_quny_fz',$up_data,array('id'=>getgpc('update_id')));


    /*徐玉枭  do it  2012-5-16 更新成绩表*/
        $upid=getgpc('update_id');
        $sd_data['tee']  = getgpc('kq_tee');
        $sd_data['zid']  = getgpc('item_ident');
		$sd_data['start_time']= strtotime(getgpc('start_time'));
        $usid =DB::result_first("SELECT uid FROM ".DB::table("nd_quny_fz")." where id=$upid ");
        DB::update('nd_score',$sd_data,array('uid'=>$usid));
    /*end*/

	cpmsg('修改成功！','action=nduser&operation=manage');

}




/*赛事基本数据列表*/
elseif($operation=="sais_data_manage"){
	$where = '';
	$sql_query =DB::query(" SELECT qcmp.realname as qc_name ,cmp.realname as ss_name,gm.* from ".DB::table('game_level_data')." as gm LEFT JOIN ".DB::table("common_member_profile")." as cmp ON gm.sais_id=cmp.uid LEFT JOIN ".DB::table("common_member_profile")." as qcmp ON gm.qc_id=qcmp.uid".$where." order by gm.gm_id desc limit ".$start_limit.",".$_G['setting']['perpage']);
	$count_result =DB::fetch_first("SELECT count(*) as num FROM ".DB::table("game_level_data").$where);
    $num = $count_result['num'];
	while($result = DB::fetch($sql_query)){
			$game_gz.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"delete_gm_data[]\" value=\"$result[gm_id]\" />",
				"<a href=\"home.php?mod=space&uid=$result[sais_id]\" target=\"_blank\"> $result[ss_name]</a>",
				"<a href=\"home.php?mod=space&uid=$result[qc_id]\" target=\"_blank\"> $result[qc_name]</a>",
				date('Y-m-d H:i:s',$result['bs_start_time']),
                $result['tee_kou']." TEE",
				($result['bs_je_time']/60).'分钟',
				"<a href=\"".ADMINSCRIPT."?action=nduser&operation=delete_sais_guiz&gm_id=".$result['gm_id']."&qc_id=".$result['qc_id']."\">删除比赛规则</a>"
				),TRUE);
	}
		$multipage = multi($num, $_G['setting']['perpage'], $page, ADMINSCRIPT."?action=nduser&operation=sais_data_manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['perpage'].$page_fenz);

		showsubmenu('生成球员管理',$menu );
		showtips('使用说明 编辑中...');
		showformheader('nduser&operation=sais_data_manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $num)).'
		'.$sais_select.'
		每页显示：<input name="want_search_num" value="'.$_G['setting']['perpage'].'" size="3" style="margin-right:10px;vertical-align: middle;">条记录
		<input type="submit" class="btn" value=" 搜 素 ">'
		);
		showsubtitle(array('', '赛事名称','球场名称','比赛开始时间','同时开球Tee台','比赛间隔时间', '操作'));
		  echo $game_gz;
		showsubmit('', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'delete_gm_data\')" class="checkbox">'.cplang('del'), '', $multipage);
		showtablefooter();
		showformfooter();
}

/*删除赛事提示 规则*/
elseif($operation=="delete_sais_guiz"){
	$gm_id =getgpc('gm_id');
	$qcid  =getgpc('qc_id');
	$input = " <input type=\"hidden\" name=\"gm_id\" value=\"".$gm_id."\">";
	$input.= " <input type=\"hidden\" name=\"qcid\" value=\"".$qcid."\">";
	cpmsg('删除本次比赛规则后 生成的分组名单也会被一起删除 你确定要删除？ ',"action=nduser&operation=delete_gz",'form','',''.$input);
}

/*删除赛事 规则*/
elseif($operation=="delete_gz"){
	DB::delete("game_level_data",array('gm_id'=>getgpc('gm_id')));
	DB::delete("nd_quny_fz",array("sc_rule_id"=>getgpc('gm_id')));
	DB::delete("nd_score",array("fieldid"=>getgpc('qcid')));
    cpmsg('删除成功 ',"action=nduser&operation=sais_data_manage");
}


?>