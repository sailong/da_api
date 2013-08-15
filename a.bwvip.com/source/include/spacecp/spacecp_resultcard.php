<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: spacecp_resultcard.php 19160 2012/4/4 angf $
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

if(($_G['adminid'] == 1 && $_G['setting']['allowquickviewprofile'] && $_G['gp_view'] != 'admin' && $_G['gp_diy'] != 'yes') || defined('IN_MOBILE')) {
	dheader("Location:home.php?mod=space&uid=$space[uid]&do=profile");
}



$operation = $_GET['op'];
$do = $_GET['do'];
if($operation == 'save') {


//需要进行组合字符串的数组
$array = array('par', 'score', 'pars');
	$arr = $_POST;
	$arr['total_score'] = $arr['score']['20'];
	foreach($arr as $key=>$val) {
		if(in_array($key, $array)) {
			$arr[$key] = implode('|', $val);
		}
	}
	$arr['dateline'] = strtotime($arr['dateline']);
	//$arr['uid'] = $_G['uid'];
	$arr['source'] = 'waika';
	$arr['addtime'] = time();
	unset($arr['profilesubmitbtn']);

	$dir = 'uploadfile/myscore/'.date('Ym');
	if(!is_dir($dir)) {
		mkdir($dir, 0777);
	}
	chmod($dir, 0777);
	$tmp = $_FILES['uploadimg']['tmp_name'];
	$str = 'abcdefghijklmnopqrstuvwxyz0123456789';
	for($i = 1; $i <= 20; $i++) {
		$max = strlen($str);
		$start = rand(0, $max);
		$rand .= substr($str, $start, '1');
	}
	$file = $dir.'/'.$rand.'.jpg';
	$info = move_uploaded_file($tmp, $file);
	$arr['uploadimg'] = $info ? $file : ($_POST['scoreimg'] ? $_POST['scoreimg'] : '');
	unset($arr['scoreimg']);
	if($do == 'scoreadd') {
		$row = DB::insert('common_score', $arr);
		$url = '/home.php?mod=spacecp&uid=1889777&ac=resultcard';
	}   else {
		showmessage('非法操作', '/home.php?mod=spacecp&uid=1889777&ac=resultcard');
	}
	if($row) {
		showmessage('操作成功', $url);
	} else {
		showmessage('操作失败', $url);
	}
}

$perpage = 10;
$perpage = mob_perpage($perpage);
$page = empty($_GET['page'])? 0: intval($_GET['page']);
if($page<1) $page=1;
$start = ($page-1)*$perpage;


$guess_num = 0;
$where ="";



/*成绩卡的状态*/
$card_status = array('0'=>'未填写','1'=>'待审核','2'=>'已审核');

/*查询球童所在的球场 和 球场下有成绩卡的球星 2012/4/4 angf */

if($_G['uid']==1899890){
	//地区
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($value = DB::fetch($query)) {
	$area[] = $value;
}

 
foreach($array as $key=>$val) {
	$par[] = $val;
}
//球洞
for($i = 1; $i <= 21; $i++) {
	if($i == '10') {
		$data[$i] = 'OUT';
	} elseif($i == '20') {
		$data[$i] = 'IN';
	} elseif($i == '21') {
		$data[$i] = 'Total';
	} elseif($i > 9) {
		$data[$i] = $i - 1;
	} else {
		$data[$i] = $i;
	}
}
//轮次
for($j = 1; $j <= 4; $j++) {
	$coun[] = $j;
}

//在添加状态下循环的输入框数
for($i = 1; $i <= 21; $i++) {
	$num[] = $i;
}

	$where =" where cs.fuid=1186  and cs.source='waika' "; 
$qiut_form_qiuc = DB::query("SELECT cs.id, cs.uid,cs.fuid,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM ". DB::table('common_score')." as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.id desc limit ".$start.",".$perpage." "); 
}
else
{	
$qiut_form_qiuc = DB::query("SELECT sc.id,qc.realname as qc_name ,ap.fuid,cmp.realname,cmp.uid,sc.dateline,sc.id,sc.tee,sc.status FROM ".DB::table('common_score')." sc  LEFT JOIN ".DB::table('home_apply')." ap  ON ap.fuid=sc.fuid LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=sc.uid LEFT JOIN ".DB::table('common_member_profile')." as qc ON qc.uid=sc.fuid where ap.uid='".$_G['uid']."' and sc.ismine=0 order by sc.id desc limit ".$start.",".$perpage." "); 	
}  
while($result = DB::fetch($qiut_form_qiuc)){
    $card_info[$result['id']]=$result;
	$card_info[$result['id']]['image'] =avatar($result['uid'], 'middle', true, false,false);
	$card_info[$result['id']]['status'] = $card_status[$result['status']];
	$card_info[$result['id']]['dateline'] =date('Y-m-d H:i:s',$result['dateline']); 	
	$result['qc_name']=getrname($result['fuid']);
	$fuid = $result['fuid'];
}
if($_G['uid']==1899890){
	$where =" where cs.id>0  and cs.source='waika' "; 
	$sql="SELECT cs.id, cs.uid,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM ". DB::table('common_score')." as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.id desc";
}else{
	$sql="select count(*) as num from ".DB::table('common_score')." where fuid='".$fuid."'";}


$qc_sql =DB::fetch_first($sql);

$multipage = multi($qc_sql['num'], $perpage , $page, CURSCRIPT.".php?mod=spacecp&uid=1899890&ac=resultcard&id=#resultcard".$urladd);



include_once(template('home/qc_result_card'));
?>