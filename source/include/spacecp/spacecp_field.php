<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

$gid=$_G['groupid'];
if($gid!='21'){
    header("Location:/");
    exit;
}


$ac = trim($_GET['ac']);
$operation = in_array($_GET['op'], array('field')) ? trim($_GET['op']) : 'field';
$do = $_GET['do'];
$uid = $_G['uid'];
$id = $_GET['id'];
$c = $_GET['c'];
//球场类型的数组
//1--3-4-5-6-7-8-
$qctype=array();
$qctype[0]="没有选择类型";
$qctype[1]="山地球场";
$qctype[2]="丘陵球场";
$qctype[3]="平原球场";
$qctype[4]="河川球场";
$qctype[5]="森林球场";
$qctype[6]="滨海球场";
$qctype[7]="林克斯球场";
$qctype[8]="地中海球场";

$fielduser = DB::fetch_first("select cf.id,cf.nickname, cm.username, cmp.realname, cf.fieldimg ,cmp.resideprovince, cmp.residecity, cmp.field1, cf.province, cf.length, cf.par, cf.standardpar, cf.cup, cf.address,cf.fieldname,cf.herb,cf.area,cf.fieldherb,cf.fieldclass,cf.designer,cf.email,cf.fax,cf.fieldphone,cd.name from ".DB::table('common_member')." as cm left join ".DB::table('common_member_profile')." as cmp on cmp.uid=cm.uid left join ".DB::table('common_field')." as cf on cf.uid=cm.uid left join ".DB::table('common_district')." as cd on cd.id=cf.province where cm.uid='$uid' order by cm.uid desc");
if($fielduser['par']) {
	$fielduser['par'] = explode(',', $fielduser['par']);
}
$fielduser["fieldclass"]=$qctype[$fielduser["fieldclass"]];

$query = DB::query('select * from '.DB::table('common_district')." where upid=0");
while($row = DB::fetch($query)) {
	$area[] = $row;
}
for($i = 1; $i <= 18; $i++) {
	$parnum[] = $i;
}

if($do == 'save') {
	$arr = $_POST;
	unset($arr['profilesubmitbtn']);
	$arr['par'] = implode(',', $arr['par']);
	$arr['fieldname'] = $fielduser['field1'];
	$arr['uid'] = $uid;
    $arr['ADDTIME']=time();
    

	/*上传文件处理*/
	if ($_FILES) {
		require_once libfile ( 'class/upload' );
		$upload = new discuz_upload ();

		foreach ( $_FILES as $key => $file ) {

			$field_key = 'field_' . $key;
			//field是路径date/attaattachment/field
			$upload->init ( $file, 'field' );
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
    
   
	//print_R($profile["file"]);exit;
    
    $arr["fieldimg"]="data/attachment/field/".$profile["file"];
	if(empty($c)) {
		
		$row = DB::insert('common_field', $arr);
	} else {
		$row = DB::update('common_field', $arr, array('uid'=>$uid));
	}
	if($row) {
		showmessage('操作成功', 'home.php?mod=spacecp&ac=field&op=field');
	} else {
		showmessage('操作失败', 'home.php?mod=spacecp&ac=field&op=field');
	}
}

$usergroup = !empty($getstat['groupid']) ? $getstat['groupid'] : $_G['groupid'];
$template = 'home/spacecp_'.$usergroup.'_field';
include template($template);
?>