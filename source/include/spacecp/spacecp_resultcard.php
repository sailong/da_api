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
	$arr['total_ju_par'] = $arr['pars']['20'];
	foreach($arr as $key=>$val) {
		if(in_array($key, $array)) {
			$arr[$key] = implode('|', $val);
		}
	}
	 
	$arr['dateline'] = strtotime($arr['dateline']);
	//$arr['uid'] = $_G['uid'];  
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
  
	$cave =  explode('|', $arr['score']);
  
	$uid=$arr['uid'];
    $ismine=0;
    $field_id=$arr['fuid'];
    $tee=$arr['tee'];
    $sid=$arr['sais_id'];
    $score=$arr['score']; 
    $par=$arr['par']; 
    $pars=$arr['pars']; 
    $dateline=$arr['dateline'];
    $addtime=time();
	$total_score=$arr['total_score'];
	$total_ju_par=$arr['total_ju_par']; 
    $par=$par;
	$status=1;
	$uploadimg=$arr['uploadimg'];
    $dong_names=$dong_names;
    $source='waika';
    $is_edit='Y';
    $realname = DB::result_first("SELECT realname FROM ".DB::table('common_member_profile')." WHERE uid='$uid'"); 
    $fenzhan_id = DB::result_first("SELECT fenzhan_id FROM tbl_fenzhan WHERE field_id='$field_id'"); 
	if($do == 'scoreadd') {
		//$row = DB::insert('common_score', $arr);
		
		$row = DB::query("
INSERT into tbl_baofen (`uid` ,  `realname`,  `sid`,`event_id`,  `fenzhan_id` ,  `field_id`, dong_names,source, is_edit,status,  `tee`,`score`, `start_time`,  `dateline`  ,`addtime`,`total_score`,`par`,`pars`,`uploadimg`,`cave_1`,`cave_2`,`cave_3`,`cave_4`,`cave_5`,`cave_6`,`cave_7`,`cave_8`,`cave_9`,`cave_10`,`cave_11`,`cave_12`,`cave_13`,`cave_14`,`cave_15`,`cave_16`,`cave_17`,`cave_18`,total_ju_par

)values($uid ,  '$realname',  '$sid','$sid','$fenzhan_id','$field_id','$dong_names','$source','$is_edit','$status','$tee','$score','$start_time','$dateline','$addtime','$total_score','$par','$pars','$uploadimg','cave[0]','cave[1]','$cave[2]','$cave[3]','$cave[4]','$cave[5]','$cave[6]','$cave[7]','$cave[8]','$cave[10]','$cave[11]','$cave[12]','$cave[13]','$cave[14]','$cave[15]','$cave[16]','$cave[17]','$cave[18]','$total_ju_par'
)");
           	
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

$fuid =DB::fetch_first("select fuid from ".DB::table('home_apply')." where uid='".$_G['uid']."' ");

/*成绩卡的状态*/
$card_status = array('0'=>'未填写','1'=>'待审核','2'=>'已审核');

/*查询球童所在的球场 和 球场下有成绩卡的球星 2012/4/4 angf */
 
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

if($_G['uid']==1899890){
	//地区
$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
			while($value = DB::fetch($query)) {
				$area[] = $value;
			  }


/*	$where =" where cs.fuid=1186  and cs.source='waika' "; 
$sql = "SELECT cs.id, cs.uid,cs.fuid,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM ". DB::table('common_score')." as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.id desc limit ".$start.",".$perpage." "; 
}
else
{	
$sql = "SELECT sc.id,qc.realname as qc_name ,ap.fuid,cmp.realname,cmp.uid,sc.dateline,sc.id,sc.tee,sc.status FROM ".DB::table('common_score')." sc  LEFT JOIN ".DB::table('home_apply')." ap  ON ap.fuid=sc.fuid LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=sc.uid LEFT JOIN ".DB::table('common_member_profile')." as qc ON qc.uid=sc.fuid where ap.uid='".$_G['uid']."' and sc.ismine=0 order by sc.id desc limit ".$start.",".$perpage." "; 	
} */ 

$where =" where cs.field_id=1186  and cs.source='waika' "; 
$sql="SELECT cs.baofen_id, cs.uid,cs.field_id,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM tbl_baofen as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.baofen_id desc limit ".$start.",".$perpage." ";

}
else
{	
$sql="SELECT sc.baofen_id,qc.realname as qc_name ,ap.fuid,cmp.realname,cmp.uid,sc.dateline,sc.tee,sc.status FROM tbl_baofen as sc  LEFT JOIN ".DB::table('home_apply')." ap  ON ap.fuid=sc.field_id LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=sc.uid LEFT JOIN ".DB::table('common_member_profile')." as qc ON qc.uid=sc.field_id where ap.uid='".$_G['uid']."'   order by sc.baofen_id desc limit ".$start.",".$perpage." ";
 
}   
$qiut_form_qiuc = DB::query($sql); 	 
while($result = DB::fetch($qiut_form_qiuc)){
    $card_info[$result['baofen_id']]=$result;
	$card_info[$result['baofen_id']]['image'] =avatar($result['uid'], 'middle', true, false,false);
	$card_info[$result['baofen_id']]['status'] = $card_status[$result['status']];
	$card_info[$result['baofen_id']]['dateline'] =date('Y-m-d H:i:s',$result['dateline']); 	
	$result['qc_name']=getrname($result['field_id']);
	$fuid = $result['fuid'];
}
if($_G['uid']==1899890){
	$where =" where cs.baofen_id>0  and cs.source='waika' "; 
	$sql="SELECT cs.baofen_id, cs.uid,cs.dateline,cs.status,cs.total_score,cmp.realname,cmp.mobile FROM ". DB::table('common_score')." as cs LEFT JOIN ".DB::table('common_member_profile')." as cmp ON cmp.uid=cs.uid  " .$where. "   ORDER BY cs.baofen_id desc";
}else{
	$sql="select count(*) as num from tbl_baofen where field_id='".$fuid."' and source='waika'";}


$qc_sql =DB::fetch_first($sql);

$multipage = multi($qc_sql['num'], $perpage , $page, CURSCRIPT.".php?mod=spacecp&uid=1899890&ac=resultcard&id=#resultcard".$urladd);



include_once(template('home/qc_result_card'));
?>