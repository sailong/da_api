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
$input_type_rows = array(''=>'全 部','0'=>'未启用','1'=>'已启用');

$input_option ="";
foreach($input_type_rows as $key=>$value){
   $input_option .= "<option value=\"$key\">$value</option>";
}

$header_inputs = '<input name="want_search_num" value="'.$_G['setting']['dazbm_perpage'].'" size="2" style="margin-right:10px;vertical-align: middle;"><input class="txt" name="keyword" ><select  name="select_input_type">"'.$input_option.'"</select> <input type="submit" class="btn" value=" 搜 素 "> ';


/*属性列表*/
if($operation == 'manage') {

    if(submitcheck('attrsubmit')){
      $delete = getgpc('key_delete');
      $key_ids = implode(',',$delete);
      cpmsg('attr_delete_sure',"action=seolink&operation=key_delete",'form','',"<input name=\"key_ids\" value=\"$key_ids\" type=\"hidden\"/>");
   }

    $where_input_select = isset($_G['gp_select_input_type']) ? " and is_start='".getgpc('select_input_type')."'" : "";
    $where_input_select = (getgpc('select_input_type')=='') ? ' ' : $where_input_select;
    $where = getgpc('keyword') ? " keyword like '%". getgpc('keyword')."%'" : " id > 0 ".$where_input_select;
    $query = DB::query("SELECT * FROM ". DB::table('keyword_link')."  where " .$where. " ORDER BY id desc limit ".$start_limit."," .$_G['setting']['dazbm_perpage']."  " );
    $attrs_num = DB::result_first("SELECT  count(*) as num  FROM ". DB::table('keyword_link')." where " .$where. " " );


        while($seolink_result = DB::fetch($query)) {
            $apply_button = ($seolink_result['apply_status']==0) ? cplang('dazbm_rz') : cplang('dazbm_rz_no');
            $apply_link_todo =($seolink_result['apply_status']==0) ? 1 : 0;

            $attr_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
                "<input type=\"checkbox\" class=\"checkbox\" name=\"key_delete[]\" value=\"$seolink_result[id]\" />",
                $seolink_result['keyword'],
                $seolink_result['link'],
                !empty($input_type_rows[$seolink_result['is_start']]) ? $input_type_rows[$seolink_result['is_start']] : cplang('enable_no_yes').'',

                "<a href=\"".ADMINSCRIPT."?action=seolink&operation=key_edit&id=$seolink_result[id]\" /> ".cplang('edit')."</a>",
                ),TRUE);
        }

        $multipage = multi($attrs_num, $_G['setting']['dazbm_perpage'], $page, ADMINSCRIPT."?action=seolink&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['dazbm_perpage']);
        showsubmenu('关键词管理',
            array(
                    array('关键字列表','seolink&operation=manage', 1),
                    array('添加关键字','seolink&operation=add', 0),
                )
            );

        showtips('文档...编辑中');
        showformheader('seolink&operation=manage');
        showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $dazbm_users_num)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：'.$header_inputs);
        showsubtitle(array('', '关键词','链接','是否启用','groups_type_operation'));
          echo $attr_rows;
        showsubmit('attrsubmit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form, \'key_delete\')" class="checkbox">'.cplang('del'), '', $multipage);
        showtablefooter();
        showformfooter();
}
 elseif($operation=="key_delete")
{
    $key_ids = getgpc('key_ids');
    $rows =explode(',', $key_ids);
    foreach($rows as $value){
        DB::delete('keyword_link',"id='$value'");
    }
    cpmsg('dazbm_delete_ok',"action=seolink&operation=manage");


}
elseif($operation=="add"){

   if(submitcheck('confirmed')){

       $data['keyword'] = getgpc('keyword');
       $data['link'] = getgpc('link');
       $data['sort_order'] = getgpc('sort_order');
       $data['is_start'] = getgpc('is_start');
       DB::insert('keyword_link',$data);
       cpmsg('添加成功',"action=seolink&operation=manage");
    }

    cpmsg('','action=seolink&operation=add','form','',"关键词：<input name=\"keyword\" > 链接：<input name=\"link\" size=\"52\"> 排序：<input name=\"sort_order\" size=\"3\"> 是否启用：<input type=\"radio\" name=\"is_start\" checked value=\"1\"> 是 <input type=\"radio\" name=\"is_start\" value=\"0\"> 否 ");


}
elseif($operation=="key_edit"){
    if($_G['gp_id']){
        $result  = DB::fetch_first(" select * from ".DB::table("keyword_link")." where id =". $_G['gp_id']);

        if($result['is_start']==1){ $check_yes ='checked="checked"'; }else{ $check_no ='checked="checked"';  };
    }

      cpmsg('','action=seolink&operation=edit&id='.$result['id'],'form','',"关键词：<input name=\"keyword\" value=\"".$result['keyword']."\" > 链接：<input name=\"link\" value=\"".$result['link']."\" size=\"52\"> 排序：<input name=\"sort_order\" size=\"3\" value=\"".$result['sort_order']."\"> 是否启用：<input type=\"radio\" name=\"is_start\"  value=\"1\" ".$check_yes."> 是 <input type=\"radio\" name=\"is_start\" ".$check_no." value=\"0\"> 否");
}

elseif($operation=="edit"){
    $data['keyword']  = getgpc('keyword');
    $data['link']     = getgpc('link');
    $data['is_start'] = getgpc('is_start');
    $data['sort_order'] = getgpc('sort_order');
    $data['id']       = getgpc('id');
    DB::update('keyword_link',$data,array('id'=>$data['id']));
    cpmsg("编辑成功","action=seolink&operation=manage");
}


?>