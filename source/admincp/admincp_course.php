<?php
if (! defined ( 'IN_DISCUZ' ) || ! defined ( 'IN_ADMINCP' )) {
	exit ( 'Access Denied' );
}
cpheader ();

echo "<script src='static/js/jquery.js'></script>";
?>
<script type="text/javascript">
	function getarea(val) {
		var url = 'home.php?mod=spacecp&ac=apply&op=caddie&do=area&val='+val;
		jQuery.post(url, {}, function(msg) {
			jQuery('#field').html(msg);
		});
	}
	function getuid(uid)
	{
		
	 jQuery('#uid').val(uid);
			  
 } 
</script>
<?php

shownav ( 'dazheng', '球场信息列表' );
//添加顶部菜单xyx
$doo = empty ( $do ) ? 'courselist' : $do;

$navmenu [] = array ('球场信息列表', 'course&do=courselist', $doo == 'courselist' );
$navmenu [] = array ('添加球场信息', 'course&do=addcourse', $doo == 'addcourse' );
showsubmenu ( "球场信息管理", $navmenu );

if ($_G ['gp_do'] == 'del') {
	$id = $_G ['gp_id'];
	
	$row = DB::query ( "delete from " . DB::table ( 'common_course' ) . " where id=$id" );
	if ($row) {
		cpmsg ( '删除成功', 'action=course&do=courselist' );
	} else {
		cpmsg ( '删除失败', 'action=course&do=courselist' );
	}
} elseif ($_G ['gp_do'] == 'addcourse') {
	
 
	$option = "<option value='0'>选择场次类型</option>";
	$option .= "<option value=\"A\"  >A场</option> ";
	$option .= "<option value=\"B\"  >B场</option> ";
	$option .= "<option value=\"C\"  >C场</option> ";
	
	$option .= "<option value=\"D\"  >D场</option> ";
	
	echo " <form action='" . ADMINSCRIPT . "?action=course&operation=course&do=save' method='post' enctype='multipart/form-data'><table width='96%' style='border:1px solid gray'>
		<tr style='height:30px'><td style='width:10%'>分场信息名称</td><td><input type='text' name='coursename' style='width:300px' maxlength='20' value='" . $row ['fieldname'] . "'/></td></tr>
		";?>
		<tr><td>选择球场</td>
        <td>
        <select name="province" id="province"
	style="border: none; border-right: 1px solid #abcdef; font-size: 13px"
	onchange="getarea(this.value)">
	<option value="0">请选择</option>
						<?php
	//地区
	$query = DB::query ( "select * from " . DB::table ( "common_district" ) . " where upid=0" );
	while ( $value = DB::fetch ( $query ) ) {
		?>
<option value="<?php
		echo $value ["id"];
		?>"><?php
		echo $value ["name"];
		?></option>
<?php
	}
	?>
							</select>
<select name="fieldid" id="field"
	style=" border: none; border-right: 1px solid #abcdef; border-left: 1px solid #abcdef; font-size: 13px"
	onchange='getuid(this.value)'>
	<option value="0">请选择</option>
</select>
<input  name="uid" id="uid" value="" size="6"  readonly="readonly"/>
</td>
</tr>
<tr><td>标准杆</td><td><?php for ($i=1; $i<=9; $i++) {
?>
<?php echo $i;?><input type='text' name='par[]' value='' style='width:20px'  maxlength="1" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
<?php }?></td></tr>
<tr><td>分场信息类别</td><td><select name='coursetype' style='widht:160px'><?php echo$option;?></select></td></tr>
<tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /><input id=id name=id value='<?php echo $row ['id'] ;?>' type=hidden></td></tr>
</table>
</form>
<?php 
}elseif ($_G ['gp_do'] == 'save') {
	 
		$arr = $_POST;
		$arr['addtime'] = time();
		$arr['fieldid'] =$_G ['gp_fieldid'] ;
		$arr['uid'] =$_G ['gp_uid'] ;
		$arr['coursename'] =$_G ['gp_coursename'] ;
		$arr['coursetype'] =$_G ['gp_coursetype'] ;
		$qd=getgpc("par"); 
			if(!empty($qd)){
			foreach($qd as $key=>$t){
				if($key==0){
					$hole=$t;
				}else{
					$hole.=",".$t;
				}
			}
        }
		$arr['par'] =$hole;  
		if(getgpc("province")>0){
		$arr['province']=getgpc("province") ;
		}else{
			unset($arr['province']); }
		unset($arr['id']); 
		//unset($arr['province']); 
		
		unset($arr['submit']); 
			$row = DB::insert('common_course', $arr);
		header ( "Location:admin.php?action=course&do=courselist" );
 
 
} elseif ($_G ['gp_do'] == 'edit') {
	$id = $_G ['gp_id'];
	
	$sql = "select ha.*, hat.fieldname from " . DB::table ( 'common_course' ) . " as ha left join " . DB::table ( 'common_field' ) . " as hat on hat.fieldid=ha.fieldid where  ha.id= " . $id;
	
	$query = DB::query ( $sql );
	while ( $row = DB::fetch ( $query ) ) {
		
	     ($row ['coursetype'] == 'A') ? $stra = ' selected ' : $stra = '';
		$strecho .= "<option value=\"A\"  " . $stra . " >A场</option> ";
		($row ['coursetype'] == 'B') ? $strb = ' selected ' : $strb = '';
		$strecho .= "<option value=\"B\"  " . $strb . " >B场</option> ";
		($row ['coursetype'] == 'C') ? $strc = ' selected ' : $strc = '';
		$strecho .= "<option value=\"C\"  " . $strc . " >C场</option> ";
		($row ['coursetype'] == 'D') ? $strd = ' selected ' : $strd = '';
		$strecho .= "<option value=\"D\"  " . $strd . " >D场</option> ";
		$option = $strecho;
	
	echo " <form action='" . ADMINSCRIPT . "?action=course&operation=course&do=eidttb' method='post' enctype='multipart/form-data'><table width='96%' style='border:1px solid gray'>
		<tr style='height:30px'><td style='width:10%'>分场信息名称</td><td><input type='text' name='coursename' style='width:300px' maxlength='20' value='" . $row ['coursename'] . "'/></td></tr>
		";?>
		<tr><td>选择球场</td>
        <td>
        <select name="province" id="province"
	style="border: none; border-right: 1px solid #abcdef; font-size: 13px"
	onchange="getarea(this.value)">
	<option value="0">请选择</option>
						<?php
	//地区
	$query = DB::query ( "select * from " . DB::table ( "common_district" ) . " where upid=0" );
	while ( $value = DB::fetch ( $query ) ) {
		?>
<option value="<?php
		echo $value ["id"];
		?>"><?php
		echo $value ["name"];
		?></option>
<?php
	}
	?>
							</select>
<select name="fieldid" id="field"
	style=" border: none; border-right: 1px solid #abcdef; border-left: 1px solid #abcdef; font-size: 13px"
		onchange='getuid(this.value)'>
	<option value="0">请选择</option>
</select>

<input  name="uid" id="uid" value="<?php echo $row ['uid'];?>" size="7"  readonly="readonly"/>
</td>
</tr>
<tr><td>标准杆</td><td><?php  
$arrpar = explode(",", $row ['par']);
for ($i=1; $i<=9; $i++) {	
?>
<?php echo $i;?><input name='par[]' type='text' style='width:20px' value='<?php echo $arrpar[$i-1];?>' maxlength="1" onkeyup="this.value=this.value.replace(/\D/g,'')"  onafterpaste="this.value=this.value.replace(/\D/g,'')"/>
<?php }?></td></tr>
<tr><td>分场信息类别</td><td><select name='coursetype' style='widht:160px'><?php echo$option;?></select></td></tr>
<tr><td colspan='2' style='text-align:left; padding-left:200px; padding-top:20px'><input type='submit' name='submit' value='提交' style='border:1px solid gray' /><input id=id name=id value='<?php echo $row ['id'] ;?>' type=hidden></td></tr>
</table></form>
<?php 
}

} elseif ($_G ['gp_do'] == 'eidttb') {
	$gid = empty ( $_G ['gp_id'] ) ? 0 : $_G ['gp_id'];
	 
		$arr['id'] = $gid;	 
        $arr['fieldid'] =$_G ['gp_fieldid'] ;
		$arr['uid'] =$_G ['gp_uid'] ;
		if(!$_G ['gp_fieldid'])
		{ $arr['fieldid']=$_G ['gp_uid'] ;}
		$arr['coursename'] =$_G ['gp_coursename'] ;
		$arr['coursetype'] =$_G ['gp_coursetype'] ;
		 
		if(getgpc("province")>0){
		$arr['province']=getgpc("province") ;
		}else{
			unset($arr['province']); 
		}
		$qd=getgpc("par"); 
			if(!empty($qd)){
			foreach($qd as $key=>$t){
				if($key==0){
					$hole=$t;
				}else{
					$hole.=",".$t;
				}
			}
        }
		$arr['par'] =$hole;  
	 
	if ($gid > 0) {
		  
		
		DB::update('common_course', $arr, array('id' => $gid));
		
		header ( "Location:admin.php?action=course&do=courselist" );
	
	}
 
}  else {
	
	$tid = $_G ['gp_tid'];
	if ($tid) {
		$strwh = " and ha.coursetype='" . $tid . "'";
	}
	$fieldid = $_G ['gp_fieldid'];
	if ($fieldid) {
		$strwh.= " and ha.fieldid='" . $fieldid . "'";
	}	
	$pro = $_G ['gp_pro'];
	if ($pro) {
		$strwh.= " and ha.province=" . $pro . "";
	}
	//增加搜索条件
	showformheader ( 'course&do=courselist' );
	$strecho = " <select name='type' id='sortid' onchange=\"javascript:window.location.href='admin.php?action=course&do=courselist&tid='+this.options[this.selectedIndex].value\">";
	$strecho .= "<option value=''>--选择分类--</option> ";
	($_G ['gp_tid'] == 'A') ? $stra = ' selected ' : $stra = '';
	$strecho .= "<option value=\"A\"  " . $stra . " >A场</option> ";
	($_G ['gp_tid'] == 'B') ? $strb = ' selected ' : $strb = '';
	$strecho .= "<option value=\"B\"  " . $strb . " >B场</option> ";
	($_G ['gp_tid'] == 'C') ? $strc = ' selected ' : $strc = '';
	$strecho .= "<option value=\"C\"  " . $strc . " >C场</option> ";
	($_G ['gp_tid'] == 'D') ? $strd = ' selected ' : $strd = '';
	$strecho .= "<option value=\"D\"  " . $strd . " >D场</option> ";
	
	$strecho .= "</select>";
	$strecho.=" <select name='province' id='province' style='border: none; border-right: 1px solid #abcdef; font-size: 13px'	onchange='getarea(this.value);'>
	<option value='0'>请选择</option>";					
	$query = DB::query ( 'select * from ' . DB::table ( "common_district" ) . ' where upid=0' );
	while ( $value = DB::fetch ( $query ) ) {
$strecho.="<option value='".$value ['id']."'>".$value ['name']."</option>";
 
	} 
$strecho.="</select>";
$strecho.="<select name='fieldid' id='field'	onchange=\"javascript:window.location.href='admin.php?action=course&do=courselist&fieldid='+this.options[this.selectedIndex].value\"
	>";
$strecho.="<option value='0'>请选择</option></select>";
$strecho.=" <select name='pro' id='pro' style='border: none; border-right: 1px solid #abcdef; font-size: 13px'	onchange=\"javascript:window.location.href='admin.php?action=course&do=courselist&pro='+this.options[this.selectedIndex].value\">
	<option value='0'>地区</option>";					
	$query = DB::query ( 'select * from ' . DB::table ( "common_district" ) . ' where upid=0' );
	while ( $value = DB::fetch ( $query ) ) {
	($_G ['gp_pro'] == $value ['id']) ? $strselect = ' selected ' : $strselect = '';
$strecho.="<option value='".$value ['id']."' " . $strselect . " >".$value ['name']."</option>";
 
	} 
$strecho.="</select>";	
	showtableheader ( $strecho );
	showformfooter ();
	$page = empty ( $_G ['gp_page'] ) ? 0 : intval ( $_G ['gp_page'] );
	$pagesize = 10;
	if ($page < 1)
		$page = 1;
	$start = ($page - 1) * $pagesize;
	$multipage = '';
	
	$str = "<table width='96%'><tr style='height:30px'><td>分场信息类别</td><td>分场信息名称</td><td>标准杆</td><td>操作</td></tr>";
	$count = DB::result ( DB::query ( "select count(*) from " . DB::table ( 'common_course' ) . " as ha left join " . DB::table ( 'common_field' ) . " as hat on hat.uid=ha.uid where  1=1 $strwh" ) );
	 if ($count) {
		$sql = "select ha.*, hat.fieldname from " . DB::table ( 'common_course' ) . " as ha INNER JOIN  " . DB::table ( 'common_field' ) . " as hat on hat.uid=ha.uid where  1=1 $strwh  order by ha.id desc  limit $start, $pagesize";
	
		$query = DB::query ( $sql );
		while ( $row = DB::fetch ( $query ) ) {
			
			$str .= "<tr id=" . $row ['id'] . " onmouseover='showcolor(" . $row ['id'] . ")' onmouseout='clearcolor(" . $row ['id'] . ")' style='height:30px'><td>" . $row ['coursetype'] . "</td><td>" . $row ['coursename'] . "</td><td>" . $row ['par'] . "</td><td><a href='" . ADMINSCRIPT . "?action=course&do=edit&id=" . $row ['id'] . "'>修改</a> | <a href='" . ADMINSCRIPT . "?action=course&do=del&id=" . $row ['id'] . "'>删除</a></td></tr>";
		}
		$multipage = multi ( $count, $pagesize, $page, '?action=course' );
		echo $str . "</table><br>" . $multipage;
	}
}

?>