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

/*赛场地区 ajax start*/
$ajaxget_url = 'dazbm.php?d_action=get_sc_dq&aid=';
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
}

if($_GET['d_action']=="get_sc_dq"){
	$query = DB::query('select id, fieldname from '.DB::table('common_field')." where province='".$_GET['aid']."' order by id desc");
	while($list = mysql_fetch_assoc($query)) {
		$field[] = $list;
	}
	$option = "<option value='0'>请选择</option>";
	foreach($field as $k=>$v) {
	   $option .= "<option value='".$v['id']."'>".$v['fieldname']."</option>";
	}
    include template('common/header_ajax');
      echo $option;
    include template('common/footer_ajax');exit;
}
/*赛场结束 end*/

$fenz_arr = array(
                 'tj57'=>'5/5长沙',
                 'bj513'=>'5/18广州',
                 'gz527'=>'5/27杭州',
                 'cs611'=>'6/16天津',
                 'nvzbj'=>'6/28北京女子邀请赛',
                 'qd625'=>'6/29北京',
                 'hz72'=>'7/6深圳',
                 'sh721'=>'7/14青岛',
                 'dl729'=>'7/27上海',
                 'sz84'=>'8/3大连',
                 'cd812'=>'8/18成都',
                 'km827'=>'8/31苏州',
                 'fz93'=>'9/8福州',
                 );

$fenz_arr2013 = array(
                 'tj511'=>'5/11天津',
                 'gz524'=>'5/24广州',
                 'sz531'=>'5/31深圳',
                 'hz615'=>'6/15杭州',
                 'sh621'=>'6/21上海',
                 'cs629'=>'6/29长沙',
                 'bj719'=>'7/19北京',
                 'dl726'=>'7/26大连',
                 'zz89'=>'8/9郑州',
                 'cd824'=>'8/24成都',
                 'sz830'=>'8/30苏州',
                 'fz97'=>'9/7福州',
                 );


$city_fenz       = fenz_select($fenz_arr,'fenz');
$city_fenz2013       = fenz_select($fenz_arr2013,'fenz2013');
$todo_game_over  = fenz_select($fenz_arr2013,'todo_game_over','disable(\'no\')');
$todo_game_over2013  = fenz_select($city_fenz2013,'todo_game_over','disable(\'no\')');

/*12家分站的信息临时用这样的结构 和 车主类型*/
//$fenz = "2013分站名称：".$city_fenz2013."2012分站名称：".$city_fenz."
$fenz = "2013分站名称：".$city_fenz2013."
		车主类型：<select name=\"car_type\">
				<option value=\"-1\">选择车型</option>
				<option value=\"1\">皇冠车主</option>
				<option value=\"0\">非皇冠车主</option>
		</select>
		支付状态：<select name =\"pay_status\">
					<option value=\"-1\">请选择</option>
					<option value=\"0\">未支付</option>
					<option value=\"1\">已支付</option>
				</select><br>
		审核状态：<select name=\"apply_status\">
		            <option value=\"-1\">请选择</option>
					<option value=\"0\">未审核</option>
					<option value=\"1\">已审核</option>
				  </select>
		比赛状态：<select name=\"game_status\">
		            <option value=\"-1\">请选择</option>
					<option value=\"1\">已经比赛</option>
					<option value=\"0\">还未比赛</option>
				  </select>
		";

/*分页设置*/
$_G['setting']['dazbm_perpage'] = isset($_G['gp_want_search_num']) ? (intval($_G['gp_want_search_num'])==0 ? 20 : intval($_G['gp_want_search_num'])) : 20;
$page = max(1, $_G['page']);
$start_limit = ($page - 1) * $_G['setting']['dazbm_perpage'];


/*用户列	2013-04-02 表*/
if($operation == 'manage') {

	$do_action = " <input type=\"radio\" name=\"footer_action\" value=\"delete_user\" class=\"radio\" \/>删除用户?";
	$do_action.= " &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  <input type=\"radio\" onclick=\"disable('yes')\" name=\"footer_action\" value=\"game_over\" class=\"radio\" \/>  设置为 已经参加比赛？$todo_game_over <span id =\"title_msg\">*</span>";

	$gp_where = $dazbm_rows = '' ;
	/*input name mobile search*/
	if(isset($_G['gp_dazbm_search']) && $_G['gp_dazbm_search']!=''){
		$gp_where = " realname like '%$_G[gp_dazbm_search]%' or  moblie like '%$_G[gp_dazbm_search]%'  or  telephone like '%$_G[gp_dazbm_search]%'  " ;
	}
	/*时间的条件 search*/
	if(isset($_G['gp_start_time']) && isset($_G['gp_end_time'])){
		if($_G['gp_start_time']!='' && $_G['gp_end_time']!=''){
			$and .= empty($gp_where) ? '' :' and ';
			$gp_where.=$and ." addtime >= '".strtotime(getgpc('start_time'))."' and addtime <= '".strtotime(getgpc('end_time'))."' ";
		}
	}
	/*时间的条件 search*/
	if(isset($_G['gp_year_time'])){ 
			$and .= empty($gp_where) ? '' :' and ';
			$gp_where.=$and ." addtime >= '".strtotime(getgpc('year_time'))."' and addtime <= '".strtotime(getgpc('end_time'))."' "; 
	}
	/*分站的条件 search*/
	if(isset($_G['gp_fenz']) && $_G['gp_fenz']!='0'){
		 $and = empty($gp_where) ? '' : ' and ';
		 $gp_where.= $and." hot_district like '%".getgpc('fenz')."%' ";
	}
	/*分站的条件 增加2013年search*/
	if(isset($_G['gp_fenz2013']) && $_G['gp_fenz2013']!='0'){
		 $and = empty($gp_where) ? '' : ' and ';
		 $gp_where.= $and." hot_district like '%".getgpc('fenz2013')."%' ";
	}
	/*车主类型 search*/
	if(isset($_G['gp_car_type']) && $_G['gp_car_type']!="-1"){
		$and = empty($gp_where) ? '' :' and ';
		$gp_where .=$and." is_huang = '".getgpc('car_type')."'";
	}
	/*支付状态 search*/
	if(isset($_G['gp_pay_status']) && $_G['gp_pay_status']!="-1"){
		$and = empty($gp_where) ? '' :' and ';
		$gp_where .=$and." pay_status = '".getgpc('pay_status')."'";
	}

    /*审核状态 search*/
	if(isset($_G['gp_apply_status']) && $_G['gp_apply_status']!="-1"){
		$and = empty($gp_where) ? '' :' and ';
		$gp_where .=$and." apply_status = '".getgpc('apply_status')."'";
	}

	 /*比赛状态 search*/
	if(isset($_G['gp_game_status']) && $_G['gp_game_status']!="-1"){
		$and = empty($gp_where) ? '' :' and ';
		$gp_where .=$and." gameover = '".getgpc('game_status')."'";
	}

	/*备注 search*/
	if(isset($_G['gp_remark_type'])  && $_G['gp_remark_type']!=-1){
		$and = empty($gp_where) ? '' :' and ';
		$gp_where .=$and." remark_type = '".getgpc('remark_type')."'";
	}
if(empty($gp_where)){
$gp_where=" addtime >= '".strtotime("2013-04-01 13:48:12")."'   ";
}  
	/*删除动作*/
	if(isset($_G['gp_dazbm_delete'])){
		$bm_id = implode(',',$_G['gp_dazbm_delete']);
    	$footer_action  = getgpc("footer_action");
        $input_fz       = getgpc('todo_game_over');
		if(empty($footer_action)) cpmsg("请选择 操作动作",'action=dazbm&operation=manage');
		cpmsg('你要确定这样 操作码？',"action=dazbm&operation=$footer_action",'form','',"<input name=\"bm_id\" value=\"$bm_id\" type=\"hidden\"/><input name=\"input_fz\" value=\"$input_fz\" type=\"hidden\">"); //提示信息
	}else{


		$where = empty($gp_where) ? ' ' : ' where '.$gp_where;
		$query = DB::query("SELECT `realname`,`cahdian`,`apply_status`,`pay_way`,`uid`,`bm_id`,`pay_status`,`apply_status`,`moblie`,`addtime`,`telephone` FROM ". DB::table('home_dazbm')." " .$where. " ORDER BY bm_id desc limit ".$start_limit."," .$_G['setting']['dazbm_perpage']." " );

		/*导出动作*/
		if(isset($_G['gp_export'])){


              /*批量修改汽车*/
              /*
              $brand_cars = DB::query("SELECT * FROM ". DB::table('car_brand')."" );
              while($angf= DB::fetch($brand_cars)){
                 $rows[]=$angf['brand_name'];
              }
              */

			  $query = DB::query("SELECT * FROM ". DB::table('home_dazbm')." " .$where. " ORDER BY bm_id desc " );
			  while($result_list = DB::fetch($query)){
				  $export_list[] = $result_list;
                  /*批量修改汽车*/
                  //DB::update("home_dazbm",array('car_brand'=>$rows[mt_rand(0,97)]),array("uid"=>$result_list['uid']));
			  }

			  dzexcel($export_list,'dazbm_export');
			  exit;
		}

		$query_num = DB::query("SELECT `bm_id` FROM ". DB::table('home_dazbm')." " .$where. " " );
		$dazbm_users_num = DB::num_rows($query_num);


		while($dazbm_result = DB::fetch($query)) {
		    $apply_button = ($dazbm_result['apply_status']==0) ? cplang('dazbm_rz') : cplang('dazbm_rz_no');
			$pay_button   =  ($dazbm_result['pay_status']==0) ? '支付' :'取消支付';
			$apply_link_todo =($dazbm_result['apply_status']==0) ? 1 : 0;
			$dazbm_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"dazbm_delete[]\" value=\"$dazbm_result[bm_id]\" />",
				"<a href=\"home.php?mod=space&uid=$dazbm_result[uid]\" target=\"_blank\"> $dazbm_result[realname]</a>",
				$dazbm_result['cahdian'],
				$dazbm_result['moblie'],
				$dazbm_result['pay_status']==1 ? cplang('already_pay') : cplang('nopay'),
				$dazbm_result['apply_status']==1 ? cplang('apply_yes') : cplang('apply_no'),
				($dazbm_result['pay_way']==1) ? '网银支付' : '线下支付',
				date('Y-m-d H:i:s',$dazbm_result['addtime']),
				"<a href=\"dazbm.php?bm_page=1&member_id=".$dazbm_result['uid']."\" target=\"_blank\" /> ".cplang('edit')." | </a>".
				"<a href=\"".ADMINSCRIPT."?action=dazbm&operation=approve&bm_id=$dazbm_result[bm_id]&apply_link_todo=$apply_link_todo\" />".$apply_button."</a>".
				"<a href=\"".ADMINSCRIPT."?action=dazbm&operation=pay&bm_id=$dazbm_result[bm_id]&pay_status=$dazbm_result[pay_status]\" />| ".$pay_button."</a>".
				"<a href=\"".ADMINSCRIPT."?action=dazbm&operation=remark&bm_id=$dazbm_result[bm_id]\" >| 添加备注</a>".
				"<a href=\"".ADMINSCRIPT."?action=dazbm&operation=remark_list&bm_id=$dazbm_result[bm_id]\">| 查看备注</a>"

				),TRUE);
		}

        echo '<script type="text/javascript" src="static/js/calendar.js"></script>'; //onclick date插件();
		$multipage = multi($dazbm_users_num, $_G['setting']['dazbm_perpage'], $page, ADMINSCRIPT."?action=dazbm&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['dazbm_perpage']);
		showsubmenu('dazbm_manage');
		showtips('dazbm_export_tips');
		showformheader('dazbm&operation=manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $dazbm_users_num)).'
		<br>关键字：<input class="text" name="dazbm_search" size="10" >
		查询时间范围：<input type="text" class="txt" name="start_time" onclick="showcalendar(event, this, 1)"> - &nbsp;
		<input type="text" class="txt" name="end_time" onclick="showcalendar(event, this, 1)">'.$fenz.'
		备注类型：'.get_remark_type().'
		每页显示：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;">条记录
		<input type="submit" class="btn" value=" 搜 素 ">
		<input type="submit" name="export" class="btn" value="导出Excel">'
		);
		showsubtitle(array('', 'realname','差点','mobile','pay_status','apply_status','pay_way', 'dazbm_add_time', 'groups_type_operation'));
		  echo $dazbm_rows;
		showsubmit('submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'dazbm_delete\')" class="checkbox"> 全选'.$do_action, '', $multipage);
		showtablefooter();
		showformfooter();
	}

	//showsetting('angf','angf_sure'); //设置表单
	//cpmsg('addons_provider_nonexistence'); //提示信息

}



elseif($operation == 'delete_user') /*用户确定删除*/
{
   $bm_id =  $_G['gp_bm_id'];
   DB::query("DELETE FROM ".DB::table('home_dazbm')." WHERE bm_id IN (".$bm_id.")");
   cpmsg('dazbm_delete_ok','action=dazbm&operation=manage'); //提示信息
}



elseif($operation == 'game_over') /*用户打完比赛*/
{
   $bm_id =  $_G['gp_bm_id'];
   $fenz_name = getgpc('input_fz');
   if(!$fenz_name) cpmsg('请选择 设置比赛结束的分站','action=dazbm&operation=manage');
   DB::query("UPDATE  ".DB::table('home_dazbm')." SET `gameover`=1  WHERE bm_id IN (".$bm_id.")");
   DB::query("UPDATE  ".DB::table('home_dazbm')." SET `city_fenz`='".$fenz_name."' WHERE bm_id IN (".$bm_id.")");
   cpmsg('设置成功','action=dazbm&operation=manage'); //提示信息
}



elseif($operation == 'edit') /*用户报名资料编辑*/
{
	/*引入城市区域列表*/
	libfile('function/profile');
	require_once libfile('function/profile');
	$html = profile_setting('residecity', $space, $vid ? false : true);
	if($html) {
    	$settings[$fieldid] = $_G['cache']['profilesetting'][$fieldid];
	    $htmls['residecity'] = $html;
	}

     $bm_info = DB::fetch_first("SELECT * FROM ".DB::table('home_dazbm')." WHERE bm_id='".$_G['gp_bm_id']."'");
	 $user_info = DB::fetch_first("SELECT `email` FROM ".DB::table('common_member')." WHERE uid='".$bm_info['uid']."'");
     $background = "/static/space/comm/images/123.jpg";  //默认的报名 背景
	 if(!empty($bm_info) && $bm_info['apply_status']==0) $background = "/static/space/comm/images/123.jpg";  //审核中的背景
	 if(!empty($bm_info) && $bm_info['apply_status']==1) $background = "/static/space/comm/images/123.jpg";  //已经审核的背景

	 showsubmenu('dazbm_manage', array( array('back_dazbm_list', 'dazbm&operation=manage', 1) ) );
	 include template ( 'daz_plus/admin_dazbm' );
}


elseif($operation == 'approve'){
   $where = $_G['gp_apply_link_todo']==1 ? array('apply_status'=>1) : array('apply_status'=>0);
   DB::update("home_dazbm",$where, array('bm_id'=>$_G['gp_bm_id']));
   cpmsg('','action=dazbm&operation=manage','loadingform');

}

/*报名信息 添加备注*/
elseif($operation == 'remark'){
   $remark_type = get_remark_type();
   $bm_id  = getgpc('bm_id');
   $input  = "<div style=\"text-align:left;margin-left:100px;\" ><input name=\"bm_id\" type=\"hidden\" value=\"".$bm_id."\">";
   $input .= "备注状态：".$remark_type ."<br>";
   $input .= "备注标题：<input name=\"title\" ><br>";
   $input .= '备注内容：<textarea name="content" cols="50" rows="5"></textarea> </div>';

   cpmsg('',"action=dazbm&operation=remark_add",'form','',$input); //提示信息
   echo $bm_id;exit;
}


/*报名信息 添加备注insert*/

elseif($operation == 'remark_add'){
   $data['bm_id']       = getgpc('bm_id');
   $data['rm_type_id']  = getgpc('remark_type');
   $data['title']       = getgpc('title');
   $data['content']     = getgpc('content');
   $data['dateline']    = time();
   if($data['rm_type_id']=='0') cpmsg('请选择备注类型');
   DB::update("home_dazbm",array('remark_type'=>$data['rm_type_id']),array('bm_id'=>$data['bm_id']));
   DB::insert('dazapp_remark',$data,true);
   if(DB::insert_id()){
		cpmsg('添加备注成功',"action=dazbm&operation=remark_list&bm_id=".$data['bm_id'],'form','',$input); //提示信息
   }else{
		cpmsg('添加失败',"action=dazbm&operation=remark",'form','',$input); //提示信息
   }
 }


/*报名信息 报名备注列表*/
elseif($operation == 'remark_list'){
	$query = DB::query("SELECT rm.* , rm_t.remark_type_name FROM ". DB::table('dazapp_remark')." as rm LEFT JOIN ".DB::table('remark_type')." as rm_t ON rm.rm_type_id = rm_t.rm_t_id  where rm.bm_id = '".getgpc('bm_id')."' and dateline>1364774400 order by dateline desc" );
	while($remark_result = DB::fetch($query)) {
		$remark_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
			"<input type=\"checkbox\" class=\"checkbox\" name=\"remark_delete[]\" value=\"$remark_result[rm_id]\" />",
			$remark_result['title'],
			"<font color='#399A03'>".$remark_result['remark_type_name'].'</font>',
			$remark_result['content'],
			date('Y-m-d h:i:s',$remark_result['dateline']),
			),TRUE);
	}
	echo '<script type="text/javascript" src="static/js/calendar.js"></script>'; //onclick date插件();
	$multipage = multi($dazbm_users_num, $_G['setting']['dazbm_perpage'], $page, ADMINSCRIPT."?action=dazbm&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['dazbm_perpage']);
	showsubmenu('报名 备注列表');
	showformheader('dazbm&operation=manage');
	showtableheader();
	showsubtitle(array('', '标题','备注类型','备注内容', '添加时间'));
	  echo $remark_rows;
	showsubmit('', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'remark_delete\')" class="checkbox">'.cplang('del'), '', $multipage);
	showtablefooter();
	showformfooter();
}

/*支付操作*/
elseif($operation=="pay"){
	$bm_id = getgpc('bm_id');
	$pay_status = getgpc("pay_status");
	$updata_status = $pay_status==1 ? 0 : 1;
	DB::update("home_dazbm",array('pay_status'=>$updata_status),array("bm_id"=>$bm_id));
	cpmsg("状态已经修改","action=dazbm&operation=manage");
}






/*获取备注类型*/
function get_remark_type(){
   $option = "<select name=\"remark_type\"><option value='-1'> -请选择备注类型- </option><option value='0'> 未填写备注</option>";
   $sql = DB::query("select * from ".DB::table('remark_type'));
   while($result = DB::fetch($sql)){
	   $option .= "<option value=\"".$result['rm_t_id']."\">".$result['remark_type_name']."</option>";
   }
   $option .="</select>";
   return $option;
}



/*分站html 标记生成*/
function fenz_select($array ,$name='fenz',$js_action){
      $onchange ="";
      if($js_action){ $onchange = "onchange = ".$js_action;}
      $fenz= "<select ".$onchange."  name=".$name."><option value='0'>请-选择分站</option>";
      foreach($array as $key=>$value){
            $fenz.="<option value='".$key."'>".$value."</option>";
      }
     return  $fenz.= "</select>";
}





//JS 书写
echo <<<EOT
    <script type="text/JavaScript">
    function disable(act) {
        if(act=='yes'){
            document.getElementById("submit_submit").disabled=true;
            document.getElementById("title_msg").innerHTML="<font color='red'>请选择分站！</font>";
        }else if(act=='no'){
           document.getElementById("submit_submit").disabled=false;
           document.getElementById("title_msg").innerHTML="<font color='red'>正确！</font>";
        }
    }
</script>
EOT;


?>