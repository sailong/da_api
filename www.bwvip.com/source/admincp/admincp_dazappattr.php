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
$_G['setting']['dazbm_perpage'] = isset($_G['gp_want_search_num']) ? (intval($_G['gp_want_search_num'])==0 ? 20 : intval($_G['gp_want_search_num'])) : 20;
$page = max(1, $_G['page']);
$start_limit = ($page - 1) * $_G['setting']['dazbm_perpage'];

/*文本类型*/
$input_type_rows = array('all_input'=>'全表单部','input'=>'文本表单','radio'=>'单选按钮' ,'checkbox'=>'多选按钮','uploadfile'=>'上传表单');

$input_option ="";
foreach($input_type_rows as $key=>$value){
   $input_option .= "<option value=\"$key\">$value</option>";
}

$header_inputs = '<input name="want_search_num" value="'.$_G['setting']['dazbm_perpage'].'" size="2" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="attr_search" ><select  name="select_input_type">"'.$input_option.'"</select> <input type="submit" class="btn" value=" 搜 素 "> <input type="submit" class="btn" value=" 全部属性 ">';

/*属性列表*/
if($operation == 'manage') {
   if(submitcheck('attrsubmit')){
      $delete = getgpc('attr_delete');
	  $atrr_ids = implode(',',$delete);
	  cpmsg('attr_delete_sure',"action=dazappattr&operation=attr_delete",'form','',"<input name=\"attr_ids\" value=\"$atrr_ids\" type=\"hidden\"/>");
   }

    $where_input_select = getgpc('select_input_type') ? " and input_type='".getgpc('select_input_type')."'" : '';
 	$where_input_select = (getgpc('select_input_type')=='all_input') ? '' : $where_input_select;
    $where = getgpc('attr_search') ? " attr_show_value like '%". getgpc('attr_search')."%'" : " attr_id <> 0 ".$where_input_select;
    $query = DB::query("SELECT * FROM ". DB::table('daz_attr')."  where " .$where. " ORDER BY attr_id desc limit ".$start_limit."," .$_G['setting']['dazbm_perpage']."  " );
	$query_num = DB::query("SELECT `attr_id` FROM ". DB::table('daz_attr')." where " .$where. " " );
	$attrs_num = DB::num_rows($query_num);

		while($attrs_result = DB::fetch($query)) {
		    $apply_button = ($attrs_result['apply_status']==0) ? cplang('dazbm_rz') : cplang('dazbm_rz_no');
			$apply_link_todo =($attrs_result['apply_status']==0) ? 1 : 0;

			$attr_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"attr_delete[]\" value=\"$attrs_result[attr_id]\" />",
				"<a href=\"home.php?mod=space&uid=$dazbm_result[uid]\" target=\"_blank\"> $attrs_result[attr_show_value]</a>",
				!empty($input_type_rows[$attrs_result['input_type']]) ? $input_type_rows[$attrs_result['input_type']] : cplang('enable_no_yes'),
				$attrs_result['enable']==1 ? cplang('ctivate_yes_input') : cplang('ctivate_no_input'),
				"<a href=\"".ADMINSCRIPT."?action=dazappattr&operation=attr_edit&attr_id=$attrs_result[attr_id]\" /> ".cplang('edit')."</a>",
				),TRUE);
		}
		$multipage = multi($attrs_num, $_G['setting']['dazbm_perpage'], $page, ADMINSCRIPT."?action=dazappattr&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['dazbm_perpage']);
		showsubmenu('attr_manage');
		showtips('attr_export_tips');
		showformheader('dazappattr&operation=manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $dazbm_users_num)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：'.$header_inputs);
		showsubtitle(array('', 'attr_name','input_type','activate_input','groups_type_operation'));
		  echo $attr_rows;
		showsubmit('attrsubmit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'attr_delete\')" class="checkbox">'.cplang('del'), '', $multipage);
		showtablefooter();
		showformfooter();
}
 elseif($operation=="attr_delete")
{
    $attr_ids = getgpc('attr_ids');
	//暂时用不到
	/*
	$checking = DB::query(" SELECT ga.attr_id,a.attr_show_value  FROM ". DB::table('daz_group_attris')." as ga LEFT JOIN ".DB::table('daz_attr')." as a ON ga.attr_id = a.attr_id   where ga.attr_id in (" .$attr_ids. ") group by ga.attr_id");
    */
    $checking = DB::query(" SELECT dzo.guess_tag_id,at.`attr_id`,at.`attr_show_value` FROM ".DB::table('daz_attr')." as at LEFT join ".DB::table('daz_guess_options')." as dzo ON dzo.guess_tag_id = at.attr_id where dzo.guess_tag_id IN(".$attr_ids.")  group by at.attr_id");

    $warning = $cant_deletes= "";
    while($result = DB::fetch($checking)){
		if($result){
		   $warning .= "【".$result['attr_show_value']."】  ";
		   $cant_deletes .= $result['guess_tag_id'];
		}
	}

	$attr_ids =explode(',',getgpc('attr_ids'));
	foreach($attr_ids as $value){
		if(strstr($cant_deletes,$value)===false){
		   DB::delete('daz_attr',"attr_id='".$value."'");
		}
	}

	if($warning!=""){
	   cpmsg('<h2>'.$warning.'已经被使用不能删除</h2><br>',"action=dazappattr&operation=manage",'error','',"<input name=\"attr_ids\" value=\"$atrr_ids\" type=\"hidden\"/>");
	}
	cpmsg('dazbm_delete_ok',"action=dazappattr&operation=manage");

}
 elseif($operation=="attr_edit")
{

   if(submitcheck('confirmed')){
	   $edit_attr_id  = getgpc('attr_id');
	   $data['attr_show_value'] = getgpc('attr_show_value');
	   $data['enable'] = getgpc('enable_type');
	   $data['input_type'] = getgpc('edit_select');
	   DB::update('daz_attr',$data,"attr_id = '".$edit_attr_id."'");
	   cpmsg('dazbm_update_ok',"action=dazappattr&operation=manage");
	}

	$script = <<<SCRIPT
	<script type="text/javascript">
	function onchange_edit_select(attr_id) {
		if(attr_id == 1){
		   $('edit_select').disabled = false
		}else{
		   $('edit_select').disabled = true
		}
	}
	</script>
SCRIPT;

	 $edit_attr_id  = getgpc('attr_id');
	 $result = DB::fetch_first("SELECT * FROM ".DB::table('daz_attr')." WHERE attr_id =".$edit_attr_id);
	 cpmsg('',"action=dazappattr&operation=attr_edit&attr_id=".$edit_attr_id,'form','',"属性名称：<td><input value=\"$result[attr_show_value]\" name=\"attr_show_value\" value=\"$atrr_ids\" type=\"text\"/> <select  onchange=\"onchange_edit_select(this.value)\" name=\"enable_type\"><option value=\"1\">启用表单功能</option><option value=\"0\">启用属性功能</option></select> <select id=\"edit_select\" name=\"edit_select\"></td>".$input_option."</select> ".$script);




}



?>