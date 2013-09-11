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

/*成绩卡状态· 重要*/
$card_status = array('0'=>'未填写','1'=>'未审核','2'=>'已审核');

/*赛事ID  重要*/
$saishi_id  =  25;


/*分页设置*/
$_G['setting']['guess_perpage'] = isset($_G['gp_want_search_num']) ? (intval($_G['gp_want_search_num'])==0 ? 20 : intval($_G['gp_want_search_num'])) : 20;
$page = max(1, $_G['page']);
$start_limit = ($page - 1) * $_G['setting']['guess_perpage'];

$where =" where cs.baofen_id>0  and cs.source='waika' and cs.dateline>". strtotime('2013-04-01'); ;

if(isset($_G['gp_card_status'])){
   $where  .= " and cs.status ='".getgpc('card_status')."' ";
}
if(isset($_G['gp_realname'])){
   $where =  $where  ." and cmp.realname like'%".getgpc('realname')."' ";
}


if($operation == 'manage') {

	   if(submitcheck('del_card_submit')){
	     cpmsg('暂未开放出来 研发中.....');
	   } 
	   $query = DB::query("SELECT cs.baofen_id, cs.uid,cs.dateline,cs.status,cs.total_score,cs.realname,cmp.mobile FROM tbl_baofen as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.baofen_id desc limit ".$start_limit."," .$_G['setting']['guess_perpage']." " ); 
	   	$query_num = DB::query("SELECT cs.baofen_id FROM tbl_baofen as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. " " );
		$guess_num = DB::num_rows($query_num);
		while($resultcard = DB::fetch($query)) {
			$card_rows.=showtablerow('', array('class="td25"', 'class="td28"'), array(
				"<input type=\"checkbox\" class=\"checkbox\" name=\"delete_card[]\" value=\"$resultcard[baofen_id]\" />",
				"<a href=\"home.php?mod=space&uid=$resultcard[uid]\" target=\"_blank\"> $resultcard[realname]</a>",
				date('Y-m-d H:i:s',$resultcard['dateline']),
				$card_status[$resultcard['status']],
                "<a href=\"home.php?mod=space&do=common&op=score&uid=".$resultcard['uid']."&id=".$resultcard['baofen_id']."&c=edit\" target=\"__blank\"/>编辑 |</a>".
				"<a href=\"home.php?mod=space&do=common&op=score&uid=".$resultcard['uid']."&id=".$resultcard['baofen_id']."\" target=\"__blank\" />查看 |</a>".
				"<a href=\"".ADMINSCRIPT."?action=resultcard&operation=delete_card&baofen_id=".$resultcard['baofen_id']."\" />删除</a>"
				),TRUE);
		}

		$multipage = multi($guess_num, $_G['setting']['guess_perpage'], $page, ADMINSCRIPT."?action=resultcard&operation=manage&submit=yes".$urladd.'&want_search_num='.$_G['setting']['guess_perpage']);
		showsubmenu('成绩卡管理',array( array('resultcrad_add', 'resultcard&operation=add_card', 1) ) );
		showtips('resultcard_export_tips');
		showformheader('resultcard&operation=manage');
		showtableheader(cplang('dazbm_search_result', array('search_bm_num' => $guess_num)).'&nbsp;&nbsp;&nbsp;'.cplang('want_search_num').'：<input name="want_search_num" value="'.$_G[setting][dazbm_perpage].'" size="3" style="margin-right:10px;vertical-align: middle;"> 姓名：<input name="realname"  style="margin-right:10px;vertical-align: middle;"><input type="submit" class="btn" value=" 搜 素 ">
		<a class="btn" href="admin.php?action=resultcard&operation=manage&card_status=0">未填写</a>
		<a class="btn" href="admin.php?action=resultcard&operation=manage&card_status=1" >等审核</a>
		<a class="btn" href="admin.php?action=resultcard&operation=manage&card_status=2" >已审核</a>');
		showsubtitle(array('', 'card_realname','card_dateline','card_status', 'groups_type_operation')); 
		echo $card_rows;
		showsubmit('del_card_submit', 'submit', '<input type="checkbox" name="chkall" onclick="checkAll(\'prefix\', this.form,\'delete_card\')" class="checkbox">'.cplang('del'), '', $multipage);
		showtablefooter();
		showformfooter();
//echo "SELECT cs.baofen_id, cs.uid,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM tbl_baofen as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where1. "  ORDER BY cmp.uid desc limit ".$start_limit."," .$_G['setting']['guess_perpage']." ";

}

/*添加成绩卡动作*/
elseif($operation == 'add_card') {


	/*赛事列表*/
	$sql_query=  DB::query(" SELECT cm.uid , cmp.field1 FROM  ".DB::table('common_member')." as cm LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid = cm.uid  where cm.groupid= '".$saishi_id."' ");
	$sais_select  = " 选择赛事：<select name=\"sid\">";
	$sais_select  .=" <option vlaue=''>请选择</option>";
	while($sais_result = DB::fetch($sql_query)){
		if($sais_result['uid']){
		  if($sais_result['uid']=='1000333'){ //临时这样 筛选出  皇冠杯城市挑战赛
				 $sais_select .= '<option value="'.$sais_result['uid'].'">'.$sais_result['field1'].'</option>';
		   }
		}

	}
	$sais_select .="</select><br />";

	/*球场列表 临时这样*/
	/*
	$list=DB::query("select uid,fieldname from ".DB::table("common_field")." where 1=1 and map_x>0 and map_y>0 ".$sql."  ".$k_sql." order by juli2 asc limit $page_start,$page_size ");
	while($row = DB::fetch($list) )
	{
		$qiuc[]=$row;
	}
	*/


	$qiuc = array(
		'1203'=>'天津滨海森林高尔夫俱乐部',
		'1290'=>'广东广州风神高尔夫球会',
		'1026'=>'广东深圳正中高尔夫俱乐部',
		'1283'=>'杭州九桥高尔夫俱乐部',
		'1186'=>'上海美兰湖高尔夫俱乐部',
		'1355'=>'湖南桃花源国际高尔夫俱乐部',
		'1077'=>'河北廊坊艾力枫社高尔夫俱乐部',
		'1137'=>'辽宁西郊乡村体育俱乐部(大连)',
		'1081'=>'河南思念高尔夫俱乐部',
		'3805114'=>'成都保利高尔夫俱乐部',
		'1113'=>'江苏苏州太湖国际高尔夫俱乐部',
		'991'=>'福建福州新东阳高尔夫俱乐部',
		/*
		'1083'=>'湖南梓山湖国际高尔夫俱乐部',
		'1290'=>'广东广州风神高尔夫球会',
		'1026'=>'广东深圳正中高尔夫俱乐部',
		'1204'=>'天津滨海湖高尔夫俱乐部',
		'1218'=>'杭州海宁尖山高尔夫俱乐部',
		'971'=>'北京银泰鸿业俱乐部',
		'1889284'=>'山东青岛桃园江景高尔夫俱乐部',
		'1186'=>'上海美兰湖高尔夫俱乐部',
		'1302'=>'辽宁大连红旗谷高尔夫俱乐部',
		'1341'=>'四川成都蓝光观岭高尔夫俱乐部',
		'1113'=>'江苏苏州太湖国际高尔夫俱乐部',
		'994'=>'福建海峡奥林匹克高尔夫俱乐部',
		*/
		);

	$qiuc_select  =" 选择球场：<select name=\"qiuc\" onchange=\"load_ab(this)\">";
	$qiuc_select  .=" <option vlaue=''>请选择</option>";
	foreach($qiuc as $key=>$value)
	{
        $qiuc_select .="<option value=\"".$key."\">".$value."</option>";
	}
	$qiuc_select .="</select>";
	$qiuc_select .="前9(OUT)场：<select name='out_par' id='out_par' onchange=\"load_select(this,'out')\"><option vlaue=''>请选择</option></select>";
	$qiuc_select .="后9(IN)场：<select name='in_par' id='in_par' onchange=\"load_select(this,'in')\"><option vlaue=''>请选择</option></select>";
	$qiuc_select .="<input type='hidden' name='out' id='out'>";
	$qiuc_select .="<input type='hidden' name='in' id='in'>";
	$qiuc_select .="
	<script>
	function load_select(obj,to_obj)
	{
		$(\"#\"+to_obj).val($(obj).find(\"option:selected\").text()); 
	}
	function load_ab(obj)
	{
		if(obj.value!=\"\")
		{	
			var state_id=obj.value;
			var action_url='default.php?g=admin&m=public&a=load_ab_action';
			var t_obj = document.getElementById(\"out_par\");
			var t_obj2 = document.getElementById(\"in_par\");

				//alert(state_id);

				$.post(action_url,{field_uid:state_id},function(res){
				//alert(res);
				if(res.split('^')[0]=='succeed')
				{
					t_obj.options.length = 0;
					t_obj.add(new Option(\"请选择\",\"\"));

					t_obj2.options.length = 0;
					t_obj2.add(new Option(\"请选择\",\"\"));

					var a=res.split('^')[1].split('-');
					var i=0
					for(i=0; i<a.length; i++)
					{
						//alert(a[i]);
						if(a[i]!=\"\" && a[i]!=\"-\")
						{
							t_obj.add(new Option(a[i].split(',')[0],a[i].split(',')[1]));
							t_obj2.add(new Option(a[i].split(',')[0],a[i].split(',')[1]));
						}

					}
					
				}
				else
				{
					alert(res.split('^')[1]);
				}
			
			});

		}
		else
		{
			alert('请先选择球场');
		}

	}


	</script>
	<br />
	";

	/*18个洞*/
	$dongk = " 请选择洞口：<select name=\"dongk\">";
	for($i=1;$i<=18;$i++){
		$dongk.="<option value=\"".$i."\">Tee ".$i."</option>";
	}
	$dongk .= "</select><br/>";

/*引入时间JS*/
	$script = <<<SCRIPT
    <script type="text/javascript" src="static/js/calendar.js"></script>
    <script type="text/javascript" src="/skin/js/jquery-1.6.2.min.js"></script>
SCRIPT;


   /*手机号表单
   $mobile_input= "";
   for($ii=1;$ii<=4;$ii++){
	  $mobile_input .= "手机号：<input name=\"mobile[$ii]\"/><br/>";
   }
   */
 /*姓名*/

  $mobile_input .= "姓名：<input name=\"realname\"/><br/>";
	$time = dgmdate(TIMESTAMP, 'Y-n-j H:i');
	cpmsg('',"action=resultcard&operation=add",'form','',"<td>". $sais_select.$qiuc_select.'选择比赛时间：<input type="text" class="txt" name="start_time" onclick="showcalendar(event, this, 1)">'.$dongk.$mobile_input.$script);

}




/*insert add 成绩卡*/
elseif($operation == 'add') {

	//print_r($_POST);

   /*add 下面的 ac =='sure' insert 添加写入数据库*/
	if($_G['gp_ac']=='sure')
	{

		$datainfo['status']    = 0;
		$mobiles = explode('|',getgpc('mobile_num'));
		$dateline = getgpc('dateline');

		/*组合相关信息*/
		$out=getgpc('out');
		$out_par=getgpc('out_par');
		$in=getgpc('in');
		$in_par=getgpc('in_par'); 
		$a=explode("|",$out_par);
         
		$n=0;
		for($i=0; $i<count($a); $i++)
		{
			$n=$i+1;
			$bzg +=$a[$i];
			$dong_names=$dong_names.$out.$n."|";
		}
		//echo $bzg;
		$b=explode("|",$in_par);
		$n=0;
		for($i=0; $i<count($b); $i++)
		{
			$n=$i+1;
			$bzg2 +=$b[$i];
			$dong_names=$dong_names.$in.$n."|";
		}
		$total_bzg=$bzg+$bzg2;
		$dong_names=substr($dong_names,0,strlen($dong_names)-1); 
		$par=$out_par."|".$bzg."|".$in_par."|".$bzg2."|".$total_bzg;




        /*循环添加对应的成绩卡*/
		foreach($mobiles as $mob){
			if($mob){
            $guid=getgpc($mob);
				/*查询是否已经存在*/
				$result = DB::fetch_first( " SELECT * FROM tbl_baofen where uid='".$guid."' and field_id='".getgpc('fuid')."' and sid='".getgpc('sid')."' and tee = '".getgpc('dongk')."' and dateline = '".$dateline."' ");
                if($result) cpmsg('已经有重复记录 请从新填写');
    if($guid){
                   // $card_add_info = array(
//                        'fuid'=>getgpc('fuid'),
//                        'uid'=>$guid,
//                        'ismine'=>0,
//                        'tee'=>'Tee '.getgpc('dongk'),
//                        'sid'=>getgpc('sid'),
//                        'dateline'=>$dateline,
//                        'addtime'=>time(),
//                        'par'=>$par,
//                        'dong_names'=>$dong_names,
//                        'source'=>'waika',
//                        'is_edit'=>'Y'
//                        );
 
    $uid=$guid;
    $ismine=0;
    $field_id=getgpc('fuid');
    $tee='Tee '.getgpc('dongk');
    $sid=getgpc('sid');
    $dateline=$dateline;
    $addtime=time();
    $par=$par;
    $dong_names=$dong_names;
    $source='waika';
    $is_edit='Y';
    $realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'"); 
    $fenzhan_id = DB::result_first("SELECT fenzhan_id FROM tbl_fenzhan WHERE field_id='$field_id'"); 
                    //DB::insert('common_score',$card_add_info);
                    
      
		$status    = 0;              
	$sql_query = DB::query("
INSERT into tbl_baofen (`uid` ,  `realname`,  `sid`,`event_id`,  `fenzhan_id` ,  `field_id`, dong_names,source, is_edit,status,  `tee`,`par`, `start_time`,  `dateline`  ,`addtime`
)values($uid ,  '$realname',  '1000333','1000333','$fenzhan_id','$field_id','$dong_names','$source','$is_edit','$status','$tee','$par','$start_time','$dateline','$addtime')");
                    
                }
            }
		}
		 cpmsg('添加成功','action=resultcard&operation=manage');
	}


	$datainfo['sid']   = getgpc('sid');
	$datainfo['fuid']      = getgpc('qiuc');
    $datainfo['dateline']  =strtotime( getgpc('start_time'));



	if(empty($_G['gp_realname']) || empty($datainfo['dateline'])){
		cpmsg('请认真填写信息');
	}

    $realname = $_G['gp_realname']; 

	$sql_query = DB::query(" SELECT cm.uid,cmp.mobile,cm.username,cmp.realname FROM ".DB::table('common_member_profile')." as cmp LEFT JOIN ".DB::table('common_member')." as cm ON cm.uid = cmp.uid  where cmp.realname  like '%".$realname."%' limit 10 ");
	while($result = DB::fetch($sql_query)){
	   $users[$result['mobile']][$result['uid'] ] = '<a href=/space-uid-'.$result['uid'].'.html target=_blank>'.$result['realname'].'|'.$result['username'].'</a>';
	}

	$user_luru_mobile =array_flip(getgpc('mobile'));
    $user_radio = "<div style=\"text-align:left;margin-left:220px;line-height:25px;\">";
	$mobile_num_str = "";
    foreach($users as $key=>$value){
			$mobile_num_str .=$key.'|';
			$user_radio .="<font color=\"red\">手机为【".$key."】的用户</font>：";
			foreach($value as $k=>$v){
				$user_radio  .= "<input type=\"radio\"   name=\"".$key."\" value=\"".$k."\">".$v;
			}
			$user_radio .="<br/>";
	}
	/*没有查询到的用户*/
	foreach($user_luru_mobile as $uk=>$uv){
			if(!$users[$uk] && !empty($uk)){
				$user_radio .="<font color=\"red\">手机为【".$uk."】的用户</font>：<font>&nbsp;没有找到相关用户</font><br/>";
			}
	}
	$user_radio.='</div>';

	$hidden_sais_input = "<input type=\"hidden\" name=\"sid\" value=\"".$datainfo['sid'] ."\">";
	$hidden_fuid_input = "<input type=\"hidden\" name=\"fuid\" value=\"".$datainfo['fuid'] ."\">";
	$hidden_mobile_num = "<input type=\"hidden\" name=\"mobile_num\" value=\"".$mobile_num_str."\">";
	$hidden_dateline   = "<input type=\"hidden\" name=\"dateline\" value=\"". $datainfo['dateline']."\">";
	$hidden_dongk      = "<input type=\"hidden\" name=\"dongk\" value=\"".$_G['gp_dongk']."\">";
	
	$hidden_dongk      .= "<input type=\"hidden\" name=\"out\" value=\"".$_G['gp_out']."\">";
	$hidden_dongk      .= "<input type=\"hidden\" name=\"out_par\" value=\"".$_G['gp_out_par']."\">";
	$hidden_dongk      .= "<input type=\"hidden\" name=\"in\" value=\"".$_G['gp_in']."\">";
	$hidden_dongk      .= "<input type=\"hidden\" name=\"in_par\" value=\"".$_G['gp_in_par']."\">";
	

    //todo...

	cpmsg('',"action=resultcard&operation=add&ac=sure",'form','', $user_radio.$hidden_sais_input.$hidden_fuid_input.$hidden_mobile_num.$hidden_dateline.$hidden_dongk);
}



/*删除 成绩卡 提示*/
elseif($operation == 'delete_card') {
  cpmsg('attr_delete_sure',"action=resultcard&operation=delete",'form','',"<input name=\"baofen_id\" value=\"".getgpc('baofen_id')."\" type=\"hidden\"/>");

}



/*删除 成绩卡*/
elseif($operation == 'delete') {
   if($_G['gp_baofen_id'])  DB::query(" delete from  tbl_baofen where baofen_id='".getgpc('baofen_id')."' limit 1");
   DB::query(" delete from  ultrax.jishigou_topic where score_id='".getgpc('baofen_id')."' limit 1");
   cpmsg('删除成功','action=resultcard&operation=manage');
}


/*获取ab场列表*/
elseif($operation == 'ab_list') 
{
	$field_uid=$_G['gp_field_uid'];
	$sub_list=DB::query("select coursetype,par from ".DB::table("common_course")." where uid='".$field_uid."' group by coursetype order by coursetype asc");
	while($row_sub=DB::fetch($sub_list))
	{
		$row_sub['par']=str_replace(",","|",$row_sub['par']);
		$str .=$row_sub['coursetype'].','.$row_sub['par']."^";
	}
	echo "succeed^".$str;
}

?>